<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Carbon;
use Auth;
use Secure;
use DB;

class StoreController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }


    public function index(){
        $views = view(session('theme').'.'.$this->device.'.store.'.'intro');

        return $views;
    }
}
