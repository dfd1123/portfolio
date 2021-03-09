<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\Wallet;

use DB;
use Auth;
use Storage;

class SecurityController extends Controller
{
    public function security_setting_document(Request $request){	
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
        
        return response()->json(null, 200);
    }

	public function security_setting_account(Request $request){
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
        
        return response()->json(null, 200);
    }
}