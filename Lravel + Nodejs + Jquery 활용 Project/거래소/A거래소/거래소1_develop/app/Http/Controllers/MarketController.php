<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

use Secure;
use Coin_info;
use My_info;
use Auth;
use Trade;
use DB;
use Log;

class MarketController extends Controller
{
	
	public function __construct()
    {
		//$this->middleware('auth');
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
    public function index($coin){

		$views = view(session('theme').'.'.$this->device.'.'.'market.market');

		$trade_coin = Coin_info::get($coin); // 코인정보 가져오기
		
		if(config('app.country') == 'kr'){
			$hm_cur = 1128;
			$local_currency = 'KRW';
			$trade_local =  bcmul($trade_coin->last_trade_price_usd, $hm_cur, 0);
			$trade_local = number_format($trade_local,0);
		}else if(config('app.country') == 'jp'){
			$hm_cur = 111;
			$local_currency = 'JPY';
			$trade_local =  bcmul($trade_coin->last_trade_price_usd, $hm_cur, 0);
			$trade_local = number_format($trade_local,0);
		}else if(config('app.country') == 'ch'){
			$hm_cur = 6.7;
			$local_currency = 'CNY';
			$trade_local =  bcmul($trade_coin->last_trade_price_usd, $hm_cur, 1);
			$trade_local = number_format($trade_local,1);
		}else if(config('app.country') == 'en'){
			$hm_cur = 1;
			$local_currency = 'USD';
			$trade_local =  bcmul($trade_coin->last_trade_price_usd, $hm_cur, 2);
			$trade_local = number_format($trade_local,2);
		}

		
		$security_lv = Secure::secure_short_verified(); //보안레벨 가져오기

		$market = DB::table('btc_settings')->where('id',session('market_type'))->first();

		if(Auth::check()){
			$username = Auth::user()->username;
			$views->username = $username;
		}

		$views->trade_coin = $trade_coin;
		$views->security_lv = $security_lv;
		$views->market = $market;
		$views->local_currency = $local_currency;
		$views->trade_local = $trade_local;

		/* 공지사항 DB작업 시작 */
		$notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->limit(6)->get();

		$views->notices = $notices;
		/* 공지사항 DB작업 시작 */

		$limit = 15; //매도,매수 리스트 제한 수 지정

		/* 매도 호가창 DB작업 시작 */
		$sub_query1 = DB::table('btc_ads')->selectRaw('sum(sell_COIN_amt) amt, sell_coin_price as price,currency')
					->whereRaw('type = "sell"')->whereRaw('cointype = "'.$coin.'"')->whereRaw('status = "OnProgress"')
					->whereRaw('sell_COIN_amt > 0')->groupBy('sell_coin_price','currency')
					->orderBy('sell_coin_price','asc')->limit($limit)->toSql();
		
		$sell_adss = DB::table(DB::raw('('.$sub_query1.') as a'))->orderByRaw('cast(price as DECIMAL(21,8)) DESC')->get();
		
		$sub_query2 = DB::table('btc_ads')->selectRaw('sum(sell_COIN_amt) amt, sell_coin_price as price')
					->whereRaw('type = "sell"')->whereRaw('cointype = "'.$coin.'"')->whereRaw('status = "OnProgress"')
					->whereRaw('sell_COIN_amt > 0')->groupBy('sell_coin_price','currency')
					->orderBy('sell_coin_price','asc')->limit($limit)->toSql();

		$total_amt_sell = DB::table(DB::raw('('.$sub_query2.') as a'))->selectRaw('sum(amt) as total_amt_sell')->orderByRaw('cast(price as DECIMAL(21,8)) DESC')->first();
		
		if($total_amt_sell == null){
			$sell_ads_cnt = 0;
		}else{
			$sell_ads_cnt = count($sell_adss);
		}

		$views->sell_ads_cnt = $sell_ads_cnt;
		$views->sell_adss = $sell_adss;
		$views->total_amt_sell = $total_amt_sell;
		/* 매도 호가창 DB작업 끝 */

		/* 매수 호가창 DB작업 시작 */
		$sub_query3 = DB::table('btc_ads')->selectRaw('sum(buy_COIN_amt) amt, buy_coin_price as price,currency')
					->whereRaw('type = "buy"')->whereRaw('cointype = "'.$coin.'"')->whereRaw('status = "OnProgress"')
					->whereRaw('buy_COIN_amt > 0')->groupBy('buy_coin_price','currency')
					->orderBy('buy_coin_price','desc')->limit($limit)->toSql();
		
		$buy_adss = DB::table(DB::raw('('.$sub_query3.') as a'))->orderByRaw('cast(price as DECIMAL(21,8)) DESC')->get();
		
		$sub_query4 = DB::table('btc_ads')->selectRaw('sum(buy_COIN_amt) amt, buy_coin_price as price')
					->whereRaw('type = "buy"')->whereRaw('cointype = "'.$coin.'"')->whereRaw('status = "OnProgress"')
					->whereRaw('buy_COIN_amt > 0')->groupBy('buy_coin_price','currency')
					->orderBy('buy_coin_price','desc')->limit($limit)->toSql();

		$total_amt_buy = DB::table(DB::raw('('.$sub_query4.') as a'))->selectRaw('sum(amt) as total_amt_buy')->orderByRaw('cast(price as DECIMAL(21,8)) DESC')->first();
		
		if($total_amt_buy == null){
			$buy_ads_cnt = 0;
		}else{
			$buy_ads_cnt = count($buy_adss);
		}

		$views->buy_ads_cnt = $buy_ads_cnt;
		$views->buy_adss = $buy_adss;
		$views->total_amt_buy = $total_amt_buy;
		/* 매수 호가창 DB작업 끝 */

		$last_trade_kind = DB::table('btc_trades_COIN')->select('last_trade_kind')->where('cointype',$trade_coin->api)->orderBy('id','desc')->first();
		if($last_trade_kind == null){
			$last_trade_kind = '';
		}else{
			$last_trade_kind = $last_trade_kind->last_trade_kind;
		}
		$views->last_trade_kind = $last_trade_kind;

		/*  코인상태바, 차트, 구매-판매박스  DB작업 시작  */

		$num_percent_change_24h = (float)$trade_coin->percent_change_24h;
				 
		$percent_change_24h_indicate_symbol = '';
		$price_change_24h_number_symbol = '';
		$up_down_color = '';
		
		if($num_percent_change_24h > 0) {
			$percent_change_24h_indicate_symbol = '▲';
			$price_change_24h_number_symbol = '+';
			$up_down_color = 'red';
		}else if($num_percent_change_24h < 0) {
			$percent_change_24h_indicate_symbol = '▼';
			$price_change_24h_number_symbol = '-';
			$up_down_color = 'blue';
		}

		$views->percent_change_24h_indicate_symbol = $percent_change_24h_indicate_symbol;
		$views->price_change_24h_number_symbol = $price_change_24h_number_symbol;
		$views->up_down_color = $up_down_color;
		
		/*  코인상태바, 차트, 구매-판매박스  DB작업 끝  */
		
		/*  코인리스트  DB작업 시작  */
		$coins = DB::table('btc_coins')->where('active',1)->where('cointype','<>','cash')->whereRaw("api not in ('divi')")->orderBy('last_trade_price_usd','desc')->get();
		
		$views->coins = $coins;

		/*  코인리스트  DB작업 끝  */
		
		/*  거래기록  DB작업 시작  */
		$trade_historys = DB::table('btc_trades_COIN')->where('cointype',$coin)->orderBy('id','desc')->limit(40)->get();
		
		$views->trade_historys = $trade_historys;
		/*  거래기록  DB작업 끝  */
		
		/*  현재 잔액표시  DB작업 시작  */
		if(Auth::check()){
			$user_current_cash_balance = My_info::get_user_balance_allcoin(Auth::id(), 'usd');
			$user_current_coin_balance = My_info::get_user_balance_allcoin(Auth::id(), $trade_coin->api);
		}else{
			$user_current_cash_balance = 0;
			$user_current_coin_balance = 0;
		}

		$views->user_current_cash_balance = $user_current_cash_balance;
		$views->user_current_coin_balance = $user_current_coin_balance;

		/*  현재 잔액표시  DB작업 끝  */

		/*  대기주문  DB작업 시작  */
		if(Auth::check()){
			$wait_trades = DB::table('btc_ads')->where('userid',Auth::user()->username)->where('cointype',$trade_coin->symbol)
										->where(function($qry) {
											$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
										})
										->where(function($qry) {
											$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
										})->orderBy('id','desc')->limit(20)->get();

			$views->wait_trades = $wait_trades;
		}
		/*  대기주문  DB작업 끝  */

		/*  24시간 거래내역  DB작업 시작  */
		if(Auth::check()){
			$today_historys = DB::table('btc_trades_COIN')
											->where(function($qry) {
												$qry->where('buyer_username', Auth::user()->username)->orWhere('seller_username', Auth::user()->username);
											})
											->where('cointype', $trade_coin->symbol)->whereRaw('created > '.time().' - ( 60 * 60 * 24) ')
											->orderBy('id','desc')->limit(20)->get();
			$views->today_historys = $today_historys;
		}

		/*  24시간 거래내역  DB작업 끝  */

		return $views;
	}

	public function buysell_coin_data(Request $request){
		
		if(Auth::user()->status == 2){

			$response = array(
				"data" => 10,
			);

			return response()->json($response); 
		}
		
		$trade_coin_symbol = $request->symbol;
		$type =  $request->order_type;
		$currency = $request->coin_currency;
		$user_current_cash_balance = My_info::get_user_balance_allcoin(Auth::id(),"usd");
		$current_coin_price = Coin_info::get_current_coin_price3($currency,$trade_coin_symbol,"");

		$market = DB::table('btc_settings')->where('id',session('market_type'))->first();
	
		if($type == 'buy'){
			$coin_address = My_info::walletinfo(Auth::id(), "address_btc");

			if($currency == "KRW") {
				$trade_buy_krw_amt = $request->buy_cash_amt;
				$trade_buy_usd_amt = "0";
				$trade_buy_btc_amt = "0";
				$trade_buy_amt = $request->buy_cash_amt;
			} else if( $currency == "USD") {
				$trade_buy_krw_amt = "0";
				$trade_buy_usd_amt = $request->buy_cash_amt;
				$trade_buy_btc_amt = "0";
				$trade_buy_amt = $request->buy_cash_amt;
			} else if( $currency == "BTC") {
				$trade_buy_krw_amt = "0";
				$trade_buy_usd_amt = "0";
				$trade_buy_btc_amt = $request->buy_cash_amt;
				$trade_buy_amt = $request->buy_cash_amt;
			}

			$buy_coin_amt = $request->max_amount;
			$buy_coin_price = $request->btc_price;
			
			$trade_coin_total_amount = bcmul($buy_coin_amt , $buy_coin_price ,17);
			$fee = 	bcmul(bcmul($trade_coin_total_amount, $market->buy_comission, 17) , 0.01 ,17 );						
			$trade_coin_total_amount_with_fee = bcadd($trade_coin_total_amount , $fee, 17);	
			
			
		
			
			if($trade_coin_total_amount_with_fee >= $user_current_cash_balance) {  $data = 0;  }
			else if($buy_coin_amt == 0) {  $data = 2;   }
			else if($buy_coin_amt < 0) {  $data = 2;   }
			else if($buy_coin_price < 0) {  $data = 2;   }
			else if($buy_coin_price == 0) {  $data = 2;   }
			//else if(($buy_coin_price > $current_coin_price*1.2 || $buy_coin_price < $current_coin_price*0.8) && $type == 'buy') {  $data = 4;   } // 시세보다 10% 이상 싸게 사려고 했을경우
			else {// 구매 조건 처리 if	
				
				// 즉시 거래처리를 위해  last_id 를 기록
				$last_id = DB::table('btc_ads')->insertGetId([
					'uid' => Auth::id(),
					'userid' => Auth::user()->username,
					'status' => 'OnProgress',
					'cointype' => strtolower($trade_coin_symbol),
					'type' => $type,
					'payment_method' => 'cash',
					'currency' => $currency,
					'trade_buy_usd_amt' => $trade_buy_usd_amt,
					'trade_buy_krw_amt' => $trade_buy_krw_amt,
					'trade_buy_btc_amt' => $trade_buy_btc_amt,
					'buy_COIN_amt' => $buy_coin_amt,
					'buy_COIN_amt_total' => $buy_coin_amt,
					'buy_coin_price' => $buy_coin_price,
					'bitcoin_address' => $coin_address,
					'created' => time(),
					'updated' => time(),
					'created_dt' => DB::raw('now()'),
					'updated_dt' => DB::raw('now()'),
				]);
			
				// 결제 금액 + fee를 Pending 처리
				DB::table('btc_users_addresses')->where('uid',Auth::id())->decrement('pending_received_balance_'.strtolower($currency), $trade_coin_total_amount_with_fee);
		
				// 즉시 거래 처리
				$trade_result =  Trade::Market_buy_execute_trade( $last_id );
				$data = 1;
			}
		}else if($type == 'sell'){
			$coin_address = My_info::walletinfo(Auth::id(),"address_btc");
			$sell_coin_amt = $request->max_amount;
			$sell_coin_price = $request->btc_price;
			
			if($currency == "KRW") {
				$trade_sell_krw_amt = $sell_coin_price * $sell_coin_amt;
				$trade_sell_usd_amt = "0";
				$trade_sell_btc_amt = "0";
				$trade_sell_amt = $sell_coin_price * $sell_coin_amt;
			} else if( $currency == "USD") {
				$trade_sell_krw_amt = "0";
				$trade_sell_usd_amt = bcmul($sell_coin_price , $sell_coin_amt,13);
				$trade_sell_btc_amt = "0";
				$trade_sell_amt = $sell_coin_price * $sell_coin_amt;
			} else if( $currency == "BTC") {
				$trade_sell_krw_amt = "0";
				$trade_sell_usd_amt = "0";
				$trade_sell_btc_amt = $sell_coin_price * $sell_coin_amt;
				$trade_sell_amt = $sell_coin_price * $sell_coin_amt;
			}
			
			$coin_balance = My_info::get_user_balance_allcoin(Auth::id(), strtolower($trade_coin_symbol));

			if( $sell_coin_amt > $coin_balance) { $data = 0; }
			else if($sell_coin_amt == 0) {  $data = 2;   }
			else if($sell_coin_amt < 0) {  $data = 2;   }
			else if($sell_coin_price < 0) {  $data = 2;   }
			else if($sell_coin_price == 0) {  $data = 2;   }
			//else if(($sell_coin_price > $current_coin_price*10 || $sell_coin_price < $current_coin_price*0.1) && $type == 'sell') {  $data = 5;   } // 시세보다 10% 이상 비싸게 팔려고 했을경우
			else {// 구매 조건 처리 if
				
				// 즉시 거래처리를 위해  last_id 를 기록
				$last_id = DB::table('btc_ads')->insertGetId([
					'uid' => Auth::id(),
					'userid' => Auth::user()->username,
					'status' => 'OnProgress',
					'cointype' => strtolower($trade_coin_symbol),
					'type' => $type,
					'payment_method' => 'cash',
					'currency' => $currency,
					'trade_sell_usd_amt' => $trade_sell_usd_amt,
					'trade_sell_krw_amt' => $trade_sell_krw_amt,
					'trade_sell_btc_amt' => $trade_sell_btc_amt,
					'sell_COIN_amt' => $sell_coin_amt,
					'sell_COIN_amt_total' => $sell_coin_amt,
					'sell_coin_price' => $sell_coin_price,
					'bitcoin_address' => $coin_address,
					'created' => time(),
					'updated' => time(),
					'created_dt' => DB::raw('now()'),
					'updated_dt' => DB::raw('now()'),
					
				]);

				$update = DB::table('btc_users_addresses')->where('uid',Auth::id())->decrement('pending_received_balance_'.strtolower($trade_coin_symbol), $sell_coin_amt);
																										
				$trade_result = Trade::Market_sell_execute_trade( $last_id );
				$data = 1;
			}
		}

		$response = array(
			"trade_result" => $trade_result,
			"data" => $data,
		);

		return response()->json($response); 
	}

	public function refresh_user_data(Request $request){
		$cointype = strtolower($request->cointype);
		$trade_coin = Coin_info::get_coin_info($cointype);

		$currency = $request->currency;

		$response = array(
			'currency_balance' => My_info::get_user_balance_allcoin(Auth::id(),"usd"),
			'coin_balance' => My_info::get_user_balance_allcoin(Auth::id(),strtolower($cointype)),
			'currency_balance_front' => number_format(My_info::get_user_balance_allcoin(Auth::id(),"usd"), $trade_coin->decimal_usd),
			'coin_balance_front' => number_format(My_info::get_user_balance_allcoin(Auth::id(),strtolower($cointype)),8),
		);

		return response()->json($response); 
	}

	public function refresh_user_readyorder(Request $request){
		$trade_coin_symbol = strtolower($request->cointype);
		$trade_coin = Coin_info::get_coin_info($trade_coin_symbol);

		$btc_adss = DB::table('btc_ads')->where('userid', Auth::user()->username)->where('cointype',$trade_coin_symbol)
		->where(function($qry){
			$qry->where('status', 'OnProgress')->orWhere('status', 'CancelRequest');
		})
		->where(function($qry){
			$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
		})
		->orderBy('id','desc')->limit(20)->get();

		$data = array();

		foreach($btc_adss as $i => $btc_ads){
			$ads_price = 0;
			$ads_amount = 0;
			$ads_percent = 0;
			$ads_total_amount = 0;
			if( $btc_ads->type == 'buy' ) { // 구매일때
				$ads_price = $btc_ads->buy_coin_price;
				$ads_amount = $btc_ads->buy_COIN_amt;
				$ads_percent = $btc_ads->trade_percentage;
				$ads_total_amount = $ads_price * $ads_amount;
			} else { // 판매일때
				$ads_price = $btc_ads->sell_coin_price;
				$ads_amount = $btc_ads->sell_COIN_amt;
				$ads_percent = $btc_ads->trade_percentage;
				$ads_total_amount = $ads_price * $ads_amount;
			}

			$data[$i]['id'] = $btc_ads->id;
			$data[$i]['type'] = $btc_ads->type;
			$data[$i]['cointype'] = strtolower($btc_ads->cointype);
			$data[$i]['local_time'] = $btc_ads->created_dt;
			$data[$i]['coinname'] = __('coin_name.'.strtolower($btc_ads->cointype))."(".strtoupper($btc_ads->cointype).")";
			$data[$i]['type_name'] = __('market.'.$btc_ads->type);
			$data[$i]['ads_price'] = number_format($ads_price,$trade_coin->decimal_usd);
			$data[$i]['ads_amount'] = number_format($ads_amount,8)." ".strtoupper($btc_ads->cointype);
			$data[$i]['ads_percent'] = number_format($ads_percent,2);
			$data[$i]['ads_total_amount'] = number_format($ads_total_amount,$trade_coin->decimal_usd);
			$data[$i]['lang_status'] = __('market.'.$btc_ads->status);
			$data[$i]['status'] = $btc_ads->status;
		}
		

		return response()->json($data);
	}

	public function refresh_user_history(Request $request){
		$trade_coin_symbol = strtolower($request->cointype);
		$trade_coin = Coin_info::get_coin_info($trade_coin_symbol);
		$username =  Auth::user()->username;

		$btc_trade_COINs = DB::table('btc_trades_COIN')
						->where(function($qry){
							$qry->where('buyer_username', Auth::user()->username)->orWhere('seller_username', Auth::user()->username);
						})
						->where('cointype',$trade_coin_symbol)
						->whereRaw('created > '.time().' - ( 60 * 60 * 24)')
						->orderBy('id','desc')->limit(20)->get();
		$data = array();
		foreach($btc_trade_COINs as $i => $btc_trade_COIN){
			$trade_type = "";
			$trade_price = 0;
			$trade_amount = 0;
			$trade_percent = 0;
			$trade_total_amount = 0;

			if( $btc_trade_COIN->buyer_username == $username && $btc_trade_COIN->seller_username == $username ){
				$trade_type = "self_trade";
				$trade_price = $btc_trade_COIN->buy_coin_price;
				$trade_amount = $btc_trade_COIN->contract_coin_amt;
				$trade_total_amount = $btc_trade_COIN->trade_usd_amt_buy;
			}else if( $btc_trade_COIN->buyer_username == $username ) { // 구매일때
				$trade_type = "buy";
				$trade_price = $btc_trade_COIN->buy_coin_price;
				$trade_amount = $btc_trade_COIN->contract_coin_amt;
				$trade_total_amount = $btc_trade_COIN->trade_usd_amt_buy;
			} else { // 판매일때
				$trade_type = "sell";
				$trade_price = $btc_trade_COIN->sell_coin_price;
				$trade_amount = $btc_trade_COIN->contract_coin_amt;
				$trade_total_amount = $btc_trade_COIN->trade_usd_amt_sell;
			} 

			$data[$i]['trade_date'] = $btc_trade_COIN->created_dt;
			$data[$i]['type'] = $trade_type;
			$data[$i]['cointype'] = strtolower($btc_trade_COIN->cointype);
			$data[$i]['coinname'] = __('coin_name.'.strtolower($btc_trade_COIN->cointype))."(".strtoupper($btc_trade_COIN->cointype).")";
			$data[$i]['type_name'] = __('market.'.$trade_type);
			$data[$i]['trade_price'] = number_format($trade_price,$trade_coin->decimal_usd);
			$data[$i]['trade_amount'] = number_format($trade_amount,8)." ".strtoupper($btc_trade_COIN->cointype);
			$data[$i]['trade_total_amount'] = number_format($trade_total_amount,$trade_coin->decimal_usd);
		}

		return response()->json($data);
	}

	public function trade_cancel(Request $request){
		$id = $request->id;

		DB::table('btc_ads')->where('id',$id)->update([
			"status" => 'CancelRequest',
			"updated" => time(),
			"updated_dt" => DB::raw('now()'),
		]);

		$market_cance_buy_result = Trade::Market_cancel_buy_order();
		$market_cance_sell_result = Trade::Market_cancel_sell_order();

		$response = array(
			"message" => __('market.cancel_success'),
		);

		return response()->json($response);
	}

	public function sess_id(Request $request){
		if(Auth::check()){
			$response = array(
				"uid" => Auth::id(),
			);
		}else{
			$response = array(
				"uid" => 0,
			);
		}
		return response()->json($response);
	}

}
