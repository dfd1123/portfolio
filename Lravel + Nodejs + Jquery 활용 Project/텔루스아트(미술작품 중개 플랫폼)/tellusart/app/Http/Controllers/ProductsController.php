<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;
use Intervention\Image\Facades\Image as InterventionImage;

use TLCfund\User;
use TLCfund\Category;
use TLCfund\Product;
use TLCfund\Batting;
use TLCfund\Order;
use TLCfund\Cart;
use TLCfund\Driver;
use TLCfund\Banner;
use TLCfund\Review;
use TLCfund\Review_like;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\SessionGuard;
use Cookie;
use File;
use Redirect;
use Session;
use Image;
use Auth;
use DB;

class ProductsController extends Controller
{
	
	public function __construct()
    {
		$this->middleware('auth', ['only' => ['create']]);
		$this->middleware('account', ['only' => ['create']]);
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
		$this->banner = Banner::where('bn_alt','갤러리')->first();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*	
        $products = Product::where('sell_yn',1)->where('ca_id',$ca_id)->with('user')->with('reviews')->get();
		
		$categorys = Category::where('ca_use',1)->get();
		
		$views = view('product.product_list');
		
		$views->products = $products;
		
		$views->categorys = $categorys;
		$views->ca_id = $ca_id;
        
		return $views;
		 * */
    }
	
	public function search_list(Request $request, $ca_id){

		
		$categorys = Category::where('ca_use',1);

		if($request->input('keyword') == NULL){
			$skeyword = '';
		}else{
			$skeyword = $request->input('keyword');
			
			if($categorys->where('ca_name','like','%'.$skeyword.'%')->count() != 0){
				$ca_id = $categorys->where('ca_name','like','%'.$skeyword.'%')->select('id')->first()->id;
			}
		}

		$offset = 10;
		
		
		if($ca_id != -1 && $request->input('keyword') == NULL){
			if(Auth::check()){
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->limit($offset)->get();
				$product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->count();
			}else{
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->limit($offset)->get();
				$product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->count();
			}
		}elseif($ca_id != -1){
			if(Auth::check()){
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->limit($offset)->get();
				$product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->count();
			}else{
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->limit($offset)->get();
				$product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->count();
			}
		}else{
			if(Auth::check()){
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->limit($offset)->get();
				$product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with(array('battings'=>function($query){$query->where('tlca_batting.uid',Auth::id());}))->orderBy('id', 'desc')->count();
			}else{
				$products = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->limit($offset)->get();
				$product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->count();
			}
		}
		
		$views = view($this->device.'.'.'product.product_search');
		$views->ca_id = $ca_id;
		$views->skeyword = $skeyword;
		$views->categorys =Category::where('ca_use',1)->get();
		$views->products = $products;
		$views->product_cnt = $product_cnt;
		$views->offset = $offset;
		$views->banner = $this->banner;
		$views->title = '작품검색';
		
		return $views;
		
	}

	
	public function batting_list(Request $request, $ca_id, $status){
		$today = date("Y-m-d",strtotime('+9 hour'));
		info($today);
		if(Auth::check()){
			$uid = Auth::id();
		}else{
			$uid = NULL;
		}

		if($request->input('keyword') == NULL){
			$skeyword = '';
		}else{
			$skeyword = $request->input('keyword');
		}
		
		$offset = 10;
		
		if(Auth::check()){
			$products = Product::where('batting_yn',1)->where('sell_yn', '<>' , 0)->where('sell_yn','<>',2)->where('ca_use',1);
			if($ca_id != 0) {
				$products = $products->where('ca_id',$ca_id);
			}
			$products = $products->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('reviews')->with(array('battings'=>function($query) use ($uid) {$query->where('tlca_batting.uid',$uid);}))->orderBy('id', 'desc')->limit($offset)->get();
			
			$product_cnt = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1);
			if($ca_id != 0) {
				$product_cnt = $product_cnt->where('ca_id',$ca_id);
			}
			$product_cnt = $product_cnt->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('reviews')->with(array('battings'=>function($query) use ($uid) {$query->where('tlca_batting.uid',$uid);}))->orderBy('id', 'desc')->count();	
		}else{
			$products = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1);
			if($ca_id != 0) {
				$products = $products->where('ca_id',$ca_id);
			}
			$products = $products->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('reviews')->with('battings')->orderBy('id', 'desc')->limit(10)->get();
			
