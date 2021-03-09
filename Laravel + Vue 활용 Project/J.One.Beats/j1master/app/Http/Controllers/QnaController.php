<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class QnaController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
     public function __construct(Request $request){
        parent::__construct($request);

        if( $this->JWTClaims ===null){
            
            $this->res['query'] =null;
            $this->res['state'] = config('rescode.NO_AUTH_100');
            $this->res['msg'] ='no-auth';
            die($this->res);
        }
    }
    public function __invoke($id)
    {
        return 'Bbs Controller';
    }

    public function index()
    {
        return 'API FOR Bbs';
    }

    // 요청경로  GET - URL  : api/makers/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            case 'list':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }

                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }

                $params['state'] =0;
                if ($request->filled('state') && $request->input('state') >= 0) {
                    $params['state'] = $p['state'];
                }

                $sql = "SELECT 
                qna_id,
                users.user_id,
                users.user_name,
                users.user_nick,
                users.user_mobile,
                ,qna_content
                ,qna_title
                ,qna_answer
                ,created_at
                ,qna_updated_at
                FROM qna
                WHERE state = :state
                ORDER BY qna_id DESC
                OFFSET :offset LIMIT 10;";

            break;
            case 'detail':
                if ($request->filled('qna_id') && $request->input('qna_id') >= 0) {
                    $params['qna_id'] = $p['qna_id'];
                } else { //prdc_id 없거나 0보다 작은경우
                    break;
                }

                $sql = "SELECT 
                qna_id,
                users.user_id,
                users.user_name,
                users.user_nick,
                users.user_mobile,
                ,qna_content
                ,qna_title
                ,qna_answer
                ,created_at
                ,qna_updated_at
                FROM qna
                WHERE qna_id = :qna_id;";
            break;
        }
        $this->execute_query($sql, $params);
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
    }
    
    public function update(Request $request, $req='def')
    {
        $p = $request->all();

        $params = array();
        if ($request->filled('req')) {
            switch ($p['req']) {
                    case 'answer':
                    
                    if (!$request->filled('qna_id', 'qna_answer') 
                        || !$this->checkLength($p['qna_answer'], 2,  4000)
                    ) {
                        $this->res['msg']='입력오류';
                        $this->res['query']=null;
                        $this->res['state']=config('rescode.NO_PARAM_0');
                        break;
                    }
                        $sql = "UPDATE qna 
                        SET qna_answer = :qna_answer
                        ,answered_at = now()
                        WHERE qna_id = :qna_id
                        RETURNING qna_id ;";

                        $params['qna_answer'] = $p['qna_answer'];
                        $params['qna_id']  = $p['qna_id'];
                        
                        $this->execute_query($sql, $params);

                    break;

                    default:
                    break;
                }
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function destroy(Request $request, $req='def')
    { 
        $p=  $request->all();
        $params = array();
        switch ($req) {
            //내용수정
            default:
            case 'def':

                if (!$request->filled('qna_id')) {
                    break;
                }

                $sql ="DELETE FROM qna
                WHERE qna_id = :qna_id
                RETURNING qna_id;";

                $params['qna_id'] = $p['qna_id'];
                $res = $this->execute_query($sql, $params);
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
