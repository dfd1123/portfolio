<?php

namespace App\Http\Controllers\company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function company_login(Request $request){
        $views = view('company_ver.company_login.company_login');

        return $views;
    }
}
