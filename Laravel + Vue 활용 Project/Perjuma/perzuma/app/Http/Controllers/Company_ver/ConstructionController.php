<?php

namespace App\Http\Controllers\company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class ConstructionController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function company_construction(){
        $views = view('company_ver.company_construction.company_construction');
        $views->title = '퍼주마 중식당';
        $views->updated_at = '2019.06.15.30 Updated';
        return $views;
    }
    public function company_const_manage(Request $request){
        $views = view('company_ver.company_construction.company_const_manage');
        $views->title = '시공현황 관리';
        return $views;
    }
}
