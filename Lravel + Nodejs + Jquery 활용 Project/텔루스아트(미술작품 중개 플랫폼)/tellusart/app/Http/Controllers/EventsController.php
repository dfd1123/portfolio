<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use TLCfund\Banner;

use Illuminate\Http\Request;

use TLCfund\Event;
use TLCfund\User;
use TLCfund\Faq;
use TLCfund\Notice;
use Auth;

class EventsController extends Controller
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
        $this->banner = Banner::where('bn_alt','공지사항')->first();
    }

    public function index()
    {
    	$notices = Notice::orderBy('created_at','desc')->paginate(10);
        $notices->withPath('notices');
        
        $faqs = Faq::orderBy('created_at','desc')->paginate(15);
        $faqs->withPath('faq');
        
        $offset = 10;

        if($this->device == 'pc'){
            $events = Event::orderBy('id','desc')->paginate(7);
            $events->withPath('events');
        }else{
            $events = Event::orderBy('id','desc')->limit($offset)->get();
            $event_cnt = Event::orderBy('id','desc')->count();
        }

		
		$today = date("Y-m-d H:i:s");
		
        $views = view($this->device.'.'.'event.event_list');
		
		$views->events = $events;
		
        $views->today = $today;
        
        $views->banner = $this->banner;

        $views->title = '이벤트';

        if($this->device == 'mobile'){
            $views->event_cnt = $event_cnt;
            $views->offset = count($events);
        }  
        
        if(Auth::check()){
            $user = Auth::user();
            $notice_count = Notice::count();
            $faq_count = Faq::count();
            $event_count = Event::count();

            $views->newnotice_cnt = $notice_count - $user->count_newnotice;
            $views->newfaq_cnt = $faq_count - $user->count_newfaq;
            $views->newevent_cnt = $event_count - $user->count_newevent;

            if($event_count != $user->count_newevent){
                User::where('id',$user->id)->update([
                    'count_newevent' => $event_count
                ]);
            }
            $views->user = $user;
        }
        
		return $views;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$event = Event::where('id',$id)->first();
		
        $views = view($this->device.'.'.'event.event_show');
		
		$today = date("Y-m-d H:i:s");
		
		$views->today = $today;
		
        $views->event = $event;
        
        $views->banner = $this->banner;

        $views->title = '이벤트';
        
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
