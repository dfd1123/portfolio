<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class FaqController extends Controller
{
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
        return 'Faq Controller';
    }

    public function index()
    {
        return 'API FOR Faq';
    }

    // 요청경로  GET - URL  : api/Faq/{req}
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
              

                $sql = "SELECT faq_id
                ,faq_question
                ,faq_answer
                FROM faq
                ORDER BY faq_id  DESC
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
            switch ($p['req']) {
                case 'reg':

                if ($request->filled('faq_question', 'faq_answer')
                && $this->checkLength($p['faq_question'], 4, 128)
                && $this->checkLength($p['faq_answer'], 4, 4000)
                 ) {
                    $sql = "INSERT INTO faq(
                    faq_question, faq_answer
                )
                VALUES(:faq_question, :faq_answer) 
                RETURNING faq_id;";

                    $params['faq_question'] = $p['faq_question'];
                    $params['faq_answer'] = $p['faq_answer'];
                    $this->execute_query($sql, $params);
                }
            	break;
        	}
		}
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req='def')
    {
        $p = $request->all();

        $params = array();
        if ($request->filled('req')) {
            switch ($p['req']) {

                    default:
                    case 'edit':
                    
                    if ($request->filled('faq_id', 'faq_question', 'faq_answer')
                    || $this->checkLength($p['faq_question'], 4, 128)
                    || $this->checkLength($p['faq_answer'], 4, 4000)
                     ) {
                        $params['faq_question'] = $p['faq_question'];
                        $params['faq_answer'] = $p['faq_answer'];
                        $params['faq_id'] = $p['faq_id'];
    
                        $sql = "UPDATE faq 
                        SET faq_question = :faq_question
                        , faq_answer = :faq_answer
                        WHERE faq_id = :faq_id
                        RETURNING faq_id;";

                        $this->execute_query($sql, $params);
                    }
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

                if (!$request->filled('faq_id')) {
                    $this->res['msg']= '입력오류';
                    $this->res['state'] = config('rescode.NO_PARAM_0');
                    break;
                }

                $sql ="DELETE FROM faq
                WHERE faq_id = :faq_id
                RETURNING faq_id;";

                $params['faq_id'] = $p['faq_id'];
                $res = $this->execute_query($sql, $params);
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
