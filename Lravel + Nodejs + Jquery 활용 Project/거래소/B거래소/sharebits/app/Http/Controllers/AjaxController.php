<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\Notify;

use DB;
use My_info;
use Auth;
use Log;
use Admin;
use Mail;
use Hash;

class AjaxController extends Controller
{
	public function user_status_change(Request $request){
		$status = $request->status;
		$id = $request->id;

		DB::table('users')->where('id',$id)->update([
			"status" => $status,
		]);

		$response = array(
			'status' => $status,
		);

		return response()->json($response); 

	}
	
	public function user_security_load(Request $request){
		$id = $request->id;
		
		$security = DB::table('users')->where('id',$id)->leftJoin('btc_security_lv','users.id','=','btc_security_lv.uid')->first();
		
		if($security->email_verified_at != NULL){
			$email_verified = 1;
		}else{
			$email_verified = 0;
		}
		$response = array(
			'email_verified' => $email_verified,
			'mobile_verified' => $security->mobile_verified,
			'document_verified' => $security->document_verified,
			'account_verified' => $security->account_verified,
			'google_verified' => $security->google_verified,
		);
		
		
		return response()->json($response); 
	}
	
	public function email_security_change(Request $request){
		$id = $request->id;

		DB::table('btc_security_lv')->where('uid',$id)->update([
			"email_verified" => $request->email_verified,
		]);
		
		if($request->email_verified){
			DB::table('users')->where('id',$id)->update([
				"email_verified_at" => date("Y-m-d H:i:s"),
			]);
		}else{
			DB::table('users')->where('id',$id)->update([
				"email_verified_at" => NULL,
			]);
		}
		
		return response()->json(1);
	}
	
	public function mobile_security_change(Request $request){
		$id = $request->id;
		
		DB::table('btc_security_lv')->where('uid',$id)->update([
			"mobile_verified" => $request->mobile_verified,
		]);

		
		return response()->json(1);
	}
	
	public function google_security_change(Request $request){
		$id = $request->id;
		
		DB::table('btc_security_lv')->where('uid',$id)->update([
			"google_verified" => $request->google_verified,
		]);
		
		return response()->json(1);
	}
	
	public function user_available_load(Request $request){
		$id = $request->id;
		
		$available = DB::table('btc_users_addresses')->where('uid',$id)->first();
		
		$coins = DB::table('btc_coins')->where('active',1)->where('id','<>',3)->get();
		
		$response = array(
			"available" => $available,
			"coins" => $coins,
		);
		
		return response()->json($available); 
		
	}
	
	public function document_agree(Request $request){
		$id = $request->id;
		
		$security = DB::table('btc_security_lv')->where('uid',$id)->first();
		
		if($security->document_1 != NULL && $security->document_2 != NULL){
			DB::table('btc_security_lv')->where('uid',$id)->update([
				"document_verified" => 1,
				"document_reject" => NULL,
			]);
			
			$response = array(
				"status" => 1
			);
		}else{
			$response = array(
				"status" => 0
			);
		}
		
		return response()->json($response); 
		
	}
	
	public function document_disagree(Request $request){
		$id = $request->id;
		
		$security = DB::table('btc_security_lv')->where('uid',$id)->first();
		
		if($security->document_1 != NULL || $security->document_2 != NULL){
			DB::table('btc_security_lv')->where('uid',$id)->update([
				"document_verified" => 0,
				"document_reject" => $request->document_reject,
			]);
			
			$response = array(
				"status" => 1
			);
		}else{
			$response = array(
				"status" => 0
			);
		}
		
		return response()->json($response); 
		
	}
	
	public function document_reject_load(Request $request){
		$id = $request->id;
		
		$security = DB::table('btc_security_lv')->where('uid',$id)->first();
		
		$reason = '';
		
		if($security->document_reject != NULL){
			$reason = $security->document_reject;
		}
		
		$response = array(
			"reject_reason" => $reason,
		);

		
		return response()->json($response); 
		
	}
	
	public function account_agree(Request $request){
		$id = $request->id;
		
		$security = DB::table('btc_security_lv')->where('uid',$id)->first();
		
		if($security->account_1 != NULL && $security->account_2 != NULL){
			DB::table('btc_security_lv')->where('uid',$id)->update([
				"account_verified" => 1,
				"account_reject" => NULL,
			]);
			
			$response = array(
				"status" => 1
			);
		}else{
			$response = array(
				"status" => 0
			);
		}
		
		return response()->json($response); 
		
	}
	
	public function account_disagree(Request $request){
		$id = $request->id;
		
		$security = DB::table('btc_security_lv')->where('uid',$id)->first();
		
		if($security->account_1 != NULL || $security->account_2 != NULL){
			DB::table('btc_security_lv')->where('uid',$id)->update([
				"account_verified" => 0,
				"account_reject" => $request->account_reject,
			]);
			
			$response = array(
				"status" => 1
			);
		}else{
			$response = array(
				"status" => 0
			);
		}
		
		return response()->json($response); 
		
	}
	
