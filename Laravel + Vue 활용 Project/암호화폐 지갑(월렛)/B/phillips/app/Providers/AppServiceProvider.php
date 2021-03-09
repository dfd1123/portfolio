<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // https://laravel-news.com/laravel-5-4-key-too-long-error
        Schema::defaultStringLength(191);

        // 로케일 설정
        if(isset($_GET['locale'])) {
            setcookie('locale', $_GET['locale'], time() + (86400 * 30), "/");
            app()->setLocale($_GET['locale']);
        } else {
            if(isset($_COOKIE['locale'])) {
                app()->setLocale($_COOKIE['locale']);
            }
        }
    }
}
