<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use TLCfund\User;
use TLCfund\Category;
use TLCfund\Product;
use TLCfund\Driver;
use TLCfund\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\SessionGuard;
use Cookie;
use File;
use Redirect;
use Session;

class GalleryController extends Controller
{

	public function __construct()
    {
		$agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    function index(){
    	$categorys = Category::where('ca_use',1)->get();
		
		$products = Product::where('sell_yn',1);
		
		$views = view($this->device.'.'.'gallery.gallery_list');
		
		$views->categorys = $categorys;
		
		$views->products = $products->limit(30)->get();
		
		$views->product_cnt = $products->count();
		
		return $views;
    }
	
	function view($id){
		
		$categorys = Category::where('ca_use',1)->get();
		
		$product = Product::where('id',$id)->first();
		
		$reviews = Product::find($id)->reviews();
		
		$views = view($this->device.'.'.'gallery.gallery_show');
		
		$views->categorys = $categorys;
		
		$views->products = $product;
		
		$views->gallery_id = $id;
		
		$views->reviews = $reviews->get();
		
		$views->reviews_cnt = $reviews_cnt->count();
		
		return $views;
	}
}