	public function account_reject_load(Request $request){
		$id = $request->id;
		
		$security = DB::table('btc_security_lv')->where('uid',$id)->first();
		
		$reason = '';
		
		if($security->account_reject != NULL){
			$reason = $security->account_reject;
		}
		
		$response = array(
			"reject_reason" => $reason,
		);

		
		return response()->json($response); 
		
	}

	public function add_balance_change(Request $request){
		$uid = $request->id;
		$cointype =  $request->cointype;
		$reason =  $request->reason;
		$amount =  $request->amount;

		if(empty($cointype) or empty($reason) or empty($amount)) {
			return;
		}

		$balance_before = 0;
		$type = $amount >= 0 ? "in" : "out";
		$status = "complete";

		$userinfo = DB::table('btc_users_addresses')->where('uid', $uid)->select('*')->first();
		if($userinfo == null) {
			return;
		}

		$balance_before = $userinfo->{'available_balance_'.$cointype};
		$balance_after = $balance_before + $amount;
		
		$username = $userinfo->label;

		if ($amount >= 0 ) { 
			$plus =  $amount; 
			$minus = 0;
		} else { 
			$plus =  0; 
			$minus = $amount; 
		}

		if($cointype == 'usd') {
			DB::table('btc_usd_io')->insert([
				'uid' => $uid,
				'username' => $username,
				'type' => $type,
				'status' => $status,
				'plus' => $plus,
				'minus' => $minus,
				'amount' => $amount,
				'balance_before' => $balance_before,
				'balance_after' => $balance_after,
				'rel_type' => null,
				'rel_id' => null,
				'memo' => $reason,
				'created' => time()
			]);
		}

		DB::table('btc_coin_io')->insert([
			'uid' => $uid,
			'username' => $username,
			'type' => $type,
			'cointype' => $cointype,
			'status' => $status,
			'plus' => $plus,
			'minus' => $minus,
			'amount' => $amount,
			'balance_before' => $balance_before,
			'balance_after' => $balance_after,
			'memo' => $reason,
			'created' => time(),
			'created_dt' => now()
		]);
		

		DB::table('btc_users_addresses')->where('uid', $uid)->update([
			"available_balance_$cointype" => DB::raw("available_balance_$cointype + $amount")
		]);
		
		$response = array(
			"status" => 1,
		);

		return response()->json($response); 
	}
	
	// trans_wallet ajax
	/*public function refresh_trans_wallet(Request $request){
		$coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();
		
		$response = array();
		
		$i = 0;
		$total_holding = 0; // ??? ????????????

		foreach($coins as $coin){
			$coin_balance = My_info::get_user_available_balance_allcoin(Auth::user()->id, $coin->api); //????????? ????????????
			$coin_balance_price = $coin_balance * $coin->price_usd; //?????? ?????? ?????? * ???????????? ??????
			$total_holding += $coin_balance_price;
			
			if($coin->api == 'btc'){
				$btc_price_usd = $coin->price_usd; // btc ????????????
			}
			$response[$i] = $coin;
			$response[$i]['balance'] = $coin_balance;
			$i++;
		}
		$response['total_holding'] = $total_holding;

		
		return response()->json($response); 
		
	}*/

	public function External_withdraw_confirm(Request $request){

		$request_id = $request->request_id;
		if(Admin::withdraw_confirm($request->request_id)){

			// ?????? ?????? ??????
			$send_request = DB::table('btc_coin_send_request')->where('id',$request_id)->first();
			$sender_userid = $send_request->sender_userid;
			$cointype = $send_request->cointype;
			$receiver_address = $send_request->receiver_address;
			$req_amount = $send_request->req_amount;

			// ?????? ?????? ?????????
			Admin::alarm_withdraw_confirm($sender_userid, $cointype, $receiver_address, $req_amount);
			
			$response = array(
				"message" => "?????? ??????????????? ??????????????? ???????????????.",
				"status" => __('admin_coin.withdraw_request_confirm'),
			);
		}else{
			$response = array(
				"message" => "?????? ?????? ??? ????????? ??????????????????."
			);
		}

		return response()->json($response); 
	}

	public function cancel_coin_io(Request $request){
		if(Admin::cancel_coin_io($request->request_id, $request->status)){
			$response = array(
				"message" => "?????? ???????????? ???????????? ???????????????.",
				"status" => __('admin_coin.withdraw_reject'),
			);
		}else{
			$response = array(
				"message" => "?????? ?????? ??? ????????? ??????????????????."
			);
		}

		return response()->json($response); 
	}

