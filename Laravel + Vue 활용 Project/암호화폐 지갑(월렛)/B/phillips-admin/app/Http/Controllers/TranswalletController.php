<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent;

use DB;
use My_info;
use Auth;
use Secure;
use Log;

class TranswalletController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
	}
	
    public function index(){
    	$user_info = DB::table('btc_security_lv')->where('uid',Auth::user()->id)->first();
		
    	$coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();
		
		
		$total_holding = 0; // 총 보유자산
		foreach($coins as $coin){
			$coin_balance = My_info::get_user_available_balance_allcoin(Auth::user()->id, $coin->api); //코인별 보유잔액
			$coin_balance_price = $coin_balance * $coin->price_krw; //코인 보유 잔액 * 해당코인 가격
			$total_holding += $coin_balance_price;
			
			$result[$coin->api]['balance'] = $coin_balance; //보유코인 잔액
		}
		$security_lv = Secure::secure_short_verified();

		$krw_req_or_not = My_info::krw_requested_or_not();
		
    	$views = view(session('theme').'.'.$this->device.'.'.'trans_wallet.trans_wallet');
		
		$views->coins = $coins;
		$views->total_holding = $total_holding;
		$views->result = $result;
		$views->security_lv = $security_lv;
		$views->user_info = $user_info;
		$views->krw_req_or_not = $krw_req_or_not;
		
    	return	$views;
    }
}
