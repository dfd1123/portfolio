<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Secure;
use Coin_info;
use My_info;
use Auth;
use AirDrop;
use Trade_new;
use DateTime;
use Redirect;
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
		}else if($standard_symbol[1] == 'krw'){
			$currency_symbol = 'krw';
			$symbol_text = '원';
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
		}else{
			$hm_cur = 1134;
			$hm_decimal = 0;
			$local_currency = 'KRW';
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
		//$notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->limit(6)->get();
		$notices = DB::connection('mysql_sub')->table('btc_notice_kr')->orderBy('created','desc')->limit(6)->get();

		$views->notices = $notices;
		/* 공지사항 DB작업 시작 */

		/* 커뮤니티 코인게시판 DB SELECT 시작 */
		$comunity_exist = DB::connection('mysql_sub')->table('comunity_board')->where('status',1)->where('bo_table', $trade_coin->api)->exists();

		$views->comunity_exist = $comunity_exist;

		if($comunity_exist){
			$comunity_lists = DB::connection('mysql_sub')->table($coin.'_board_'.config('app.country'))->orderBy('created_at','desc')->limit(10)->get();
			$board_lists = array();
        	$re_board_lists = array();
			foreach($comunity_lists as $board){
				if($board->re_id == NULL){
					array_push($board_lists, $board);
				}else{
					array_push($re_board_lists, $board);
				}
			}

			$views->comunity_lists = $comunity_lists;
			$views->board_lists = $board_lists;
			$views->re_board_lists = $re_board_lists;
		}
		/* 커뮤니티 코인게시판 DB SELECT 끝 */

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
			$price_change_24h_number_symbol = '';
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

		/*  잔고 및 자산현황 시작  */
		if(Auth::check()){
			//자산 현황
			$coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();

			$total_holding = 0; // 총 보유자산
			$total_buying = 0;
			$total_trading = 0;
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
				//$coin_trading_price = bcmul($coin_buy_amt, $coin->price_krw, 0); // 코인 보유 양 * 해당코인 가격  = 매수평가금액
				$coin_balance_price = bcmul($coin_balance, $coin->price_krw,0); // 코인 보유 양 * 해당코인 가격 = 평가금액

				if($coin_buying_price != 0){
					$coin_buy_percent = bcmul(bcdiv(bcsub($coin_balance_price, $coin_buying_price,0),$coin_buying_price,4),"100",2); //코인별 평가손익
					$coin_eval_revenue = bcsub($coin_balance_price,$coin_buying_price,0);
				}else{
					$coin_buy_percent = 0;
					$coin_eval_revenue = 0;
				}

				$total_buying = bcadd($total_buying,$coin_buying_price,0); //총 매수금액
				//$total_trading = bcadd($total_trading,$coin_trading_price,0);// 총 매수코인 현재시세
				$total_holding = bcadd($total_holding,$coin_balance_price,0); // 총 보유자산

				$result[$coin->api]['balance'] = $coin_balance; //코인별 보유수량
				$result[$coin->api]['avg'] = $coin_buy_avg; //코인별 평균가 금액
				$result[$coin->api]['buying'] = $coin_buying_price; //코인별 매수 금액
				$result[$coin->api]['eval'] = $coin_balance_price; //코인별 평가 금액
				$result[$coin->api]['eval_revenue'] = $coin_eval_revenue; //코인별 평가 수익
				$result[$coin->api]['eval_percent'] =  $coin_buy_percent; //코인별 평가손익
			}

			if($total_buying != 0){ //코인 총 매수금액이 0일때 0으로 다 대체
				$total_eval_revenue = bcsub($total_holding,$total_buying,0); //총 평가수익
				$total_eval_percent = bcmul(bcdiv($total_eval_revenue,$total_buying,4),"100",2); //총 평가수익률
			}else{
				$total_eval_revenue = 0; //총 평가수익
				$total_eval_percent = 0; //총 평가수익률
			}
			$views->total_buying = $total_buying; //총 매수금액
			$views->total_holding = $total_holding; //총 평가금액
			$views->total_eval_revenue = $total_eval_revenue; //총 평가수익
			$views->total_eval_percent = $total_eval_percent; //총 평가수익
			$views->coin_balance_krw = $coin_balance_krw; //보유 usdc 자산
			$views->total_asset = bcadd($total_holding, $coin_balance_krw,0); //총 보유자산
			$views->result = $result; //코인별 보유정보

			/*  잔고 및 자산현황 끝  */
		}

		/* 이벤트 부분 */
		$event_info = new \stdClass();
		$event_info->status = false;
		$event_info->number = 0;
		$event_info->productName = '';
		$winning_Array = array();

		
		date_default_timezone_set('Asia/Seoul');

		$now = date("Y-m-d");
		
		$etc = DB::connection('mysql_sub')->table('btc_events')->where('active',1)->where('category', 'n')->whereDate('start_time','<=', $now)->whereDate('end_time','>=', $now)->first();
			
		if(isset($etc)) {
			$btc_events_n_data = DB::table('btc_events_n')->where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'),'=',DB::raw('DATE_FORMAT(now(), "%Y-%m-%d")'))->first();

			if(isset($btc_events_n_data)) {

				$json_data = json_decode($btc_events_n_data->json_data, true);

				if(isset($json_data) && is_array($json_data)) {

					if(Auth::check()){
						if(!$btc_events_n_data->all_status && (Auth::id() != 137237) && (Auth::id() != 5269)) {
							$trades_array = DB::select('select * from
							(select @num:=@num+1 as num, b.*
							from (select @num:=0) a, html.btc_trades_COIN_btc b 
							where DATE_FORMAT(b.created_dt, "%Y-%m-%d") = DATE_FORMAT(now(), "%Y-%m-%d") and 
							b.contract_coin_amt >= 1000 order by id asc) c
							where c.buyer_uid = :uid order by num asc', ['uid' => Auth::id()]);

							foreach ($trades_array as $trade) {
								
								for($i = 0; $i < sizeof($json_data); $i++) {
									if(!$json_data[$i]['status'] && $json_data[$i]['number'] == $trade->num) {
										$event_info->status = true;
										$event_info->number = $json_data[$i]['number'];
										$event_info->productName = $json_data[$i]['productName'];
										// btc_events update 해야함. 당첨된 번호 true 처리함.
										$json_data[$i]['status'] = true;
										$json_data[$i]['uid'] = Auth::id();
										$json_data[$i]['nickname'] = Auth::user()->nickname;
									}
								}

								if($event_info->status) {
									DB::table('btc_events_n')->where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'),'=',DB::raw('DATE_FORMAT(now(), "%Y-%m-%d")'))->update(['json_data' => json_encode($json_data)]);
									break;
								}
							}
						}
					}

				
					for($i = 0; $i < sizeof($json_data); $i++) {	
						if($json_data[$i]['status']) {
							array_push($winning_Array, '▶ '.$json_data[$i]['number'].'번째 행운 이벤트 당첨자 : '.$json_data[$i]['nickname'].' ');
						}
					}

					$winners = $btc_events_n_data->winners;
	
					if(count($winning_Array) >= $winners && !$btc_events_n_data->all_status) {
						DB::table('btc_events_n')->where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'),'=',DB::raw('DATE_FORMAT(now(), "%Y-%m-%d")'))->update(['all_status' => true]);
					}
				}
			}

			$event_info->winning_Array = $winning_Array;
		}
		

		$views->event_info = $event_info;

		/* 이벤트 부분 */

		$views->today = new DateTime();

		return $views;
	}

	public function buysell_coin_data(Request $request){
		$trade_coin_symbol = $request->symbol;
		$type =  $request->order_type;
		$currency = $request->coin_currency;

		$market = DB::table('btc_settings')->where('id',session('market_type'))->first();
		$trade_coin_info = Coin_info::get_coin_info(strtolower($trade_coin_symbol));
		$standard_info = Coin_info::get(strtolower($currency)); //사용할 코인
		$trade_result = "";

		/* 이벤트 부분 */
		$event_info = new \stdClass();
		$event_info->status = false;
		$event_info->number = 0;
		$event_info->productName = '';

		if($type == 'buy'){
			DB::beginTransaction();
			try {
				$possible_balance_row = DB::table('btc_users_addresses')->where('uid',Auth::id())->sharedLock()->first();
				$user_current_cash_balance = bcadd($possible_balance_row->{'available_balance_'.strtolower($standard_info->api)} , $possible_balance_row->{'pending_received_balance_'.strtolower($standard_info->api)},17); // 자기 사용가능한 코인 잔액
				//$user_current_cash_balance = My_info::get_user_balance_allcoin(, $standard_info->api ); // 자기 사용가능한 코인 잔액

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
				else if($trade_coin_info->market == 'sports' && $buy_coin_price <= 20) {  $data = 6;   }
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
						
				}
				$commit_flag = DB::commit();
			} catch (Exception $e) {
				$rollback_flag = DB::rollback();
				info("rollback : ".$rollback_flag);

				info($e);
			}
			// 즉시 거래 처리
            if (isset($last_id)) {
				$trade_result =  Trade_new::Market_buy_execute_trade($last_id);
				$data = 1;

				if(Auth::check()){

					date_default_timezone_set('Asia/Seoul');

					$now = date("Y-m-d");
					
					$etc = DB::connection('mysql_sub')->table('btc_events')->where('active',1)->where('category', 'n')->whereDate('start_time','<=', $now)->whereDate('end_time','>=', $now)->first();
					
					if(isset($etc)) {
		
						$btc_events_n_data = DB::table('btc_events_n')->where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'),'=',DB::raw('DATE_FORMAT(now(), "%Y-%m-%d")'))->first();
		
						if(isset($btc_events_n_data)) {

							if(!$btc_events_n_data->all_status) {
								$json_data = json_decode($btc_events_n_data->json_data, true);
								
								if(isset($json_data) && is_array($json_data)) {
									if((Auth::id() != 137237) && (Auth::id() != 5269)) {
										$trades_array = DB::select('select * from
										(select @num:=@num+1 as num, b.*
										from (select @num:=0) a, html.btc_trades_COIN_btc b 
										where DATE_FORMAT(b.created_dt, "%Y-%m-%d") = DATE_FORMAT(now(), "%Y-%m-%d") and 
										b.contract_coin_amt >= 1000 order by id asc) c
										where c.buyer_uid = :uid order by num asc', ['uid' => Auth::id()]);
						
										foreach ($trades_array as $trade) {
											
											for($i = 0; $i < sizeof($json_data); $i++) {
												if(!$json_data[$i]['status'] && $json_data[$i]['number'] == $trade->num) {
													$event_info->status = true;
													$event_info->number = $json_data[$i]['number'];
													$event_info->productName = $json_data[$i]['productName'];
													$event_info->nickName = Auth::user()->nickname;
													// btc_events update 해야함. 당첨된 번호 true 처리함.
													$json_data[$i]['status'] = true;
													$json_data[$i]['uid'] = Auth::id();
													$json_data[$i]['nickname'] = Auth::user()->nickname;
												}
											}
						
											if($event_info->status) {
												DB::table('btc_events_n')->where(DB::raw('DATE_FORMAT(date, "%Y-%m-%d")'),'=',DB::raw('DATE_FORMAT(now(), "%Y-%m-%d")'))->update(['json_data' => json_encode($json_data)]);
												break;
											}
										}
									}
								}
							}
						}
					}
				}
				/* 이벤트 부분 */

            }
		}else if($type == 'sell'){
			DB::beginTransaction();
			try {
				$possible_balance_row = DB::table('btc_users_addresses')->where('uid',Auth::id())->sharedLock()->first();
				$coin_balance = bcadd($possible_balance_row->{'available_balance_'.strtolower($trade_coin_symbol)} , $possible_balance_row->{'pending_received_balance_'.strtolower($trade_coin_symbol)},8); // 자기 사용가능한 코인 잔액
				//$coin_balance = My_info::get_user_balance_allcoin(Auth::id(), strtolower($trade_coin_symbol)); //잔액

				$sell_coin_amt = $request->max_amount; // 판매할 양
				$sell_coin_price = $request->btc_price; // 판매할 가격

				$trade_coin_total_amount = bcmul($sell_coin_amt,$sell_coin_price,17);

				if( $sell_coin_amt > $coin_balance) { $data = 0; }
				else if($sell_coin_amt == 0) {  $data = 2;   }
				else if($sell_coin_amt < 0) {  $data = 2;   }
				else if($sell_coin_price < 0) {  $data = 2;   }
				else if($sell_coin_price == 0) {  $data = 2;   }
				else if($trade_coin_info->market == 'sports' && $sell_coin_price <= 20) {  $data = 6;   }
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
						
				}
				$commit_flag = DB::commit();
			} catch (Exception $e) {
				$rollback_flag = DB::rollback();
				info("rollback : ".$rollback_flag);

				info($e);
			}
            if (isset($last_id)) {
				$trade_result = Trade_new::Market_sell_execute_trade($last_id);
				$data = 1;
            }
		}

		AirDrop::drop(Auth::id(), 'transaction');

		$response = array(
			"trade_result" => $trade_result,
			"data" => $data,
			"event_info" => $event_info,
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
		DB::beginTransaction();
		try{
			$ad_info = DB::table('btc_ads_btc')->where('id',$id)->where('status','OnProgress')->where('uid',Auth::id())->sharedLock()->first();

			$market = DB::table('btc_settings')->where('id',session('market_type'))->first();

			if(isset($ad_info)){

				if($ad_info->type == 'buy'){
					$process_currency = $ad_info->currency;
					$trade_coin_symbol = $ad_info->cointype;
			
					$buy_COIN_amt = $ad_info->buy_COIN_amt;
					$buy_coin_price = $ad_info->buy_coin_price;
			

					$pending_usd_amt = 0;
					$pending_usd_amt_with_fee = 0;

					$pending_usd_amt = bcmul($buy_COIN_amt , $buy_coin_price,17 ); // 구매금액
					$pending_usd_amt_with_fee =  bcadd($pending_usd_amt , bcmul(bcmul( $pending_usd_amt , $market->buy_comission , 17) , '0.01' ,17 ),17) ;
					
					$buyer_uid = $ad_info->uid;

					DB::table('btc_ads_btc')->where('id',$id)->update([
						"status" => 'Cancel',
						"updated" => time(),
						"updated_dt" => DB::raw('now()'),
					]);
					
					DB::table('btc_users_addresses')->where('uid', $buyer_uid)->increment('pending_received_balance_'.strtolower($process_currency), $pending_usd_amt_with_fee);

					
				}else{
					$process_currency = $ad_info->currency;
					$trade_coin_symbol = $ad_info->cointype;
					$pending_COIN_amt = $ad_info->sell_COIN_amt;
					$seller_uid = $ad_info->uid;

					DB::table('btc_ads_btc')->where('id',$id)->update([
						"status" => 'Cancel',
						"updated" => time(),
						"updated_dt" => DB::raw('now()'),
					]);

					DB::table('btc_users_addresses')->where('uid', $seller_uid)->increment('pending_received_balance_'.strtolower($trade_coin_symbol), $pending_COIN_amt);

					
				}
				$response = array(
					"message" => "주문취소가 완료 되었습니다.",
				);
			}else{
				$response = array(
					"message" => "해당 주문이 이미 체결되었거나 취소 완료되었습니다.",
				);
			}
			$commit_flag = DB::commit();
		} catch (Exception $e) {
			$rollback_flag = DB::rollback();

			info($e);
		}

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

	public function refresh_user_asset(Request $request){
		//보유 코인 페이지
		$coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();

		$total_holding = 0; // 총 보유자산
		$total_buying = 0;
		$total_trading = 0; //거래한 코인 현시세
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
			//$coin_trading_price = bcmul($coin_buy_amt, $coin->price_krw, 0); // 코인 보유 양 * 해당코인 가격  = 매수평가금액
			$coin_balance_price = bcmul($coin_balance, $coin->price_krw,0); // 코인 보유 양 * 해당코인 가격 = 평가금액

			if($coin_buying_price != 0){
				$coin_buy_percent = bcmul(bcdiv(bcsub($coin_balance_price, $coin_buying_price,0),$coin_buying_price,4),"100",2); //코인별 평가손익
				$coin_eval_revenue = bcsub($coin_balance_price,$coin_buying_price,0);
			}else{
				$coin_buy_percent = 0;
				$coin_eval_revenue = 0;
			}

			$total_buying = bcadd($total_buying,$coin_buying_price,0); //총 매수금액
			//$total_trading = bcadd($total_trading,$coin_trading_price,0);// 총 매수코인 현재시세
			$total_holding = bcadd($total_holding,$coin_balance_price,0); // 총 보유자산



			$result[$coin->api]['balance'] = $coin_balance; //코인별 보유수량
			$result[$coin->api]['avg'] = $coin_buy_avg; //코인별 평균가 금액
			$result[$coin->api]['buying'] = $coin_buying_price; //코인별 매수 금액
			$result[$coin->api]['eval'] = $coin_balance_price; //코인별 평가 금액
			$result[$coin->api]['eval_revenue'] = $coin_eval_revenue; //코인별 평가 수익
			$result[$coin->api]['eval_percent'] =  $coin_buy_percent; //코인별 평가손익
		}

		if($total_buying != 0){ //코인 총 매수금액이 0일때 0으로 다 대체
			$total_eval_revenue = bcsub($total_holding,$total_buying,0); //총 평가수익
			$total_eval_percent = bcmul(bcdiv($total_eval_revenue,$total_buying,4),"100",2); //총 평가수익률
		}else{
			$total_eval_revenue = 0; //총 평가수익
			$total_eval_percent = 0; //총 평가수익률
		}

		$response = array(
			"total_buying" => $total_buying,
			"total_holding" => $total_holding,
			"total_eval_revenue" => $total_eval_revenue,
			"total_eval_percent" => $total_eval_percent,
			"coin_balance_krw" => $coin_balance_krw,
			"total_asset" => bcadd($total_holding, $coin_balance_krw,0),
			"result" => $result
		);

		return response()->json($response);
	}
}
