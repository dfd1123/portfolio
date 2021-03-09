<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Google2FA\Google2FA;

use DB;
use Redirect;
use Auth;
use Settings;
use Secure;
use Hash;

use Facades\App\Classes\NiceCheck;

class MypageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';


    }

    public function index(){

    }

    public function alarm_setting(){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        $user = DB::table('users')->where('id', Auth::user()->id)->select('alarm_email', 'alarm_sms', 'alarm_io_email', 'alarm_io_sms')->first();

        $views = view(session('theme').'.'.$this->device.'.'.'mypage.alarm_setting');
        $views->user = $user;
        $views->status = Secure::secure_short_verified();

        return $views;
    }

    public function alarm_setting_update(Request $request){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        DB::table('users')->where('id', Auth::user()->id)->update([
            'alarm_email' => $request->alarm_email == null ? 0 : 1,
            'alarm_sms' => $request->alarm_sms == null ? 0 : 1,
            'alarm_io_email' => $request->alarm_io_email == null ? 0 : 1,
            'alarm_io_sms' => $request->alarm_io_sms == null ? 0 : 1
        ]);

        return Redirect::route('mypage.alarm_setting')->with('jsCheck', '알림설정이 변경되었습니다.');
    }

    public function otp_setting(Request $request){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        $status = Secure::secure_short_verified();

        $views = view(session('theme').'.'.$this->device.'.'.'mypage.otp_setting');
        $views->country = config('app.country');
        $views->status = $status;

        if($status == 4) {
            /* sudo apt-get install php7.1-gd 필요 */
            $google2fa = new Google2FA();
            $google2fa->setAllowInsecureCallToGoogleApis(true);
            $google2fa_secret = $google2fa->generateSecretKey();
            $qr_image = $google2fa->getQRCodeInline(
                Settings::Settings()->name,
                Auth::user()->email,
                $google2fa_secret
            );

            $views->qr_image = $qr_image;
            $views->google2fa_secret = $google2fa_secret;
            $views->status = $status;

            DB::table('btc_security_lv')->where('uid', Auth::user()->id)->update(['google_pin' => $google2fa_secret]);
        }

        return $views;
    }

    public function otp_setting_register(Request $request){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        $secret = $request->input('secret');
        if(empty($secret)) {
            return;
        }

        $google2fa_secret = DB::table('btc_security_lv')->where('uid', Auth::user()->id)->first()->google_pin;
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($google2fa_secret, $secret, 2);

        if($valid) {
            DB::table('btc_security_lv')->where('uid', Auth::user()->id)->update(['google_verified'=> 1, 'google_pin' => $google2fa_secret]);
            return Redirect::route('mypage.otp_setting')->with('jsCheck',__('otp.success'));
        } else {
            return Redirect::route('mypage.otp_setting')->with('jsAlert',__('otp.fail'));
        }
    }

    public function lock_reward(Request $request, $coin = null){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        date_default_timezone_set("Asia/Seoul");

        $lock_coins = DB::table('btc_lock_coins')->select('*')->get();

        if($coin == null) {
            if($lock_coins->count() > 0) {
                $coin = $lock_coins->first()->coin;
            } else {
                //진행 중인 락리워드가 없을 때
                $views = view(session('theme').'.'.$this->device.'.'.'mypage.lock_reward');
                $views->no_lock_reward = true;
                $views->status = Secure::secure_short_verified();
                return $views;
            }
        } else {
            $found = false;
            foreach ($lock_coins as $lock_coin) {
                if($lock_coin->coin == $coin) {
                    $found = true;
                }
            }
            if(!$found) {
                return Redirect::route('main');
            }
        }

        // 화면 정보 조회
        $lock_user = DB::table('btc_lock_users')->select('*')->where('uid', Auth::user()->id)->where('coin', $coin)->first();
        if($lock_user == null) {
            $lock_amount = '0.00000000';
            $unlocking_amount = '0.00000000';
        } else {
            $lock_amount = $lock_user->lock_amount;
            $unlocking_amount = $lock_user->unlocking_amount;
        }

        $user_balance = DB::table('btc_users_addresses')
            ->selectRaw("cast(available_balance_$coin as decimal(21,8)) + cast(pending_received_balance_$coin as decimal(21,8)) available_amount")
            ->where('uid', Auth::user()->id)
            ->first();
        if($user_balance == null) {
            $available_amount = '0.00000000';
        } else {
            $available_amount = $user_balance->available_amount;
        }

        $lock_coin_status = DB::table('btc_lock_coins')->select('status')->where('coin', $coin)->first()->status;

        // 페이징
        $page = 1;
        $limit = 10;

        // 락 내역 조회
        $lock_items_query = DB::table('btc_lock_list')->select('*')->where('uid', Auth::user()->id)->where('coin', $coin)->orderBy('id','desc');
        $lock_items = $lock_items_query->forPage($page, $limit)->get();
        $lock_items_next_page = $lock_items_query->forPage($page + 1, $limit)->get()->count() > 0 ? $page + 1 : 0;

        // 배당 내역 조회
        $dividend_items_query = DB::table('btc_lock_dividend')->select('*')->where('uid', Auth::user()->id)->where('coin', $coin)->orderBy('id','desc');
        $dividend_items = $dividend_items_query->forPage($page,$limit)->get();
        $dividend_items_next_page = $dividend_items_query->forPage($page + 1, $limit)->get()->count() > 0 ? $page + 1 : 0;

        $views = view(session('theme').'.'.$this->device.'.'.'mypage.lock_reward');
        $views->no_lock_reward = false;
        $views->lock_coin_status = $lock_coin_status;
        $views->coin = $coin;
        $views->lock_coins = $lock_coins;
        $views->lock_amount = $lock_amount;
        $views->unlocking_amount = $unlocking_amount;
        $views->available_amount = $available_amount;
        $views->lock_items = $lock_items;
        $views->lock_items_next_page = $lock_items_next_page;
        $views->lock_items_limit = $limit;
        $views->dividend_items = $dividend_items;
        $views->dividend_items_next_page = $dividend_items_next_page;
        $views->dividend_items_limit = $limit;
        $views->status = Secure::secure_short_verified();

        return $views;
    }

    public function lock_reward_update(Request $request, $coin){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        $operation = $request->operation;
        $amount = $request->amount;
        $coin = $request->coin;

        if($operation == 'lock') {
            // 사용자 화폐에서 사용 가능한 금액 확인
            $available = DB::table('btc_users_addresses')
                ->selectRaw("available_balance_$coin + pending_received_balance_$coin available")
                ->where('uid', Auth::user()->id)
                ->first()
                ->available;

            // 락 할 금액이 사용 가능한 금액보다 크면 중단
            if((float) $amount > (float) $available) {
                return;
            }

            // 사용자 화폐에서 락 할 액수만큼 거래대기 금액에 추가
            DB::table('btc_users_addresses')
                ->where('uid', Auth::user()->id)
                ->update([
                    "pending_received_balance_$coin" => DB::raw("pending_received_balance_$coin - cast($amount as decimal(21,8))")
                ]);

            // 사용자 락 테이블에 값이 있는지 체크 후 있으면 사용하고 없으면 추가
            $lock_user = DB::table('btc_lock_users')->where('uid', Auth::user()->id)->where('coin', $coin)->first();
            if($lock_user == null) {
                $lock_user_id = DB::table('btc_lock_users')->insertGetId([
                    'uid' => Auth::user()->id,
                    'coin' => $coin,
                    'created_dt' => now(),
                    'updated_dt' => now()
                ]);
            } else {
                $lock_user_id = $lock_user->id;
            }

            // 잠금된 액수에 값 더하기
            DB::table('btc_lock_users')->where('id', $lock_user_id)->update([
                'lock_amount' => DB::raw("lock_amount + cast($amount as decimal(21,8))"),
                'updated_dt' => now()
            ]);

            // 락 리스트에 내역 추가 [-1:잠금해제, 0:잠금해제중, 1:잠금중]
            DB::table('btc_lock_list')->insert([
                'uid' => Auth::user()->id,
                'coin' => $coin,
                'amount' => $amount,
                'operation' => 1,
                'created_dt' => now(),
                'updated_dt' => now()
            ]);
        } elseif($operation == 'unlock') {
            // 사용자 락 테이블에서 값 가져오기
            $lock_user = DB::table('btc_lock_users')->where('uid', Auth::user()->id)->where('coin', $coin)->first();
            if($lock_user == null) {
                return;
            }
            $lock_user_id = $lock_user->id;
            $lock = $lock_user->lock_amount;

            // 잠금해제할 금액이 잠금된 금액보다 크면 중단
            if((float) $amount > (float) $lock) {
                return;
            }

            // 잠금된 액수에서 잠금해제할 금액 빼기, 잠금해제중 액수에 값 더하기
            DB::table('btc_lock_users')
                ->where('id', $lock_user_id)
                ->update([
                    'lock_amount' => DB::raw("lock_amount - cast($amount as decimal(21,8))"),
                    'unlocking_amount' => DB::raw("unlocking_amount + cast($amount as decimal(21,8))"),
                    'updated_dt' => now()
                ]);

            // 락 리스트에 내역 추가. 잠금해제중 상태로 저장 후 24시간 뒤에 잠금해제됨 [-1:잠금해제, 0:잠금해제중, 1:잠금중]
            DB::table('btc_lock_list')->insert([
                'uid' => Auth::user()->id,
                'coin' => $coin,
                'amount' => $amount,
                'operation' => 0,
                'created_dt' => now(),
                'updated_dt' => now()
            ]);
        }

        return Redirect::route('mypage.lock_reward', $coin);
    }

    public function password_change(){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        $views = view(session('theme').'.'.$this->device.'.'.'mypage.password_change');
        $views->status = Secure::secure_short_verified();
        return $views;
    }

    public function password_change_update(Request $request){
        if(!Auth::check()){
            return Redirect::route('main');
        }

        $password = $request->password; //현재 비밀번호
        $cpassword = $request->cpassword; //변경할 비밀번호

        if(empty($password) || empty($cpassword)) {
            return;
        } else if(strlen($cpassword) < 8 || strlen($cpassword) > 12) {
            return;
        }

        if(Auth::attempt(['id' => Auth::user()->id, 'password' => $password])) {
            DB::table('users')->where('id', Auth::user()->id)->update(['password' => Hash::make($cpassword)]);

            return Redirect::route('mypage.password_change')->with('jsCheck', __('myp.changed_password'));
        } else {
            return Redirect::route('mypage.password_change')->with('jsAlert',__('myp.invalid_password'));
        }
    }

    public function security_setting(Request $request){

        $this->middleware('auth');
        $this->middleware('signed')->only('verify');

        if(!Auth::check()){
            return Redirect::route('main');
        }

        $status = Secure::secure_short_verified();
        if($status == 4){
            return Redirect::route('main');
        }
		$security_lv = DB::table('btc_security_lv')->where('uid', Auth::id())->first();
		$nice_info = NiceCheck::NiceCheck_main();


        $views = view(session('theme').'.'.$this->device.'.'.'mypage.security_setting');

        $views->status = Secure::secure_short_verified();
		$views->security = $security_lv;
		$views->enc_data = $nice_info['enc_data'];

        return $views;
    }

	public function checkplus_success(Request $request){
		$nice_info = NiceCheck::NiceCheck_success();
		if(is_array($nice_info)){
			$message = $nice_info['returnMsg'];
			$mobile_number = $nice_info['mobileno'];
			$status = 1;

			DB::table('users')->where('id',Auth::id())->update([
                "mobile_number" => $mobile_number,
                "country" => 'kr',
            ]);

			DB::table('btc_security_lv')->where('uid',Auth::id())->update([
                "mobile_verified" => 1,
            ]);

		}else{
			$message = $nice_info;
			$status = 0;

		}
		$views = view(session('theme').'.'.$this->device.'.'.'mypage.nicecheck_return');

		$views->message = $message;
		$views->status = $status;

		return $views;
	}

	public function checkplus_fail(Request $request){
		$nice_info = NiceCheck::NiceCheck_fail();

		$status = 0;
		$message = $nice_info;

		$views = view(session('theme').'.'.$this->device.'.'.'mypage.nicecheck_return');

		$views->message = $message;
		$views->status = $status;

		return $views;
	}


	public function security_setting_document(Request $request){
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');

        if(!Auth::check()){
            return Redirect::route('main');
        }

		$storage_save_path = 'public/image/security';

		$security = DB::table('btc_security_lv')->where('uid',Auth::id())->first();
		$path1 = $security->document_1;
		$path2 = $security->document_2;

		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$old_path1 = $storage_save_path.'/'.$path1;
				if(Storage::exists($old_path1)) {
					Storage::delete($old_path1);
				}
				$path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
			}
		}
		if($request->hasFile('file2')){
			if ($request->file('file2')->isValid()) {
				$old_path2 = $storage_save_path.'/'.$path2;
				if(Storage::exists($old_path2)) {
						Storage::delete($old_path2);
				}
				$path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
			}
		}
		DB::table('btc_security_lv')->where('uid',Auth::id())->update([
			'document_1' => env('APP_URL')."/storage/image/security/".$path1,
			'document_2' => env('APP_URL')."/storage/image/security/".$path2,
			'document_verified' => 2,

		]);

        return Redirect::route('mypage.security_setting');
    }

	public function security_setting_account(Request $request){
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');

        if(!Auth::check()){
            return Redirect::route('main');
        }

		$storage_save_path = 'public/image/security';

		$security = DB::table('btc_security_lv')->where('uid',Auth::id())->first();
		$path1 = $security->account_1;
		$path2 = $security->account_2;

		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$old_path1 = $storage_save_path.'/'.$path1;
				if(Storage::exists($old_path1)) {
						Storage::delete($old_path1);
				}
				$path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
			}
		}
		if($request->hasFile('file2')){
			if ($request->file('file2')->isValid()) {
				$old_path2 = $storage_save_path.'/'.$path2;
				if(Storage::exists($old_path2)) {
						Storage::delete($old_path2);
				}
				$path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
			}
		}
		DB::table('btc_security_lv')->where('uid',Auth::id())->update([
			'account_num' => $request->account_num,
			'account_bank' => $request->account_bank,
			'account_1' => env('APP_URL')."/storage/image/security/".$path1,
			'account_2' => env('APP_URL')."/storage/image/security/".$path2,
			'account_verified' => 2,

		]);

        return Redirect::route('mypage.security_setting');
    }
}
