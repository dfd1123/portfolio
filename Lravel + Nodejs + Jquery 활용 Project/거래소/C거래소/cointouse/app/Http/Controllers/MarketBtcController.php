<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Secure;
use Coin_info;
use My_info;
use Auth;
use Trade_new;
use DB;
use Log;

class MarketBtcController extends Controller
{
    public function __construct()
    {
		//$this->middleware('auth');
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }

    public function index($coin){

		$views = view(session('theme').'.'.$this->device.'.'.'market_btc.market');
		$query_string=getenv("QUERY_STRING");
        $pagename = $_SERVER['REQUEST_URI'];
        $pagename = str_replace($query_string,'',$pagename);
        $pagename = str_replace('?','',$pagename);
        $pagename = explode('/',$pagename);

		$standard_symbol = explode('_',$pagename[1]);

		if($standard_symbol[1] == 'usdc'){
			$currency_symbol = 'usd';
			$symbol_text = 'USDC';
		}else{
			$currency_symbol = $standard_symbol[1];
			$symbol_text = strtoupper($standard_symbol[1]);
		}

		$standard_info = Coin_info::get($currency_symbol);

		$trade_coin = Coin_info::get($coin); // 코인정보 가져오기

		$local_currency = 'KRW';
		$hm_decimal = 0;

		if(config('app.country') == 'kr'){
			$hm_cur = 1134;
			$hm_decimal = 0;
			$local_currency = 'KRW';
			$trade_local =  bcmul(bcmul($trade_coin->{'last_trade_price_'.$standard_info->api}, $hm_cur, 10),$standard_info->last_trade_price_usd, 10);
			$trade_local = number_format($trade_local, $hm_decimal);
		}else if(config('app.country') == 'jp'){
			$hm_cur = 112;
			$hm_decimal = 0;
			$local_currency = 'JPY';
			$trade_local =  bcmul(bcmul($trade_coin->{'last_trade_price_'.$standard_info->api}, $hm_cur, 10),$standard_info->last_trade_price_usd, 10);
			$trade_local = number_format($trade_local, $hm_decimal);
		}else if(config('app.country') == 'ch'){
			$hm_cur = 6.7;
			$hm_decimal = 1;
			$local_currency = 'CNY';
			$trade_local =  bcmul(bcmul($trade_coin->{'last_trade_price_'.$standard_info->api}, $hm_cur, 10),$standard_info->last_trade_price_usd, 10);
			$trade_local = number_format($trade_local, $hm_decimal);
		}else if(config('app.country') == 'en'){
			$hm_cur = 1;
			$hm_decimal = 2;
			$local_currency = 'USD';
			$trade_local =  bcmul(bcmul($trade_coin->{'last_trade_price_'.$standard_info->api}, $hm_cur, 10),$standard_info->last_trade_price_usd, 10);
			$trade_local = number_format($trade_local, $hm_decimal);
		}


		$security_lv = Secure::secure_short_verified(); //보안레벨 가져오기

		$market = DB::table('btc_settings')->where('id',session('market_type'))->first();

		if(Auth::check()){
			$username = Auth::user()->username;
			$views->username = $username;
		}
		$views->symbol_text = $symbol_text;
		$views->standard_info = $standard_info;
		$views->trade_coin = $trade_coin;
		$views->security_lv = $security_lv;
		$views->market = $market;
		$views->local_currency = $local_currency;
		$views->trade_local = $trade_local;
		$views->hm_cur = $hm_cur;
		$views->hm_decimal = $hm_decimal;

		/* 공지사항 DB작업 시작 */
		$notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->limit(6)->get();

		$views->notices = $notices;
		/* 공지사항 DB작업 시작 */

		$limit = 15; //매도,매수 리스트 제한 수 지정

		/* 매도 호가창 DB작업 시작 */
		$sub_query1 = DB::table('btc_ads_btc')->whereRaw('currency = "'.$standard_info->api.'"')->selectRaw('sum(sell_COIN_amt) amt, sell_coin_price as price,currency')
					->whereRaw('type = "sell"')->whereRaw('cointype = "'.$coin.'"')->whereRaw('status = "OnProgress"')
					->whereRaw('sell_COIN_amt > 0')->groupBy('sell_coin_price','currency')
					->orderBy('sell_coin_price','asc')->limit($limit)->toSql();

		$sell_adss = DB::table(DB::raw('('.$sub_query1.') as a'))->orderByRaw('cast(price as DECIMAL(21,8)) DESC')->get();

		$sub_query2 = DB::table('btc_ads_btc')->whereRaw('currency = "'.$standard_info->api.'"')->selectRaw('sum(sell_COIN_amt) amt, sell_coin_price as price')
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
		$sub_query3 = DB::table('btc_ads_btc')->whereRaw('currency = "'.$standard_info->api.'"')->selectRaw('sum(buy_COIN_amt) amt, buy_coin_price as price,currency')
					->whereRaw('type = "buy"')->whereRaw('cointype = "'.$coin.'"')->whereRaw('status = "OnProgress"')
					->whereRaw('buy_COIN_amt > 0')->groupBy('buy_coin_price','currency')
					->orderBy('buy_coin_price','desc')->limit($limit)->toSql();

		$buy_adss = DB::table(DB::raw('('.$sub_query3.') as a'))->orderByRaw('cast(price as DECIMAL(21,8)) DESC')->get();

		$sub_query4 = DB::table('btc_ads_btc')->whereRaw('currency = "'.$standard_info->api.'"')->selectRaw('sum(buy_COIN_amt) amt, buy_coin_price as price')
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

		$last_trade_kind = DB::table('btc_trades_COIN_btc')->select('last_trade_kind')->where('currency','=',$standard_info->api)->where('cointype',$trade_coin->api)->orderBy('id','desc')->first();
		if($last_trade_kind == null){
			$last_trade_kind = '';
		}else{
			$last_trade_kind = $last_trade_kind->last_trade_kind;
		}
		$views->last_trade_kind = $last_trade_kind;

		/*  코인상태바, 차트, 구매-판매박스  DB작업 시작  */

		$num_percent_change_24h = (float)$trade_coin->{'percent_change_24h_'.$standard_info->api};

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
		$coins = DB::table('btc_coins')->where('active',1)->where('cointype','<>','cash')->orderBy('id','asc')->get();

		$views->coins = $coins;

		/*  코인리스트  DB작업 끝  */

		/*  거래기록  DB작업 시작  */
		$trade_historys = DB::table('btc_trades_COIN_btc')->where('currency','=',$standard_info->api)->where('cointype',$coin)->orderBy('id','desc')->limit(40)->get();

		$views->trade_historys = $trade_historys;
		/*  거래기록  DB작업 끝  */

		/*  현재 잔액표시  DB작업 시작  */
		if(Auth::check()){
			$user_current_cash_balance = My_info::get_user_balance_allcoin(Auth::id(), $standard_info->api);
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
			$wait_trades = DB::table('btc_ads_btc')->where('currency','=',$standard_info->api)->where('userid',Auth::user()->username)->where('cointype',$trade_coin->symbol)->where('status', 'OnProgress')
										->where(function($qry) {
											$qry->where('sell_COIN_amt', '>', 0)->orWhere('buy_COIN_amt', '>', 0);
										})->orderBy('id','desc')->limit(20)->get();

			$views->wait_trades = $wait_trades;
		}
		/*  대기주문  DB작업 끝  */

		/*  24시간 거래내역  DB작업 시작  */
		if(Auth::check()){
			$today_historys = DB::table('btc_trades_COIN_btc')
											->where(function($qry) {
												$qry->where('buyer_username', Auth::user()->username)->orWhere('seller_username', Auth::user()->username);
											})
											->where('cointype', $trade_coin->symbol)->where('currency','=',$standard_info->api)->whereRaw('created > '.time().' - ( 60 * 60 * 24) ')
											->orderBy('id','desc')->limit(20)->get();
			$views->today_historys = $today_historys;
		}

		/*  24시간 거래내역  DB작업 끝  */

		return $views;
	}