			$product_cnt = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('sell_yn','<>',2)->where('ca_use',1);
			if($ca_id != 0) {
				$product_cnt = $product_cnt->where('ca_id',$ca_id);
			}
			$product_cnt = $product_cnt->where('batting_status',$status)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('reviews')->with('battings')->orderBy('id', 'desc')->count();		
		}
		$betting_set = DB::table('tlca_batting_set')->first(); 

		date_default_timezone_set("Asia/Seoul");
		$now_day = strtotime(date("Y/m/d H:i:s"));
		$endday = strtotime($betting_set->end_time." +1 days");
		
		$diff = $endday - $now_day;
		
		$days = floor($diff/86400);
		$time = $diff - ($days*86400);
		$hours = floor($time/3600);
		$time = $time - ($hours*3600);
		$mins = floor($time/60);
		$secs = $time-($mins*60);
		
		$categorys = Category::where('ca_use',1)->get();
		
		$views = view($this->device.'.'.'product.bat_product');

		$views->status = $status;
		$views->products = $products;
		$views->product_cnt = $product_cnt;
		$views->skeyword = $skeyword;
		$views->offset = $offset;
		
		$views->categorys = $categorys;
		$views->ca_id = $ca_id;
		$views->hours = $hours;
		$views->days = $days;
		$views->hours = $hours;
		$views->mins = $mins;
		$views->secs = $secs;
		$views->batting_cnt = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('ca_use',1)->where('ca_id',$ca_id)->where('batting_status',1)->count();
		$views->title = '베팅리스트';
		
		$views->uid = $uid;

		$banner = Banner::where('bn_alt','배팅')->first();

		$views->banner = $banner;
		
		return $views;
	}
	

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	/*$email = session('uemail');
        $user = User::where('email',$email)->first();
		
			$views = view('product.product_create');
			$views->uid = $user->id;
			$views->uname = $user->name;
			*/
			$categorys = Category::where('ca_use',1)->get();
			
			$views = view($this->device.'.'.'product.product_create')->with('categorys',$categorys);

			$views->banner = $this->banner;
			$views->title = '작품등록';

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
		$path2 = 'default_profile.png';
		
		$images = array();
		
		$path = array();
		
		$path[0] = 'no_image.svg';

		$storage_path = "../storage/app/public/";
		$save_path = "image/product/";

		$watermark =  Image::make(storage_path('../storage/app/public/image/homepage/watermark_50.png'));
		
		if($files = $request->file('images')){
			$i = 0;
			foreach($files as $key => $file){
				if ($file->isValid()) {

					// 이미지 회전 후 저장
					$rotate = $request->{'img_rotate'.($i + 1)} * -1;
					$img = InterventionImage::make($files[$i]);

					if($img->width() >= 1000){
						$img->resize(700, null, function ($constraint) {
							$constraint->aspectRatio(); //비율유지
						})->rotate($rotate)->encode('jpg');
					}else{
						$img->rotate($rotate)->encode('jpg');
					}

					//#1
					$watermarkSize = $img->width() - 20; //size of the image minus 20 margins
					//#2
					$watermarkSize = $img->width() / 2; //half of the image size
					//#3
					$resizePercentage = 70;//70% less then an actual image (play with this value)

					$watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2);

					// resize watermark width keep height auto
					$watermark->resize($watermarkSize, null, function ($constraint) {
						$constraint->aspectRatio();
					});

					$hash = md5($img->__toString(). time());
					$path[$i] = $storage_path.$save_path.$hash.".jpg";

					//insert resized watermark to image center aligned
					$img->insert($watermark, 'center');

					$img->save($path[$i]);

					$path[$i] = '/'.str_replace($storage_path.$save_path,"",$path[$i]);

					$i++;
				}	
			}
		}

		$save_path2 = "image/";
		
		if($request->hasFile('artist_img')){
			if ($request->file('artist_img')->isValid()) {

				// 이미지 회전 후 저장
				$rotate = $request->img_rotate_artist * -1;
				$img = InterventionImage::make($request->file('artist_img'));

				if($img->width() >= 100){
					$img->resize(100, null, function ($constraint) {
						$constraint->aspectRatio(); //비율유지
					})->rotate($rotate)->encode('jpg');
				}else{
					$img->rotate($rotate)->encode('jpg');
				}

				$hash = md5($img->__toString(). time());
				$path2 = $storage_path.$save_path2.$hash.".jpg";
				$img->save($path2);

				$path2 = '/'.str_replace($storage_path.$save_path2,"",$path2);
			}
		}

		$betting_set = DB::table('tlca_batting_set')->first(); 
		
		$art_date = $request->input('date_y').$request->input('date_m').$request->input('date_d');
		
		date_default_timezone_set("Asia/Seoul");
		
		$start_batting_day = date("Y/m/d",strtotime($betting_set->end_time." +1 days"));
		$end_batting_day = date("Y/m/d",strtotime($start_batting_day." +".($betting_set->batting_term - 1)." days"));
		
		$ca_use = Category::where('id', $request->input('category'))->first()->ca_use;
		info(str_replace(',', '', $request->input('coin_price')));

		$today = date("Y-m-d");
		if( $request->input('batting_yn') == 1){
			Product::create([
	        	'title' => $request->input('title'),
	        	'seller_id' => $user->id,
	        	'artist_img' => $path2,
	        	'artist_name' => $request->input('artist_name'),
	        	'artist_intro' => $request->input('artist_intro'),
	        	'artist_career' => $request->input('artist_career'),
	        	'image1' => $path[0],
	        	'image2' => isset($path[1])?$path[1]:NULL,
	        	'image3' => isset($path[2])?$path[2]:NULL,
	        	'image4' => isset($path[3])?$path[3]:NULL,
	        	'image5' => isset($path[4])?$path[4]:NULL,
	        	'introduce' => $request->input('introduce'),
	        	'art_width_size' => $request->input('art_width_size'),
	        	'art_height_size' => $request->input('art_height_size'),
	        	'art_date' => $art_date,
	        	'ca_id' => $request->input('category'),
	        	'ca_use' => $ca_use,
				'coin_price' => str_replace(',', '', $request->input('coin_price')),
				'cash_price' => str_replace(',', '', $request->input('cash_price')),
				'delivery_price' => str_replace(',', '', $request->input('delivery_price')),
	        	'batting_yn' => $request->input('batting_yn'),
	        	'start_time' => $start_batting_day,
				'end_time' => $end_batting_day,
				'created_at' => $today,
				'updated_at' => $today
	        ]);	
		}else{
			Product::create([
	        	'title' => $request->input('title'),
	        	'seller_id' => $user->id,
	        	'artist_img' => $path2,
	        	'artist_name' => $request->input('artist_name'),
	        	'artist_intro' => $request->input('artist_intro'),
	        	'artist_career' => $request->input('artist_career'),
	        	'image1' => $path[0],
	        	'image2' => isset($path[1])?$path[1]:NULL,
	        	'image3' => isset($path[2])?$path[2]:NULL,
	        	'image4' => isset($path[3])?$path[3]:NULL,
	        	'image5' => isset($path[4])?$path[4]:NULL,
	        	'introduce' => $request->input('introduce'),
	        	'art_width_size' => $request->input('art_width_size'),
	        	'art_height_size' => $request->input('art_height_size'),
	        	'art_date' => $art_date,
	        	'ca_id' => $request->input('category'),
	        	'ca_use' => $ca_use,
				'coin_price' => str_replace(',', '', $request->input('coin_price')),
				'cash_price' => str_replace(',', '', $request->input('cash_price')),
				'delivery_price' => str_replace(',', '', $request->input('delivery_price')),
				'created_at' => $today,
				'updated_at' => $today
	        ]);	
		}

		User::where('id',$user->id)->update([
			'level' => '2',
		]);
		
		if($this->device == 'pc'){
			return redirect(route('mypage.myart_list'));
		}else{
			return redirect(route('mypage.mobile_mypage',['index' => 1]));
		}
    }
	
	public function batting_create()
    {
		$views = view($this->device.'.'.'product.batting_create');
		$views->banner = $this->banner;

		return $views;
    }
	
	public function batting_update(Request $request)
    {
		$views = view($this->device.'.'.'product.batting_create');
		$views->banner = $this->banner;

		return $views;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$value = Cookie::get('products');
		
		//dd($value);
		
		if($value == NULL){
			setcookie('products',$id, time() + (86400 * 30), "/");
			//dd($_COOKIE['notice']);
			Product::where('id',$id)->increment('get_hit');
		}
		
        $product = Product::where('id',$id)->with('user')->with('category')->with(array('reviews' => function($query){ $query->orderBy('tlca_review.id','desc'); }))->with(array('expert_reviews' => function($query){ $query->orderBy('tlca_expert_review.id','desc'); }))->first();
		
		$product_cnt = User::where('id',$product->seller_id)->with('products')->first();
		
		$selling_cnt = Order::where('seller_id',$product->seller_id)->where('order_state','<>',4)->count();
		
		if(Auth::check()){
			$batting_cnt = Batting::where('batting_status',1)->where('art_id',$id)->where('uid',Auth::user()->id)->count();
		}else{
			$batting_cnt = 0;
		}
				
		if($batting_cnt > 0){
			$batting_yn = 1;
		}else{
			$batting_yn = 0;
		}

		$betting_set = DB::table('tlca_batting_set')->first();
		date_default_timezone_set("Asia/Seoul");
		$now_day = strtotime(date("Y/m/d H:i:s"));
		$endday = strtotime($betting_set->end_time." +1 days");
		
		$diff = $endday - $now_day;
		
		$days = floor($diff/86400);
		$time = $diff - ($days*86400);
		$hours = floor($time/3600);
		$time = $time - ($hours*3600);
		$mins = floor($time/60);
		$secs = $time-($mins*60);
		
		$views = view($this->device.'.'.'product.product_show');
		
		$views->product = $product;
		
		$views->product_cnt = $product_cnt;
		
		$views->selling_cnt = $selling_cnt;
		
		$views->batting_yn = $batting_yn;

		$views->review_offset = 10;
		$views->title = '작품상세보기';
		
		$views->days = $days;
		$views->hours = $hours;
		$views->mins = $mins;
		$views->secs = $secs;

		$views->banner = $this->banner;
		
		
		
		return $views;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$product = Product::where('id',$id)->first();
		
		$categorys = Category::where('ca_use',1)->get();
		
		$art_date =  explode( '-', $product->art_date );
		
		$views = view($this->device.'.'.'product.product_edit');
		
		$views->product = $product;
		
		$views->categorys = $categorys;
        
        $views->date_y = $art_date[0];
        
		$views->date_m = $art_date[1];
		
		$views->date_d = $art_date[2];

		$views->banner = $this->banner;

		$views->title = '작품수정';
		
        return $views;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	/*
    public function update(Request $request, $id)
    {
        $user = Auth::user();
		
		$input = $request->all();
		$images = array();
		
		$path = array();
		$path2 = $request->input('artist_img');
		
		$product = Product::where('id',$id)->first();
		
		if($files = $request->file('images')){
			for($i=0; $i<5; $i++){
				if(isset($files[$i])){
					if ($files[$i]->isValid()) {
						$path[$i] = $files[$i]->store('public/image/product/');
						$path[$i] = str_replace("public/image/product/","",$path[$i]);
						
						$img_path = '../storage/app/public/image/product/'.$product['image'.($i+1)];
        
						if(File::exists($img_path)) {
						    File::delete($img_path);
						}

					}else{
						$path[$i] = $product['image'.($i+1)];
					}	
				}else{
					$path[$i] = $product['image'.($i+1)];
				}	
			}
		}else{
			for($i=0; $i<5; $i++){
				$path[$i] = $product['image'.($i+1)];
			}
		}
		
		if($path2 != 'default_profile.png'){
			if($request->hasFile('artist_img')){
				if ($request->file('artist_img')->isValid()) {
					$path2 = $request->artist_img->store('public/image/');
					
					$img_path = '../storage/app/public/image/'.$product['artist_img'];
        
					if(File::exists($img_path)) {
					    File::delete($img_path);
					}
				}
			}else{
				$path2 == 'default_profile.png';
			}
		}
		
		$art_date = $request->input('date_y').$request->input('date_m').$request->input('date_d');
		
		$path2 = str_replace("public/image/","",$path2);


		$betting_set = DB::table('tlca_batting_set')->first();
		date_default_timezone_set("Asia/Seoul");
		
		$start_batting_day = date("Y/m/d",strtotime($betting_set->end_time." +1 days"));
		$end_batting_day = date("Y/m/d",strtotime($start_batting_day." +".$betting_set->batting_term." days"));
		
		$ca_use = Category::where('id', $request->input('category'))->first()->ca_use;
		
		if( $request->input('batting_yn') == 1 || $request->input('batting_yn') == 2){
			Product::where('id',$id)->update([
	        	'title' => $request->input('title'),
	        	'seller_id' => $user->id,
	        	'artist_img' => $path2,
	        	'artist_name' => $request->input('artist_name'),
	        	'artist_intro' => $request->input('artist_intro'),
	        	'artist_career' => $request->input('artist_career'),
	        	'image1' => $path[0],
	        	'image2' => isset($path[1])?$path[1]:NULL,
	        	'image3' => isset($path[2])?$path[2]:NULL,
	        	'image4' => isset($path[3])?$path[3]:NULL,
	        	'image5' => isset($path[4])?$path[4]:NULL,
	        	'introduce' => $request->input('introduce'),
	        	'art_width_size' => $request->input('art_width_size'),
	        	'art_height_size' => $request->input('art_height_size'),
	        	'art_date' => $art_date,
	        	'ca_id' => $request->input('category'),
	        	'ca_use' => $ca_use,
				'cash_price' => str_replace(',', '', $request->input('cash_price')),
				'coin_price' => str_replace(',', '', $request->input('coin_price')),
				'delivery_price' => str_replace(',', '', $request->input('delivery_price')),
				'batting_yn' => $request->input('batting_yn'),
				'sell_yn' => 0,
	        	'start_time' => $start_batting_day,
	        	'end_time' => $end_batting_day,
	        	'batting_status' => 0,
	        ]);	
		}else{
			Product::where('id',$id)->update([
	        	'title' => $request->input('title'),
	        	'seller_id' => $user->id,
	        	'artist_img' => $path2,
	        	'artist_name' => $request->input('artist_name'),
	        	'artist_intro' => $request->input('artist_intro'),
	        	'artist_career' => $request->input('artist_career'),
	        	'image1' => $path[0],
	        	'image2' => isset($path[1])?$path[1]:NULL,
	        	'image3' => isset($path[2])?$path[2]:NULL,
	        	'image4' => isset($path[3])?$path[3]:NULL,
	        	'image5' => isset($path[4])?$path[4]:NULL,
	        	'introduce' => $request->input('introduce'),
	        	'art_width_size' => $request->input('art_width_size'),
	        	'art_height_size' => $request->input('art_height_size'),
	        	'art_date' => $art_date,
	        	'ca_id' => $request->input('category'),
	        	'ca_use' => $ca_use,
				'cash_price' => str_replace(',', '', $request->input('cash_price')),
				'coin_price' => str_replace(',', '', $request->input('coin_price')),
				'delivery_price' => str_replace(',', '', $request->input('delivery_price')),
				'sell_yn' => 0,
	        ]);	
		}

		User::where('id',$user->id)->where('level',1)->where('level','<>',3)->update([
			'level' => '2',
		]);
		
		if($this->device == 'pc'){
			return redirect(route('mypage.myart_list'));
		}else{
			return redirect(route('mypage.mobile_mypage',['index' => 1]));
		}
	}
	*/
	
	public function updates(Request $request, $id)
    {
        $user = Auth::user();
		
		$input = $request->all();
		$images = array();
		
		$path = array();
		$path2 = $request->input('artist_img');
		
		$product = Product::where('id',$id)->first();

		$storage_path = "../storage/app/public/";
		$save_path = "image/product/";

		$watermark =  Image::make(storage_path('../storage/app/public/image/homepage/watermark_50.png'));
		
		if($files = $request->file('images')){
			for($i=0; $i<5; $i++){
				if(isset($files[$i])){
					if ($files[$i]->isValid()) {
						
						// 이미지 회전 후 저장
						$rotate = $request->{'img_rotate'.($i + 1)} * -1;
						$img = InterventionImage::make($files[$i]);
						if($img->width() >= 1000){
							$img->resize(700, null, function ($constraint) {
								$constraint->aspectRatio(); //비율유지
							})->rotate($rotate)->encode('jpg');
						}else{
							$img->rotate($rotate)->encode('jpg');
						}

						//#1
						$watermarkSize = $img->width() - 20; //size of the image minus 20 margins
						//#2
						$watermarkSize = $img->width() / 2; //half of the image size
						//#3
						$resizePercentage = 70;//70% less then an actual image (play with this value)

						$watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2);

						// resize watermark width keep height auto
						$watermark->resize($watermarkSize, null, function ($constraint) {
							$constraint->aspectRatio();
						});

						$hash = md5($img->__toString(). time());
						$path[$i] = $storage_path.$save_path.$hash.".jpg";

						//insert resized watermark to image center aligned
						$img->insert($watermark, 'center');

						$img->save($path[$i]);

						$path[$i] = '/'.str_replace($storage_path.$save_path,"",$path[$i]);
						$img_path = $storage_path.$save_path.$product['image'.($i+1)];
        
						if(File::exists($img_path)) {
							if($product['image'.($i+1)] != 'no_image.svg') {
								File::delete($img_path);
							}
						}

					}else{
						$path[$i] = $product['image'.($i+1)];
					}	
				}else{
					$path[$i] = $product['image'.($i+1)];
				}	
			}
		}else{
			for($i=0; $i<5; $i++){
				$path[$i] = $product['image'.($i+1)];
			}
		}
		
		$save_path2 = "image/";
		
		if($path2 != 'default_profile.png'){
			if($request->hasFile('artist_img')){
				if ($request->file('artist_img')->isValid()) {
	
					// 이미지 회전 후 저장
					$rotate = $request->img_rotate_artist * -1;
					$img = InterventionImage::make($request->file('artist_img'));
	
					if($img->width() >= 100){
						$img->resize(100, null, function ($constraint) {
							$constraint->aspectRatio(); //비율유지
						})->rotate($rotate)->encode('jpg');
					}else{
						$img->rotate($rotate)->encode('jpg');
					}
	
					$hash = md5($img->__toString(). time());
					$path2 = $storage_path.$save_path2.$hash.".jpg";
					$img->save($path2);
	
					$path2 = '/'.str_replace($storage_path.$save_path2,"",$path2);
					
					// 기존 이미지 삭제
					$img_path = '../storage/app/public/image/'.$product['artist_img'];

					if(File::exists($img_path)) {
						if($product['artist_img'] != 'default_profile.png') {
							File::delete($img_path);
						}
					}
				}
			}
			
		}
		
		$art_date = $request->input('date_y').'-'.$request->input('date_m').'-'.$request->input('date_d');
		
		date_default_timezone_set("Asia/Seoul");
		
		$betting_set = DB::table('tlca_batting_set')->first();
		$start_batting_day = date("Y/m/d",strtotime($betting_set->end_time.' +1 days'));
		$end_batting_day = date("Y/m/d",strtotime($start_batting_day." +".($betting_set->batting_term - 1)." days"));
		
		$ca_use = Category::where('id', $request->input('category'))->first()->ca_use;
		
		if( $request->input('batting_yn') == 1 || $request->input('batting_yn') == 2){
			Product::where('id',$id)->update([
	        	'title' => $request->input('title'),
	        	'seller_id' => $user->id,
	        	'artist_img' => $path2,
	        	'artist_name' => $request->input('artist_name'),
	        	'artist_intro' => $request->input('artist_intro'),
	        	'artist_career' => $request->input('artist_career'),
	        	'image1' => $path[0],
	        	'image2' => isset($path[1])?$path[1]:NULL,
	        	'image3' => isset($path[2])?$path[2]:NULL,
	        	'image4' => isset($path[3])?$path[3]:NULL,
	        	'image5' => isset($path[4])?$path[4]:NULL,
	        	'introduce' => $request->input('introduce'),
	        	'art_width_size' => $request->input('art_width_size'),
	        	'art_height_size' => $request->input('art_height_size'),
	        	'art_date' => $art_date,
	        	'ca_id' => $request->input('category'),
	        	'ca_use' => $ca_use,
				'cash_price' => str_replace(',', '', $request->input('cash_price')),
				'coin_price' => str_replace(',', '', $request->input('coin_price')),
				'delivery_price' => str_replace(',', '', $request->input('delivery_price')),
				'batting_yn' => $request->input('batting_yn'),
				'sell_yn' => 0,
	        	'start_time' => $start_batting_day,
	        	'end_time' => $end_batting_day,
				'batting_status' => 0,
				'updated_at' => date("Y-m-d"),
	        ]);	
		}else{
			Product::where('id',$id)->update([
	        	'title' => $request->input('title'),
	        	'seller_id' => $user->id,
	        	'artist_img' => $path2,
	        	'artist_name' => $request->input('artist_name'),
	        	'artist_intro' => $request->input('artist_intro'),
	        	'artist_career' => $request->input('artist_career'),
	        	'image1' => $path[0],
	        	'image2' => isset($path[1])?$path[1]:NULL,
	        	'image3' => isset($path[2])?$path[2]:NULL,
	        	'image4' => isset($path[3])?$path[3]:NULL,
	        	'image5' => isset($path[4])?$path[4]:NULL,
	        	'introduce' => $request->input('introduce'),
	        	'art_width_size' => $request->input('art_width_size'),
	        	'art_height_size' => $request->input('art_height_size'),
	        	'art_date' => $art_date,
	        	'ca_id' => $request->input('category'),
	        	'ca_use' => $ca_use,
				'cash_price' => str_replace(',', '', $request->input('cash_price')),
				'coin_price' => str_replace(',', '', $request->input('coin_price')),
				'delivery_price' => str_replace(',', '', $request->input('delivery_price')),
				'sell_yn' => 0,
				'updated_at' => date("Y-m-d"),
	        ]);	
		}

		User::where('id',$user->id)->where('level',1)->where('level','<>',3)->update([
			'level' => '2',
		]);
		
		if($this->device == 'pc'){
			return redirect(route('mypage.myart_list'));
		}else{
			return redirect(route('mypage.mobile_mypage',['index' => 1]));
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    public function delete($id)
    {
        $product=Product::where('id',$id)->first();//delete();
        
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
			return redirect() -> back() -> with('jsAlert', '삭제 하시려는 작품이 베팅중 입니다.');
		}
		
		return redirect()->back();
	}
	
    public function destroy($id)
    {
        //
	}
	
	function imageCreateFromAny($filepath) { 
		$type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
		$allowedTypes = array( 
			1,  // [] gif 
			2,  // [] jpg 
			3,  // [] png 
			6   // [] bmp 
		); 
		if (!in_array($type, $allowedTypes)) { 
			return false; 
		} 
		switch ($type) { 
			case 1 : 
				$im = imageCreateFromGif($filepath); 
			break; 
			case 2 : 
				$im = imageCreateFromJpeg($filepath); 
			break; 
			case 3 : 
				$im = imageCreateFromPng($filepath); 
			break; 
			case 6 : 
				$im = imageCreateFromBmp($filepath); 
			break; 
		}    
		return $im;  
	} 
}