	public function manual_confirm(Request $request){
		if(Admin::cancel_coin_io($request->tx_id, $request->id)){
			
			// ?????? ?????? ??????
			$send_request = DB::table('btc_coin_send_request')->where('id',$request_id)->first();
			$sender_userid = $send_request->sender_userid;
			$cointype = $send_request->cointype;
			$receiver_address = $send_request->receiver_address;
			$req_amount = $send_request->req_amount;

			// ?????? ?????? ?????????
			Admin::alarm_withdraw_confirm($sender_userid, $cointype, $receiver_address, $req_amount);

			$response = array(
				"message" => "?????? ?????? ????????? ??????????????? ???????????????.",
				"status" => __('admin_coin.withdraw_confirm'),
			);
		}else{
			$response = array(
				"message" => "?????? ?????? ?????? ??? ????????? ??????????????????."
			);
		}

		return response()->json($response); 
	}

	public function Internal_withdraw_confirm(Request $request){

		$id = $request->id;

		DB::table('btc_coin_send_request')->where('id',$id)->update([ // ????????? ???????????? db ??????
			'status' => 'withdraw_complete',
			'updated' => time(),
			'updated_dt' => DB::raw('now()'),
		]);

		$send_rq = DB::table('btc_coin_send_request')->where('id',$id)->first();

		$symbol = $send_rq->cointype;
		$tx_id = $send_rq->tx_id;
		$address = $send_rq->reciever_address;
		$amt = $send_rq->req_amount;
		$send_fee = $send_rq->send_fee;

		$internal_external = DB::table('btc_users_addresses')->where('address_'.strtolower($symbol),$address)->first();
		
		DB::table('btc_transaction')->insert([  // ????????? ???????????? db ??????
			'cointxid' => strtolower($symbol)."_".$tx_id."_send",
			'cointype' => strtolower($symbol),
			'account' => Auth::user()->username,
			'address' => $address,
			'category' => 'send',
			'amount' => $amt,
			'confirmations' => 999,
			'txid' => $tx_id,
			'normtxid' => '',
			'tr_time' => time(),
			'timereceived' => time(),
			'processed' => 'y',
			'created_dt' => DB::raw('now()'),
		]);
		
		DB::table('btc_transaction')->insert([  // ????????? ???????????? db ??????
			'cointxid' => strtolower($symbol)."_".$tx_id."_receive",
			'cointype' => strtolower($symbol),
			'account' => $internal_external->label,
			'address' => $address,
			'category' => 'receive',
			'amount' => $amt,
			'confirmations' => 999,
			'txid' => $tx_id,
			'normtxid' => '',
			'tr_time' => time(),
			'timereceived' => time(),
			'processed' => 'y',
			'created_dt' => DB::raw('now()'),
		]);
		
		DB::table('btc_users_addresses')->where('uid',Auth::id())->decrement('available_balance_'.strtolower($symbol),bcadd($amt,$send_fee,8)); // ????????? ?????? ??????

		DB::table('btc_users_addresses')->where('uid',Auth::id())->increment('pending_received_balance_'.strtolower($symbol),bcadd($amt,$send_fee,8)); // ????????? ?????? ??????
		
		DB::table('btc_users_addresses')->where('uid',$internal_external->uid)->increment('available_balance_'.strtolower($symbol),$amt); // ????????? ?????? ??????
		
		// ?????? ?????? ??????
		$send_request = DB::table('btc_coin_send_request')->where('id',$request_id)->first();
		$sender_userid = $send_request->sender_userid;
		$cointype = $send_request->cointype;
		$receiver_address = $send_request->receiver_address;
		$req_amount = $send_request->req_amount;

		// ?????? ?????? ?????????
		Admin::alarm_withdraw_confirm($sender_userid, $cointype, $receiver_address, $req_amount);

		$response = array(
			"message" => "?????? ????????? ??????????????? ???????????????.",
			"status" => __('admin_coin.withdraw_confirm'),
		);

		return response()->json($response); 
	
	}

	public function popup(Request $request){
		$id = $request->id;

		setcookie("nopopup".$id, 1, time() + (86400 * 30), "/");

		$response = array(
			"status" => 1,
		);

		return response()->json($response);
	}
	
	public function user_secession(Request $request){
		$id = $request->id;

		$user = DB::table('users')->where('id', $id)->first();
		if($user == null) {
			return;
		}

		DB::table('users')->where('id', $id)->update([
			'password' => '',
			'secret_pin' => null,
			'email' =>	$user->email . "^^" . Hash::make($id . str_random(64)),
			'email_verified_at' => null,
			'mobile_number' => null,
			'status' => null,
			'hash' => null,
			'wallet' => null,
			'ip' => null,
			'time_signup' => null,
			'time_signin' => null,
			'time_activity' => null,
			'referral_id' => null,
			'remember_token' => null,
			'alarm_email' => null,
			'alarm_sms' => null,
			'alarm_io_email' => null,
			'alarm_io_sms' => null,
			'created_at' => null,
			'updated_at' => null
		]);

		$response = array(
			"status" => 1,
		);

		return response()->json($response);
	}
}
