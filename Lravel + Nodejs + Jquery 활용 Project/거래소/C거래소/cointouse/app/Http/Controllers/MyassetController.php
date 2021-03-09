<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

use DB;
use My_info;
use Settings;
use Auth;
use Log;

class MyassetController extends Controller
{
	
	public function __construct()
    {
        $this->middleware('auth');
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
    public function index()
    {
    	$start_date = date("Y-m-d H:i:s",strtotime("-1 months"));
		$end_date = date("Y-m-d H:i:s");
    	//보유 코인 페이지
		$coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();
		
		$total_holding = 0; // 총 보유자산
		$total_buying = 0;
		foreach($coins as $coin){
			$coin_balance = My_info::get_user_available_balance_allcoin(Auth::user()->id, $coin->api); //코인별 보유잔액
			if($coin->symbol == 'KRW'){
				$coin_balance_krw = bcadd($coin_balance,0,0); // 보유 krwc 양
				continue;
			}
			$coin_buy_amt = My_info::buy_amt($coin->symbol); //코인별 총매수량
			$coin_buy_total = My_info::buy_total($coin->symbol); //코인별 총 (가격 * 양) 
			
			if($coin_buy_amt != 0){ // 코인별 총매수량이 0일때 0으로 대체
				$coin_buy_avg = bcdiv($coin_buy_total,$coin_buy_amt, 0); //코인별 매수 평균
			}else{
				$coin_buy_avg = 0;
			}
			
			$coin_buying_price = bcmul($coin_balance, $coin_buy_avg, 0); // 코인 보유 양 * 매수평균 가격  = 매수금액
			$coin_balance_price = bcmul($coin_balance, $coin->price_krw,0); // 코인 보유 양 * 해당코인 가격 = 평가금액
			
			if($coin_buying_price != 0){
				$coin_buy_percent = bcmul(bcdiv(bcsub($coin_balance_price, $coin_buying_price,0),$coin_buying_price,2),"100",2); //코인별 평가손익
			}else{
				$coin_buy_percent = 0;
			}
			
			$total_buying = bcadd($total_buying,$coin_buying_price,0); //총 매수금액
			$total_holding = bcadd($total_holding,$coin_balance_price,0); // 총 보유자산
			
			
			
			$result[$coin->api]['balance'] = $coin_balance; //코인별 보유수량
			$result[$coin->api]['avg'] = $coin_buy_avg; //코인별 평균가 금액
			$result[$coin->api]['buying'] = $coin_buying_price; //코인별 매수 금액
			$result[$coin->api]['eval'] = $coin_balance_price; //코인별 평가 금액
			$result[$coin->api]['eval_revenue'] = bcsub($coin_balance_price,$coin_buying_price,0); //코인별 평가 수익
			$result[$coin->api]['eval_percent'] =  $coin_buy_percent; //코인별 평가손익
		}

		if($total_buying != 0){ //코인 총 매수금액이 0일때 0으로 다 대체
			$total_eval_revenue = bcsub($total_holding,$total_buying,0); //총 평가수익
			$total_eval_percent = bcmul(bcdiv($total_eval_revenue,$total_buying,4),"100",2); //총 평가수익률
		}else{
			$total_eval_revenue = 0; //총 평가수익
			$total_eval_percent = 0; //총 평가수익률
		}
		
		// 거래내역 총개수
		$historys_count = DB::table('btc_trades_COIN_btc')->where(function ($query){
			$query->where('buyer_uid',Auth::user()->id)->orwhere('seller_uid',Auth::user()->id);
		})->whereBetween('created_dt',[$start_date, $end_date])->count();
		
		// 거래내역 20개 SELECT
		$historys = DB::table('btc_trades_COIN_btc')->join('btc_coins','btc_trades_COIN_btc.cointype','=','btc_coins.api')
		->where(function ($query){
			$query->where('buyer_uid',Auth::user()->id)->orwhere('seller_uid',Auth::user()->id);
		})->whereBetween('created_dt',[$start_date, $end_date])->select('btc_trades_COIN_btc.*','btc_coins.decimal_usd','btc_coins.decimal_btc','btc_coins.decimal_eth','btc_coins.decimal_krw')->orderBy('id','desc')->limit(20)->get();
		
		// 거래소 셋팅정보
		$setting = Settings::Settings();
		
		//미체결 총개수
		$wait_trades_count = DB::table('btc_ads_btc')->where('userid',Auth::user()->username)
		->where(function($qry) {
			$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
		})
		->where(function($qry) {
			$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
		})->orderBy('id','desc')->count();
		
		//미체결 20개 SELECT
		$wait_trades = DB::table('btc_ads_btc')->join('btc_coins','btc_ads_btc.cointype','=','btc_coins.api')
		->where('userid',Auth::user()->username)
		->where(function($qry) {
			$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
		})
		->where(function($qry) {
			$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
		})->select('btc_ads_btc.*','btc_coins.decimal_usd','btc_coins.decimal_btc','btc_coins.decimal_eth','btc_coins.decimal_krw')->orderBy('id','desc')->limit(20)->get();

		
    	$views = view(session('theme').'.'.$this->device.'.'.'my_asset.my_asset');
		
		$views->coins = $coins; //코인정보
		$views->total_buying = $total_buying; //총 매수금액
		$views->total_holding = $total_holding; //총 평가금액
		$views->total_eval_revenue = $total_eval_revenue; //총 평가수익
		$views->total_eval_percent = $total_eval_percent; //총 평가수익
		$views->coin_balance_krw = $coin_balance_krw; //보유 usdc 자산
		$views->total_asset = bcadd($total_holding, $coin_balance_krw,0); //총 보유자산
		$views->result = $result; //코인별 보유정보
		
		$views->historys = $historys; //거래 정보
		$views->historys_count = $historys_count; //거래 정보 총 개수
		
		$views->wait_trades = $wait_trades; // 미체결목록
		$views->wait_trades_count = $wait_trades_count; // 미체결 정보 총 개수
		
		$views->setting = $setting;
		
       
	    return $views;
    }
}
