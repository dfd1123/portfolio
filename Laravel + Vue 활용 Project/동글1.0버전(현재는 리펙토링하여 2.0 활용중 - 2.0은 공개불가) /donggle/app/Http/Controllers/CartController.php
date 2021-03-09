<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use App;
use DB;
use Auth;

class CartController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    
    /**
	 * @OA\Get(
	 *     path="/cart",
	 *     @OA\Response(response="200", description="Display a listing of projects.")
	 * )
	 */

    public function __construct(){
        //$this->middleware('auth:api', ['except' => ['show']]); 
        //dd('dd');
    }

    public function index()
    {
        return 'API FOR CART';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'cart_list': 
                if($request->filled('limit')){
                    $cart = DB::table('cart')
                    ->join('items','items.item_id','=','cart.item_id')
                    ->select('cart.*','items.store_id', 'items.images', 'items.possible_ready_term')
                    ->where('uid',Auth::guard('api')->user()->id)
                    ->where('buy_yn',0)
                    ->where('items.delete_yn',0)
                    ->where('items.sell_yn',1)
                    ->orderBy('created_at', 'desc')
                    ->limit($request->limit)->get();
                }else{
                    $cart = DB::table('cart')
                    ->join('items','items.item_id','=','cart.item_id')
                    ->select('cart.*','items.store_id', 'items.images', 'items.possible_ready_term')
                    ->where('uid',Auth::guard('api')->user()->id)
                    ->where('buy_yn',0)
                    ->where('items.delete_yn',0)
                    ->where('items.sell_yn',1)
                    ->orderBy('created_at', 'desc')->get();
                }
                $this->res['query'] = $cart;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'order_list':
                if(!$request->filled('cart_ids')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $order_id = time().mt_rand(1000,9999);
                $update_orderid = DB::table('cart')->whereIn('id',$request->cart_ids)->where('uid',Auth::guard('api')->user()->id)->where('buy_yn',0)->update([
                    "order_id" => $order_id,
                ]);
                $carts = DB::table('cart')
                ->join('items','items.item_id','=','cart.item_id')
                ->select('cart.*','items.store_id', 'items.images', 'items.possible_ready_term')
                ->whereIn('id',$request->cart_ids)
                ->where('uid',Auth::guard('api')->user()->id)
                ->where('buy_yn',0)
                ->where('items.delete_yn',0)
                ->where('items.sell_yn',1)
                ->orderBy('created_at', 'desc')->get();
                
                $titles = array();
                $total_price = 0;
                $send_cost = 0;

                $level = Auth::guard('api')->user()->level;

                //등급할인
                $level_discount = 0;
                
                foreach($carts as $cart){
                    $titles[] = $cart->item_name;

                    $item_price = $cart->price;
                    $item_qty = $cart->qty;
                    $option_price = $cart->option_price;
                    $coupon_price = $cart->cp_price;
                    
                    $object_price = bcsub(bcmul(bcadd($item_price,$option_price,0),$item_qty,0),$coupon_price,0);
                    $total_price = bcadd($total_price,$object_price,0);

                    $send_cost = $cart->send_cost;
                }

                $total_cp_id = $request->total_cp_id;
                $total_coupon = DB::table('coupon')->where('cp_id',$total_cp_id)->first();
                $total_coupon_price = isset($total_coupon->cp_price)?$total_coupon->cp_price:0;
                $total_price = bcsub($total_price,$total_coupon_price,0); //전체쿠폰할인
                $total_price = bcsub($total_price,$level_discount,0); //등급할인
                $total_price = bcadd($total_price,$send_cost,0);

                $merchantKey = config('app.merchantkey'); // 상점키
                $MID         = config('app.mid'); // 상점아이디
                $goodsName   = implode($titles); // 결제상품명
                $price       = $total_price; // 결제상품금액
                $totalCouponPrice = $total_coupon_price;
                $buyerEmail  = Auth::guard('api')->user()->email; // 구매자메일주소
                $moid        = $order_id; // 상품주문번호
                $returnURL	 = config('app.nice_return_url_test'); // 결과페이지(절대경로) - 모바일 결제창 전용
                $ediDate = date("YmdHis");
                $hashString = bin2hex(hash('sha256', $ediDate.$MID.$price.$merchantKey, true));

                $payple_card = DB::table('personalpay')
                ->select('pp_tno')
                ->where('uid',Auth::guard('api')->user()->id)
                ->where('pp_pg','PAYPLE')
                ->where('pp_method','pay')
                ->where('pp_settle_case','card')
                ->orderBy('id','DESC')
                ->first();

                $payple_transfer = DB::table('personalpay')
                ->select('pp_tno')
                ->where('uid',Auth::guard('api')->user()->id)
                ->where('pp_pg','PAYPLE')
                ->where('pp_method','pay')
                ->where('pp_settle_case','transfer')
                ->orderBy('id','DESC')
                ->first();

                $response = array();
                $response['carts'] = $carts;
                $response['MID'] = $MID;
                $response['goodsName'] = $goodsName;
                $response['price'] = $price;
                $response['buyerEmail'] = $buyerEmail;
                $response['moid'] = $moid;
                $response['returnURL'] = $returnURL;
                $response['ediDate'] = $ediDate;
                $response['hashString'] = $hashString;
                $response['totalDiscountPrice'] = $total_coupon_price;
                $response['levelDiscountPrice'] = $level_discount;
                $response['payple_card'] = $payple_card;
                $response['payple_transfer'] = $payple_transfer;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if(!$request->filled('item_id', 'options')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $item_id = $request->item_id;
        $item = DB::table('items')->where('item_id', $item_id)->first();
        if($item == null){
            $this->res['query'] = null;
            $this->res['msg'] = "item 정보 없음!";
            $this->res['state'] = config('res_code.NO_DATA');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        try {
            $options = json_decode($request->options);
            $order_id = array();
            foreach($options as $row){
                $option_id = $row->id;
                $option = DB::table('item_option')->where('id',$option_id)->first();

                //$cartfield = $this->CartFieldArray($row, $option, $item, 'create');
                $id = DB::table('cart')->insertGetId([
                    "uid" => Auth::guard('api')->user()->id,
                    "ca_id" => $item->ca_id,
                    "item_id" => $item->item_id,
                    "item_name" => $item->title,
                    "company_name" => $item->company_name,
                    "price" => $item->price,
                    "send_cost" => $item->sc_price,
                    "buy_yn" => 0,
                    "created_at" => DB::raw('now()'),
                    "updated_at" => DB::raw('now()'),
                    "stock_use" => 0,
                    "option" => $option->name,
                    "qty" => $row->qty,
                    "notax" => $item->notax,
                    "tax_mny" => bcadd($item->tax_mny,$option->tax_mny,0),
                    "vat_mny" => bcadd($item->vat_mny,$option->vat_mny,0),
                    "free_mny" => 0,
                    "option_subject" => $item->option_subject,
                    "option_price" => $option->price,
                    "ip" =>  $_SERVER['REMOTE_ADDR'],
                    "fee_price" => $item->fee_price,
                    "select_time" => DB::raw('now()'),
                ]);

                $obj = array(
                    "id"=> $id
                );

                array_push($order_id, $obj);
            }
        }catch(Exception $e){
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)"; 
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $this->res['query'] = $order_id;
        $this->res['msg'] = "성공";
        $this->res['state'] = config('res_code.OK');
       
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        switch($req){
            case 'coupon':
                if(!$request->filled('cart_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $cart_id = $request->cart_id;
                $cart_info = DB::table('cart')->where('uid',Auth::guard('api')->user()->id)->where('id',$cart_id)->first();
                if(!isset($cart_info->price)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 카트id 가 잘못된 경로로 넘어왔거나 삭제된 목록임";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $price = bcadd($cart_info->price,$cart_info->option_price,0);

                $cp_id = $request->cp_id;
                $cp_info = DB::table('coupon')->where('cp_id',$cp_id)->where('cp_use',0)->first();

                if(!isset($cp_info->cp_id)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 쿠폰id 가 잘못된 경로로 넘어왔거나 삭제된 목록임";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if($cp_info->cp_type == 1){
                    $discount_percent = bcdiv($cp_info->cp_price,100,8);
                    $discount_price = bcmul($price,$discount_percent, 0);
                    $cp_price = floor(bcmul(bcdiv($discount_price,$cp_info->cp_trunc,0),$cp_info->cp_trunc,0));
                }else{
                    $cp_price = isset($cp_info->cp_price)?$cp_info->cp_price:0;
                }

                $update = DB::table('cart')->where('uid',Auth::guard('api')->user()->id)->where('id',$cart_id)->update([
                    "cp_id" => $cp_id,
                    "cp_price" => $cp_price,
                ]);

                $this->res['query'] = $update;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'option': 
                if(!$request->filled('cart_id','options')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $cart_id = $request->cart_id;
                $item = DB::table('cart')->select('items.tax_mny', 'items.vat_mny')
                ->join('items','cart.item_id','=','items.item_id')
                ->where('id',$cart_id)->first();
                
                try {
                    $options = json_decode($request->options);
                    foreach($options as $row){
                        $option_id = $row->id;
                        $option = DB::table('item_option')->where('id',$option_id)->first();
                        $update = DB::table('cart')->where('uid',Auth::guard('api')->user()->id)->where('id',$cart_id)->update([
                            "updated_at" => DB::raw('now()'),
                            "option" => $option->name,
                            "qty" => $row->qty,
                            "tax_mny" => bcadd($item->tax_mny,$option->tax_mny,0),
                            "vat_mny" => bcadd($item->vat_mny,$option->vat_mny,0),
                            "option_price" => $option->price,
                        ]);
                    }
                    //$cartfield = $this->CartFieldArray($option, $item, 'edit');
                    
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
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function destroy(Request $request, $req)
    {  
        if(!$request->filled('cart_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $cart_id = $request->cart_id;

        $delete = DB::table('cart')->whereIn('id',$cart_id)->where('uid',Auth::guard('api')->user()->id)->delete();
        $this->res['query'] = $delete;
        $this->res['msg'] = "성공";
        $this->res['state'] = config('res_code.OK');


        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
