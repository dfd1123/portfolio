<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use DB;
use Auth;

class MypageController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Mypage controller';
    }

    public function index()
    {
        return 'Mypage FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'header':
                $uid = Auth::guard('api')->id();
                
                $orderSubComplete = DB::table('order')
                ->select('order_id')
                ->where('s_uid',$uid)
                ->where(function($query){
                    $query
                    ->where('od_status','order_apply')
                    ->orwhere('od_status','delivery_wait')
                    ->orwhere('od_status','delivery_complete');
                })
                ->groupBy('order_id');
                $orderComplete = DB::table(DB::raw("({$orderSubComplete->toSql()}) as sub"))
                ->mergeBindings($orderSubComplete)->count();

                $orderSubCancel = DB::table('order')
                ->select('order_id')
                ->where('s_uid',$uid)
                ->where(function($query){
                    $query
                    ->where('od_status','refund_apply')
                    ->orwhere('od_status','order_cancel')
                    ->orwhere('od_status','refund_complete');
                })
                ->groupBy('order_id');
                $orderCancel = DB::table(DB::raw("({$orderSubCancel->toSql()}) as sub"))
                ->mergeBindings($orderSubCancel)->count();


                $couponCount = DB::table('coupon')->where('mb_id',$uid)->where('cp_use',0)->count();

                $response = array();

                $response["orderComplete"] = $orderComplete;
                $response["orderCancel"] = $orderCancel;
                $response["couponCount"] = $couponCount;
                
                $this->res['query'] = $response;
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
