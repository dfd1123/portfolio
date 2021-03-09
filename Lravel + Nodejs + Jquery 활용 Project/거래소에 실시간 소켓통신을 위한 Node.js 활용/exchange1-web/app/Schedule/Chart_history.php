<?php

namespace App\Schedule;

use DB;
use Settings;

class Chart_history
{
	public static function reset_coinmarketcap_price_all() {
    	
		$url = "https://api.coinmarketcap.com/v1/ticker/?convert=ETH"; // Where you want to post data
		$ch = curl_init();                    // Initiate cURL
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    $output = curl_exec ($ch); // Execute
	    curl_close ($ch); // Close cURL handle  
		$obj = json_decode($output);
		for($i=0;$i<100;$i++){
			$usd_price = floatval($obj[$i]->price_usd) * 1;
			$btc_price = floatval($obj[$i]->price_btc) * 1;
			$eth_price = floatval($obj[$i]->price_eth) * 1;
			
			DB::table('btc_coins')->where('api', strtolower($obj[$i]->symbol))
			->update([
				"last_coinmarketcap_price_usd" => $usd_price,
				"last_coinmarketcap_price_btc" => $btc_price,
				"last_coinmarketcap_price_eth" => $eth_price,
			]);
    	}
    }
    
	public static function update_market_info() {
    	$coins = DB::table('btc_coins')->where('active','1')->where(function ($query){
			$query->where('cointype','coin')->orwhere('cointype','token');
		})->get();
		
		foreach($coins as $coin){
			DB::table('btc_coins')->where('symbol', $coin->symbol)-> update([
	            '24h_volume' => DB::raw("IFNULL( ( SELECT SUM(  `contract_coin_amt` )  24h_volume FROM `btc_trades_COIN` WHERE `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'percent_change_24h' => DB::raw("IFNULL(( price_usd - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN` WHERE `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),0)  )* 100 /NULLIF(price_usd,0) , 0)"),
	            'price_change_24h' => DB::raw("IFNULL( price_usd - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN` WHERE `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),0)  , 0)"),
	            'price_all_24h' => DB::raw("IFNULL( ( SELECT SUM(  `trade_usd_amt_sell` )  price_all_24h FROM `btc_trades_COIN` WHERE `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'opening_price' => DB::raw("IFNULL(( SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN` WHERE `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),0)"),
	        	'closing_price' => DB::raw("IFNULL(( SELECT `buy_coin_price` as closing_price FROM `btc_trades_COIN` WHERE `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id desc limit 1 ),0)"),
	            'min_price' => DB::raw("IFNULL( (select min(`buy_coin_price`) min_price from `btc_trades_COIN` where `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'max_price' => DB::raw("IFNULL( (select max(`buy_coin_price`) max_price  from `btc_trades_COIN` where `cointype` = '".$coin->api."' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'updated_dt' => DB::raw('now()'),
	            'updated' => time(),
	        ]);
		}
    }
	
	public static function update_market_info_new() {
    	$coins = DB::table('btc_coins')->where('active','1')->where(function ($query){
			$query->where('cointype','coin')->orwhere('cointype','token');
		})->get();
		
		foreach($coins as $coin){
			DB::table('btc_coins')->where('symbol', strtoupper($coin->api))-> update([
	            '24h_volume_usd' => DB::raw("IFNULL( ( SELECT SUM(  `contract_coin_amt` )  24h_volume FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'percent_change_24h_usd' => DB::raw("IFNULL(( price_usd - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),price_usd)  )* 100 / NULLIF(price_usd,0) , 0)"),
	            'price_change_24h_usd' => DB::raw("IFNULL( price_usd - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),price_usd)  , 0)"),
	            'price_all_24h_usd' => DB::raw("IFNULL( ( SELECT SUM(  `trade_total_sell` )  price_all_24h FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'opening_price_usd' => DB::raw("IFNULL(( SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),0)"),
	        	'closing_price_usd' => DB::raw("IFNULL(( SELECT `buy_coin_price` as closing_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id desc limit 1 ),0)"),
	            'min_price_usd' => DB::raw("IFNULL( (select min(`buy_coin_price`) min_price from `btc_trades_COIN_btc` where `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'max_price_usd' => DB::raw("IFNULL( (select max(`buy_coin_price`) max_price  from `btc_trades_COIN_btc` where `cointype` = '".$coin->api."' and currency = 'usd' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'updated_dt' => DB::raw('now()'),
	            'updated' => time(),
	        ]);
			
			DB::table('btc_coins')->where('symbol', strtoupper($coin->api))-> update([
	            '24h_volume_btc' => DB::raw("IFNULL( ( SELECT SUM(  `contract_coin_amt` )  24h_volume FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'percent_change_24h_btc' => DB::raw("IFNULL(( price_btc - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),price_btc)  )* 100 / NULLIF(price_btc,0) , 0)"),
	            'price_change_24h_btc' => DB::raw("IFNULL( price_btc - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),price_btc)  , 0)"),
	            'price_all_24h_btc' => DB::raw("IFNULL( ( SELECT SUM(  `trade_total_sell` )  price_all_24h FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'opening_price_btc' => DB::raw("IFNULL(( SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),0)"),
	        	'closing_price_btc' => DB::raw("IFNULL(( SELECT `buy_coin_price` as closing_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id desc limit 1 ),0)"),
	            'min_price_btc' => DB::raw("IFNULL( (select min(`buy_coin_price`) min_price from `btc_trades_COIN_btc` where `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'max_price_btc' => DB::raw("IFNULL( (select max(`buy_coin_price`) max_price  from `btc_trades_COIN_btc` where `cointype` = '".$coin->api."' and currency = 'btc' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'updated_dt' => DB::raw('now()'),
	            'updated' => time(),
	        ]);
			
			DB::table('btc_coins')->where('symbol', strtoupper($coin->api))-> update([
	            '24h_volume_eth' => DB::raw("IFNULL( ( SELECT SUM(  `contract_coin_amt` )  24h_volume FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'percent_change_24h_eth' => DB::raw("IFNULL(( price_eth - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),price_eth)  )* 100 / NULLIF(price_eth,0) , 0)"),
	            'price_change_24h_eth' => DB::raw("IFNULL( price_eth - IFNULL( (SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),price_eth)  , 0)"),
	            'price_all_24h_eth' => DB::raw("IFNULL( ( SELECT SUM(  `trade_total_sell` )  price_all_24h FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR) ),0)"),
	            'opening_price_eth' => DB::raw("IFNULL(( SELECT `buy_coin_price` as opening_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id asc limit 1 ),0)"),
	        	'closing_price_eth' => DB::raw("IFNULL(( SELECT `buy_coin_price` as closing_price FROM `btc_trades_COIN_btc` WHERE `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR ) order by id desc limit 1 ),0)"),
	            'min_price_eth' => DB::raw("IFNULL( (select min(`buy_coin_price`) min_price from `btc_trades_COIN_btc` where `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'max_price_eth' => DB::raw("IFNULL( (select max(`buy_coin_price`) max_price  from `btc_trades_COIN_btc` where `cointype` = '".$coin->api."' and currency = 'eth' and `created` >  UNIX_TIMESTAMP(NOW() - INTERVAL 24 HOUR))  , 0)"),
	            'updated_dt' => DB::raw('now()'),
	            'updated' => time(),
	        ]);
		}
    }
	
    public static function test() {
    	$symbol = 'ETH';
        $resolution = '60';
        $from = $request->from;
        $to = $request->to;
        
        if(strpos($resolution,'D') !== false){
			$resolution_unit = explode('D',$resolution);
			$resolution_timestamp = $resolution_unit[0]*60*60*24;
		}else if(strpos($resolution,'W') !== false){
			$resolution_unit = explode('W',$resolution);
			$resolution_timestamp = $resolution_unit[0]*60*60*24*7;
		}else if(strpos($resolution,'M') !== false){
			$resolution_unit = explode('M',$resolution);
			$resolution_timestamp = $resolution_unit[0]*60*60*24*7*30;
		}else if(strpos($resolution,'Y') !== false){
			$resolution_unit = explode('Y',$resolution);
			$resolution_timestamp = $resolution_unit[0]*60*60*24*7*30*12;
		}else{
			$resolution_timestamp = $resolution*60;
		}
    	
    	$result_sets = DB::table('btc_trades_COIN')
        				->selectRaw('FLOOR(created/'.$resolution_timestamp.')*('.$resolution_timestamp.') AS t,  MAX(buy_coin_price) AS h, MIN(buy_coin_price) AS l , SUM(contract_coin_amt) as v, MID(MIN(CONCAT(created,buy_coin_price)),11) as o, MID(MAX(CONCAT(created,buy_coin_price)),11) as c ')
						->where('cointype',$symbol)->whereBetween(DB::raw('FLOOR(created/'.$resolution_timestamp.')*('.$resolution_timestamp.')'), [$from, $to])
						->groupBy(DB::raw('FLOOR(created/'.$resolution_timestamp.')*('.$resolution_timestamp.')'))->get();
    	
		$t = array();
        $o = array();
        $h = array();
        $l = array();
        $c = array();
        $v = array();
        
        foreach($result_sets as $result_set){
            $t[] = $result_set->t;
            $o[] = $result_set->o;
            $h[] = $result_set->h;
            $l[] = $result_set->l;
            $c[] = $result_set->c;
            $v[] = $result_set->v;
        }

        $response = array(
            't' => $t,
            'o' => $o,
            'h' => $h,
            'l' => $l,
            'c' => $c,
            'v' => $v,
            's' => $s,
        );
		
		echo json_encode($response);
    		
    	
    	//$from_date = date("Y-m",strtotime("-11 months"));
      	//$to_date = date("Y-m");

		//신규유저수
    	//$new_user_count = DB::table('users')->whereMonth('created_at',date('m'))->count(); 
    	
		//달별 코인별 거래량 일년기간
		/*$month_trades = DB::table('btc_trades_COIN')->select(DB::raw('SUM(contract_coin_amt) AS amt'), DB::raw('CONCAT(YEAR(created_dt),"-",MONTH(created_dt)) AS date'),'cointype')
		->where('created','>',DB::raw('UNIX_TIMESTAMP(NOW() - INTERVAL 1 YEAR)'))->orderBy(DB::raw('CONCAT(YEAR(created_dt),"-",MONTH(created_dt))'),'asc')->groupBy('cointype',DB::raw('CONCAT(YEAR(created_dt),"-",MONTH(created_dt))'))->get();
		*/
		
		//달별 코인총합 수수료 수익 일년기간
		/*$month_trades_revenue = DB::table('btc_trades_COIN')
		->leftJoin('btc_lock_dividend', function($join)	{
			$join->on(DB::raw('CONCAT(YEAR(btc_trades_COIN.created_dt),"-",MONTH(btc_trades_COIN.created_dt))'),'=',DB::raw('CONCAT(YEAR(btc_lock_dividend.created_dt),"-",MONTH(btc_lock_dividend.created_dt))'));
			$join->on(DB::raw('CONCAT(YEAR(btc_trades_COIN.created_dt),"-",MONTH(btc_trades_COIN.created_dt))'),'=',DB::raw('CONCAT(YEAR(btc_lock_dividend.created_dt),"-",MONTH(btc_lock_dividend.created_dt))'));
		})->select(
			DB::raw('(SUM(btc_trades_COIN.contract_coin_amt*btc_trades_COIN.buy_coin_price) * (SELECT (buy_comission + sell_comission) AS fee FROM btc_settings WHERE id = '.Session('market_type').' ) * 0.01) - IFNULL(SUM(btc_lock_dividend.amount),0) AS total_fee_price'), 
			DB::raw('CONCAT(YEAR(btc_trades_COIN.created_dt),"-",MONTH(btc_trades_COIN.created_dt)) AS date')
		)->where('btc_trades_COIN.created','>',DB::raw('UNIX_TIMESTAMP(NOW() - INTERVAL 1 YEAR)'))
		->orderBy(DB::raw('CONCAT(YEAR(btc_trades_COIN.created_dt),"-",MONTH(btc_trades_COIN.created_dt))'),'asc')
		->groupBy(DB::raw('CONCAT(YEAR(btc_trades_COIN.created_dt),"-",MONTH(btc_trades_COIN.created_dt))'))->get();
		
		print_r($month_trades_revenue);*/
		
		//1:1문의 미응답 개수
		//$qna_count = DB::connection('mysql_sub')->table('btc_qna')->select(DB::raw('COUNT(id) as count'),'country')->where('answered',0)->groupBy('country')->get();
		
		//출금신청 미응답 개수
		//$send_requests_count = 	DB::table('btc_coin_send_request')->where('status','withdraw_request')->count();
	
		//ICO 컨펌 미응답 개수
		//$ICO_confirm_count = DB::connection('mysql_sub')->table('btc_ico_new')->where('active',0)->where('ico_category',0)->count();
		
		//장외거래 컨펌 미응답 개수
		/*$p2p_confirm_count = DB::connection('mysql_sub')->table('btc_p2p')->where('deleted',0)->where('state','<>','stop')->where(function ($query){
			$query->where('confirm',1)->orwhere('confirm',2)->orwhere('confirm',3);
		})->count();*/
		
		
    }
  
}