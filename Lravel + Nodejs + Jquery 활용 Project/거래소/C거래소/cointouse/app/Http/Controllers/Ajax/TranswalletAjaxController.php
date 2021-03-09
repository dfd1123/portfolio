<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use My_info;
use Coin_info;
use Settings;
use Secure;
use Auth;
use Log;
use Admin;

class TranswalletAjaxController extends Controller
{
    //입출금 자산 새로고침
    public function refresh_trans_wallet(Request $request){
        $coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();
        
        $uid = Auth::user()->id;
        
        $result = array();
        
        $i = 0;
        $total_holding = 0; // 총 보유자산

        foreach($coins as $coin){
            $coin_balance = My_info::get_user_available_balance_allcoin($uid, $coin->api); //코인별 보유잔액
            $coin_balance_price = $coin_balance * $coin->price_krw; //코인 보유 잔액 * 해당코인 가격
            $total_holding += $coin_balance_price;
            
            $result[$i]['symbol'] = $coin->symbol;
            $result[$i]['api'] = $coin->api;
            $result[$i]['decimal_krw'] = $coin->decimal_krw;
            $result[$i]['decimal_usd'] = $coin->decimal_usd;
            $result[$i]['balance'] = (float) $coin_balance;
            $result[$i]['price'] = (float) $coin->price_krw;
            $result[$i]['price_usd'] = (float) $coin->price_usd;
            $i++;
        }
        $response = array(
            "coin" => $result,
            "total_holding" => $total_holding,
        );
        return response()->json($response); 
    }
    //USD <-> BTC 변환 기능
    public function exchangeCashCoin(Request $request){
        $before_cointype = $request->before_cointype;
        $after_cointype = $request->after_cointype;
        $swap_fee = 1;
        
        $uid = Auth::user()->id;
        $username = Auth::user()->username;
        
        $before_cointype_info = Coin_info::get($before_cointype); // 바꾸기 전 코인 정보
        $after_cointype_info = Coin_info::get($after_cointype); // 바꿀 코인정보
        
        if($before_cointype_info->api == 'krw'){
            $ratio = 1 / $after_cointype_info->price_krw;	//krw 일때 코인비율 환산
        }else{
            $ratio = (float) $before_cointype_info->price_krw; //usdc 일때 비율 환산
        }
        $before_balance = My_info::get_user_balance_allcoin(Auth::id(), $before_cointype_info->api); // 변환할 거래대기 제외한 잔액
        $after_balance = My_info::get_user_balance_allcoin(Auth::id(), $after_cointype_info->api); // 변환될 단위 현재 잔액
        
        if($before_balance == 0){
            $response = array(
                "status" => "fail_balance" 
            );
            
            return response()->json($response);
        }
        
        $convert = $before_balance * $ratio; // 변환 한 액수
        $fee = $before_balance * $ratio * 0.01 * $swap_fee; // 변환액수 수수료 
        
        $result = $convert - $fee; //수수료를 뺀 잔액
        
        if($before_cointype == 'USD'){
            $before_cointype_text = 'USDC';
            $after_cointype_text = $after_cointype;
            $before_decimal = 2;
            $after_decimal = 0;
        }else{
            $before_cointype_text = $before_cointype;
            $after_cointype_text = 'USDC';
            $before_decimal =0;
            $after_decimal = 2;
        }
        
        $memo = "Swap" .number_format($before_balance,$before_decimal)." ".$before_cointype_text." to ".number_format($result,$after_decimal)." ".$after_cointype_text." ( ".$swap_fee."% swap fee )"; // 기록
        
		//바꾼 코인 잔액변환 기록
		DB::table('btc_krw_io')->insert([
            "uid" => $uid,
            "username" => $username,
            "type" => "withdraw",
            "status" => "confirm",
            "plus" => $before_balance,
            "minus" => bcmul($before_balance , 0.01,0),
            "amount" => $before_balance,
            "balance_before" => $after_balance,
            "balance_after" => bcadd($after_balance,$result,0),
            "memo" => $memo,
            "created" => time(),
        ]);
        // 잔액 변환

        DB::table('btc_users_addresses')->where('uid',$uid)->update([
            'available_balance_'.strtolower($before_cointype) => DB::raw('available_balance_'.strtolower($before_cointype).' - '.$before_balance),
            'available_balance_'.strtolower($after_cointype) => DB::raw('available_balance_'.strtolower($after_cointype).' + '.$result),
        ]);
        
        $response = array(
            "status" => "success",
            "before_balance" => $before_balance,
            "after_balance" => $result,
            "swap_fee" => $swap_fee,
            "fee" => $fee,
        );
        
        return response()->json($response); 
        
    }
    // 코인선택시
    public function select_coin(Request $request){
        $symbol = $request->symbol;
        $coin = DB::table('btc_coins')->where('symbol',$symbol)->first();
        
        $uid = Auth::user()->id;
        
		if($symbol != "KRW" && $symbol != "USD"){
	        $security_lv = Secure::secure_short_verified();
	        $Settings = Settings::Settings();
	        
	        //보안 레벨 별 최대 출금금액 계산
	        if($security_lv == 0){
	            $lv_amt = 0;
	        }else if($security_lv == 1){
	            $lv_amt = $Settings->lv1_max;
	        }else if($security_lv == 2 || $security_lv == 2.5){
	            $lv_amt = $Settings->lv2_max;
	        }else if($security_lv == 3 || $security_lv == 3.5){
	            $lv_amt = $Settings->lv3_max;
	        }else if($security_lv == 4){
	            $lv_amt = $Settings->lv4_max;
	        }else if($security_lv == 5){
	            $lv_amt = $Settings->lv_max_max;
	        }
	        if($symbol == "USD"){
	            $withdraw_limit_amt = $lv_amt;
	        }else{
	            $withdraw_limit_amt = bcdiv($lv_amt, max($coin->price_krw,1),8);
	        }
		}else{
			$first_history_created = My_info::first_krw_io_history();
			
			if($first_history_created == null){
				$withdraw_limit_amt = 1000000;	
			}else{
				$month3_ago = strtotime("-3 month", time());
				$month1_ago = strtotime("-1 month", time());
				
				if($first_history_created < $month3_ago){
					$withdraw_limit_amt = 30000000;	
				}else if($first_history_created < $month1_ago){
					$withdraw_limit_amt = 10000000;
				}else{
					$withdraw_limit_amt = 1000000;
				}
			}
		}
		
        $coin_balance_total = My_info::get_user_available_balance_allcoin($uid, $coin->api); //해당코인 전체보유잔액
        $coin_balance_pending = My_info::get_user_pending_balance_allcoin($uid, $coin->api); //해당코인 거래대기금액
        $coin_balance_available = $coin_balance_total + $coin_balance_pending; //해당코인 출금가능잔액
        $coin_balance_eval = $coin_balance_total * $coin->price_krw; //평가금액
        
        if($symbol == "USD"){
            $symbol_text = "USDC";
            $coin_decimal = 2;
            $cash_decimal = 0;
            $coin_lang = "usdc";
        }else if($symbol == "KRW"){
            $symbol_text = $symbol;
            $coin_decimal = 0;
            $cash_decimal = $coin->decimal_krw;
            $coin_lang = $coin->api;
        }else{
            $symbol_text = $symbol;
            $coin_decimal = 8;
            $cash_decimal = $coin->decimal_krw;
            $coin_lang = $coin->api;	
        }
        
		if($symbol != 'USD' && $symbol != 'KRW'){
			$deposit_address = My_info::get_user_address_allcoin($uid,$coin_lang);	
		}else{
			$deposit_address = '';	
			
		}
		
        
        
        $response = array(
            "symbol" => $coin->symbol,
            "symbol_text" => $symbol_text,
            "api" => $coin->api,
            "coinname" => __('coin_name.'.$coin_lang),
            "coin_decimal" => $coin_decimal,
            "cash_decimal" => $cash_decimal,
            "total" => (float) $coin_balance_total,
            "pending" => (float) $coin_balance_pending,
            "available" => (float) $coin_balance_available,
            "eval" => (float) $coin_balance_eval,
            "address" => $deposit_address,
            "withdraw_limit_amt" => $withdraw_limit_amt,
        );
        
        return response()->json($response); 
        
    }
    // 주소값 체크
    public function check_address(Request $request){
        $symbol = $request->symbol;
        $address = $request->address;
        
        $Settings = Settings::Settings();
        $url = $Settings->url_io."isvalid/isvalid.php"; // Where you want to post data
        $postdata = array(
            'coin' => strtolower($symbol),
            'address' => $address
        );
        $ch = curl_init();                    // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle  
        
        
        $response = json_decode($output);
        
        
        return response()->json($response); 
    }
    // 최대 출금 출력
    public function withdraw_max_amt(Request $request){
        $symbol = $request->symbol;
    
        $uid = Auth::user()->id;

        $coin = DB::table('btc_coins')->where('symbol',$symbol)->first();
        
        if($symbol != "KRW" && $symbol != "USD"){
        	$decimal = 8;
	        $security_lv = Secure::secure_short_verified();
	        $Settings = Settings::Settings();
	        
	        //보안 레벨 별 최대 출금금액 계산
	        if($security_lv == 0){
	            $lv_amt = 0;
	        }else if($security_lv == 1){
	            $lv_amt = $Settings->lv1_max;
	        }else if($security_lv == 2 || $security_lv == 2.5){
	            $lv_amt = $Settings->lv2_max;
	        }else if($security_lv == 3 || $security_lv == 3.5){
	            $lv_amt = $Settings->lv3_max;
	        }else if($security_lv == 4){
	            $lv_amt = $Settings->lv4_max;
	        }else if($security_lv == 5){
	            $lv_amt = $Settings->lv_max_max;
	        }
	        if($symbol == "USD"){
	            $withdraw_limit_amt = $lv_amt;
	        }else{
	            $withdraw_limit_amt = bcdiv($lv_amt, max($coin->price_krw,1),8);
	        }
		}else{
			$decimal = 0;
			$first_history_created = My_info::first_krw_io_history();
			
			if($first_history_created == null){
				$withdraw_limit_amt = 1000000;	
			}else{
				$month3_ago = strtotime("-3 month", time());
				$month1_ago = strtotime("-1 month", time());
				
				if($first_history_created < $month3_ago){
					$withdraw_limit_amt = 30000000;	
				}else if($first_history_created < $month1_ago){
					$withdraw_limit_amt = 10000000;
				}else{
					$withdraw_limit_amt = 1000000;
				}
			}
		}

        $coin_balance_available = My_info::get_user_balance_allcoin($uid, $coin->api); //해당코인 사용가능한 보유잔액
        $send_fee = $coin->send_fee;
        
		
		
        if($withdraw_limit_amt < $coin_balance_available){
            $max_amount = bcsub($withdraw_limit_amt , $send_fee,$decimal);
        }else{
            $max_amount = bcsub($coin_balance_available , $send_fee,$decimal);
        }
        $response = array(
            "max_amount" => $max_amount,
            "send_fee" => number_format($send_fee,$decimal),
            "total_amount" => number_format(bcadd($max_amount,$send_fee,$decimal),$decimal),
        );
        
        return response()->json($response); 
    }

