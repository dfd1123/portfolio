<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;
use File;

class BannerController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
     /**
	 * @OA\Get(
	 *     path="/banner",
	 *     @OA\Response(response="200", description="Display a listing of projects.")
	 * )
	 */
    public function __invoke($id)
    {
        return 'Banner controller';
    }

    public function index()
    {
        return 'Banner FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'top':
                $query = DB::table('banner')
                ->where('bn_begin_time','<=',date('Y-m-d'))
                ->where('bn_end_time','>=',date('Y-m-d'))
                ->where('bn_position', 'top')
                ->orderBy('bn_order','ASC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'main':
                $query = DB::table('banner')
                ->where('bn_begin_time','<=',date('Y-m-d'))
                ->where('bn_end_time','>=',date('Y-m-d'))
                ->where('bn_position', 'main')
                ->orderBy('bn_order','ASC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'head':
                $query = DB::table('banner')
                ->where('bn_begin_time','<=',date('Y-m-d'))
                ->where('bn_end_time','>=',date('Y-m-d'))
                ->where('bn_position', 'head')
                ->orderBy('bn_order','ASC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'left':
                $query = DB::table('banner')
                ->where('bn_begin_time','<=',date('Y-m-d'))
                ->where('bn_end_time','>=',date('Y-m-d'))
                ->where('bn_position', 'left')
                ->orderBy('bn_order','ASC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'right':
                $query = DB::table('banner')
                ->where('bn_begin_time','<=',date('Y-m-d'))
                ->where('bn_end_time','>=',date('Y-m-d'))
                ->where('bn_position', 'right')
                ->orderBy('bn_order','ASC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'foot':
                $query = DB::table('banner')
                ->where('bn_begin_time','<=',date('Y-m-d'))
                ->where('bn_end_time','>=',date('Y-m-d'))
                ->where('bn_position', 'foot')
                ->orderBy('bn_order','ASC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if(!$request->filled('bn_alt', 'bn_url','bn_new_win','bn_begin_time','bn_end_time','bn_order','bn_position')
        || !$request->hasFile('bn_img')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $bn_imgs = File_store::Image_store('banner', $request->bn_img);
        if($bn_imgs == 'EXT_ERR'){  //이미지 에러
            $this->res['query'] =null;
            $this->res['msg'] = "배너 이미지 확장자 에러!"; 
            $this->res['state'] = config('res_code.EXT_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($bn_imgs == 'VALID_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "배너 이미지가 아님!";
            $this->res['state'] = config('res_code.IMG_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($bn_imgs == 'PARAM_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "배너 이미지 첨부 필수!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $bn_alt = $request->bn_alt;
        $bn_url = $request->bn_url;
        $bn_new_win = $request->bn_new_win;
        $bn_begin_time = $request->bn_begin_time;
        $bn_end_time = $request->bn_end_time;
        $bn_order = $request->bn_order;
        $bn_alt = $request->bn_alt;
        $bn_position = $request->bn_position;

        try {
            $insert = DB::table('banner')->insert([
                'bn_alt' => $bn_alt,
                'bn_url' => $bn_url,
                'bn_new_win' => $bn_new_win,
                'bn_begin_time' => $bn_begin_time,
                'bn_end_time' => $bn_end_time,
                'bn_order' => $bn_order,
                'bn_alt' => $bn_alt,
                'bn_position' => $bn_position,
                'bn_img' => json_encode($bn_imgs),
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

    public function update(Request $request, $req)
    {
        switch($req){
            case 'update':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                if(!$request->filled('id', 'bn_alt', 'bn_url','bn_new_win','bn_begin_time','bn_end_time','bn_order','bn_position')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $bn_id = $request->id;
                $query = DB::table('banner')->where('id', $bn_id)->first();
                
                if($request->hasFile('bn_img')){
                    $bn_imgs = File_store::Image_update('banner', $request->bn_img, json_decode($query->bn_img), array(0));
                    if($bn_imgs == 'EXT_ERR'){  //이미지 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "배너 이미지 확장자 에러!"; 
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($bn_imgs == 'VALID_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "배너 이미지가 아님!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($bn_imgs == 'PARAM_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "배너 이미지 첨부 필수!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                    if(isset(json_decode($query->bn_img)[0])){
                        if(File::exists('../storage/app/public/image/banner'.json_decode($query->bn_img)[0])) {
                            File::delete('../storage/app/public/image/banner'.json_decode($query->bn_img)[0]);
                        }
                    }
                }else{
                    $bn_imgs = json_decode($query->bn_img);
                }
        
                $bn_alt = $request->bn_alt;
                $bn_url = $request->bn_url;
                $bn_new_win = $request->bn_new_win;
                $bn_begin_time = $request->bn_begin_time;
                $bn_end_time = $request->bn_end_time;
                $bn_order = $request->bn_order;
                $bn_alt = $request->bn_alt;
                $bn_position = $request->bn_position;
        
                try {
                    $update = DB::table('banner')->where('id', $bn_id)->update([
                        'bn_alt' => $bn_alt,
                        'bn_url' => $bn_url,
                        'bn_new_win' => $bn_new_win,
                        'bn_begin_time' => $bn_begin_time,
                        'bn_end_time' => $bn_end_time,
                        'bn_order' => $bn_order,
                        'bn_alt' => $bn_alt,
                        'bn_position' => $bn_position,
                        'bn_img' => json_encode($bn_imgs),
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                
                } catch(Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;
        }
  
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