	public function buysell_coin_data(Request $request){
		$trade_coin_symbol = $request->symbol;
		$type =  $request->order_type;
		$currency = $request->coin_currency;

		$market = DB::table('btc_settings')->where('id',session('market_type'))->first();

		$standard_info = Coin_info::get(strtolower($currency)); //사용할 코인
		$trade_result = '';
		if($type == 'buy'){
			$user_current_cash_balance = My_info::get_user_balance_allcoin(Auth::id(), $standard_info->api ); // 자기 사용가능한 코인 잔액

			$buy_coin_amt = $request->max_amount; // 구매할 양
			$buy_coin_price = $request->btc_price; // 구매할 가격

			$trade_coin_total_amount = bcmul($buy_coin_amt , $buy_coin_price ,17); // 총 가격
			$fee = 	bcmul(bcmul($trade_coin_total_amount, $market->buy_comission, 17) , 0.01 ,17 ); // 수수료
			$trade_coin_total_amount_with_fee = bcadd($trade_coin_total_amount , $fee, 17);	 // 수수료 포함한 가격

			if($trade_coin_total_amount_with_fee >= $user_current_cash_balance) {  $data = 0;  }
			else if($buy_coin_amt == 0) {  $data = 2;   }
			else if($buy_coin_amt < 0) {  $data = 2;   }
			else if($buy_coin_price < 0) {  $data = 2;   }
			else if($buy_coin_price == 0) {  $data = 2;   }
			else {// 구매 조건 처리 if

				// 즉시 거래처리를 위해  last_id 를 기록
				$last_id = DB::table('btc_ads_btc')->insertGetId([
					'uid' => Auth::id(),
					'userid' => Auth::user()->username,
					'status' => 'OnProgress',
					'cointype' => strtolower($trade_coin_symbol),
					'type' => $type,
					'currency' => strtolower($currency),
					'trade_total_buy' => $trade_coin_total_amount_with_fee,
					'buy_COIN_amt' => $buy_coin_amt,
					'buy_COIN_amt_total' => $buy_coin_amt,
					'buy_coin_price' => $buy_coin_price,
					'created' => time(),
					'updated' => time(),
					'created_dt' => DB::raw('now()'),
					'updated_dt' => DB::raw('now()'),
				]);

				// 결제 금액 + fee를 Pending 처리
				DB::table('btc_users_addresses')->where('uid',Auth::id())->decrement('pending_received_balance_'.strtolower($currency), $trade_coin_total_amount_with_fee);

				// 즉시 거래 처리
				$trade_result =  Trade_new::Market_buy_execute_trade( $last_id );
				$data = 1;
			}
		}else if($type == 'sell'){
			$coin_balance = My_info::get_user_balance_allcoin(Auth::id(), strtolower($trade_coin_symbol)); //잔액

			$sell_coin_amt = $request->max_amount; // 판매할 양
			$sell_coin_price = $request->btc_price; // 판매할 가격

			$trade_coin_total_amount = bcmul($sell_coin_amt,$sell_coin_price,17);

			if( $sell_coin_amt > $coin_balance) { $data = 0; }
			else if($sell_coin_amt == 0) {  $data = 2;   }
			else if($sell_coin_amt < 0) {  $data = 2;   }
			else if($sell_coin_price < 0) {  $data = 2;   }
			else if($sell_coin_price == 0) {  $data = 2;   }
			else {// 구매 조건 처리 if

				// 즉시 거래처리를 위해  last_id 를 기록
				$last_id = DB::table('btc_ads_btc')->insertGetId([
					'uid' => Auth::id(),
					'userid' => Auth::user()->username,
					'status' => 'OnProgress',
					'cointype' => strtolower($trade_coin_symbol),
					'type' => $type,
					'currency' => strtolower($currency),
					'trade_total_sell' => $trade_coin_total_amount,
					'sell_COIN_amt' => $sell_coin_amt,
					'sell_COIN_amt_total' => $sell_coin_amt,
					'sell_coin_price' => $sell_coin_price,
					'created' => time(),
					'updated' => time(),
					'created_dt' => DB::raw('now()'),
					'updated_dt' => DB::raw('now()'),
				]);

				$update = DB::table('btc_users_addresses')->where('uid',Auth::id())->decrement('pending_received_balance_'.strtolower($trade_coin_symbol), $sell_coin_amt);

				$trade_result = Trade_new::Market_sell_execute_trade( $last_id );
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

		$currency_balance = My_info::get_user_balance_allcoin(Auth::id(),$currency);
		$coin_balance = My_info::get_user_balance_allcoin(Auth::id(),$trade_coin->api);

		$response = array(
			'currency_balance' => $currency_balance,
			'coin_balance' => $coin_balance,
			'currency_balance_front' => number_format($currency_balance, $trade_coin->{'decimal_'.$currency}),
			'coin_balance_front' => number_format($coin_balance,8),
		);

		return response()->json($response);
	}

	public function refresh_user_readyorder(Request $request){
		$trade_coin_symbol = strtolower($request->cointype);
		$trade_coin = Coin_info::get_coin_info($trade_coin_symbol);

		$currency = $request->currency;

		$btc_adss = DB::table('btc_ads_btc')->where('currency',$currency)->where('userid', Auth::user()->username)->where('cointype',$trade_coin_symbol)->where('status', 'OnProgress')
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
			$data[$i]['ads_price'] = number_format($ads_price,$trade_coin->{'decimal_'.$currency});
			$data[$i]['ads_amount'] = number_format($ads_amount,8)." ".strtoupper($btc_ads->cointype);
			$data[$i]['ads_percent'] = number_format($ads_percent,2);
			$data[$i]['ads_total_amount'] = number_format($ads_total_amount,$trade_coin->{'decimal_'.$currency});
			$data[$i]['lang_status'] = __('market.'.$btc_ads->status);
			$data[$i]['status'] = $btc_ads->status;
		}

		return response()->json($data);
	}

	public function refresh_user_history(Request $request){
		$trade_coin_symbol = strtolower($request->cointype);
		$trade_coin = Coin_info::get_coin_info($trade_coin_symbol);

		$currency = $request->currency;

		$username =  Auth::user()->username;

		$btc_trade_COINs = DB::table('btc_trades_COIN_btc')
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
				$trade_total_amount = $btc_trade_COIN->trade_total_buy;
			}else if( $btc_trade_COIN->buyer_username == $username ) { // 구매일때
				$trade_type = "buy";
				$trade_price = $btc_trade_COIN->buy_coin_price;
				$trade_amount = $btc_trade_COIN->contract_coin_amt;
				$trade_total_amount = $btc_trade_COIN->trade_total_buy;
			} else { // 판매일때
				$trade_type = "sell";
				$trade_price = $btc_trade_COIN->sell_coin_price;
				$trade_amount = $btc_trade_COIN->contract_coin_amt;
				$trade_total_amount = $btc_trade_COIN->trade_total_sell;
			}

			$data[$i]['trade_date'] = $btc_trade_COIN->created_dt;
			$data[$i]['type'] = $trade_type;
			$data[$i]['cointype'] = strtolower($btc_trade_COIN->cointype);
			$data[$i]['coinname'] = __('coin_name.'.strtolower($btc_trade_COIN->cointype))."(".strtoupper($btc_trade_COIN->cointype).")";
			$data[$i]['type_name'] = __('market.'.$trade_type);
			$data[$i]['trade_price'] = number_format($trade_price,$trade_coin->{'decimal_'.$currency});
			$data[$i]['trade_amount'] = number_format($trade_amount,8)." ".strtoupper($btc_trade_COIN->cointype);
			$data[$i]['trade_total_amount'] = number_format($trade_total_amount,$trade_coin->{'decimal_'.$currency});
		}

		return response()->json($data);
	}

	public function trade_cancel(Request $request){
		$id = $request->id;

		DB::table('btc_ads_btc')->where('id',$id)->update([
			"status" => 'CancelRequest',
			"updated" => time(),
			"updated_dt" => DB::raw('now()'),
		]);

		$market_cance_buy_result = Trade_new::Market_cancel_buy_order();
		$market_cance_sell_result = Trade_new::Market_cancel_sell_order();

		$response = array(
			"message" => "주문취소가 완료 되었습니다.",
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
