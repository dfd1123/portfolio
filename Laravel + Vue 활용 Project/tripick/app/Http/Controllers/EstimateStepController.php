<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class EstimateStepController extends Controller
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

    // 요청경로  GET - URL  : api/estimate_step/{$req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            case 'step5':
                $params = array();
                if ($request->filled('step_parent', 'step_sort')) {
                    $params['step_parent'] = $p['step_parent'];
                    $params['step_sort'] = $p['step_sort'];
                    $whereClause.= " \n AND step_parent = :step_parent AND step_sort = :step_sort";
                
                    //WHERE절 끝
                    $sql = "
                    SELECT 
                    step_id, 
                    step_title,
                    FROM  estimate_step
                    WHERE 1 = 1 ".$whereClause."
                    ORDER BY step_id ASC;
                    ";

                    $this->res = $this->execute_query($sql, $params);
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
                }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/estimate_step
    public function store(Request $request)
    {
        //관리자만 삽입 가능
        $p = $request->all();

        if ($request->filled('step_title','step_parent','step_sort')) 
        {
            $sql = 'INSERT INTO estimate_step
            (
            step_title,
            step_parent,
            step_sort
            )
            VALUES (
            :step_title,
            :step_parent,
            :step_sort
            )RETURNING step_id;';

            $params = array(
                'step_title' => $p['step_title'],
                'step_parent' => $p['step_parent'],
                'step_sort' => $p['step_sort']
            );

            $this->execute_query($sql, $params);

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->step_id > 0) {
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
    
    // 요청경로  PUT - URL  : api/estimate_step/{$req}
    public function update(Request $request, $req)
    {
    }

    // 요청경로  DELETE - URL  : api/estimate_step/{req}
    public function destroy(Request $request, $req)
    {
        //관리자만 삭제가능
        // 1.토큰 혹은 키로 관리자검증
        //스텝 5만  삭제가능
        switch ($req) {
            case 'step_delete':
            $p = $request->all();
            if (!$request->filled('step_id')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
            } else {
                $sql = "DELETE FROM estimate_step WHERE step_id = :step_id";

                $param = array('step_id'=>$p['step_id']);

                $this->execute_query($sql, $param, 'delete');
            }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
