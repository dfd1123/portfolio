<?php

namespace App\Classes;

use DB;

class AirDrop {
	public function drop($uid, $case)
	{
        $airdrops = DB::table('btc_airdrop')->where('status',1)->where('cases',$case)->orderBy('status','desc')->get();
        $user = DB::table('users')->where('id',$uid)->first();
        $today = date("Y-m-d H:i:s");

        $airdrop_yn = 0;
        $message = '';

        foreach($airdrops as $airdrop){
            if($airdrop->start_time <= $today && $today <= $airdrop->end_time){
                if($airdrop->residual_cnt != 0 && bcsub($airdrop->residual_cnt,$airdrop->send_cnt,8) >= 0 ){
                    if($airdrop->overlap_yn == 0){
                        $getter_cnt = DB::table('btc_airdrop_history')->where('drop_id',$airdrop->id)->where('uid',$uid)->count();
                        if($getter_cnt == 0){
                            DB::table('btc_users_addresses')->where('uid',$uid)->increment('available_balance_'.$airdrop->coin, $airdrop->send_cnt);
                            DB::table('btc_airdrop')->where('id',$airdrop->id)->decrement('residual_cnt', $airdrop->send_cnt);
                            DB::table('btc_airdrop_history')->insert([
                                "drop_id" => $airdrop->id,
                                "uid" => $uid,
                                "label" => $user->username,
                                "cases" => $airdrop->cases,
                                "cointype" => $airdrop->coin,
                                "get_amt" => $airdrop->send_cnt,
                                "status" => 1,
                                "get_date" => date('Y-m-d H:i:s'),
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                            DB::table('btc_transaction')->insert([
                                "cointxid" => $airdrop->coin.'_airdrop_transfer_'.$airdrop->send_cnt.'_cointouse to '.$user->username,
                                "cointype" => $airdrop->coin,
                                "account" => "cointouse",
                                "address" => $user->username,
                                "category" => "airdrop",
                                "amount" => $airdrop->send_cnt,
                                "confirmations" => 999,
                                "txid" => $airdrop->coin.'_airdrop_transfer_'.$airdrop->send_cnt.'_cointouse to '.$user->username,
                                "normtxid" => '',
                                "tr_time" => time(),
                                "timereceived" => time(),
                                "processed" => 'y',
                                'created_dt' => DB::raw('now()'),
                            ]);

                            $airdrop_yn = 1;
					        $message = $airdrop->send_cnt.' '.$airdrop->coin.' AirDrop!!';
                        }
                    }else{
                        DB::table('btc_users_addresses')->where('uid',$uid)->increment('available_balance_'.$airdrop->coin, $airdrop->send_cnt);
                        DB::table('btc_airdrop')->where('id',$airdrop->id)->decrement('residual_cnt', $airdrop->send_cnt);
                        DB::table('btc_airdrop_history')->insert([
                            "drop_id" => $airdrop->id,
                            "uid" => $uid,
                            "label" => $user->username,
                            "cases" => $airdrop->cases,
                            "cointype" => $airdrop->coin,
                            "get_amt" => $airdrop->send_cnt,
                            "status" => 1,
                            "get_date" => date('Y-m-d H:i:s'),
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        DB::table('btc_transaction')->insert([
                            "cointxid" => $airdrop->coin.'_airdrop_transfer_'.$airdrop->send_cnt.'_cointouse to '.$user->username,
                            "cointype" => $airdrop->coin,
                            "account" => "cointouse",
                            "address" => $user->username,
                            "category" => "airdrop",
                            "amount" => $airdrop->send_cnt,
                            "confirmations" => 999,
                            "txid" => $airdrop->coin.'_airdrop_transfer_'.$airdrop->send_cnt.'_cointouse to '.$user->username,
                            "normtxid" => '',
                            "tr_time" => time(),
                            "timereceived" => time(),
                            "processed" => 'y',
                            'created_dt' => DB::raw('now()'),
                        ]);

                        $airdrop_yn = 1;
					    $message = $airdrop->send_cnt.' '.$airdrop->coin.' AirDrop!!';
                    }
                    

                }
            }else if($today > $airdrop->end_time){
                DB::table('btc_airdrop')->where('id',$airdrop->id)->update([
                    "status" => 0,
                ]);
            }
        }

        $result = array(
            'airdrop_yn' => $airdrop_yn,
            'message' => $message,
        );
		
		return $result;
	}
}
