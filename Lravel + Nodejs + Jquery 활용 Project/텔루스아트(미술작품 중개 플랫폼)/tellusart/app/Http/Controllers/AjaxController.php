<?php

namespace TLCfund\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use TLCfund\User;
use TLCfund\Review;
use TLCfund\Expert_review;
use TLCfund\Category;
use TLCfund\Batting;
use TLCfund\Cart;
use TLCfund\Order;
use TLCfund\Delivery;
use TLCfund\Product;
use TLCfund\Video;
use TLCfund\Banner;
use TLCfund\Review_like;
use TLCfund\Address;
use TLCfund\Address_charge;
use TLCfund\Slide;
use TLCfund\Fee;
use TLCfund\Admin;
use Facades\App\Classes\EthApi;
use Facades\App\Classes\SweetTracker;
use File;
use Auth;
use Log;
use DB;

class AjaxController extends Controller
{
    function review_store(Request $request){
    	$user = Auth::user();

		$input = $request->all();


		Review::create([
			'art_id' => $request->art_id,
			'writer_id' =>$user->id,
			'writer_name' => $user->name,
			'profile_img' => $user->profile_img,
			'unickname' => $user->nickname,
			'review_body' => $request->review_body,
		]);

		$review = Review::where('art_id',$request->art_id)->where('writer_id', $user->id)->orderBy('id','desc')->first();

		$response = array(
			'review_id' => $review->id,
			'profile_img' => $user->profile_img,
			'nickname' =>  $user->nickname,
			'date' =>  date("Y.m.d"),
			'review_body' => $request->review_body,
		);


		return response()->json($response);
    }

	function review_store2(Request $request){
    	$user = Auth::user();

		$input = $request->all();

		if($user->level == 2 || $user->level == 3){
			Expert_review::create([
	        	'uid' => $user->id,
	        	'art_id' =>$request->art_id,
	        	'profile_img' => $user->profile_img,
	        	'uname' => $user->name,
	        	//'review_title' => $request->review_title,
	        	'review_body' => $request->review_body,
	        	'rating' => $request->rating,
	        ]);

			$expert_review = Expert_review::where('art_id',$request->art_id)->where('uid', $user->id)->orderBy('id','desc')->first();

			$response = array(
				'review_id' => $expert_review->id,
				'profile_img' => $user->profile_img,
				'name' =>  $user->name,
				'date' =>  date("Y.m.d"),
				'review_title' => $request->review_title,
				'review_body' => $request->review_body,
				'rating' => $request->rating,
			);
		}


		return response()->json($response);
    }

	function review_test(){
		return view('review_test');
	}

	function mypage_commnet_show(Request $request){

		$review_id = $request->review_id;

		$review = Review::where('id', $review_id)->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->first();

		$response = array(
			'product_image1' => $review->product->image1,
			'ca_name' =>  $review->product->category->ca_name,
			'title' =>  $review->product->title,
			'artist_name' =>  $review->product->artist_name,
			'rating' =>  sprintf("%1.1f" ,$review->rating),
			'review_body' =>  $review->review_body,
		);

		return response()->json($response);

	}

	function mypage_comment_edit(Request $request){
		$review_id = $request->review_id;

		$review = Review::where('id', $review_id)->update([
			'review_body' =>  $request->review_body,
		]);

		$response = array(
			'review_body' =>  $request->review_body,
		);

		return response()->json($response);
	}

	function mypage_comment_delete(Request $request){
		$review_id = $request->review_id;

		Review_like::where('review_id',$review_id)->delete();

		Review::where('id', $review_id)->delete();


		return response()->json(1);
	}

	function mypage_expert_commnet_show(Request $request){

		$review_id = $request->review_id;

		$review = Expert_review::where('id', $review_id)->with(array('product' =>function($query){ $query->with('category')->with('user'); }))->first();

		$response = array(
			'product_image1' => $review->product->image1,
			'ca_name' =>  $review->product->category->ca_name,
			'title' =>  $review->product->title,
			'artist_name' =>  $review->product->artist_name,
			'rating' =>  sprintf("%1.1f" ,$review->rating),
			'review_body' =>  $review->review_body,
		);

		return response()->json($response);

	}

	function mypage_expert_comment_edit(Request $request){
		$review_id = $request->review_id;

		$review = Expert_review::where('id', $review_id)->update([
			'review_body' =>  $request->review_body,
			'rating' => $request->review_rating,
			'recomend' => 0,
			'unrecomend' => 0
		]);

		$response = array(
			'review_body' =>  $request->review_body,
		);

		return response()->json($response);
	}

	function mypage_expert_comment_delete(Request $request){
		$review_id = $request->review_id;

		Expert_review::where('id', $review_id)->delete();


		return response()->json(1);
	}

	function user_level(Request $request){
		$id = $request->id;

		User::where('id',$id)->update([
			'level' => $request->level,
		]);



		return response()->json(1);
	}



	function category_image_change(Request $request){

		$supplier_name = $request->supplier_name;

		$id = $request->id;

		if($request->hasFile('file')){
			if ($request->file('file')->isValid()) {
				$path = $request->file->store('public/image');
			}
		}

		$path = str_replace("public/image/","",$path);

		$category = Category::where('id',$id)->first();

		Storage::delete(str_replace("/","",$category->ca_icon));

		 $img_path = '../storage/app/public/image/'.$category->ca_icon;

		if(File::exists($img_path)) {
		    File::delete($img_path);
		}

		Category::where('id',$id)->update([
			'ca_icon' => $path,
		]);

		$response = array('path' => '/storage/image/'.$path);


		return response()->json($response);
	}

	function category_name_change(Request $request){
		$id = $request->id;

		Category::where('id',$id)->update([
			'ca_name' => $request->name,
		]);

		return response()->json(1);
	}

	function category_sm_name_change(Request $request){
		$id = $request->id;

		Category::where('id',$id)->update([
			'ca_sm_name' => $request->sm_name,
		]);

		return response()->json(1);
	}

	function category_discript_change(Request $request){
		$id = $request->id;

		Category::where('id',$id)->update([
			'ca_discript' => $request->ca_discript,
		]);

		return response()->json(1);
	}

	function category_status_change(Request $request){
		$id = $request->id;

		Category::where('id',$id)->update([
			'ca_use' => $request->ca_use,
		]);

		Product::where('ca_id')->update([
			'ca_use' => $request->ca_use,
		]);

		return response()->json(1);
	}

