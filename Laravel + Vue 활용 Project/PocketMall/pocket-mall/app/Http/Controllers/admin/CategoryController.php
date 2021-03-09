<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class CategoryController extends Controller
{
    public function index(){
        $views = view('admin.category.list');
        $categorys = DB::table('category')->orderBy('ca_id', 'ASC')->orderBy('ca_order','ASC')->get();
        $views->categorys = $categorys;

        return $views;
    }

    public function store(){
        DB::table('category')->insert([
            "ca_name" => "새 카테고리"
        ]);

        return redirect() -> back() -> with('jsAlert', '카테고리 생성 성공!');
    }

    public function update(Request $request, $id){
        if(!$request->filled('ca_name')){
            return redirect() -> back() -> with('jsAlert', '카테고리명을 입력하세요!');
        }

        if(!$request->filled('ca_intro')){
            return redirect() -> back() -> with('jsAlert', ' 카테고리 인사글을 입력하세요!');
        }

        DB::table('category')->where('ca_id', $id)->update([
            "ca_name" => $request->ca_name,
            "ca_intro" => $request->ca_intro,
            "ca_use" => $request->ca_use,
            "ca_order" => $request->ca_order?$request->ca_order:1,
            "updated_at" => now()
        ]);

        return redirect() -> back() -> with('jsAlert', '카테고리 정보 수정 성공!');
    }

    public function destroy($id){
        DB::table('category')->where('ca_id', $id)->delete();

        return redirect() -> back() -> with('jsAlert', '카테고리 삭제 성공!');
    }
}