    // 일일 출금 가능한 양 체크 (keyup)
    public function withdraw_onkey_amt(Request $request){
        $symbol = $request->symbol;
        $amt = $request->amt;
    	info($amt);
        $uid = Auth::user()->id;
        $coin = DB::table('btc_coins')->where('symbol',$symbol)->first();
        
        if($symbol != "KRW" && $symbol != "USD"){
        	$decimal = 8;
	        $security_lv = Secure::secure_short_verified();
	        $Settings = Settings::Settings();
	        
	        //보안 레벨 별 최대 출금금액 계산
	        if($security_lv == 0){
	            $lv_amt = 0;
	        }else if($security_lv == 1){
	            $lv_amt = $Settings->lv1_max;
	        }else if($security_lv == 2 || $security_lv == 2.5){
	            $lv_amt = $Settings->lv2_max;
	        }else if($security_lv == 3 || $security_lv == 3.5){
	            $lv_amt = $Settings->lv3_max;
	        }else if($security_lv == 4){
	            $lv_amt = $Settings->lv4_max;
	        }else if($security_lv == 5){
	            $lv_amt = $Settings->lv_max_max;
	        }
	        if($symbol == "USD"){
	            $withdraw_limit_amt = $lv_amt;
	        }else{
	            $withdraw_limit_amt = bcdiv($lv_amt, max($coin->price_krw,1),8);
	        }
		}else{
			$decimal = 0;
			$first_history_created = My_info::first_krw_io_history();
			
			if($first_history_created == null){
				$withdraw_limit_amt = 1000000;	
			}else{
				$month3_ago = strtotime("-3 month", time());
				$month1_ago = strtotime("-1 month", time());
				
				if($first_history_created < $month3_ago){
					$withdraw_limit_amt = 30000000;	
				}else if($first_history_created < $month1_ago){
					$withdraw_limit_amt = 10000000;
				}else{
					$withdraw_limit_amt = 1000000;
				}
			}
		}

        $coin_balance_available = My_info::get_user_balance_allcoin($uid, $coin->api); //해당코인 사용가능한 보유잔액
        $send_fee = $coin->send_fee;
        
        if($withdraw_limit_amt < $coin_balance_available){
            $max_amount = bcsub($withdraw_limit_amt , $send_fee,$decimal);
        }else{
            $max_amount = bcsub($coin_balance_available , $send_fee,$decimal);
        }
		
        // 최대 출금 한도보다 클때 최대출금양 적용
        if($amt > $max_amount){
            $amt = $max_amount;
            $check_amt = true;
        }else{
            $check_amt = false;
        }
		
        $response = array(
            "amount" => $amt,
            "send_fee" => number_format($send_fee,$decimal),
            "total_amount" => number_format(bcadd($amt,$send_fee,$decimal),$decimal),
            "check_amt" => $check_amt,
        );
        
        return response()->json($response); 
    }

