<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
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
                    $p['offset'] =0;
                }


                $sql =   "SELECT rv_no
                ,rv_title
                ,rv_content
                ,rv_imgs
                ,reg_dt
                ,rv_point
                ,client_no
                ,agent_no
                ,ctrt_no
                FROM  review
                WHERE state= 0
                ORDER BY rv_no DESC
                OFFSET :offset LIMIT 10;";


                $this->res = $this->execute_query($sql, $p, 'select');

            break;
            //유저 번호로 조회
            case 'byuser':
                if ($request->filled('client_no') && ((int)$request->input('client_no')) >0) {

                    //오프셋 확인
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params = array('client_no'=>$p['client_no'],
                     'offset'=>$p['offset'] );

                    $sql ="SELECT rv_no
                    ,rv_title
                    ,rv_content
                    ,rv_imgs
                    ,reg_dt
                    ,rv_point
                    ,client_no
                    ,agent_no
                    ,ctrt_no
                    FROM  review
                    WHERE client_no = :client_no
                    ORDER BY rv_no DESC
                    OFFSET :offset LIMIT 10;";

                    $this->res= $this->execute_query($sql, $params, 'select');
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;

            //업체 번호로 조회
            case 'byagnet':
                if ($request->filled('agent_no') && ((int)$request->input('agent_no')) >0) {

                    //오프셋 확인
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params = array('agent_no'=>$p['agent_no'],
                    'offset'=>$p['offset'] );

                    $sql ="SELECT rv_no
                    ,rv_title
                    ,rv_content
                    ,rv_imgs
                    ,reg_dt
                    ,rv_point
                    ,client_no
                    ,agent_no
                    ,ctrt_no
                    FROM  review
                    WHERE agent_no = :agent_no
                    ORDER BY rv_no DESC
                    OFFSET :offset LIMIT 10;";

                    $this->res= $this->execute_query($sql, $params, 'select');
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;
            default:
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : 0';
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //유저가입과 업체가입으로 나뉨
    //업체로 가입한 경우 유저로그인이 가능하지만,
    //유저로가입 한 경우 업체로그인은 동일아이디로 사용원할경우 인증받아야함
    public function store(Request $request)
    {
        $p = $request->all();
        //차후에 검증, 인증 로직이 추가되어야 할 수 있음으로 user와 agent를 먼저 나눈다.


        if ($request->filled('rv_title', 'rv_content', 'rv_point', 'client_no', 'agent_no', 'ctrt_no')
        && (strlen($p['rv_title'])>2)
        && (strlen($p['rv_content'])>2)
        && (((int)$p['rv_point'])>=0  && ((int)$p['rv_point']) <= 5)
        && (((int)$p['client_no'])>0)
        && (((int)$p['agent_no'])>0)
        && (((int)$p['ctrt_no'])>0)
        ) {
            $sql = 'INSERT INTO review
        (rv_title
        ,rv_content
        ,rv_imgs
        ,rv_point
        ,client_no
        ,agent_no
        ,ctrt_no )
        VALUES (:rv_title
        , :rv_content
        , :rv_imgs
        , :rv_point
        , :client_no
        , :agent_no 
        , :ctrt_no)
        RETURNING rv_no ;';

            if ($request->filled('rv_imgs') && $request->input('rv_imgs') != null) {
            } else {
                //start가 없거나 0보다 작은경우
                $p['rv_imgs'] =null;
            }

            $param = array('rv_title' => $p['rv_title'],
            'rv_content' => $p['rv_content'],
            'rv_imgs' => $p['rv_imgs'],
            'rv_point' => $p['rv_point'],
            'client_no' => $p['client_no'],
            'agent_no' => $p['agent_no'],
            'ctrt_no' => $p['ctrt_no']
            );

            $this->execute_query($sql, $param, 'select');

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->user_no > 0) {
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
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function update(Request $request, $req)
    {
        $p = $request->all();

        //var_dump($request->all());

        switch ($req) {
            //관리자만 수정가능
            case 'statedelete':
            if (!$request->filled('rv_no')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
                break;
            }
            
            $sql ="UPDATE review 
            SET state = 1
            WHERE rv_no = :rv_no";

            $param = array('rv_no' => $p['rv_no']);
            //정상실행일경우 state 1 query 1
            $this->execute_query($sql, $param, 'update');
            break;
            //개인정보수정
            case 'normalinfo':
           
                      

            if (!$request->filled('rv_title', 'rv_content', 'client_no' ,'rv_no')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
                break;
            }
            
            $sql ="UPDATE review 
            SET rv_title = :rv_title, rv_content = :rv_content";

            
            if ($request->hasFile('rv_imgs') && $request->file('rv_imgs')->isValid()) {
                $extension = $request->rv_imgs->extension();
                $path = $request->rv_imgs->storeAs(
                    config('filesystems.rv_imgs'),
                    'review_'.$p['rv_no'].'.'.$extension
                );
                $sql .=" , rv_imgs =  :rv_imgs";
                $params['rv_imgs'] = $path;
            }
            $sql .="\nWHERE client_no = :client_no
            AND rv_no = :rv_no";

            $param = array('rv_title' => $p['rv_title']
            , 'rv_content' => $p['rv_content']
            , 'client_no' => $p['client_no']
            , 'rv_no' => $p['rv_no']
            , 'rv_imgs' => $p['rv_imgs']);
            $this->execute_query($sql, $params, 'update');

            break;
        }

        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
