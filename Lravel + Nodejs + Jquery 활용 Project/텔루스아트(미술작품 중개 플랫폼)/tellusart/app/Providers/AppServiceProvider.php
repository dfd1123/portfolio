<?php

namespace TLCfund\Providers;

use Jenssegers\Agent\Agent;

use Illuminate\Support\ServiceProvider;
use TLCfund\User;
use TLCfund\Cart;
use TLCfund\Company;
use TLCfund\Howtouse;
use TLCfund\Address;
use TLCfund\Address_charge;
use TLCfund\Banner;
use TLCfund\Batting;
use TLCfund\Order;
use TLCfund\Notice;
use TLCfund\Faq;
use TLCfund\Event;
use Facades\App\Classes\EthApi;
use Auth;
use View;
use Cache;
use Log;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		View::composer(['pc.layouts.app', 'mobile.layouts.app','pc.notice.notice_list','pc.faq.faq_list','pc.event.event_list'], function ($view) {
			
			$company = Company::first();
			$howtouse = Howtouse::first();
			$agent = new Agent();

			$device = ($agent->isDesktop()) ? 'pc' : 'mobile';

			$pagename = explode('/',$_SERVER['REQUEST_URI']);

			if($pagename[1] == '' || $pagename[1] == 'home'){
				$main = 1;
			}else{
				$main = 0;
			}
			
			if($pagename[1] == ''){
					$popups = DB::table('tlca_popup')->whereDate('start_time','<=',date("Y-m-d"))->whereDate('end_time','>=',date("Y-m-d"))->orderBy('updated_at','desc')->get();
					
					$view->popups = $popups;
			}else{
					$view->popups = array();
			}

			if(!Auth::check()) {
				$view->count1 = Notice::where('created_at','>',date("Y.m.d", strtotime("-2 days")))->count();
            	$view->count2 = Faq::where('created_at','>',date("Y.m.d", strtotime("-2 days")))->count();
            	$view->count3 = Event::where('created_at','>',date("Y.m.d", strtotime("-2 days")))->count();
			}else{
				$user = Auth::user();

				$address_info = Address::select('available_balance_tlc as available')->where('user_email', $user->email)->first();
				$coin_balance = floatval($address_info->available);
				$coin_address = EthApi::getAddress($user->email);
				$transactions = EthApi::listTransactions($user->email, 10, 0);
				//$charge_lists = Address_charge::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->limit(10)->get();
				$share_cart_cnt = Cart::where('uid', $user->id)->count();

				if($device == 'mobile'){
					$my_batting_cnt = Batting::where('uid',$user->id)->count();
					$my_order_cnt = Order::where('uid',$user->id)->where('order_state','<>',4)->where('order_state','<>',5)->where('order_cancel','<>',1)->where('order_cancel','<>',2)->count();
					$view->my_batting_cnt = $my_batting_cnt;
					$view->my_order_cnt = $my_order_cnt;
				}
				
				$view->share_cart_cnt = $share_cart_cnt;
				//$view->charge_lists = $charge_lists;
				$view->coin_balance = $coin_balance;
				$view->coin_address = $coin_address;
				$view->transactions = $transactions;				
			}
			
			$view->company = $company;
			$view->howtouse = $howtouse;
			$view->main = $main;
			$view->pagename = $pagename;
			$view->device = $device;
			
			return $view;
			
			
		});
		
		
		
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
