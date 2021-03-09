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


        // 사용자 언어설정
        if (isset($_GET['country'])) {
            $this->setAppUserCountry($_GET['country']);
        } else {
            if (isset($_COOKIE['locale'])) {
                $this->setAppUserCountry($_COOKIE['locale']);
            } else {
                $this->setAppUserCountry('kr');
            }
        }

        // 사용자 마켓 설정
        $market = DB::table('btc_settings')->where('url', 'https://admin.trustorn.com/')->first();
        if ($market == null) {
            return abort(403);
        }

        $market = DB::table('btc_settings')->where('url', 'https://admin.trustorn.com/')->first();

        Session::put('theme', 'theme.'.$market->theme);
        Session::put('market_type', $market->id);

        App::setLocale(config('app.country'));

        View::composer([ session('theme').'.pc.layouts.app', session('theme').'.mobile.layouts.app'], function ($view) {
            $agent = new Agent();

            $theme = str_replace(".", "/", session('theme')).'/';

            $device = ($agent->isDesktop()) ? 'pc' : 'mobile';

            $pagename = explode('/', $_SERVER['REQUEST_URI']);

            $query_string=getenv("QUERY_STRING");
            $pagename = $_SERVER['REQUEST_URI'];
            $pagename = str_replace($query_string, '', $pagename);
            $pagename = str_replace('?', '', $pagename);
            $pagename = explode('/', $pagename);

            if ($pagename[1] == '') {
                $popups = DB::connection('mysql_sub')->table('btc_popup_'.config('app.country'))->whereDate('start_time', '<=', date("Y-m-d"))->whereDate('end_time', '>=', date("Y-m-d"))->orderBy('updated_at', 'desc')->get();

                $view->popups = $popups;
            } else {
                $view->popups = array();
            }
            if (Auth::check()) {
                if ($device == 'mobile') {
                    $coins = DB::table('btc_coins')->where('active', 1)->orderBy('market', 'asc')->get();

                    $total_holding = 0; // 총 보유자산
                    foreach ($coins as $coin) {
                        $coin_balance = My_info::get_user_available_balance_allcoin(Auth::user()->id, $coin->api); //코인별 보유잔액

                        $coin_balance_price = bcmul($coin_balance, $coin->price_usd, 8); // 코인 보유 양 * 해당코인 가격 = 평가금액

                        $total_holding = bcadd($total_holding, $coin_balance_price, 8); // 총 보유자산
                    }
                    $view->total_holding = $total_holding;
                }
            }

            $view->path = $theme.$device;

            $view->device = $device;

            $view->pagename = $pagename;


            $view->common_path = 'common/'.$device;

            return $view;
        });
    }

    private function setAppUserCountry($country)
    {
        $timezones = [
            'kr' => 'Asia/Seoul',
            'jp' => 'Asia/Tokyo',
            'en' => 'America/Chicago',
            'ch' => 'Asia/Shanghai',
            'th' => 'Asia/Bangkok'
        ];

        setcookie('locale', $country, time() + (86400 * 30), "/");
        setcookie('timezone', $timezones[$country], time() + (86400 * 30), "/");

        config(['app.country' => $country]);
        config(['app.locale' => $country]);
        config(['app.cus_timezone' => $timezones[$country]]);

        app()->setLocale($country);
    }
}
