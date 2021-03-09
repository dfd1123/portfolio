<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class EscrowController extends Controller
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
    public function show(Request $request,$req)
    {
        $p = $request->all();
        switch ($req) {
            case 'list':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    $p['offset'] =0;
                }
                $sql =   "SELECT ecr_no
                ,ctrt_no
                ,client_no
                ,agent_no
                ,reg_dt
                FROM  escrow
                ORDER BY ecr_no DESC
                OFFSET :offset LIMIT 10;";
                $this->res = $this->execute_query($sql, $p, 'select');
            break;
            case 'detail':
                if($request->filled('ecr_no') && ((int)$request->input('ecr_no')) > 0)
                {
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] =0;
                    }
                    $sql =   "SELECT ecr_no
                    ,ecr_extra_info
                    ,ctrt_no
                    ,client_no
                    ,agent_no
                    ,state
                    ,reg_dt
                    FROM  bbs_notice
                    WHERE ecr_no = :ecr_no;";
                    $this->res = $this->execute_query($sql, array('ecr_no'=>$p['ecr_no']), 'select');
                }
                else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : TYPE ';
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request){
        $p = $request->all();
        if ($request->filled('ctrt_no', 'client_no', 'agent_no')
        && (((int)$p['ctrt_no'])>0)
        && (((int)$p['client_no'])>0)
        && (((int)$p['agent_no'])>0)
       ) {
           if($request->filled('ecr_extra_info')){
           }else{
                $p['ecr_extra_info'] = null;
           }
            $sql = 'INSERT INTO escrow
        (ctrt_no
        ,client_no
        ,agent_no
        ,ecr_extra_info)
        VALUES (:ctrt_no
        , :client_no
        , :agent_no
        , :ecr_extra_info)
        RETURNING ecr_no ;';

            $param = array('ctrt_no' => $p['ctrt_no']
            ,'client_no' => $p['client_no']
            ,'agent_no' => $p['agent_no']
            ,'ecr_extra_info' => $p['ecr_extra_info']
            );

            $this->execute_query($sql, $param, 'select');

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->ecr_no > 0) {
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '쿼리응답에러';
            }
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function update(Request $request){
        $p = $request->all();
                if (!$request->filled('bl_no', 'bl_name', 'bl_thumb')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $sql = "UPDATE business_list SET bl_name = :bl_name
                , bl_thumb = :bl_thumb
                WHERE bl_no = :bl_no;";

                $param = array('bl_name'=>$p['bl_name']
                , 'bl_thumb'=>$p['bl_thumb']
                , 'bl_no'=>$p['bl_no']);
                $this->execute_query($sql, $param, 'update');
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function destroy(Request $request){
        $p = $request->all();
        if (!$request->filled('bl_no')) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수없음 - CODE : 1';
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $sql = "DELETE FROM business_list WHERE bl_no = :bl_no;";
        $param = array('bl_no'=>$p['bl_no']);
        $this->execute_query($sql, $param, 'delete');

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
