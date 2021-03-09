<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
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
                $sql =   "SELECT notice_no
                ,notice_title
                ,notice_content
                ,reg_dt::date
                ,view_cnt
                FROM  bbs_notice
                ORDER BY notice_no DESC
                OFFSET :offset LIMIT 10;";
                $this->res = $this->execute_query($sql, $p, 'select');
            break;
            case 'search':
                if ($request->filled('notice_title') && strlen($request->input('notice_title')) >0) {
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params = array('notice_title'=>
                    '%'.$p['notice_title'].'%',
                    'offset'=>$p['offset'] );
                    $sql =   "SELECT notice_no
                    ,notice_title
                    ,notice_content
                    ,reg_dt::date
                    FROM  bbs_notice
                    WHERE notice_title like :notice_title
                    ORDER BY notice_no DESC
                    OFFSET :offset LIMIT 10;";
                    $this->res = $this->execute_query($sql, $params, 'select');
                }
                else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;
            case 'detail':
                if($request->filled('notice_no') && ((int)$request->input('notice_no')) > 0)
                {
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] =0;
                    }
                    $sql =   "SELECT notice_no
                    ,notice_title
                    ,notice_content
                    ,reg_dt::date
                    ,view_cnt
                    FROM  bbs_notice
                    WHERE notice_no = :notice_no;";
                    $this->res = $this->execute_query($sql, array('notice_no'=>$p['notice_no']), 'select');
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
        if ($request->filled('notice_title', 'notice_content')
        && (strlen($p['notice_title'])>2)
        && (strlen($p['notice_content'])>4)
       ) {
            $sql = 'INSERT INTO bbs_notice
        (notice_title
        ,notice_content)
        VALUES (:notice_title
        , :notice_content)
        RETURNING notice_no ;';

            $param = array('notice_title' => $p['notice_title'],
            'notice_content' => $p['notice_content']);

            $this->execute_query($sql, $param, 'select');

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->notice_no > 0) {
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
    public function update(Request $request,$req){
        $p = $request->all();
        switch($req){
            case 'notice':
                if (!$request->filled('notice_title', 'notice_content', 'notice_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE bbs_notice SET notice_title = :notice_title
                , notice_content = :notice_content
                WHERE notice_no = :notice_no;";

                $param = array('notice_title'=>$p['notice_title']
                , 'notice_content'=>$p['notice_content']
                , 'notice_no'=>$p['notice_no']);
                $this->execute_query($sql, $param, 'update');
            break;
            case 'viewadd':
                if (!$request->filled('notice_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE bbs_notice SET view_cnt = (view_cnt + 1)
                WHERE notice_no = :notice_no;";

                $param = array('notice_no'=>$p['notice_no']);
                $this->execute_query($sql, $param, 'update');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function destroy(Request $request){
        $p = $request->all();
        if (!$request->filled('notice_no')) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수없음 - CODE : 1';
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $sql = "DELETE FROM bbs_notice WHERE notice_no = :notice_no;";
        $param = array('notice_no'=>$p['notice_no']);
        $this->execute_query($sql, $param, 'delete');

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
