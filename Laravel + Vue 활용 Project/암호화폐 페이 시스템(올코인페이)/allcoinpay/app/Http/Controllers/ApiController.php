<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Facades\App\Classes\Settings;

class ApiController extends Controller
{
	public function payment_window(Request $request)
    {
        
        if(Auth::check()){
            $uid = Auth::user()->id;
        }else{
            if(isset($request->apikey)){
				$ApiKey = $request->apikey;
				$api_info = DB::table('btc_apikey')->where('public_apikey',$ApiKey)->where('use',1)->whereDate('expiration_at','>',DB::raw('now()'))->first();
				if(!isset($api_info)){
					return redirect()->route('payment_window')->with('error_alert','API 사용자의 경우 만료, 폐기 또는 존재하지 않는 API KEY 입니다.');
                }else{
                    $uid = $api_info->uid;
                }
			}else{
				return redirect()->route('payment_window')->with('error_alert','APIKEY 를 입력 안하셨습니다.');
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
        //시세 가져오기 끝
        
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

        $views = view('payment.payment_window');
        
        $views->symbol = $coin;
        $views->amount = $amount;
        $views->address = $address;
        $views->price = $price;
        $views->qty = $qty;
        $views->last_id = $last_id;
		
        return $views;
    }

	public function api_detail()
    { 
        $apis = DB::table('btc_apikey')->where('uid',Auth::user()->id)->where('use',1)->get();

        $views = view('api.api_detail');
        
        $views->apis = $apis;
		
        return $views;
    }

    public function api_insert(request $request)
    {   
        $expiration_at = date("Y-m-d H:i:s", strtotime("+3 month",time()));
        $apikey = hash('sha256',Auth::user()->username.time()."private".$request->site_url);
        $public_apikey = hash('sha256',Auth::user()->username.time()."public".$request->site_url);

        $result = DB::table('btc_apikey')->insert([
            'uid' => Auth::user()->id,
            'username' => Auth::user()->username,
            'apikey' => $apikey,
            'public_apikey' => $public_apikey,
            'site_url' => $request->site_url,
            'expiration_at' => $expiration_at,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);
        
        if($result > 0){
            return redirect()->back()->with('jsAlert','API 발급이 완료되었습니다.');
        }else{
            return redirect()->back()->with('jsAlert','API 발급에 실패하셨습니다. 잠시 다시 후 실행해 주세요.');
        }
    }
    
}
