<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Facades\App\Classes\Secure;
use Auth;

class PaymentAjaxController extends Controller
{
    //입출금 자산 새로고침
    public function call_orderbook(Request $request){
        $coin = $request->coin;
		$type = $request->type;
		$amount = $request->amount;
		
		
        $url = "http://167.179.88.124:9600/orderbook"; // Where you want to post data
		$postdata = array(
			'coin' => $coin,
			'type' => $type,
			'amount' => $amount
		);
		
		$ch = curl_init();                    // Initiate cURL
	    curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // Define what you want to post
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
	    $output = curl_exec ($ch); // Execute
	    curl_close ($ch); // Close cURL handle  

        return $output; 
        
    }
    
	public function timeout(Request $request)
    {
        $id = $request->id;
        $status = DB::table('btc_payment')->where('id',$id)->update([
            'status' => 'timeout'
        ]);
        
        return $status; 
	}
	
	public function cancel(Request $request)
    {
        $id = $request->id;
        $status = DB::table('btc_payment')->where('id',$id)->update([
            'status' => 'cancel'
        ]);
        
        return $status; 
	}
	
	public function check_status(Request $request)
	{
		$id = $request->id;
		$check_status = DB::table('btc_payment')->where('id',$id)->first()->status;

		if($check_status == 'complete'){
			$status = 1;
		}else{
			$status = 0;
		}

		return $status;
	}

	public function payment_refund(Request $request)
	{
		if(Auth::check()){
            $uid = Auth::user()->id;
        }else{
			if(isset($request->apikey)){
				$ApiKey = $request->apikey;
				$api_info = DB::table('btc_apikey')->where('apikey',$ApiKey)->where('use',1)->whereDate('expiration_at','>',DB::raw('now()'))->first();
				
				if(!isset($api_info)){
					$response = array(
						"status" => 'error',
						"message" => 'Wrong_Apikey_or_SiteURL',
					);
					return response()->json($response);
				}else{
                    $uid = $api_info->uid;
                }
			}else{
				$response = array(
					"status" => 'error',
					"message" => 'Missing_Apikey',
				);
				return response()->json($response);
			}
        }
        

		$id = $request->id;

		$order = DB::table('btc_payment')->where('id',$id)->where('status','complete')->where('uid',$uid)->first();

		if(!isset($order)){
			$response = array(
				"status" => 'error',
				"message" => 'Already_refund or Not exist this order',
			);
			return response()->json($response);
		}
		$user_addresses = DB::table('btc_users_addresses')->where('uid',$order->uid)->first(); //krw 잔액 체크
		
		$buyer_username = $order->buyer_username;

		$cash_amt = $order->cash_price;
		$cointype = 'KRW';
		
		
		$memo = '페이 '.$cash_amt.'원 환불';

		$balance_status = DB::table('btc_users_addresses')->where('label',$buyer_username)->increment('available_balance_'.strtolower($cointype) ,$cash_amt);

		if($balance_status > 0){
			DB::table('btc_krw_io')->insert([
				'uid' => $order->uid,
				'username' => $order->username,
				'type' => 'deposite',
				'status' => 'confirm',
				'plus' => $cash_amt,
				'minus' => 0,
				'amount' => $cash_amt,
				'balance_before' => $user_addresses->available_balance_krw,
				'balance_after' => bcadd($user_addresses->available_balance_krw, $cash_amt, 0),
				'memo' => $memo,
				'created' => time(),
				'rand_plus' => 0,
				'web_type' => 1
			]);
			

			DB::table('btc_payment')->where('id',$id)->update([
				'status' => 'refund'
			]);

			$response = array(
				"status" => 'OK',
			);
		}else{
			$response = array(
				"status" => 'error',
				"message" => 'balance_update',
			);
		}


		/*$coin_amt = $order->coin_amt;
		$cointype = $order->cointype;
		
		$balance_status = DB::table('btc_users_addresses')->where('label',$buyer_username)->increment('available_balance_'.strtolower($cointype) ,$coin_amt);

		$send_cointxid = $cointype."_internal_refund_".$coin_amt."_".$order->username."_".$buyer_username."_".str_random(6)."_send";
		$receive_cointxid = $cointype."_internal_refund_".$coin_amt."_".$order->username."_".$buyer_username."_".str_random(6)."_receive";

		if($balance_status > 0){
			DB::table('btc_transaction')->insert([
				'cointxid' => $receive_cointxid,
				'cointype' => $cointype,
				'account' => $buyer_username,
				'address' => $order->username,
				'category' => 'receive',
				'amount' => $coin_amt,
				'confirmations' => 999,
				'txid' => $receive_cointxid,
				'tr_time' => time(),
				'timereceived' => time(),
				'processed' => 'y',
				'created_dt' => DB::raw('now()')
			]);
			DB::table('btc_payment')->where('id',$id)->update([
				'status' => 'refund'
			]);

			$response = array(
				"status" => 'OK',
			);
		}else{
			$response = array(
				"status" => 'error',
				"message" => 'balance_update',
			);
		}*/

		return response()->json($response);
	}

