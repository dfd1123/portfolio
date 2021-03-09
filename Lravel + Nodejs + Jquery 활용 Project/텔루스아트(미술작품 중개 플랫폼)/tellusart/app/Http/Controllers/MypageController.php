<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use Illuminate\Http\Request;
use TLCfund\User;
use TLCfund\Batting;
use TLCfund\Product;
use TLCfund\Cart;
use TLCfund\Order;
use TLCfund\Delivery;
use TLCfund\Review;
use TLCfund\Banner;

use Facades\App\Classes\SweetTracker;
use Auth;
use File;
use Hash;
use DB;

class MypageController extends Controller
{
	
	
	public function __construct()
	{
		$this->middleware('auth')->except('howtouse');
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
		$this->banner = Banner::where('bn_alt','마이페이지')->first();
	}
	
	public function myinfor(){
		$user = Auth::user();
		
		$battings = Batting::where('uid',$user->id);
		$products = Product::where('seller_id',$user->id);
		$carts = Cart::where('uid',$user->id);
		
			$views = view($this->device.'.'.'mypage.myinfor_edit');
		
		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->cart_cnt = $carts->count();
		$views->mp_kind = 0;
		$views->banner = $this->banner;
		
		
		return $views;
	}
	
	public function myinfor_update(Request $request){
    	$user = Auth::user();
		
		User::where('id',$user->id)->update([
			'name' => $request->input("mb_id"),
			'nickname' => $request->input("mb_nickname"),
			'mobile_number' => $request->input("mb_hp"),
			'post_num' => $request->input("post_num"),
			'addr1' => $request->input("mb_addr1"),
			'addr2' => $request->input("mb_addr2"),
		]);
		
		return redirect(route('mypage.myinfor'));
		
	}
	
	public function profile_img_change(Request $request){
		
		if($request->hasFile('profile_img')){
			if ($request->file('profile_img')->isValid()) {
				$path = $request->profile_img->store('public/image/');
				
				$before_img = asset('/storage/image/').Auth::user()->profile_img;
			
				if(File::exists($before_img)) {
					if(Auth::user()->profile_img != 'default_profile.png') {
						File::delete($before_img);
					}
				}
				
				$path = str_replace("public/image/","",$path);
				
				User::where('id',Auth::user()->id)->update([
					'profile_img' => $path,
				]);
			}
		}
		
		return redirect()->back();
		
	}
	
	public function myart_list(){
		
		$user = Auth::user();
		
		$battings = Batting::where('uid',$user->id);
		$products = $products = Product::where('seller_id',$user->id)->with('reviews')->with('battings')->orderBy('id','desc');
		$carts = Cart::where('uid',$user->id);
		
    	$views = view($this->device.'.'.'mypage.myart_list');
		
		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->products = $products;
		$views->cart_cnt = $carts->count();
		$views->mp_kind = 1;
		$views->banner = $this->banner;
		
		
		return $views;
    }
	
