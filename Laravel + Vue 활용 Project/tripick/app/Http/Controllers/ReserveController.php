<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Input;

class ReserveController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
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
        return 'Reserve controller';
    }

    public function index()
    {
        return 'API FOR RESERVE';
    }

    // 요청경로  GET - URL  : api/reserve/{$req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            //권한체크 - 유저로그인

            case 'list_user':
                $params = array();

                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }

                $whereClause = '';
                if ($request->filled('filter')) {
                    if($p['filter'] == 'all'){
                        $whereClause = '';
                    }else if($p['filter'] == 'before'){
                        $whereClause = 'AND (rsrv.state = 0 OR rsrv.state = 6)';
                    }else if($p['filter'] == 'after'){
                        $whereClause = 'AND (rsrv.state != 0 AND rsrv.state != 6)';
                    }
                }

                $params['user_id'] = $this->decode_res['uid']; //user 확인
                //아직 시안안나와서 데이터 다뽑음
                $sql = "SELECT 
                            rsrv.rsrv_id,
                            rsrv.rsrv_deposit,
                            rsrv.rsrv_price,
                            rsrv.state,
                            rsrv.prd_id,
                            rsrv.is_revw,
                            pln.pln_id,
                            pln.pln_name,
                            pln.pln_thumb,
                            pln.pln_type,
                            pln.pln_desc,
                            estm.estm_period,
                            estm.estm_id,
                            prd.prd_schedule
                        FROM  reserve rsrv
                        INNER JOIN planner pln ON rsrv.pln_id = pln.pln_id
                        LEFT JOIN 
                            estimate_bidding eb
                                INNER JOIN estimate estm
                                ON eb.estm_id = estm.estm_id
                        ON rsrv.eb_id = eb.eb_id
                        LEFT JOIN product prd ON rsrv.prd_id = prd.prd_id
                        WHERE rsrv.user_id = :user_id ".$whereClause."
                        ORDER BY rsrv.created_at DESC
                        OFFSET :offset LIMIT 20;";

                $this->res = $this->execute_query($sql, $params);

            break;

            case 'list_planner':
                $params = array();
                
                $params['pln_id'] = $p['pln_id']; //user 확인
                //아직 시안안나와서 데이터 다뽑음
                $sql = "SELECT *
                        FROM  reserve rsrv
                        INNER JOIN estimate estm ON rsrv.estm_id = estm.estm_id
                        INNER JOIN users usr ON rsrv.user_id = usr.id
                        INNER JOIN planner pln ON rsrv.pln_id = pln.pln_id
                        WHERE rsrv.pln_id = :pln_id
                        ORDER BY rsrv_id DESC;";

                $this->res = $this->execute_query($sql, $params);

            break;

            case 'user_detail':
                $params = array();
                    
                $params['user_id'] = $this->decode_res['uid']; //user 확인

                if ($request->filled('rsrv_id') || $request->filled('rsrv_id')) {
                    $params['rsrv_id'] = $p['rsrv_id'];
                    //아직 시안안나와서 데이터 다뽑음
                    $sql = "SELECT 
                                rsrv.rsrv_id,
                                rsrv.rsrv_deposit,
                                rsrv.rsrv_price,
                                rsrv.state,
                                estm.estm_area,
                                estm.estm_area_type,
                                estm.estm_period,
                                estm.estm_group_qtt,
                                estm.estm_budget_min,
                                estm.estm_budget_max,
                                estm.estm_step4,
                                estm.estm_step5,
                                estm.estm_step5_add,
                                prd.prd_subtitle,
                                prd.prd_title,
                                prd.prd_desc,
                                prd.prd_course,
                                prd.prd_schedule,
                                prd.prd_place_time
                            FROM  reserve rsrv
                            LEFT JOIN 
                                estimate_bidding eb
                                INNER JOIN 
                                    estimate estm 
                                ON eb.estm_id = estm.estm_id
                            ON rsrv.eb_id = eb.eb_id
                            LEFT JOIN
                                product prd
                            ON rsrv.prd_id = prd.prd_id
                            WHERE rsrv.rsrv_id = :rsrv_id AND rsrv.user_id = :user_id;";

                    $this->res = $this->execute_query($sql, $params);
                }else{
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    
                }
            break;
            case 'user_is_exist':
				$params = array();
				$params['user_id'] = $this->decode_res['uid']; //pln 확인
				if ($request->filled('reg_type','type')) 
	        	{
	        		$reg_type = '';
		    		if($request->reg_type == 0){
		    			$sql = 'SELECT
							rsrv_id,
							rsrv_price,
							r.state,
							prd_title
						FROM
							reserve r
						JOIN product p ON
							r.prd_id = p.prd_id
						WHERE
							r.user_id = :user_id
							AND r.prd_id = :type';
		    		}else if($request->reg_type == 1){
		    			$sql = 'SELECT
							rsrv_id,
							rsrv_price,
							state
						FROM
							reserve r
						WHERE
							r.user_id = :user_id
							AND r.eb_id = :type';
		    		}
	        	}
	        	$params['type'] = $request->type;
				
				$this->res = $this->execute_query($sql, $params);
			break;
			case 'pln_is_exist':
				$params = array();
				$params['pln_id'] = $this->decode_res['uid']; //pln 확인
				if ($request->filled('reg_type','type')) 
	        	{
	        		$reg_type = '';
		    		if($request->reg_type == 0){
		    			$sql = 'SELECT
							rsrv_id,
							rsrv_price,
							prd_title
						FROM
							reserve r
						JOIN product p ON
							r.prd_id = p.prd_id
						WHERE
							r.pln_id = :pln_id
							AND r.prd_id = :type';
		    		}else if($request->reg_type == 1){
		    			$sql = 'SELECT
							rsrv_id,
							rsrv_price
						FROM
							reserve r
						WHERE
							r.pln_id = :pln_id
							AND r.eb_id = :type';
		    		}
	        	}
	        	$params['type'] = $request->type;
				
				$this->res = $this->execute_query($sql, $params);
			break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/reserve
    public function store(Request $request)
    {
        $p = $request->all();
        if ($request->filled('rsrv_price', 'reg_type','type', 'user_id')) 
        {
        	$reg_type = '';
    		if($request->reg_type == 0){
    			$reg_type = 'prd_id';
    		}else if($request->reg_type == 1){
    			$reg_type = 'eb_id';
    		}
            $sql = 'INSERT INTO 
                        reserve(user_id, pln_id, rsrv_price, created_at, '.$reg_type.')
                    VALUES (:user_id, :pln_id, :rsrv_price, now(), :type) RETURNING rsrv_id;';

            $params = array(
            'user_id' => $p['user_id'],
            'pln_id' => $this->decode_res['uid'],
            'rsrv_price' => $p['rsrv_price'],
            'type' => $p['type']
            );
            $this->execute_query($sql, $params);

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->rsrv_id > 0) {
            } else { 
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '쿼리응답에러';
            }
                
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    // 요청경로  PUT - URL  : api/reserve/{$req}
    public function update(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //예약변경 운영이슈 필요
            case 'state':
                if (!$request->filled('state') && !$request->filled('rsrv_id')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $sql = "UPDATE reserve
                SET state = :state
                WHERE rsrv_id = :rsrv_id;";

                $params = array('rsrv_id'=>$p['rsrv_id'] , 'state'=>$p['state']);

                $this->execute_query($sql, $params, 'update');
            break;

            case 'pay':
                if (!$request->filled('rsrv_id') && !$request->filled('use_point')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                //use_point 안쓰면 0 값넘겨야됨
                $sql = 'WITH rsrv_state AS
                    (UPDATE reserve
                    SET state = 1
                    WHERE rsrv_id = :rsrv_id and user_id = :user_id
                    RETURNING user_id) 
                UPDATE Users usr
                SET user_point = user_point - :use_point
                FROM rsrv_state
                WHERE usr.id = rsrv_state.user_id';

                $params = array('rsrv_id'=>$p['rsrv_id'] , 'user_id'=>$this->decode_res['uid'], 'use_point'=>$p['use_point']);

                $this->execute_query($sql, $params, 'update');
            break;
			//결제완료
			case 'estm_paid':
				if (!$request->filled('rsrv_id')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
				$sql1 = "WITH eb_state AS(
							UPDATE
								reserve
							SET
								state = 1,
								paied_at = NOW()
							WHERE
								rsrv_id = :rsrv_id
								AND user_id = :user_id RETURNING rsrv_id
						)SELECT
							eb.estm_id
						FROM
							reserve r
						JOIN estimate_bidding eb ON
							r.eb_id = eb.eb_id
						JOIN eb_state es ON
							r.rsrv_id = es.rsrv_id";
                $param1 = array(
                    'rsrv_id'=>$p['rsrv_id'],
                    'user_id'=>$this->decode_res['uid']
                );
                $res = DB::select($sql1,$param1);
				if(count($res) >0){
					$sql2 = "UPDATE
								estimate_bidding
							SET
								state = 0
							WHERE
								estm_id = :estm_id;";
					$param2 = array(
						'estm_id' => $res[0]->estm_id
					);
					$this->execute_query($sql2, $param2, 'update');
				}else{
					$this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '구매내역없음 - CODE : 2';
                    break;
				}
				
				//해당 견적의 다른 입찰건 전부 disable 상태로 변경
				
                //$this->execute_query($sql, $param, 'update');
			break;
			
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  DELETE - URL  : api/reserve
    public function destroy(Request $request, $req)
    {
       
    }
}
