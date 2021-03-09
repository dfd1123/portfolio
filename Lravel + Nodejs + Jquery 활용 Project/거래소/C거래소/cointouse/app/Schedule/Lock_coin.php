<?php

namespace App\Schedule;

use DB;

class Lock_coin
{
    // 매일 18시마다 락 중인 금액 스냅샷
    public static function lock_snapshot_daily()
    {
        date_default_timezone_set("Asia/Seoul");

        info('[CoinLock' . date('(T)') . ': ' . ' run] lock_snapshot_daily');

        $day_count = date('N'); // 1(월요일) ~ 7(일요일)

        $user_count = DB::table('btc_lock_users')
            ->where('lock_amount', '>=', 0)
            ->whereNotIn('coin', function ($query) {
                $query->select('coin')->from('btc_lock_coins')->where('status', 0);
            })
            ->update([
                "day$day_count" => DB::raw('lock_amount'),
                'updated_dt' => DB::raw('now()')
            ]);

        info('[CoinLock' . date('(T)') . ': ' . ' end] lock_snapshot_daily -> 스냅샷된 사용자 수: ' . $user_count);
    }

    // 1시간마다 락 해제요청된 금액 중에서 24시간이 지난 금액 반환
    public static function lock_release_unlocking()
    {
        date_default_timezone_set("Asia/Seoul");

        info('[CoinLock' . date('(T)') . ': ' . ' run] lock_release_unlocking');

        $lock_lists = DB::table('btc_lock_list')
            ->select('id', 'uid', 'coin', 'amount', 'operation')
            ->where('operation', 0)
            ->whereRaw('created_dt <= now() - INTERVAL 1 DAY')
            ->get();

        $oper_count = $lock_lists->count();

        foreach ($lock_lists as $lock_list) {
            $id = $lock_list->id;
            $uid = $lock_list->uid;
            $coin = $lock_list->coin;
            $amount = $lock_list->amount;

            // 해당 금액을 잠금해제중에서 잠금해제 상태로 변경
            DB::table('btc_lock_list')->where('id', $id)->update([
                'operation' => -1,
                'updated_dt' => DB::raw('now()')
            ]);

            // 잠금 사용자 정보에서 잠금해제중인 금액처리
            DB::table('btc_lock_users')->where('uid', $uid)->where('coin', $coin)->update([
                'unlocking_amount' => DB::raw("(unlocking_amount - cast($amount as decimal(21,8)))"),
                'updated_dt' => DB::raw('now()')
            ]);

            // 사용대기 중인 금액에서 잠금해제 할 금액처리
            DB::table('btc_users_addresses')->where('uid', $uid)->update([
                "pending_received_balance_$coin" => DB::raw("(pending_received_balance_$coin + cast($amount as decimal(21,8)))")
            ]);
        }

        info('[CoinLock' . date('(T)') . ': ' . ' end] lock_release_unlocking -> 잠금해제한 요청 수: ' . $oper_count);
    }

