<?php

namespace TLCfund\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TLCfund\Address;
use TLCfund\Company;
use TLCfund\User;
use TLCfund\Category;
use TLCfund\Product;
use TLCfund\Batting;
use TLCfund\Order;
use TLCfund\Event;
use TLCfund\Video;
use TLCfund\Banner;
use TLCfund\Batting_art;
use TLCfund\Faq;
use TLCfund\Notice;
use TLCfund\Contents;
use TLCfund\Howtouse;
use TLCfund\Slide;
use TLCfund\Result_calculate;
use TLCfund\Fee;
use Facades\App\Classes\EthApi;
use Session;
use File;
use Redirect;
use DateTime;
use Auth;
use DB;
use Facades\App\Classes\Coolsms;
use Facades\App\Classes\SweetTracker;

class AdminController extends Controller
{
	
	
    public function index(){
		
    	$views = view('admin.main');
		
		$views->today_user = User::whereDate('created_at',">=",date('Y-m-d', strtotime('-1 day')))->count();
		
		$views->today_product = Product::where('sell_yn',0)->whereDate('created_at',">=",date('Y-m-d', strtotime('-1 day')))->count();
    
		$views->today_order = Order::whereDate('created_at',">=",date('Y-m-d', strtotime('-1 day')))->count();
		
		$views->today_batting = Batting::where('batting_status',1)->count();
		
		$two_week = array();
		
		for($i=0;$i<=14;$i++){
			$two_week[$i] = User::whereDate('created_at',"<=",date('Y-m-d', strtotime('-'.($i).' day')))->whereDate('created_at',">",date('Y-m-d', strtotime('-'.($i+1).' day')))->count();
			$views->two_week[$i] = $two_week[$i];
		}
		
		//$views->$two_week = $two_week;
		
		return $views;
	}

	public function login(){
		if(Auth::guard('admin')->check()){
			return redirect()->route('admin.main');
		}else{
			$views = view('admin.layouts.login_app');

        	return $views;
		}
	}
	
	public function login_form(Request $request){
		date_default_timezone_set("Asia/Seoul");
		if (Auth::guard('admin') -> attempt(['email' => $request -> input('email'), 'password' => $request -> input('password')])) {
			
			DB::table('admin')->where('id',Auth::guard('admin')->user()->id)->update([
				"ip" => $_SERVER['REMOTE_ADDR'],
				"time_signin" => date('Y-m-d H:i:s'),
			]);
			
			return redirect() -> route('admin.main');

        } else {
            return redirect() -> back() -> with('jsAlert', '아이디나 비밀번호가 맞지 않습니다.');
        }

        $views = view('admin.layouts.login_app');

        return $views;
	}
	
	public function logout(Request $request) {
        Auth::guard('admin')->logout();
        Session::flush();

        return Redirect::route('admin.login');
    }

	
	public function company(){
		
		$views = view('admin.company');
		
		$company = Company::first();
		
		$views->company = $company;
			
		return $views;
		
	}
	
	public function company_update(Request $request){
		$input = $request->all();
		
		$company = Company::first();

		if(isset($company)){
			Company::where('service_name',$company->service_name)->update([
				'service_name' => $request->input('service_name'),
	        	'company_name' => $request->input('company_name'),
	        	'ceo_name' => $request->input('ceo_name'),
				'company_email' => $request->input('company_email'),
				'business_number' => $request->input('business_number'),
				'tellsell_number' => $request->input('tellsell_number'),
				'phone_num' => $request->input('phone_num'),
				'fax_num' => $request->input('fax_num'),
				'address' => $request->input('address'),
				'infor_admin' => $request->input('infor_admin'),
				'account_bank' =>$request->input('account_bank'),
				'account_number' =>$request->input('account_number'),
				'account_user' =>$request->input('account_user'),
			]);
		}else{
			Company::create([
	        	'service_name' => $request->input('service_name'),
	        	'company_name' => $request->input('company_name'),
	        	'ceo_name' => $request->input('ceo_name'),
				'company_email' => $request->input('company_email'),
				'business_number' => $request->input('business_number'),
				'tellsell_number' => $request->input('tellsell_number'),
				'phone_num' => $request->input('phone_num'),
				'fax_num' => $request->input('fax_num'),
				'address' => $request->input('address'),
				'infor_admin' => $request->input('infor_admin'),
				'account_bank' =>$request->input('account_bank'),
				'account_number' =>$request->input('account_number'),
				'account_user' =>$request->input('account_user'),
				
	        ]);
		}
		
		
		return redirect(route('admin.company'));
	}

	
	public function user_search(Request $request){
		$keyword_srch = '';
		$keyword = '';

		if(($request->input('keyword_srch'))!=NULL){
			if(($request->input('keyword_srch'))=='name'){
				$users =  User::where('name', 'like', '%'.$request->input('keyword').'%')->where('status', 1)->where('level','<>','3')->orderBy('id','desc');
			}else if(($request->input('keyword_srch'))=='id'){
				$users =  User::where('email', 'like', '%'.$request->input('keyword').'%')->where('status', 1)->where('level','<>','3')->orderBy('id','desc');
			}else if(($request->input('keyword_srch'))=='nickname'){
				$users =  User::where('nickname', 'like', '%'.$request->input('keyword').'%')->where('status', 1)->where('level','<>','3')->orderBy('id','desc');
			}else if(($request->input('keyword_srch'))=='mobile'){
				$users =  User::where('mobile_number', 'like', '%'.$request->input('keyword'))->where('status', 1)->where('level','<>','3')->orderBy('id','desc');
			}
		}else{
			$users =  User::where('status', 1)->where('level','<>','3')->orderBy('created_at','desc');		
		}

		$keyword_srch = $request->input('keyword_srch');
		$keyowrd = $request->input('keyowrd');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.user_list');
		$users_page = $users->paginate(20);
		$users_page->withPath('/admin/user_list');
		$views->keyword_srch = $keyword_srch;
		$views->keyword = $keyword;
		$views->users_page = $users_page;
		$views->datetime = $datetime;
		
		
		return $views;
	}
	
	public function user_delete($id){
		
		User::where('id',$id)->delete();
		
		return redirect()->back();
	}
	
	public function category_list(){
		$categorys = Category::where('ca_use','<>',2)->orderBy('id','desc')->get();
		
		$categorys_page = Category::paginate(20);
		$categorys_page->withPath('/admin/category_list');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.category_list');
		$views->categorys = $categorys;
		$views->categorys_page = $categorys_page;
		$views->datetime = $datetime;
		return $views;
	}
	
