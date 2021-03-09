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
            if ($query1 == null) {
                info('[CoinLock' . date('(T)') . ': ' . ' err] 거래소 id가 1이 아님');
                info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 작업 중단됨');
            }

            $commission = $query1->commission;

            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 거래 수수료 비율 -> ' . $commission);
            info('[CoinLock' . date('(T)') . ': ' . ' log] lock_dividend 분배 비율 -> ' . $ratio);

            //해당 코인의 일주일간 수수료합 * 분배비율 값

            // USD
            /*
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
            */

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

            // $total_value = bcadd($usd_to_krw_total_value, $krw_total_value, 8);
            $total_value = bcadd($krw_total_value, '0', 8);

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

    //1회성 함수
    public static function lock_precoin_release() {
        $coins = DB::table('btc_coins')->where('market','sports')->get();
        $precoin_users = DB::table('lock_precoin')
        ->join('users','users.email','=','lock_precoin.email')
        ->select('lock_precoin.*','users.username')->get();

        foreach($precoin_users as $precoin_user){
            /*foreach($coins as $coin){
                DB::table('btc_transaction')->insert([  // 출금자 트랜잭션 db 삽입
                    'cointxid' => $coin->api."_".$precoin_user->{$coin->api}."_receive_precoin_".$precoin_user->id,
                    'cointype' => $coin->api,
                    'account' => $precoin_user->username,
                    'address' => '',
                    'category' => 'receive',
                    'amount' => $precoin_user->{$coin->api},
                    'confirmations' => 999,
                    'txid' => $coin->api."_".$precoin_user->{$coin->api}."_receive_precoin_".$precoin_user->id,
                    'normtxid' => '',
                    'tr_time' => time(),
                    'timereceived' => time(),
                    'processed' => 'y',
                    'created_dt' => DB::raw('now()'),
                ]);
            }


            //전체 유저 사전판매 코인 제공
            $sql = "UPDATE btc_users_addresses SET 
            available_balance_mnu = available_balance_mnu + ".$precoin_user->mnu.",  pending_received_balance_mnu = pending_received_balance_mnu + ".(-1)*$precoin_user->mnu.", 
            available_balance_bar = available_balance_bar + ".$precoin_user->bar.",  pending_received_balance_bar = pending_received_balance_bar + ".(-1)*$precoin_user->bar.", 
            available_balance_rma = available_balance_rma + ".$precoin_user->rma.",  pending_received_balance_rma = pending_received_balance_rma + ".(-1)*$precoin_user->rma.", 
            available_balance_che = available_balance_che + ".$precoin_user->che.",  pending_received_balance_che = pending_received_balance_che + ".(-1)*$precoin_user->che.", 
            available_balance_brn = available_balance_brn + ".$precoin_user->brn.",  pending_received_balance_brn = pending_received_balance_brn + ".(-1)*$precoin_user->brn.", 
            available_balance_asn = available_balance_asn + ".$precoin_user->asn.",  pending_received_balance_asn = pending_received_balance_asn + ".(-1)*$precoin_user->asn.", 
            available_balance_mct = available_balance_mct + ".$precoin_user->mct.",  pending_received_balance_mct = pending_received_balance_mct + ".(-1)*$precoin_user->mct.", 
            available_balance_liv = available_balance_liv + ".$precoin_user->liv.",  pending_received_balance_liv = pending_received_balance_liv + ".(-1)*$precoin_user->liv.", 
            available_balance_int = available_balance_int + ".$precoin_user->int.",  pending_received_balance_int = pending_received_balance_int + ".(-1)*$precoin_user->int.", 
            available_balance_tot = available_balance_tot + ".$precoin_user->tot.",  pending_received_balance_tot = pending_received_balance_tot + ".(-1)*$precoin_user->tot.", 
            available_balance_nap = available_balance_nap + ".$precoin_user->nap.",  pending_received_balance_nap = pending_received_balance_nap + ".(-1)*$precoin_user->nap.", 
            available_balance_atm = available_balance_atm + ".$precoin_user->atm.",  pending_received_balance_atm = pending_received_balance_atm + ".(-1)*$precoin_user->atm.", 
            available_balance_dor = available_balance_dor + ".$precoin_user->dor.",  pending_received_balance_dor = pending_received_balance_dor + ".(-1)*$precoin_user->dor.", 
            available_balance_val = available_balance_val + ".$precoin_user->val.",  pending_received_balance_val = pending_received_balance_val + ".(-1)*$precoin_user->val."
            WHERE label = '".$precoin_user->username."'";
            
            DB::update($sql);*/

            /*$sql = "UPDATE btc_users_addresses SET 
            pending_received_balance_mnu = pending_received_balance_mnu + ".$precoin_user->mnu*(0.04).", 
            pending_received_balance_bar = pending_received_balance_bar + ".$precoin_user->bar*(0.04).", 
            pending_received_balance_rma = pending_received_balance_rma + ".$precoin_user->rma*(0.04).", 
            pending_received_balance_che = pending_received_balance_che + ".$precoin_user->che*(0.04).", 
            pending_received_balance_brn = pending_received_balance_brn + ".$precoin_user->brn*(0.04).", 
            pending_received_balance_asn = pending_received_balance_asn + ".$precoin_user->asn*(0.04).", 
            pending_received_balance_mct = pending_received_balance_mct + ".$precoin_user->mct*(0.04).", 
            pending_received_balance_liv = pending_received_balance_liv + ".$precoin_user->liv*(0.04).", 
            pending_received_balance_int = pending_received_balance_int + ".$precoin_user->int*(0.04).", 
            pending_received_balance_tot = pending_received_balance_tot + ".$precoin_user->tot*(0.04).", 
            pending_received_balance_nap = pending_received_balance_nap + ".$precoin_user->nap*(0.04).", 
            pending_received_balance_atm = pending_received_balance_atm + ".$precoin_user->atm*(0.04).", 
            pending_received_balance_dor = pending_received_balance_dor + ".$precoin_user->dor*(0.04).", 
            pending_received_balance_val = pending_received_balance_val + ".$precoin_user->val*(0.04)."
            WHERE label = '".$precoin_user->username."'";

            DB::update($sql);*/
            

            //echo json_encode($precoin_user)."<br>";
        }
    }
    //1회성 함수
    public static function lock_airdrop_release() {
        /*$coins = DB::table('btc_coins')->where('market','sports')->get();
        $precoin_users = DB::table('lock_airdrop')
        ->join('users','users.email','=','lock_airdrop.email')
        ->select('lock_airdrop.*','users.username')->where('lock_airdrop.id','<',10000)->get();
        $i = 0;
        foreach($precoin_users as $precoin_user){
            foreach($coins as $coin){
                DB::table('btc_transaction')->insert([  // 출금자 트랜잭션 db 삽입
                    'cointxid' => $coin->api."_".$precoin_user->{$coin->api}."_remove_airdrop_".$precoin_user->id,
                    'cointype' => $coin->api,
                    'account' => $precoin_user->username,
                    'address' => '',
                    'category' => 'send',
                    'amount' => $precoin_user->{$coin->api},
                    'confirmations' => 999,
                    'txid' => $coin->api."_".$precoin_user->{$coin->api}."_remove_airdrop_".$precoin_user->id,
                    'normtxid' => '',
                    'tr_time' => time(),
                    'timereceived' => time(),
                    'processed' => 'y',
                    'created_dt' => DB::raw('now()'),
                ]);
            }
        

            
            //전체 유저 사전판매 코인 제공
            $sql = "UPDATE btc_users_addresses SET 
            available_balance_mnu = available_balance_mnu - ".$precoin_user->mnu.",  pending_received_balance_mnu = pending_received_balance_mnu + ".$precoin_user->mnu.", 
            available_balance_bar = available_balance_bar - ".$precoin_user->bar.",  pending_received_balance_bar = pending_received_balance_bar + ".$precoin_user->bar.", 
            available_balance_rma = available_balance_rma - ".$precoin_user->rma.",  pending_received_balance_rma = pending_received_balance_rma + ".$precoin_user->rma.", 
            available_balance_che = available_balance_che - ".$precoin_user->che.",  pending_received_balance_che = pending_received_balance_che + ".$precoin_user->che.", 
            available_balance_brn = available_balance_brn - ".$precoin_user->brn.",  pending_received_balance_brn = pending_received_balance_brn + ".$precoin_user->brn.", 
            available_balance_asn = available_balance_asn - ".$precoin_user->asn.",  pending_received_balance_asn = pending_received_balance_asn + ".$precoin_user->asn.", 
            available_balance_mct = available_balance_mct - ".$precoin_user->mct.",  pending_received_balance_mct = pending_received_balance_mct + ".$precoin_user->mct.", 
            available_balance_liv = available_balance_liv - ".$precoin_user->liv.",  pending_received_balance_liv = pending_received_balance_liv + ".$precoin_user->liv.", 
            available_balance_int = available_balance_int - ".$precoin_user->int.",  pending_received_balance_int = pending_received_balance_int + ".$precoin_user->int.", 
            available_balance_tot = available_balance_tot - ".$precoin_user->tot.",  pending_received_balance_tot = pending_received_balance_tot + ".$precoin_user->tot.", 
            available_balance_nap = available_balance_nap - ".$precoin_user->nap.",  pending_received_balance_nap = pending_received_balance_nap + ".$precoin_user->nap.", 
            available_balance_atm = available_balance_atm - ".$precoin_user->atm.",  pending_received_balance_atm = pending_received_balance_atm + ".$precoin_user->atm.", 
            available_balance_dor = available_balance_dor - ".$precoin_user->dor.",  pending_received_balance_dor = pending_received_balance_dor + ".$precoin_user->dor.", 
            available_balance_val = available_balance_val - ".$precoin_user->val.",  pending_received_balance_val = pending_received_balance_val + ".$precoin_user->val."
            WHERE label = '".$precoin_user->username."'";
            
            DB::update($sql);

            info($i);
            $i++;
            //echo json_encode($precoin_user)."<br>";
        }*/
    }

    //1회성 함수
    public static function lock_kyc_release() {
        $coins = DB::table('btc_coins')->where('market','sports')->get();
        $precoin_users = DB::table('lock_kyc')
        ->join('users','users.email','=','lock_kyc.email')
        ->select('lock_kyc.*','users.username')->get();
        $i = 0;
        foreach($precoin_users as $precoin_user){
            /*foreach($coins as $coin){
                DB::table('btc_transaction')->insert([  // 출금자 트랜잭션 db 삽입
                    'cointxid' => $coin->api."_".$precoin_user->{$coin->api}."_receive_precoin_".$precoin_user->id,
                    'cointype' => $coin->api,
                    'account' => $precoin_user->username,
                    'address' => '',
                    'category' => 'receive',
                    'amount' => $precoin_user->{$coin->api},
                    'confirmations' => 999,
                    'txid' => $coin->api."_".$precoin_user->{$coin->api}."_receive_precoin_".$precoin_user->id,
                    'normtxid' => '',
                    'tr_time' => time(),
                    'timereceived' => time(),
                    'processed' => 'y',
                    'created_dt' => DB::raw('now()'),
                ]);
            }


            //전체 유저 사전판매 코인 제공
            $sql = "UPDATE btc_users_addresses SET 
            available_balance_mnu = available_balance_mnu + ".$precoin_user->mnu.",  pending_received_balance_mnu = pending_received_balance_mnu + ".(-1)*$precoin_user->mnu.", 
            available_balance_bar = available_balance_bar + ".$precoin_user->bar.",  pending_received_balance_bar = pending_received_balance_bar + ".(-1)*$precoin_user->bar.", 
            available_balance_rma = available_balance_rma + ".$precoin_user->rma.",  pending_received_balance_rma = pending_received_balance_rma + ".(-1)*$precoin_user->rma.", 
            available_balance_che = available_balance_che + ".$precoin_user->che.",  pending_received_balance_che = pending_received_balance_che + ".(-1)*$precoin_user->che.", 
            available_balance_brn = available_balance_brn + ".$precoin_user->brn.",  pending_received_balance_brn = pending_received_balance_brn + ".(-1)*$precoin_user->brn.", 
            available_balance_asn = available_balance_asn + ".$precoin_user->asn.",  pending_received_balance_asn = pending_received_balance_asn + ".(-1)*$precoin_user->asn.", 
            available_balance_mct = available_balance_mct + ".$precoin_user->mct.",  pending_received_balance_mct = pending_received_balance_mct + ".(-1)*$precoin_user->mct.", 
            available_balance_liv = available_balance_liv + ".$precoin_user->liv.",  pending_received_balance_liv = pending_received_balance_liv + ".(-1)*$precoin_user->liv.", 
            available_balance_int = available_balance_int + ".$precoin_user->int.",  pending_received_balance_int = pending_received_balance_int + ".(-1)*$precoin_user->int.", 
            available_balance_tot = available_balance_tot + ".$precoin_user->tot.",  pending_received_balance_tot = pending_received_balance_tot + ".(-1)*$precoin_user->tot.", 
            available_balance_nap = available_balance_nap + ".$precoin_user->nap.",  pending_received_balance_nap = pending_received_balance_nap + ".(-1)*$precoin_user->nap.", 
            available_balance_atm = available_balance_atm + ".$precoin_user->atm.",  pending_received_balance_atm = pending_received_balance_atm + ".(-1)*$precoin_user->atm.", 
            available_balance_dor = available_balance_dor + ".$precoin_user->dor.",  pending_received_balance_dor = pending_received_balance_dor + ".(-1)*$precoin_user->dor.", 
            available_balance_val = available_balance_val + ".$precoin_user->val.",  pending_received_balance_val = pending_received_balance_val + ".(-1)*$precoin_user->val."
            WHERE label = '".$precoin_user->username."'";
            
            DB::update($sql);*/

            /*$sql = "UPDATE btc_users_addresses SET 
            pending_received_balance_mnu = pending_received_balance_mnu + ".$precoin_user->mnu*(0.05).", 
            pending_received_balance_bar = pending_received_balance_bar + ".$precoin_user->bar*(0.05).", 
            pending_received_balance_rma = pending_received_balance_rma + ".$precoin_user->rma*(0.05).", 
            pending_received_balance_che = pending_received_balance_che + ".$precoin_user->che*(0.05).", 
            pending_received_balance_brn = pending_received_balance_brn + ".$precoin_user->brn*(0.05).", 
            pending_received_balance_asn = pending_received_balance_asn + ".$precoin_user->asn*(0.05).", 
            pending_received_balance_mct = pending_received_balance_mct + ".$precoin_user->mct*(0.05).", 
            pending_received_balance_liv = pending_received_balance_liv + ".$precoin_user->liv*(0.05).", 
            pending_received_balance_int = pending_received_balance_int + ".$precoin_user->int*(0.05).", 
            pending_received_balance_tot = pending_received_balance_tot + ".$precoin_user->tot*(0.05).", 
            pending_received_balance_nap = pending_received_balance_nap + ".$precoin_user->nap*(0.05).", 
            pending_received_balance_atm = pending_received_balance_atm + ".$precoin_user->atm*(0.05).", 
            pending_received_balance_dor = pending_received_balance_dor + ".$precoin_user->dor*(0.05).", 
            pending_received_balance_val = pending_received_balance_val + ".$precoin_user->val*(0.05)."
            WHERE label = '".$precoin_user->username."'";

            DB::update($sql);
            
            $i++;
            info($i);*/
            //echo json_encode($precoin_user)."<br>";
        }
    }
}