    // 출금 체크 후 전송
    public function send_transaction(Request $request){
        $symbol = $request->symbol;
        $amt = $request->amt;
        $address = $request->address;
        $uid = Auth::user()->id;
        $username = Auth::user()->username;
        
        if($symbol == 'USD'){
            $check_address_symbol = "USDC";		
        }else{
            $check_address_symbol = $symbol;
        }
        // 전송전 주소값 체크
        $Settings = Settings::Settings();
        $url = $Settings->url_io."isvalid/isvalid.php"; // Where you want to post data
        $postdata = array(
            'coin' => strtolower($check_address_symbol),
            'address' => $address
        );
        $ch = curl_init();                    // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle  
        
        $valid_address = json_decode($output);
        if($valid_address->result == 'invalid'){
            $response = array(
                "state" => "check_address",
            );
            return response()->json($response); 
        }
            
        
        // 전송전 출금한도 체크
        $coin = DB::table('btc_coins')->where('symbol',$symbol)->first();
        
        if($symbol != "KRW" && $symbol != "USD"){
        	$decimal = 8;
	        $security_lv = Secure::secure_short_verified();
	        $Settings = Settings::Settings();
	        
	        //보안 레벨 별 최대 출금금액 계산
	        if($security_lv == 0){
	            $lv_amt = 0;
	        }else if($security_lv == 1){
	            $lv_amt = $Settings->lv1_max;
	        }else if($security_lv == 2 || $security_lv == 2.5){
	            $lv_amt = $Settings->lv2_max;
	        }else if($security_lv == 3 || $security_lv == 3.5){
	            $lv_amt = $Settings->lv3_max;
	        }else if($security_lv == 4){
	            $lv_amt = $Settings->lv4_max;
	        }else if($security_lv == 5){
	            $lv_amt = $Settings->lv_max_max;
	        }
	        if($symbol == "USD"){
	            $withdraw_limit_amt = $lv_amt;
	        }else{
	            $withdraw_limit_amt = bcdiv($lv_amt, max($coin->price_krw,1),8);
	        }
		}else{
			$decimal = 0;
			$first_history_created = My_info::first_krw_io_history();
			
			if($first_history_created == null){
				$withdraw_limit_amt = 1000000;	
			}else{
				$month3_ago = strtotime("-3 month", time());
				$month1_ago = strtotime("-1 month", time());
				
				if($first_history_created < $month3_ago){
					$withdraw_limit_amt = 30000000;	
				}else if($first_history_created < $month1_ago){
					$withdraw_limit_amt = 10000000;
				}else{
					$withdraw_limit_amt = 1000000;
				}
			}
		}

        $coin_balance_available = My_info::get_user_balance_allcoin($uid, $coin->api); //해당코인 사용가능한 보유잔액
        $send_fee = $coin->send_fee;
        
        if($withdraw_limit_amt < $coin_balance_available){
            $max_amount = bcsub($withdraw_limit_amt , $send_fee,$decimal);
        }else{
            $max_amount = bcsub($coin_balance_available , $send_fee,$decimal);
        }
        if($amt > $max_amount){
            $response = array(
                "state" => "over_balance",
            );
            
            return response()->json($response); 
        }
        if($amt <= $send_fee){
            $response = array(
                "state" => "under_fee",
            );
            
            return response()->json($response); 
        }
        
        //주소가 내부인지 외부인지 구별
        $internal_external = DB::table('btc_users_addresses')->where('address_'.strtolower($symbol),$address)->first();
        
        if($internal_external == null){ // null 이면 외부로 출금 아니면 내부로 출금
            DB::table('btc_coin_send_request')->insert([ //출금자 출금 요청정보 삽입
                'cointype' => $symbol,
                'coin_category' => $coin->cointype,
                'type' => 'withdraw',
                'send_type' => 'external',
                'sender_userid' => $username,
                'receiver_address' => $address,
                'req_amount' => $amt,
                'send_fee' => $send_fee,
                'total_amt' => bcadd($amt,$send_fee,8),
                'status' => 'withdraw_request',
                'tx_id' => '',
                'created' => time(),
                'created_dt' => DB::raw('now()'),
                'updated' => time(),
                'updated_dt' => DB::raw('now()'),
                'price_krw' => $coin->last_trade_price_krw,
            ]);
            

            DB::table('btc_users_addresses')->where('uid',$uid)->update([ //출금자 출금할 금액 거래대기
                'pending_received_balance_'.strtolower($symbol) => DB::raw('pending_received_balance_'.strtolower($symbol).' - '.bcadd($amt,$send_fee,8)),
            ]);
            
            $response = array(
                "state" => 'success',
            );
        }else{

            $tx_id = 'internal_transfer_'.$amt.'_'.$username.'_'.$internal_external->label.'_'.substr(md5(rand()),0,7); // 내부 tx_id 생성
            
            if(bcmul($amt , $coin->price_krw, 8) > 1000000){ // 100만원 이상일경우 관리자 승인 받고 안받고
                DB::table('btc_coin_send_request')->insert([ // 출금자 출금 요청정보 삽입
                    'cointype' => $symbol,
                    'coin_category' => $coin->cointype,
                    'type' => 'withdraw',
                    'send_type' => 'internal',

                    'sender_userid' => $username,
                    'receiver_address' => $address,
                    'req_amount' => $amt,
                    'send_fee' => $send_fee,
                    'total_amt' => bcadd($amt,$send_fee,8),
                    'status' => 'withdraw_request',
                    'tx_id' => '',
                    'created' => time(),
                    'created_dt' => DB::raw('now()'),
                    'updated' => time(),
                    'updated_dt' => DB::raw('now()'),
                    'price_krw' => $coin->last_trade_price_krw,
                ]);
                

                DB::table('btc_users_addresses')->where('uid',$uid)->update([ //출금자 출금할 금액 거래대기
                    'pending_received_balance_'.strtolower($symbol) => DB::raw('pending_received_balance_'.strtolower($symbol).' - '.bcadd($amt,$send_fee,8)),
                ]);
                
                $response = array(
                    "state" => 'success',
                );
            }else{
                DB::table('btc_coin_send_request')->insert([ // 출금자 트랜잭션 db 삽입
                    'cointype' => $symbol,
                    'coin_category' => $coin->cointype,
                    'type' => 'withdraw',
                    'send_type' => 'internal',

                    'sender_userid' => $username,
                    'receiver_address' => $address,
                    'req_amount' => $amt,
                    'send_fee' => $send_fee,
                    'total_amt' => bcadd($amt,$send_fee,8),
                    'status' => 'withdraw_complete',
                    'tx_id' => $tx_id,
                    'created' => time(),
                    'created_dt' => DB::raw('now()'),
                    'updated' => time(),
                    'updated_dt' => DB::raw('now()'),
                    'price_krw' => $coin->last_trade_price_krw,
                ]);
                
                DB::table('btc_transaction')->insert([  // 출금자 트랜잭션 db 삽입
                    'cointxid' => strtolower($symbol)."_".$tx_id."_send",
                    'cointype' => strtolower($symbol),

                    'account' => $username,
                    'address' => $address,
                    'category' => 'send',
                    'amount' => $amt,
                    'confirmations' => 999,
                    'txid' => $tx_id,
                    'normtxid' => '',
                    'tr_time' => time(),
                    'timereceived' => time(),
                    'processed' => 'y',
                    'created_dt' => DB::raw('now()'),
                ]);
                
                DB::table('btc_transaction')->insert([  // 입금자 트랜잭션 db 삽입
                    'cointxid' => strtolower($symbol)."_".$tx_id."_receive",
                    'cointype' => strtolower($symbol),
                    'account' => $internal_external->label,
                    'address' => $address,
                    'category' => 'receive',
                    'amount' => $amt,
                    'confirmations' => 999,
                    'txid' => $tx_id,
                    'normtxid' => '',
                    'tr_time' => time(),
                    'timereceived' => time(),
                    'processed' => 'y',
                    'created_dt' => DB::raw('now()'),
                ]);
                

                DB::table('btc_users_addresses')->where('uid',$uid)->decrement('available_balance_'.strtolower($symbol),bcadd($amt,$send_fee,8)); // 출금자 코인 차감
                
                DB::table('btc_users_addresses')->where('uid',$internal_external->uid)->increment('available_balance_'.strtolower($symbol),$amt); // 입금자 코인 더함
                
                Admin::alarm_withdraw_confirm($username, $symbol, $address, $amt); // 출금 알림 보내기
                
                $response = array(
                    "state" => 'success_now',
                );
            }
            
        }
        
        return response()->json($response); 
    }