	public function category_create(){		
		$views = view('admin.category_edit');
		return $views;
	}
	
	public function category_update(Request $request){
		$input = $request->all();
		$path = NULL;
		
		if($request->hasFile('ca_icon')){
			if ($request->file('ca_icon')->isValid()) {
				$path = $request->ca_icon->store('public/image');
			}
		}
		
		$path = str_replace("public/image/","",$path);
		
		Category::create([
        	'ca_name' => $request->input('ca_name'),
        	'ca_sm_name' => $request->input('ca_sm_name'),
        	'ca_discript' => $request->input('ca_discript'),
        	'ca_icon' => $path,
			'ca_use' => $request->input('ca_use'),
        ]);

		return redirect()->route('admin.category_list');
	}
	
	public function category_delete($id){
		
		Product::where('ca_id',$id)->update([
			'ca_id' => 13579,
		]);
				
		$category = Category::where('id',$id)->first();
		
		Storage::delete(str_replace("/","",$category->ca_icon));
		
		 $img_path = '../storage/app/public/image/'.$category->ca_icon;
		
		if(File::exists($img_path)) {
		    File::delete($img_path);
		}
		
		Category::where('id',$id)->delete();
		
		return redirect()->route('admin.category_list');
	}
	
	public function product_list(Request $request, $sell_yn){

		$keyword_srch = '';
		$keyword = '';
		
		if(($request->input('keyword_srch'))!=NULL){
			if(($request->input('keyword_srch'))=='user_name'){
				$keyword = $request->input('keyword');
				$products = Product::where('sell_yn',$sell_yn)->with(array('user'=>function($query) use ($keyword) {$query->where('users.name','like','%'.$keyword.'%');}));
			}else if(($request->input('keyword_srch'))=='user_email'){
				$keyword = $request->input('keyword');
				$products = Product::where('sell_yn',$sell_yn)->with(array('user'=>function($query) use ($keyword) {$query->where('users.email','like','%'.$keyword.'%');}));
			}else if(($request->input('keyword_srch'))=='title'){
				$keyword = $request->input('keyword');
				$products = Product::where('sell_yn',$sell_yn)->where('title','like','%'.$keyword.'%')->with('user');
			}
		}else{
			$products = Product::where('sell_yn',$sell_yn)->with('user');	
		}
		
		$products_page = $products->orderBy('created_at','desc')->paginate(20);
		$products_page->withPath('/admin/product_list/'.$sell_yn);

		$keyword_srch = $request->input('keyword_srch');
		$keyword = $request->input('keyword');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.product_list');
		
		$views->products = $products->get();
		$views->products_page = $products_page;
		$views->datetime = $datetime;
		$views->sell_yn = $sell_yn;
		$views->keyword_srch = $keyword_srch;
		$views->keyword = $keyword;
		
		if($sell_yn == 0){ //판매승인대기
			$title = "판매 신청 작품";
		}else if($sell_yn == 1){  //판매승인
			$title = "판매 승인 작품";
		}else if($sell_yn == 2){  //판매승인취소
			$title = "판매 거절 작품";
		}else if($sell_yn == 3){  //판매완료
			$title = "판매 완료 작품";
		}
		
		$views->title = $title;
		
		return $views;
		
	}

	public function sell_yn_change(Request $request){
		$input = $request->all();
		
		$product = Product::where('id',$request->input('id'))->first();
		
		if($request->input('sell_yn_change') == 1){
			Product::where('id',$request->input('id'))->update([
				'sell_yn' => $request->input('sell_yn_change'),
				'reject_infor' => '',
			]);	
		}else if($request->input('sell_yn_change') == 2){
			Product::where('id',$request->input('id'))->update([
				'sell_yn' => $request->input('sell_yn_change'),
	        	'reject_infor' => $request->input('reject_infor'),
			]);
		}

		return redirect(route('admin.product_list',$product->sell_yn));
	}
	
	public function adm_batting_list($kind){
		if($kind == 0){
			
		}else if($kind == 1){
			
		}else if($kind == 2){
			
		}
	}
	
