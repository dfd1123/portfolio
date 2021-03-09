<?php

namespace TLCfund\Http\Controllers;

use Illuminate\Http\Request;
use TLCfund\Product;
use TLCfund\Category;
use TLCfund\User;
use TLCfund\Batting;
use TLCfund\Notice;
use TLCfund\Video;
use TLCfund\Event;
use TLCfund\Slide;
use TLCfund\Company;
use Auth;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		//$this->middleware('auth');
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
		$this->title = 'TELLUSART';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	
		$categorys = Category::where('ca_use',1)->get();
		if(Auth::check()){
			$product_battings = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('batting_status',1)->with('category')->with('user')->with('reviews')->with(array('battings'=>function($query) {$query->where('tlca_batting.uid',Auth::user()->id);}))->orderBy('get_like','desc')->limit(5);	
		}else{
			$product_battings = Product::where('batting_yn',1)->where('sell_yn','<>',0)->where('batting_status',1)->with('category')->with('user')->with('reviews')->with('battings')->orderBy('get_like','desc')->limit(5);			
		}
		
		$bat_product =  Product::where('sell_yn','<>',0)->orderBy('created_at','desc')->count();
		
		$products = Product::where('sell_yn', 1)->with('user')->orderBy('get_hit','desc')->limit(10)->get();
		
		$battings = Batting::get();
		
		$artist_cnt = User::where('level',2)->count();
		
		$notices = Notice::limit(2)->orderBy('created_at','desc')->get();
		
		$video = Video::where('use_video',1)->first();
		
		$events = Event::whereDate('start_time','<=',date('Y-m-d'))->whereDate('end_time','>=',date('Y-m-d'))->limit(2)->orderBy('created_at','desc')->get();
		
		$slide_default = Slide::where('slide_default', 'Y')->first();

		$slides = Slide::where('slide_default', 'N')->get();

		$batting_sum = 0;
		
		foreach ($battings as $batting) {
			$batting_sum += $batting->batting_price;
		}
		
        $views = view($this->device.'.'.'home');
		
		$views->product_battings = $product_battings->limit(5)->orderBy('get_like','desc')->get();
		$views->bat_product = $bat_product;
		$views->products = $products;
		$views->battings = $battings;
		$views->batting_sum = $batting_sum;
		$views->artist_cnt = $artist_cnt;
		$views->categorys = $categorys;
		$views->notices = $notices;
		$views->video = $video;
		$views->events = $events;
		$views->slide_default = $slide_default;
		$views->slides = $slides;
		$views->title = $this->title;
		
		if($this->device == 'mobile'){
			$company = Company::first();
			$views->company = $company;
			$events = Event::whereDate('start_time','<=',date('Y-m-d'))->whereDate('end_time','>=',date('Y-m-d'))->limit(10)->orderBy('created_at','desc')->get();

			$pattern = array('<','/','>','div','p','h1','br');
			$views->pattern = $pattern;
		}
		
		return $views;
		}
		
		public function service(){
			
		}
}
