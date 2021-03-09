<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\App\Classes\Coolsms;

use DB;
use Auth;


class OrderController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */

    public function __construct(){
        $this->cst_id = "donggle2";
        $this->custKey = "122862243e51b6c01663125a5fd7b94c1ff26946a9aea1faa732995d52563309";
        $this->payple_curl = "https://cpay.payple.kr";
        $this->PCD_REFUND_KEY = "06d3ef3ed1b65bd8f7b747c773b917e40506f2664d0264a2bf6f6f721dd84daf";
        //$this->cst_id = "test";
        //$this->custKey = "abcd1234567890";
        //$this->payple_curl = "https://testcpay.payple.kr";
        //$this->PCD_REFUND_KEY = "a41ce010ede9fcbfb3be86b24858806596a9db68b79d138b147c3e563e1829a0";
        $this->referer = env('APP_URL');
    }

    public function __invoke($id)
    {
        return 'Order controller';
    }

    public function index()
    {
        return 'Order FOR API';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'mypage_main_list': 
                $limit = ($request->filled('limit'))?$request->limit:5;

                $orders = DB::table('order')
                ->join('items','items.item_id','=','order.item_id')
                ->select('order.*','items.images', 'items.possible_ready_term')
                ->where('s_uid',Auth::guard('api')->user()->id)
                ->where('order.deleted',0)
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

            case 'mypage_list': 
                if(!$request->filled('start_date','end_date')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $offset = ($request->filled('offset'))?$request->offset:0;
                $limit = ($request->filled('limit'))?$request->limit:5;
                $od_status = '%'.$request->od_status.'%';
                $start_date = $request->start_date." 00:00:00";
                $end_date = $request->end_date." 23:59:59";

                DB::enableQueryLog();

                $orders = DB::table('order')
                ->join('items','items.item_id','=','order.item_id')
                ->select('order.*','items.images', 'items.possible_ready_term')
                ->where('od_status','like',$od_status)
                ->where('order.deleted',0)
                ->where('s_uid',Auth::guard('api')->user()->id)
                ->whereBetween('order.created_at',[$start_date,$end_date])
                ->where(function ($query){
                    $query
                    ->where('od_status','<>','order_cancel')
                    ->where('od_status','<>','refund_apply')
                    ->where('od_status','<>','refund_reject')
                    ->where('od_status','<>','refund_complete');
                });

                $count = $orders->count();
                $orders = $orders->orderBy('order.order_no','DESC')->offset($offset)->limit($limit)->get();
                //dd(DB::getQueryLog());

                $query = array(
                    "count" => $count,
                    "orders" => $orders,
                );

                $this->res['query'] = $query;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'mypage_detail': 
                if(!$request->filled('order_id') && !$request->filled('order_no')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $order_id = $request->filled('order_id')?$request->order_id:null;
                $order_no = $request->filled('order_no')?$request->order_no:null;

                DB::enableQueryLog();
                
                if($order_id){
                    $orders = DB::table('order')
                    ->join('items','items.item_id','=','order.item_id')
                    ->select('order.*','items.images', 'items.possible_ready_term')
                    ->where('s_uid',Auth::guard('api')->user()->id)
                    ->where('order_id',$order_id)
                    ->where(function($query){
                        $query->where('od_status','!=','order_cancel')->orwhere('od_status','!=','refund_complete');
                    });

                    $count = $orders->count();
                    $orders = $orders->get();
                    //dd(DB::getQueryLog());
                    $request_cancel_price = 0;
                    foreach($orders as $order){
                        $request_cancel_price = bcsub($order->receipt_price, $order->cancel_price,0);
                    }
                    
                    $query = array(
                        "count" => 1,
                        "orders" => $orders,
                        "request_cancel_price" => $request_cancel_price
                    );
                }else{
                    $orders = DB::table('order')
                    ->join('items','items.item_id','=','order.item_id')
                    ->select('order.*','items.images')
                    ->where('s_uid',Auth::guard('api')->user()->id)
                    ->where('order_no',$order_no);

                    $count = 1;
                    $orders = $orders->get();
                    //dd(DB::getQueryLog());
                    
                    $query = array(
                        "count" => 1,
                        "orders" => $orders
                    );
                }

                $this->res['query'] = $query;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'mypage_detail_part': 
                if(!$request->filled('order_no')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $order_no = $request->filled('order_no')?$request->order_no:null;

                DB::enableQueryLog();
                
                
                $orders = DB::table('order')
                ->join('items','items.item_id','=','order.item_id')
                ->leftJoin('coupon','coupon.cp_id','=','order.total_cp_id')
                ->select('order.*','items.images','coupon.cp_minimum')
                ->where('s_uid',Auth::guard('api')->user()->id)
                ->where('order_no',$order_no);

                $count = 1;
                $orders = $orders->get();

                $receipt_price = 0;
                $coupon_discount = 0;
                $request_cancel_price = 0;

                foreach($orders as $order){
                    $order_id = $order->order_id;

                    $compare = DB::table('order')
                    ->where('order_id',$order_id)
                    ->where('od_status','order_apply')
                    ->count();

                    if($compare > 1){ //이 주문에 이 order_no 제외하고 아직 취소할게 남아있는 상태
                        if($order->cp_minimum != null){
                            $order_price = bcsub(bcsub($order->total_price, $order->this_price,0),$order->cancel_price,0);
                            if($order_price < $order->cp_minimum){
                                $request_cancel_price = bcsub($order->this_price,$order->coupon_discount,0); //전체 할인쿠폰적용된 가격 제외한 상품가격
                                if($request_cancel_price < 0){
                                    $request_cancel_price = 0;
                                }
                            }else{
                                $request_cancel_price = $order->this_price;
                            }
                        }else{
                            $request_cancel_price = $order->this_price;
                        }
                    }else{
                        $request_cancel_price = bcsub($order->receipt_price, $order->cancel_price,0);
                    }
                    $receipt_price = bcsub($order->receipt_price,$order->cancel_price,0);
                    $coupon_discount = $order->coupon_discount;
                    
                }
                //dd(DB::getQueryLog());

                $query = array(
                    "count" => 1,
                    "orders" => $orders,
                    "remain_receipt_price" => $receipt_price,
                    "coupon_discount" => $coupon_discount,
                    "request_cancel_price" => $request_cancel_price
                );
                

                $this->res['query'] = $query;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'mypage_cancel': 
                if(!$request->filled('start_date','end_date')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $offset = ($request->filled('offset'))?$request->offset:0;
                $limit = ($request->filled('limit'))?$request->limit:5;
                $od_status = '%'.$request->od_status.'%';
                $start_date = $request->start_date." 00:00:00";
                $end_date = $request->end_date." 23:59:59";

                DB::enableQueryLog();

                $orders = DB::table('order')
                ->join('items','items.item_id','=','order.item_id')
                ->select('order.*','items.images', 'items.possible_ready_term')
                ->where('od_status','like',$od_status)
                ->where('order.deleted',0)
                ->where('s_uid',Auth::guard('api')->user()->id)
                ->whereBetween('order.created_at',[$start_date,$end_date])
                ->where(function ($query){
                    $query
                    ->where('od_status','order_cancel')
                    ->orwhere('od_status','refund_apply')
                    ->orwhere('od_status','refund_reject')
                    ->orwhere('od_status','refund_complete');
                });

                $count = $orders->count();
                $orders = $orders->offset($offset)->limit($limit)->get();

                $query = array(
                    "count" => $count,
                    "orders" => $orders,
                );
                //dd(DB::getQueryLog());

                $this->res['query'] = $query;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'pay_info': 
                if(!$request->filled('order_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $order_id = $request->filled('order_id')?$request->order_id:null;

                DB::enableQueryLog();

                $pay = DB::table('personalpay')
                ->where('order_id',$order_id)->first();

                //dd(DB::getQueryLog());
                

                $this->res['query'] = $pay;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {   
        $order_id = $request->Moid;

        $orders = DB::table('cart')->where('order_id', $order_id)->get();

        $s_uid = Auth::guard('api')->user()->id;
        $s_id = Auth::guard('api')->user()->email;
        $s_name = $request->s_name;
        $s_hp = $request->s_hp;
        $hope_date = $request->hope_date;
        $g_name = $request->g_name;
        $g_hp = $request->g_hp;
        $g_post_num = $request->g_post_num;
        $g_addr1 = $request->g_addr1;
        $g_addr2 = $request->g_addr2;
        $g_extra_addr = $request->g_extra_addr;
        $g_addr_jibeon = $request->g_addr_jibeon;
        $delivery_index = $request->delivery_index;
        $delivery_memo = $request->delivery_memo;
        $settle_case = $request->PayMethod;
        $deposit_name = $request->deposit_name;
        $send_cost = $request->sendCost;
        $mobile_yn = $request->mobile_yn;
        $pg = $request->pg;
        $cart_count = count($orders);

        $company = DB::table('company')->first();
        $level = Auth::guard('api')->user()->level;

        //등급할인
        $level_discount = 0;

        //전체 할인 쿠폰
        
        $total_cp_id = $request->total_cp_id;
        $total_coupon = DB::table('coupon')->where('cp_id',$total_cp_id)->first();
        $total_coupon_price = isset($total_coupon->cp_price)?$total_coupon->cp_price:0;
        $total_price = 0;
        DB::beginTransaction();
        foreach($orders as $key=>$order){
            $cart_id = $order->id;
            $cp_id = $order->cp_id; //개별쿠폰아이디
            $item_id = $order->item_id;
            $option = $order->option;

            $cart = DB::table('cart')
            ->join('items','cart.item_id','=','items.item_id')
            ->join('users','items.seller_id','=','users.id')
            ->select('cart.*','items.store_id',DB::raw('users.id AS seller_uid'),DB::raw('users.email AS seller_id'),DB::raw('users.name AS seller_name'),DB::raw('users.mobile_number AS seller_hp'))
            ->where('cart.id',$cart_id)->first();
            $option = DB::table('item_option')->where('item_id',$item_id)->where('name',$option)->first();
            $coupon = DB::table('coupon')->where('cp_id',$cp_id)->first();

            $cart_price = $cart->price;
            $cart_qty = $cart->qty;
            $option_price = $option->price;
            $coupon_price = $cart->cp_price;

            $object_price = bcsub(bcmul(bcadd($cart_price,$option_price,0),$cart_qty,0),$coupon_price,0); //개별 아이템 가격
            $total_price = bcadd($total_price, $object_price, 0); //총가격

            $insert = DB::table('order')->insert([
                "order_id" => $order_id,
                "item_id" => $cart->item_id,
                "item_name" => $cart->item_name,
                "s_uid" => $s_uid,
                "s_id" => $s_id,
                "s_name" => $s_name,
                "s_hp" => $s_hp,
                "s_post_num" => Auth::guard('api')->user()->post_num,
                "s_addr1" => Auth::guard('api')->user()->address,
                "s_extra_addr" => Auth::guard('api')->user()->extra_addr,
                "s_addr_jibeon" => Auth::guard('api')->user()->addr_jibeon,
                "deposit_name" => $deposit_name,
                "s_ip" =>  $_SERVER['REMOTE_ADDR'],
                "g_name" => $g_name,
                "g_hp" => $g_hp,
                "g_post_num" => $g_post_num,
                "g_addr1" => $g_addr1,
                "g_addr2" => $g_addr2,
                "g_extra_addr" => $g_extra_addr,
                "g_addr_jibeon" => $g_addr_jibeon,
                "memo" => $request->memo[$key],
                "delivery_memo" => $request->delivery_memo,
                "cart_count" => $cart_count,
                "option_subject" => $cart->option_subject,
                "option" => $cart->option,
                "qty" => $cart->qty,
                "this_price" => $object_price,
                "total_cp_id" => $total_cp_id,
                "coupon_id" => $cp_id,
                "coupon_subject" => isset($coupon->cp_subject)?$coupon->cp_subject:null,
                "coupon_discount" => $total_coupon_price,
                "coupon_price" => $coupon_price,
                "level_discount" => $level_discount,
                "send_cost" => $send_cost,
                "mod_history" => '입금대기중',
                "od_status" => 'deposit_wait',
                "hope_day" => $hope_date,
                "settle_case" => $settle_case,
                "tax_flag" => $cart->notax,
                "tax_mny" => $cart->tax_mny,
                "vat_mny" => $cart->vat_mny,
                "free_mny" => $cart->free_mny,
                "fee_price" => $cart->fee_price,
                "delivery_company" => $company->delivery_company,
                "created_at" => DB::raw('now()'),
                "updated_at" => DB::raw('now()'),
                "seller_uid" => $cart->seller_uid,
                "seller_id" => $cart->seller_id,
                "seller_name" => $cart->seller_name,
                "seller_hp" => $cart->seller_hp,
                "store_id" => $cart->store_id,
                "company_name" => $cart->company_name,
                "item_price" => $cart->price,
                "item_option_price" => $cart->option_price,
                "mobile_yn" => $mobile_yn,
                "pg" => $pg
            ]);

            $delivery_insert = DB::table('frequen_delivery')->updateOrInsert(
                [
                "uid" => $s_uid,
                "delivery_index" => $delivery_index
                ],
                [
                "name" => $g_name,
                "phone_num" => $s_hp,
                "addr1" => $g_addr1,
                "addr2" => $g_addr2,
                "post_num" => $g_post_num,
                "frequen_yn" => '1'
                ]
            );
            
        }
        $receipt_price = bcsub($total_price,$total_coupon_price); //전체쿠폰할인
        $receipt_price = bcsub($receipt_price,$level_discount); //등급할인
        $receipt_price = bcadd($receipt_price, $send_cost);//배송비 가감


        DB::table('order')->where('order_id',$order_id)->update([
            'total_price' => $total_price,
            'receipt_price' => $receipt_price,
            'misu' => $receipt_price,
            "created_at" => DB::raw('now()'),
        ]);

        DB::commit();
        $this->res['query'] = true;
        $this->res['msg'] = "결제 완료";
        $this->res['state'] = config('res_code.OK');
        

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        
        switch($req){
            case 'order_part_cancel':
                $refund_reason = $request->filled('refund_reason')?$request->refund_reason:null;
                $refund_detail = $request->filled('refund_detail')?$request->refund_detail:null;
                if(!$request->filled('order_no')){ //order_id 랑 헷갈리면 안됨
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $order_no = $request->order_no;
                $order = DB::table('order')
                ->where('order_no',$order_no)
                ->where('od_status','order_apply')
                ->first();

                if(!isset($order)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 order_no 에 관한 주문정보 없음!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if($order->misu > 0){
                    $this->res['query'] = null;
                    $this->res['msg'] = "미수 금액이 존재할때는 부분취소를 진행하실 수 없습니다. 전체취소나 결제 완료후 부분취소를 진행해주세요.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->user()->id;
                $user = DB::table('users')->where('id',$uid)->first();

                $order_id = $order->order_id;
                $total_price = $order->total_price;
                $total_cp_id = $order->total_cp_id;
                $cp_id = $order->coupon_id;
                $flag_pg = false;

                $cancel_price = 0;

                $total_coupon = DB::table('coupon')->where('cp_id',$total_cp_id)->first();

                $compare = DB::table('order')
                ->where('order_id',$order_id)
                ->where('od_status','order_apply')
                ->count();

                DB::beginTransaction();
                if($compare > 1){
                    info("부분취소 이상품 외에 결제상품이 존재 ");
                    if(isset($total_coupon->cp_minimum)){ //전체 쿠폰 적용
                        $order_price = bcsub(bcsub($order->total_price, $order->this_price,0),$order->cancel_price,0);
                        if($order_price < $total_coupon->cp_minimum){
                            info("이 상품을 취소하면 쿠폰 최소결제비용에 맞지 않음 그래서 가격에서 할인가를 뺌");
                            $request_cancel_price = bcsub($order->this_price,$order->coupon_discount,0);
                            if($request_cancel_price < 0){
                                info("할인가를 뺏는데 0보다 작아서 0으로 만들어버림 ");
                                $request_cancel_price = 0;
                            }else{
                                info("할인가를 뺏는데 0보다 큼 그래서 이 가격을 할인함 ");
                                $order_total_cp_update = DB::table('order')->where('total_cp_id',$total_cp_id)->update([ //주문에서 total_cp_id null로 변경
                                    'total_cp_id' => null,
                                    'coupon_discount' => 0
                                ]);
                                $total_cp_update = DB::table('coupon')->where('cp_id',$total_cp_id)->update([
                                    'cp_use' => 0
                                ]);
                                $total_cp_delete = DB::table('coupon_use_log')->where('cp_id',$total_cp_id)->delete();
                            }
                        }else{
                            $request_cancel_price = $order->this_price;
                        }
                    }else{
                        $request_cancel_price = $order->this_price;
                    }
                }else{
                    info("부분취소 이상품 외에 결제상품이 존재하지 않음 (이게 마지막 부분취소 상품) ");
                    $request_cancel_price = bcsub($order->receipt_price, $order->cancel_price,0);
                    $order_total_cp_update = DB::table('order')->where('total_cp_id',$total_cp_id)->update([ //주문에서 total_cp_id null로 변경
                        'total_cp_id' => null,
                        'coupon_discount' => 0
                    ]);
                    $total_cp_update = DB::table('coupon')->where('cp_id',$total_cp_id)->update([
                        'cp_use' => 0
                    ]);
                    $total_cp_delete = DB::table('coupon_use_log')->where('cp_id',$total_cp_id)->delete();
                }
                
                
                $option = DB::table('item_option')->where('item_id',$order->item_id)->where('name',$order->option)->increment('stock_qty',$order->qty);
                if($option == 0){
                    DB::rollback();
                    $this->res['query'] = null;
                    $this->res['msg'] = "option 정보가 잘못됨!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $coupon = DB::table('coupon')->where('cp_id',$order->coupon_id)->update([
                    "cp_use" => 0,
                ]);

                $delete_coupon_log = DB::table('coupon_use_log')->where('cp_id',$order->coupon_id)->delete();


                $flag_pg = true;
                $pg_result = DB::table('personalpay')->where('order_id',$order_id)->first();


                
                //order table 상태 변경
                $update_order = DB::table('order')->where('order_no',$order_no)->update([
                    'od_status' => 'order_cancel',
                    'refund_reason' => $refund_reason,
                    'refund_detail' => $refund_detail,
                    'mod_history' => '주문취소',
                    "updated_at" => DB::raw('now()')
                ]);

                $update_total = DB::table('order')->where('order_id',$order_id)->update([
                    'cancel_price' => DB::raw('cancel_price + '.$request_cancel_price)
                ]);

                if($update_order == 0){
                    DB::rollback();
                    $this->res['query'] = null;
                    $this->res['msg'] = "이미 취소되었거나 변경할 주문이 없음";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                
                info("부분취소 환불시키려는 금액 : ".$request_cancel_price);
                if($request_cancel_price > 0){
                    //pg 로 결제했을때
                    if($pg_result->pp_pg == 'NICEPAY'){

                        $merchantKey = config('app.merchantkey');
                        $mid = config('app.mid');
                        $moid = $order_id;
                        $cancelMsg = "고객요청";
                        $tid = $pg_result->pp_tno;			
                        $cancelAmt = $request_cancel_price; 
                        $partialCancelCode = 1;
                        if($pg_result->pp_settle_case == 'VBANK' || $pg_result->pp_settle_case == 'CELLPHONE' ){
                            if(isset(Auth::guard('api')->user()->account_number) && isset(Auth::guard('api')->user()->account_bank) && isset(Auth::guard('api')->user()->account_name)){
                                $RefundAcctNo = Auth::guard('api')->user()->account_number;
                                $RefundBankCd = explode("/",Auth::guard('api')->user()->account_bank)[0];
                                $RefundAcctNm = Auth::guard('api')->user()->account_name;
                            }else{
                                DB::rollback();
                                $this->res['query'] = null;
                                $this->res['msg'] = "가상계좌 취소나 핸드폰 결제의 경우 마이페이지 > 카드 및 환불계좌 관리 > 환불 계좌를 등록하셔야 됩니다.";
                                $this->res['state'] = config('res_code.PARAM_ERR');
                                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                            }
                            
                        }

                        $ediDate = date("YmdHis");
                        $signData = bin2hex(hash('sha256', $mid . $cancelAmt . $ediDate . $merchantKey, true));

                        try{
                            if($pg_result->pp_settle_case == 'VBANK' || ($pg_result->pp_settle_case == 'CELLPHONE' && date("m", strtotime($pg_result->pp_receipt_time)) < date('m')) ){  //휴대폰일경우 익월일 경우만
                                $data = Array(
                                    'TID' => $tid,
                                    'MID' => $mid,
                                    'Moid' => $moid,
                                    'CancelAmt' => $cancelAmt,
                                    'CancelMsg' => iconv("UTF-8", "EUC-KR", $cancelMsg),
                                    'PartialCancelCode' => $partialCancelCode,
                                    'EdiDate' => $ediDate,
                                    'SignData' => $signData,
                                    'RefundAcctNo' => $RefundAcctNo,
                                    'RefundBankCd' => $RefundBankCd,
                                    'RefundAcctNm' => iconv("UTF-8","EUC-KR",$RefundAcctNm),
                                    'CharSet' => 'utf-8'
                                );	
                            }else{
                                $data = Array(
                                    'TID' => $tid,
                                    'MID' => $mid,
                                    'Moid' => $moid,
                                    'CancelAmt' => $cancelAmt,
                                    'CancelMsg' => iconv("UTF-8", "EUC-KR", $cancelMsg),
                                    'PartialCancelCode' => $partialCancelCode,
                                    'EdiDate' => $ediDate,
                                    'SignData' => $signData,
                                    'CharSet' => 'utf-8'
                                );	
                            }
                            $response = $this->reqPost($data, "https://webapi.nicepay.co.kr/webapi/cancel_process.jsp"); //취소 API 호출
                            $cancel_data = json_decode($response);

                            if($cancel_data->ResultCode != '2001' && $cancel_data->ResultCode != '2211' ){
                                DB::rollback();
                                $this->res['query'] = null;
                                $this->res['msg'] = $cancel_data->ResultCode." : ".$cancel_data->ResultMsg;
                                $this->res['state'] = config('res_code.PARAM_ERR');
                                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                            }
                            
                            $insert_personalpaycancel = DB::table('personalpay')->insert([
                                'order_id' => $cancel_data->Moid,
                                'uid' => $uid,
                                'pp_method' => 'cancel',
                                'pp_resultcode' => $cancel_data->ResultCode,
                                'pp_name' => $user->name,
                                'pp_email' => $user->email,
                                'pp_hp' => $user->mobile_number,
                                'pp_content' => $pg_result->pp_content,
                                'pp_price' => $cancel_data->CancelAmt,
                                'pp_pg' => "PAYPLE",
                                'pp_tno' => $cancel_data->TID,
                                'pp_receipt_price' => $cancel_data->CancelAmt,
                                'pp_settle_case' => $cancel_data->PayMethod,
                                'pp_deposit_name' => $user->name,
                                'pp_receipt_time' => $cancel_data->CancelDate.$cancel_data->CancelTime,
                                'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                                'pp_ip' => $_SERVER["REMOTE_ADDR"],
                                'created_at' => DB::raw("now()"),
                                'updated_at' => DB::raw("now()"),
                                'pp_result' => json_encode($cancel_data),
                            ]);

                        }catch(Exception $e){
                            $e->getMessage();
                            $ResultCode = "9999";
                            $ResultMsg = "통신실패";
                            DB::rollback();
                            $this->res['query'] = null;
                            $this->res['msg'] = "통신실패";
                            $this->res['state'] = config('res_code.NETWORK_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }
                    }else if($pg_result->pp_pg == 'PAYPLE'){

                        $url = $this->payple_curl."/php/auth.php";
                        $cst_id = $this->cst_id;
                        $custKey = $this->custKey;
                        $PCD_PAYCANCEL_FLAG = "Y";

                        $data = array(
                            "cst_id" => $cst_id,
                            "custKey" => $custKey,
                            "PCD_PAYCANCEL_FLAG" => $PCD_PAYCANCEL_FLAG
                        );

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_REFERER, $this->referer);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                        $response = curl_exec($ch);
                        curl_close($ch);

                        $paypleauth = json_decode($response);

                        if($paypleauth->result != 'success'){
                            $this->res['query'] = null;
                            $this->res['msg'] = $result->result_msg;
                            $this->res['state'] = config('res_code.API_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }

                        //결제취소 필요한 parameters
                        $PCD_CST_ID = $paypleauth->cst_id;
                        $PCD_CUST_KEY = $paypleauth->custKey;
                        $PCD_AUTH_KEY = $paypleauth->AuthKey;
                        $PCD_REFUND_KEY = $this->PCD_REFUND_KEY;
                        $PCD_PAYCANCEL_FLAG = "Y";
                        $PCD_PAY_OID = $order_id;
                        $PCD_PAY_DATE = date('Ymd');
                        $PCD_REFUND_TOTAL = $request_cancel_price;

                        $url = $paypleauth->return_url;

                        $headers = array( 
                            "Content-Type: application/json;"
                        );

                        $data = array(
                            "PCD_CST_ID" => $PCD_CST_ID,
                            "PCD_CUST_KEY" => $PCD_CUST_KEY,
                            "PCD_AUTH_KEY" => $PCD_AUTH_KEY,
                            "PCD_REFUND_KEY" => $PCD_REFUND_KEY,
                            "PCD_PAYCANCEL_FLAG" => $PCD_PAYCANCEL_FLAG,
                            "PCD_PAY_OID" => $PCD_PAY_OID,
                            "PCD_PAY_DATE" => $PCD_PAY_DATE,
                            "PCD_REFUND_TOTAL" => $PCD_REFUND_TOTAL,
                            "PCD_REGULER_FLAG" => "N"
                        );

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                        $response = curl_exec($ch);
                        curl_close($ch);

                        $result = json_decode($response);

                        if($result->PCD_PAY_RST != 'success'){
                            DB::rollBack();
                            $this->res['query'] = $data;
                            $this->res['msg'] = $result->PCD_PAY_MSG;
                            $this->res['state'] = config('res_code.API_ERR');

                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }

                        $insert_personalpaycancel = DB::table('personalpay')->insert([
                            'order_id' => $order_id,
                            'uid' => $uid,
                            'pp_method' => 'cancel',
                            'pp_resultcode' => $result->PCD_PAY_RST,
                            'pp_name' => $user->name,
                            'pp_email' => $user->email,
                            'pp_hp' => $user->mobile_number,
                            'pp_content' => $result->PCD_PAY_GOODS,
                            'pp_price' => $result->PCD_REFUND_TOTAL,
                            'pp_pg' => "PAYPLE",
                            'pp_tno' => $result->PCD_PAYER_ID,
                            'pp_receipt_price' => $result->PCD_REFUND_TOTAL,
                            'pp_settle_case' => $result->PCD_PAY_TYPE,
                            'pp_deposit_name' => $user->name,
                            'pp_receipt_time' => $pg_result->pp_receipt_time,
                            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                            'pp_ip' => $_SERVER["REMOTE_ADDR"],
                            'created_at' => DB::raw("now()"),
                            'updated_at' => DB::raw("now()"),
                            'pp_result' => json_encode($result),
                        ]);
                        
                    }
                }
                DB::commit();
                $txt = "주문하신 (".$order->item_name.") 상품을 취소하여 ".number_format($request_cancel_price,0)." 원이 환불될 예정입니다.";
                $sms = Coolsms::send_sms($user->mobile_number, $txt);
                $this->res['query'] = 1;
                $this->res['msg'] = "주문취소 완료";
                $this->res['state'] = config('res_code.OK');
                
            break;
            
            case 'order_all_cancel': //주문취소
                $refund_reason = $request->filled('refund_reason')?$request->refund_reason:null;
                $refund_detail = $request->filled('refund_detail')?$request->refund_detail:null;
                if(!$request->filled('order_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->user()->id;
                $user = DB::table('users')->where('id',$uid)->first();

                $order_id = $request->order_id;
                $orders = DB::table('order')->where('order_id',$order_id)->where('s_uid',$uid)->where(function($query){
                    $query->where('od_status','deposit_wait')->orwhere('od_status','order_apply');
                })->get();
                
                if(!isset($orders)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 order_id 에 관한 주문정보 없음!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $flag_pg = false;
                $pg_result = '';
                $item_name = '';
                $order_count = count($orders);
                $receipt_price = 0;
                $cancel_price = 0;
                DB::beginTransaction();
                foreach($orders as $order){
                    $receipt_price = $order->receipt_price;
                    $cancel_price = $order->cancel_price;
                    $item_name = $order->item_name;
                    $option = DB::table('item_option')->where('item_id',$order->item_id)->where('name',$order->option)->increment('stock_qty',$order->qty);
                    if($option == 0){
                        DB::rollback();
                        $this->res['query'] = null;
                        $this->res['msg'] = "option 정보가 잘못됨!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                    $total_coupon = DB::table('coupon')->where('cp_id',$order->total_cp_id)->update([
                        "cp_use" => 0,
                    ]);
                    $coupon = DB::table('coupon')->where('cp_id',$order->coupon_id)->update([
                        "cp_use" => 0,
                    ]);
                    if($order->settle_case != 'DEPOSIT'){
                        if($order->od_status == 'deposit_wait'){
                            $flag_pg = true;
                            $pg_result = DB::table('personalpay')->where('order_id',$order_id)->first();
                        }else{
                            $flag_pg = true;
                            $pg_result = DB::table('personalpay')->where('order_id',$order_id)->first();
                        }
                    }
                }
                $delete_coupon_log = DB::table('coupon_use_log')->where('od_id',$order_id)->delete();
                //order table 상태 변경
                $update_order = DB::table('order')->where('order_id',$order_id)->update([
                    'od_status' => 'order_cancel',
                    'cancel_price' => $receipt_price,
                    'misu' => 0,
                    'mod_history' => '주문취소',
                    'refund_reason' => $refund_reason,
                    'refund_detail' => $refund_detail,
                    "updated_at" => DB::raw('now()')
                ]);

                if($update_order == 0){
                    DB::rollback();
                    $this->res['query'] = null;
                    $this->res['msg'] = "이미 취소되었거나 변경할 주문이 없음";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $request_cancel_price = bcsub($receipt_price, $cancel_price,0); 
                //pg 로 결제했을때
                if($pg_result->pp_pg == 'NICEPAY'){

                    $merchantKey = config('app.merchantkey');
                    $mid = config('app.mid');
                    $moid = $order_id;
                    $cancelMsg = "고객요청";
                    $tid = $pg_result->pp_tno;			
                    $cancelAmt = bcsub($order->receipt_price, $order->cancel_price,0); 
                    if($order->cancel_price > 0){ // 부분취소가 진행되어서 부분취소기능으로 전체취소를해야됨
                        $partialCancelCode = 1;
                    }else{
                        $partialCancelCode = 0;
                    }
                    
                    if($pg_result->pp_settle_case == 'VBANK' || $pg_result->pp_settle_case == 'CELLPHONE' ){
                        if(isset(Auth::guard('api')->user()->account_number) && isset(Auth::guard('api')->user()->account_bank) && isset(Auth::guard('api')->user()->account_name)){
                            $RefundAcctNo = Auth::guard('api')->user()->account_number;
                            $RefundBankCd = explode("/",Auth::guard('api')->user()->account_bank)[0];
                            $RefundAcctNm = Auth::guard('api')->user()->account_name;
                        }else{
                            DB::rollback();
                            $this->res['query'] = null;
                            $this->res['msg'] = "가상계좌 취소나 핸드폰 결제의 경우 마이페이지 > 카드 및 환불계좌 관리 > 환불 계좌를 등록하셔야 됩니다.";
                            $this->res['state'] = config('res_code.PARAM_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }
                        
                    }

                    $ediDate = date("YmdHis");
                    $signData = bin2hex(hash('sha256', $mid . $cancelAmt . $ediDate . $merchantKey, true));

                    try{
                        if($pg_result->pp_settle_case == 'VBANK' || ($pg_result->pp_settle_case == 'CELLPHONE' && date("m", strtotime($pg_result->pp_receipt_time)) < date('m')) ){  //휴대폰일경우 익월일 경우만
                            $data = Array(
                                'TID' => $tid,
                                'MID' => $mid,
                                'Moid' => $moid,
                                'CancelAmt' => $cancelAmt,
                                'CancelMsg' => iconv("UTF-8", "EUC-KR", $cancelMsg),
                                'PartialCancelCode' => $partialCancelCode,
                                'EdiDate' => $ediDate,
                                'SignData' => $signData,
                                'RefundAcctNo' => $RefundAcctNo,
                                'RefundBankCd' => $RefundBankCd,
                                'RefundAcctNm' => iconv("UTF-8","EUC-KR",$RefundAcctNm),
                                'CharSet' => 'utf-8'
                            );	
                        }else{
                            $data = Array(
                                'TID' => $tid,
                                'MID' => $mid,
                                'Moid' => $moid,
                                'CancelAmt' => $cancelAmt,
                                'CancelMsg' => iconv("UTF-8", "EUC-KR", $cancelMsg),
                                'PartialCancelCode' => $partialCancelCode,
                                'EdiDate' => $ediDate,
                                'SignData' => $signData,
                                'CharSet' => 'utf-8'
                            );	
                        }
                        $response = $this->reqPost($data, "https://webapi.nicepay.co.kr/webapi/cancel_process.jsp"); //취소 API 호출
                        $cancel_data = json_decode($response);

                        if($cancel_data->ResultCode != '2001' && $cancel_data->ResultCode != '2211' ){
                            DB::rollback();
                            $this->res['query'] = null;
                            $this->res['msg'] = $cancel_data->ResultCode." : ".$cancel_data->ResultMsg;
                            $this->res['state'] = config('res_code.PARAM_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }
                        
                        $insert_personalpaycancel = DB::table('personalpay')->insert([
                            'order_id' => $cancel_data->Moid,
                            'uid' => $uid,
                            'pp_method' => 'cancel',
                            'pp_resultcode' => $cancel_data->ResultCode,
                            'pp_name' => $user->name,
                            'pp_email' => $user->email,
                            'pp_hp' => $user->mobile_number,
                            'pp_content' => $pg_result->pp_content,
                            'pp_price' => $cancel_data->CancelAmt,
                            'pp_pg' => "NICEPAY",
                            'pp_tno' => $cancel_data->TID,
                            'pp_receipt_price' => $cancel_data->CancelAmt,
                            'pp_settle_case' => $cancel_data->PayMethod,
                            'pp_deposit_name' => $user->name,
                            'pp_receipt_time' => $cancel_data->CancelDate.$cancel_data->CancelTime,
                            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                            'pp_ip' => $_SERVER["REMOTE_ADDR"],
                            'created_at' => DB::raw("now()"),
                            'updated_at' => DB::raw("now()"),
                            'pp_result' => json_encode($cancel_data),
                        ]);
                        
                    }catch(Exception $e){
                        $e->getMessage();
                        $ResultCode = "9999";
                        $ResultMsg = "통신실패";
                        DB::rollback();
                        $this->res['query'] = null;
                        $this->res['msg'] = "통신실패";
                        $this->res['state'] = config('res_code.NETWORK_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }else if($pg_result->pp_pg == 'PAYPLE'){

                    $url = $this->payple_curl."/php/auth.php";
                    $cst_id = $this->cst_id;
                    $custKey = $this->custKey;
                    $PCD_PAYCANCEL_FLAG = "Y";
                    

                    $data = array(
                        "cst_id" => $cst_id,
                        "custKey" => $custKey,
                        "PCD_PAYCANCEL_FLAG" => $PCD_PAYCANCEL_FLAG
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_REFERER, $this->referer);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $paypleauth = json_decode($response);

                    if($paypleauth->result != 'success'){
                        DB::rollback();
                        $this->res['query'] = null;
                        $this->res['msg'] = $paypleauth->result_msg;
                        $this->res['state'] = config('res_code.API_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }

                    $headers = array( 
                        "Content-Type: application/json;"
                    );

                    //결제취소 필요한 parameters
                    $PCD_CST_ID = $paypleauth->cst_id;
                    $PCD_CUST_KEY = $paypleauth->custKey;
                    $PCD_AUTH_KEY = $paypleauth->AuthKey;
                    $PCD_REFUND_KEY = $this->PCD_REFUND_KEY;
                    $PCD_PAYCANCEL_FLAG = "Y";
                    $PCD_PAY_OID = $order_id;
                    $PCD_PAY_DATE = date('Ymd');
                    $PCD_REFUND_TOTAL = bcsub($order->receipt_price, $order->cancel_price,0);

                    $url = $paypleauth->return_url;

                    $data = array(
                        "PCD_CST_ID" => $PCD_CST_ID,
                        "PCD_CUST_KEY" => $PCD_CUST_KEY,
                        "PCD_AUTH_KEY" => $PCD_AUTH_KEY,
                        "PCD_REFUND_KEY" => $PCD_REFUND_KEY,
                        "PCD_PAYCANCEL_FLAG" => $PCD_PAYCANCEL_FLAG,
                        "PCD_PAY_OID" => $PCD_PAY_OID,
                        "PCD_PAY_DATE" => $PCD_PAY_DATE,
                        "PCD_REFUND_TOTAL" => $PCD_REFUND_TOTAL,
                        "PCD_REGULER_FLAG" => "N"
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $result = json_decode($response);

                    if($result->PCD_PAY_RST != 'success'){
                        DB::rollBack();
                        $this->res['query'] = $data;
                        $this->res['msg'] = $result->PCD_PAY_MSG;
                        $this->res['state'] = config('res_code.API_ERR');

                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }

                    $insert_personalpaycancel = DB::table('personalpay')->insert([
                        'order_id' => $order_id,
                        'uid' => $uid,
                        'pp_method' => 'cancel',
                        'pp_resultcode' => $result->PCD_PAY_RST,
                        'pp_name' => $user->name,
                        'pp_email' => $user->email,
                        'pp_hp' => $user->mobile_number,
                        'pp_content' => $result->PCD_PAY_GOODS,
                        'pp_price' => $result->PCD_REFUND_TOTAL,
                        'pp_pg' => "PAYPLE",
                        'pp_tno' => $result->PCD_PAYER_ID,
                        'pp_receipt_price' => $result->PCD_REFUND_TOTAL,
                        'pp_settle_case' => $result->PCD_PAY_TYPE,
                        'pp_deposit_name' => $user->name,
                        'pp_receipt_time' => $pg_result->pp_receipt_time,
                        'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                        'pp_ip' => $_SERVER["REMOTE_ADDR"],
                        'created_at' => DB::raw("now()"),
                        'updated_at' => DB::raw("now()"),
                        'pp_result' => json_encode($result),
                    ]);
                }

                DB::commit();
                if($order_count > 1){
                    $txt = "주문하신 (".$item_name.") 외 ".bcsub($order_count,1,0)."개의 상품을 취소하여 ".number_format($request_cancel_price,0)." 원이 환불될 예정입니다.";    
                }else{
                    $txt = "주문하신 (".$item_name.") 상품을 취소하여 ".number_format($request_cancel_price,0)." 원이 환불될 예정입니다.";    
                }
                
                $sms = Coolsms::send_sms($user->mobile_number, $txt);
                $this->res['query'] = 1;
                $this->res['msg'] = "주문취소 완료";
                $this->res['state'] = config('res_code.OK');
            break;
            
            case 'order_all_refund': //전체주문환불신청
                if(!$request->filled('order_id', 'refund_reason', 'refund_detail')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->user()->id;
                $order_id = $request->order_id;
                $refund_reason = $request->refund_reason;
                $refund_detail = $request->refund_detail;
                
                try{
                    $update = DB::table('order')->where('s_uid',$uid)->where('order_id',$order_id)->update([
                        "refund_reason" => $refund_reason,
                        "refund_detail" => $refund_detail,
                        "od_status" => "refund_apply",
                        "updated_at" => DB::raw('now()'),
                    ]);
                }catch(Exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $this->res['query'] = $update;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'order_part_refund':
                if(!$request->filled('order_no', 'refund_reason', 'refund_detail')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->user()->id;
                $order_no = $request->order_no;
                $refund_reason = $request->refund_reason;
                $refund_detail = $request->refund_detail;

                try{
                    $update = DB::table('order')->where('s_uid',$uid)->where('order_no',$order_no)->update([
                        "refund_reason" => $refund_reason,
                        "refund_detail" => $refund_detail,
                        "od_status" => "refund_apply",
                        "updated_at" => DB::raw('now()'),
                    ]);

                    if($update == 0){
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)"; 
                        $this->res['state'] = config('res_code.QUERY_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');    
                    }
                }catch(Exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $this->res['query'] = $update;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

            break;

            case 'delivery_change':
                if(!$request->filled('order_id', 'g_name', 'g_hp', 'g_addr1', 'g_addr2', 'g_post_num', 'delivery_memo')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->user()->id;
                $order_id = $request->order_id;
                $g_name = $request->g_name;
                $g_hp = $request->g_hp;
                $g_addr1 = $request->g_addr1;
                $g_addr2 = $request->g_addr2;
                $g_post_num = $request->g_post_num;
                $delivery_memo = $request->delivery_memo;

                try{
                    $update = DB::table('order')->where('s_uid',$uid)->where('order_id',$order_id)->update([
                        "g_name" => $g_name,
                        "g_hp" => $g_hp,
                        "g_addr1" => $g_addr1,
                        "g_addr2" => $g_addr2,
                        "g_post_num" => $g_post_num,
                        "delivery_memo" => $delivery_memo,
                        "updated_at" => DB::raw('now()'),
                    ]);

                    if($update == 0){
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)"; 
                        $this->res['state'] = config('res_code.QUERY_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');    
                    }
                }catch(Exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $user = Auth::guard('api')->user();
                $this->res['query'] = $user;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');


            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
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