	public function batting_product(Request $request, $batting_status){
		$keyword_srch = '';
		$keyword = '';
		$case = 1;
		
		if(($request->input('keyword_srch'))!=NULL){
			if(($request->input('keyword_srch'))=='user_name'){
				$keyword = $request->input('keyword');

				$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('batting_status',$batting_status)->with(array('user'=>function($query) use ($keyword) {$query->where('users.name','like','%'.$keyword.'%');}));	

				foreach($products->get() as $product){
					if(!isset($product->user)){
						$case = 0;
					}
				}
			}else if(($request->input('keyword_srch'))=='user_email'){
				$keyword = $request->input('keyword');

				$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('batting_status',$batting_status)->with('battings')->with(array('user'=>function($query) use ($keyword) {$query->where('users.email','like','%'.$keyword.'%');}));	

				foreach($products->get() as $product){
					if(!isset($product->user)){
						$case = 0;
					}
				}
			}else if(($request->input('keyword_srch'))=='title'){
				$keyword = $request->input('keyword');

				$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('batting_status',$batting_status)->where('title','like','%'.$keyword.'%')->with('user');	

			}
		}else{
			$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('batting_status',$batting_status)->with('battings')->with('user');	
		}

		if($batting_status == 1){
			$title = '베팅 중인 작품';
		}elseif($batting_status == 2){
			$title = '베팅 종료 작품';
		}
		
		$products_page = $products->orderBy('start_time','desc')->paginate(20);
		$products_page->withPath('/admin/batting_product/'.$batting_status);

		$keyword_srch = $request->input('keyword_srch');
		$keyowrd = $request->input('keyowrd');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.batting_product');
		$views->batting_status = $batting_status;
		$views->title = $title;
		$views->result = 0;
		$views->keyword_srch = $keyword_srch;
		$views->keyword = $keyword;

		$views->products = $products->orderBy('start_time','desc')->get();	

		$views->products_page = $products_page;
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function this_week_batting(Request $request){
		$case = 1;
		$keyword_srch='';
		$keyword='';
		
		$batting_set = DB::table('tlca_batting_set')->first();
		
		if(($request->input('keyword_srch'))!=NULL){
			if(($request->input('keyword_srch'))=='user_name'){
				$keyword = $request->input('keyword');
				$products = Product::where('batting_yn',1)
					->where('batting_status',1)
					->where('sell_yn','<>',0)
					->where('sell_yn','<>',2)
					->where('ca_use',1)
					->where('artist_name','like','%'.$keyword.'%')
					->with('battings');
			}else if(($request->input('keyword_srch'))=='user_email'){
				$keyword = $request->input('keyword');
				$products = Product::where('batting_yn',1)
					->where('batting_status',1)
					->where('sell_yn','<>',0)
					->where('sell_yn','<>',2)
					->where('ca_use',1)
					->with('battings')
					->whereHas('user', function($query) use ($keyword) {$query->where('users.email','like','%'.$keyword.'%');});
			}else if(($request->input('keyword_srch'))=='title'){
				$keyword = $request->input('keyword');
				$products = Product::where('batting_yn',1)
					->where('batting_status',1)
					->where('sell_yn','<>',0)
					->where('sell_yn','<>',2)
					->where('ca_use',1)
					->where('title','like','%'.$keyword.'%')
					->with('battings')
					->with('user');
			}
		}else{
			$products = Product::where('batting_yn',1)->where('batting_status',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1)->with('battings')->with('user');
		}

		foreach($products->get() as $product){
			$result = 0;
			foreach($product->battings as $battings){
				$result += $battings->batting_price;
			}
			Product::where('id',$product->id)->update([
				'coin_batting'=>$result,
			]);
		}

		$keyword_srch = $request->input('keyword_srch');
		$keyword = $request->input('keyword');

		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$dt_max = $batting_set->end_time;
		$dt_min = date('Y-m-d',strtotime($dt_max.'-'.$batting_set->batting_term.' days'));
		
		/*$dt_min = new DateTime("last saturday");
		$dt_min->modify('+2 day');
		$batting_set->end_time
		$dt_max = clone($dt_min);
		$dt_max->modify('+6 days');*/

		$products = $products->whereBetween('end_time', [$dt_min, $dt_max]);

		$products_page = $products->orderBy('coin_batting','desc')->paginate(20)->appends(request()->query());
		$products_page->withPath('/admin/this_week_batting');
		
		$views = view('admin.week_batting');
		$views->products = $products->orderBy('coin_batting','desc')->get();
		$views->ranking = 1;
		$views->products_page = $products_page;
		$views->keyword_srch = $keyword_srch;
		$views->keyword = $keyword;
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function batting_list(Request $request){
		$case = 1;
		$keyword_srch = '';
		$keyword = '';
		
		if(($request->input('keyword_srch'))!=NULL){
			if(($request->input('keyword_srch'))=='user_name'){
				$keyword = $request->input('keyword');
				$battings = Batting::whereHas('user',function($query) use ($keyword) {$query->where('name','like','%'.$keyword.'%')->orwhere('nickname','like','%'.$keyword.'%');})->whereHas('product');

			}else if(($request->input('keyword_srch'))=='user_email'){
				$keyword = $request->input('keyword');
				$battings = Batting::whereHas('user',function($query) use ($keyword) {$query->where('email','like','%'.$keyword.'%');})->whereHas('product');

			}else if(($request->input('keyword_srch'))=='mobile'){
				$keyword = $request->input('keyword');
				$battings = Batting::whereHas('user',function($query) use ($keyword) {$query->where('mobile_number','like','%'.$keyword.'%');})->whereHas('product');	
			}
		}else{
			$battings = Batting::whereHas('user')->whereHas('product');
			
		}
		
		$battings_page = $battings->orderBy('id','desc')->paginate(20)->appends(request()->query());
		$battings_page->withPath('/admin/batting_list');
		
		$keyword_srch = $request->input('keyword_srch');
		$keyword = $request->input('keyword');
		
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.batting_list');
		
		
		//$views->battings = $battings->orderBy('created_at','desc')->get();	
		
		$views->battings_page = $battings_page;
		$views->datetime = $datetime;
		$views->keyword_srch = $keyword_srch;
		$views->keyword = $keyword;
		
		return $views;
		
	}
	
	public function batting_winner(){
		
	}
	
	public function past_batting_list($ca_id, $bat_cnt){
		if($ca_id == 0){
			if($bat_cnt == 0){
				$past_batlis_latest_cnt = Batting_art::orderBy('id', 'desc')->first();
				$past_batlis = Batting_art::with('category')->with('product')->with('user')->orderBy('id','desc');
				$categorys = Category::where('ca_use',1)->get();
			
				if($past_batlis_latest_cnt === null) {
					$past_batlis_latest_cnt = 0;
				} else {
					$past_batlis_latest_cnt = $past_batlis_latest_cnt->bat_cnt;
				}
			}else{
				$past_batlis_latest_cnt = Batting_art::where('bat_cnt',$bat_cnt)->orderBy('id', 'desc')->first();
				$past_batlis = Batting_art::where('bat_cnt',$bat_cnt)->with('category')->with('product')->with('user')->orderBy('id','desc');
				$categorys = Category::where('ca_use',1)->get();
			
				if($past_batlis_latest_cnt === null) {
					$past_batlis_latest_cnt = 0;
				} else {
					$past_batlis_latest_cnt = $past_batlis_latest_cnt->bat_cnt;
				}	
			}
		}else{
			if($bat_cnt == 0){
				$past_batlis_latest_cnt = Batting_art::where('ca_id',$ca_id)->orderBy('id', 'desc')->first();
				$past_batlis = Batting_art::where('ca_id',$ca_id)->with('category')->with('product')->with('user')->orderBy('id','desc');
				$categorys = Category::where('ca_use',1)->get();
			
				if($past_batlis_latest_cnt === null) {
					$past_batlis_latest_cnt = 0;
				} else {
					$past_batlis_latest_cnt = $past_batlis_latest_cnt->bat_cnt;
				}
			}else{
				$past_batlis_latest_cnt = Batting_art::where('ca_id',$ca_id)->orderBy('id', 'desc')->first();
				$past_batlis = Batting_art::where('ca_id',$ca_id)->where('bat_cnt',$bat_cnt)->with('category')->with('product')->with('user')->orderBy('id','desc');
				$categorys = Category::where('ca_use',1)->get();
			
				if($past_batlis_latest_cnt === null) {
					$past_batlis_latest_cnt = 0;
				} else {
					$past_batlis_latest_cnt = $past_batlis_latest_cnt->bat_cnt;
				}
			}
			
		}

		$past_batlis = $past_batlis->paginate(9)->appends(request()->query());
		$past_batlis->withPath("/admin/past_batting_list/$ca_id/$bat_cnt");

		$views = view('admin.past_batting_list');
		$views->past_batlis = $past_batlis;
		$views->past_batlis_latest_cnt = $past_batlis_latest_cnt;
		$views->bat_cnt = $bat_cnt;
		$views->categorys = $categorys;
		$views->ca_id = $ca_id;
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views->datetime = $datetime;
		
		return $views;
		
	}
	
	public function order_list(Request $request){
				
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$order_state = ($request->input('order_state')) != NULL ? $request->input('order_state') : "0";
		$how_pay = ($request->input('how_pay')) != NULL ? $request->input('how_pay') : "0";
		$start_time = $request->input('start_time') ?: date("Y-m-d", strtotime("-100 day", time()));
		$end_time =$request->input('end_time') ?: date("Y-m-d");

		// 타임존 보정
		$start_time_utc_0 = date("Y-m-d", strtotime($start_time.'-9 hours'));
		$end_time_utc_0 = date("Y-m-d", strtotime($end_time.'-9 hours'));

		$orders = Order::whereDate('created_at','>=',$start_time_utc_0)
			->whereDate('created_at','<=',$end_time_utc_0)
			->where('order_state',$order_state)
			->where('how_pay',$how_pay)->with('user')
			->with('seller')
			->with('product')
			->with('delivery')
			->with('deposit_pay');

		$orders_page = $orders->orderBy('created_at','desc')->paginate(20);
		$orders_page->withPath('/admin/order_list');
		
		
		
		$views = view('admin.order_list');
		
		$views->start_time = $start_time;
		$views->end_time = $end_time;
		$views->orders = $orders->orderBy('created_at','desc')->get();
		$views->orders_page = $orders_page;		
		$views->order_state = $order_state;
		$views->how_pay = $how_pay;
		
		$views->title = "주문리스트";
		$views->datetime = $datetime;
		
		return $views;
	}

	public function order_deposite($id){
		$order = Order::where('id',$id)->with('seller')->with('product')->first();
		
		if(strpos($order->seller->mobile_number,'-') !== false ){
			$mobile_number = str_replace('-','',$order->seller->mobile_number);
		}else{
			$mobile_number = $order->seller->mobile_number;
		}
		
		//문자 전송 api 입력
		$sms = Coolsms::send_sms($mobile_number,'주문번호 '.$id.'('.$order->product->title.')의 입금처리가 완료되었습니다. 해당 물품을 배송보내신 후 배송회사와 송장번호를 입력해 주세요.');
		
		if($sms['status'] == 'ok'){
			Order::where('id',$id)->update([
	        	'order_state' => 1,
			]);
			
			return redirect()->route('admin.order_list')->with('jsAlert','판매자에게 입금확인 SMS가 날라갑니다.');
		}else if($sms['status'] == 'bad_request'){
			return redirect()->route('admin.order_list')->with('jsAlert','문자 업체 통신 장애로 잠시 후 다시 시도해 주시기 바랍니다.');
		}else if($sms['status'] == 'payment_required'){
			return redirect()->route('admin.order_list')->with('jsAlert','잔액이 부족합니다. coolsms.co.kr 로 가서 충전해주세요.');
		}else{
			return redirect()->route('admin.order_list')->with('jsAlert','네트워크 상 오류가 발생했습니다. 잠시 후 다시 시도해 주시기 바랍니다.');
		}
		
	}

	public function order_refund($id){		
		$result = Order::where('id', $id)->update([
			'order_state' => 4,
		]);

		if($result){
			$order = Order::where('id', $id)->first();
		
			if($order->how_pay == 10){
				// 코인이면 바로 환불 처리
				$user = Address::where('user_id',$order->uid)->first();
				EthApi::addInfoRequest($user->user_email, 'deposit', $order->total_price, 'refund');
				Address::where('user_id',$order->uid)->increment('available_balance_tlc', $order->total_price);
			}

			// 판매완료 상태에서 판매중 상태로 변경
			Product::where('id', $order->product_id)->update([
				'sell_yn' => 1, 
			]);

			return redirect()->route('admin.order_list')->with('jsAlert','해당 주문의 환불처리가 완료되었습니다.');
		}

		return redirect()->route('admin.order_list');
	}
	
	public function order_delivery($id){
		Order::where('id',$id)->update([
			'order_state' => 3,
		]);
	
		return redirect()->route('admin.order_list')->with('jsAlert','해당 주문의 배송상태가 배송완료로 처리되었습니다.');
	}

	public function event_list($state) {
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		$today = date("Y-m-d 00:00:00");
		$events = Event::orderBy('created_at','desc')->orderBy('id','desc');
		
		if($state == 1) {
			$events = $events->whereDate('start_time','>',$today);
		} else if($state == 2) {
			$events = $events->whereDate('start_time','<=',$today)->whereDate('end_time','>=',$today);
		} else if($state == 3){
			$events = $events->whereDate('end_time','<',$today);
		}
				
		$events_page = $events->paginate(20);
		$events_page->withPath('/admin/event_list');
		
		
		$views = view('admin.event_list');
		
		$views->events = $events->get();
		$views->events_page = $events_page;
		
		$views->state = $state;
		$views->today = $today;
		$views->title = "이벤트관리";
		$views->datetime = $datetime;
		
		return $views;
	}

	public function event_create() {
		$views = view('admin.event_edit');
		return $views;
	}
	
	public function event_show($id) {
		$event = Event::where('id', $id)->get()->first();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.event_show');
		
		$views->event = $event;
		$views->today = date("Y-m-d H:i:s");
		$views->title = "이벤트관리";
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function event_store(Request $request){
		$input = $request->all();
		$path1 = NULL;
		$path2 = NULL;
		$path3 = NULL;
		
		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$file_name1 = $request->file1->getClientOriginalName();
				$path1 = $request->file1->storeAs('public/event', $file_name1);
				$path1 = str_replace("public/event/","",$path1);
			}
		}
		
		if($request->hasFile('pc_banner')){
			if ($request->file('pc_banner')->isValid()) {
				$file_name2 = $request->pc_banner->getClientOriginalName();
				$path2 = $request->pc_banner->storeAs('public/event', $file_name2);
				$path2 = str_replace("public/event/","",$path2);
			}
		}
		
		if($request->hasFile('mobile_banner')){
			if ($request->file('mobile_banner')->isValid()) {
				$file_name3 = $request->mobile_banner->getClientOriginalName();
				$path3 = $request->mobile_banner->storeAs('public/event', $file_name3);
				$path3 = str_replace("public/event/","",$path3);
			}
		}
		
		date_default_timezone_set("Asia/Seoul");
		
        Event::create([
        	'title' => $request->input('title'),
        	'body' => $request->input('body'),
        	'start_time' => $request->input('start_time') . ' 00:00:00',
        	'end_time' => $request->input('end_time') . ' 00:00:00',
        	'file1' => $path1,
        	'pc_banner' => $path2,
        	'mobile_banner' => $path3,
        ]);
		
		return Redirect::route('admin.event_list', 0);
	}
	
	public function event_delete($id){				
		$event = Event::where('id',$id)->first();
		
		$img_path = '../storage/app/public/image/'.$event->file1;
		
		if(File::exists($img_path)) {
		    File::delete($img_path);
		}
		
		$img_path = '../storage/app/public/image/'.$event->file2;
		
		if(File::exists($img_path)) {
		    File::delete($img_path);
		}
		
		Event::where('id',$id)->delete();
		
        return Redirect::route('admin.event_list', 0);
    }

	public function event_edit($id){
		$event = Event::where('id',$id)->first();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.event_edit');
		
		$views->event = $event;
		$views->today = date("Y-m-d H:i:s");
		$views->title = "이벤트관리";
		$views->datetime = $datetime;
		
		return $views;
	}

    public function event_update(Request $request, $id){
        $event = Event::where('id',$id)->first();	
		$path1 = $event->file1;
		$path2 = $event->pc_banner;
		$path3 = $event->mobile_banner;
		
		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$img_path1 = '../storage/app/public/event/'.$path1;
				if($path1 != NULL && File::exists($img_path1)) {
				    File::delete($img_path1);
				}
				
				$path1 = $request->file1->store('public/image/event');
				$path1 = str_replace("public/image/event/","",$path1);

				$file_name1 = $request->file1->getClientOriginalName();
				$path1 = $request->file1->storeAs('public/event', $file_name1);
				$path1 = str_replace("public/event/","",$path1);
			}
		}
		
