<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use File;

class ItemController extends Controller
{
    public function index(){
        $views = view('admin.items.list');
        $categorys = DB::table('category')->orderBy('ca_id', 'ASC')->orderBy('ca_order','ASC')->get();
        $items = DB::table('items')->orderBy('item_id', 'DESC')->get();
        $views->categorys = $categorys;
        $views->items = $items;

        return $views;
    }

    public function create(){
        $views = view('admin.items.create');
        
        $categorys = DB::table('category')->orderBy('ca_id', 'ASC')->orderBy('ca_order','ASC')->get();
        $views->categorys = $categorys;

        return $views;
    }

    public function edit($id){
        $views = view('admin.items.view');
        $item = DB::table('items')->where('item_id', $id)->first();
        $categorys = DB::table('category')->orderBy('ca_id', 'ASC')->orderBy('ca_order','ASC')->get();

        if($item->images){
            $images = json_decode($item->images);
        }else{
            $images = null;
        }

        $views->item = $item;
        $views->images = $images;
        $views->categorys = $categorys;

        return $views;
    }

    public function store(Request $request){
        if(!$request->filled('ca_id') || $request->ca_id === ''){
            return redirect() -> back() -> with('jsAlert', '카테고리를 선택하세요!');
        }

        if(!$request->filled('title')){
            return redirect() -> back() -> with('jsAlert', ' 상품명을 입력하세요!');
        }

        if(!$request->filled('simple_intro')){
            return redirect() -> back() -> with('jsAlert', '상품 간략 설명을 입력하세요!');
        }

        if(!$request->filled('intro')){
            return redirect() -> back() -> with('jsAlert', '상품 상세 설명을 입력하세요!');
        }

        /*
        if(!$request->filled('video_url')){
            return redirect() -> back() -> with('jsAlert', '유튜브 영상 url을 입력하세요!');
        }
        */

        if(!$request->filled('origin_price')){
            return redirect() -> back() -> with('jsAlert', ' 상품 원래 가격을 입력하세요!');
        }

        if(!$request->filled('sale_price')){
            return redirect() -> back() -> with('jsAlert', ' 상품 판매 가격을 입력하세요!');
        }

        if(!$request->hasFile('images')){
            return redirect() -> back() -> with('jsAlert', ' 상품 기본 이미지를 선택하세요!');
        }

        $images = File_store::Image_store('items', $request->images);
        if($images == 'EXT_ERR'){  //이미지 에러
            return redirect() -> back() -> with('jsAlert', '상품 이미지 확장자 에러!');
        }else if($images == 'VALID_ERR'){
            return redirect() -> back() -> with('jsAlert', '상품 이미지가 아님!');
        }else if($images == 'PARAM_ERR'){
            return redirect() -> back() -> with('jsAlert', '상품 이미지 첨부 필수!');
        }

        $ca_id = $request->ca_id;
        $title = $request->title;
        $simple_intro = $request->simple_intro;
        $intro = $request->intro;
        $m_intro = $request->m_intro?$request->m_intro:NULL;
        $video_url = $request->video_url?$request->video_url:null;
        $origin_price = $request->origin_price;
        $sale_price = $request->sale_price;
        $tax_mny = bcdiv($sale_price, 1.1, 0);
        $vat_mny = bcsub($sale_price, $tax_mny, 0);
        $sell_yn = $request->sell_yn;

        $category = DB::table('category')->where('ca_id', $ca_id)->first();

        $ca_name = $category->ca_name;

        $insertId = DB::table('items')->insertGetId([
            "ca_id" => $ca_id,
            "ca_name" => $ca_name,
            "title" => $title,
            "simple_intro" => $simple_intro,
            "intro" => $intro,
            "m_intro" => $m_intro,
            "video_url" => $video_url,
            "images" => json_encode($images),
            "origin_price" => $origin_price,
            "sale_price" => $sale_price,
            "tax_mny" => $tax_mny,
            "vat_mny" => $vat_mny,
            "sell_yn" => $sell_yn
        ]);

        if($insertId){
            DB::table('item_options')->insert([
                "item_id" => $insertId,
                "op_type" => "A",
                "op_concept" => "기본형",
                "op_simple_intro" => "-",
                "op_images" => json_encode($images)
            ]);
        }

        return redirect() -> route('admin.items') -> with('jsAlert', '상품 생성 성공!');
    }

