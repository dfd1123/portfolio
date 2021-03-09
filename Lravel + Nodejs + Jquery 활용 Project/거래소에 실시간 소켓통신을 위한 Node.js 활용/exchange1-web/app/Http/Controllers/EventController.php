<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

use DB;

class EventController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }
    
    public function index(){
        $views = view(session('theme').'.'.$this->device.'.'.'event.event');

        if($this->device == 'pc'){
            $events = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->orderBy('created','desc')->paginate(15);
            $events->withPath('event');
        }else{
            $count = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->orderBy('created','desc')->count();
            $events = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->orderBy('created','desc')->limit(15)->get();
            $views->count = $count;
        }

        $views->events = $events;
        $views->today = now()->format("Y-m-d");
        
        return	$views;
    }
    
    public function view($id){
        $event = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('id',$id)->where('active', 1)->first();
        $before_event = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('id','<',$id)->where('active', 1)->orderBy('created','desc')->first();
        $after_event = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('id','>',$id)->where('active', 1)->orderBy('created','asc')->first();
        
        $views = view(session('theme').'.'.$this->device.'.'.'event.event_view');
        
        $views->event = $event;
        $views->before_event = $before_event;
        $views->after_event = $after_event;
        $views->today = now()->format("Y-m-d");

        return	$views;
    }
}
