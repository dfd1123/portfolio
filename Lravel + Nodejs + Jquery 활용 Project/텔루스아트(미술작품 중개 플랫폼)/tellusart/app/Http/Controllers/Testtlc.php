<?php

namespace TLCfund\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TLCfund\User;
use TLCfund\Address;
use App\Classes\Tlcapi;
use Log;
use File;
use Auth;

class Testtlc extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    function index()
    {
    	
		$tlc = new Tlcapi();
		//Log::info($tlc->listtransactions("test@test.com",5,0)); // list 체크 
		$user_info = User::where('id',10)->with('address')->first();
		//Log::info($tlc->getaddressesbyaccount('test@test.com')); // 잔액 체크후 받기
		//Log::info($tlc->sendfrom('test@test.com','TLzpgT6UkBbDj8tcfLTE8LmdM7GVapnYrM',1)); // 보내기*/
		$send_tlc = $tlc->sendfrom('slkdgjsldkjg','sdgsdgklsjdgj',10);	
			if($send_tlc == ''){
				$result = '네트워크상의 문제로 현재 코인보내기가 불가합니다. 나중에 다시 시도해주세요.';
			}else{
				$result = '코인 '.$amount.'개를 출금하셨습니다.';
			}
			Log::info($result);	
		Log::info($tlc->listaccounts(1));
        $views = view('test');
		$views->user_info = $user_info;
		$views->listtransaction = $tlc->listtransactions("*",10,0);
		
		return $views;
		
		//return response()->json($tlc->validateaddress($request->address)['isvalid']);
    }
	
	protected function store()
    {
    	$tlc = new Tlcapi();
		$email = 'test@test.com';
		$user_info = User::where('id',10)->with('address')->first();
		//$tlc->sendfrom('THYvPgLXdD68qirQeSaSXKa6pbhifHmDo2','TLzpgT6UkBbDj8tcfLTE8LmdM7GVapnYrM',1);
		
		/*$getnewaddress_tlc  = $tlc->getnewaddress($email);
        Address::create([
            'user_id' => '10',
            'user_email' => $email,
            'address_tlc' => $getnewaddress_tlc
        ]);*/
        /*	$tlc = new Tlcapi();
		$getnewaddress_tlc  = $tlc->getnewaddress($data['email']);
    	Address::create([
           
            'user_email' => $data['email'],
            'address_tlc' => $getnewaddress_tlc
        ]);*/
		$views = view('test');
		$views->user_info = $user_info;
		$views->listtransaction = $tlc->listtransactions("*",10,0);
		Log::info($tlc->listtransactions("*",10, 0));
		Log::info($tlc->getbalance('test@test.com'));
		
		return $views;
    }
}
