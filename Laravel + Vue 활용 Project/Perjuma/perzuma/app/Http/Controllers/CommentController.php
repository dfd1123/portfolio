<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class CommentController extends Controller
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
                $sql =   "SELECT ucc_no
                ,client_no
                ,agent_no
                ,ucc_title
                ,ucc_comment
                ,ucc_imgs
                ,reg_dt::date
                ,trd_no
                FROM  user_trd_comment
                ORDER BY ucc_no DESC
                OFFSET :offset LIMIT 10;";
                $this->res = $this->execute_query($sql, $p, 'select');
            break;
            case 'trdcomment':
                if ($request->filled('trd_no') && ((int)$request->input('trd_no')) >0) {
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params = array('trd_no'=>$p['trd_no'],
                    'offset'=>$p['offset'] );
                    $sql =   "SELECT ucc_title
                    ,ucc_comment
                    ,user_trd_comment.reg_dt::date
                    FROM  user_trd_comment
                    WHERE trd_no = :trd_no
                    ORDER BY ucc_no DESC
                    OFFSET :offset LIMIT 10;";
                    $this->res = $this->execute_query($sql, $params, 'select');
                }
                else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request){
        $p = $request->all();
        if ($request->filled('title', 'content', 'user_no', 'pwd')
        && (strlen($p['title'])>2  && strlen($p['title']) < 16)
        && (strlen($p['content'])>4)
        && (((int)$p['user_no'])>0)
        && (strlen($p['pwd'])>3)
       ) {
            $sql = 'INSERT INTO bbs
        (title
        ,content
        ,user_no
        ,pwd )
        VALUES (:title
        , :content
        , :user_no 
        , :pwd)
        RETURNING bbs_no ;';

            $param = array('title' => $p['title'],
            'content' => $p['content'],
            'user_no' => $p['user_no'],
            'pwd' => $p['pwd']
            );

            $this->execute_query($sql, $param, 'select');

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->bbs_no > 0) {
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
            case 'userupt':
                if (!$request->filled('title', 'content', 'user_no', 'pwd','bbs_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE bbs SET title = :title
                , content = :content
                WHERE bbs_no = :bbs_no
                AND user_no = :user_no
                AND pwd LIKE :pwd;";

                $param = array('title'=>$p['title']
                , 'content'=>$p['content']
                , 'user_no'=>$p['user_no']
                , 'pwd'=>$p['pwd']
                , 'bbs_no'=>$p['bbs_no']);
                $this->execute_query($sql, $param, 'update');
            break;
            case 'answer':
                if (!$request->filled('ans','bbs_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE bbs SET ans = :ans
                , ans_dt = now()
                WHERE bbs_no = :bbs_no;";

                $param = array('ans'=>$p['ans']
                , 'bbs_no'=>$p['bbs_no']);
                $this->execute_query($sql, $param, 'update');
            break;
            case 'answerudt':
                if (!$request->filled('ans', 'bbs_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE bbs SET ans = :ans
                , ans_dt = now()
                WHERE bbs_no = :bbs_no;";

                $param = array('ans'=>$p['ans']
                , 'bbs_no'=>$p['bbs_no']);
                $this->execute_query($sql, $param, 'update');
            break;

        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function destroy(Request $request){
        //삭제 가능하게 할껀지?
    }
}
