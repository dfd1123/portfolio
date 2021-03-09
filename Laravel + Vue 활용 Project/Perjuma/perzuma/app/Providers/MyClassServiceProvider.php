<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App;

class MyClassServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('test', function()
        {
            return new \App\Classes\Test;
        });

        App::bind('test2', function()
        {
            return new \App\Classes\Test2;
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
