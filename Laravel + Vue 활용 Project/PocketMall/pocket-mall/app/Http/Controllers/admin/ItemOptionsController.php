<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use File;

class ItemOptionsController extends Controller
{
    public function index(Request $request){
        $views = view('admin.item_options.list');

        $categorys = DB::table('category')->get();

        if($request->filled('ca_id')){
            $items = DB::table('items')->where('ca_id', $request->ca_id)->get();
            $ca_id = $request->ca_id;
        }else{
            $items = DB::table('items')->where('ca_id', $categorys[0]->ca_id)->get();
            $ca_id = $categorys[0]->ca_id;
        }

        $item_options = DB::table('item_options')->get();

        $views->categorys = $categorys;
        $views->items = $items;
        $views->item_options = $item_options;
        $views->ca_id = $ca_id;

        return $views;
    }

    public function create(Request $request){
        $views = view('admin.item_options.create');
        if(!$request->filled('item_id')){
            return redirect() -> back() -> with('jsAlert', '잘못된 접근 입니다.');
        }
        
        $item = DB::table('items')->where('item_id', $request->item_id)->first();

        $views->item = $item;

        return $views;
    }

    public function edit($id){
        $views = view('admin.item_options.view');
        $option = DB::table('item_options')->where('op_id', $id)->first();
        $item = DB::table('items')->where('item_id', $option->item_id)->first();

        if($option->op_images){
            $op_images = json_decode($option->op_images);
        }else{
            $op_images = null;
        }

        $views->item = $item;
        $views->op_images = $op_images;
        $views->option = $option;

        return $views;
    }

    public function store(Request $request){
        if(!$request->filled('item_id') || $request->ca_id === ''){
            return redirect() -> back() -> with('jsAlert', '필수 요소 부족!');
        }

        if(!$request->filled('op_type')){
            return redirect() -> back() -> with('jsAlert', '옵션 타입을 입력하세요!');
        }

        if(!$request->filled('op_concept')){
            return redirect() -> back() -> with('jsAlert', '옵션 컨셉을 입력하세요!');
        }

        if(!$request->filled('op_simple_intro')){
            return redirect() -> back() -> with('jsAlert', ' 옵션 간력 설명을 입력하세요!');
        }

        if(!$request->filled('op_intro')){
            return redirect() -> back() -> with('jsAlert', '옵션 상세 설명을 입력하세요!');
        }

        if(!$request->filled('op_sale_price')){
            return redirect() -> back() -> with('jsAlert', '옵션 추가 가격을 입력하세요!');
        }

        if(!$request->hasFile('op_images')){
            return redirect() -> back() -> with('jsAlert', '옵션 이미지를 선택하세요!');
        }

        $images = File_store::Image_store('items', $request->op_images);
        if($images == 'EXT_ERR'){  //이미지 에러
            return redirect() -> back() -> with('jsAlert', '옵션 이미지 확장자 에러!');
        }else if($images == 'VALID_ERR'){
            return redirect() -> back() -> with('jsAlert', '옵션 이미지가 아님!');
        }else if($images == 'PARAM_ERR'){
            return redirect() -> back() -> with('jsAlert', '옵션 이미지 첨부 필수!');
        }

        $item_id = $request->item_id;
        $op_type = $request->op_type;
        $op_concept = $request->op_concept;
        $op_simple_intro = $request->op_simple_intro;
        $op_intro = $request->op_intro;
        $op_m_intro = $request->op_m_intro?$request->op_m_intro:NULL;
        $op_video_url = $request->filled('op_video_url')?$request->op_video_url:NULL;
        $op_origin_price = $request->op_sale_price;
        $op_sale_price = $request->op_sale_price;
        $op_tax_mny = bcdiv($op_sale_price, 1.1, 0);
        $op_vat_mny = bcsub($op_sale_price, $op_tax_mny, 0);
        $op_sell_yn = $request->op_sell_yn;

        DB::table('item_options')->insert([
            "item_id" => $item_id,
            "op_type" => $op_type,
            "op_concept" => $op_concept,
            "op_simple_intro" => $op_simple_intro,
            "op_intro" => $op_intro,
            "op_m_intro" => $op_m_intro,
            "op_video_url" => $op_video_url,
            "op_images" => json_encode($images),
            "op_origin_price" => $op_origin_price,
            "op_sale_price" => $op_sale_price,
            "op_tax_mny" => $op_tax_mny,
            "op_vat_mny" => $op_vat_mny,
            "op_sell_yn" => $op_sell_yn
        ]);

        return redirect() -> route('admin.items') -> with('jsAlert', '상품 옵션 생성 성공!');
    }

