<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Facades\App\Classes\Secure;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function payment()
    {
        $company_info = DB::table('btc_payment_company')->where('uid',Auth::user()->id)->first();
        if(isset($company_info)){
            $company_confirm = $company_info->company_confirm;
        }else{
            $company_confirm = 0;
        }
        
        if($company_confirm != 1){
            return redirect('company')->with('jsAlert','사업자등록이 필요한 서비스입니다. 작성해주세요. ');
        }

    	$coins = DB::table('btc_coins')->where('active',1)->where('cointype','<>','cash')->where('market','major')->get();
		
		$views = view('payment.payment');
		
		$views->coins = $coins;
		
        return $views;
    }
	
	public function payment_history()
    {   
        $company_info = DB::table('btc_payment_company')->where('uid',Auth::user()->id)->first();
        if(isset($company_info)){
            $company_confirm = $company_info->company_confirm;
        }else{
            $company_confirm = 0;
        }

        if($company_confirm != 1){
            return redirect('company')->with('jsAlert','사업자등록이 필요한 서비스입니다. 작성해주세요.');
        }
        $historys = DB::table('btc_payment')->where('uid',Auth::user()->id)->where(function($query){
            $query->where('status','complete')->orwhere('status','calculate')->orwhere('status','refund');
        })->orderBy('created_dt','desc')->paginate(10);
        $historys->withPath('payment_history');

        $views = view('payment.payment_history');

        $views->historys = $historys;

        return $views;
    }
	
}
