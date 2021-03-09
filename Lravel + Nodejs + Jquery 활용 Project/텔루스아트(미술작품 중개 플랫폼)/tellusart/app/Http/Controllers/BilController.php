<?php

namespace TLCfund\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent;
use Facades\App\Classes\SweetTracker;

use TLCfund\User;
use TLCfund\Product;
use TLCfund\Deposit_pay;
use TLCfund\Order;
use TLCfund\Delivery;
use TLCfund\Company;
use Auth;

class BilController extends Controller
{
    public function __construct()
    {
		$this->middleware('auth');
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function show($order_id){
        
        $order = Order::where('id',$order_id)->with('user')->with('seller')->with('delivery')->with('product')->first();

        $company = Company::first();
        if($order->uid != Auth::id()){
            return redirect()->route('mypage.my_order_list');
        }

        if($order->delivery->delivery_company != NULL){
            $delivery_company = SweetTracker::companyname($order->delivery->delivery_company);
        }else{
            $delivery_company = '-';
        }

        $views = view($this->device.'.'.'bil.order_bil');

        $views->title = '주문서 확인';

        $views->order = $order;
        $views->delivery_company = $delivery_company;
        $views->company = $company;
        return $views;
    }
}
