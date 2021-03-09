<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'abcd';
    }

    //유저로그인페이지 반환
    public function index()
    {
        return $this->userLoginPage();
    }

    private function userLoginPage()
    {
        return '유저 로그인페이지 반환';
    }
    private function agentLoginPage()
    {
        return '업체 로그인페이지 반환';
    }

    // 요청경로  GET - URL  : api/Login/{req}
    // 분기해서 로그인 페이지 반환
    public function show($req)
    {
        switch ($req) {
            default:
            case 'user':
            return $this->userLoginPage();

            case 'agent':
            return $this->agentLoginPage();
        }
    }

    //유저로긴과 업체로긴으로 나뉨
    //로긴후 입장페이지 다름.
    public function store(Request $request)
    {
        $p = $request->all();
        //차후에 검증, 인증 로직이 추가되어야 할 수 있음으로 user와 agent를 먼저 나눈다.

        //유저가입
        if ($request->filled('type') && $p['type'] ==='user') {
            if ($request->filled('user_email') && strlen($p['user_email']) >8) {
                $this->checkId($p['user_email']);

                //아이디 존재
                if (count($this->res['query']) >= 1) {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.OK');
                    $this->res['msg'] = '중복된 아이디';
                } else {
                  
                }
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : 0 ';
            }
        }
        //업체로긴
        elseif ($request->filled('type') && $p['type'] ==='agent') {
            //유저로긴 보고 처리한뒤 이 주석 지우고.
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE ';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    //계정존재여부확인
    private function checkId($id)
    {
        $sql = "SELECT user_email
        FROM users
        WHERE user_email LIKE  :user_email; ";

        $params = array('user_email'=> $id );
        
        $this->res= $this->execute_query($sql, $params, 'select');
    }
}
