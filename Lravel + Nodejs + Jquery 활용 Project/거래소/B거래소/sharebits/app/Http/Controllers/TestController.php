<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

class TestController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }

    public function test(){
        $views = view(session('theme').'.'.$this->device.'.'.'test.server_test');
        return $views;
    }
}
