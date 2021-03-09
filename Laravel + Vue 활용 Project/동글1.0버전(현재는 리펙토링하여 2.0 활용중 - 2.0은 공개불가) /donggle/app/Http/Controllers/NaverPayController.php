<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;


class NaverPayController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */

    public function __invoke($id)
    {
        return 'Pay controller';
    }

    public function index()
    {
        $redirect_url = "https://dong-gle.co.kr/paying";


        $response = jsondecode($result);

        if($response->code == "Success"){
            $order_id = $response->body->detail->merchantPayKey;


        }else if($response->code == "Fail"){

        }else if($response->code == "InvalidMerchant"){

        }else if($response->code == "TimeExpired"){

        }else if($response->code == "TimeExpired"){

        }else if($response->code == "AlreadyOnGoing"){

        }else if($response->code == "AlreadyComplete"){

        }else if($response->code == "OwnerAuthFail"){

        }
        
        if($authResultCode === "0000"){

            $order_id = $request->Moid;

            $orders = DB::table('order')->where('order_id', $order_id)->get();
            
            $cart_count = count($orders);

            if($payMethod == 'VBANK' || $payMethod == 'BANK'){
                $od_status = 'deposit_wait';
            }else{
                $od_status = 'order_apply';
            }
            $company = DB::table('company')->first();
            $total_cp_id = '';
            $mobile_yn = '';
            if(count($orders) == 0){
                $data = Array(
                    'TID' => $txTid,
                    'AuthToken' => $authToken,
                    'MID' => $mid,
                    'Amt' => $amt,
                    'EdiDate' => $ediDate,
                    'SignData' => $signData,
                    'NetCancel' => '1',
                    'CharSet' => 'utf-8'
                );
                $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "결제하기 버튼누를때 주문정보가 제대로 삽입되지 않았습니다. 새로고침 후 다시 시도해주세요.";
                $this->res['state'] = config('res_code.NO_DATA');
                return redirect($redirect_url."?message=".$this->res['msg']);
            }
            DB::beginTransaction();
            foreach($orders as $key => $order){
                $total_cp_id = $order->total_cp_id;
                $mobile_yn = $order->mobile_yn;
                if($mobile_yn == 0){
                    if(env('APP_ENV'))
                    $redirect_url = env('APP_ENV') === 'production'?"https://dong-gle.co.kr/order/paying":"http://localhost:8080/order/paying";
                }else{
                    $redirect_url = env('APP_ENV') === 'production'?"https://m.dong-gle.co.kr/order/paying":"http://localhost:8081/order/paying";
                }

                $personal_cp_id = $order->coupon_id;

                $coupon = DB::table('coupon')->where('cp_id',$personal_cp_id)->first();
                
                if(isset($coupon->cp_id)){
                    $coupon_update = DB::table('coupon')->where('cp_id',$coupon->cp_id)->update([
                        "cp_use" => 1,
                    ]);
                    if($coupon_update == 0){
                        $data = Array(
                            'TID' => $txTid,
                            'AuthToken' => $authToken,
                            'MID' => $mid,
                            'Amt' => $amt,
                            'EdiDate' => $ediDate,
                            'SignData' => $signData,
                            'NetCancel' => '1',
                            'CharSet' => 'utf-8'
                        );
                        $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                        DB::rollBack();
                        DB::table('cart')->where('order_id',$order_id)->update([
                            "cp_id" => null,
                            "cp_price" => 0
                        ]);
                        $this->res['query'] = null;
                        $this->res['msg'] = "쿠폰 이미 사용되었거나 존재하지 않는쿠폰";
                        $this->res['state'] = config('res_code.NO_DATA');
                        return redirect($redirect_url."?message=".$this->res['msg']);
                    }
                    $coupon_log_insert = DB::table('coupon_use_log')->insert([
                        "cp_id" => $coupon->cp_id,
                        "cp_subject" => $coupon->cp_subject,
                        "mb_id" => $s_uid,
                        "od_id" => $order_id,
                        "item_id" => $order->item_id,
                        "cp_price" => $coupon->cp_price,
                        "cl_datetime" => DB::raw('now()'),
                    ]);
                    if($coupon_log_insert == 0){
                        $data = Array(
                            'TID' => $txTid,
                            'AuthToken' => $authToken,
                            'MID' => $mid,
                            'Amt' => $amt,
                            'EdiDate' => $ediDate,
                            'SignData' => $signData,
                            'NetCancel' => '1',
                            'CharSet' => 'utf-8'
                        );
                        $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                        DB::rollBack();
                        DB::table('cart')->where('order_id',$order_id)->update([
                            "cp_id" => null,
                            "cp_price" => 0
                        ]);
                        $this->res['query'] = null;
                        $this->res['msg'] = "쿠폰 사용이력 작성 실패";
                        $this->res['state'] = config('res_code.NO_DATA');
                        return redirect($redirect_url."?message=".$this->res['msg']);
                    }
                }
                $option = DB::table('item_option')->where('item_id',$order->item_id)->where('name',$order->option)->first();
                if(!isset($option->id)){
                    $data = Array(
                        'TID' => $txTid,
                        'AuthToken' => $authToken,
                        'MID' => $mid,
                        'Amt' => $amt,
                        'EdiDate' => $ediDate,
                        'SignData' => $signData,
                        'NetCancel' => '1',
                        'CharSet' => 'utf-8'
                    );
                    $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    $this->res['query'] = null;
                    $this->res['msg'] = "옵션정보 없음";
                    $this->res['state'] = config('res_code.NO_DATA');
                    return redirect($redirect_url."?message=".$this->res['msg']);
                }

                $stock_decrese = DB::table('item_option')->where('id',$option->id)->where('stock_qty','>',0)->decrement('stock_qty',$order->qty);
                if($stock_decrese == 0){
                    $data = Array(
                        'TID' => $txTid,
                        'AuthToken' => $authToken,
                        'MID' => $mid,
                        'Amt' => $amt,
                        'EdiDate' => $ediDate,
                        'SignData' => $signData,
                        'NetCancel' => '1',
                        'CharSet' => 'utf-8'
                    );
                    $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    $this->res['query'] = null;
                    $this->res['msg'] = "재고량 감소 실패 (품절됨)";
                    $this->res['state'] = config('res_code.NO_DATA');
                    return redirect($redirect_url."?message=".$this->res['msg']);
                }
                
                
            }
            
            try{
                //장바구니 상태변경
                $cart_update = DB::table('cart')->where('order_id',$order_id)->update([
                    "buy_yn" => 1,
                ]);
                if($cart_update == 0){
                    $data = Array(
                        'TID' => $txTid,
                        'AuthToken' => $authToken,
                        'MID' => $mid,
                        'Amt' => $amt,
                        'EdiDate' => $ediDate,
                        'SignData' => $signData,
                        'NetCancel' => '1',
                        'CharSet' => 'utf-8'
                    );
                    $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    $this->res['query'] = null;
                    $this->res['msg'] = "장바구니 상태값 변경 실패";
                    $this->res['state'] = config('res_code.NO_DATA');
                    return redirect($redirect_url."?message=".$this->res['msg']);
                }
                //전체쿠폰 작성이력
                $total_coupon = DB::table('coupon')->where('cp_id',$total_cp_id)->first();
                if(isset($total_coupon->cp_id)){
                    $total_coupon_update = DB::table('coupon')->where('cp_id',$total_coupon->cp_id)->update([
                        "cp_use" => 1,
                    ]);
                    if($total_coupon_update == 0){
                        $data = Array(
                            'TID' => $txTid,
                            'AuthToken' => $authToken,
                            'MID' => $mid,
                            'Amt' => $amt,
                            'EdiDate' => $ediDate,
                            'SignData' => $signData,
                            'NetCancel' => '1',
                            'CharSet' => 'utf-8'
                        );
                        $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                        DB::rollBack();
                        DB::table('cart')->where('order_id',$order_id)->update([
                            "cp_id" => null,
                            "cp_price" => 0
                        ]);
                        $this->res['query'] = null;
                        $this->res['msg'] = "전체쿠폰 이미 사용되었거나 존재하지 않는쿠폰";
                        $this->res['state'] = config('res_code.NO_DATA');
                        return redirect($redirect_url."?message=".$this->res['msg']);
                    }
                    $coupon_log_insert = DB::table('coupon_use_log')->insert([
                        "cp_id" => $total_coupon->cp_id,
                        "cp_subject" => $total_coupon->cp_subject,
                        "mb_id" => $s_uid,
                        "od_id" => $order_id,
                        "cp_price" => $total_coupon->cp_price,
                        "cl_datetime" => DB::raw('now()'),
                    ]);
                    if($coupon_log_insert == 0){
                        $data = Array(
                            'TID' => $txTid,
                            'AuthToken' => $authToken,
                            'MID' => $mid,
                            'Amt' => $amt,
                            'EdiDate' => $ediDate,
                            'SignData' => $signData,
                            'NetCancel' => '1',
                            'CharSet' => 'utf-8'
                        );
                        $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                        DB::rollBack();
                        DB::table('cart')->where('order_id',$order_id)->update([
                            "cp_id" => null,
                            "cp_price" => 0
                        ]);
                        $this->res['query'] = null;
                        $this->res['msg'] = "전체쿠폰 사용이력 작성 실패";
                        $this->res['state'] = config('res_code.NO_DATA');
                        return redirect($redirect_url."?message=".$this->res['msg']);
                    }
                }

                $data = Array(
                    'TID' => $txTid,
                    'AuthToken' => $authToken,
                    'MID' => $mid,
                    'Amt' => $amt,
                    'EdiDate' => $ediDate,
                    'SignData' => $signData,
                    'CharSet' => 'utf-8'
                );		
                $response = json_decode($this->reqPost($data, $nextAppURL)); //승인 호출
                
                if($response->ResultCode == '3001'){ //신용카드 성공
                    $insert_personalpay = DB::table('personalpay')->insert([
                        'order_id' => $order_id,
                        'pp_resultcode' => $response->ResultCode,
                        'pp_name' => $response->BuyerName,
                        'pp_email' => $response->BuyerEmail,
                        'pp_hp' => $response->BuyerTel,
                        'pp_content' => $response->GoodsName,
                        'pp_price' => $response->Amt,
                        'pp_pg' => "NICEPAY",
                        'pp_tno' => $response->TID,
                        'pp_apply_no' => $response->BuyerName,
                        'pp_receipt_price' => 0,
                        'pp_settle_case' => $response->PayMethod,
                        'pp_receipt_time' => $response->AuthDate,
                        'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                        'pp_ip' => $_SERVER["REMOTE_ADDR"],
                        'created_at' => DB::raw("now()"),
                        'updated_at' => DB::raw("now()"),
                        'pp_result' => json_encode($response),
                    ]);

                    $update_pg_result = DB::table('order')->where('order_id',$order_id)->update([
                        "od_status" => "order_apply",
                        "hope_date" => DB::raw("DATE_ADD(CURDATE(), INTERVAL + hope_day DAY)"),
                        "pg_result" => json_encode($response),
                        "settle_case" => $response->PayMethod,
                        "receipt_time" => $response->AuthDate,
                        "deleted" => 0,
                        "misu" => 0,
                        "created_at" => DB::raw('now()'),
                    ]);
                }else{ //실패
                    $this->res['msg'] = $response->ResultMsg;
                    $data = Array(
                        'TID' => $txTid,
                        'AuthToken' => $authToken,
                        'MID' => $mid,
                        'Amt' => $amt,
                        'EdiDate' => $ediDate,
                        'SignData' => $signData,
                        'NetCancel' => '1',
                        'CharSet' => 'utf-8'
                    );
                    $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행

                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    
                    return redirect($redirect_url."?message=".$this->res['msg']);
                }

                


            }catch(Exception $e){
                $e->getMessage();
                $data = Array(
                    'TID' => $txTid,
                    'AuthToken' => $authToken,
                    'MID' => $mid,
                    'Amt' => $amt,
                    'EdiDate' => $ediDate,
                    'SignData' => $signData,
                    'NetCancel' => '1',
                    'CharSet' => 'utf-8'
                );
                $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = $e->getMessage();
                $this->res['state'] = config('res_code.NO_DATA');
                return redirect($redirect_url."?message=".$this->res['msg']);
            }	


            DB::commit();
            $this->res['query'] = 1;
            $this->res['msg'] = "결제 완료";
            $this->res['state'] = config('res_code.OK');

        }else{
            $this->res['query'] = null;
            $this->res['msg'] = $ResultMsg;
            $this->res['state'] = $authResultCode;
            return redirect($redirect_url."?message=".$this->res['msg']."&state=".$this->res['state']);
        }
    
    

        return redirect($redirect_url."?order_id=".$order_id."&message=".$this->res['msg']."&state=".$this->res['state']);
    }

    public function show(Request $request, $req)
    {
    
    }


    public function store(Request $request)
    {   
        

            
            
    }

    public function update(Request $request, $req)
    {
        
    }

    //사용고민중.
    public function destroy(Request $request)
    {

    }

    private function reqPost(Array $data, $url){
        $requestData = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded;charset=euc-kr"',
                'content' => http_build_query($data),
                'timeout' => 15
            )
        ));
        
        $response = file_get_contents($url, FALSE, $requestData);
        return $response;
    }

    
}
