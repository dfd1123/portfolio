<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent; 


use DB;
use Auth;
use View;
use Cache;
use Session;
use APP;
use Cookie;
use Visitor;
use My_info;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
            $protocol = 'https://';
            if (\App::environment(['local'])) {
                $protocol = 'http://';
            }
            
            $url = $protocol.$_SERVER["HTTP_HOST"]."/";
            //Visitor::set();
        }else{
            $url = env('APP_URL')."/";
        }
        if(isset($_SERVER['REQUEST_URI'])){
            $query_string=getenv("QUERY_STRING");
            $uri = $_SERVER['REQUEST_URI'];
            $uri = str_replace($query_string,'',$uri);
            $uri = str_replace('?','',$uri);
            $uri = explode('/',$uri);
        }else{
            $query_string=getenv("QUERY_STRING");
            $uri = '/compile';
            $uri = str_replace($query_string,'',$uri);
            $uri = str_replace('?','',$uri);
            $uri = explode('/',$uri);
        }

        if($uri[1] == ''){
            if(!isset($_GET['country'])){
                if(!isset($_COOKIE['locale']) || !isset($_COOKIE['timezone'])){
                    $country = 'kr';
                    $timezone = 'Asia/Seoul';
                    setcookie('locale',$country, time() + (86400 * 30), "/");
                    setcookie('timezone',$timezone, time() + (86400 * 30), "/");
                    config(['app.country' => $country]);
                    config(['app.cus_timezone' => $timezone]);
                    config(['app.locale' => $country]);
                    \App::setLocale($country);
                }else{
                    $country = $_COOKIE['locale'];
                    $timezone = $_COOKIE['timezone'];
                    setcookie('locale',$country, time() + (86400 * 30), "/");
                    setcookie('timezone',$timezone, time() + (86400 * 30), "/");
                    config(['app.country' => $country]);
                    config(['app.cus_timezone' => $timezone]);
                    config(['app.locale' => $country]);
                    \App::setLocale($country);
                }
            }else{
                $country = $_GET['country'];
                setcookie('locale',$country, time() + (86400 * 30), "/");
                if($country == 'kr' || $country == 'jp' || $country == 'ch' || $country == 'th'){
                        if($country == 'kr'){
                            $timezone = 'Asia/Seoul';
                        }else if($country == 'jp'){
                            $timezone = 'Asia/Tokyo';
                        }else if($country == 'ch'){
                            $timezone = 'Asia/Shanghai';
                        }else if($country == 'th'){
                            $timezone = 'Asia/Bangkok';
                        }

                        setcookie('timezone',$timezone, time() + (86400 * 30), "/");
                        
                        $country = $country;
                        config(['app.country' => $country]);
                        config(['app.cus_timezone' => $timezone]);
                        config(['app.locale' => $country]);
                        \App::setLocale($country);
                }else{
                        $country = 'en';
						$timezone = 'America/Chicago';
						setcookie('timezone',$timezone, time() + (86400 * 30), "/");
						
                        config(['app.country' => $country]);
                        config(['app.cus_timezone' => $timezone]);
                        config(['app.locale' => $country]);
                        \App::setLocale($country);
                }
            }
        }else{

            $value = Cookie::get('locale');

            if($value == NULL){
                setcookie('locale','kr', time() + (86400 * 30), "/");
            }

            config(['app.country' => Cookie::get('locale')]);
            config(['app.cus_timezone' => Cookie::get('timezone')]);
            config(['app.locale' => Cookie::get('locale')]);


        }

        // info("url: " . $url);
        
        //$market = DB::table('btc_settings')->where('url',$url)->first();

        Session::put('theme', 'theme.basic');
        Session::put('market_type', 1);
        
        \App::setLocale(config('app.country'));
        
        View::composer([ session('theme').'.pc.layouts.app', session('theme').'.mobile.layouts.app'], function ($view) {
            
            $agent = new Agent();
            
            $theme = str_replace(".", "/", session('theme')).'/';
            
            $device = ($agent->isDesktop()) ? 'pc' : 'mobile';
            
            $pagename = explode('/',$_SERVER['REQUEST_URI']);

            $query_string=getenv("QUERY_STRING");
            $pagename = $_SERVER['REQUEST_URI'];
            $pagename = str_replace($query_string,'',$pagename);
            $pagename = str_replace('?','',$pagename);
            $pagename = explode('/',$pagename);

            /*
            if($pagename[1] == ''){
                //$popups = DB::connection('mysql_sub')->table('btc_popup_'.config('app.country'))->whereDate('start_time','<=',date("Y-m-d"))->whereDate('end_time','>=',date("Y-m-d"))->orderBy('updated_at','desc')->get();
                
                $view->popups = $popups;
            }else{
                $view->popups = array();
            }
            if(Auth::check()){
                if($device == 'mobile'){
                    //$coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get();
        
                    $total_holding = 0; // 총 보유자산
                    foreach($coins as $coin){
                        $coin_balance = My_info::get_user_available_balance_allcoin(Auth::user()->id, $coin->api); //코인별 보유잔액

                        $coin_balance_price = bcmul($coin_balance, $coin->price_usd,8); // 코인 보유 양 * 해당코인 가격 = 평가금액
                        
                        $total_holding = bcadd($total_holding,$coin_balance_price,8); // 총 보유자산
                        
                    }
                    $view->total_holding = $total_holding;
                }
            }
            */
            
            $view->path = $theme.$device;
            
            $view->device = $device;
            
            $view->pagename = $pagename;

            
            $view->common_path = 'common/'.$device;
            
            return $view;
            
        });
    }
}
