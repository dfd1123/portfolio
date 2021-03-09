<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
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

    public function index()
    {
        return 'User API
        LIST : ~ 
        wjrjf : ~';
    }

    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            case 'list':
            
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }

                $sql =   "SELECT log_no
                ,user_no
                ,log_type
                ,log_msg
                ,reg_dt
                FROM  system_log
                ORDER BY log_no DESC
                OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $p, 'select');
            break;
        

         default:
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 0';
         break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function store(Request $request)
    {
            if ($request->filled('user_no','log_type', 'log_msg') 
            && ((int)$p['user_no']) >0
            && ((int)$p['log_type']) >= 0
            && strlen($p['log_msg']) > 0 
            ) {

                $sql = 'INSERT INTO system_log
                    (user_no
                    ,log_type
                    ,log_msg)
                    VALUES (:user_no
                    , :log_type
                    , :log_msg)
                    RETURNING log_no ;';

                $param = array('user_no' => $p['user_no'],
                'log_type' => $p['log_type'],
                'log_msg' => $p['log_msg']
                );

                $this->execute_query($sql, $param, 'select');

                //정상적으로 실행된 경우
                if (count($this->res['query']) >0 &&  $this->res['query'][0]->log_no > 0) {
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.NO_DATA');
                    $this->res['msg'] = '쿼리응답에러';
                }
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : 0 ';
            }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    
    public function update(Request $request, $req)
    {
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
