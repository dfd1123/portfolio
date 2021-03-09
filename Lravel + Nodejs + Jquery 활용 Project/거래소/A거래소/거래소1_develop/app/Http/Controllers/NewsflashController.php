<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

use DB;

class NewsflashController extends Controller
{
	public function __construct()
    {
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
    public function index(Request $request){
			$count = 0;
			$srch_filter = $request->filter;
			$srch = $request->srch;

			if($this->device == 'pc'){
				if($srch_filter !== NULL){
					if($srch_filter === 'all'){
						$newsflashs = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))
											->where(function ($query) use ($srch) {
												$query->where('title', 'like', '%'.$srch.'%')
															->orWhere('description', 'like', '%'.$srch.'%');
											})->orderBy('created','desc')->paginate(15);
					}else{
						$newsflashs = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))
											->where($srch_filter, 'like', '%'.$srch.'%')->orderBy('created','desc')->paginate(15);
					}
				}else{
					$newsflashs = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))->orderBy('created','desc')->paginate(15);
				}
				$newsflashs->withPath('newsflash');
			}else{
				$count = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))->count();
				//dd($count);
				$newsflashs = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))->orderBy('created','desc')->limit(15)->get();
			}

			$views = view(session('theme').'.'.$this->device.'.'.'newsflash.newsflash');
			$views->count = $count;
			$views->srch_filter = $srch_filter;
			$views->srch = $srch;
			$views->newsflashs = $newsflashs;
		
		
    	return	$views;
    }
	
	public function view($id){
		
		$newsflash = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))->where('id',$id)->first();
		$before_newsflash = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))->where('id','<',$id)->orderBy('created','desc')->first();
		$after_newsflash = DB::connection('mysql_sub')->table('btc_newsflash_'.config('app.country'))->where('id','>',$id)->orderBy('created','asc')->first();
		
    	$views = view(session('theme').'.'.$this->device.'.'.'newsflash.newsflash_view');
		
		$views->newsflash = $newsflash;
		$views->before_newsflash = $before_newsflash;
		$views->after_newsflash = $after_newsflash;
		
    	return	$views;
    }
	
	public function write(Request $request){
		$views = view(session('theme').'.'.$this->device.'.'.'newsflash.newsflash_write');
		
    	return	$views;
		/*
    	$title = $request-> input('title');
		$category = $request -> input('category'); 
		$cont = $request -> textarea('cont');
		return redirect()->back->with('jsAlert','작성완료');
		*/
    }
}
