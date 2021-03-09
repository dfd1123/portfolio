<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class LicenseController extends Controller
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
        return 'License Controller';
    }

    public function index()
    {
        return 'License FOR Faq';
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
              
                $sql = "SELECT lcens_id
                ,lcens_name
                ,lcens_type
                ,state
                ,lcens_days
                ,lcens_price
                ,lcens_desc
                ,reg_at
                FROM license
                ORDER BY lcens_id  DESC
                OFFSET :offset LIMIT 10;";

            break;
        }
        $this->execute_query($sql, $params);
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

                if ($request->filled('lcens_name', 'lcens_type', 'lcens_days', 'lcens_price', 'lcens_desc')
                && $this->checkLength($p['lcens_name'], 4, 64)
                && $this->checkRange($p['lcens_type'], 0, 3)
                && $this->checkRange($p['lcens_days'], 1, 365)
                && $this->checkRange($p['lcens_price'], 1, 1000000000)
                && $this->checkLength($p['lcens_desc'], 4, 2000)
                 ) {
                    $sql = "INSERT INTO license 
                    (lcens_name, lcens_type ,lcens_days ,lcens_price ,lcens_desc)
                VALUES (:lcens_name, :lcens_type ,:lcens_days ,:lcens_price ,:lcens_desc)
                RETURNING lcens_id;";

                    $params['lcens_name'] = $p['lcens_name'];
                    $params['lcens_type'] = $p['lcens_type'];
                    $params['lcens_days'] = $p['lcens_days'];
                    $params['lcens_price'] = $p['lcens_price'];
                    $params['lcens_desc'] = $p['lcens_desc'];
                    $this->execute_query($sql, $params);
                }else{
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
                if ($request->filled('lcens_id', 'lcens_name', 'lcens_type', 'lcens_days', 'lcens_price', 'lcens_desc','state')
                && $this->checkLength($p['lcens_name'], 4, 64)
                && $this->checkRange($p['lcens_type'], 0, 3)
                && $this->checkRange($p['lcens_days'], 1, 365)
                && $this->checkRange($p['lcens_price'], 1, 1000000000)
                && $this->checkRange($p['state'], 0, 1)
                && $this->checkLength($p['lcens_desc'], 4, 2000)
                 ) {
                    $sql = "UPDATE license 
                    SET lcens_name = :lcens_name
                    , lcens_type = :lcens_type
                    ,lcens_days  = :lcens_days
                    ,lcens_price = :lcens_price
                    ,lcens_desc = :lcens_desc
                    ,state = :state
                    WHERE lcens_id = :lcens_id
                    RETURNING lcens_id, state;";


                    $params['lcens_id'] = $p['lcens_id'];
                    $params['lcens_name'] = $p['lcens_name'];
                    $params['lcens_type'] = $p['lcens_type'];
                    $params['lcens_days'] = $p['lcens_days'];
                    $params['lcens_price'] = $p['lcens_price'];
                    $params['state'] = $p['state'];
                    $params['lcens_desc'] = $p['lcens_desc'];
                    $this->execute_query($sql, $params);
                }else{
                    $this->res['msg']='입력오류';
                    $this->res['state']= config('rescode.NO_PARAM_0');
                }
            break;
			case 'state':
				if ($request->filled('lcens_id','state')){
					$sql = "UPDATE license 
                    SET state = :state
                    WHERE lcens_id = :lcens_id
                    RETURNING lcens_id;";
                    $params = array('state'=>$p['state'], 'lcens_id'=>$p['lcens_id']);
                    $this->execute_query($sql, $params);
				}else{
                    $this->res['msg']='입력오류';
                    $this->res['state']= config('rescode.NO_PARAM_0');
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

                $sql ="DELETE FROM license
                WHERE lcens_id = :lcens_id
                RETURNING faq_id;";

                $params['lcens_id'] = $p['lcens_id'];
                $res = $this->execute_query($sql, $params);
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}