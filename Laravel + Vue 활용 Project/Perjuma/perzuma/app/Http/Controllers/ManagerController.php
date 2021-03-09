<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagerController extends Controller
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

        //권한체크 - 최고관리자만 이하코드 실행가능

        switch ($req) {
            case 'list':
            
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    $p['offset'] =0;
                }

                $sql =   "SELECT sp_no
                ,sp_aes
                ,sp_name
                ,sp_contact
                ,reg_dt
                ,state
                FROM  manager
                ORDER BY sp_no DESC
                OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $p, 'select');

            break;
            //이름으로 조회
            case 'byname':
                //이름2자이상
                if ($request->filled('sp_name') && strlen($request->input('sp_name')) >2) {
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params = array('sp_name'=>
                    '%'.$p['sp_name'].'%',
                     'offset'=>$p['offset'] );

                    $sql ="SELECT sp_no
                    ,sp_aes
                    ,sp_name
                    ,sp_contact
                    ,reg_dt
                    ,state
                    FROM  manager
                    WHERE sp_name LIKE :sp_name
                    ORDER BY sp_no DESC
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
    public function store(Request $request)
    {
        $p = $request->all();

            if ($request->filled('sp_name','sp_contact') 
            && strlen($p['sp_name']) >2
            && (int)$p['sp_contact']>0) {
                $this->checkId($p['sp_name']);

                //중복이름 검사( 필요한가? )
                if (count($this->res['query']) >= 1) {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.OK');
                    $this->res['msg'] = '중복된 이름';
                } else {
                    $sql = 'INSERT INTO manager
                (sp_name
                ,sp_contact)
                VALUES ( :sp_name
                , :sp_contact)
                RETURNING sp_no ;';

                    $param = array('sp_name' => $p['sp_name'],
                    'sp_contact' => $p['sp_contact']
                    );

                    $this->execute_query($sql, $param, 'select');

                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->sp_no > 0) {
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : 0 ';
            }
        
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    //계정존재여부확인
    private function checkId($id)
    {
        $sql = "SELECT sp_name
        FROM manager
        WHERE sp_name LIKE  :sp_name; ";

        $params = array('sp_name'=> $id );
        
        $this->res= $this->execute_query($sql, $params, 'select');
    }

    public function update(Request $request, $req)
    {
        $p = $request->all();

        //var_dump($request->all());

        switch ($req) {
            //관리자만 수정가능
            case 'state':
                if (!$request->filled('sp_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE manager SET state = :state WHERE sp_no = :sp_no";
                $param = array('state'=>$p['state'], 'sp_no'=>$p['sp_no']);
                $this->execute_query($sql, $param , 'update');

            break;
            //개인정보수정
            case 'normalinfo':
                //정보 전체 수정
                if (!$request->filled('sp_name', 'sp_contact','sp_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql ="UPDATE manager SET state = 1 ";

                $params = array();
                if ($request->filled('sp_aes')    && (((int)$p['sp_aes'])>0 )) {
                    $sql .=" , sp_aes = :sp_aes ";
                    $params['sp_aes'] = $p['sp_aes'];
                }
                if ($request->filled('sp_name') && (strlen($p['sp_name'])>2)) {
                    $sql .=" , sp_name = :sp_name ";
                    $params['sp_name'] = $p['sp_name'];
                }
                if ($request->filled('sp_contact')   &&  (strlen($p['sp_contact'])>2)) {
                    $sql .=" , sp_contact = :sp_contact ";
                    $params['sp_contact'] = $p['sp_contact'];
                }

                $params['sp_no']  = $p['sp_no'];   //차후 JWT로 변경
                $sql .=" WHERE sp_no = :sp_no ;";

                //정상실행일경우 state 1 query 1
                $this->execute_query($sql, $params, 'update');

            break;
        }

        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function destroy(Request $request)
    {
        $p = $request->all();
            //관리자만 삭제 가능
                if (!$request->filled('sp_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $sql = "DELETE FROM manager WHERE sp_no = :sp_no;";
                $param = array('sp_no'=>$p['sp_no']);
              
                //정상실행일경우 state 1 query 1
                $this->execute_query($sql, $param, 'delete');


        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
