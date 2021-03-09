<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Jenssegers\Agent\Agent; 

use App;

class MyAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('secure', function()
        {
            return new \App\Classes\Secure;
        });
        
        App::bind('coin_info', function()
        {
            return new \App\Classes\Coin_info;
        });

        App::bind('my_info', function()
        {
            return new \App\Classes\My_info;
        });
        
        App::bind('settings', function()
        {
            return new \App\Classes\Settings;
        });

        App::bind('trade', function()
        {
            return new \App\Classes\Trade;
        });

        App::bind('common', function()
        {
            return new \App\Classes\Common;
        });

        App::bind('admin_facade', function()
        {
            return new \App\Classes\Admin;
        });

        App::bind('nexmo_sms', function()
        {
            return new \App\Classes\Nexmo_sms;
        });

        App::bind('visitor', function()
        {
            return new \App\Classes\Visitor;
        });
		App::bind('airdrop', function()
        {
            return new \App\Classes\AirDrop;
        });        
        App::bind('directsend_mail', function()
        {
            return new \App\Classes\Directsend_mail;
        });
		
		App::bind('trade_new', function()
        {
            return new \App\Classes\Trade_new;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