    public function refresh_withdraw_history(Request $request){
        $symbol = $request->symbol; 
        $username = Auth::user()->username;
		
		if($symbol == 'KRW'){
			$send_transaction = DB::table("btc_krw_io")->select(
			"id",
			"type",
			"amount",
			"status",
			DB::raw("CONVERT_TZ(FROM_UNIXTIME(created),'+00:00','+09:00') as updated"),
			DB::raw("'KRW' as cointype ")
			)->where('uid',Auth::id())->where('type','<>','currency_change')->orderBy('id','desc');
		}else{
	        $send_transaction = DB::table("btc_coin_send_request")->select(
	        "btc_coin_send_request.type",
	        "btc_coin_send_request.total_amt",
	        "btc_coin_send_request.status",
	        "btc_coin_send_request.tx_id",
	        DB::raw("CONVERT_TZ(FROM_UNIXTIME(btc_coin_send_request.updated),'+00:00','+09:00') as updated"),
	        "btc_coin_send_request.cointype",
	        "btc_coin_send_request.receiver_address"
	
	        )->where("sender_userid",$username)->where("cointype",$symbol);
	        
	        $receive_transaction = DB::table("btc_transaction")->select(
	        "btc_transaction.category",
	        "btc_transaction.amount",
	        "btc_transaction.confirmations",
	        "btc_transaction.txid",
	        DB::raw("CONVERT_TZ(FROM_UNIXTIME(btc_transaction.tr_time),'+00:00','+09:00') as updated"),
	        "btc_transaction.cointype",
	        "btc_transaction.address"
	
	        )->where("account",$username)->where("cointype",strtolower($symbol))->where("category","receive");
	        $send_transaction = $send_transaction->unionAll($receive_transaction);
	        
	        $coin_io_transactions = DB::table("btc_coin_io")->select(
	        "btc_coin_io.type",
	        "btc_coin_io.amount",
	        "btc_coin_io.tx_id",
	        "btc_coin_io.memo",
	        DB::raw("CONVERT_TZ(FROM_UNIXTIME(btc_coin_io.created),'+00:00','+09:00') as updated"),
	        "btc_coin_io.cointype",
	        "btc_coin_io.memo"
	
	        )->where("username",$username)->where("cointype",$symbol);
	        $send_transaction = $send_transaction->unionAll($coin_io_transactions)->orderBy("updated","desc");
        }
        $response = array(
            "transaction_list" => $send_transaction->get(),
        );
        
        return response()->json($response);
    }

