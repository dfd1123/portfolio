<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;

use View;
use URL;

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

        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }


        View::composer(['user_ver.layouts.app', 'company_ver.layouts.app', 'auth.passwords.app'], function ($view) {

            $agent = new Agent();

            $device = ($agent->isDesktop()) ? 'pc' : 'mobile';

            $main_hd = array(
                "",
                "home",
            );

            $url = explode('?',$_SERVER['REQUEST_URI']);
            $pagename = explode('/',$url[0]);
            
            if(count($pagename) == 2){
                if($pagename[1] == 'login' || $pagename[1] == 'register'){
                    $pagename[2] = '-';
                }else{
                    $pagename[2] = '';
                }
            }

            $view->device = $device;
            $view->main_hd = $main_hd;
            $view->pagename = $pagename;
            $view->main_hd = $main_hd;

            return $view;


        });
    }
}
