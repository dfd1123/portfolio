<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class CouponController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Coupon controller';
    }

    public function index()
    {
        return 'Coupon FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'mypage_coupon':

                $uid = Auth::guard('api')->id();
                $orderBy = 'latest';
                if($request->filled('orderBy')){
                    $orderBy = $request->orderBy;
                }else{
                    $orderBy = 'latest';
                }

                if($orderBy === 'latest'){
                    $query = DB::table('coupon')->where('mb_id', $uid)
                    ->where('cp_use',0)
                    ->orderBy('cz_id','DESC')
                    ->get();
                }

                if($orderBy === 'discount'){
                    $query = DB::table('coupon')->where('mb_id', $uid)
                    ->where('cp_use',0)
                    ->orderBy('cp_method', 'ASC')
                    ->orderBy('cp_price','DESC')
                    ->get();
                }

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

            break;
            case 'my_coupon':
                if(!$request->filled('total_price')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "총 주문금액 값이 없음";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $total_price = $request->total_price;
                $uid = Auth::guard('api')->id();
                $orderBy = 'latest';
                if($request->filled('orderBy')){
                    $orderBy = $request->orderBy;
                }

                if($orderBy === 'latest'){
                    $query = DB::table('coupon')->where('mb_id', $uid)
                    ->where('cp_use',0)
                    ->where('cp_start','<=',DB::raw("CURDATE()"))
                    ->where('cp_end','>=',DB::raw("CURDATE()"))
                    ->orderBy('cz_id','DESC')
                    ->get();
                }

                if($orderBy === 'discount'){
                    $query = DB::table('coupon')->where('mb_id', $uid)
                    ->where('cp_use',0)
                    ->where('cp_start','<=',DB::raw("CURDATE()"))
                    ->where('cp_end','>=',DB::raw("CURDATE()"))
                    ->orderBy('cp_method', 'ASC')
                    ->orderBy('cp_price','DESC')
                    ->get();
                }
                $response = array();
                foreach($query as $row){
                    if($row->cp_method == 2 || $row->cp_method == 3){
                        if($row->cp_minimum <= $total_price){
                            $response[] = $row;
                        }
                    }else{
                        $response[] = $row;
                    }
                }

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

            break;
            case 'list_total':
                $uid = Auth::guard('api')->id();
                $query = DB::table('coupon')->where('mb_id', $uid)
                
                ->where(function($query){
                    $query->where('cp_method',2)->orwhere('cp_method',3);
                })
                ->where('cp_use',0)
                ->whereNull('cp_target')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            
            break;

            case 'list_individual':
                $uid = Auth::guard('api')->id();
                $query = DB::table('coupon')->where('mb_id', $uid)
                ->where(function($query){
                    $query->where('cp_method',0)->orwhere('cp_method',1);
                })
                ->where('cp_use',0)
                ->whereNotNull('cp_target')->get();

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        
        if(!$request->filled('cz_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        $cz_id = $request->cz_id;
        $coupon_zone = DB::table('coupon_zone')->where('id',$cz_id)->first();

        
        $cp_subject = $coupon_zone->cz_subject;
        $cp_method = $coupon_zone->cp_method;
        $period = $coupon_zone->cz_period;
        $cp_target = $coupon_zone->cp_target;
        $cp_start = date("Y-m-d");
        $cp_end = date("Y-m-d",strtotime("+".$period." days", strtotime($cp_start)));
        $mb_id = Auth::guard('api')->id();
        $cp_price = $coupon_zone->cp_price;
        $cp_type = $coupon_zone->cp_type;
        $cp_trunc = $coupon_zone->cp_trunc;
        $cp_minimum = $coupon_zone->cp_minimum;
        $cp_maximum = $coupon_zone->cp_maximum;

        while(1){// 쿠폰 중복 확인 있으면 새로 발급
            $cp_id = $this->coupon_generator();
            $exist = DB::table('coupon')->where('cp_id',$cp_id)->exists();
            if(!$exist){
                break; 
            }
        }
        
        try {
            $insert = DB::table('coupon')->insert([
                'cp_id' => $cp_id,
                'cp_subject' => $cp_subject,
                'cp_method' => $cp_method,
                'cp_target' => $cp_target,
                'mb_id' => $mb_id,
                'cz_id' => $cz_id,
                'cp_start' => $cp_start,
                'cp_end' => $cp_end,
                'cp_price' => $cp_price,
                'cp_type' => $cp_type,
                'cp_trunc' => $cp_trunc,
                'cp_minimum' => $cp_minimum,
                'cp_maximum' => $cp_maximum,
                'cp_use' => 0,
            ]);

            if($insert && $coupon_zone->cz_download > 0){
                DB::table('coupon_zone')->where('id',$cz_id)->update([
                    "cz_download" => $coupon_zone->cz_download - 1
                ]);
            }

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
            case 'download':
                if(!$request->filled('cp_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $cp_id = $request->cp_id;

                $exist = DB::table('coupon')->where('cp_id',$cp_id)->whereNull('mb_id')->exists();

                if(!$exist){
                    $this->res['query'] = null;
                    $this->res['msg'] = "이미 발급이 완료된 쿠폰입니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                
                $update = DB::table('coupon')->where('cp_id',$cp_id)->update([
                    "mb_id" => Auth::guard('api')->user()->id
                ]);

                if($update > 0){
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }else{
                    $this->res['query'] = $update;
                    $this->res['msg'] = "이미 삭제되었거나 권한이 없는 쿠폰아이디";
                    $this->res['state'] = config('res_code.NO_DATA');
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {  
        switch($req){
            case 'delete':
                if(!$request->filled('id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                
                $delete = DB::table('coupon')->where('id',$id)->where('mb_id',Auth::guard('api')->user()->id)->delete();
                if($delete > 0){
                    $this->res['query'] = $delete;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }else{
                    $this->res['query'] = $delete;
                    $this->res['msg'] = "이미 삭제되었거나 권한이 없는 쿠폰아이디";
                    $this->res['state'] = config('res_code.NO_DATA');
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    private function coupon_generator()
    {
        $len = 16;
        $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";

        srand((double)microtime()*1000000);

        $i = 0;
        $str = '';
        while ($i < $len) {
            $num = rand() % strlen($chars);
            $tmp = substr($chars, $num, 1);
            $str .= $tmp;
            $i++;
        }

        $str = preg_replace("/([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})/", "\\1-\\2-\\3-\\4", $str);

        return $str;
    }


}
