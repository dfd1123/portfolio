<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;

class LockRewardAjaxController extends Controller
{
	public function history_view_more(Request $request){
		$page = $request->page;
		$limit = $request->limit;
		$coin = $request->coin;
		
		$lock_items_query = DB::table('btc_lock_list')->selectRaw('operation, amount, created_dt')->where('uid', Auth::user()->id)->where('coin', $coin)->orderBy('id','desc');
		$lock_items = $lock_items_query->forPage($page, $limit)->get();
		$lock_items_next_page = $lock_items_query->forPage($page + 1, $limit)->get()->count() > 0 ? $page + 1 : 0;
		
		$response = array(
			'lock_items' => $lock_items,
			'lock_items_next_page' => $lock_items_next_page
		);

		return response()->json($response); 
	}

	public function dividend_view_more(Request $request){
		$page = $request->page;
		$limit = $request->limit;
		$coin = $request->coin;
		
		$dividend_items_query = DB::table('btc_lock_dividend')->selectRaw('amount, created_dt')->where('uid', Auth::user()->id)->where('coin', $coin)->orderBy('id','desc');
		$dividend_items = $dividend_items_query->forPage($page,$limit)->get();
		$dividend_items_next_page = $dividend_items_query->forPage($page + 1, $limit)->get()->count() > 0 ? $page + 1 : 0;
		
		$response = array(
			'dividend_items' => $dividend_items,
			'dividend_items_next_page' => $dividend_items_next_page
		);

		return response()->json($response);
	}
}
