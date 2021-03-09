<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;
use Session;

use Facades\App\Classes\Secure;
use Facades\App\Classes\NiceCheck;


class SecurityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
     
    public function security()
    {
        $this->middleware('signed')->only('verify');
        $status = Secure::secure_short_verified();
        
        $security_lv = DB::table('btc_security_lv')->where('uid', Auth::id())->first();
        $nice_info = NiceCheck::NiceCheck_main();

        info($nice_info);
		
		$views = view('security.security');
		
        $views->status = $status;
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
		$views = view('security.nicecheck_return');
		
		$views->message = $message;
		$views->status = $status;
		
		return $views;
	}
	
	public function checkplus_fail(Request $request){
		$nice_info = NiceCheck::NiceCheck_fail();
		
		$status = 0;
		$message = $nice_info;	
		
		$views = view('security.nicecheck_return');
		
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
				info($old_path1);
				if(Storage::exists($old_path1)) {
						Storage::delete($old_path1);
				}
				$path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
			}
		}
		if($request->hasFile('file2')){
			if ($request->file('file2')->isValid()) {
				$old_path2 = $storage_save_path.'/'.$path2;
				info($old_path2);
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

        return redirect()->back()->with('jsAlert','신분증 자료를 제출하셨습니다. 관리자에서 승인할 시 신분증 인증이 완료됩니다.');
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
				info($old_path1);
				if(Storage::exists($old_path1)) {
						Storage::delete($old_path1);
				}
				$path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
			}
		}
		if($request->hasFile('file2')){
			if ($request->file('file2')->isValid()) {
				$old_path2 = $storage_save_path.'/'.$path2;
				info($old_path2);
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

        return redirect()->back()->with('jsAlert','계좌 자료를 제출하셨습니다. 관리자에서 승인할 시 계좌 인증이 완료됩니다.');
    }
	
}
