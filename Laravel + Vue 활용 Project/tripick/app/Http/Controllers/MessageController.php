<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Input;

class MessageController extends Controller
{
    
    public function __construct(Request $request)
    {
        parent::__construct($request);

        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }
    }
    public function __invoke($id)
    {
        return 'Message controller';
    }

    public function index()
    {
        return 'API FOR Message';
    }

    // 요청경로  GET - URL  : api/favorite/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            //권한체크 - 자기자신의 메시지 목록만 조회가능
            case 'inbox':

            if ($this->decode_res['uid'] === null) {
                break;
            }
                $params = array();
                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } 
                
                $params['whoami'] = $this->decode_res['uid'] ;
                $sql = "SELECT
                TM.eb_id
               , TM.prd_id
               , COUNT(TM.state) FILTER (	WHERE TM.receiver_read = 0 AND msg_sender != :whoami) AS msgunread
               , COUNT(TM.state) FILTER (	WHERE TM.receiver_read= 1 AND msg_sender != :whoami) AS msgread
               , TP.pln_type
               , TP.pln_name
               , SUBSTRING( MAX(TP.pln_desc), 1, 20) AS pln_desc
               , TO_CHAR (MAX(TM.created_at)
               , 'DD일 HH24:MI' ) AS created_at
               , MAX(TP.pln_thumb) AS pln_thumb
               , JSONB_AGG(TP.jurisdiction_area)->0 AS judi_area
               , (ARRAY_AGG(TM.msg_content ORDER BY TM.created_at DESC))[1] AS CONTENT
               , :whoami::INT AS me
           FROM
               message2 TM
           LEFT JOIN planner TP ON
               TM.plnr_id = TP.pln_id
           WHERE TM.msg_users @> '". $params["whoami"]."'
               AND TM.state != 3
           GROUP BY
                TM.eb_id
               , TM.prd_id
               , TP.pln_name
               , TP.pln_type
               , TM.plnr_id
               , TM.user_id
           ORDER BY
               created_at DESC offset :offset
           LIMIT 20;  ";

                $this->res = $this->execute_query($sql, $params);

            break;


            case 'plnrinbox':

            if ($this->decode_res['uid'] === null) {
                break;
            }
                $params = array();
                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } 

                $params['whoami'] = $this->decode_res['uid'];

                $sql = " SELECT TM.eb_id
               , TM.prd_id
               , COUNT(TM.state) FILTER (	WHERE TM.receiver_read = 0 AND msg_sender != :whoami) AS msgunread
               , COUNT(TM.state) FILTER (	WHERE TM.receiver_read= 1 AND msg_sender != :whoami) AS msgread
               , TU.name
               , TO_CHAR (MAX(TM.created_at), 'DD일 HH24:MI' ) AS created_at
               , MAX(TU.user_thumb) AS user_thumb
               , TP.pln_thumb
               , (ARRAY_AGG(TM.msg_content ORDER BY TM.created_at DESC))[1] AS content
               , :whoami::INT AS me
           FROM message2 TM LEFT JOIN users TU 
           ON TM.user_id = TU.id
           LEFT JOIN planner TP
           ON TM.plnr_id = TP.pln_id
           WHERE TM.msg_users @> '".$params['whoami']."'
               AND TM.state != 3
           GROUP BY
                TM.eb_id
               , TM.prd_id
               , TU.name
               , TP.pln_thumb
           ORDER BY
               created_at DESC offset :offset
           LIMIT 20;";


                
                $this->res = $this->execute_query($sql, $params);
               

            break;
            
            //recv가 조회자 기준임
            case 'msglist':

            if( $this->decode_res ===null){
                break;
            }

                $params = array();

                $where =  " ";
                if ($request->filled('eb_id') && $p['eb_id'] >= 0) {
                    $params['eb_id'] = $p['eb_id'];
                    $where =  " eb_id = :eb_id ";
                } 
                else if ($request->filled('prd_id') && $p['prd_id'] >= 0) {
                    $params['prd_id'] = $p['prd_id'];
                    $where =  " prd_id = :prd_id ";
                }
                else { 
                    $this->res['query']=null;
                    $this->res['msg']='요청오류';
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    break;
                }
                $params['offset'] =0;
                if ($request->filled('offset') && $p['offset'] >= 0) {
                    $params['offset'] = $p['offset'];
                }
                
                $params['whoami'] = $this->decode_res['uid'];

                //조인하거나 서브쿼리로 이미지, 이름 가져와야함. View처리 추천.
                $sql = "SELECT TO_CHAR (TM.created_at, 'DD일 HH24:MI' ) AS created_at
                ,TM.msg_content
                ,TM.msg_id
                ,TU.name
                ,TU.user_thumb
                ,TP.pln_thumb
                ,user_id
                ,plnr_id
                ,msg_sender
                ,:whoami::INT AS me
                FROM message2 TM  JOIN users TU
                ON TM.user_id = TU.id
                JOIN planner TP
                ON TM.plnr_id = TP.pln_id
                WHERE ". $where ."  AND TM.msg_users @> '". $params["whoami"]."'
                ORDER BY TM.created_at ASC
                offset :offset LIMIT 20;";

                $this->res = $this->execute_query($sql, $params);
            
            break;

            //유저의 저장목록
            case 'saved':

            $params = array();
            
            $params['offset'] =0;
           
            if ($this->decode_res['uid'] === null) {
                break;
            }
                $params = array();
                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } 
                $sql = "SELECT
                TM.msg_id
                , TM.msg_content
                , TO_CHAR (TM.created_at
                , 'DD일 HH24:MI' ) AS created_at
                , TM.eb_id
                , TM.prd_id
                , TU.id
                , TU.name
                , TM.user_id
                , :whoami::INT AS me
            FROM message2 TM LEFT JOIN users TU
                ON TM.user_id = TU.id
            WHERE
            TM.user_id = :whoami
                AND TM.state = 1
            ORDER BY
                msg_saved_at DESC offset :offset
            LIMIT 20; ";

                $params['whoami'] = $this->decode_res['uid'] ;
                $this->execute_query($sql, $params);

            break;

            case 'savedplnr':

	            $params = array();
	            
	            $params['offset'] =0;
	           
	            if ($this->decode_res['uid'] === null) {
	                break;
	            }
                $params = array();
                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } 
                $sql = "SELECT
                    TM.msg_id
                    , TM.msg_content
                    , TO_CHAR (TM.created_at , 'DD일 HH24:MI' ) AS created_at
                    , TM.eb_id
                    , TM.prd_id
                    , TP.pln_id
                    , TP.pln_name
                    , :whoami::INT AS me
                FROM message2 TM LEFT JOIN planner TP
                    ON TM.plnr_id = TP.pln_id
                WHERE TM.plnr_id = :whoami
                    AND msg_sender != :whoami
                    AND TM.state = 1
                ORDER BY
                    msg_saved_at DESC OFFSET :offset
                LIMIT 20; ";

                $params['whoami'] = $this->decode_res['uid'] ;
                $this->execute_query($sql, $params);

            break;
			
			case 'whoByuser':
				if($request->filled('eb_id')){
					$where = " WHERE eb_id = :eb_id ";
					$params['eb_id'] = $request->eb_id;
				}else if($request->filled('prd_id')){
					$where = " WHERE prd_id = :prd_id ";
					$params['prd_id'] = $request->prd_id;
				}
				
				$sql = "SELECT
							p.pln_name
						FROM
							message2 m
						JOIN planner p ON
							m.plnr_id = p.pln_id"
						.$where.
						"AND
							user_id = :user_id
						GROUP BY
							p.pln_name";
                $params['user_id'] = $this->decode_res['uid'] ;
                $this->execute_query($sql, $params);
			break;
			case 'whoBypln':
				if($request->filled('eb_id')){
					$where = " WHERE eb_id = :eb_id ";
					$params['eb_id'] = $request->eb_id;
				}else if($request->filled('prd_id')){
					$where = " WHERE prd_id = :prd_id ";
					$params['prd_id'] = $request->prd_id;
				}
				
				$sql = "SELECT
							u.name
						FROM
							message2 m
						JOIN users u ON
							m.user_id = u.id"
						.$where.
						"AND
							plnr_id = :plnr_id
						GROUP BY
							u.name";
                $params['plnr_id'] = $this->decode_res['uid'] ;
                $this->execute_query($sql, $params);
			break;

        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    //메세지발송
    //견적메시지 발송
    public function store(Request $request)
    {
        //user_id 나중에 auth()->user()->id 로 바꿔야됨
        $p = $request->all();


        if( !$request->filled('req')){
            $p['req']= 'def';
        }


        //공통적으로 사용하는 수신자, 메시지내용은 여기서 검사
        if(!$request->filled('msg_content')){
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 204';
        }
        else{
            $sql ="";
            $params = array();
            switch ($p['req']) {

                case 'def':

                    //유저번호 (인증값) 없는경우 리젝
                    
                    $sql = "INSERT INTO message2 ( msg_sender
                    ,msg_users
                    ,msg_content)
                    VALUES( :msg_sender
                    ,:msg_users
                    ,:msg_content)
                    RETURNING msg_id;";

                    $params['msg_users'] = "[".$p['msg_users']."]";
                    $params['msg_content'] = $p['msg_content'];
                    $params['msg_sender'] = $this->decode_res['uid'];
                    dd($params);
                break;
                //견적서 메시지
                case 'estimate':

                    if(!$request->filled('eb_id')){
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수 없음 - CODE : TYPE 194';
                        break;
                    }
					$plnr_id = DB::select("SELECT
						pln_id
					FROM
						estimate_bidding
					WHERE
						eb_id = :eb_id",[
						'eb_id' => $p['eb_id']
					]);
					
                    $sql = "INSERT INTO message2( msg_sender
                    ,msg_users
                    ,msg_content
                    ,eb_id
                    ,plnr_id
					,user_id)
                    VALUES( :msg_sender
                    ,:msg_users
                    ,:msg_content
                    ,:eb_id
                    ,:plnr_id
                    ,:user_id)
                    RETURNING msg_id;";
					
					$msg_users = array();
					array_push($msg_users,$plnr_id[0]->pln_id);
					array_push($msg_users,$this->decode_res['uid']);
					
                    $params['msg_users'] = json_encode($msg_users);
                    $params['msg_content'] = $p['msg_content'];
                    $params['plnr_id'] = $plnr_id[0]->pln_id;
                    $params['msg_sender'] = $this->decode_res['uid'];
                    $params['eb_id'] = $p['eb_id'];
					$params['user_id'] = $this->decode_res['uid'];
					
                break;
                case 'plnrestimate':

                    if(!$request->filled('eb_id')){
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수 없음 - CODE : TYPE 194';
                        break;
                    }

                
                    $sql = "INSERT INTO message2( msg_sender
                    ,msg_users
                    ,msg_content
                    ,eb_id
                    ,plnr_id
					,user_id)
                    VALUES( :msg_sender
                    ,:msg_users
                    ,:msg_content
                    ,:eb_id
                    ,:plnr_id
                    ,:user_id)
                    RETURNING msg_id;";
					
					$msg_users = array();
					array_push($msg_users,(int)$p['msg_users']);
					array_push($msg_users,$this->decode_res['uid']);
					
                    $params['msg_users'] = json_encode($msg_users);
                    $params['msg_content'] = $p['msg_content'];
                    $params['msg_sender'] = $this->decode_res['uid'];
                    $params['plnr_id'] = $this->decode_res['uid'];
                    $params['eb_id'] = $p['eb_id'];
					$params['user_id'] = $p['msg_users'];
					
                break;
                //상품 메세지
                case 'prd':
                
                if(!$request->filled('prd_id')){
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : TYPE 220';
                    break;
                }

                	$plnr_id = DB::select("SELECT
						pln_id
					FROM
						product
					WHERE
						prd_id = :prd_id",[
						'prd_id' => $p['prd_id']
					]);
					
                    $sql = "INSERT INTO message2( msg_sender
                    ,msg_users
                    ,msg_content
                    ,prd_id
                    ,plnr_id
					,user_id)
                    VALUES( :msg_sender
                    ,:msg_users
                    ,:msg_content
                    ,:prd_id
                    ,:plnr_id
                    ,:user_id)
                    RETURNING msg_id;";

					$msg_users = array();
					array_push($msg_users,$plnr_id[0]->pln_id);
					array_push($msg_users,$this->decode_res['uid']);
					
                    $params['msg_users'] = json_encode($msg_users);
                    $params['msg_content'] = $p['msg_content'];
                    $params['plnr_id'] = $plnr_id[0]->pln_id;
                    $params['msg_sender'] = $this->decode_res['uid'];
                    $params['prd_id'] = $p['prd_id'];
					$params['user_id'] = $this->decode_res['uid'];
                break;
                case 'plnrprd':
                
                if(!$request->filled('prd_id')){
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : TYPE 220';
                    break;
                }
                    $sql = "INSERT INTO message2( msg_sender
                    ,msg_users
                    ,msg_content
                    ,prd_id
                    ,plnr_id
					,user_id)
                    VALUES( :msg_sender
                    ,:msg_users
                    ,:msg_content
                    ,:prd_id
                    ,:plnr_id
                    ,:user_id)
                    RETURNING msg_id;";
					
					$msg_users = array();
					array_push($msg_users,(int)$p['msg_users']);
					array_push($msg_users,$this->decode_res['uid']);
					
                    $params['msg_users'] = json_encode($msg_users);
                    $params['msg_content'] = $p['msg_content'];
                    $params['plnr_id'] = $this->decode_res['uid'];
                    $params['msg_sender'] = $this->decode_res['uid'];
                    $params['prd_id'] = $p['prd_id'];
					$params['user_id'] = $p['msg_users'];
                break;
            }
        
            $this->execute_query($sql, $params);   
        
            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->msg_id > 0) {
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '등록오류 CODE :197';
            }
        }
        //정상등록된 경우 state 1  query : msg_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req='save')
    {

        //공통적으로 사용하는 수신자, 메시지내용은 여기서 검사
        if($req=='read'){
        	$params = array();
			$where = "";
			if($request->filled('eb_id')){
				$where = "eb_id = :eb_id";
				$params['eb_id'] = $request->eb_id;
			}else if($request->filled('prd_id')){
				$where = "prd_id = :prd_id";
				$params['prd_id'] = $request->prd_id;
			}else{
				$this->res['query'] = null;
	            $this->res['state'] = config('res_code.PARAM_ERR');
	            $this->res['msg'] = '변수 없음 - CODE : TYPE 204';
			}
            $sql = "UPDATE
						message2
					SET
						receiver_read = 1
					WHERE ".
						$where
					." AND msg_users @> '". $this->decode_res['uid'] ."'
					AND msg_sender != '". $this->decode_res['uid'] ."'";
        }else{
        	if( $this->decode_res ===null || !$request->filled('msg_id')){
	            $this->res['query'] = null;
	            $this->res['state'] = config('res_code.PARAM_ERR');
	            $this->res['msg'] = '변수 없음 - CODE : TYPE 204';
	        }else{
	            $p = $request->all();
	
	            
				$params['msg_id'] = $p['msg_id'] ;
	            $sql ="";
	            switch ($req) {
	                //저장
	                default:
	                case  'save':
	                    $sql ="UPDATE message2
	                    SET state =1, msg_saved_at = now()
	                    WHERE msg_id = :msg_id
	                    AND msg_users @> '". $this->decode_res['uid'] ."'
	                    RETURNING msg_id; ";
	
	                    //msg_id 반환함으로써, 실수로 삭제한경우 다시 복구 가능하게함
	                break;
	                //삭제복구
	                case 'restore':
	                    $sql ="UPDATE message
	                    SET state =1
	                    WHERE msg_id = :msg_id
	                    AND state =2 
	                    AND msg_users @> '". $this->decode_res['uid'] ."'
	                    RETURNING msg_id; ";
	                break;
	                //저장취소
	                case  'unsave':
	                    $sql ="UPDATE message2
	                    SET state =1
	                    WHERE msg_id = :msg_id
	                    AND msg_users @> '". $this->decode_res['uid'] ."'
	                    RETURNING msg_id; ";
	                break;
	
	                //삭제
	                case  'del':
	                    $sql ="UPDATE message2
	                    SET state =2
	                    WHERE msg_id = :msg_id
	                    AND msg_users @> '". $this->decode_res['uid'] ."'
	                    RETURNING msg_id; ";
	                break;
	            }
	        }
        }
        
		$this->execute_query($sql, $params);   
        //정상수행된 경우 state 1  query : msg_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function destroy(Request $request, $req='msg')
    {
    }
}