	public function mybatting_list(Request $request){
		
		$user = Auth::user();
		

		$products = $products = Product::where('seller_id',$user->id)->with('reviews')->with('battings');
		$carts = Cart::where('uid',$user->id);
		$battings = Batting::where('uid',$user->id);
		
		if($request->input("status") != 0 && $request->input("status") !== NULL){
			$status = $request->input("status");
			
			if($request->input("dateTerm") !== NULL){
				$dateTerm = $request->input("dateTerm");
				$from_date = NULL;
				$to_date = NULL;

				$batting_ings = Batting::where('uid',$user->id)->where('batting_status',1)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
				$batting_ends = Batting::where('uid',$user->id)->where('batting_status',2)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
				
				if($status == 1){
					$batting_lists = $batting_ings->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
				}else{
					$batting_lists = $batting_ends->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
				}
				
			}else{
				
				if($request->input('from_date') !== NULL){
					$from_date = $request->input('from_date');
					$to_date = $request->input('to_date');
					$dateTerm = NULL;

					$batting_ings = Batting::where('uid',$user->id)->where('batting_status',1)->whereDate('created_at',">=",$from_date)->whereDate('updated_at',"<=",$to_date);
					$batting_ends = Batting::where('uid',$user->id)->where('batting_status',2)->whereDate('created_at',">=",$from_date)->whereDate('updated_at',"<=",$to_date);
					
					if($status == 1){
						$batting_lists = $batting_ings->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
					}else{
						$batting_lists = $batting_ends->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
					}
						
				}else{
					$from_date = NULL;
					$to_date = NULL;
					$dateTerm = 7;

					$batting_ings = Batting::where('uid',$user->id)->where('batting_status',1)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
					$batting_ends = Batting::where('uid',$user->id)->where('batting_status',2)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
					
					if($status == 1){
						$batting_lists = $batting_ings->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
					}else{
						$batting_lists = $batting_ends->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
					}
				}
			}
			
			
		}else{
			$status = 0;
			
			if($request->input("dateTerm") !== NULL){
				$dateTerm = $request->input("dateTerm");
				$from_date = NULL;
				$to_date = NULL;

				$batting_ings = Batting::where('uid',$user->id)->where('batting_status',1)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
				$batting_ends = Batting::where('uid',$user->id)->where('batting_status',2)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
				
				if($status == 1){
					$batting_lists = $battings->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
				}else{
					$batting_lists = $battings->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
				}
				
			}else{
				
				if($request->input('from_date') !== NULL){
					$from_date = $request->input('from_date');
					$to_date = $request->input('to_date');
					$dateTerm = NULL;

					$batting_ings = Batting::where('uid',$user->id)->where('batting_status',1)->whereDate('created_at',">=",$from_date)->whereDate('updated_at',"<=",$to_date);
					$batting_ends = Batting::where('uid',$user->id)->where('batting_status',2)->whereDate('created_at',">=",$from_date)->whereDate('updated_at',"<=",$to_date);
					
					$batting_lists = $battings->whereDate('created_at',">=",$from_date)->whereDate('updated_at',"<=",$to_date)->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);

				}else{
					$from_date = NULL;
					$to_date = NULL;
					$dateTerm = 7;

					$batting_ings = Batting::where('uid',$user->id)->where('batting_status',1)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
					$batting_ends = Batting::where('uid',$user->id)->where('batting_status',2)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
					
					$batting_lists = $battings->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);

				}
			}

			$batting_ings = Batting::where('uid',$user->id)->where('batting_status',1)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
			$batting_ends = Batting::where('uid',$user->id)->where('batting_status',2)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')));
			