		if($request->hasFile('pc_banner')){
			if ($request->file('pc_banner')->isValid()) {
				$img_path2 = '../storage/app/public/event/'.$path2;
				if($path2 != NULL && File::exists($img_path2)) {
				    File::delete($img_path2);
				}
				
				$file_name2 = $request->pc_banner->getClientOriginalName();
				$path2 = $request->pc_banner->storeAs('public/event', $file_name2);
				$path2 = str_replace("public/event/","",$path2);
			}
		}
		
		if($request->hasFile('mobile_banner')){
			if ($request->file('mobile_banner')->isValid()) {
				$img_path3 = '../storage/app/public/event/'.$path3;
				if($path3 != NULL && File::exists($img_path3)) {
				    File::delete($img_path3);
				}
				
				$file_name3 = $request->mobile_banner->getClientOriginalName();
				$path3 = $request->mobile_banner->storeAs('public/event', $file_name3);
				$path3 = str_replace("public/event/","",$path3);
			}
		}
        
		Event::where('id',$event->id)->update([
			'title' => $request->input('title'),
        	'body' => $request->input('body'),
        	'start_time' => $request->input('start_time') . ' 00:00:00',
        	'end_time' => $request->input('end_time') . ' 00:00:00',
        	'file1' => $path1,
        	'pc_banner' => $path2,
        	'mobile_banner' => $path3,
		]);

