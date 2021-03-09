<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 


class OtpController extends Controller
{
     public function __construct()
    {
        //$this->middleware('auth');
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
	public function index(Request $request){
        
        $user = session()->get('request');
        $views = view(session('theme').'.'.$this->device.'.'.'otp.otp');
        $views->user = $user;
		return $views;
    }
    
}
