<?php

namespace App\Http\Controllers\company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;
//차후 페이지 디폴트 컨트롤러에서
//Facade사용하여 CRUD 해결해볼까..  -KJS
class MypageController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    //디폴트페이지 로딩
    //메뉴구성
    /**
     * 정산
     * 이력등록
     * 업체정보수정
     *  */
    public function index()
    {
        //마이페이지 메인
        $view = view('company_ver.company_mypage.mypage');
        $view->title = '마이페이지';

        return $view;
    }


    //sub페이지 default 로딩해서 뿌려주기
    public function show(Request $request,$subpage){

        $view = view('company_ver.company_mypage.mypage');
        $view->title = '마이페이지';
        switch($subpage){
            //정산
            default:
            case 'balance':
            $view->title = '정산';
            break;
            //이력등록
            case 'trdhistroy':
            $view->title = '시공이력';
            break;
            //정보수정
            case 'edit':
            $view->title = '정보수정';
            break;
        }
        return $view;
    }

}
