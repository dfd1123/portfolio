<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use TLCfund\Notice;
use TLCfund\Driver;
use TLCfund\Banner;
use TLCfund\User;
use TLCfund\Faq;
use TLCfund\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Cookie;
use File;
use Redirect;
//use DebugBar\DebugBar;

class NoticesController extends Controller
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
        
        $events = Event::orderBy('created_at','desc')->paginate(7);
        $events->withPath('events');
        
		$views = view($this->device.'.'.'notice.notice_list');
		
        $views->notices = $notices;
        
        $views->banner = $this->banner;
        

        if(Auth::check()){
            $user = Auth::user();
            $notice_count = Notice::count();
            $faq_count = Faq::count();
            $event_count = Event::count();

            $views->newnotice_cnt = $notice_count - $user->count_newnotice;
            $views->newfaq_cnt = $faq_count - $user->count_newfaq;
            $views->newevent_cnt = $event_count - $user->count_newevent;

            if($notice_count != $user->count_newnotice){
                User::where('id',$user->id)->update([
                    'count_newnotice' => $notice_count
                ]);
            }
            $views->user = $user;
        }
        $views->title = '공지사항';

		return $views;
    }

    public function list(Request $request)
    {
        $offset = 10;

        if($this->device == 'pc'){
            if(isset($request->status)){
                $notices = Notice::where('title','like','%'.$request->search.'%')->orWhere('body','like','%'.$request->search.'%')->orderBy('id','desc')->paginate($offset);
                $notices->withPath('notice');
            }else{
                $notices = Notice::orderBy('id','desc')->paginate($offset);
                $notices->withPath('notice');
            }
        }else{
            $notices = Notice::orderBy('id','desc')->orderBy('id','desc')->limit($offset)->get();
            $notice_cnt = Notice::orderBy('id','desc')->count();
        }
        
        $faqs = Faq::orderBy('id','desc')->paginate(15);
		$faqs->withPath('faq');
        
        $events = Event::orderBy('id','desc')->paginate(7);
        $events->withPath('events');
		
		$views = view($this->device.'.'.'notice.notice_list');

        $views->notices = $notices;

        $views->banner = $this->banner;
        $views->offset = $offset;
        $views->title = '공지사항';

        if($this->device == 'mobile'){
            $views->notice_cnt = $notice_cnt;
        }

        if(Auth::check()){
            $user = Auth::user();
            $notice_count = Notice::count();
            $faq_count = Faq::count();
            $event_count = Event::count();

            $views->newnotice_cnt = $notice_count - $user->count_newnotice;
            $views->newfaq_cnt = $faq_count - $user->count_newfaq;
            $views->newevent_cnt = $event_count - $user->count_newevent;

            if($notice_count != $user->count_newnotice){
                User::where('id',$user->id)->update([
                    'count_newnotice' => $notice_count
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
        $views = view($this->device.'.'.'notice.notice_create');

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
		
		
		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$path = $request->file1->store('images');
			}
		}
		
        Notice::create([
        	'title' => $request->input('title'),
        	'body' => $request->input('body'),
        	'file1' => $path,
        	'hit' => 0,
        ]);
		
		return Redirect::route('notices.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$value = Cookie::get('notice');
		
		//dd($value);
		
		if($value == NULL){
			setcookie('notice',$id, time() + (86400 * 30), "/");
			//dd($_COOKIE['notice']);
			Notice::where('id',$id)->increment('hit');
		}
		
    	$notice = Notice::where('id',$id)->first();
	
		
        $views = view($this->device.'.'.'notice.notice_show')->with('notice',$notice);

        $views->banner = $this->banner;
        $views->title = '공지사항';
        
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
    	$notice = Notice::where('id',$id)->first();
		
		$views = view($this->device.'.'.'notice.notice_eidt');
        $views->notice = $notice;
        
        $views->banner = $this->banner;
		 
        return $views;
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
        $input = $request->all();
		$path = NULL;
		
		
		if($request->hasFile('file1')){
			if ($request->file('file1')->isValid()) {
				$path = $request->file1->store('images');
			}
		}
		
		
		$asdasd=Notice::where('id',$id)->update([
			'title' => $request->input('title'),
			'body' => $request->input('body'),
			'file1' => $path,
		]);
		
        
		return Redirect::route('notices.show',$id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$notices = Notice::get();
		
        Notice::where('id',$id)->delete();
		
		return Redirect::route('notices.index');
    }
	
	public function delete($id)
    {
        $del_notice=Notice::where('id',$id)->first();//delete();
        
        //dd($del_notice->file1);
        
        $img_path = '../storage/app/'.$del_notice->file1;
        
		if(File::exists($img_path)) {
		    File::delete($img_path);
		}else{
			
		}
		
		Notice::where('id',$id)->delete();
		
		return Redirect::route('notices.index');
    }
	
	
}
