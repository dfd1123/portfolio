<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

use DB;

class NewsController extends Controller
{
	public function __construct()
    {
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
    public function index(){
		$count = 0;

		if($this->device == 'pc'){
			$news_lists = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->orderBy('created','desc')->paginate(15);
			$news_lists->withPath('news');
		}else{
			$count = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->count();
			//dd($count);
			$news_lists = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->orderBy('created','desc')->limit(15)->get();
		}

		$views = view(session('theme').'.'.$this->device.'.'.'news.news');
		$views->count = $count;
		$views->news_lists = $news_lists;
		
		
    	return	$views;
    }
	
	public function view($id){
		
		$news = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->where('id',$id)->first();
		$before_news = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->where('id','<',$id)->orderBy('created','desc')->first();
		$after_news = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->where('id','>',$id)->orderBy('created','asc')->first();
		
    	$views = view(session('theme').'.'.$this->device.'.'.'news.news_view');
		
		$views->news = $news;
		$views->before_news = $before_news;
		$views->after_news = $after_news;
		
    	return	$views;
    }
	
	public function write(Request $request){
		$views = view(session('theme').'.'.$this->device.'.'.'news.news_write');
		
    	return	$views;
		/*
    	$title = $request-> input('title');
		$category = $request -> input('category'); 
		$cont = $request -> textarea('cont');
		return redirect()->back->with('jsAlert','작성완료');
		*/
    }
}
