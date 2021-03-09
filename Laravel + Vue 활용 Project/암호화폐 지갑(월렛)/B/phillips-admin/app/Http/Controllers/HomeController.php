<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Facades\App\Classes\LoginTrace;

use Carbon;
use Auth;
use Secure;
use DB;
use App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        //$this->middleware('auth');
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created','desc')->limit(6)->get();
        $coins = DB::table('btc_coins')->where('active',1)->where('cointype','<>','cash')->whereRaw("api not in ('divi')")->orderBy('last_trade_price_usd','desc')->get();
        $events = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->get();

        $views = view(session('theme').'.'.$this->device.'.'.'home');
        $views->notices = $notices;
        $views->coins = $coins;
        $views->events = $events;
        $views->country = config('app.country');
        return $views;
    }
    
    public function login(Request $request) {
        $password = $request->input('password');
            $email = $request->input('email');
            
        if(Auth::attempt([ 'email' => $email, 'password' => $password ])){
            LoginTrace::trace($request);
            return redirect()->back();
        }else{
            return redirect()->back()->with('jsAlert','아이디나 비밀번호가 맞지 않습니다.');
        }
    }
}
