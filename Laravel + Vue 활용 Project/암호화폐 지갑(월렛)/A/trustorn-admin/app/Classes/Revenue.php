<?php

namespace App\Classes;

use DB;

class Revenue {
	public function userRevenue($uid)
	{
    $this_year = date('Y');
    $this_month = date('m');

    $revenue = DB::table('monthly_personal_revenue')->where('uid', $uid)->
    Wallet::balance(Auth::user()->id, 'tru');
		$coin = DB::table('btc_coins')->where('symbol',$coin_symbol)->first();
		
		return $coin;
	}

}
