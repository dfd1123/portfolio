<?php

namespace App\Http\Controllers\Company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class RegistController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function company_regist(Request $request,$step){
        $views = view('company_ver.company_regist.step_'.$step);

        $title = '업체 등록';
        if(isset($request->id)){
            $id = $request->id;
            $views->estimate_id = $id;
        }
        $views->ft_btn_name = '선택 완료';
        if($step==4){
            $views->ft_btn_name = '다음';
        }
        else if($step==5){
            $views->ft_btn_name = '최종 등록';
        }
        else if($step==6){
            $title = '업체정보 등록 완료';
        }

        $views->index = $step;
        $views->title = $title;
        return $views;
    }
}
