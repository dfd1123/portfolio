<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use TLCfund\User;
use TLCfund\Category;
use TLCfund\Product;
use TLCfund\Driver;
use TLCfund\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\SessionGuard;
use Cookie;
use File;
use Redirect;
use Session;

class CategorysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
		$agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
        $this->banner = Banner::where('bn_alt','갤러리')->first();
    }

    public function index($id)
    {
    	$categorys = Category::where('ca_use',1)->get();
		
        $ca_products = Category::find($id)->products()->limit(30)->get();
		
		$views = view($this->device.'.'.'product.sel_product');
		
		$views->categorys = $categorys;
		
		$views->ca_products = $ca_products;
		
		return $views;
    }
	
	public function sel_product(Request $request, $ca_id){
		$categorys = Category::where('ca_use',1)->get();
		
		if($request->input('keyword') == NULL){
			$skeyword = '';
		}else{
			$skeyword = $request->input('keyword');
        }
        
        $offset = 10;

        if($ca_id == 0){
            $products = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->limit($offset)->get();
        
            $product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->count();
        }else{
            $products = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->limit($offset)->get();
        
            $product_cnt = Product::where('sell_yn',1)->where('ca_use',1)->where('ca_id',$ca_id)->where(function($query) use ($skeyword) { $query->orWhere('title','like','%'.$skeyword.'%')->orWhere('artist_name','like','%'.$skeyword.'%')->orWhere('introduce','like','%'.$skeyword.'%'); })->with('user')->with('category')->with('reviews')->with('battings')->orderBy('id', 'desc')->count();
        }

		$views = view($this->device.'.'.'product.sel_product');
		
        $views->products = $products;
        
        $views->product_cnt = $product_cnt;
		
		$views->categorys = $categorys;
		
		$views->ca_id = $ca_id;
		
        $views->skeyword = $skeyword;
        
        $views->banner = $this->banner;

        $views->offset = $offset;

        $views->title = '갤러리';
		
		return $views;
		
	}
	
	public function bat_product($id)
	{
		$categorys = Category::where('ca_use',1)->get();
		
		$ca_products = Category::find($id)->products()->where('batting_yn',1)->orderBy('id', 'desc')->limit(30)->get();
		
		$views->categorys = $categorys;
		
        $views->ca_products = $ca_products;
        
        $views->banner = $this->banner;
		
		return $views;
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $views = view($this->device.'.'.'category.category_create');
        
        $views->banner = $this->banner;

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
        $input = $request->all();
		$path = NULL;
		
		if($request->hasFile('ca_icon')){
			if ($request->file('ca_icon')->isValid()) {
				$path = $request->ca_icon->store('public/image');
			}
		}
		
		return Category::create([
        	'ca_name' => $request->input('ca_name'),
        	'ca_discript' => $request->input('ca_discript'),
        	'ca_icon' => $path,
        ]);
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
}
