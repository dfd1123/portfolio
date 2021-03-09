<?php

namespace App\Classes;

use DB;

class Coin_info {
	public function get($coin_symbol)
	{
		$coin = DB::table('btc_coins')->where('symbol',$coin_symbol)->first();
		
		return $coin;
	}

	public function get_coin_info($symbol) { // 개별 코인 정보를 가져온다.
	
		$coin = DB::table('btc_coins')->where('api',$symbol)->first();
	
		if($coin != null) {
			return  $coin;
		} else {
			return  false;
		}
	}

	public function get_current_coin_price3($currency, $cointype, $source) { // coinmarketcap, trade, input, appopriate 중에 소스 종류 바뀜

		$coin = DB::table('btc_coins')->where('symbol',strtoupper($cointype))->first();
		
		if($coin != null){
			if($source == 'coinmarketcap') {
				return $coin->{'last_coinmarketcap_price_'.strtolower($currency)};
			} else if($source == 'trade') {
				return $coin->{'last_trade_price_'.strtolower($currency)};
			} else if($source == 'appropriate') {
				return $coin->appropriate_price_usd;
			} else if($source == 'bithumb') {
				return $coin->last_bithumb_price_usd;
			} else if($source == '') {
				return $coin->{'price_'.strtolower($currency)};
			} else {
				return $coin->{'price_'.strtolower($currency)};
			}
		}

	}

}
