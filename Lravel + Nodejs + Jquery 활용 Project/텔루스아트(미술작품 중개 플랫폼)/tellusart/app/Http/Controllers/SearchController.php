<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use TLCfund\Banner;

use Illuminate\Http\Request;

class SearchController extends Controller
{

	public function __construct()
    {
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
		$this->banner = Banner::where('bn_alt','갤러리')->first();
	}
	
    public function search_list(Request $request){
		
		if($request->input('ca_id') == NULL){
			$ca_id = -1;
		}else{
			$ca_id = $request->input('ca_id');
		}
		
		if($request->input('keyword') == NULL){
			$keyword = '';
		}else{
			$keyword = $request->input('keyword');
		}
		
		$categorys = Category::where('ca_use',1)->where('ca_name', 'like', '%'.$keyword.'%')->get();
		
		$products = Product::where('sell_yn',1)->where('title', 'like', '%'.$keyword.'%')->orWhere('artist_name', '%'.$keyword.'%')->orWhere('artist_name', '%'.$keyword.'%')->orWhere('introduce', '%'.$keyword.'%')->get();
		
		$views = view($this->device.'.'.'product.product_search');
		$views->ca_id = $ca_id;
		$views->keyword = $keyword;
		$views->categorys = $categorys;
		$views->products = $products;

		$views->banner = $this->banner;
		
		return $views;
		
	}
}