	function batting_do(Request $request){
		$email = Auth::user()->email;
		$user = User::where('email',$email)->first();

		$exist = Batting::where('art_id',$request->art_id)->where('uid',$user->id)->exists();

		if(!$exist){
			$address_info = Address::select('available_balance_tlc as available')->where('user_email', $user->email)->first();
			$balance = floatval($address_info->available);
			Log::info($request->batting_price);
			Log::info($balance);

			if($request->batting_price <= $balance){
				$product = Product::where('id',$request->art_id)->first();

				EthApi::addInfoRequest($user->email, 'withdraw', $request->batting_price, 'batting');
				Address::where('user_email', $user->email)->update([
					'available_balance_tlc' => DB::raw("available_balance_tlc - $request->batting_price")
				]);

				Batting::create([
					'art_id' => $request->art_id,
					'uid' => $user->id,
					'user_id' => $user->email,
					'unickname' => $user->nickname,
					'batting_price' => $request->batting_price,
					'batting_status' => 1,
					'start_time' => $product->start_time,
					'end_time' => $product->end_time,
				]);

				Product::where('id', $request->art_id)->increment('get_like', 1);

				$response = array('batting_price' => $request->batting_price);
			}else{
				$response = 'balance';
			}
			return response()->json($response);
		}
	}

	function batting_edit(Request $request){
		$email = Auth::user()->email;
        $user = User::where('email',$email)->first();

		$batting = Batting::where('art_id',$request->art_id)->where('uid',$user->id)->first();

		//여기서 베팅한 유저의 보유 코인량 수정해야함!
		if($batting->batting_price < $request->batting_price){
			// 수정한 베팅 코인량이 기존에 베팅한 코인량 보다 많으면 추가로 감액
			$address_info = Address::select('available_balance_tlc as available')->where('user_email', $user->email)->first();
			$balance = floatval($address_info->available);

			$edit_price = $request->batting_price - $batting->batting_price;
			if($balance >= $edit_price){
				EthApi::addInfoRequest($user->email, 'withdraw', $edit_price, 'batting');
				Address::where('user_email', $user->email)->update([
					'available_balance_tlc' => DB::raw("available_balance_tlc - $edit_price")
				]);


				Batting::where('art_id',$request->art_id)->where('uid',$user->id)->update([
					'batting_price' => $request->batting_price,
				]);
				$response = array('batting_price' => $request->batting_price);
			}else{
				$response = 'balance';
			}
		}else if($batting->batting_price > $request->batting_price){
			/*
			$edit_price = $batting->batting_price - $request->batting_price;
			EthApi::addInfoRequest($user->email, 'deposit', $edit_price, 'batting');
			Address::where('user_email', $user->email)->update([
				'available_balance_tlc' => DB::raw("available_balance_tlc + $edit_price")
			]);

			Batting::where('art_id',$request->art_id)->where('uid',$user->id)->update([
				'batting_price' => $request->batting_price,
			]);
			$response = array('batting_price' => $request->batting_price);
			*/
			$response = 'nominus';
		} else {
			$response = 'nochange';
		}

		return response()->json($response);
	}

	function batting_load(Request $request){
		$email = Auth::user()->email;;
        $user = User::where('email',$email)->first();



		$batting = Batting::where('art_id',$request->art_id)->where('uid',$user->id)->first();

		$response = array('batting_price' => $batting->batting_price);


		return response()->json($response);
	}

	function batting_cancel(Request $request){
		$email = Auth::user()->email;;
        $user = User::where('email',$email)->first();

		$batting_start_date = Batting::where('art_id',$request->art_id)->where('uid',$user->id)->value('created_at');

		$batting_start_date = strtotime($batting_start_date);
		$today = strtotime(date('Y-m-d H:i:s'));

		$past_day = $today - $batting_start_date;

		$years = floor($past_day/31536000);
		$days = floor($past_day/86400);
		$date = ($days - (365*$years))-1;
		$time = $past_day - ($days*86400);
		$hours = floor($time/3600);
		$time = $time - ($hours*3600);
		$min = floor($time/60);
		$sec = $time - ($min*60);

		$hour = ($today - $batting_start_date)/3600;


		if($hour>=24){
			$response = array('dateover' => 1);
		}else{
			$response = array('dateover' => 0, 'batting_price' => 0);
			Batting::where('art_id',$request->art_id)->where('uid',$user->id)->delete();
			Product::where('id', $request->art_id)->decrement('get_like', 1);
			//여기서 베팅한 유저의 보유 코인량 더해줘야함!


		}

		return response()->json($response);
	}

	function recomend(Request $request){

		$email = Auth::user()->email;;
        $user = User::where('email',$email)->first();

		$review_id = $request->review_id;

		if(Review_like::where('review_id', $review_id)->where('uid', $user->id)->count() > 0){
			$review_kind = Review_like::where('review_id', $review_id)->where('uid', $user->id)->first();

			if($review_kind->recomend == 1){
				Review::where('id', $review_id)->decrement('recomend', 1);
				Review_like::where('review_id', $review_id)->where('uid', $user->id)->delete();

				$response = array('msg' => '해당 코멘트의 추천을 취소하였습니다.', 'change' => 1);
			}else if($review_kind->unrecomend == 1){
				Review::where('id', $review_id)->decrement('unrecomend', 1);
				Review_like::where('review_id', $review_id)->where('uid', $user->id)->update([
					'recomend' => 1,
					'unrecomend' => 0,
				]);
				Review::where('id', $review_id)->increment('recomend', 1);

				$response = array('msg' => '해당 코멘트를 추천 하였습니다.', 'change' => 2);
			}



		}else{
			Review_like::create([
				'uid' => $user->id,
				'review_id' => $review_id,
				'recomend' => 1,
			]);
			Review::where('id', $review_id)->increment('recomend', 1);

			$response = array('msg' => '해당 코멘트를 추천 하였습니다.', 'change' => 0);
		}

		return response()->json($response);
	}

