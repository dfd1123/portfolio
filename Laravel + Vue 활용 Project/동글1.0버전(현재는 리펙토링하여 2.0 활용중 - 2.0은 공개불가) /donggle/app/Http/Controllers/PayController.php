<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\App\Classes\Coolsms;

use DB;
use Auth;


class PayController extends Controller
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
        return 'Pay FOR API';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'mypage_main_list': 
                $limit = ($request->filled('limit'))?$request->limit:5;

                $orders = DB::table('order')
                ->join('items','items.item_id','=','order.item_id')
                ->select('order.*','items.images')
                ->where('s_uid',Auth::guard('api')->user()->id)
                ->where(function ($query){
                    $query
                    ->where('od_status','<>','order_cancel')
                    ->where('od_status','<>','refund_apply')
                    ->where('od_status','<>','refund_reject')
                    ->where('od_status','<>','refund_complete');
                })
                ->orderBy('order.created_at','DESC')->limit($limit)->get();
                $this->res['query'] = $orders;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {   
        $redirect_url = config('app.nice_complete_url');

            $authResultCode = $request->AuthResultCode;		// 인증결과 : 0000(성공)
            $authResultMsg = $request->AuthResultMsg;		// 인증결과 메시지
            $nextAppURL = $request->NextAppURL;				// 승인 요청 URL
            $txTid = $request->TxTid;						// 거래 ID
            $authToken = $request->AuthToken;				// 인증 TOKEN
            $payMethod = $request->PayMethod;				// 결제수단
            $mid = $request->MID;							// 상점 아이디
            $moid = $request->Moid;							// 상점 주문번호
            $amt = $request->Amt;							// 결제 금액
            $reqReserved = $request->ReqReserved;			// 상점 예약필드
            $netCancelURL = $request->NetCancelURL;		

            $response = "";
            
            if($authResultCode === "0000"){
                $ediDate = date("YmdHis");
                $merchantKey = config('app.merchantkey'); // 상점키
                $signData = bin2hex(hash('sha256', $authToken . $mid . $amt . $ediDate . $merchantKey, true));

                $order_id = $request->Moid;

                $orders = DB::table('order')->where('order_id', $order_id)->get();
                
                $cart_count = count($orders);

                if($payMethod == 'VBANK' || $payMethod == 'BANK'){
                    $od_status = 'deposit_wait';
                }else{
                    $od_status = 'order_apply';
                }
                $company = DB::table('company')->first();
                $uid = '';
                $total_cp_id = '';
                $mobile_yn = '';
                $item_name = '';
                $hope_day = '';
                $order_count = count($orders);
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
                    $hope_day = $order->hope_day;
                    $item_name = $order->item_name;
                    $uid = $order->s_uid;
                    $total_cp_id = $order->total_cp_id;
                    $mobile_yn = $order->mobile_yn;
                    if($mobile_yn == 0){
                        if(env('APP_ENV'))
                        $redirect_url = env('APP_ENV') === 'production'? config('app.nice_complete_url') : ( env('APP_ENV') === 'local' ? "http://localhost:8080/order/paying" : "https://dev.dong-gle.co.kr/order/paying");
                    }else{
                        $redirect_url = env('APP_ENV') === 'production'? config('app.nice_complete_mobile_url') : ( env('APP_ENV') === 'local' ? "http://localhost:8081/order/paying" : "https://devm.dong-gle.co.kr/order/paying");
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
                            "mb_id" => $order->s_uid,
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
                            "mb_id" => $order->s_uid,
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
                    $user = DB::table('users')->where('id',$uid)->first();
                    $response = json_decode($this->reqPost($data, $nextAppURL)); //승인 호출

                    $days = $hope_day;

                    for($i=0;$i<$days;$i++){
                        $day = date('N',strtotime("+".($i+1)."day"));
                        if($day>5)
                            $days++;
                    }
                    
                    if($response->ResultCode == '3001'){ //신용카드 성공
                        $insert_personalpay = DB::table('personalpay')->insert([
                            'order_id' => $order_id,
                            'uid' => $uid,
                            'pp_method' => 'pay',
                            'pp_resultcode' => $response->ResultCode,
                            'pp_name' => $user->name,
                            'pp_email' => $user->email,
                            'pp_hp' => $user->mobile_number,
                            'pp_content' => $response->GoodsName,
                            'pp_price' => $response->Amt,
                            'pp_pg' => "NICEPAY",
                            'pp_tno' => $response->TID,
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
                            "hope_date" => date( 'Y-m-d',strtotime("+".$days." day")),
                            "pg_result" => json_encode($response),
                            "settle_case" => $response->PayMethod,
                            "receipt_time" => $response->AuthDate,
                            "deleted" => 0,
                            "misu" => 0,
                            "created_at" => DB::raw('now()'),
                        ]);
                    }else if($response->ResultCode == '4000'){ //계좌이체 성공
                        $insert_personalpay = DB::table('personalpay')->insert([
                            'order_id' => $order_id,
                            'uid' => $uid,
                            'pp_method' => 'pay',
                            'pp_resultcode' => $response->ResultCode,
                            'pp_name' => $user->name,
                            'pp_email' => $user->email,
                            'pp_hp' => $user->mobile_number,
                            'pp_content' => $response->GoodsName,
                            'pp_price' => $response->Amt,
                            'pp_pg' => "NICEPAY",
                            'pp_tno' => $response->TID,
                            'pp_receipt_price' => $response->Amt,
                            'pp_settle_case' => $response->PayMethod,
                            'pp_account_number' => $response->BankName,
                            'pp_bank_number' => $response->BankCode,
                            'pp_cash' => $response->RcptType,
                            'pp_cash_no' => $response->RcptTID,
                            'pp_cash_info' => $response->RcptAuthCode,
                            'pp_deposit_name' => $user->name,
                            'pp_receipt_time' => $response->AuthDate,
                            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                            'pp_ip' => $_SERVER["REMOTE_ADDR"],
                            'created_at' => DB::raw("now()"),
                            'updated_at' => DB::raw("now()"),
                            'pp_result' => json_encode($response),
                        ]);

                        $update_pg_result = DB::table('order')->where('order_id',$order_id)->update([
                            "od_status" => "order_apply",
                            "hope_date" => date( 'Y-m-d',strtotime("+".$days." day")),
                            "pg_result" => json_encode($response),
                            "settle_case" => $response->PayMethod,
                            "receipt_time" => $response->AuthDate,
                            "deleted" => 0,
                            "misu" => 0,
                            "created_at" => DB::raw('now()'),
                        ]);
                    }else if($response->ResultCode == '4100'){ //가상계좌 성공
                        $insert_personalpay = DB::table('personalpay')->insert([
                            'order_id' => $order_id,
                            'uid' => $uid,
                            'pp_method' => 'pay',
                            'pp_resultcode' => $response->ResultCode,
                            'pp_name' => $user->name,
                            'pp_email' => $user->email,
                            'pp_hp' => $user->mobile_number,
                            'pp_content' => $response->GoodsName,
                            'pp_price' => $response->Amt,
                            'pp_pg' => "NICEPAY",
                            'pp_tno' => $response->TID,
                            'pp_apply_no' => $response->BuyerName,
                            'pp_receipt_price' => $response->Amt,
                            'pp_settle_case' => $response->PayMethod,
                            'pp_account_number' => $response->VbankBankName,
                            'pp_bank_number' => $response->VbankBankCode,
                            'pp_bank_account' => $response->VbankNum,
                            'pp_deposit_name' => $user->name,
                            'pp_receipt_time' => $response->AuthDate,
                            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                            'pp_ip' => $_SERVER["REMOTE_ADDR"],
                            'created_at' => DB::raw("now()"),
                            'updated_at' => DB::raw("now()"),
                            'pp_result' => json_encode($response),
                        ]);

                        $update_pg_result = DB::table('order')->where('order_id',$order_id)->update([
                            'bank_name' => $response->VbankBankName,
                            'bank_account' => $response->VbankBankCode,
                            'bank_number' => $response->VbankNum,
                            "pg_result" => json_encode($response),
                            "settle_case" => $response->PayMethod,
                            "receipt_time" => $response->AuthDate,
                            "deleted" => 0,
                            "created_at" => DB::raw('now()'),
                        ]);
                    }else if($response->ResultCode == 'A000'){ //휴대폰 소액결제 성공
                        $insert_personalpay = DB::table('personalpay')->insert([
                            'order_id' => $order_id,
                            'uid' => $uid,
                            'pp_method' => 'pay',
                            'pp_resultcode' => $response->ResultCode,
                            'pp_name' => $user->name,
                            'pp_email' => $user->email,
                            'pp_hp' => $user->mobile_number,
                            'pp_content' => $response->GoodsName,
                            'pp_price' => $response->Amt,
                            'pp_pg' => "NICEPAY",
                            'pp_tno' => $response->TID,
                            'pp_apply_no' => $response->BuyerName,
                            'pp_receipt_price' => 0,
                            'pp_settle_case' => $response->PayMethod,
                            'pp_deposit_name' => $user->name,
                            'pp_receipt_time' => $response->AuthDate,
                            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                            'pp_ip' => $_SERVER["REMOTE_ADDR"],
                            'created_at' => DB::raw("now()"),
                            'updated_at' => DB::raw("now()"),
                            'pp_result' => json_encode($response),
                        ]);

                        $update_pg_result = DB::table('order')->where('order_id',$order_id)->update([
                            "od_status" => "order_apply",
                            "hope_date" => date( 'Y-m-d',strtotime("+".$days." day")),
                            "pg_result" => json_encode($response),
                            "settle_case" => $response->PayMethod,
                            "receipt_time" => $response->AuthDate,
                            "misu" => 0,
                            "deleted" => 0,
                            "created_at" => DB::raw('now()'),
                        ]);
                    }else if($response->ResultCode == '7001'){ //현금영수증
                        $insert_personalpay = DB::table('personalpay')->insert([
                            'order_id' => $order_id,
                            'uid' => $uid,
                            'pp_method' => 'pay',
                            'pp_resultcode' => $response->ResultCode,
                            'pp_name' => $user->name,
                            'pp_email' => $user->email,
                            'pp_hp' => $user->mobile_number,
                            'pp_content' => $response->GoodsName,
                            'pp_price' => $response->Amt,
                            'pp_pg' => "NICEPAY",
                            'pp_tno' => $response->TID,
                            'pp_apply_no' => $response->BuyerName,
                            'pp_receipt_price' => $response->Amt,
                            'pp_settle_case' => $response->PayMethod,
                            'pp_deposit_name' => $user->name,
                            'pp_receipt_time' => $response->AuthDate,
                            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                            'pp_ip' => $_SERVER["REMOTE_ADDR"],
                            'created_at' => DB::raw("now()"),
                            'updated_at' => DB::raw("now()"),
                            'pp_result' => json_encode($response),
                        ]);

                        $update_pg_result = DB::table('order')->where('order_id',$order_id)->update([
                            "pg_result" => json_encode($response),
                            "settle_case" => $response->PayMethod,
                            "receipt_time" => $response->AuthDate,
                            "deleted" => 0,
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
                if($order_count > 1){
                    $txt = "주문하신 (".$item_name.") 외 ".bcsub($order_count,1,0)."개를 결제하셨습니다.";    
                }else{
                    $txt = "주문하신 (".$item_name.") 을 결제하셨습니다.";    
                }
                $sms = Coolsms::send_sms($user->mobile_number, $txt);
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
