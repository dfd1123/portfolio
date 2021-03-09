<?php

namespace App\Schedule;

use DB;

class Lock_coin
{
    public static function lock_snapshot_daily() {
        info('[Schedule] lock_snapshot_daily : 매일 18시 마다 락 되어있는 금액 스냅샷');

        date_default_timezone_set("Asia/Seoul");

        $day_count = date('N');

        DB::table('btc_lock_users')
            ->where('lock_amount', '>', 0)
            ->whereNotIn('coin', function($query) { $query->select('coin')->from('btc_lock_coins')->where('status', 0); })
            ->update([
                "day$day_count" => DB::raw('lock_amount'), 
                'updated_dt' => now()
            ]);

        info('[Schedule] lock_snapshot_daily : 스냅샷 완료');
    }

    public static function lock_release_unlocking() {
        info('[Schedule] lock_release_unlocking : 1시간마다 락 해제요청된 금액 중에서 24시간이 지난 금액 반환');

        date_default_timezone_set("Asia/Seoul");

        $lock_lists = DB::table('btc_lock_list')
            ->select('*')
            ->where('operation', 0)
            ->whereRaw('created_dt <= DATE_SUB(NOW(), INTERVAL 1 DAY)')
            ->get();
        foreach ($lock_lists as $lock_list) {
            $id = $lock_list->id;
            $uid = $lock_list->uid;
            $coin = $lock_list->coin;
            $amount = $lock_list->amount;

            // 해당 금액을 잠금해제중에서 잠금해제 상태로 변경
            DB::table('btc_lock_list')->where('id', $id)->update([
                'operation' => -1,
                'updated_dt' => now()
            ]);

            // 잠금 사용자 정보에서 잠금해제중인 금액처리
            DB::table('btc_lock_users')->where('uid', $uid)->where('coin', $coin)->update([
                'unlocking_amount' => DB::raw("(unlocking_amount - cast($amount as decimal(21,8)))"),
                'updated_dt' => now()
            ]);

            // 사용대기 중인 금액에서 잠금해제 할 금액처리
            DB::table('btc_users_addresses')->where('uid', $uid)->update([
                "pending_received_balance_$coin" => DB::raw("(pending_received_balance_$coin + cast($amount as decimal(21,8)))")
            ]);
        }

        info('[Schedule] lock_release_unlocking : 잠금해제중인 금액 잠금해제 완료');
    }

