<?php

namespace App\Classes;

use Facades\App\Classes\Nexmo_sms;
use App\Mail\EmailVerify;

use Auth;
use DB;
use Mail;

class Secure {
	public function info() {
		return DB::table('btc_security_lv')->where('uid', Auth::id())->first();
	}

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

		//신분증 제출 확인
		if($security_lv->document_verified == 2 || $security_lv->document_verified == 1){
			$status = 2.5;
		}else{
			return $status;
		}

		//계좌 제출여부 확인
		if($security_lv->account_verified == 2 || $security_lv->account_verified == 1){
			$status = 3.5;
		}else{
			return $status;
		}

		//계좌 인증여부 확인
		if($security_lv->account_verified == 1){
			$status = 4;
		}else{
			return $status;
		}

		//구글 인증여부 확인
		if($security_lv->google_verified == 1){
			$status = 5;
		}else{
			return $status;
		}

		return $status;

	}

	public function sms_verify_code($mobile_number, $country){
		$duplicate = DB::table('users')->where('mobile_number', $mobile_number)->count();

		if($duplicate > 0){
			return false;
		}else{
			$rand = rand(00000,99999);

			DB::table('sms_verify_temp')->updateOrInsert([
				'mobile_number' => $mobile_number
			], [
				'verify_code' => $rand,
				'updated' => DB::raw('CURRENT_TIMESTAMP()')
			]);

			if($country == 'kr'){
				$str =  "[".$rand."] 인증 번호를 입력하세요. -";
			}else if($country == 'jp'){
				$str =  "[".$rand."] 認証番号を入力してください。 -";
			}else if($country == 'ch'){
				$str =  "[".$rand."] 请输入您的验证码。 -";
			}else if($country == 'th'){
				$str =  "[".$rand."] กรุณาใส่หมายเลขการตรวจสอบของคุณ -";
			}else if($country == 'en'){
				$str =  "[".$rand."] Please enter your verification number. -";
			}

			Nexmo_sms::send_sms($country,$mobile_number,$str);
			return true;
		}
	}

	public function sms_certify_code($mobile_number, $verify_code){
		$verify = DB::table('sms_verify_temp')->where('mobile_number', $mobile_number)->where('verify_code', $verify_code)->first();
		if($verify != null){
			if(date('Y-m-d H:i:s', strtotime('-3 minutes')) > date('Y-m-d H:i:s', strtotime($verify->updated))) {
				return false;
			}

			DB::table('sms_verify_temp')->where('mobile_number', $mobile_number)->where('verify_code', $verify_code)->delete();
			return true;
		}else{
			return false;
		}
	}

	public function email_verify_code($email, $country){
		$duplicate = DB::table('users')->where('email', $email)->count();

		if($duplicate > 0){
			return false;
		}else{
			$rand = $this->generateRandomString(6);

			DB::table('email_verify_temp')->updateOrInsert([
				'email' => $email
			], [
				'verify_code' => $rand
			]);

			if($country == 'kr') {
				$data = array(
					'title' => '[PHILLIPS PAY] PHILLIPS PAY 회원가입 인증 메일입니다.',
					'content' =>
						"안녕하세요, ".$email." 계정인증 메일입니다. 아래의 문자를 복사해서 홈페이지의 입력란에 넣고 인증하기 버튼을 눌러주세요.".
						"<br><br>".$rand."<br><br>".
						"문제가 있으시면 고객센터로 연락주세요."
				);
			} else if($country == 'jp') {
				/*
				$data = array(
					'title' => '[Allcoin Wallet] オルコインワレット 会員登録認証メールです。',
					'content' =>
						"こんにちは、 ".$email." アカウント認証メールです。下の文字をコピーして、ホームページのテキストボックスに入れて認証するボタンを押してください。".
						"<br><br>".$rand."<br><br>".
						"問題がございましたら、お客様センター1600-4719までご連絡ください。"
				);
				*/
			}

			Mail::to($email)->send(new EmailVerify($data));
			return true;
		}
	}

	public function email_certify_code($email, $verify_code){
		$verify = DB::table('email_verify_temp')->where('email', $email)->first();

		if($verify != null){
			$verify_check = $verify->verify_code == $verify_code;
			if(!$verify_check) {
				return 'certify_fail';
			}

			DB::table('email_verify_temp')->where('email', $email)->where('verify_code', $verify_code)->delete();
			return 'certify_ok';
		}else{
			return 'certify_error';
		}
	}

	public function password_verify_code($email, $country){
		$find = DB::table('users')->where('email', $email)->count();

		if($find === 0){
			return false;
		}else{
			$rand = $this->generateRandomString(16);

			DB::table('email_verify_temp')->updateOrInsert([
				'email' => $email
			], [
				'verify_code' => $rand
			]);

			if($country == 'kr') {
				$data = array(
					'title' => '[PHILLIPS PAY] PHILLIPS PAY 비밀번호찾기 인증 메일입니다.',
					'content' =>
						"안녕하세요, ".$email." 비밀번호찾기 인증 메일입니다. 아래의 문자를 복사해서 홈페이지의 입력란에 넣고 인증하기 버튼을 눌러주세요.".
						"<br><br>".$rand."<br><br>".
						"문제가 있으시면 고객센터로 연락주세요."
				);
			}

			Mail::to($email)->send(new EmailVerify($data));
			return true;
		}
	}

	public function password_certify_code($email, $verify_code){
		$verify = DB::table('email_verify_temp')->where('email', $email)->first();

		if($verify !== null){
			$verify_check = $verify->verify_code == $verify_code;
			if(!$verify_check) {
				return 'certify_fail';
			}

			DB::table('email_verify_temp')->where('email', $email)->where('verify_code', $verify_code)->delete();
			return 'certify_ok';
		}else{
			return 'certify_error';
		}
	}

	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