    // 금요일 17시마다 배당액 계산 후 배당
    public static function lock_dividend()
    {
        date_default_timezone_set("Asia/Seoul");

        info('[CoinLock' . date('(T)') . ': ' . ' run] lock_dividend');

        //USD 환율 가져오기
        $query0 = DB::table('btc_coins')
            ->select('price_krw')
            ->where('api', 'usd')
            ->where('cointype', 'cash')
            ->first();

        if ($query0 === null) {
            info('[CoinLock' . date('(T)') . ': ' . ' err] lock_dividend 환율을 가져올 수 없음');
            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 작업 중단됨');
            return;
        }
        $price_usd_to_krw = $query0->price_krw;

        info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 1USD 당 KRW 환율 -> ' . $price_usd_to_krw);

        $coin_infos = DB::table('btc_lock_coins')->select('id', 'coin', 'ratio', 'status')->where('status', '<>', 0)->get();
        foreach ($coin_infos as $coin_info) {
            $id = $coin_info->id;
            $coin = $coin_info->coin;
            $ratio = $coin_info->ratio;
            $status = $coin_info->status;
            $user_count = 0;

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend >>> 코인 배당 시작 -> ' . $coin);

            //해당 코인의 거래 수수료 비율
            $query1 = DB::table('btc_settings')
                ->select(DB::raw('CAST(buy_comission as decimal(5,4)) * 0.01 commission'))
                ->where('id', 1) // id 1이 코인락을 적용할 거래소인지 확인 필수
                ->first();
            $commission = $query1->commission;

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 거래 수수료 비율 -> ' . $commission);
            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 분배 비율 -> ' . $ratio);

            //해당 코인의 일주일간 수수료합 * 분배비율 값

            // USD
            $query2_1 = DB::table('btc_ads_btc')
                ->select(DB::raw("sum((buy_COIN_amt_finished * buy_coin_price) + (sell_COIN_amt_finished * sell_coin_price)) * CAST($commission as decimal(21,8)) * CAST($ratio as decimal(21,8)) * CAST($price_usd_to_krw as decimal(21,8)) usd_to_krw_total_value"))
                ->where('cointype', $coin)
                ->where('userid', '<>', 'sbtr01')
                ->whereRaw("UPPER(currency) = 'USD'")
                ->whereRaw("created > UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY)")
                ->first();

            $usd_to_krw_total_value = $query2_1->usd_to_krw_total_value;
            if ($usd_to_krw_total_value === null) {
                $usd_to_krw_total_value = '0';
            }

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 일주일간 USD 마켓 수수료합 * 분배비율 값 -> ' . $usd_to_krw_total_value);

            // KRW
            $query2_2 = DB::table('btc_ads_btc')
                ->select(DB::raw("sum((buy_COIN_amt_finished * buy_coin_price) + (sell_COIN_amt_finished * sell_coin_price)) * CAST($commission as decimal(21,8)) * CAST($ratio as decimal(21,8)) krw_total_value"))
                ->where('cointype', $coin)
                ->where('userid', '<>', 'sbtr01')
                ->whereRaw("UPPER(currency) = 'KRW'")
                ->whereRaw("created > UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY)")
                ->first();

            $krw_total_value = $query2_2->krw_total_value;
            if ($krw_total_value === null) {
                $krw_total_value = '0';
            }

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 일주일간 KRW 마켓 수수료합 * 분배비율 값 -> ' . $krw_total_value);

            $total_value = bcadd($usd_to_krw_total_value, $krw_total_value, 8);

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 일주일간 총 수수료합 * 분배비율 값 -> ' . $total_value);

            //해당 코인의 일주일간 모든 사용자 락평균값의 합
            $query3 = DB::table('btc_lock_users')->select(DB::raw('CAST(sum((day1 + day2 + day3 + day4 + day5 + day6 + day7) / 7) as decimal(30,8)) sum_avg_lock'))->where('coin', $coin)->first();
            $sum_avg_lock = $query3->sum_avg_lock;
            if ($sum_avg_lock === null) {
                $sum_avg_lock = '0';
            }

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 사용자 락평균값의 합 -> ' . $sum_avg_lock);

            // 락평균값이 0이거나 총 수수료합이 1원보다 작으면 배당안함
            if (bccomp($sum_avg_lock, '0', 8) !== 0 && bccomp($total_value, '0', 0) !== 0) {
                // 해당 코인의 일주일간 사용자 락평균값의 전체에서의 비율 * 전체 배당금액
                $lock_users = DB::table('btc_lock_users')
                    ->select(DB::raw("uid, ((day1 + day2 + day3 + day4 + day5 + day6 + day7) / 7) / CAST($sum_avg_lock as decimal(30,8)) * CAST($total_value as decimal(30,8)) dividend"))
                    ->where('coin', $coin)
                    ->get();

                // 각 사용자에게 배당 분배
                foreach ($lock_users as $lock_user) {
                    $user_id = $lock_user->uid;
                    $user_dividend = bcadd($lock_user->dividend, '0', 0);

                    $user_info = DB::table('users')->select('username')->where('id', $user_id)->first();
                    $user_address = DB::table('btc_users_addresses')->select("available_balance_krw")->where('uid', $user_id)->first();

                    // 사용자 정보가 없거나 계산된 배당이 1원보다 작으면 배당안함
                    if ($user_info !== null && $user_address !== null && bccomp($user_dividend, '0', 0) !== 0) {
                        $user_name = $user_info->username;
                        $balance_before = $user_address->available_balance_krw;
                        $balance_after = bcadd($balance_before, $user_dividend, 8);
                        $user_memo = 'Coin lock dividend';

                        // 배당내역 테이블에 내역 추가
                        DB::table('btc_lock_dividend')->insert([
                            'uid' => $user_id,
                            'coin' => $coin,
                            'amount' => $user_dividend,
                            'created_dt' => DB::raw('now()')
                        ]);

                        // 코인 입출금 테이블에 내역 추가
                        DB::table('btc_coin_io')->insert([
                            'uid' => $user_id,
                            'username' => $user_name,
                            'type' => 'in',
                            'cointype' => 'krw',
                            'plus' => $user_dividend,
                            'minus' => 0,
                            'amount' => $user_dividend,
                            'balance_before' => $balance_before,
                            'balance_after' => $balance_after,
                            'memo' => $user_memo,
                            'created' => time(),
                            'created_dt' => DB::raw('now()')
                        ]);

                        // 배당에 따라 사용자 지갑에 KRW으로 지급
                        DB::table('btc_users_addresses')->where('uid', $user_id)->update([
                            'available_balance_krw' => DB::raw("available_balance_krw + CAST($user_dividend as decimal(30,8))")
                        ]);

                        $user_count += 1;
                    }
                }
            }

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 코인락 스냅샷 초기화 -> ' . $coin);

            // 스냅샷 초기화
            DB::table('btc_lock_users')->where('coin', $coin)->update([
                "day1" => 0,
                "day2" => 0,
                "day3" => 0,
                "day4" => 0,
                "day5" => 0,
                "day6" => 0,
                "day7" => 0,
                'updated_dt' => DB::raw('now()')
            ]);


            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend <<< 코인락 배당 완료 -> ' . $coin . ', 배당된 사용자 수 -> ' . $user_count);

            //락 종료설정된 코인은 여기서 종료처리
            if ($status == -1) {
                DB::table('btc_lock_coins')->where('id', $id)->update(['status' => 0]);
                info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 코인락 종료처리된 코인 -> ' . $coin);
            }
        }

        info('[CoinLock' . date('(T)') . ': ' . ' end] lock_dividend');
    }
}
