<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use Facades\App\Classes\EthApi;

use Illuminate\Http\Request;
use TLCfund\User;
use TLCfund\Product;
use TLCfund\Deposit_pay;
use TLCfund\Order;
use TLCfund\Delivery;
use TLCfund\Cart;
use TLCfund\Banner;
use TLCfund\Address;
use Auth;

class OrdersController extends Controller
{
	public function __construct()
    {
		$this->middleware('auth');
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
		$this->banner = Banner::where('bn_alt','구매')->first();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($order_id)
    {
		$user = Auth::user();
	
		/*
		$mobile_number[0] = substr($user->mobile_number, 0, 3);
		$mobile_number[1] = substr($user->mobile_number, 3, 4);
		$mobile_number[2] = substr($user->mobile_number, 7, 10);
		
		$email = explode('@', $user->email);
		*/
		
		$product = Product::where('id',$order_id)->first();
		
		$views = view($this->device.'.'.'order.order_create');
		$views->user = $user;
		$views->mobile_number = $user->mobile_number;
		$views->email = $user->email;
		$views->product = $product;
		$views->order_id = $order_id;
		$views->banner = $this->banner;
		$views->title = '주문하기';
		
		return $views;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$user = Auth::user();
		
		$input = $request->all();
		
		$views = view($this->device.'.'.'order.order_complete');
		$views->title = '주문완료';
		$views->message = '주문해주셔서 감사합니다.';
		$views->banner = $this->banner;
		$already_order = Order::where('product_id',$request->input('pro_id'))->where('uid',$user->id)->where('order_state','<>',4)->first();
		
		if($already_order != null){
			$views->title = '주문취소';
			$views->message = '이미 주문중인 상품이 존재합니다.';
			$views->order_number = sprintf("%09d",$already_order->id);
			$views->order_id = $already_order->id;
			return $views;
		}else{
			//$getter_mobile = $request->input('user_mobile_number1').$request->input('user_mobile_number2').$request->input('user_mobile_number3');
			$getter_mobile = $request->input('user_mobile_number');
			if($request->input('payment_kind') == 0){ // 무통장입금
				$order_state = 0;
				$payment_price = '0';
				$product = Product::where('id', $request->input('pro_id'))->first();
				$total_price = $product->cash_price + $product->delivery_price;
			}else if($request->input('payment_kind') == 10){ // 코인결제
				$order_state = 1;
				$product = Product::where('id', $request->input('pro_id'))->first();
				$payment_price = $product->coin_price;
				$total_price = $product->coin_price;

				$address = Address::where('user_id', $user->id)->first();
				if(bccomp($address->available_balance_tlc, $total_price, 8) == -1) {
					// 코인 금액 부족
					return redirect()->back()->with('jsAlert', '코인 금액이 부족합니다.');
				}
			}
		
			Order::create([
				'product_id' => $product->id,
				'seller_id' => $product->seller_id,
				'uid' => $user->id,
				'order_state' => $order_state,
				'how_pay' => $request->input('payment_kind'),
				'total_price' => $total_price,
				'sales_price' => 0,
				'payment_price' => $payment_price,
			]);
		
			Product::where('id',$request->input('pro_id'))->update([
				"sell_yn" => 3,
			]);
	
			Cart::where('uid',$user->id)->where('product_id',$request->input('pro_id'))->delete();
			
			$order_id = Order::get()->last()->id;

			Delivery::create([
				'order_id' => $order_id,
				'getter_name' => $request->input('user_name'),
				'getter_mobile' => $getter_mobile,
				'getter_email' => $request->input('user_email'),
				'user_addr1' => $request->input('user_addr1'),
				'user_addr2' => $request->input('user_addr2'),
				'user_extra_addr' => $request->input('user_extra_addr'),
				'post_num' => $request->input('post_num'),
				'delivery_ask' => $request->input('delivery_ask'),
				'delivery_result' => 0,
			]);
			
			if($request->input('payment_kind') == 0){
				// 무통장입금
				if($request->input('bil_kind')==1){
					if($request->input('individual_kind') == 0){
						Deposit_pay::create([
							'id' => $order_id,
							'bank' => $request->input('bank'),
							'bil_kind' => $request->input('bil_kind'),
							'individual_kind' => $request->input('individual_kind'),
							'mobile_number' => $request->input('bilmobile_number'),
							'bilcard_number' => '',
							'business_number' => '',
							'user_bank_name' => $request->input('bank'),
							'user_bank_number' => $request->input('user_bank_number'),
						]);
					}else{
						Deposit_pay::create([
							'id' => $order_id,
							'bank' => $request->input('bank'),
							'bil_kind' => $request->input('bil_kind'),
							'individual_kind' => $request->input('individual_kind'),
							'mobile_number' => '',
							'bilcard_number' => $request->input('bilcard_number'),
							'business_number' => '',
							'user_bank_name' => $request->input('bank'),
							'user_bank_number' => $request->input('user_bank_number'),
						]);
					}
				}else if($request->input('bil_kind')==2){
					Deposit_pay::create([
						'id' => $order_id,
						'bank' => $request->input('bank'),
						'bil_kind' => $request->input('bil_kind'),
						'individual_kind' => '',
						'mobile_number' => '',
						'bilcard_number' => '',
						'business_number' => $request->input('business_number'),
						'user_bank_name' => $request->input('bank'),
						'user_bank_number' => $request->input('user_bank_number'),
					]);
				}else{
					Deposit_pay::create([
						'id' => $order_id,
						'bank' => $request->input('bank'),
						'bil_kind' => $request->input('bil_kind'),
						'individual_kind' => '',
						'mobile_number' => '',
						'bilcard_number' => '',
						'business_number' => '',
						'user_bank_name' => $request->input('bank'),
						'user_bank_number' => $request->input('user_bank_number'),
					]);
				}
			} else if($request->input('payment_kind') == 10){
				// 코인결제
				// 구매자 금액 차감 후 거래내역 기록
				EthApi::addInfoRequest($user->email, 'withdraw', $total_price, 'buy');
				Address::where('user_id', $user->id)->decrement('available_balance_tlc', $total_price);
			}

			$views->order_number = sprintf("%09d",$order_id);
			$views->order_id = $order_id;
		
			
			return $views;
		}
		
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	public function complete()
    {
		$views = view($this->device.'.'.'order.order_complete');
		$views->banner = $this->banner;
		$views->title = '주문완료';
		return $views;
    }
	
}
