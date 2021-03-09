<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;


class CompanyController extends Controller
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
     
     
    public function company()
    {
    	$company = DB::table('btc_payment_company')->where('uid',Auth::user()->id)->first();
		
		$views = view('company.company');
		
		$views->company = $company;
		
        return $views;
    }
	
	public function create(Request $request)
    {
		$storage_save_path = 'public/image/company';
		$company = DB::table('btc_payment_company')->where('uid',Auth::user()->id)->first();
		
		if(isset($company)){
			$path0 = $company->company_file;
			$path1 = $company->document_file;
			$path2 = $company->account_file;
			if($request->hasFile('file0')){
				if ($request->file('file0')->isValid()) {
					$old_path0 = $path0;
					info($old_path0);
					if(Storage::exists($old_path0)) {
							Storage::delete($old_path0);
					}
					$path0 = str_replace($storage_save_path.'/', "", $request->file0->store($storage_save_path));
				}
			}
			if($request->hasFile('file1')){
				if ($request->file('file1')->isValid()) {
					$old_path1 = $path1;
					info($old_path1);
					if(Storage::exists($old_path1)) {
							Storage::delete($old_path1);
					}
					$path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
				}
			}
			if($request->hasFile('file2')){
				if ($request->file('file2')->isValid()) {
					$old_path2 = $path2;
					info($old_path2);
					if(Storage::exists($old_path2)) {
							Storage::delete($old_path2);
					}
					$path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
				}
			}
			info($company->id);
			DB::table('btc_payment_company')->where('id',$company->id)->update([
				'company_name' => $request->input('company_name'),
				'company_number' => $request->input('company_number'),
				'company_sector' => $request->input('company_sector'),
				'company_type' => $request->input('company_type'),
				'company_file' => env('APP_URL')."/storage/image/company/".$path0,
				'document_file' => env('APP_URL')."/storage/image/company/".$path1,
				'account_file' => env('APP_URL')."/storage/image/company/".$path2,
				'account_num' => $request->input('account_num'),
				'account_bank' => $request->input('account_bank'),
				'company_confirm' => 0,
				'updated_at' => DB::raw('now()'),
			]);
			
	        return redirect()->back()->with('jsAlert','회사정보를 수정하셨습니다. 완료가 되었다 하더라도 수정을 하시면 심사를 다시 해야 페이서비스를 사용하실 수 있습니다.');
		}else{
			if($request->hasFile('file0')){
				if ($request->file('file0')->isValid()) {
					$path0 = str_replace($storage_save_path.'/', "", $request->file0->store($storage_save_path));
				}
			}
			if($request->hasFile('file1')){
				if ($request->file('file1')->isValid()) {
					$path1 = str_replace($storage_save_path.'/', "", $request->file1->store($storage_save_path));
				}
			}
			if($request->hasFile('file2')){
				if ($request->file('file2')->isValid()) {
					$path2 = str_replace($storage_save_path.'/', "", $request->file2->store($storage_save_path));
				}
			}
			
	    	DB::table('btc_payment_company')->insert([
				'uid' => Auth::user()->id,
				'username' => Auth::user()->username,
				'fullname' => Auth::user()->fullname,
				'company_name' => $request->input('company_name'),
				'company_number' => $request->input('company_number'),
				'company_sector' => $request->input('company_sector'),
				'company_type' => $request->input('company_type'),
				'company_file' => env('APP_URL')."/storage/image/company/".$path0,
				'document_file' => env('APP_URL')."/storage/image/company/".$path1,
				'account_file' => env('APP_URL')."/storage/image/company/".$path2,
				'account_num' => $request->input('account_num'),
				'account_bank' => $request->input('account_bank'),
				'created_at' => DB::raw('now()'),
				'updated_at' => DB::raw('now()'),
			]);
			
	        return redirect()->back()->with('jsAlert','회사정보를 제출하셨습니다. 심사가 통과되면 페이서비스를 사용하실 수 있습니다.');
		}
		
    }
	
	
	
}
