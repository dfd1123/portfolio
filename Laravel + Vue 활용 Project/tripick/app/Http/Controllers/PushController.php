<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use Facades\App\Classes\FcmPush;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Input;

class PushController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('rescode.NO_AUTH_100');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }
    }
    public function __invoke($id)
    {
        return 'PUSH Controller';
    }

    public function index()
    {
        return 'API FOR PUSH';
    }
    public function show(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //push메시지 목록 혹은 특정 push메세지
            case 'list':
                $params = array();

                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                }
                
                $where = "";
                if ($request->filled('push_id') && $request->input('push_id') >= 0) {
                    $params['push_id'] = $p['push_id'];
                    $where = " push_id = :push_id ";
                }
                

                $sql = "SELECT push_id 
                ,UT.name
                ,push_title
                ,push_content
                ,push_topic
                ,PT.created_at at time zone 'KST' AS created_at
                ,user_id
                ,fcm_return_key
                FROM push PT
                LEFT JOIN users UT
                ON PT.user_id = UT.id"
                .$where." 
                ORDER BY push_id DESC
                OFFSET :offset LIMIT 10;";
                
                $this->execute_query($sql, $params);
            break;
    
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //메시지발송
    public function store(Request $request)
    {
    	
        $p = $request->all();


        if ($request->filled('req')) {
            $req= $request->input('req');
            switch ($req) {
            //push메시지 목록 혹은 특정 push메세지
            case 'list':
                $params = array();
                
                if ($request->filled('push_title', 'push_content', 'push_topic')
                && $this->checkLength($p['push_title'], 4, 128)
                 ) {
                 	//PUSH 발송
                 	$data = array();
                 	$data['title'] = $p['push_title'];
                 	$data['body'] = $p['push_content'];
					$fcm_return_key = FcmPush::push_topic($p['push_topic'], $data);
	                //PUSH발송끝
                 		
                    $sql = "INSERT INTO push( 
	                push_title
	                ,push_content
	                ,push_topic
	                ,created_at
	                ,fcm_return_key)
	                VALUES(
	                :push_title
	                ,:push_content
	                ,:push_topic
	                ,now()
	                ,:fcm_return_key)
	                RETURNING push_id;";

                    $params['push_title'] = $p['push_title'];
                    $params['push_content'] = $p['push_content'];
                    $params['push_topic'] = $p['push_topic'];
					$params['fcm_return_key'] = $fcm_return_key;
					
                    $this->execute_query($sql, $params);
                }else{
                	$this->res['query'] =null;
		            $this->res['state'] = config('rescode.NO_PARAM_0');
		            $this->res['msg'] ='값 누락 - BRANCH';
				}

            break;
			
			case 'one':
                $params = array();

                if ($request->filled('push_title', 'push_content', 'user_id')
                && $this->checkLength($p['push_title'], 4, 128)
                 ) {
                 	$sql = "SELECT fcm_token FROM users WHERE id = :user_id and push_agree = 1";
                 	$params['user_id'] = $p['user_id'];
                 	
                 	$this->execute_query($sql, $params,'select');
                 	
                 	if(isset($this->res['query'][0]->fcm_token)){
	                 	//PUSH 발송
	                 	$tokens = array();
	                 	$tokens[] = $this->res['query'][0]->fcm_token;
	                 	
	                 	$data = array();
	                 	$data['title'] = $p['push_title'];
	                 	$data['body'] = $p['push_content'];
						$fcm_return_key = FcmPush::push_one($tokens, $data);
		                //PUSH발송끝
	                 		
	                    $sql = "INSERT INTO push( 
			                push_title
			                ,push_content
			                ,user_id
			                ,created_at
			                ,fcm_return_key)
			                VALUES(
			                :push_title
			                ,:push_content
			                ,:user_id
			                ,now()
			                ,:fcm_return_key)
			                RETURNING push_id;";
		
		                    $params['push_title'] = $p['push_title'];
		                    $params['push_content'] = $p['push_content'];
		                    $params['user_id'] = $p['user_id'];
							$params['fcm_return_key'] = $fcm_return_key;
							
		                    $this->execute_query($sql, $params);
		                }else{
		                	$this->res['query'] =null;
				            $this->res['state'] = config('rescode.NO_PARAM_0');
				            $this->res['msg'] ='값 누락 - BRANCH';
						}
					}else{
						$this->res['query'] =null;
			            $this->res['state'] = config('rescode.NO_PARAM_0');
			            $this->res['msg'] ='푸시 미동의자 or 존재하지 않는 회원번호';
					}
                
            break;
            
            case 'toUser':
                $params = array();
                if ($request->filled('push_title', 'push_content', 'estm_id')
                && $this->checkLength($p['push_title'], 4, 128)
                 ) {
                 	$sql_user = "SELECT user_id FROM estimate WHERE estm_id = :estm_id";
					$user_id = DB::select($sql_user, array('estm_id' => $p['estm_id']));
                 	$sql = "SELECT fcm_token FROM users WHERE id = :user_id AND push_agree = 1";
                 	$params['user_id'] = $user_id[0]->user_id;
                 	$this->execute_query($sql, $params,'select');
                 	if(isset($this->res['query'][0]->fcm_token)){
	                 	//PUSH 발송
	                 	$tokens = array();
	                 	$tokens[] = $this->res['query'][0]->fcm_token;
	                 	$data = array();
	                 	$data['title'] = $p['push_title'];
	                 	$data['body'] = $p['push_content'];
						$fcm_return_key = FcmPush::push_one($tokens, $data);
		                //PUSH발송끝
	                 		
	                    $sql = "INSERT INTO push( 
			                push_title
			                ,push_content
			                ,user_id
			                ,created_at
			                ,fcm_return_key)
			                VALUES(
			                :push_title
			                ,:push_content
			                ,:user_id
			                ,now()
			                ,:fcm_return_key)
			                RETURNING push_id;";
		
	                    $params['push_title'] = $p['push_title'];
	                    $params['push_content'] = $p['push_content'];
	                    //$params['user_id'] = $p['user_id'];
						$params['fcm_return_key'] = $fcm_return_key;
						
	                    $this->execute_query($sql, $params);
	                }else{
	                	$this->res['query'] =null;
			            $this->res['state'] = config('rescode.NO_PARAM_0');
			            $this->res['msg'] ='값 누락 - BRANCH';
					}
				}else{
					$this->res['query'] =null;
		            $this->res['state'] = config('rescode.NO_PARAM_0');
		            $this->res['msg'] ='푸시 미동의자 or 존재하지 않는 회원번호';
				}
                
            break;
            
            case 'toPln':
                $params = array();

                if ($request->filled('push_title', 'push_content', 'estm_id')
                && $this->checkLength($p['push_title'], 4, 128)
                 ) {
                 	$sql_area = "SELECT
								estm_area
							FROM
								estimate
							WHERE
								estm_id = :estm_id;";
					$areas = DB::select($sql_area, array('estm_id' => $request->estm_id));
					
					
					if(isset($areas[0]->estm_area)){
                    	$sql_estmpln = "WITH areas AS (
											SELECT
												pln_id,
												JSONB_ARRAY_ELEMENTS_TEXT(jurisdiction_area::JSONB) AS juri
											FROM
												planner
										) SELECT
											p.pln_id
										FROM
											planner p
										JOIN areas a ON
											p.pln_id = a.pln_id
											AND a.juri LIKE :area
										GROUP BY
											p.pln_id";
						$estm_pln = DB::select($sql_estmpln, array('area' => "%".$areas[0]->estm_area."%"));
	                }else{
	                	$this->res['query'] =null;
			            $this->res['state'] = config('rescode.NO_PARAM_0');
			            $this->res['msg'] ='해당 지역 플래너 없음';
						break;
	                }
                 	if(isset($estm_pln[0]->pln_id)){
	                    foreach($estm_pln as $pln_id){
	                        $sql = "SELECT fcm_token FROM users WHERE id = :user_id and push_agree = 1";
		                 	$param1['user_id'] = $pln_id->pln_id;
		                 	
		                 	$this->execute_query($sql, $param1,'select');
		                 	if(isset($this->res['query'][0]->fcm_token)){
			                 	//PUSH 발송
			                 	$tokens = array();
			                 	$tokens[] = $this->res['query'][0]->fcm_token;
			                 	
			                 	$data = array();
			                 	$data['title'] = $p['push_title'];
			                 	$data['body'] = $p['push_content'];
								
								$fcm_return_key = FcmPush::push_one($tokens, $data);
				                //PUSH발송끝
			                 		
			                    $sql = "INSERT INTO push( 
					                push_title
					                ,push_content
					                ,user_id
					                ,created_at
					                ,fcm_return_key)
					                VALUES(
					                :push_title
					                ,:push_content
					                ,:user_id
					                ,now()
					                ,:fcm_return_key)
					                RETURNING push_id;";
				
			                    $params['push_title'] = $p['push_title'];
			                    $params['push_content'] = $p['push_content'];
			                    $params['user_id'] = $pln_id->pln_id;
								$params['fcm_return_key'] = $fcm_return_key;
								
			                    $this->execute_query($sql, $params);
			                }else{
			                	$this->res['query'] =null;
					            $this->res['state'] = config('rescode.NO_PARAM_0');
					            $this->res['msg'] ='값 누락 - BRANCH';
							}
	                    }
	                }
				}else{
					$this->res['query'] =null;
		            $this->res['state'] = config('rescode.NO_PARAM_0');
		            $this->res['msg'] ='푸시 미동의자 or 존재하지 않는 회원번호';
				}
                
            	break;
    
            }
        } else {
            $this->res['query'] =null;
            $this->res['state'] = config('rescode.NO_PARAM_0');
            $this->res['msg'] ='입력오류 - BRANCH';
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}