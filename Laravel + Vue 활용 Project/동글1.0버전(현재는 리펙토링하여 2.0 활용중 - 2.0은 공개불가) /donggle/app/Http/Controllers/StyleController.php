<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;

class StyleController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Style controller';
    }

    public function index()
    {
        return 'API FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list': 
                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                try {
                    $query = DB::table('style')->offset($offset)->limit(20)->get();
                    
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                    
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 
            
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if($request->filled('ca_name', 'style_tags') && $request->hasFile('style_img')){
            $ca_name = $request->ca_name;
            $style_tags = $request->style_tags;

            $style_hash_arr = array();
            foreach($style_tags as $style_tag){
                array_push($style_hash_arr,$style_tag);
            }

            $images = File_store::Image_store('style', $request->style_img);
            if($images == 'EXT_ERR'){
                $this->res['query'] =null;
                $this->res['msg'] = "이미지 확장자 에러!"; 
                $this->res['state'] = config('res_code.EXT_ERR');
            }else if($images == 'VALID_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "이미지가 아님!";
                $this->res['state'] = config('res_code.IMG_ERR');
            }else if($images == 'PARAM_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "이미지 첨부 필수!";
                $this->res['state'] = config('res_code.PARAM_ERR');
            }else{
                try {
                    $insert = DB::table('style')->insert([
                        'ca_name' => $ca_name,
                        'style_tag' => json_encode($style_hash_arr),
                        'style_img' => json_encode($images),
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
            }
        }else{
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        if(!$request->filled('ca_name', 'style_tags')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
        }

        $ca_name = $request->ca_name;
        $style_tags = $request->style_tags;

        $style_hash_arr = array();
        foreach($style_tags as $style_tag){
            array_push($style_hash_arr,$style_tag);
        }
        $images = File_store::Image_store('style', $request->style_img);

        if($request->hasFile('style_img')){ //이미지 변경 있을때
            $update = DB::table('style')->update([
                'ca_name' => $ca_name,
                'style_tag' => json_encode($style_hash_arr),
                'style_img' => json_encode($images),
            ]);
        }else{ //이미지 변경 없을때
            $update = DB::table('style')->update([
                'ca_name' => $ca_name,
                'style_tag' => json_encode($style_hash_arr),
            ]);
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
