<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use DB;

class ConvertReward extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function index(Request $request)
    {
    	$converts = DB::table('btc_convert_list')
    	->join('users','btc_convert_list.email','=','users.email')->select('users.username','btc_convert_list.amount','btc_convert_list.id')
    	->get();
		
		/*foreach($converts as $convert){
			DB::table('btc_transaction')->insert([
				'cointxid' => 'ucss_profit_share_'.date('Y-m-d').'_'.$convert->id,
				'cointype' => 'usd',
				'account' => $convert->username,
				'address' => 'ucss_profit_share_'.date('Y-m-d').'_'.$convert->id,
				'category' => 'receive',
				'amount' => $convert->amount,
				'confirmations' => 6,
				'txid' => 'ucss_profit_share_'.date('Y-m-d').'_'.$convert->id,
				'tr_time' => time(),
				'timereceived' => time(),
				'processed' => 'y',
				'created_dt' => now()
			]);
			
			DB::table('btc_users_addresses')->where('label', $convert->username)->update([
				"available_balance_usd" => DB::raw("available_balance_usd + ".$convert->amount)
			]);
			
			
		}*/
        
		$views = view(session('theme').'.pc.convert.convertreward');
		
        return $views;
    }
}
