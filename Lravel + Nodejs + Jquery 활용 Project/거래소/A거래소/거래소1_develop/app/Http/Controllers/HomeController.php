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
        /*if(config('app.country') !== null){
            $country = config('app.country');
            info('test');
        }else{
            $country = 'kr';
            info('test2');
        }*/
        if($this->device === 'pc'){
            $today = date("Y-m-d");
            $notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created', 'desc')->limit(6)->get();
            $news_lists = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->where('thumb_img', '<>', null)->orderBy('created', 'desc')->limit(10)->get();
            $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('id', 'asc')->get();
            $events = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->get();
            $youtube_active = DB::connection('mysql_sub')->table('youtube')->where('active',1)->first();
            $youtube_list = DB::connection('mysql_sub')->table('youtube')->get();
            $game_schedules = DB::connection('mysql_sub')->table('game_schedule')->limit(7)->get();
            $yoil = array("일","월","화","수","목","금","토");
            $yoil_name = $yoil[date('w', strtotime($today))];
            
            $football_count = 0;
            $basketball_count = 0;
            $baseball_count = 0;

            $all_schedules = array();
            if(count($game_schedules) === 0){
                $schedule_lists = array();
            }else{
                foreach($game_schedules as $game_schedule){
                    $schedule_lists = json_decode($game_schedule->schedule_lists, false);
                    if($schedule_lists === null){
                        $schedule_lists = array();
                    }else{
                        if(isset($schedule_lists->game_type)){
                            if($schedule_lists->game_type == '축구'){
                                $football_count++;
                            }else if($schedule_lists->game_type == '농구'){
                                $basketball_count++;
                            }else if($schedule_lists->game_type == '야구'){
                                $baseball_count++;
                            }else{
                                $football_count++;
                            }
                        }else{
                            $football_count++;
                        }
                    }
                    
                    array_push($all_schedules, $schedule_lists);
                }
            }

            //dd($game_schedules);
            
            

            $sports_coins = DB::table('btc_coins')->select('api','symbol','market','decimal_krw','24h_volume_krw','last_trade_price_krw','price_change_24h_krw','percent_change_24h_krw','image')->where('active', 1)->where('cointype', '<>', 'cash')->where('market', 'sports')->orderBy('id', 'asc')->get();
            $public_coins = DB::table('btc_coins')->select('api','symbol','market','decimal_krw','24h_volume_krw','last_trade_price_krw','price_change_24h_krw','percent_change_24h_krw','image')->where('active', 1)->where('cointype', '<>', 'cash')->where('market', 'public')->orderBy('id', 'asc')->get();

            $views = view(session('theme').'.'.$this->device.'.'.'home');
            $views->notices = $notices;
            $views->news_lists = $news_lists;
            $views->coins = $coins;
            $views->sports_coins = $sports_coins;
            $views->public_coins = $public_coins;
            $views->events = $events;
            $views->youtube_active = $youtube_active;
            $views->youtube_list = $youtube_list;
            $views->game_schedules = $game_schedules;
            $views->yoil = $yoil;
            $views->all_schedules = $all_schedules;
            $views->football_count = $football_count;
            $views->basketball_count = $basketball_count;
            $views->baseball_count = $baseball_count;
            //$views->schedule_lists = $schedule_lists;
            $views->today = $today;
            $views->country = config('app.country');
            return $views;
        }else{
            $today = date("Y-m-d");
            $notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created', 'desc')->limit(6)->get();
            $news_lists = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->where('thumb_img', '<>', null)->orderBy('created', 'desc')->limit(10)->get();
            $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('id', 'asc')->get();
            $events = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->get();
            $youtube_active = DB::connection('mysql_sub')->table('youtube')->where('active',1)->first();
            $youtube_list = DB::connection('mysql_sub')->table('youtube')->get();
            $game_schedules = DB::connection('mysql_sub')->table('game_schedule')->limit(7)->get();
            $yoil = array("일","월","화","수","목","금","토");
            $yoil_name = $yoil[date('w', strtotime($today))];

            $football_count = 0;
            $basketball_count = 0;
            $baseball_count = 0;

            $all_schedules = array();
            if(count($game_schedules) === 0){
                $schedule_lists = array();
            }else{
                foreach($game_schedules as $game_schedule){
                    $schedule_lists = json_decode($game_schedule->schedule_lists, false);
                    if($schedule_lists === null){
                        $schedule_lists = array();
                    }else{
                        if(isset($schedule_lists->game_type)){
                            if($schedule_lists->game_type == '축구'){
                                $football_count++;
                            }else if($schedule_lists->game_type == '농구'){
                                $basketball_count++;
                            }else if($schedule_lists->game_type == '야구'){
                                $baseball_count++;
                            }else{
                                $football_count++;
                            }
                        }else{
                            $football_count++;
                        }
                    }
                    array_push($all_schedules, $schedule_lists);
                }
            }

            $sports_coins = DB::table('btc_coins')->select('api','symbol','market','decimal_krw','24h_volume_krw','last_trade_price_krw','price_change_24h_krw','percent_change_24h_krw','image')->where('active', 1)->where('cointype', '<>', 'cash')->where('market', 'sports')->orderBy('id', 'asc')->get();
            $public_coins = DB::table('btc_coins')->select('api','symbol','market','decimal_krw','24h_volume_krw','last_trade_price_krw','price_change_24h_krw','percent_change_24h_krw','image')->where('active', 1)->where('cointype', '<>', 'cash')->where('market', 'public')->orderBy('id', 'asc')->get();

            $views = view(session('theme').'.'.$this->device.'.'.'home');
            
            $views->notices = $notices;
            $views->news_lists = $news_lists;
            $views->coins = $coins;
            $views->sports_coins = $sports_coins;
            $views->public_coins = $public_coins;
            $views->events = $events;
            $views->youtube_active = $youtube_active;
            $views->youtube_list = $youtube_list;
            $views->game_schedules = $game_schedules;
            $views->yoil = $yoil;
            $views->all_schedules = $all_schedules;
            $views->football_count = $football_count;
            $views->basketball_count = $basketball_count;
            $views->baseball_count = $baseball_count;
            $views->today = $today;
            $views->country = config('app.country');
            return $views;
            return $views;
        }
    }

    public function mobile_main(Request $request){
        $notices = DB::connection('mysql_sub')->table('btc_notice_'.config('app.country'))->orderBy('created', 'desc')->limit(6)->get();
        $news_lists = DB::connection('mysql_sub')->table('btc_news_'.config('app.country'))->where('thumb_img', '<>', null)->orderBy('created', 'desc')->limit(10)->get();
        $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('id', 'asc')->get();
        $events = DB::connection('mysql_sub')->table('btc_events')->where('lang', config('app.country'))->where('active', 1)->get();

        $sports_coins = DB::table('btc_coins')->select('api','symbol','market','decimal_krw','24h_volume_krw','last_trade_price_krw','price_change_24h_krw','percent_change_24h_krw','image')->where('active', 1)->where('cointype', '<>', 'cash')->where('market', 'sports')->orderBy('id', 'asc')->get();
        $public_coins = DB::table('btc_coins')->select('api','symbol','market','decimal_krw','24h_volume_krw','last_trade_price_krw','price_change_24h_krw','percent_change_24h_krw','image')->where('active', 1)->where('cointype', '<>', 'cash')->where('market', 'public')->orderBy('id', 'asc')->get();

        $views = view(session('theme').'.'.$this->device.'.'.'home');
        $views->notices = $notices;
        $views->news_lists = $news_lists;
        $views->coins = $coins;
        $views->sports_coins = $sports_coins;
        $views->public_coins = $public_coins;
        $views->events = $events;
        $views->country = config('app.country');
        return $views;
    }

    public function login(Request $request)
    {
        $password = $request->input('password');
        $email = $request->input('email');

        if (Auth::attempt([ 'email' => $email, 'password' => $password ])) {
            LoginTrace::trace($request);
            return redirect()->back();
        } else {
            return redirect()->back()->with('jsAlert', '아이디나 비밀번호가 맞지 않습니다.');
        }
    }

    public function server_test(){
        $views = view(session('theme').'.'.$this->device.'.'.'test.server_test');
        return $views;
    }
}
