<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use My_info;
use Settings;
use Auth;
use Log;

class MyAssetAjaxController extends Controller
{
    //내자산 >> 거래내역  더보기버튼 기능
    public function show_more_history(Request $request){
    	$offset = $request->offset;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		$uid = Auth::user()->id;
			
		$start_date = $start_date." 00:00:00";
		$end_date = $end_date." 23:59:59";
		// 거래내역 총개수
		$historys_count = DB::table('btc_trades_COIN_btc')->where(function ($query) use ($uid){
			$query->where('buyer_uid',$uid)->orwhere('seller_uid',$uid);
		})->whereBetween('created_dt',[$start_date, $end_date])->count();
		
		// 거래내역 20개 SELECT
		$historys = DB::table('btc_trades_COIN_btc')->join('btc_coins','btc_trades_COIN_btc.cointype','=','btc_coins.api')
		->where(function ($query) use ($uid){
			$query->where('buyer_uid',$uid)->orwhere('seller_uid',$uid);
		})->whereBetween('created_dt',[$start_date, $end_date])->select('btc_trades_COIN_btc.*','btc_coins.decimal_usd','btc_coins.decimal_btc','btc_coins.decimal_eth','btc_coins.decimal_krw')->orderBy('id','desc')->offset($offset)->limit(10)->get();
		
		// 거래소 셋팅정보
		$setting = Settings::Settings();
		
		$result = array();
		$i = 0;
		foreach($historys as $history){
			if(strtoupper($history->currency) == 'USD'){
				$result[$i]['currency'] = 'USDC';
			}else{
				$result[$i]['currency'] = strtoupper($history->currency);				
			}
			
			if($history->buyer_uid == $history->seller_uid){
				$result[$i]['trade_type'] = "self";
				$result[$i]['trade_type_name'] = __('my_asset.self_trade');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.self_trade');
				$result[$i]['lang_trade_quantity'] = __('my_asset.trade_quantity');
				$result[$i]['lang_trade_unit_price'] = __('my_asset.trade_unit_price');
				$result[$i]['lang_trade_price'] = __('my_asset.trade_price');
				$result[$i]['lang_fees'] = __('my_asset.fees');
				$result[$i]['lang_settlement_amount_mb'] = __('my_asset.settlement_amount_mb');
				
				$result[$i]['api'] = strtolower($history->cointype);
				$result[$i]['symbol'] = strtoupper($history->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($history->created_dt)));
				$result[$i]['coinname'] = __('coin_name.'.strtolower($history->cointype));
				$result[$i]['contract_coin_amt'] = number_format($history->contract_coin_amt,8);
				$result[$i]['coin_price'] = number_format($history->buy_coin_price,$history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['total_price'] = number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['fee'] = number_format($history->contract_coin_amt,8);
				$result[$i]['calcul_price'] = number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
			}else if($history->buyer_uid == $uid){
				$result[$i]['trade_type'] = "buy";
				$result[$i]['trade_type_name'] = __('my_asset.buy');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.self_trade');
				$result[$i]['lang_trade_quantity'] = __('my_asset.trade_quantity');
				$result[$i]['lang_trade_unit_price'] = __('my_asset.trade_unit_price');
				$result[$i]['lang_trade_price'] = __('my_asset.trade_price');
				$result[$i]['lang_fees'] = __('my_asset.fees');
				$result[$i]['lang_settlement_amount_mb'] = __('my_asset.settlement_amount_mb');
				
				$result[$i]['api'] = strtolower($history->cointype);
				$result[$i]['symbol'] = strtoupper($history->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($history->created_dt)));
				$result[$i]['coinname'] = __('coin_name.'.strtolower($history->cointype));
				$result[$i]['contract_coin_amt'] = number_format($history->contract_coin_amt,8);
				$result[$i]['coin_price'] = number_format($history->buy_coin_price,$history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['total_price'] = number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['fee'] = number_format($history->contract_coin_amt,8);
				$result[$i]['calcul_price'] = number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
			}else{
				$result[$i]['trade_type'] = "sell";
				$result[$i]['trade_type_name'] = __('my_asset.sell');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.self_trade');
				$result[$i]['lang_trade_quantity'] = __('my_asset.trade_quantity');
				$result[$i]['lang_trade_unit_price'] = __('my_asset.trade_unit_price');
				$result[$i]['lang_trade_price'] = __('my_asset.trade_price');
				$result[$i]['lang_fees'] = __('my_asset.fees');
				$result[$i]['lang_settlement_amount_mb'] = __('my_asset.settlement_amount_mb');
				
				$result[$i]['api'] = strtolower($history->cointype);
				$result[$i]['symbol'] = strtoupper($history->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($history->created_dt)));
				$result[$i]['coinname'] = __('coin_name.'.strtolower($history->cointype));
				$result[$i]['contract_coin_amt'] = number_format($history->contract_coin_amt,8);
				$result[$i]['coin_price'] = number_format($history->sell_coin_price, $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['total_price'] = number_format(bcadd($history->trade_total_sell,bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['fee'] = number_format(bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['calcul_price'] = number_format($history->trade_total_sell, $history->{'decimal_'.strtolower($history->currency)});
			}
			$i++;
			
		}
		
		$response = array(
			"historys_count" => $historys_count,
			"historys" => $result,
			"setting" => $setting,
		);		
		
		return response()->json($response); 
		
	}
	
	//내자산 >> 거래내역  조회 기능
    public function search_date_history(Request $request){
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		$uid = Auth::user()->id;
		
		$start_date = $start_date." 00:00:00";
		$end_date = $end_date." 23:59:59";
		// 거래내역 총개수
		$historys_count = DB::table('btc_trades_COIN_btc')->where(function ($query) use ($uid){
			$query->where('buyer_uid',$uid)->orwhere('seller_uid',$uid);
		})->whereBetween('created_dt',[$start_date, $end_date])->count();
		// 거래내역 20개 SELECT
		$historys = DB::table('btc_trades_COIN_btc')->join('btc_coins','btc_trades_COIN_btc.cointype','=','btc_coins.api')
		->where(function ($query) use ($uid){
			$query->where('buyer_uid',$uid)->orwhere('seller_uid',$uid);
		})->whereBetween('created_dt',[$start_date, $end_date])->select('btc_trades_COIN_btc.*','btc_coins.decimal_usd','btc_coins.decimal_btc','btc_coins.decimal_eth','btc_coins.decimal_krw')->orderBy('id','desc')->limit(20)->get();
		
		// 거래소 셋팅정보
		$setting = Settings::Settings();
		
		$result = array();
		$i = 0;
		foreach($historys as $history){
			if(strtoupper($history->currency) == 'USD'){
				$result[$i]['currency'] = 'USDC';
			}else{
				$result[$i]['currency'] = strtoupper($history->currency);				
			}
			if($history->buyer_uid == $history->seller_uid){
				$result[$i]['trade_type'] = "self";
				$result[$i]['trade_type_name'] = __('my_asset.self_trade');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.self_trade');
				$result[$i]['lang_trade_quantity'] = __('my_asset.trade_quantity');
				$result[$i]['lang_trade_unit_price'] = __('my_asset.trade_unit_price');
				$result[$i]['lang_trade_price'] = __('my_asset.trade_price');
				$result[$i]['lang_fees'] = __('my_asset.fees');
				$result[$i]['lang_settlement_amount_mb'] = __('my_asset.settlement_amount_mb');
				
				$result[$i]['api'] = strtolower($history->cointype);
				$result[$i]['symbol'] = strtoupper($history->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($history->created_dt)));
				$result[$i]['coinname'] = __('coin_name.'.strtolower($history->cointype));
				$result[$i]['contract_coin_amt'] = number_format($history->contract_coin_amt,8);
				$result[$i]['coin_price'] = number_format($history->buy_coin_price,$history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['total_price'] = number_format($history->trade_total_buy, $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['fee'] = number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['calcul_price'] = number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
			}else if($history->buyer_uid == $uid){
				$result[$i]['trade_type'] = "buy";
				$result[$i]['trade_type_name'] = __('my_asset.buy');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.gm');
				$result[$i]['lang_trade_quantity'] = __('my_asset.trade_quantity');
				$result[$i]['lang_trade_unit_price'] = __('my_asset.trade_unit_price');
				$result[$i]['lang_trade_price'] = __('my_asset.trade_price');
				$result[$i]['lang_fees'] = __('my_asset.fees');
				$result[$i]['lang_settlement_amount_mb'] = __('my_asset.settlement_amount_mb');
				
				$result[$i]['api'] = strtolower($history->cointype);
				$result[$i]['symbol'] = strtoupper($history->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($history->created_dt)));
				$result[$i]['coinname'] = __('coin_name.'.strtolower($history->cointype));
				$result[$i]['contract_coin_amt'] = number_format($history->contract_coin_amt,8);
				$result[$i]['coin_price'] = number_format($history->buy_coin_price,$history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['total_price'] = number_format($history->trade_total_buy, $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['fee'] = number_format(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['calcul_price'] = number_format(bcadd(bcmul(bcmul($history->trade_total_buy,$setting->buy_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_buy,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
			}else{
				$result[$i]['trade_type'] = "sell";
				$result[$i]['trade_type_name'] = __('my_asset.sell');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.pm');
				$result[$i]['lang_trade_quantity'] = __('my_asset.trade_quantity');
				$result[$i]['lang_trade_unit_price'] = __('my_asset.trade_unit_price');
				$result[$i]['lang_trade_price'] = __('my_asset.trade_price');
				$result[$i]['lang_fees'] = __('my_asset.fees');
				$result[$i]['lang_settlement_amount_mb'] = __('my_asset.settlement_amount_mb');
				
				$result[$i]['api'] = strtolower($history->cointype);
				$result[$i]['symbol'] = strtoupper($history->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($history->created_dt)));
				$result[$i]['coinname'] = __('coin_name.'.strtolower($history->cointype));
				$result[$i]['contract_coin_amt'] = number_format($history->contract_coin_amt,8);
				$result[$i]['coin_price'] = number_format($history->sell_coin_price, $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['total_price'] = number_format($history->trade_total_sell, $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['fee'] = number_format(bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
				$result[$i]['calcul_price'] = number_format(bcadd(bcmul(bcmul($history->trade_total_sell,$setting->sell_comission,$history->{'decimal_'.strtolower($history->currency)}),"0.01",$history->{'decimal_'.strtolower($history->currency)}),$history->trade_total_sell,$history->{'decimal_'.strtolower($history->currency)}), $history->{'decimal_'.strtolower($history->currency)});
			}
			$i++;
			
		}
		
		$response = array(
			"historys_count" => $historys_count,
			"historys" => $result,
			"setting" => $setting,
		);			
		
		return response()->json($response); 
		
	}

	//내자산 >> 미체결  더보기버튼 기능
    public function show_more_not_concluded(Request $request){
    	$offset = $request->offset;
		$username = Auth::user()->username;
		//미체결 총개수
		$wait_trades_count = DB::table('btc_ads_btc')->where('userid',$username)
		->where(function($qry) {
			$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
		})
		->where(function($qry) {
			$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
		})->orderBy('id','desc')->count();
		
		//미체결 20개 SELECT
		$wait_trades = DB::table('btc_ads_btc')->join('btc_coins','btc_ads_btc.cointype','=','btc_coins.api')
		->where('userid',$username)
		->where(function($qry) {
			$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
		})
		->where(function($qry) {
			$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
		})->select('btc_ads_btc.*','btc_coins.decimal_usd','btc_coins.decimal_btc','btc_coins.decimal_eth','btc_coins.decimal_krw')->orderBy('id','desc')->offset($offset)->limit(10)->get();
		
		$result = array();
		$i = 0;
		foreach($wait_trades as $wait_trade){
			if(strtoupper($wait_trade->currency) == 'USD'){
				$result[$i]['currency'] = 'USDC';
			}else{
				$result[$i]['currency'] = strtoupper($wait_trade->currency);				
			}
			if($wait_trade->type == 'buy'){
				$result[$i]['id'] = $wait_trade->id;
				
				$result[$i]['type'] = $wait_trade->type;
				$result[$i]['type_name'] = __('my_asset.buy');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.gm');
				$result[$i]['lang_order_price'] = __('my_asset.order_price');
				$result[$i]['lang_order_quntity'] = __('my_asset.order_quntity');
				$result[$i]['lang_conclusion_quantity'] = __('my_asset.conclusion_quantity');
				$result[$i]['lang_not_conclusion_quantity'] = __('my_asset.not_conclusion_quantity');
				$result[$i]['lang_now'] = __('my_asset.now');
				$result[$i]['lang_cancel_trade'] = __('my_asset.cancel_trade');
				
				$result[$i]['api'] = strtolower($wait_trade->cointype);
				$result[$i]['symbol'] = strtoupper($wait_trade->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($wait_trade->created_dt))); //만든날짜
				$result[$i]['coinname'] = __('coin_name.'.strtolower($wait_trade->cointype)); //코인이름
				$result[$i]['coin_price'] = number_format($wait_trade->buy_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}); //주문가격
				$result[$i]['coin_amt_total'] = number_format($wait_trade->buy_COIN_amt_total,8); // 주문수량
				$result[$i]['coin_amt_finished'] = number_format($wait_trade->buy_COIN_amt_finished,8); //체결수량
				$result[$i]['coin_amt'] = number_format($wait_trade->buy_COIN_amt,8); //미체결수량
			}else{
				$result[$i]['type'] = $wait_trade->type;
				$result[$i]['type_name'] = __('my_asset.sell');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.pm');
				$result[$i]['lang_order_price'] = __('my_asset.order_price');
				$result[$i]['lang_order_quntity'] = __('my_asset.order_quntity');
				$result[$i]['lang_conclusion_quantity'] = __('my_asset.conclusion_quantity');
				$result[$i]['lang_not_conclusion_quantity'] = __('my_asset.not_conclusion_quantity');
				$result[$i]['lang_now'] = __('my_asset.now');
				$result[$i]['lang_cancel_trade'] = __('my_asset.cancel_trade');
				
				$result[$i]['api'] = strtolower($wait_trade->cointype);
				$result[$i]['symbol'] = strtoupper($wait_trade->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($wait_trade->created_dt))); //만든날짜
				$result[$i]['coinname'] = __('coin_name.'.strtolower($wait_trade->cointype)); //코인이름
				$result[$i]['coin_price'] = number_format($wait_trade->sell_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}); //주문가격
				$result[$i]['coin_amt_total'] = number_format($wait_trade->sell_COIN_amt_total,8); // 주문수량
				$result[$i]['coin_amt_finished'] = number_format($wait_trade->sell_COIN_amt_finished,8); //체결수량
				$result[$i]['coin_amt'] = number_format($wait_trade->sell_COIN_amt,8);//미체결수량
			}
			$i++;
			
		}
		
		$response = array(
			"wait_trades_count" => $wait_trades_count,
			"wait_trades" => $result,
		);		
		
		return response()->json($response); 
		
	}

	//내자산 >> 미체결  더보기버튼 기능
    public function refresh_not_concluded(Request $request){
    	$offset = $request->offset;
		$username = Auth::user()->username;
		
		//미체결 총개수
		$wait_trades_count = DB::table('btc_ads_btc')->where('userid',Auth::user()->username)
		->where(function($qry) {
			$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
		})
		->where(function($qry) {
			$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
		})->orderBy('id','desc')->count();
		
		//미체결 리스트
		$wait_trades = DB::table('btc_ads_btc')->join('btc_coins','btc_ads_btc.cointype','=','btc_coins.api')
		->where('userid',Auth::user()->username)
		->where(function($qry) {
			$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
		})
		->where(function($qry) {
			$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
		})->select('btc_ads_btc.*','btc_coins.decimal_usd','btc_coins.decimal_btc','btc_coins.decimal_eth','btc_coins.decimal_krw')->orderBy('id','desc')->limit(20)->get();
		
		$result = array();
		$i = 0;
		foreach($wait_trades as $wait_trade){
			if(strtoupper($wait_trade->currency) == 'USD'){
				$result[$i]['currency'] = 'USDC';
			}else{
				$result[$i]['currency'] = strtoupper($wait_trade->currency);				
			}
			if($wait_trade->type == 'buy'){
				$result[$i]['id'] = $wait_trade->id;
				
				$result[$i]['type'] = $wait_trade->type;
				$result[$i]['type_name'] = __('my_asset.buy');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.gm');
				$result[$i]['lang_order_price'] = __('my_asset.order_price');
				$result[$i]['lang_order_quntity'] = __('my_asset.order_quntity');
				$result[$i]['lang_conclusion_quantity'] = __('my_asset.conclusion_quantity');
				$result[$i]['lang_not_conclusion_quantity'] = __('my_asset.not_conclusion_quantity');
				$result[$i]['lang_now'] = __('my_asset.now');
				$result[$i]['lang_cancel_trade'] = __('my_asset.cancel_trade');
				
				$result[$i]['api'] = strtolower($wait_trade->cointype);
				$result[$i]['symbol'] = strtoupper($wait_trade->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($wait_trade->created_dt))); //만든날짜
				$result[$i]['coinname'] = __('coin_name.'.strtolower($wait_trade->cointype)); //코인이름
				$result[$i]['coin_price'] = number_format($wait_trade->buy_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}); //주문가격
				$result[$i]['coin_amt_total'] = number_format($wait_trade->buy_COIN_amt_total,8); // 주문수량
				$result[$i]['coin_amt_finished'] = number_format($wait_trade->buy_COIN_amt_finished,8); //체결수량
				$result[$i]['coin_amt'] = number_format($wait_trade->buy_COIN_amt,8); //미체결수량
			}else{
				$result[$i]['id'] = $wait_trade->id;
				
				$result[$i]['type'] = $wait_trade->type;
				$result[$i]['type_name'] = __('my_asset.sell');
				
				$result[$i]['lang_market'] = __('my_asset.market');
				$result[$i]['lang_type_name'] = __('my_asset.pm');
				$result[$i]['lang_order_price'] = __('my_asset.order_price');
				$result[$i]['lang_order_quntity'] = __('my_asset.order_quntity');
				$result[$i]['lang_conclusion_quantity'] = __('my_asset.conclusion_quantity');
				$result[$i]['lang_not_conclusion_quantity'] = __('my_asset.not_conclusion_quantity');
				$result[$i]['lang_now'] = __('my_asset.now');
				$result[$i]['lang_cancel_trade'] = __('my_asset.cancel_trade');
				
				$result[$i]['api'] = strtolower($wait_trade->cointype);
				$result[$i]['symbol'] = strtoupper($wait_trade->cointype);
				
				$result[$i]['created_dt'] = date("m-d H:i:s", strtotime("+9 hours", strtotime($wait_trade->created_dt))); //만든날짜
				$result[$i]['coinname'] = __('coin_name.'.strtolower($wait_trade->cointype)); //코인이름
				$result[$i]['coin_price'] = number_format($wait_trade->sell_coin_price,$wait_trade->{'decimal_'.strtolower($wait_trade->currency)}); //주문가격
				$result[$i]['coin_amt_total'] = number_format($wait_trade->sell_COIN_amt_total,8); // 주문수량
				$result[$i]['coin_amt_finished'] = number_format($wait_trade->sell_COIN_amt_finished,8); //체결수량
				$result[$i]['coin_amt'] = number_format($wait_trade->sell_COIN_amt,8);//미체결수량
			}
			$i++;
			
		}
		
		$response = array(
			"wait_trades_count" => $wait_trades_count,
			"wait_trades" => $result,
		);		
		
		return response()->json($response); 
		
	}
}