	function unrecomend(Request $request){

		$email = Auth::user()->email;
        $user = User::where('email',$email)->first();

		$review_id = $request->review_id;

		if(Review_like::where('review_id', $review_id)->where('uid', $user->id)->count() > 0){
			$review_kind = Review_like::where('review_id', $review_id)->where('uid', $user->id)->first();

			if($review_kind->unrecomend == 1){
				Review::where('id', $review_id)->decrement('unrecomend', 1);
				Review_like::where('review_id', $review_id)->where('uid', $user->id)->delete();

				$response = array('msg' => '해당 코멘트의 비추천을 취소하였습니다.', 'change' => 1);
			}else if($review_kind->recomend == 1){
				Review::where('id', $review_id)->decrement('recomend', 1);
				Review_like::where('review_id', $review_id)->where('uid', $user->id)->update([
					'recomend' => 0,
					'unrecomend' => 1,
				]);
				Review::where('id', $review_id)->increment('unrecomend', 1);

				$response = array('msg' => '해당 코멘트를 비추천 하였습니다.', 'change' => 2);
			}
		}else{
			Review_like::create([
				'uid' => $user->id,
				'review_id' => $review_id,
				'unrecomend' => 1,
			]);
			Review::where('id', $review_id)->increment('unrecomend', 1);

			$response = array('msg' => '해당 코멘트를 비추천 하였습니다.', 'change' => 0);
		}

		return response()->json($response);
	}

	function cart_insert(Request $request){
		if(Auth::check()){
			$product = Product::where('id',$request->art_id)->first();
			if($product->seller_id != Auth::id()){
				if(Cart::where('uid',Auth::id())->where('product_id', $request->art_id)->count() > 0){
					$response = array('cart_yn' => 0);
				}else{
					Cart::create([
						'uid' => Auth::id(),
						'product_id' => $request->art_id
					]);
					$response = array('cart_yn' => 1);
				}
			}else{
				$response = array(
					'cart_yn' => 0,
					'msg' => '자신의 작품은 장바구니에 담을 수 없습니다.',
				);
			}
		}

		return response()->json($response);
	}

	function cart_delete(Request $request){
		if(Auth::check()){
			Cart::where('uid',Auth::id())->where('product_id',$request->art_id)->delete();
		}
		return response()->json(1);
	}

	function refund_change(Request $request){
		$id = $request->id;

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

			return response()->json(1);
		}