		return Redirect::route('admin.event_show', $event->id);
	}
	
	public function refund_list(Request $request){		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$refund_type = ($request->input('refund_type')) != NULL ? $request->input('refund_type') : "1";
		$how_pay = ($request->input('how_pay')) != NULL ? $request->input('how_pay') : "0";
		$start_time = $request->input('start_time') ?: date("Y-m-d", strtotime("-100 day", time()));
		$end_time =$request->input('end_time') ?: date("Y-m-d");

		// 타임존 보정
		$start_time_utc_0 = date("Y-m-d", strtotime($start_time.'-9 hours'));
		$end_time_utc_0 = date("Y-m-d", strtotime($end_time.'-9 hours'));

		if($refund_type == 1) {
			// 환불신청
			$orders = Order::whereDate('created_at','>=',$start_time_utc_0)
				->whereDate('created_at','<=',$end_time_utc_0)
				->where('order_cancel', 2)
				->where('how_pay',$how_pay)
				->whereIn('order_state', [1, 2, 3])
				->with('user')
				->with('delivery')
				->with('deposit_pay');
		} else if($refund_type == 2){
			// 환불완료
			$orders = Order::whereDate('created_at','>=',$start_time_utc_0)
				->whereDate('created_at','<=',$end_time_utc_0)
				->where('order_cancel', 2)
				->where('how_pay',$how_pay)
				->whereIn('order_state', [4])
				->with('user')
				->with('delivery')
				->with('deposit_pay');
		}
			
		$orders_page = $orders->paginate(20);
		$orders_page->withPath('/admin/order_list');
		
		$views = view('admin.refund_list');
		
		$views->start_time = $start_time;
		$views->end_time = $end_time;
		$views->orders = $orders->orderBy('created_at','desc')->get();
		$views->orders_page = $orders_page;		
		$views->refund_type = $refund_type;
		$views->how_pay = $how_pay;
		
		$views->title = "환불리스트";
		$views->datetime = $datetime;
		
		return $views;
	}

	public function video_list(){
		$videos = Video::query();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$videos_page = $videos->paginate(20);
		$videos_page->withPath('video_list');
		
		$views = view('admin.video_list');
		
		$views->videos = $videos->get();
		$views->videos_page = $videos_page;
		
		$views->title = "홍보영상리스트";
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function video_create(){
		$views = view('admin.video_create');
		return $views;
	}

	public function video_store(Request $request){
		$input = $request->all();
		
        Video::create([
        	'title' => $request->input('title'),
        	'video_link' => $request->input('video_link'),
        ]);
		
		return Redirect::route('admin.video_list');
	}
	
	public function video_delete($id){
		Video::where('id',$id)->delete();
		
        return Redirect::route('admin.video_list');
    }

	public function banner_list(){
		$banners = Banner::query();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$banners_page = $banners->paginate(20);
		$banners_page->withPath('/admin/banner_list');
		
		$views = view('admin.banner_list');
		
		$views->banners = $banners->get();
		$views->banners_page = $banners_page;
		
		$views->title = "배너리스트";
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function faq_list(){
		$faqs = Faq::orderBy('created_at','desc')->paginate(10);
		
		$faqs->withPath('/admin/faq');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views = view('admin.faq_list');
		
		$views->faqs = $faqs;
		
		$views->datetime = $datetime;
		
		return $views;
		
	}
	
	public function faq_show($id){
		$faq = Faq::where('id',$id)->first();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		
		$views = view('admin.faq_show');
		
		$views->faq = $faq;
		
		$views->datetime = $datetime;
		
		return $views;
		
	}
	
	public function faq_create(){
		$views = view('admin.faq_create');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function faq_insert(Request $request){
		$question = $request->input('question');
		$answer = $request->input('answer');
		
		Faq::create([
			'question' => $question,
			'answer' => $answer,
		]);
		
		return redirect()->route('admin.faq_list');
	}
	
	public function faq_edit($id){
		$faq = Faq::where('id',$id)->first();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		
		$views = view('admin.faq_edit');
		
		$views->faq = $faq;
		
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function faq_update(Request $request, $id){
		$question = $request->input('question');
		$answer = $request->input('answer');
		
		Faq::where('id',$id)->update([
			'question' => $question,
			'answer' => $answer,
		]);
		
		return redirect()->back();
	}
	
	public function faq_delete($id){
		Faq::where('id',$id)->delete();
		
		return redirect()->route('admin.faq_list');
	}
	
	public function notice_list(){
		$notices = Notice::orderBy('id','desc')->paginate(10);
		
		$notices->withPath('/admin/notice');
		
		$views = view('admin.notice_list');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views->notices = $notices;
		
		$views->datetime = $datetime;
        
		return $views;
		
	}
	
	public function notice_show($id){
		$notice = Notice::where('id',$id)->first();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		
		$views = view('admin.notice_show');
		
		$views->notice = $notice;
		
		$views->datetime = $datetime;
		
		return $views;
		
	}
	
	public function notice_create(){
		$views = view('admin.notice_create');
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function notice_insert(Request $request){
		$input = $request->all();
		$path = NULL;
		

		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$file_name = $request->file1->getClientOriginalName();
				$path = $request->file1->storeAs('public/notice', $file_name);
				$path = str_replace("public/notice/","",$path);
			}
		}
		
        Notice::create([
        	'title' => $request->input('title'),
        	'body' => $request->input('body'),
        	'file1' => $path,
        	'hit' => 0,
		]);
		
		return redirect()->route('admin.notice_list');
	}
	
	public function notice_edit($id){
		$notice = Notice::where('id',$id)->first();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		
		$views = view('admin.notice_edit');
		
		$views->notice = $notice;
		
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function notice_update(Request $request, $id){
		$input = $request->all();
		$path = NULL;
		
		
		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$file_name = $request->file1->getClientOriginalName();
				$path = $request->file1->storeAs('public/notice', $file_name);
				$path = str_replace("public/notice/","",$path);
			}
		}
		
		
		$asdasd=Notice::where('id',$id)->update([
			'title' => $request->input('title'),
			'body' => $request->input('body'),
			'file1' => $path,
		]);
		
		return redirect()->back();
	}
	
	public function notice_delete($id){
		 $del_notice=Notice::where('id',$id)->first();//delete();
        
        //dd($del_notice->file1);
        
        $img_path = '../storage/app/'.$del_notice->file1;
        
		if(File::exists($img_path)) {
		    File::delete($img_path);
		}else{
			
		}
		
		Notice::where('id',$id)->delete();
		
		return redirect()->route('admin.notice_list');
	}
	
	public function privacy_edit(){
		$privacy = Contents::find(1);
		
		$views = view('admin.privacy_edit');
		
		$views->privacy = $privacy;
		
		return $views;
	}
	
	public function privacy_update(Request $request){
		Contents::find(1)->update([
			"pc_contents" => $request->input('pc_contents'),
			"mobile_contents" => $request->input('mobile_contents'),
		]);
		
		return redirect()->back();
	}
	
	public function policy_edit(){
		$policy = Contents::where('id',2)->first();
		
		$views = view('admin.policy_edit');
		
		$views->policy = $policy;
		
		return $views;
	}
	
	public function policy_update(Request $request){
		Contents::where('id',2)->update([
			"pc_contents" => $request->input('pc_contents'),
			"mobile_contents" => $request->input('mobile_contents'),
		]);
		
		return redirect()->back();
	}
	
	public function howtouse_edit(){
		$howtouse = Howtouse::first();
		
		$views = view('admin.howtouse_edit');
		
		$views->howtouse = $howtouse;
		
		return $views;
	}
	
	public function howtouse_update(Request $request){
		$input = $request->all();
		$images = array();
		
		$path = array();
		$path2 = array();
		
		$howtouse = Howtouse::first();
		
		if($files = $request->file('pc_imgs')){
			for($i=0; $i<5; $i++){
				if(isset($files[$i])){
					if ($files[$i]->isValid()) {
						$path[$i] = $files[$i]->store('public/image/howtouse/');
						$path[$i] = str_replace("public/image/howtouse/","",$path[$i]);
						
						$img_path = '../storage/app/public/image/howtouse/'.$howtouse['pc_imgs'.($i+1)];
        
						if(File::exists($img_path)) {
						    File::delete($img_path);
						}

					}else{
						$path[$i] = $howtouse['pc_img'.($i+1)];
					}	
				}else{
					$path[$i] = $howtouse['pc_img'.($i+1)];
				}	
			}
		}else{
			for($i=0; $i<5; $i++){
				$path[$i] = $howtouse['pc_img'.($i+1)];
			}
		}
		
		if($files2 = $request->file('mb_imgs')){
			for($i=0; $i<5; $i++){
				if(isset($files2[$i])){
					if ($files2[$i]->isValid()) {
						$path2[$i] = $files2[$i]->store('public/image/howtouse/');
						$path2[$i] = str_replace("public/image/howtouse/","",$path2[$i]);
						
						$img_path = '../storage/app/public/image/howtouse/'.$howtouse['mb_imgs'.($i+1)];
        
						if(File::exists($img_path)) {
						    File::delete($img_path);
						}

					}else{
						$path2[$i] = $howtouse['mb_img'.($i+1)];
					}	
				}else{
					$path2[$i] = $howtouse['mb_img'.($i+1)];
				}	
			}
		}else{
			for($i=0; $i<5; $i++){
				$path2[$i] = $howtouse['mb_img'.($i+1)];
			}
		}
		
		Howtouse::where('title',$howtouse->title)->update([
			"pc_img1" => $path[0],
			"pc_img2" => $path[1],
			"pc_img3" => $path[2],
			"mb_img1" => $path2[0],
			"mb_img2" => $path2[1],
			"mb_img3" => $path2[2],
		]);
		
		return redirect()->back();
	}

	public function howtouse_delete(Request $request, $img_name){
		if(!empty($img_name)) {
			$howtouse = Howtouse::first();
			
			if(isset($howtouse->{$img_name})) {
				$img_path = '../storage/app/public/image/howtouse/'.$howtouse->{$img_name};
				
				if(File::exists($img_path)) {
					File::delete($img_path);
				}

				Howtouse::where('title',$howtouse->title)->update([
					$img_name => null,
				]);
			}
		}

		return redirect()->back();
	}

	public function slide_list(){
		$slides = Slide::query();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		
		$slides_page = $slides->paginate(20);
		$slides_page->withPath('/admin/slide_list');
		
		$views = view('admin.slide_list');
		
		$views->slides = $slides->get();
		$views->slides_page = $slides_page;
		
		$views->title = "슬라이드 리스트";
		$views->datetime = $datetime;
		
		return $views;
	}

	public function slide_create() {
		$views = view('admin.slide_create');
		return $views;
	}

	public function slide_store(Request $request) {
		$input = $request->all();

		$store_img_path = 'public/image/slide';

		$pc_file1 = NULL;
		if($file = $request->file('file')){
            if(isset($file)){
                if ($file->isValid()) {            		
					$pc_path1 = $file->store($store_img_path);
					$pc_file1 = str_replace($store_img_path."/","",$pc_path1);
                }
            }   	
		}
		
        Slide::create([
			'slide_file' => $pc_file1,
			'slide_info' => $request->input('info')
        ]);
		
		return Redirect::route('admin.slide_list');
	}

	public function slide_delete($id){
		$slide = Slide::where('id', $id)->first();

		$store_img_path = 'public/image/slide';
		$img_path = '../storage/app/'.$store_img_path.'/'.$slide->slide_file;
		if(File::exists($img_path)) {
			File::delete($img_path);
		} 
		
		Slide::where('id', $id)->delete();

		return redirect()->back();
	}

	public function fee_list(){
		$fee = Fee::first();
		
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");

		$views = view('admin.fee_list');
		
		$views->fee = $fee;
		$views->datetime = $datetime;

		return $views;
	}

	public function io_list(Request $request){
		$request_type = 0; 
		if($request->input('request_type') != NULL) {
			$request_type = $request->input('request_type');
		}

		$request_status = 0; 
		if($request->input('request_status') != NULL) {
			$request_status = $request->input('request_status');
		}

		$keyword_srch = 0;
		if($request->input('keyword_srch') != NULL) {
			$keyword_srch = $request->input('keyword_srch');
		}

		$keyword = '';
		if($request->input('keyword') != NULL) {
			$keyword = $request->input('keyword');
		}

		$ios = DB::table('eth_io_request')
			->whereIn('in_progress', [0, 1, 2])
			->where('coin_kind', 'tlg')
			->where('request_type', DB::raw([
				0 => 'request_type',
				1 => "'deposit'",
				2 => "'withdraw'"
			][$request_type]));
		
		if($request_status != 1) {
			$ios = $ios->where('request_status', DB::raw([
				0 => 'request_status',
				2 => "'fee'",
				3 => "'batting'",
				4 => "'reward'",
				5 => "'buy'",
				6 => "'sell'",
				7 => "'refund'"
			][$request_status]));
		} else {
			$ios = $ios->whereNotIn('request_status', [
				'buy', 'sell', 'refund', 'batting', 'reward', 'fee'
			]);
		}	
		
		if($keyword_srch != 0) {
			$ios = $ios->where([
				1 => 'request_user_id',
				2 => 'request_address',
				3 => 'confirm_tx',
				4 => 'updated'
			][$keyword_srch], 'like', '%'.$keyword.'%');
		} else {
			$keyword = '';
		}

		$ios = $ios->orderBy('id', 'desc');

		$ios_page = $ios->paginate(20)->appends(request()->query());
		$ios_page->withPath('/admin/io_list');

		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");

		$views = view('admin.io_list');
		$views->ios = $ios->get();
		$views->ios_page = $ios_page;	
		$views->datetime = $datetime;
		$views->title = "입출금내역";
		$views->request_type = $request_type;
		$views->request_status = $request_status;
		$views->keyword_srch = $keyword_srch;
		$views->keyword = $keyword;

		return $views;
	}

	public function result_calculate(Request $request, $status){
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");

		if(!isset($request->start_time) || !isset($request->end_time)){
			$start=mktime(0,0,0,date('m'),1,date('Y')); // 이번달의 첫날
			$start_time=date('Y-m-d',$start);
			$end=mktime(0,0,0,date('m')+1,1,date('Y'))-1; //이번달의 마지막날
			$end_time=date('Y-m-d',$end);
		}else{
			$start_time = $request->start_time;
			$end_time = $request->end_time;
		}

		if($status == 0){
			$result_cals = Result_calculate::whereDate('created_at','<=',$end_time)->whereDate('created_at','>=',$start_time)->orderBy('created_at','desc')->paginate(30);
		}else if($status == 1){
			$result_cals = Result_calculate::whereDate('created_at','<=',$end_time)->whereDate('created_at','>=',$start_time)->orderBy('created_at','desc')->where('complete',0)->paginate(30);
		}else if($status == 2){
			$result_cals = Result_calculate::whereDate('created_at','<=',$end_time)->whereDate('created_at','>=',$start_time)->orderBy('created_at','desc')->where('complete',1)->paginate(30);
		}

		$result_cals->withPath('/admin/result_calculate/'.$status);

		$date_in_price = Result_calculate::whereDate('created_at','<=',$end_time)->whereDate('created_at','>=',$start_time)->sum('fee');

		$total_magin = Result_calculate::sum('fee');

		$views = view('admin.result_calculate');

		$views->result_cals = $result_cals;
		$views->date_in_price = $date_in_price;
		$views->total_magin = $total_magin;
		$views->datetime = $datetime;
		$views->start_time = $start_time;
		$views->end_time = $end_time;
		$views->status = $status;

		return $views;
	}

	public function result_all_confirm(Request $request){
		$idnum = explode("|", $request->is_confirm_id);
		
		for($i=0; $i<count($idnum); $i++){
			Result_calculate::where('id',$idnum[$i])->update([
				"complete" => 1,
			]);
		}
		
		return redirect()->route('admin.result_calculate', $request->status);
	}

	public function popup_list(){
		date_default_timezone_set("Asia/Seoul");
		$views = view('admin.popup.list');
		
		$popups = DB::table('tlca_popup')->paginate(15);
		
		$popups->withPath('/admin/popup/list');
		
		$datetime = date("H시 i분 s초");
		$views->popups = $popups;
		$views->datetime = $datetime;
		
		return $views;
	}
	
	public function popup_create(){
		$views = view('admin.popup.create');
		return $views;
	}
	
	public function popup_insert(Request $request){	
		$store_img_path = 'public/image/popup';
		$pc_path = NULL;
		$mb_path = NULL;
		

		if($file = $request->file('pc_img')){    	
			if ($file->isValid()) {            		
				$pc_path = $file->store($store_img_path.'/');                  
				$pc_path = str_replace($store_img_path.'/',"",$pc_path);		
			}
		}
		
		if($file = $request->file('mb_img')){    	
			if ($file->isValid()) {            		
				$mb_path = $file->store($store_img_path.'/');                  
				$mb_path = str_replace($store_img_path.'/',"",$mb_path);
			}
		}
		
		
		DB::table('tlca_popup')->insert([
			"writer_id" => Auth::guard('admin')->user()->id,
			"writer_name" => Auth::guard('admin')->user()->fullname,
			"title" => $request->title,
			"body" => $request->body,
			"pc_img" => $pc_path,
			"mb_img" => $mb_path,
			"link" => $request->link,
			"start_time" => $request->start_time,
			"end_time" => $request->end_time,
			"created_at" => now(),
			"updated_at" => now(),
		]);
		
		return redirect()->route('admin.popup_list');
	}
	
	public function popup_edit($id){
        $views = view('admin.popup.edit');
        
        $popup = DB::table('tlca_popup')->where('id',$id)->first();

        $views->popup = $popup;
		return $views;
	}

	public function popup_update(Request $request, $id){		
		$store_img_path = 'public/image/popup';
        
        $popup = DB::table('tlca_popup')->where('id',$id)->first();

        $pc_path = $popup->pc_img;
        $mb_path = $popup->mb_img;

		
		if($file = $request->file('pc_img')){
            if(isset($file)){
                if ($file->isValid()) {            		
                    $pc_path = $file->store($store_img_path.'/');                  
                    $pc_path = str_replace($store_img_path.'/',"",$pc_path);	
                    
                    $img_path = '../storage/app/public/image/popup/'.$popup->pc_img;
                    
                    if(File::exists($img_path)) {
                        File::delete($img_path);
                    }  
                }
            }   	
		}
		
		if($file = $request->file('mb_img')){
            if(isset($file)){
                if ($file->isValid()) {            		
                    $mb_path = $file->store($store_img_path.'/');                  
                    $mb_path = str_replace($store_img_path.'/',"",$mb_path);	
                    
                    $img_path = '../storage/app/public/image/popup/'.$popup->mb_img;
                    
                    if(File::exists($img_path)) {
                        File::delete($img_path);
                    }  
                }
            }   	
		}
		
		DB::table('tlca_popup')->where('id',$id)->update([
			"writer_id" => Auth::guard('admin')->user()->id,
			"writer_name" => Auth::guard('admin')->user()->fullname,
			"title" => $request->title,
			"body" => $request->body,
			"pc_img" => $pc_path,
			"mb_img" => $mb_path,
			"link" => $request->link,
			"start_time" => $request->start_time,
			"end_time" => $request->end_time,
			"updated_at" => now(),
		]);
		
		return redirect()->route('admin.popup_list');
	}
	
	public function popup_delete($id){
        $popup = DB::table('tlca_popup')->where('id',$id)->first();

        $img_path = '../storage/app/public/image/popup/'.$popup->pc_img;

        if(File::exists($img_path)) {
            File::delete($img_path);
        }  

        $img_path = '../storage/app/public/image/popup/'.$popup->mb_img;

        if(File::exists($img_path)) {
            File::delete($img_path);
        }

        DB::table('tlca_popup')->where('id',$id)->delete();
        


		return redirect()->route('admin.popup_list');
	}

	public function batting_set(){
		$batting_set = DB::table('tlca_batting_set')->first();

		$views = view('admin.batting_set');

		$views->batting_set = $batting_set;

		return $views;

	}

	public function batting_set_update(Request $request){
		DB::table('tlca_batting_set')->update([
			"batting_term" => $request->batting_term,
			"reward_seller" => $request->reward_seller / 100,
			"reward_management" => $request->reward_management / 100,
			"reward_review" => $request->reward_review / 100,
			"reward_people" => $request->reward_people / 100,
			"reward_welfare" => $request->reward_welfare / 100,
		]);

		return redirect()->back();
	}

	public function admin_user_list(){
		$admin_users = DB::table('admin')->get();

		$views = view('admin.admin_user_list');

		$views->admin_users = $admin_users;
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		$views->datetime = $datetime;
		return $views;
	}

	public function admin_user_create(){

		if(Auth::guard('admin')->user()->level > 2){
			return redirect()->route('admin.admin_user_list');
		}

		$views = view('admin.admin_user_create');
		date_default_timezone_set("Asia/Seoul");
		$datetime = date("H시 i분 s초");
		$views->datetime = $datetime;
		return $views;
	}

	public function admin_user_store(Request $request){
		date_default_timezone_set("Asia/Seoul");

		if(Auth::guard('admin')->user()->level > 2){
			return redirect()->route('admin.admin_user_list');
		}

		DB::table('admin')->insert([
			"email" => $request->email,
			"password" => Hash::make($request->password),
			"fullname" => $request->fullname,
			"mobile_number" => $request->mobile_number,
			"level" => $request->level,
			"time_signin" => date("Y-m-d H:i:s"),
		]);

		return redirect()->route('admin.admin_user_list');
		
	}

	public function admin_user_password_edit($id){

		if(Auth::guard('admin')->user()->id != $id){
			return redirect()->route('admin.admin_user_list');
		}

		$views = view('admin.admin_user_password_edit');

		$datetime = date("H시 i분 s초");
		$views->datetime = $datetime;
		$views->id = $id;
		return $views;
	}

	public function admin_user_password_change(Request $request, $id){
		if(Auth::guard('admin')->user()->id != $id){
			return redirect()->route('admin.admin_user_list');
		}

		DB::table('admin')->where('id',$id)->update([
			"password" => Hash::make($request->password),
		]);

		return redirect()->back()->with('jsAlert', '비밀번호를 성공적으로 변경하였습니다.');
	}

	public function admin_user_delete(Request $request){
		$id = $request->id;
		if(Auth::guard('admin')->user()->level > 2){
			return redirect()->route('admin.admin_user_list');
		}

		DB::table('admin')->where('id',$id)->delete();

		return redirect()->route('admin.admin_user_list')->with('jsAlert', '성공적으로 삭제하였습니다.');
	}

}
