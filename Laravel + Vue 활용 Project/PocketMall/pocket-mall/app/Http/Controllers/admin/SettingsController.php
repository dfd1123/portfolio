<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class SettingsController extends Controller
{
    public function index(){
        $views = view('admin.settings.settings');
        $company = DB::table('settings')->first();

        $views->company = $company;

        return $views;
    }

    public function show($id){

    }

    public function store(Request $request){

    }

    public function update(Request $request, $id){
        if(!$request->filled('company_name')){
            return redirect() -> back() -> with('jsAlert', '회사명을 입력하세요!');
        }

        if(!$request->filled('ceo_name')){
            return redirect() -> back() -> with('jsAlert', ' 대표자명을 입력하세요!');
        }

        if(!$request->filled('tel')){
            return redirect() -> back() -> with('jsAlert', '회사 전화번호를 입력하세요!');
        }

        if(!$request->filled('email')){
            return redirect() -> back() -> with('jsAlert', '회사 이메일을 입력하세요!');
        }

        DB::table('settings')->update([
            "company_name" => $request->company_name,
            "ceo_name" => $request->ceo_name,
            "tel" => $request->tel,
            "email" => $request->email,
            "fax" => $request->fax?$request->fax:null,
            "cyber_sell_license" => $request->cyber_sell_license?$request->cyber_sell_license:null,
            "buisness_number" => $request->buisness_number?$request->buisness_number:null,
            "addr1" => $request->addr1?$request->addr1:null,
            "addr2" => $request->addr2?$request->addr2:null
        ]);

        return redirect() -> back() -> with('jsAlert', '회사 정보 수정 성공!');
    }

    public function destroy($id){

    }
}
