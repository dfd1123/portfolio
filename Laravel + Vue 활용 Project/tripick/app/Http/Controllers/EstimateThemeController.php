<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class EstimateThemeController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'EstimateTheme controller';
    }

    public function index()
    {
        return 'API FOR USERS';
    }

    // 요청경로  GET - URL  : api/estimate_theme/{$req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            //권한체크 - 로그인한 유저에게만

            //유저가 입찰한 플래너(개인 or 업체)리스트를 보는부분 or 전체 입찰건
            case 'theme':

            //반드시 $param이라는 array 생성후 쿼리에서 사용되는 변수만 넣어줘야함
            //그렇지 않으면 사용하지 않는 column을 서버에 전송할경우 오류페이지가 반환됨.
                $params = array();
                
                //WHERE절 끝
                $sql = "SELECT 
                            theme_id, theme_title
                        FROM  estimate_theme
                        ORDER BY theme_id ASC;
                "; 

                $this->res = $this->execute_query($sql, $params);

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/estimate_theme
    public function store(Request $request)
    {
        //관리자만 삽입 가능
        $p = $request->all();

        if ($request->filled('theme_title')) 
        {
            $sql = 'INSERT INTO 
                        estimate_theme(theme_title)
                    VALUES (:theme_title)RETURNING theme_id;';

            $param = array(
            'theme_title' => $p['theme_title']
            );

            $this->execute_query($sql, $param);

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->theme_id > 0) {
            } else { 
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '쿼리응답에러';
            }
                
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    // 요청경로  PUT - URL  : api/estimate_theme/{$req}
    public function update(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //관리자만 수정가능
            // 1.토큰 혹은 키로 관리자검증
            // 2.쿼리날려서 state수정.
            //테마 제목 수정
            case 'title':
                if (!$request->filled('theme_id') && !$request->filled('theme_title')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $sql = "UPDATE estimate_theme
                SET theme_title = :theme_title
                WHERE theme_id = :theme_id;";

                $params = array('theme_id'=>$p['theme_id'] , 'theme_title'=>$p['theme_title']);

                $this->execute_query($sql, $params, 'update');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  DELETE - URL  : api/estimate_theme
    public function destroy(Request $request, $req)
    {
        switch($req){
            case 'step_delete':
            $p = $request->all();
            if (!$request->filled('theme_id')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
            }else{
                $sql = "DELETE FROM estimate_theme WHERE theme_id = :theme_id;";

                $param = array('theme_id'=>$p['theme_id']);

                $this->execute_query($sql, $param, 'delete');
            }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
