<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

use DB;

class NoticeController extends Controller
{
	public function __construct()
    {
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
    public function index(){
		$count = 0;

		if($this->device == 'pc'){
			$notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->paginate(15);
			$notices->withPath('notice');
		}else{
			$count = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->count();
			//dd($count);
			$notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->limit(15)->get();
		}

		$views = view(session('theme').'.'.$this->device.'.'.'notice.notice');
		$views->count = $count;
		$views->notices = $notices;
		
		
    	return	$views;
    }
	
	public function view($id){
		
		$notice = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->where('id',$id)->first();
		$before_notice = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->where('id','<',$id)->orderBy('created','desc')->first();
		$after_notice = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->where('id','>',$id)->orderBy('created','asc')->first();
		
    	$views = view(session('theme').'.'.$this->device.'.'.'notice.notice_view');
		
		$views->notice = $notice;
		$views->before_notice = $before_notice;
		$views->after_notice = $after_notice;
		
    	return	$views;
    }
	
	public function write(Request $request){
		$views = view(session('theme').'.'.$this->device.'.'.'notice.notice_write');
		
    	return	$views;
		/*
    	$title = $request-> input('title');
		$category = $request -> input('category'); 
		$cont = $request -> textarea('cont');
		return redirect()->back->with('jsAlert','작성완료');
		*/
    }
}