    public function update(Request $request, $id){
        if(!$request->filled('op_type')){
            return redirect() -> back() -> with('jsAlert', '옵션 타입을 입력하세요!');
        }

        if(!$request->filled('op_concept')){
            return redirect() -> back() -> with('jsAlert', '옵션 컨셉을 입력하세요!');
        }

        if(!$request->filled('op_simple_intro')){
            return redirect() -> back() -> with('jsAlert', ' 옵션 간력 설명을 입력하세요!');
        }

        if(!$request->filled('op_intro')){
            return redirect() -> back() -> with('jsAlert', '옵션 상세 설명을 입력하세요!');
        }

        if(!$request->filled('op_sale_price')){
            return redirect() -> back() -> with('jsAlert', '옵션 추가 가격을 입력하세요!');
        }

        $option = DB::table('item_options')->where('op_id', $id)->first();

        if($request->hasFile('op_images')){
            $images = File_store::Image_store('items', $request->op_images);
            if($images == 'EXT_ERR'){  //이미지 에러
                return redirect() -> back() -> with('jsAlert', '상품 이미지 확장자 에러!');
            }else if($images == 'VALID_ERR'){
                return redirect() -> back() -> with('jsAlert', '상품 이미지가 아님!');
            }else if($images == 'PARAM_ERR'){
                return redirect() -> back() -> with('jsAlert', '상품 이미지 첨부 필수!');
            }
        }else{
            if($option->op_images){
                $images = json_decode($option->op_images);
            }else{
                $images = null;
            }
        }

        $op_type = $request->op_type;
        $op_concept = $request->op_concept;
        $op_simple_intro = $request->op_simple_intro;
        $op_intro = $request->op_intro;
        $op_m_intro = $request->op_m_intro?$request->op_m_intro:NULL;
        $op_video_url = $request->filled('op_video_url')?$request->op_video_url:NULL;
        $op_origin_price = $request->op_sale_price;
        $op_sale_price = $request->op_sale_price;
        $op_tax_mny = bcdiv($op_sale_price, 1.1, 0);
        $op_vat_mny = bcsub($op_sale_price, $op_tax_mny, 0);
        $op_sell_yn = $request->op_sell_yn;

        DB::table('item_options')->where('op_id', $id)->update([
            "op_type" => $op_type,
            "op_concept" => $op_concept,
            "op_simple_intro" => $op_simple_intro,
            "op_intro" => $op_intro,
            "op_m_intro" => $op_m_intro,
            "op_video_url" => $op_video_url,
            "op_images" => $images?json_encode($images):NULL,
            "op_origin_price" => $op_origin_price,
            "op_sale_price" => $op_sale_price,
            "op_tax_mny" => $op_tax_mny,
            "op_vat_mny" => $op_vat_mny,
            "op_sell_yn" => $op_sell_yn
        ]);

        return redirect() -> route('admin.item_options') -> with('jsAlert', '상품 옵션 수정 성공!');
    }

    public function destroy($id){
        $path = '../storage/app/public/';
        $option = DB::table('item_options')->where('op_id', $id)->first();

        $files = json_decode($option->op_images);

        if ($option->op_images) {
            foreach ($files as $file) {
                if (File::exists($path.$file)) {
                    File::delete($path.$file);
                }
            }
        }

        DB::table('item_options')->where('op_id', $id)->delete();

        return redirect() -> route('admin.item_options') -> with('jsAlert', '상품 옵션 삭제 성공!');
    }
}
