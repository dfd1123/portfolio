<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use TLCfund\Banner;
use TLCfund\User;
use TLCfund\Notice;
use TLCfund\Event;

use Illuminate\Http\Request;
use TLCfund\Faq;
use Auth;

class FaqController extends Controller
{

    public function __construct()
    {
		$agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
        $this->banner = Banner::where('bn_alt','FAQ')->first();
    }
    
    public function index()
    {
    	$notices = Notice::orderBy('created_at','desc')->paginate(10);
        $notices->withPath('notices');
        
        $faqs = Faq::orderBy('created_at','desc')->paginate(15);
		$faqs->withPath('faq');
        
        $events = Event::orderBy('created_at','desc')->paginate(7);
        $events->withPath('events');
		
        $views = view($this->device.'.'.'faq.faq_list');
		
        $views->faqs = $faqs;
        
        $views->banner = $this->banner;

        $views->title = '고객센터';

        if(Auth::check()){
            $user = Auth::user();
            $notice_count = Notice::count();
            $faq_count = Faq::count();
            $event_count = Event::count();

            $views->newnotice_cnt = $notice_count - $user->count_newnotice;
            $views->newfaq_cnt = $faq_count - $user->count_newfaq;
            $views->newevent_cnt = $event_count - $user->count_newevent;

            if($faq_count != $user->count_newfaq){
                User::where('id',$user->id)->update([
                    'count_newfaq' => $faq_count
                ]);
            }
            $views->user = $user;
        }
		return $views;
    }
}
