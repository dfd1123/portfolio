<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\File_store;

use DB;
use Auth;
use File;

class ColorController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Color controller';
    }

    public function __construct(){
       
    }

    public function index()
    {
        try{
            // 매장정보 가져오기 
            $colors = DB::table('colors')->get();

            $main_colors = array();
            $sub_colors = array();

            foreach($colors as $color){
                if($color->kind === 1){
                    array_push($main_colors, $color);
                }else{
                    array_push($sub_colors, $color);
                }
            }

            if($colors == null){
                $this->res['query'] = null;
                $this->res['msg'] = "색상 정보가 없음!";
                $this->res['state'] = config('res_code.NO_DATA');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }

            $query = array(
                "main_colors" => $main_colors,
                "sub_colors" => $sub_colors
            );

            $this->res['query'] = $query;
            $this->res['msg'] = "성공!";
            $this->res['state'] = config('res_code.OK');
        }catch(exception $e){
            $this->res['query'] =null;
            $this->res['msg'] = "판매자 정보 조회 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function show(Request $request, $req){
        switch($req){
            case 'lists_admin': 
                try{
                    // 매장정보 가져오기 
                    $colors = DB::table('colors')->where('kind',1)->get();
        
                    $this->res['query'] = $colors;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "판매자 정보 조회 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;

            case 'lists_store': 
                try{
                    // 매장정보 가져오기 
                    $colors = DB::table('colors')->where('kind',2)->get();
        
                    $this->res['query'] = $colors;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "판매자 정보 조회 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request){
        if(!$request->filled('color_name', 'kind')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if($request->hasFile('color_img')){
            $color_imgs = File_store::Image_store('color', $request->color_img);

            if($color_imgs == 'EXT_ERR'){  //이미지 에러
                $this->res['query'] =null;
                $this->res['msg'] = "컬러 이미지 확장자 에러!"; 
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($color_imgs == 'VALID_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "컬러 이미지 유효성 에러!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($color_imgs == 'PARAM_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "컬러 이미지 첨부 필수!";
                $this->res['state'] = config('res_code.PARAM_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        }else{
            $color_imgs = [];
        }

        $uid = Auth::guard('api')->id();
        $color_name = $request->color_name;
        $kind = $request->kind;

        $exists = DB::table('colors')->where('color_name', $color_name)->exists();

        if($exists){
            $this->res['query'] =null;
            $this->res['msg'] = "색상 이미 존재함"; 
            $this->res['state'] = 55;
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try {
            $insert = DB::table('colors')->insert([
                'uid' => $uid,
                'color_name' => $color_name,
                'kind' => $kind,
                'color_img' => json_encode($color_imgs),
                'created_at' => DB::raw('now()'),
            ]);
            $this->res['query'] = $insert;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        
        } catch(Exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)"; 
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req){
        switch($req){
            case 'update': 
                if(!$request->filled('color_name', 'id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $color_id = $request->id;
                $uid = Auth::guard('api')->id();
                $query = DB::table('colors')->where('idcolors', $color_id)->first();

                if($query->kind == 1){
                    if($admin_id != $query->uid){ //admin 권한 체크 만들어야됨
                        $this->res['query'] = null;
                        $this->res['msg'] = "관리자 권한 없음";
                        $this->res['state'] = config('res_code.NO_AUTH');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }else{
                    if($uid != $query->uid){
                        $this->res['query'] = null;
                        $this->res['msg'] = "판매자 권한 없음";
                        $this->res['state'] = config('res_code.NO_AUTH');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }

                    if($request->hasFile('color_img')){
                        $color_imgs = File_store::Image_update('color', $request->color_img, json_decode($query->color_img), 0);
                        if($color_imgs == 'EXT_ERR'){  //이미지 에러
                            $this->res['query'] =null;
                            $this->res['msg'] = "컬러 이미지 확장자 에러!"; 
                            $this->res['state'] = config('res_code.EXT_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }else if($color_imgs == 'VALID_ERR'){
                            $this->res['query'] = null;
                            $this->res['msg'] = "컬러 이미지 유효성 에러!";
                            $this->res['state'] = config('res_code.IMG_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }else if($color_imgs == 'PARAM_ERR'){
                            $this->res['query'] = null;
                            $this->res['msg'] = "컬러 이미지 첨부 필수!";
                            $this->res['state'] = config('res_code.PARAM_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }
                    }else{
                        $color_imgs = json_decode($query->color_img);
                    }

                    $color_name = $request->color_name;

                    $update = DB::table('colors')->where('idcolors',$color_id)->update([
                        'color_name' => $color_name,
                        'color_img' => json_encode($color_imgs),
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }
            break;
        }
    }

    public function destroy(){
        
    }
}
