<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;
use File;

class CouponZoneController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'CouponZone controller';
    }

    public function index()
    {
        return 'CouponZone API';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $uid = Auth::guard('api')->id();
                $today = date("Y-m-d");

                $query = DB::table('coupon_zone')
                ->where('cz_download', '>', 0)
                ->where('cz_start','<=',$today)
                ->where('cz_end','>=',$today)
                ->select('coupon_zone.*', DB::raw('(SELECT EXISTS(SELECT 1 FROM coupon WHERE mb_id = '.$uid.' and cz_id = coupon_zone.id)) AS get_coupon'));

                if($request->filled('orderBy')){
                    if($request->orderBy == 'latest'){
                        $query->orderBy('cz_datetime','DESC');
                    }else if($request->orderBy == 'discount'){
                        $query->orderBy('cp_type','DESC')->orderBy('cp_price','DESC');
                    }else{
                        $query->orderBy('cz_datetime','DESC');
                    }
                }else{
                    $query->orderBy('cz_datetime','DESC');
                }
                $query = $query->get();
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        
        if(!$request->filled('cz_type','cz_subject','cz_start','cz_end','cz_period','cp_method','cp_price','cp_type','cp_trunc','cp_minimum','cp_maximum','cz_cp_limit')
        || !$request->hasFile('cz_file')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $cz_type = $request->cz_type;
        $cz_subject = $request->cz_subject;
        $cz_start = $request->cz_start;
        $cz_end = $request->cz_end;
        $cz_period = $request->cz_period;
        $cp_method = $request->cp_method;
        if($cp_method == 0 || $cp_method == 1){
            $cp_target = $request->cp_target;
        }else{
            $cp_target = null;
        }
        $cp_price = $request->cp_price;
        $cp_type = $request->cp_type;
        $cp_trunc = $request->cp_trunc;
        $cp_minimum = $request->cp_minimum;
        $cp_maximum = $request->cp_maximum;
        $cz_cp_limit = $request->cz_cp_limit;

        $cz_file = File_store::Image_store('coupon', $request->cz_file);
        if($cz_file == 'EXT_ERR'){  //이미지 에러
            $this->res['query'] =null;
            $this->res['msg'] = "쿠폰 이미지 이미지 확장자 에러!"; 
            $this->res['state'] = config('res_code.EXT_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($cz_file == 'VALID_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "쿠폰 이미지 이미지가 아님!";
            $this->res['state'] = config('res_code.IMG_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($cz_file == 'PARAM_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "쿠폰 이미지 이미지 첨부 필수!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try {
            $insert = DB::table('coupon_zone')->insert([
                'cz_type' => $cz_type,
                'cz_subject' => $cz_subject,
                'cz_start' => $cz_start,
                'cz_end' => $cz_end,
                'cz_file' => json_encode($cz_file),
                'cz_period' => $cz_period,
                'cp_method' => $cp_method,
                'cp_target' => $cp_target,
                'cp_price' => $cp_price,
                'cp_type' => $cp_type,
                'cp_trunc' => $cp_trunc,
                'cp_minimum' => $cp_minimum,
                'cp_maximum' => $cp_maximum,
                'cz_datetime' => DB::raw('now()'),
                'cz_cp_limit' => $cz_cp_limit,
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
                if(!$request->filled('id','cz_type','cz_subject','cz_start','cz_end','cz_period','cp_price','cp_type','cp_trunc','cp_minimum','cp_maximum','cz_cp_limit')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $cz_id = $request->id;
                $cz_type = $request->cz_type;
                $cz_subject = $request->cz_subject;
                $cz_start = $request->cz_start;
                $cz_end = $request->cz_end;
                $cz_period = $request->cz_period;
                $cp_price = $request->cp_price;
                $cp_type = $request->cp_type;
                $cp_trunc = $request->cp_trunc;
                $cp_minimum = $request->cp_minimum;
                $cp_maximum = $request->cp_maximum;
                $cz_cp_limit = $request->cz_cp_limit;

                $coupon_zone = DB::table('coupon_zone')->where('id',$cz_id)->first();
                $coupon_index = array(0);
                if($request->hasFile('cz_file')){
                    
                    $cz_file = File_store::Image_update('coupon', $request->cz_file, json_decode($coupon_zone->cz_file), $coupon_index);
                    if($cz_file == 'EXT_ERR'){  //이미지 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "쿠폰 이미지 이미지 확장자 에러!"; 
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($cz_file == 'VALID_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "쿠폰 이미지 이미지가 아님!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($cz_file == 'PARAM_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "쿠폰 이미지 이미지 첨부 필수!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }else{
                    $cz_file = json_decode($coupon_zone->cz_file);
                }

                try {
                    $update = DB::table('coupon_zone')->where('id',$cz_id)->update([
                        'cz_type' => $cz_type,
                        'cz_subject' => $cz_subject,
                        'cz_start' => $cz_start,
                        'cz_end' => $cz_end,
                        'cz_file' => json_encode($cz_file),
                        'cz_period' => $cz_period,
                        'cp_price' => $cp_price,
                        'cp_type' => $cp_type,
                        'cp_trunc' => $cp_trunc,
                        'cp_minimum' => $cp_minimum,
                        'cp_maximum' => $cp_maximum,
                        'cz_cp_limit' => $cz_cp_limit,
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
    public function destroy(Request $request, $req)
    {
       

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }



}
