<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

class OtpController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
	public function index(){
		$views = view(session('theme').'.'.$this->device.'.'.'otp.otp');
		return $views;
	}
}
