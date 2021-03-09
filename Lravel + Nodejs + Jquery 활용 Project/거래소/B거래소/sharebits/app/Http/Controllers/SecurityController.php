<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

class SecurityController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
    
    public function index()
    {
        $this->middleware('auth');
        
        $views = view(session('theme').'.'.$this->device.'.'.'security_lv.security_lv');
        
        return $views;
    }
}
