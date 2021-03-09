<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class NoticeController extends Controller
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
        return 'Notice Controller';
    }

    public function index()
    {
        return 'API FOR Notice';
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
                if ($request->filled('offset') && $request->input('offset') >= 0 ) {
                    $params['offset'] = $p['offset'];
                }
              
                $sql = "SELECT notice_id
                ,notice_title
                ,notice_content
                ,created_at
                ,updated_at
                FROM notice
                ORDER BY notice  DESC
                OFFSET :offset LIMIT 10;";
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

                if ($request->filled('notice_title', 'notice_content')
                && $this->checkLength($p['notice_title'], 2, 64)
                && $this->checkLength($p['notice_content'], 2, 4000)) {
                    $sql = "INSERT INTO notice 
                            (notice_title, notice_content)
                        VALUES (:notice_title, :notice_content)
                        RETURNING notice_id;";

                    $params['notice_title']  = $p['notice_title'];
                    $params['notice_content']  = $p['notice_content'];
                    $this->execute_query($sql, $params);
                } else {
                    $this->res['msg']='입력오류';
                    $this->res['state']= config('rescode.NO_PARAM_0');
                }
                break;
            }
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req='edit')
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            default:

            case 'edit':
                if (!$request->filled('notice_id', 'notice_content', 'notice_title' )) {
                    break;
                }
                $sql="UPDATE notice
                SET notice_title = :notice_title
                ,notice_content = :notice_content
                WHERE notice_id =:notice_id 
                RETURNING notice_id;";

                $params['notice_id'] = $p['notice_id'];
                $params['notice_content'] = $p['notice_content'];
                $params['notice_title'] = $p['notice_title'];

                $this->execute_query($sql, $params);
            break;

            case 'state':
            if (!$request->filled('notice_id', 'state' )) {
                break;
            }
            
                $sql="UPDATE notice
                SET state = :state
                WHERE notice_id =:notice_id 
                RETURNING notice_id;";

                $params['notice_id'] = $p['notice_id'];
                $params['state'] = $p['state'];

                $this->execute_query($sql, $params);
            
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function destroy(Request $request, $req='def')
    {
        $p =  $request->all();
        $params = array();
        switch ($req) {
            //내용수정
            default:
            case 'def':

                if (!$request->filled('notice_id')) {
                    $this->res['msg']= '입력오류';
                    $this->res['state'] = config('rescode.NO_PARAM_0');
                    break;
                }
                
                $sql="DELETE FROM notice
                WHERE notice_id = :notice_id
                RETURNING notice_id;";
                $params['notice_id'] = $p['notice_id'];

                $this->execute_query($sql, $params);

                break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}