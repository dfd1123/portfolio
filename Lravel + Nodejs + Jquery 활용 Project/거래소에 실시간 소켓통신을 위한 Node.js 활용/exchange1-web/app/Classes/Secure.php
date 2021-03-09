<?php

namespace App\Classes;

use App\Mail\Notify;

use Mail;
use Auth;
use Settings;
use Nexmo_sms;
use Session;
use DB;

class Secure {
	public function secure_short_verified()
	{
		$status = 0;

		if(!Auth::check()){
			return $status;
		}

		$security_lv = DB::table('btc_security_lv')->where('uid', Auth::id())->first();
		
		//이메일 인증여부를 확인
		if(Auth::user()->email_verified_at !== NULL){
			$status = 1;
		}else{

			if($security_lv->email_verified == 1){
				DB::table('btc_security_lv')->where('uid', $security_lv->uid)->update([
					"email_verified" => 1,
				]);
				DB::table('users')->where('id', $security_lv->uid)->update([
					"email_verified_at" => date('Y-m-d H:i:s'),
				]);

				$status = 1;
			}else{
				return $status;
			}

			return $status;
		}
		
		//핸드폰 인증여부 확인
		if($security_lv->mobile_verified == 1){
			$status = 2;
		}else{
			return $status;
		}
		
		//구글 인증여부 확인
		if($security_lv->google_verified == 1){
			$status = 3;
		}else{
			return $status;
		}		
		
		return $status;
		
	}
	
	public function secure_long_verified()
	{
		$status = 0;

		if(!Auth::check()){
			return $status;
		}
		
		$security_lv = DB::table('btc_security_lv')->where('uid', Auth::user()->id)->first();

		//이메일 인증여부를 확인
		if(Auth::user()->email_verified_at !== NULL){
			$status = 1;
		}else{

			if($security_lv->email_verified == 1){
				DB::table('btc_security_lv')->where('uid', $security_lv->uid)->update([
					"email_verified" => 1,
				]);
				DB::table('users')->where('id', $security_lv->uid)->update([
					"email_verified_at" => date('Y-m-d H:i:s'),
				]);

				$status = 1;
			}else{
				return $status;
			}

			return $status;
		}
		
		//핸드폰 인증여부 확인
		if($security_lv->mobile_verified == 1){
			$status = 2;
		}else{
			return $status;
		}
		
		//계좌 인증여부 확인		
		if($security_lv->account_verified == 1){
			$status = 3;
		}else{
			if($security_lv->account_1 != NULL && $security_lv->account_2 != NULL){
				$status = 2.5;
			}else{
				return $status;
			}
		}
		
		//신분증 인증여부 확인
		if($security_lv->documnet_verified == 1){
			$status = 4;
		}else{
			if($security_lv->documnet_1 != NULL && $security_lv->documnet_2 != NULL){
				$status = 3.5;
			}else{
				return $status;
			}
		}
		
		if($security_lv->account_verified)
		
		//구글 인증여부 확인
		if($security_lv->google_verified == 1){
			$status = 5;
		}else{
			return $status;
		}		
		
		
		return $status;
	}

	public function sms_verify_code($mobile_number, $country){
		$setting = Settings::Settings();

		$rand = rand(00000,99999);

		Session::put('sms_verify', $rand);

		$duplicate = DB::table('users')->where('mobile_number',$mobile_number)->count();

		if($duplicate > 0){
			return false;
		}else{
			if($country == 'kr'){
				$str =  "[".$rand."] 인증 번호를 입력하세요. -".$setting->name;
			}else if($country == 'jp'){
				$str =  "[".$rand."] 認証番号を入力してください。 -".$setting->name;
			}else if($country == 'ch'){
				$str =  "[".$rand."] 请输入您的验证码。 -".$setting->name;
			}else if($country == 'th'){
				$str =  "[".$rand."] กรุณาใส่หมายเลขการตรวจสอบของคุณ -".$setting->name;
			}else if($country == 'en'){
				$str =  "[".$rand."] Please enter your verification number. -".$setting->name;
			}
	
			return Nexmo_sms::send_sms($country,$mobile_number,$str);	
		}

	}

	public function sms_certify($verify_code){
		if(session('sms_verify') == $verify_code){

			Session::forget('sms_verify');

			DB::table('btc_security_lv')->where('uid',Auth::id())->update([
				"mobile_verified" => 1,
			]);

			return true;
		}else{
			return false;
		}
	}

	public function email_Isdupicate($email){
		$duplicate = DB::table('users')->where('email',$email)->count();

		if($duplicate > 0){
			return true;
		}else{
			return false;
		}
	}

	public function username_Isdupicate($username){
		$duplicate = DB::table('users')->where('username',$username)->count();

		if($duplicate > 0){
			return true;
		}else{
			return false;
		}
	}


}
