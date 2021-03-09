<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 
use App\Schedule\Lock_coin;
use App\Schedule\Auto_trade;

class TestController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }

    public function test(){
    	//$phpinfo = phpinfo();
        $views = view(session('theme').'.'.$this->device.'.'.'test.server_test');
		//$views->phpinfo = $phpinfo;
        return $views;
    }
}