    public function update(Request $request, $id){
        if(!$request->filled('ca_id') || $request->ca_id === ''){
            return redirect() -> back() -> with('jsAlert', '카테고리를 선택하세요!');
        }

        if(!$request->filled('title')){
            return redirect() -> back() -> with('jsAlert', ' 상품명을 입력하세요!');
        }

        if(!$request->filled('simple_intro')){
            return redirect() -> back() -> with('jsAlert', '상품 간략 설명을 입력하세요!');
        }

        if(!$request->filled('intro')){
            return redirect() -> back() -> with('jsAlert', '상품 상세 설명을 입력하세요!');
        }

        if(!$request->filled('origin_price')){
            return redirect() -> back() -> with('jsAlert', ' 상품 원래 가격을 입력하세요!');
        }

        if(!$request->filled('sale_price')){
            return redirect() -> back() -> with('jsAlert', ' 상품 판매 가격을 입력하세요!');
        }

        $item = DB::table('items')->where('item_id', $id)->first();

        if($request->hasFile('images')){
            $images = File_store::Image_store('items', $request->images);
            if($images == 'EXT_ERR'){  //이미지 에러
                return redirect() -> back() -> with('jsAlert', '상품 이미지 확장자 에러!');
            }else if($images == 'VALID_ERR'){
                return redirect() -> back() -> with('jsAlert', '상품 이미지가 아님!');
            }else if($images == 'PARAM_ERR'){
                return redirect() -> back() -> with('jsAlert', '상품 이미지 첨부 필수!');
            }
        }else{
            //dd($item);
            if($item->images){
                $images = json_decode($item->images);
            }else{
                $images = NULL;
            }
        }

        $ca_id = $request->ca_id;
        $title = $request->title;
        $simple_intro = $request->simple_intro;
        $intro = $request->intro;
        $m_intro = $request->m_intro?$request->m_intro:NULL;
        $video_url = $request->video_url?$request->video_url:null;
        $origin_price = $request->origin_price;
        $sale_price = $request->sale_price;
        $tax_mny = bcdiv($sale_price, 1.1, 0);
        $vat_mny = bcsub($sale_price, $tax_mny, 0);
        $sell_yn = $request->sell_yn;

        $category = DB::table('category')->where('ca_id', $ca_id)->first();

        $ca_name = $category->ca_name;

        DB::table('items')->where('item_id', $id)->update([
            "ca_id" => $ca_id,
            "ca_name" => $ca_name,
            "title" => $title,
            "simple_intro" => $simple_intro,
            "intro" => $intro,
            "m_intro" => $m_intro,
            "video_url" => $video_url,
            "images" => $images?json_encode($images):NULL,
            "origin_price" => $origin_price,
            "sale_price" => $sale_price,
            "tax_mny" => $tax_mny,
            "vat_mny" => $vat_mny,
            "sell_yn" => $sell_yn
        ]);

        return redirect() -> route('admin.items') -> with('jsAlert', '상품 수정 성공!');
    }

    public function destroy($id){
        $path = '../storage/app/public/';
        $options = DB::table('item_options')->where('item_id', $id)->get();
        $item = DB::table('items')->where('item_id', $id)->first();

        foreach($options as $option){
            $files = json_decode($option->op_images);

            if ($option->op_images) {
                foreach ($files as $file) {
                    if (File::exists($path.$file)) {
                        File::delete($path.$file);
                    }
                }
            }
        }

        $files = json_decode($item->images);

        if ($item->images) {
            foreach ($files as $file) {
                if (File::exists($path.$file)) {
                    File::delete($path.$file);
                }
            }
        }

        DB::table('item_options')->where('item_id', $id)->delete();
        DB::table('items')->where('item_id', $id)->delete();

        return redirect() -> route('admin.items') -> with('jsAlert', '상품 삭제 성공!');
    }
}