    public static function lock_dividend() {
        info('[Schedule] lock_dividend : 금요일 17시마다 배당액 계산 후 배당');

        date_default_timezone_set("Asia/Seoul");

        $day_count = date('N'); // 1 (월요일) ~ 7 (일요일)
        if($day_count != 5) {
            info('[Schedule] lock_dividend : 잘못된 날짜 -> ' . $day_count);
            return;
        }

        $coin_infos = DB::table('btc_lock_coins')->select('*')->where('status', '<>', 0)->get();
        foreach ($coin_infos as $coin_info) {
            $id = $coin_info->id;
            $coin = $coin_info->coin;
            $ratio = $coin_info->ratio;
            $status = $coin_info->status;

            info('[Schedule] lock_dividend : 코인 -> ' . $coin);
            
            //해당 코인의 거래 수수료 비율
            $query1 = DB::table('btc_settings')->select(DB::raw('CAST(buy_comission as decimal(5,4)) * 0.01 commission'))->limit(1)->first();
            $commission = $query1->commission;
            info('[Schedule] lock_dividend : 해당 코인의 거래 수수료 비율 -> ' . $commission);
            info('[Schedule] lock_dividend : 해당 코인의 분배 비율 -> ' . $ratio);

            //해당 코인의 일주일간 수수료합 * 분배비율 값
            $query2 = DB::table('btc_ads')
                ->select(DB::raw("sum((buy_COIN_amt_finished * buy_coin_price) + (sell_COIN_amt_finished * sell_coin_price)) * CAST($commission as decimal(21,8)) * CAST($ratio as decimal(21,8)) total_value"))
                ->where('cointype', $coin)
                ->whereRaw("created > UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY)")
                ->first();
            $total_value = $query2->total_value;
            info('[Schedule] lock_dividend : 해당 코인의 일주일간 수수료합 * 분배비율 값 -> ' . $total_value);

            //해당 코인의 일주일간 모든 사용자 락평균값의 합
            $query3 = DB::table('btc_lock_users')->select(DB::raw('sum((day1 + day2 + day3 + day4 + day5 + day6 + day7) / 7) sum_avg_lock'))->where('coin', $coin)->first();
            $sum_avg_lock = $query3->sum_avg_lock;
            info('[Schedule] lock_dividend : 해당 코인의 모든 사용자 락평균값의 합 -> ' . $sum_avg_lock);

            // 해당 값이 0인 경우에는 배당없음
            if((float) $sum_avg_lock != 0 && (float) $total_value != 0 ) {
                //해당 코인의 일주일간 사용자 락평균값의 전체에서의 비율 * 전체 배당금액
                $lock_users = DB::table('btc_lock_users')
                    ->select(DB::raw("uid, CAST(((day1 + day2 + day3 + day4 + day5 + day6 + day7) / 7) / CAST($sum_avg_lock as decimal(30,8)) * CAST($total_value as decimal(30,8)) as decimal(30,8)) dividend"))
                    ->where('coin', $coin)
                    ->get();
                foreach ($lock_users as $lock_user) {
                    $user_id = $lock_user->uid;
                    $user_dividend = $lock_user->dividend;

                    $user_info = DB::table('users')->select('username')->where('id', $user_id)->first();
                    $user_address = DB::table('btc_users_addresses')->select("available_balance_usd")->where('uid', $user_id)->first();

                    if($user_info != null && $user_address != null && (float) $user_dividend != 0) {
                        $user_name = $user_info->username;
                        $balance_before = $user_address->available_balance_usd;
                        $balance_after = $balance_before + $user_dividend;
                        $user_reason = 'Coin lock dividend';

                        // 배당내역 테이블에 내역 추가
                        DB::table('btc_lock_dividend')->insert([
                            'uid' => $user_id,
                            'coin' => $coin,
                            'amount' => $user_dividend,
                            'created_dt' => now()
                        ]);

                        // USD 입출금 테이블에 내역 추가
                        DB::table('btc_usd_io')->insert([
                            'uid' => $user_id,
                            'username' => $user_name,
                            'type' => 'in',
                            'plus' => $user_dividend,
                            'minus' => 0,
                            'amount' => $user_dividend,
                            'balance_before' => $balance_before,
                            'balance_after' => $balance_after,
                            'rel_type' => null,
                            'rel_id' => null,
                            'memo' => $user_reason,
                            'created' => time()
                        ]);

                        // 코인 입출금 테이블에 내역 추가
                        DB::table('btc_coin_io')->insert([
                            'uid' => $user_id,
                            'username' => $user_name,
                            'type' => 'in',
                            'cointype' => 'usd',
                            'plus' => $user_dividend,
                            'minus' => 0,
                            'amount' => $user_dividend,
                            'balance_before' => $balance_before,
                            'balance_after' => $balance_after,
                            'memo' => $user_reason,
                            'created' => time(),
                            'created_dt' => now()
                        ]);

                        // 배당에 따라 사용자 지갑에 usd로 지급
                        DB::table('btc_users_addresses')->where('uid', $user_id)->update([
                            'available_balance_usd' => DB::raw("available_balance_usd + CAST($user_dividend as decimal(30,8))")
                        ]);
                    }
                }
            }

            //락 종료설정된 코인은 여기서 종료처리
            if($status == -1) {
                DB::table('btc_lock_coins')->where('id', $id)->update(['status' => 0]);
                info('[Schedule] lock_dividend : 종료처리된 코인 -> ' . $coin);
            }
        }

        info('[Schedule] lock_dividend : 배당 분배 완료');
    }
}