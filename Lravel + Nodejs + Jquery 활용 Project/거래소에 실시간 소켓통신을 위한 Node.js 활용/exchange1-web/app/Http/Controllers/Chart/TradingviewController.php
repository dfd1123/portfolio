<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Coin_info;

class TradingviewController extends Controller
{
    public function index(){

    }

    public function config(){
        $response = array(
            'supports_search' => true,
            'supports_group_request' => false,
            'supports_marks' => false,
            'supports_timescale_marks' => false,
            'supports_time' => false,	
            'supported_resolutions' => [
                 '1','3', '5','10','15','30','60','360','1D'
            ]
        );
        
        return response()->json($response);
    }

    public function history(Request $request){
        $symbol = $request->symbol;
        $resolution = $request->resolution;
        $from = $request->from;
        $to = $request->to;
        
		
		if(strpos($resolution,'D') !== false){
			$resolution_unit = explode('D',$resolution);
			$resolution_timestamp = (int) $resolution_unit[0] * 60 * 60 * 24;
		}else if(strpos($resolution,'W') !== false){
			$resolution_unit = explode('W',$resolution);
			$resolution_timestamp = (int) $resolution_unit[0] * 60 * 60 * 24 * 7;
		}else if(strpos($resolution,'M') !== false){
			$resolution_unit = explode('M',$resolution);
			$resolution_timestamp = (int) $resolution_unit[0] * 60 * 60 * 24 * 7 * 30;
		}else if(strpos($resolution,'Y') !== false){
			$resolution_unit = explode('Y',$resolution);
			$resolution_timestamp = (int) $resolution_unit[0] * 60 * 60 * 24 * 7 * 30 * 12;
		}else{
			$resolution_timestamp = $resolution*60;
		}
        $result_sets = DB::table('btc_trades_COIN_btc')
        				->selectRaw('FLOOR(created/'.$resolution_timestamp.')*('.$resolution_timestamp.') AS t,  MAX(buy_coin_price) AS h, MIN(buy_coin_price) AS l , SUM(contract_coin_amt) as v, MID(MIN(CONCAT(created,buy_coin_price)),11) as o, MID(MAX(CONCAT(created,buy_coin_price)),11) as c ')
						->where('cointype',$symbol)->whereBetween( DB::raw('FLOOR(created/'.$resolution_timestamp.')*('.$resolution_timestamp.')'), [$from, $to])
						->groupBy(DB::raw('t'))->get();
        
     
        
        
        $s = "ok";

        if(count($result_sets) <= 0){
            $s = "no_data";
        }

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

        return response()->json($response);

        
    }

    public function symbols(Request $request){
        $symbol = $request->symbol;

        $response = array(
            'name' => $symbol,
            'exchange-traded' => "",
            'exchange-listed' => "",
            'timezone' => "Asia/Seoul",
            'minmov' => 1,
            'minmov2' => 0,
            'pointvalue' => 1,
            'has_intraday' => true,
            'has_no_volume' => false,
            'volume_precision' => 8,
            'description' => $symbol,
            'type' => $symbol,
            'pricescale' => 100000000,
            'ticker' => $symbol
        );

        return response()->json($response);
    }

    public function time(){
        $timestamp = time();
        return $timestamp + (60*60*1000*9);
    }
	
	public function config_new(){
        $response = array(
            'supports_search' => true,
            'supports_group_request' => false,
            'supports_marks' => false,
            'supports_timescale_marks' => false,
            'supports_time' => false,	
            'supported_resolutions' => [
                '1','3', '5','10','30','60','360','1D', '1W', '1M'
            ]
        );
        
        return response()->json($response);
    }

    public function history_new(Request $request){
        $symbol = $request->symbol;
        $resolution = $request->resolution;
        $from = $request->from;
        $to = $request->to;
		
		$symbol_explode = explode('/',$symbol);
		
		$trade_symbol = $symbol_explode[0];
		if($symbol_explode[1] == 'UCSS'){
			$market_symbol = 'USD';
		}else{
			$market_symbol = $symbol_explode[1];
		}
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
        $result_sets = DB::table('btc_trades_COIN_btc')
        				->selectRaw('FLOOR(created/'.$resolution_timestamp.')*('.$resolution_timestamp.') AS t,  MAX(buy_coin_price) AS h, MIN(buy_coin_price) AS l , SUM(contract_coin_amt) as v, MID(MIN(CONCAT(created,buy_coin_price)),11) as o, MID(MAX(CONCAT(created,buy_coin_price)),11) as c ')
						->where('cointype',$trade_symbol)->where('currency',$market_symbol)->whereBetween( DB::raw('FLOOR(created/'.$resolution_timestamp.')*('.$resolution_timestamp.')'), [$from, $to])
						->groupBy(DB::raw('t'))->get();
						

        $s = "ok";

        if(count($result_sets) <= 0){
            $s = "no_data";
        }

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

        return response()->json($response);

        
    }

    public function symbols_new(Request $request){
        $symbol = $request->symbol;
		$symbol_explode = explode('/',$symbol);
		
		$trade_symbol = $symbol_explode[0];
		if($symbol_explode[1] == 'UCSS'){
			$market_symbol = 'USD';
		}else{
			$market_symbol = $symbol_explode[1];
		}
		
		$trade_coin = Coin_info::get($symbol_explode[0]); // 코인정보 가져오기
		
		$pricescale = pow(10,$trade_coin->{'decimal_'.strtolower($market_symbol)});
		
        $response = array(
            'name' => $symbol,
            'exchange-traded' => "",
            'exchange-listed' => "",
            'timezone' => "Asia/Seoul",
            'minmov' => 1,
            'minmov2' => 0,
            'pointvalue' => 1,
            'has_intraday' => true,
            'has_no_volume' => false,
            'volume_precision' => 8,
            'description' => $symbol,
            'type' => $symbol,
            'pricescale' => $pricescale,
            'ticker' => $symbol
        );

        return response()->json($response);
    }

    public function time_new(){
        $timestamp = time();
        return $timestamp + (60*60*1000*9);
    }
}