			$batting_lists = $battings->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('batting_status','asc')->paginate(7);
		}
		
		$batting_lists->withPath('/mypage/mybatting_list');
		
		
    	$views = view($this->device.'.'.'mypage.mybatting_list');
		
		$views->battings = $battings;
		$views->batting_ings = $batting_ings;
		$views->batting_ends = $batting_ends;
		$views->batting_lists = $batting_lists;
		$views->batting_cnt = Batting::where('uid',$user->id)->count();
		$views->search_batting_cnt = $batting_ings->count() + $batting_ends->count();
		$views->product_cnt = $products->count();
		$views->products = $products;
		$views->cart_cnt = $carts->count();
		$views->status = $status;
		$views->dateTerm = $dateTerm;
		$views->from_date = $from_date;
		$views->to_date = $to_date;
		$views->mp_kind = 2;
		$views->banner = $this->banner;
		
		
		return $views;

    }
	
	public function cart(){
		
		$user = Auth::user();
		
		$battings = Batting::where('uid',$user->id);
		$products = Product::where('seller_id',$user->id);
		$carts = Cart::where('uid',$user->id);
		
    	$views = view($this->device.'.'.'mypage.cart');
		
		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->cart_cnt = $carts->count();
		$carts = $carts->with(array('product' =>function($query){ $query->with('user'); }))->paginate(7);
		$views->carts = $carts;
		
		$carts->withPath('/mypage/cart');
		
		$views->mp_kind = 3;
		$views->banner = $this->banner;
		
		
		return $views;
    }
	
	public function cart_delete(Request $request){
		
		$idnum = explode("|", $request->input('delete_num'));
		
		for($i=0; $i<count($idnum); $i++){
			Cart::where('id',$idnum[$i])->delete();
		}
		
		return redirect(route('mypage.cart'));
	}
	
	public function my_order_list(Request $request){
		$user = Auth::user();
		
		$battings = Batting::where('uid',$user->id);
		$products = Product::where('seller_id',$user->id);
		$carts = Cart::where('uid',$user->id);
		$orders = Order::where('uid',$user->id);
		$delivery_companys = SweetTracker::companylist();
		
		$views = view($this->device.'.'.'mypage.my_order_list');
		
		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->cart_cnt = $carts->count();
		$views->mp_kind = 4;
		$views->delivery_companys = $delivery_companys;
		
		
		$views->order_zcnt = Order::where('uid', Auth::user()->id)->where('order_state',0)->where('order_cancel','<>',1)->count();
		$views->order_ocnt = Order::where('uid', Auth::user()->id)->where('order_state',1)->where('order_cancel','<>',1)->count();
		$views->order_tcnt = Order::where('uid', Auth::user()->id)->where('order_state',2)->where('order_cancel','<>',1)->count();
		$views->order_thcnt = Order::where('uid', Auth::user()->id)->where('order_state',3)->where('order_cancel','<>',1)->count();
		$views->order_fcnt = Order::where('uid', Auth::user()->id)->where(function($query){ $query->where('order_state',4)->orwhere('order_cancel',1); })->count();
		$views->order_ficnt = Order::where('uid', Auth::user()->id)->where('order_state',5)->where('order_cancel','<>',1)->count();
		
		if($request->input("status") != -1 && $request->input("status") !== NULL){
			
			$status = $request->input("status");
			
			if($request->input("dateTerm") !== NULL){
				$dateTerm = $request->input("dateTerm");
				$from_date = NULL;
				$to_date = NULL;
				
				$orders = $orders->where('order_state',$status)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('id', 'desc')->paginate(7);	

			}else{
				
				if($request->input('from_date') !== NULL){
					$from_date = $request->input('from_date');
					$to_date = $request->input('to_date');
					$dateTerm = NULL;
					
					$orders = $orders->where('order_state',$status)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('id', 'desc')->paginate(7);

				}else{
					$from_date = NULL;
					$to_date = NULL;
					$dateTerm = 100;
					$orders = $orders->where('order_state',$status)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('id', 'desc')->paginate(7);

				}
			}
			
			
		}else{
			$status = -1;
			
			if($request->input("dateTerm") !== NULL){
				$dateTerm = $request->input("dateTerm");
				$from_date = NULL;
				$to_date = NULL;
					
				$orders = $orders->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('id', 'desc')->paginate(7);

			}else{
				if($request->input('from_date') !== NULL){
					$from_date = $request->input('from_date');
					$to_date = $request->input('to_date');
					$dateTerm = NULL;	
					
					$orders = $orders->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('id', 'desc')->paginate(7);

				}else{
					$from_date = NULL;
					$to_date = NULL;
					$dateTerm = 100;
					
					$orders = $orders->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->orderBy('id', 'desc')->paginate(7);

				}
			}
			//$orders = $orders->whereDate('created_at',">=",date('Y-m-d', strtotime('-7 day')))->with(array('product' =>function($query){ $query->with('user'); }))->paginate(7);
		}

		$orders->withPath('/mypage/my_order_list');

		$views->status = $status;
		$views->dateTerm = $dateTerm;
		$views->from_date = $from_date;
		$views->to_date = $to_date;
		$views->orders = $orders;

		$views->banner = $this->banner;
		
		return $views;
    }

	public function my_sale_list(Request $request){
		$user = Auth::user();
		
		$battings = Batting::where('uid',$user->id);
		$products = Product::where('seller_id',$user->id);
		$carts = Cart::where('uid',$user->id);
		$delivery_companys = SweetTracker::companylist();
		
		$views = view($this->device.'.'.'mypage.my_sale_list');
		
		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->cart_cnt = $carts->count();
		$views->mp_kind = 5;
		$views->delivery_companys = $delivery_companys;
		
		
		$sales = Order::where('seller_id', Auth::user()->id);
		
		$views->sale_zcnt = Order::where('seller_id', Auth::user()->id)->where('order_state',0)->count();
		$views->sale_ocnt = Order::where('seller_id', Auth::user()->id)->where('order_state',1)->count();
		$views->sale_tcnt = Order::where('seller_id', Auth::user()->id)->where('order_state',2)->count();
		$views->sale_thcnt = Order::where('seller_id', Auth::user()->id)->where('order_state',3)->count();
		$views->sale_fcnt = Order::where('seller_id', Auth::user()->id)->where('order_state',4)->count();
		$views->sale_ficnt = Order::where('seller_id', Auth::user()->id)->where('order_state',5)->count();

		
		if($request->input("status") != -1 && $request->input("status") !== NULL){
				
			$status = $request->input("status");
			
			if($request->input("dateTerm") !== NULL){
				$dateTerm = $request->input("dateTerm");
				$from_date = NULL;
				$to_date = NULL;
				
				$sales = $sales->where('order_state',$status)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with('user')->with(array('product' =>function($query){ $query->with('category'); }))->orderBy('id', 'desc')->paginate(7);	
				
			}else{
				
				if($request->input('from_date') !== NULL){
					$from_date = $request->input('from_date');
					$to_date = $request->input('to_date');
					$dateTerm = NULL;
					
					$sales = $sales->where('order_state',$status)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with('user')->with(array('product' =>function($query){ $query->with('category'); }))->orderBy('id', 'desc')->paginate(7);	
					
				}else{
					$from_date = NULL;
					$to_date = NULL;
					$dateTerm = 100;
					$sales = $sales->where('order_state',$status)->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with('user')->with(array('product' =>function($query){ $query->with('category'); }))->orderBy('id', 'desc')->paginate(7);	
				}
			}
			
			
		}else{
			$status = -1;
			
			if($request->input("dateTerm") !== NULL){
				$dateTerm = $request->input("dateTerm");
				$from_date = NULL;
				$to_date = NULL;
					
				$sales = $sales->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with('user')->with(array('product' =>function($query){ $query->with('category'); }))->orderBy('id', 'desc')->paginate(7);
			}else{
				if($request->input('from_date') !== NULL){
					$from_date = $request->input('from_date');
					$to_date = $request->input('to_date');
					$dateTerm = NULL;	
					
					$sales = $sales->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with('user')->with(array('product' =>function($query){ $query->with('category'); }))->orderBy('id', 'desc')->paginate(7);
					
				}else{
					$from_date = NULL;
					$to_date = NULL;
					$dateTerm = 100;
					
					$sales = $sales->whereDate('created_at',">=",date('Y-m-d', strtotime('-'.$dateTerm.' day')))->with('delivery')->with('user')->with(array('product' =>function($query){ $query->with('category'); }))->orderBy('id', 'desc')->paginate(7);
				}
			}
			//$orders = $orders->whereDate('created_at',">=",date('Y-m-d', strtotime('-7 day')))->with(array('product' =>function($query){ $query->with('user'); }))->paginate(7);
		}

		$sales->withPath('/mypage/my_sale_list');

		$views->status = $status;
		$views->dateTerm = $dateTerm;
		$views->from_date = $from_date;
		$views->to_date = $to_date;
		$views->sales = $sales;

		$views->banner = $this->banner;
		
		return $views;
    }
	
	public function insert_delivery(Request $request){
		$order_id = $request->input('order_id');
		$delivery_company = $request->input('delivery_company');
		$send_post_num = $request->input('send_post_num');
		
		$delivery_api_res = SweetTracker::trackingInfo($delivery_company,$send_post_num);
		if(!isset($delivery_api_res['status'])){
			Delivery::where('order_id',$order_id)->update([
				"delivery_company" => $delivery_company,
				"send_post_num" => $send_post_num,
				"delivery_date" => date("Y-m-d H:i:s"),
			]);
			
			Order::where('id',$order_id)->update([
				"order_state" => 2,
			]);
			return redirect()->back()->with('jsAlert', '주문번호 '.$order_id.'의 배송정보 입력이 완료되었습니다.');
		}else{
			return redirect()->back()->with('jsAlert', '존재하지 않는 송장번호입니다. 택배사와 송장번호를 다시 확인해 주세요.');
		}
	}
	
	public function my_comment_list(){
		$user = Auth::user();
		
		$battings = Batting::where('uid',$user->id);
		$products = Product::where('seller_id',$user->id);
		$carts = Cart::where('uid',$user->id);
		
    	$views = view($this->device.'.'.'mypage.my_comment_list');
		
		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->cart_cnt = $carts->count();
		$views->mp_kind = 6;
		
		$views->review_cnt = Review::where('writer_id',$user->id)->count();
		
		$reviews = Review::where('writer_id',$user->id)->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->paginate(7);
		
		$reviews->withPath('/mypage/my_comment_list');

		$views->reviews = $reviews;

		$views->banner = $this->banner;
		
		
    	return $views;
    }

	public function order_cancel(Request $request){
		$order_id = $request->input('order_id');
		$cancel_reason = $request->input('cancel_reason');
		
		$order = Order::where('id',$order_id)->first();
		if($order->order_state == 0) {
			// 주문신청만 됐을 시 바로 취소
			Order::where('id',$order_id)->where('order_state', 0)->update([
				"order_state" => 4,
				"order_cancel" => 1,
				"pay_cancel_infor" => $cancel_reason,
			]);
			
			// 판매완료 상태에서 판매중 상태로 변경
			Product::where('id', $order->product_id)->update([
				'sell_yn' => 1, 
			]);
			
		} else if($order->order_state == 2) {
			// return redirect()->back()->with('jsAlert', '배송중에는 환불신청이 불가합니다. 배송완료시까지 기다려 주시기 바랍니다.');
		} else {
			// 배송대기나 배송완료 시 환불신청 가능
			Order::where('id',$order_id)->whereIn('order_state', [1, 3])->update([
				"order_cancel" => 2,
				"pay_cancel_infor" => $cancel_reason,
			]);
		}
		
		return redirect()->back();
	}

	public function password_edit(){

		$user = Auth::user();
		
		$battings = Batting::where('uid',$user->id);
		$products = Product::where('seller_id',$user->id);
		$carts = Cart::where('uid',$user->id);

		$views = view($this->device.'.'.'mypage.password_change');

		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->cart_cnt = $carts->count();
		$views->mp_kind = 7;
		$views->title = '마이페이지';

		$views->banner = $this->banner;

		return $views;
	}

	public function password_update(Request $request){

    if(!Auth::check()){
            return redirect()->route('home');
		}
		
		$new_password = $request->password;
		$new_password_confirm = $request->password_confirm;

		if(empty($new_password) || empty($new_password_confirm)){
			return redirect()->route('mypage.password_edit')->with('jsAlert', '입력값이 없습니다.');
		}

		if($new_password == $new_password_confirm){
			User::where('id', Auth::user()->id)->update(['password' => Hash::make($new_password)]);
		
			return redirect()->route('mypage.password_edit')->with('jsAlert', '성공적으로 비밀번호를 변경하였습니다.');
		}else{
			return redirect()->route('mypage.password_edit')->with('jsAlert', '비밀번호가 서로 맞지 않습니다.\n 다시 확인 후 시도해주세요.');
		}
  }
	
	public function account_edit(){
		$user = Auth::user();
		
		$battings = Batting::where('id',$user->id);
		$products = Product::where('seller_id',$user->id);
		$carts = Cart::where('uid',$user->id);
		$views = view($this->device.'.'.'mypage.account_edit');

		$views->batting_cnt = $battings->count();
		$views->product_cnt = $products->count();
		$views->cart_cnt = $carts->count();
		$views->mp_kind = 8;

		$views->banner = $this->banner;
		$views->title = '계좌정보';
		
		return $views;
	}
	public function account_update(Request $request){
		if(!Auth::check()){
      return redirect()->route('home');
		}
		$user = Auth::user();
		
		$account_bank = $request->account_bank;
		$account_number = (string)$request->account_number;
		$account_user = $request->account_user;
		
		
		if($account_bank=='0'){
			return redirect()->route('mypage.account_edit')->with('jsAlert', '은행을 선택해주세요');
		}
		
		User::where('id',$user->id)->update([
					'account_bank' => $account_bank,
					'account_number' => $account_number,
					'account_user' => $account_user,
				]);
		return redirect()->back()->with('jsAlert', '변경되었습니다');
	}

	public function mobile_mypage(Request $request){
		$index = $request->index;

		$views = view($this->device.'.'.'mypage.mypage');

		$views->index = $index;
		$views->title = '마이페이지';
		return $views;
	}

	public function howtouse(){
		if($this->device == 'pc') {
			return redirect()->route('home');
		} else if($this->device == 'mobile') {
			$howtouse = DB::table('tlca_howtouse')->first();
			
			$views = view($this->device.'.'.'howtouse.howtouse');
			$views->howtouse = $howtouse;
			$views->title = '이용방법';
			return $views;
		}
	}


}