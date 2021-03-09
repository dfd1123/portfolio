<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use DB;
use Auth;
use File;

class CouponUseLogController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'CouponUseLog controller';
    }

    public function index()
    {
        return 'CouponUseLog FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                if(!$request->filled('start_date','end_date')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $limit = $request->filled('limit')?$request->limit:15;
                $page = $request->filled('page')?$request->page:1;

                $uid = Auth::guard('api')->id();

                if($request->filled('offset')){
                    $offset = $request->offset;
                }else{
                    $offset = ($page - 1)*$limit;
                }

                $start_date = $request->start_date." 00:00:00";
                $end_date = $request->end_date." 23:59:59";
                
                $count = DB::table('coupon_use_log')->where('mb_id',$uid)->whereBetween('cl_datetime',[$start_date,$end_date])->count();
                $coupon_use_log = DB::table('coupon_use_log')->where('mb_id',$uid)->whereBetween('cl_datetime',[$start_date,$end_date])->limit($limit)->offset($offset)->orderBy('id','DESC')->get();

                $query = array(
                    "count" => $count,
                    "page" => $page,
                    "offset" => $offset,
                    "coupon_use_log" => $coupon_use_log,
                );
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {

    }
}
