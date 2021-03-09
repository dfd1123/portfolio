<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class MoodController extends Controller
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
        return 'Mood Controller';
    }

    public function index()
    {
        return 'API FOR Mood';
    }

    // 요청경로  GET - URL  : api/License/{req}
    public function show(Request $request, $req='list')
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            default:
            case 'list':

                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                }
                
                $sql = "SELECT mood_id
                ,mood_title
                ,created_at
                ,updated_at
                ,mood_thumb,
                state
                FROM mood
                ORDER BY mood_id  DESC
                OFFSET :offset LIMIT 50;";
                $this->execute_query($sql, $params);

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        $p = $request->all();

        $params = array();
        if ($request->filled('req')) {
            switch ($request->input('req')) {
            default:
            case 'reg':

                if ($request->filled('mood_title')
                && $this->checkLength($p['mood_title'], 2, 32)
                && $this->checkFile('thumb', $request) )  {
                    if ($this->checkFile('thumb', $request)) {
                        if ($ext = $this->checkExtension('thumb', $request, array('jpeg','png','jpg','gif'))) {
                            $p['mood_thumb'] = basename($this->saveFile(
                                'thumb',  //FORM 이름
                                $request,  //request 변수
                              config('filesystems.mood_thumb'),  //파일저장경로
                                $ext       //확장자
                            ));
                        }
                        $sql = "INSERT INTO mood 
                                (mood_title, mood_thumb)
                            VALUES (:mood_title, :mood_thumb)
                            RETURNING mood_id;";

                        $params['mood_title']  = $p['mood_title'];
                        $params['mood_thumb']  = $p['mood_thumb'];
                        $this->execute_query($sql, $params);
                    } else {
                        $this->res['msg']='파일오류';
                        $this->res['state']= config('rescode.NO_PARAM_0');
                    }
                } else {
                    $this->res['msg']='입력오류';
                    $this->res['state']= config('rescode.NO_PARAM_0');
                }
            break;
            
            case 'edit':
                $p = $request->all();

                if ($request->filled('mood_id','mood_title')  && $this->checkLength($p['mood_title'], 2, 32) ) {

                    $setClause = ' ';
                    $params = array();
                    if ($this->checkFile('thumb', $request)) {
                                               
                        $sql = "UPDATE mood 
                        SET mood_title = :mood_title 
                        ,updated_at = NOW() "
                        .$setClause
                        ." WHERE mood_id = :mood_id
                         RETURNING mood_id, mood_thumb;";


                         $params['mood_title']  = $p['mood_title'];
                         $params['mood_id']  = $p['mood_id'];
                         $res=  $this->execute_query($sql, $params);

                         // 파일이 있다면, 덮어씌우기
                         if ($ext = $this->checkExtension('thumb', $request, array('jpeg','png','jpg','gif'))) {

                            if( count( $res['query'] ) ==1)
                                $p['mood_thumb'] = basename($this->saveFileNameUnder(
                                    'thumb',  //FORM 이름
                                    $request,  //request 변수
                                    $res['query'][0]->mood_thumb ,   //파일저장경로
                                    $ext       //확장자
                                ));

                            //안 덮어씌우고 새로만들경우, 제거도해줘야함
                            //$setClause .=", mood_thumb = :mood_thumb ";
                            //$params['mood_thumb'] = $p['mood_thumb'];
                        }


                    
                    } else {
                        $this->res['msg']='파일오류';
                        $this->res['state']= config('rescode.NO_PARAM_0');
                    }
                } else {
                    $this->res['msg']='입력오류';
                    $this->res['state']= config('rescode.NO_PARAM_0');
                }
            break;
            }
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req='state')
    {
        $p = $request->all();

        $params = array();
            switch ($req) {
                default:
                case 'state':
                if ($request->filled('mood_id')) {
                    $sql="UPDATE mood
                    SET state = :state
                    WHERE mood_id =:mood_id";

                    //$params['mood_id'] = $p['mood_id'];
                    $params = array('mood_id'=> $p['mood_id'], 'state'=>$p['state']);

                    $this->execute_query($sql, $params);
                }
                
                break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    //license_order에 외래키걸어놔서, 제약조건을 수정하던 칼럼을 추가해야함
    // ->삭제후에도 구매한이용권은 보여야하니.

    //일단 삭제 사용금지, update에서 state로 진행
    public function destroy(Request $request, $req='def')
    {
        $p=  $request->all();
        $params = array();
        switch ($req) {
            //내용수정
            default:
            case 'def':

                if (!$request->filled('lcens_id')) {
                    $this->res['msg']= '입력오류';
                    $this->res['state'] = config('rescode.NO_PARAM_0');
                    break;
                }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