	public function payment_history(Request $request)
	{
		if(Auth::check()){
            $uid = Auth::user()->id;
        }else{
			if(isset($request->apikey)){
				$ApiKey = $request->apikey;
				$api_info = DB::table('btc_apikey')->where('apikey',$ApiKey)->where('use',1)->whereDate('expiration_at','>',DB::raw('now()'))->first();

				if(!isset($api_info)){
					$response = array(
						"status" => 'error',
						"message" => 'Wrong_Apikey_or_SiteURL',
					);
					return response()->json($response);
				}else{
                    $uid = $api_info->uid;
                }
			}else{
				$response = array(
					"status" => 'error',
					"message" => 'Missing_Apikey',
				);
				return response()->json($response);
			}
        }
		
		$historys = DB::table('btc_payment')
		->select('company_name','cointype','address','status','cash_price','coin_amt','coin_price','buyer_address','buyer_fullname','seller_fullname','created_dt','updated_dt')
		->where('uid',$uid)
		->where(function($query){
            $query->where('status','complete')->orwhere('status','calculate')->orwhere('status','refund');
		})->orderBy('created_dt','desc')->get();

		if(isset($historys)){
			$response = array(
				"status" => 'OK',
				"data" => $historys
			);
		}else{
			$response = array(
				"status" => 'error'
			);
		}
		
		return response()->json($response);
	}

	public function payment_info(Request $request)
	{
		if(Auth::check()){
            $uid = Auth::user()->id;
        }else{
			if(isset($request->apikey)){
				$ApiKey = $request->apikey;
				$api_info = DB::table('btc_apikey')->where('apikey',$ApiKey)->where('use',1)->whereDate('expiration_at','>',DB::raw('now()'))->first();

				if(!isset($api_info)){
					$response = array(
						"status" => 'error',
						"message" => 'Wrong_Apikey_or_SiteURL',
					);
					return response()->json($response);
				}else{
                    $uid = $api_info->uid;
                }
			}else{
				$response = array(
					"status" => 'error',
					"message" => 'Missing_Apikey',
				);
				return response()->json($response);
			}
		}
		
		$coin = $request->symbol;
		$amount = $request->cash_price; //결제하려는 현금(KRW)
		
		//주소 확인 및 생성 시작
        $address_query = DB::table('btc_payment_company')
        ->join('btc_users_addresses','btc_payment_company.username','=','btc_users_addresses.label')
        ->select('btc_users_addresses.address_'.strtolower($coin), 'btc_payment_company.uid', 'btc_payment_company.username', 'btc_payment_company.fullname', 'btc_payment_company.company_name')
        ->where('btc_payment_company.uid',$uid)
        ->first();

        if(isset($address_query)){
            if($address_query->{'address_'.strtolower($coin)} == null){
                //주소생성
                $Settings = Settings::Settings();
                $url = $Settings->url_io."api/create_address.php?v=".time(); // Where you want to post data
                $coin_api_key = "sdafuyhoFicxdDvzhnvkewmnjkGE324dsSsvuzxcvb";
                $postdata = array(
                    'userid' => $address_query->username,
                    'coin_type' => $coin,
                    'api_key' => $coin_api_key
                );
                $ch = curl_init();                    // Initiate cURL
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Define what you want to post
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
                $output = curl_exec ($ch); // Execute
                curl_close ($ch); // Close cURL handle  
                
                $address = $output;

                DB::table('btc_users_addresses')->where('label',$address_query->username)->update([
                    'address_'.strtolower($coin) => $address
                ]);
            }else{
                $address = $address_query->{'address_'.strtolower($coin)};
            }
        }else{
            return redirect()->route('payment_window')->with('error_alert','네트워크 문제로 인해 실행할 수 없습니다. 고객센터에 문의해 주세요.');
        }
        //주소확인 및 생성 끝
        
        // 시세 가져오기 시작
        $url = "http://167.179.88.124:9600/orderbook"; // Where you want to post data
		$postdata = array(
			'coin' => $coin,
			'type' => 'buy',
			'amount' => $amount
		);
		
		$ch = curl_init();                    // Initiate cURL
	    curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // Define what you want to post
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle  

        $result = json_decode($output);
        
        $price = $result->orders[0]->price;
		$qty = $result->orders[0]->qty;
		
		//DB 이전 이력 삭제 및 새 이력 삽입 시작
        DB::table('btc_payment')->where('uid',$address_query->uid)->where('status','requested')->delete();
        $last_id = DB::table('btc_payment')->insertGetId([
            'uid' => $address_query->uid,
            'username' => $address_query->username,
            'company_name' => $address_query->company_name,
            'cointype' => $coin,
            'address' => $address,
            'status' => 'requested',
            'cash_price' => $amount,
            'coin_amt' => $qty,
            'coin_price' => $price,
            'seller_fullname' => $address_query->fullname,
            'created_dt' => DB::raw('now()'),
            'updated_dt' => DB::raw('now()')
        ]);
		//DB 이전 이력 삭제 및 새 이력 삽입 끝
		
		$data = array(
			"symbol" => $coin,
			"amount" => $amount,
			"address" => $address,
			"price" => $price,
			"qty" => $qty,
			"order_id" => $last_id,
		);

		$response = array(
			"status" => 'OK',
			"data" => $data
		);
		return response()->json($response);
	}
}
