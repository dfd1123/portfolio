<?php

namespace App\Http\Controllers\Company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class SignupController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function company_signup(Request $request, $step){
        $views = view('company_ver.company_signup.step_'.$step);

        $title = '업체 등록';
        $views->ft_btn_name = '가입 완료';
        $views->index = $step;
        $views->title = $title;
        return $views;
    }
}