		return response()->json(0);
	}

	function refund_cancel(Request $request){
		$id = $request->id;

		Order::where('id',$id)->update([
			'order_cancel' => 0,
			'pay_cancel_infor' => NULL,
		]);

		return response()->json(1);
	}

	function video_title_change(Request $request){
		$id = $request->id;

		Video::where('id',$id)->update([
			'title' => $request->input('title'),
		]);

		return response()->json(1);
	}

	function video_link_change(Request $request){
		$id = $request->id;
		$link = $request->input('video_link');

		$link = str_replace("/embed", "", $link);

		$link = str_replace("https://www.youtube.com/watch?v=", "https://youtube.com/embed/", $link);

		$link = str_replace("https://youtu.be/", "https://youtube.com/embed/", $link);

		Video::where('id',$id)->update([
			'video_link' => $link,
		]);

		return response()->json(1);
	}

	function video_use_change(Request $request){
		$id = $request->id;

		Video::query()->update(['use_video' => 0]);
		Video::where('id',$id)->update([
			'use_video' => $request->input('use_video'),
		]);

		return response()->json(1);
	}

	function banner_file_change(Request $request){
		$id = $request->id;
		$banner = Banner::where('id',$id)->first();

		$store_img_path = 'public/image/banner';
		if($file = $request->file('file')){
            if(isset($file)){
                if ($file->isValid()) {
					$pc_path = $file->store($store_img_path);
					$pc_file = str_replace($store_img_path."/","",$pc_path);

                    $img_path = '../storage/app/'.$store_img_path.'/'.$banner->bn_file;

                    if(File::exists($img_path)) {
                        File::delete($img_path);
                    }
                }
            }
		}

		Banner::where('id',$banner->id)->update([
			'bn_file' => $pc_file
		]);

		return response()->json([
			'file' => $pc_file
		]);
	}

	function banner_file_delete(Request $request){
		$id = $request->id;
		$banner = Banner::where('id',$id)->first();

		$store_img_path = 'public/image/banner';
		$img_path = '../storage/app/'.$store_img_path.'/'.$banner->bn_file;
		if(File::exists($img_path)) {
			File::delete($img_path);
		}

		Banner::where('id',$banner->id)->update([
			'bn_file' => NULL,
		]);

		return response()->json(1);
	}

	function banner_time_change(Request $request){
		$id = $request->id;

		Banner::where('id',$id)->update($request->only(['bn_begin_time', 'bn_end_time']));

		return response()->json(1);
	}

	function email_certify(Request $request){
		$email = $request->email;

		$user_cnt = User::where('email', $email)->count();

		if($user_cnt > 0){
			$response = array('exist' => 1);
		}else{
			$response = array('exist' => 0);
		}

		return response()->json($response);
	}

	function nickname_certify(Request $request){
		$nickname = $request->nickname;

		$user_cnt = User::where('nickname', $nickname)->count();

		if($user_cnt > 0){
			$response = array('exist' => 1);
		}else{
			$response = array('exist' => 0);
		}

		return response()->json($response);
	}

	function mobile_certify(Request $request){
		$mobile = $request->mobile;

		$user_cnt = User::where('mobile_number', $mobile)->count();

		if($user_cnt > 0){
			$response = array('exist' => 1);
		}else{
			$response = array('exist' => 0);
		}

		return response()->json($response);
	}

	function more_sel_product(Request $request){
		$offset = $request->offset;
		$count = $request->count;
		$skeyword = $request->skeyword;
		$ca_id = $request->ca_id;
		if($ca_id == 0){
			$products = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
		}else{
			$products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
		}

		$offset = $offset + count($products);

		$response = array(
			"offset" => $offset,
			"products" => $products,
		);

		return response()->json($response);
	}

	function more_search_product(Request $request){
		$offset = $request->offset;
		$skeyword = $request->skeyword;
		$ca_id = $request->ca_id;


		$categorys = Category::where('ca_use',1);



		if($ca_id != -1){
			if(Auth::check()){
				$login_yn = 1;
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->where('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}else{
				$login_yn = 0;
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->where('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}
		}else{
			if(Auth::check()){
				$login_yn = 1;
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->where('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}else{
				$login_yn = 0;
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->where('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}
		}

		$offset = $offset + count($products);

		$response = array(
			"offset" => $offset,
			"login_yn" => $login_yn,
			"products" => $products,
		);



		return response()->json($response);
	}

	function more_bat_product(Request $request){
		$offset = $request->offset;
		$skeyword = $request->skeyword;
		$ca_id = $request->ca_id;
		$status = $request->status;



		if(Auth::check()){
			$login_yn = 1;
			if($ca_id == 0){
				$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1)->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('category')->with('user')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid', Auth::id());}))->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}else{
				$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1)->where('ca_id',$ca_id)->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('category')->with('user')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid', Auth::id());}))->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}
		}else{
			$login_yn = 0;
			if($ca_id == 0){
				$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1)->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('category')->with('user')->with('reviews')->with('battings')->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}else{
				$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1)->where('ca_id',$ca_id)->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('category')->with('user')->with('reviews')->with('battings')->orderBy('id', 'desc')->offset($offset)->limit(6)->get();
			}
		}

		$offset = $offset + count($products);

		$response = array(
			"offset" => $offset,
			"status" => $status,
			"login_yn" => $login_yn,
			"products" => $products,
		);

		return response()->json($response);
	}

	function view_cancel_reason(Request $request){
		$order = Order::where('id',$request->order_id)->first();
		$response = array('reason' => $order->pay_cancel_infor);

		return response()->json($response);
	}
	function view_delivery(Request $request){
		$delivery = Delivery::where('order_id',$request->order_id)->first();
		$response = array(
			'order_id' => $delivery->order_id,
			'delivery_company_code' => $delivery->delivery_company,
			'delivery_company' => SweetTracker::companyname($delivery->delivery_company),
			'send_post_num' => $delivery->send_post_num
		);

		return response()->json($response);
	}
	function address_valid(Request $request){
		$address = $request->address;

		return response()->json(EthApi::isAddress($address));
	}
	function address_maximum(Request $request){
		$address = Address::selectRaw('available_balance_tlc - 0.1 as available')->where('user_email', Auth::user()->email)->first();

		return response()->json(floatval($address->available));
	}
	function address_send(Request $request){
		$address = $request->address;
		$amount = str_replace(',', '', $request->amount);
		$total_amount = bcadd($amount, '0.1', 8); // 요청금액 + 수수료 소수점 8자리까지

		$email = Auth::user()->email;
		$address_info = Address::select('available_balance_tlc as available')->where('user_email', $email)->first();
		$balance = $address_info->available;
		$valid = EthApi::isAddress($address);

		if(floatval($amount) <= 0){
			$result = '요청금액은 0보다 작을 수 없습니다.';
		}else if(bccomp($balance, $total_amount, 8) == -1){ // $balance < $total_amount
			$result = '잔액이 부족합니다.';
		}else if(!$valid){
			$result = '올바르지 않은 주소값입니다.';
		}else{
			EthApi::addInfoRequest($email, 'withdraw', '0.1', 'fee');
			EthApi::newWithdrawRequest($email, $address, $amount);
			Address::where('user_email', $email)->decrement('available_balance_tlc', $total_amount);
			$result = '코인 '.$amount.'개를 출금하셨습니다.';
		}

		return response()->json($result);
	}
	function address_refresh(Request $request){
		$transactions_ajax = EthApi::listTransactions(Auth::user()->email, 10, 0);

		return response()->json($transactions_ajax);
	}
	function address_infinity(Request $request){
		$page = $request->page;

		$transactions_ajax = EthApi::listTransactions(Auth::user()->email, 10, $page);

		return response()->json($transactions_ajax);
	}
	function balance_refresh(Request $request){
		$address_info = Address::select('available_balance_tlc as available')->where('user_email', Auth::user()->email)->first();
		$balance = floatval($address_info->available);

		$my_balance = number_format(floor(floatval($address_info->available)*1000)/1000 , 2);

		return response()->json($my_balance);
	}
	function balance_refresh_user(Request $request){
		$user = User::where('id', $request->id)->first()->email;
		$address_info = Address::select('available_balance_tlc as available')->where('user_email', $user)->first();
		$balance = floatval($address_info->available);

		return response()->json($address_info->available);
	}
	function balance_add_user(Request $request){
		$uid = $request->id;
		$reason =  $request->reason;
		$amount =  $request->amount;

		if(empty($uid) or empty($reason) or empty($amount)) {
			return;
		}

		if ($amount >= 0 ) {
			$request_type = 'deposit';
			$request_status = 'deposit_confirmed';
		} else {
			$request_type = 'withdraw';
			$request_status = 'withdraw_confirmed';
		}

		$user = User::where('id', $uid)->first();
		EthApi::addInfoMessageRequest($user->email, $request_type, $amount, $request_status, $reason);

		DB::table('tlca_user_addresses')->where('user_id', $uid)->update([
			"available_balance_tlc" => DB::raw("available_balance_tlc + $amount")
		]);

		$response = array(
			"status" => 1,
		);

		return response()->json($response);
	}
	function charge_refresh(Request $request){
		$userid = Auth::user()->id ;
		$charge_lists = Address_charge::where('user_id', $userid)->orderBy('created_at','desc')->limit(10)->get();
		return response()->json($charge_lists);
	}
	function charge_infinity(Request $request){
		$page = $request->page;

		$userid = Auth::user()->id;

		$charge_lists = Address_charge::where('user_id', $userid)->orderBy('created_at','desc')->offset($page)->limit(10)->get();
		return response()->json($charge_lists);
	}

	function charge_order(Request $request){
		$cash = $request->cash;
		$exchange = 0.00087692356240241;

		$api_usdc = $cash * $exchange;

		$ch = curl_init();                    // Initiate cURL
	    $url = "https://api.cointouse.com/orderbook/TLC"; // Where you want to post data
	    curl_setopt($ch, CURLOPT_URL,$url);

	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $output = curl_exec ($ch); // Execute

	    curl_close ($ch); // Close cURL handle


	    $total_cash = 0;
	    $orderbook = json_decode($output,true);
		if(!isset($orderbook['data']['asks'])){
			$result = "nobid";
		}else if($orderbook['status'] == 'Success'){
			$orderbook_reverse = collect($orderbook['data']['asks'])->reverse()->toArray();
			foreach($orderbook_reverse as $order){
				$total_cash += ($order['quantity'] * $order['price']);
				if($total_cash > $api_usdc){

					$quantity = $order['quantity'];
					$price = $order['price'];
					break;
				}
				$quantity = $order['quantity'];
				$price = $order['price'];
			}
			if($total_cash < $api_usdc){
				$result = 0;
			}else{
				$result = $api_usdc / $price;
			}

		}else{
			$result = "error";
		}
		return response()->json($result);
	}

	function charge_buy(Request $request){
		$cash = $request->cash;
		$exchange = 0.00087692356240241;

		$api_usdc = $cash * $exchange;

		$ch = curl_init();                    // Initiate cURL
	    $url = "https://api.cointouse.com/orderbook/TLC"; // Where you want to post data
		curl_setopt($ch, CURLOPT_URL,$url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec ($ch); // Execute

		curl_close ($ch); // Close cURL handle


		$total_cash = 0;
		$orderbook = json_decode($output,true);
		if(!isset($orderbook['data']['asks'])){
			$result = "nobid";
		}else if($orderbook['status'] == 'Success'){
			$orderbook_reverse = collect($orderbook['data']['asks'])->reverse()->toArray();
			foreach($orderbook_reverse as $order){
				$total_cash += ($order['quantity'] * $order['price']);
				if($total_cash > $api_usdc){

					$quantity = $order['quantity'];
					$price = $order['price'];
					break;
				}
				$quantity = $order['quantity'];
				$price = $order['price'];
			}
			if($total_cash < $api_usdc){
				$result = 0;
			}else{
				$amount = $api_usdc / $price;
			}
		}else{
			$result = "error";
		}

		/*$postdata = array(
			'apiKey' => 'apikey 입력',
			'secretKey' => 'secretkey 입력',
			'symbol' => 'TLC',
			'price' => $price,
			'amount' => $amount
		);

		$ch = curl_init();                    // Initiate cURL
		$url = "https://api.cointouse.com/market_buy"; // Where you want to post data // market_buy 면 매수 market_sell 이면 매도
		curl_setopt($ch, CURLOPT_URL,$url);

	    curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // Define what you want to post
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $output = curl_exec ($ch); // Execute

	    curl_close ($ch); // Close cURL handle*/
		$api_result = json_decode($output,true);
		if($api_result['status'] == 'Success'){
			Address_charge::create([
				'user_id' => Auth::user()->id,
				'amount_krw' => $cash,
				'amount_tlc' => $amount
			]);

			EthApi::addInfoRequest(Auth::user()->email, 'deposit', $amount, 'refund');
			Address::where('user_email', Auth::user()->email)->increment('available_balance_tlc', $amount);

			$result = 'success';
		}else{
			$result = 'failed';
		}
		return response()->json($result);
	}

	function product_img_delete(Request $request){

		$img = str_replace("/","",$request->img);

		$img_path = '../storage/app/public/image/product/'.$img;

		if(File::exists($img_path)) {
			if($img != 'no_image.svg') {
				File::delete($img_path);
			}

			Product::find($request->id)->update([
				"image".$request->index => NULL,
			]);

		}



		return response()->json(1);
	}
	function slide_file_change(Request $request){
		$id = $request->id;
		$slide = Slide::where('id',$id)->first();

		$store_img_path = 'public/image/slide';
		if($file = $request->file('file')){
            if(isset($file)){
                if ($file->isValid()) {
					$pc_path = $file->store($store_img_path);
					$pc_file = str_replace($store_img_path."/","",$pc_path);

                    $img_path = '../storage/app/'.$store_img_path.'/'.$slide->slide_file;

                    if(File::exists($img_path)) {
                        File::delete($img_path);
                    }
                }
            }
		}

		Slide::where('id',$slide->id)->update([
			'slide_file' => $pc_file
		]);

		return response()->json([
			'file' => $pc_file
		]);
	}

	function normal_review_more(Request $request){
		$login_yn = Auth::check();
		$uid = -1;
		if($login_yn){
			$uid = Auth::id();
		}

		$reviews = Review::where('art_id',$request->proid)->offset($request->offset)->limit(10)->get();

		$offset = $request->offset + count($reviews);

		$response = array(
			"login_yn" => $login_yn,
			"uid" => $uid,
			"reviews" => $reviews,
			"offset" => $offset,
		);

		return response()->json($response);
	}

	function expert_review_more(Request $request){
		$login_yn = Auth::check();
		$uid = -1;
		if($login_yn){
			$uid = Auth::id();
		}

		$reviews = Expert_review::where('art_id',$request->proid)->offset($request->offset)->limit(10)->get();

		$offset = $request->offset + count($reviews);

		$response = array(
			"login_yn" => $login_yn,
			"uid" => $uid,
			"reviews" => $reviews,
			"offset" => $offset,
		);

		return response()->json($response);
	}

	function fee_product_change(Request $request){

		Fee::where('id',1)->update([
			'product_fee' => $request->input('product_fee'),
		]);

		return response()->json(1);
	}

	function fee_withdraw_change(Request $request){

		Fee::where('id',1)->update([
			'withdraw_fee' => $request->input('withdraw_fee'),
		]);

		return response()->json(1);
	}

	function fee_email_change(Request $request){

		$user_yn = User::where('email',$request->input('fee_email'))->first();

		if($user_yn != null){
			Fee::where('id',1)->update([
				'fee_email' => $request->input('fee_email'),
			]);
			return response()->json(1);
		}else{
			return response()->json(0);
		}




	}

	function fee_withdraw(){
		$fee = Fee::select('withdraw_fee')->where('id',1)->first();

		return response()->json($fee->withdraw_fee);
	}

	function order_confirm(Request $request){

		Order::where('id',$request->order_id)->update([
			"order_state" => 5,
		]);

		$order = Order::where('id',$request->order_id)->with('seller')->with('product')->first();

		if($order->how_pay == 0) {
			// 현금 구매 시 수수료 원단위 이하 절사
			$fee = bcmul($order->total_price, 0.05, 0);
			$result_price = bcsub($order->total_price, $fee, 0);
		} else if($order->how_pay == 10) {
			// 코인 구매시 소수점 2자리까지(임시)
			$fee = bcmul($order->total_price, 0.05, 2);
			$result_price = bcsub($order->total_price, $fee, 8);

			// 판매자 금액 증감 후 거래내역 기록
			$user = User::where('email', $order->seller->email)->first();
			EthApi::addInfoRequest($user->email, 'deposit', $order->total_price, 'sell');
			EthApi::addInfoRequest($user->email, 'withdraw', $fee, 'fee');
			Address::where('user_id', $user->id)->increment('available_balance_tlc', $result_price);

			// 수수료만큼 관리자 계정 증감 후 거래내역 기록
			EthApi::addInfoRequest('adminfee@admin.com', 'deposit', $fee, 'reward');
			Address::where('user_email', 'adminfee@admin.com')->increment('available_balance_tlc', $fee);
		}

		// 결과 기록
		DB::table('tlca_result_calculate')->insert([
			"order_id" => $order->id,
			"product_name" => $order->product->title,
			"seller_name" => $order->seller->name,
			"seller_email" => $order->seller->email,
			"seller_phone" => $order->seller->mobile_number,
			"bank_name" => $order->seller->account_bank,
			"bank_holder" => $order->seller->account_user,
			"bank_number" => $order->seller->account_number,
			"sale_price" => $order->total_price,
			"fee" => $fee,
			"result_price" => $result_price
		]);

		return response()->json(1);
	}

	function result_confirm(Request $request){

		DB::table('tlca_result_calculate')->where('id',$request->id)->update([
			"complete" => 1,
		]);

		return response()->json(1);
	}

	function mobile_mypage_my_info(Request $request) {
		$user = Auth::user();
		return response()->json($user);
	}

	function mobile_mypage_my_info_update(Request $request) {
		$user = Auth::user();

		User::where('id',$user->id)->update([
			'nickname' => $request->input("mb_nickname"),
			'mobile_number' => $request->input("mb_hp"),
			'post_num' => $request->input("post_num"),
			'addr1' => $request->input("mb_addr1"),
			'addr2' => $request->input("mb_addr2"),
		]);

		$user_update = User::where('id',$user->id)->first();

		return response()->json($user_update);
	}

	function mobile_mypage_product(Request $request) {
		$start = $request->start;
		$limit = $request->limit;

		$user = Auth::user();


		$products = DB::table('tlca_product')
		->select(DB::raw('tlca_product.*, tlca_category.ca_name, (SELECT count(*) FROM tlca_review WHERE art_id = tlca_product.id) as review_count'))
		->join('tlca_category','tlca_product.ca_id','=','tlca_category.id')
		->where('seller_id',$user->id)
		->orderBy('tlca_product.created_at','desc');

		$response = array(
			"product_cnt" => $products->count(),
			"products" => $products->skip($start)->limit($limit)->get(),
		);

		return response()->json($response);
	}

	function mobile_mypage_product_delete(Request $request) {
		$user = Auth::user();

		$id = $request->product_id;

		$product = Product::where('id',$id)->first();

		$img_path = '../storage/app/public/image/product/'.$product->image1;

		$img_path2 = '../storage/app/public/image/'.$product->artist_img;

		if(File::exists($img_path)) {
			if($product->image1 != 'no_image.svg') {
				File::delete($img_path);
			}
		}
		if(File::exists($img_path2)) {
			if($product->artist_img != 'default_profile.png') {
				File::delete($img_path2);
			}
		}

		if($product->batting_status != 1){
			$reviews = Review::where('art_id',$id)->get();

			foreach($reviews as $review){
				Review_like::where('id',$review->id)->delete();
			}

			Review::where('art_id',$id)->delete();

			Cart::where('product_id',$id)->delete();

			Product::where('id',$id)->delete();
		}else{
			return redirect()->back()->with('jsAlert', '삭제 하시려는 작품이 베팅중 입니다.');
		}

		return response()->json(1);
	}

	function mobile_mypage_batting(Request $request) {
		$start = $request->start;
		$limit = $request->limit;

		$date_term = $request->date_term;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$status = $request->status;

		$to_date = $to_date." 23:59:59";

		if($status == 0){
			$status = '%%';
		}

		$user = Auth::user();

		if($date_term == 0){
			$battings = DB::table('tlca_batting')
			->select('tlca_batting.*', 'tlca_product.title', 'tlca_product.artist_name', 'tlca_product.image1')
			->join('tlca_product','tlca_batting.art_id','=','tlca_product.id')
			->where('tlca_batting.uid',$user->id)->whereBetween('tlca_batting.created_at',[$from_date,$to_date])->where('tlca_batting.batting_status','like',$status)
			->orderBy('tlca_batting.created_at','desc');


		}else{
			$battings = DB::table('tlca_batting')
			->select('tlca_batting.*', 'tlca_product.title', 'tlca_product.artist_name', 'tlca_product.image1')
			->join('tlca_product','tlca_batting.art_id','=','tlca_product.id')
			->where('tlca_batting.uid',$user->id)->whereBetween('tlca_batting.created_at',[$from_date,$to_date])->where('tlca_batting.batting_status','like',$status)->whereDate('tlca_batting.created_at',">=",date('Y-m-d', strtotime('-'.$date_term.' day')))
			->orderBy('tlca_batting.created_at','desc');
		}

		$batting_lists = clone $battings;
		$batting_ings = clone $battings;
		$batting_ends = clone $battings;

		$response = array(
			"batting_cnt" => $batting_lists->count(),
			"batting_ings_cnt" => $batting_ings->where('tlca_batting.batting_status',1)->count(),
			"batting_ends_cnt" => $batting_ends->where('tlca_batting.batting_status',2)->count(),
			"battings" => $battings->skip($start)->limit($limit)->get(),
		);


		return response()->json($response);
	}

	function mobile_mypage_cart(Request $request) {
		$start = $request->start;
		$limit = $request->limit;

		$user = Auth::user();

		$carts = DB::table('tlca_cart')
		->select('tlca_cart.*', 'tlca_product.title', 'tlca_product.artist_name', 'tlca_product.image1', 'tlca_product.coin_price', 'tlca_product.cash_price', 'tlca_category.ca_name', 'tlca_product.batting_status')
		->join('tlca_product','tlca_cart.product_id','=','tlca_product.id')
		->join('tlca_category','tlca_product.ca_id','=','tlca_category.id')
		->where('tlca_cart.uid',$user->id)
		->orderBy('tlca_product.created_at','desc');

		$response = array(
			"cart_cnt" => $carts->count(),
			"carts" => $carts->skip($start)->limit($limit)->get(),
		);

		return response()->json($response);
	}

	function mobile_mypage_cart_delete(Request $request) {
		$idnum = explode("|", $request->input('delete_id'));

		$delete_count = 0;

		for($i=0; $i<count($idnum); $i++){
			$delete_count += Cart::where('id',$idnum[$i])->delete();
		}

		if($delete_count == count($idnum)){
			$response = array(
				"messages" => 'success',
			);
		}else{
			$response = array(
				"messages" => 'fail',
			);
		}

		return response()->json($response);
	}

	function mobile_mypage_buy_list(Request $request) {
		$start = $request->start;
		$limit = $request->limit;

		$date_term = $request->date_term;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$status = $request->status;

		$to_date = $to_date." 23:59:59";

		if($status == -1){
			$status = '%%';
		}

		$user = Auth::user();
		$buy_cnt_total = DB::table('tlca_order')->where('uid',$user->id);

		if($date_term == 0){
			$buy_orders = DB::table('tlca_order')
			->select('tlca_order.*', 'tlca_product.title', 'tlca_product.artist_name', 'tlca_product.image1', 'tlca_product.coin_price', 'tlca_product.cash_price', 'tlca_category.ca_name', 'users.name', 'users.mobile_number')
			->join('tlca_product','tlca_order.product_id','=','tlca_product.id')
			->join('tlca_category','tlca_product.ca_id','=','tlca_category.id')
			->join('users','tlca_product.seller_id','=','users.id')
			->where('tlca_order.uid',$user->id)->whereBetween('tlca_order.created_at',[$from_date,$to_date])->where('tlca_order.order_state','like',$status)
			->orderBy('created_at','desc');
		}else{
			$buy_orders = DB::table('tlca_order')
			->select('tlca_order.*', 'tlca_product.title', 'tlca_product.artist_name', 'tlca_product.image1', 'tlca_product.coin_price', 'tlca_product.cash_price', 'tlca_category.ca_name', 'users.name', 'users.mobile_number')
			->join('tlca_product','tlca_order.product_id','=','tlca_product.id')
			->join('tlca_category','tlca_product.ca_id','=','tlca_category.id')
			->join('users','tlca_product.seller_id','=','users.id')
			->where('tlca_order.uid',$user->id)->whereBetween('tlca_order.created_at',[$from_date,$to_date])->where('tlca_order.order_state','like',$status)->whereDate('tlca_order.created_at',">=",date('Y-m-d', strtotime('-'.$date_term.' day')))
			->orderBy('created_at','desc');
		}

		$buy_lists = clone $buy_cnt_total;

		$buy_request = clone $buy_cnt_total;
		$buy_ready = clone $buy_cnt_total;
		$buy_ing = clone $buy_cnt_total;
		$buy_end = clone $buy_cnt_total;
		$buy_cancel = clone $buy_cnt_total;
		$buy_finish = clone $buy_cnt_total;

		$buy_sidebar = clone $buy_cnt_total;

		$response = array(
			"buy_cnt" => $buy_lists->count(),
			"buy_request_cnt" => $buy_request->where('order_state',0)->where('order_cancel','<>',1)->count(),
			"buy_ready_cnt" => $buy_ready->where('order_state',1)->where('order_cancel','<>',1)->count(),
			"buy_ing_cnt" => $buy_ing->where('order_state',2)->where('order_cancel','<>',1)->count(),
			"buy_end_cnt" => $buy_end->where('order_state',3)->where('order_cancel','<>',1)->count(),
			"buy_cancel_cnt" => $buy_cancel->where(function($query){ $query->where('order_state',4)->orwhere('order_cancel',1); })->count(),
			"buy_finish_cnt" => $buy_finish->where('order_state',5)->where('order_cancel','<>',1)->count(),
			"buy_sidebar_cnt" => $buy_sidebar->where('order_state','<>',4)->where('order_state','<>',5)->where('order_cancel','<>',1)->where('order_cancel','<>',2)->count(),
			"buys" => $buy_orders->skip($start)->limit($limit)->get(),
		);


		return response()->json($response);
	}

	function mobile_mypage_buy_cancel(Request $request) {
		$order_id = $request->input('order_id');
		$cancel_reason = $request->input('cancel_reason');

		$cancel_cnt = 0;
		$refund_cnt = 0;

		$refund_cnt += Order::where('id',$order_id)->where('order_state','<>',0)->update([
			"order_cancel" => 2,
			"pay_cancel_infor" => $cancel_reason,
		]);

		$cancel_cnt += Order::where('id',$order_id)->where('order_state',0)->update([
			"order_cancel" => 1,
			"pay_cancel_infor" => $cancel_reason,
			"order_state" => 4,
		]);

		if($cancel_cnt != 0 && $refund_cnt == 0){
			$response = array(
				"messages" => 'cancel_success',
			);
		}else if($cancel_cnt == 0 && $refund_cnt != 0){
			$response = array(
				"messages" => 'refund_success',
			);
		}else{
			$response = array(
				"messages" => 'fail',
			);
		}

		return response()->json($response);
	}

	function mobile_mypage_sell_list(Request $request) {
		$start = $request->start;
		$limit = $request->limit;

		$date_term = $request->date_term;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$status = $request->status;

		$to_date = $to_date." 23:59:59";

		if($status == -1){
			$status = '%%';
		}

		$user = Auth::user();
		$sell_cnt_total = DB::table('tlca_order')->where('seller_id',$user->id);

		if($date_term == 0){
			$sell_orders = DB::table('tlca_order')
			->select('tlca_order.*', 'tlca_product.title', 'tlca_product.artist_name', 'tlca_product.image1', 'tlca_product.coin_price', 'tlca_product.cash_price', 'tlca_category.ca_name', 'users.name', 'users.mobile_number')
			->join('tlca_product','tlca_order.product_id','=','tlca_product.id')
			->join('tlca_category','tlca_product.ca_id','=','tlca_category.id')
			->join('users','tlca_order.uid','=','users.id')
			->where('tlca_order.seller_id',$user->id)->whereBetween('tlca_order.created_at',[$from_date,$to_date])->where('tlca_order.order_state','like',$status)
			->orderBy('created_at','desc');
		}else{
			$sell_orders = DB::table('tlca_order')
			->select('tlca_order.*', 'tlca_product.title', 'tlca_product.artist_name', 'tlca_product.image1', 'tlca_product.coin_price', 'tlca_product.cash_price', 'tlca_category.ca_name', 'users.name', 'users.mobile_number')
			->join('tlca_product','tlca_order.product_id','=','tlca_product.id')
			->join('tlca_category','tlca_product.ca_id','=','tlca_category.id')
			->join('users','tlca_order.uid','=','users.id')
			->where('tlca_order.seller_id',$user->id)->whereBetween('tlca_order.created_at',[$from_date,$to_date])->where('tlca_order.order_state','like',$status)->whereDate('tlca_order.created_at',">=",date('Y-m-d', strtotime('-'.$date_term.' day')))
			->orderBy('created_at','desc');
		}

		$sell_lists = clone $sell_cnt_total;
		$sell_request = clone $sell_cnt_total;
		$sell_ready = clone $sell_cnt_total;
		$sell_ing = clone $sell_cnt_total;
		$sell_end = clone $sell_cnt_total;
		$sell_cancel = clone $sell_cnt_total;
		$sell_finish = clone $sell_cnt_total;

		$response = array(
			"sell_cnt" => $sell_lists->count(),
			"sell_request_cnt" => $sell_request->where('order_state',0)->where('order_cancel','<>',1)->count(),
			"sell_ready_cnt" => $sell_ready->where('order_state',1)->where('order_cancel','<>',1)->count(),
			"sell_ing_cnt" => $sell_ing->where('order_state',2)->where('order_cancel','<>',1)->count(),
			"sell_end_cnt" => $sell_end->where('order_state',3)->where('order_cancel','<>',1)->count(),
			"sell_cancel_cnt" => $sell_cancel->where(function($query){ $query->where('order_state',4)->orwhere('order_cancel',1); })->count(),
			"sell_finish_cnt" => $sell_finish->where('order_state',5)->where('order_cancel','<>',1)->count(),
			"sells" => $sell_orders->skip($start)->limit($limit)->get(),
		);


		return response()->json($response);
	}

	function mobile_mypage_comment_list(Request $request) {
		$start = $request->start;
		$limit = $request->limit;

		$user = Auth::user();

		$reviews = Review::where('writer_id',$user->id)->with(array('product' =>function($query){ $query->with('category')->with('user'); }));
		$response = array(
			"comment_cnt" => $reviews->count(),
			"comments" => $reviews->skip($start)->limit($limit)->get(),
		);
		return response()->json($response);
	}

	function mobile_mypage_comment_delete(Request $request) {
		$id = $request->id;

		Review_like::where('review_id', $id)->delete();

		Review::where('id', $id)->delete();

		return response()->json(1);
	}

	function mobile_mypage_expertreview_list(Request $request) {
		$start = $request->start;
		$limit = $request->limit;

		$user = Auth::user();

		$reviews = Expert_review::where('uid',$user->id)->with(array('product' =>function($query){ $query->with('category')->with('user'); }));
		$response = array(
			"expertreivew_cnt" => $reviews->count(),
			"expertreivews" => $reviews->skip($start)->limit($limit)->get(),
		);
		return response()->json($response);
	}

	function mobile_mypage_expertreview_edit(Request $request){
		$review_id = $request->review_id;

		$review = Expert_review::where('id', $review_id)->update([
			'review_body' =>  $request->review_body,
			'rating' => $request->rating
		]);

		$response = array(
			'review_body' =>  $request->review_body,
		);

		return response()->json($response);
	}

	function mobile_mypage_expertreview_delete(Request $request) {
		$id = $request->id;
		Expert_review::where('id', $id)->delete();
		return response()->json(1);
	}

	function mobile_product_list(Request $request) {
		$ca_id = $request->ca_id;
		$status = $request->status;
		$start = $request->start;
		$limit = $request->limit;
		$skeyword = $request->keyword;

		$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1);

		if($ca_id != 0) {
			$products = $products->where('ca_id',$ca_id);
		}

		$products = $products->where('batting_status',$status)->with('user')->with('reviews')->with('category')->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); });

		if(Auth::check()){
			$uid = Auth::id();
			$products = $products->with(array('battings'=>function($query) use ($uid) {$query->where('tlca_batting.uid', $uid);}));
		}else{
			$products = $products->with('battings');
		}

		$products = $products->orderBy('id', 'desc');

		$response = array(
			"product_cnt" => $products->count(),
			"products" => $products->skip($start)->limit($limit)->get(),
		);

		return response()->json($response);
	}

	function mobile_delivery_company_list(){
		$response = SweetTracker::companylist();
		return response()->json($response);
	}

	function mobile_mypage_insert_delivery(Request $request){
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
			return response()->json($order_id);
		}else{
			return response()->json('error');
		}
	}

	public function popup(Request $request){
		$id = $request->id;

		setcookie("nopopup".$id, 1, time() + (86400 * 30), "/");

		$response = array(
			"status" => 1,
		);

		return response()->json($response);
	}

	public function notice_more(Request $request){
		$offset = $request->offset;

		$notices = DB::table('tlca_notice')->orderBy('id','desc')->offset($offset)->limit(10)->get();

		$offset = $offset + count($notices);

		$response = array(
			"notices" => $notices,
			"offset" => $offset,
		);

		return response()->json($response);
	}

	public function event_more(Request $request){
		$offset = $request->offset;

		$events = DB::table('tlca_event')->orderBy('id','desc')->offset($offset)->limit(10)->get();

		$offset = $offset + count($events);


		$response = array(
			"events" => $events,
			"offset" => $offset,
		);

		return response()->json($response);
	}

	public function admin_valid(Request $request){
		$email = $request->email;

			$user_cnt = Admin::where('email', $email)->count();

			if($user_cnt > 0){
				$response = array('exist' => 1);
			}else{
				$response = array('exist' => 0);
			}

			return response()->json($response);
	}

	public function admin_user_level_change(Request $request){
			$id = $request->id;
			$level = $request->level;
			DB::table('admin')->where('id',$id)->update([
				"level" => $level,
			]);

			return response()->json(1);
	}

	public function user_delete(Request $request){
		$response = array(
			'status' => 0
		);

		if(isset($request->uid)){

			$uid = $request->uid;

			if($uid === NULL || $uid === ''){
				return response()->json($response);
			}

			DB::table('users')->where('id', $uid)->update([
				'email_verified_at' => NULL,
				'name' => '탈퇴회원('.$uid.')',
				'password' => Hash::make(time()),
				'profile_img' => 'default_profile.png',
				'nickname' => '탈퇴회원('.$uid.')',
				'mobile_number' => NULL,
				'post_num' => NULL,
				'addr1' => NULL,
				'addr2' => NULL,
				'extra_addr' => NULL,
				'account_bank' => NULL,
				'account_number' => NULL,
				'account_user' => NULL,
				'updated_at' => now(),
				'status' => 0
			]);

			DB::table('tlca_product')->where('seller_id', $uid)->update([
				'sell_yn' => 2,
				'batting_status' => 0
			]);

			$response = array(
				'status' => 1
			);

		}else{

			if(!Auth::check()){
				return response()->json($response);
			}

			DB::table('users')->where('id', Auth::id())->update([
					'email_verified_at' => NULL,
					'name' => '탈퇴회원('.Auth::id().')',
					'password' => Hash::make(time()),
					'profile_img' => 'default_profile.png',
					'nickname' => '탈퇴회원('.Auth::id().')',
					'mobile_number' => NULL,
					'post_num' => NULL,
					'addr1' => NULL,
					'addr2' => NULL,
					'extra_addr' => NULL,
					'account_bank' => NULL,
					'account_number' => NULL,
					'account_user' => NULL,
					'updated_at' => now(),
					'status' => 0
			]);

			DB::table('tlca_product')->where('seller_id', Auth::id())->update([
				'sell_yn' => 2,
				'batting_status' => 0
			]);

			$response = array(
				'status' => 1
			);
			
		}

		return response()->json($response);
	}
}
