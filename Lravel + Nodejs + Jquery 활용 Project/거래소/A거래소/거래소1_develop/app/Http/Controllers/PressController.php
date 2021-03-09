<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Carbon;
use Auth;
use Secure;
use DB;

class PressController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }

    public function index(){
        $views = view(session('theme').'.'.$this->device.'.press.press_list');

        return $views;
    }

    public function show($id){
        $views = view(session('theme').'.'.$this->device.'.press.press_view');

        return $views;
    }
}