	public function refresh_erc_eth_balance(Request $request){
		$Settings = Settings::Settings();
		
		$url = $Settings->url_io."update_eth_erc_balance/update_eth_erc_balance.php";
        $postdata = array(
            'userid' => Auth::user()->username
        );
        $ch = curl_init();                    // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle  
        
        $response = json_decode($output);
		
        return response()->json($response);
    }
    
	public function cash_deposite(Request $request){
		if(My_info::krw_requested_or_not()){
			$response = array(
	            "status" => "error1",
	        );
		}else{
			$amount = $request->amount; // 입금한 금액
			if($amount < 1000){
                $response = array(
                    "status" => "error3",
                );
            }else{
                $Settings = Settings::Settings(); // 마켓정보
                
                $user_info = DB::table('users')->where('id', Auth::user()->id)->first(); // 유저정보
                $user_addresses = DB::table('btc_users_addresses')->where('uid', Auth::user()->id)->first(); //krw 잔액 체크
                $user_security_info = DB::table('btc_security_lv')->where('uid', Auth::user()->id)->first(); //보안레벨의 은행정보 확인
                
                $memo = "입금계좌 : ( ".$user_security_info->account_bank." | ".$user_security_info->account_num." | ".$user_info->fullname." )";
                
                $check_status = DB::table('btc_krw_io')->insert([  // 입금자 트랜잭션 db 삽입
                    'uid' => $user_info->id,
                    'username' => $user_info->username,
                    'type' => 'deposite',
                    'status' => 'deposite_request',
                    'plus' => $amount,
                    'minus' => 0,
                    'amount' => $amount,
                    'balance_before' => $user_addresses->available_balance_krw,
                    'balance_after' => bcadd($user_addresses->available_balance_krw, $amount, 0),
                    'memo' => $memo,
                    'created' => time(),
                    'bankname' => $user_security_info->account_bank,
                    'bankaccount' => $user_security_info->account_num,
                    'bankowner' => $user_info->fullname,
                    'rand_plus' => 0,
                    'web_type' => $Settings->id,
                ]);
                
                if ($check_status > 0) {
                    $response = array(
                        "status" => "success",
                    );
                } else {
                    $response = array(
                        "status" => "error2",
                    );
                }
            }
		}

        return response()->json($response);		
	}

