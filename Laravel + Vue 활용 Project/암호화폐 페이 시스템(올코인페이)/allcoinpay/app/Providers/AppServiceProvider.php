<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use DB;
use Auth;
use View;
use App;
use Session;
use Artisan;
use Facades\App\Classes\Secure;

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
        //
        
        if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
            $protocol = 'https://';
            if (App::environment(['local'])) {
                $protocol = 'http://';
            }
            
            $url = $protocol.$_SERVER["HTTP_HOST"]."/";
            //Visitor::set();
        }else{
            $url = env('APP_URL')."/";
        }
		
        $market = DB::table('btc_settings')->where('url',$url)->first();
		Session::put('market_type', $market->id);
        
        View::composer('layouts.app', function ($view) {
        	if(Auth::check()){
                
	        	$company_info = DB::table('btc_payment_company')->where('uid',Auth::user()->id)->first();
				if(isset($company_info)){
	        		$company_confirm = $company_info->company_confirm;
				}else{
					$company_confirm = 0;
                }
                $security_lv = Secure::secure_short_verified();
			}else{
                $company_confirm = 0;	
                $security_lv = 0;
			}
            $view->company_confirm = $company_confirm;
            $view->security_lv = $security_lv;
			
			return $view;
		});
    }
}