	public function cash_withdraw(Request $request){
		if(My_info::krw_requested_or_not()){
			$response = array(
	            "messages" => __('trans.cash_deposite_error1'),
	        );
		}else{
			$amount = $request->amount; // 입금한 금액
			$symbol = $request->symbol;
			
			$Settings = Settings::Settings(); // 마켓정보
			
			$decimal = 0;
			$first_history_created = My_info::first_krw_io_history();
			
			if($first_history_created == null){
				$withdraw_limit_amt = 1000000;	
			}else{
				$month3_ago = strtotime("-3 month", time());
				$month1_ago = strtotime("-1 month", time());
				
				if($first_history_created < $month3_ago){
					$withdraw_limit_amt = 30000000;	
				}else if($first_history_created < $month1_ago){
					$withdraw_limit_amt = 10000000;
				}else{
					$withdraw_limit_amt = 1000000;
				}
			}
			
			
			
			$user_info = DB::table('users')->where('id',Auth::user()->id)->first(); // 유저정보
			$user_addresses = DB::table('btc_users_addresses')->where('uid',Auth::user()->id)->first(); //krw 잔액 체크
			$user_security_info = DB::table('btc_security_lv')->where('uid',Auth::user()->id)->first(); //보안레벨의 은행정보 확인
			
			if($amount > bcadd($user_addresses->available_balance_krw + $user_addresses->pending_received_balance_krw,0) || $amount <= 0){
				$response = array(
		            "status" => "error1",
		        );
			}else if($amount > $withdraw_limit_amt){
				$response = array(
		            "status" => "error2",
		        );
			}else{
				$coin = DB::table('btc_coins')->where('symbol',$symbol)->first();
				$memo = "출금계좌 : ( ".$user_security_info->account_bank." | ".$user_security_info->account_num." | ".$user_info->fullname." )";
				
				DB::table('btc_users_addresses')->where('uid',Auth::id())->decrement('pending_received_balance_'.strtolower($symbol), bcadd($amount,$coin->send_fee,0));
				
				$check_status = DB::table('btc_krw_io')->insert([  // 입금자 트랜잭션 db 삽입
		            'uid' => $user_info->id,
		            'username' => $user_info->username,
		            'type' => 'withdraw',
		            'status' => 'withdraw_request',
		            'plus' => $amount,
		            'minus' => $coin->send_fee,
		            'amount' => bcadd($amount,$coin->send_fee,0),
		            'balance_before' => $user_addresses->available_balance_krw,
		            'balance_after' => bcsub($user_addresses->available_balance_krw, bcadd($amount,$coin->send_fee,0), 0),
		            'memo' => $memo,
		            'created' => time(),
		            'bankname' => $user_security_info->account_bank,
		            'bankaccount' => $user_security_info->account_num,
		            'bankowner' => $user_info->fullname,
		            'rand_plus' => 0,
		            'web_type' => $Settings->id,
		        ]);
				
				if($check_status > 0){
					$response = array(
			            "status" => "success",
			        );
				}else{
					$response = array(
			            "status" => "error3",
			        );
				}
			}
		}

        return response()->json($response);		
	}
	
	public function cash_cancel(Request $request){
		$id = $request->id;
		$type = $request->type;
		
		if($type == 0){
			$check_status = DB::table('btc_krw_io')->where('id',$id)->update([
				'status' => 'deposite_cancel',
			]);
		}else{
			$this_cash_info = DB::table('btc_krw_io')->where('id',$id)->first();
			
			DB::table('btc_users_addresses')->where('uid',Auth::id())->increment('pending_received_balance_krw', $this_cash_info->amount);
			$check_status = DB::table('btc_krw_io')->where('id',$id)->update([
				'status' => 'withdraw_cancel',
			]);
		}
		
		if($check_status > 0){
			$response = array(
	            "status" => "success",
	        );
		}else{
			$response = array(
	            "status" => "error",
	        );
		}

		return response()->json($response);
	}
	
}
