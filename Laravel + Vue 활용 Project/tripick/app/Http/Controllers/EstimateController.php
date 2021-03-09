<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Input;

class EstimateController extends Controller
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
        return 'Estimate controller';
    }

    public function index()
    {
        return 'API FOR USERS';
    }

    // 요청경로  GET - URL  : api/estimate/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            //권한체크 - 로그인

            //플래너가 유저들이 진행중인 견적들을 보는곳
            case 'estimate_planner':
                //권한체크 - 로그인한 유저만 실행 가능

                $sql_juri = "SELECT jsonb_array_elements_text(jurisdiction_area::jsonb) as juri FROM planner WHERE pln_id = :pln_id";
                $juris = DB::select($sql_juri, array('pln_id'=>$this->decode_res['uid']));

                $whereClause = '';
                if(isset($juris[0]->juri)){
                    $whereClause = 'AND ( 1 != 1';
                    foreach($juris as $juri){
                        $juri_explode = explode(" ",$juri->juri);
                        $whereClause .= " OR estm_area like '%".$juri_explode[count($juri_explode)-1]."%'";
                    }
                    $whereClause .= ' )';
                }

                $params = array();

                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }

                $whereSearch = '';
                if ($request->filled('keyword') && $request->input('keyword') >= 0) {
                    $params['keyword'] = "%".$p['keyword']."%";
                    $whereSearch .= 'AND estm_area like :keyword';
                }

                $params['pln_id'] = $this->decode_res['uid'];
                //WHERE절 끝
                $sql = "SELECT 
                estm.estm_id,
                estm.user_id,
                estm.estm_area,
                estm.estm_area_type,
                estm.estm_period,
                estm.estm_group_qtt,
                estm.estm_budget_min,
                estm.estm_budget_max,
                estm.estm_theme,
                estm.estm_step4,
                estm.estm_step5,
                estm.updated_at at time zone 'KST' as updated_at,
                usr.name,
                usr.user_thumb
                FROM estimate estm
                INNER JOIN users usr
                ON estm.user_id = usr.id
                WHERE estm.state = 1 AND estm.user_id != :pln_id AND
                COALESCE((SELECT eb_id FROM estimate_bidding WHERE pln_id = :pln_id AND estm_id = estm.estm_id),0) = 0
                ".$whereClause."
                ".$whereSearch."
                ORDER BY estm.estm_id DESC
                OFFSET :offset LIMIT 10";

                $this->res = $this->execute_query($sql, $params);
            break;

            //유저 본인의 견적 리스트 보는곳
            case 'estimate_user_list':
                // 로그인한 유저 체크
                $params = array();

                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }
                $params['user_id'] = $this->decode_res['uid'];

                $sql = "SELECT estm_id
                ,estm_area
                FROM  estimate
                WHERE user_id = :user_id AND state = 1
                ORDER BY estm_id DESC
                OFFSET :offset LIMIT 10;
                ";

                $this->res = $this->execute_query($sql, $params);
            break;
            
            // 내견적 리스트
            case 'list_mine':
                $params = array();
                $params['user_id'] = $this->decode_res['uid'];
                $sql = "SELECT
                            estm_id,
                            estm_area,
                            estm_area_type,
                            estm_period,
                            estm_group_qtt
                        FROM
                            estimate
                        WHERE
                            user_id = :user_id AND state = 1
                        ORDER BY updated_at DESC";

                $this->res = $this->execute_query($sql, $params);

            break;


            //유저 본인의 견적 하나 상세보기
            case 'estimate_detail':
                // 로그인한 유저 체크
                $params = array();
                
                if ($request->filled('estm_id')) {
                    $params['estm_id'] = $p['estm_id'];
                    $sql = "SELECT 
                    estm.estm_id
                    ,estm.user_id
                    ,estm.estm_area
                    ,estm.estm_area_type
                    ,estm.estm_period
                    ,estm.estm_group_qtt
                    ,estm.estm_budget_min
                    ,estm.estm_budget_max
                    ,estm.estm_theme
                    ,estm.estm_step4
                    ,estm.estm_step5::jsonb
                    ,estm.estm_step5_add
                    ,estm.updated_at at time zone 'KST' as updated_at
                    ,usr.name
                    ,usr.user_thumb
                    FROM estimate estm
                    INNER JOIN users usr
                    ON estm.user_id = usr.id
                    WHERE estm.state = 1 AND estm.estm_id = :estm_id;";
                    $this->res = $this->execute_query($sql, $params);
                }else{
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                }
            break;

            //플래너가 유저의 견적 하나 상세보기
            case 'pln_estimate_detail':
                // 로그인한 유저 체크
                $params = array();
                
                if ($request->filled('estm_id')) {
                    $params['estm_id'] = $p['estm_id'];
                    $sql = "SELECT 
                    estm.estm_id
                    ,estm.user_id
                    ,estm.estm_area
                    ,estm.estm_area_type
                    ,estm.estm_period
                    ,estm.estm_group_qtt
                    ,estm.estm_budget_min
                    ,estm.estm_budget_max
                    ,estm.estm_theme
                    ,estm.estm_step4
                    ,estm.estm_step5::jsonb
                    ,estm.estm_step5_add
                    ,estm.updated_at at time zone 'KST' as updated_at
                    ,usr.name
                    ,usr.user_thumb
                    FROM estimate estm
                    INNER JOIN users usr
                    ON estm.user_id = usr.id
                    WHERE estm.state = 1 AND estm.estm_id = :estm_id;";
                    $this->res = $this->execute_query($sql, $params);
                }else{
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/estimate
    public function store(Request $request)
    {
        $p = $request->all();
        if ($request->filled('estm_area', 'estm_area_type', 'estm_period', 'estm_group_qtt')) 
        {
            $sql = "WITH upsert  AS (
                    UPDATE 
                        estimate 
                    SET 
                        estm_area = :estm_area, 
                        estm_area_type = :estm_area_type, 
                        estm_period = :estm_period,
                        estm_group_qtt = :estm_group_qtt,
                        estm_period_inquiry = :estm_period_inquiry,
                        updated_at = now()
                    WHERE user_id = :user_id AND state = 0 RETURNING estm_id )
                    INSERT INTO estimate(
                            user_id, 
                            estm_area,
                            estm_area_type, 
                            estm_period, 
                            estm_group_qtt, 
                            estm_period_inquiry,
                            state, 
                            created_at, 
                            updated_at)
                    SELECT 
                        :user_id,
                        :estm_area, 
                        :estm_area_type, 
                        :estm_period, 
                        :estm_group_qtt,
                        :estm_period_inquiry,
                        0,
                        now(),
                        now()
                    WHERE 
                        NOT EXISTS(SELECT estm_id FROM upsert) RETURNING estm_id;";
                    
            $param = array(
                'user_id' => $this->decode_res['uid'],
                'estm_area' => $p['estm_area'],
                'estm_area_type' => $p['estm_area_type'],
                'estm_period' => $p['estm_period'],
                'estm_group_qtt' => $p['estm_group_qtt'],
                'estm_period_inquiry' =>$p['estm_period_inquiry']
            );

            $this->execute_query($sql, $param);

            //정상적으로 실행된 경우
            if ($this->res['state'] == 1) {
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

    // 요청경로  PUT - URL  : api/estimate/{$req}
    public function update(Request $request, $req)
    {
        $p = $request->all();
        if (!$request->filled('estm_id')) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수없음 - CODE : 1';
        }else{
            switch ($req) {
                //관리자만 수정가능
                // 1.토큰 혹은 키로 관리자검증
                //STEP2 UPDATE
                case 'step2':
                    if (!$request->filled('estm_budget_min') && !$request->filled('estm_budget_max')) {
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수없음 - CODE : 1';
                        break;
                    }
                    $sql = "UPDATE estimate 
                            SET 
                                estm_budget_min = :estm_budget_min,
                                estm_budget_max = :estm_budget_max
                            WHERE estm_id = :estm_id AND user_id = :user_id;";

                            $param = array(
                                'estm_id'=>$p['estm_id'], 
                                'estm_budget_min'=>$p['estm_budget_min'], 
                                'estm_budget_max'=>$p['estm_budget_max'],
                                'user_id'=>$this->decode_res['uid']
                            );

                            $this->execute_query($sql, $param, 'update');
                break;
                //STEP3 UPDATE
                case 'step3':
                    if (!$request->filled('estm_theme')) {
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수없음 - CODE : 1';
                        break;
                    }
                    $sql = "UPDATE estimate 
                            SET 
                                estm_theme = :estm_theme
                            WHERE estm_id = :estm_id AND user_id = :user_id;";

                            $param = array(
                                'estm_id'=>$p['estm_id'], 
                                'estm_theme'=>$p['estm_theme'],
                                'user_id'=>$this->decode_res['uid']
                            );
                            
                            $this->execute_query($sql, $param, 'update');
                break;
                //STEP4 UPDATE
                case 'step4':
                    if (!$request->filled('estm_step4')) {
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수없음 - CODE : 1';
                        break;
                    }
                    $sql = "UPDATE estimate 
                            SET estm_step4 = :estm_step4, estm_step5 = '[]'::jsonb, updated_at = now()
                            WHERE estm_id = :estm_id AND user_id = :user_id;";

                            $param = array(
                                'estm_id'=>$p['estm_id'], 
                                'estm_step4'=>$p['estm_step4'],
                                'user_id'=>$this->decode_res['uid']
                            );
                            
                            $this->execute_query($sql, $param, 'update');
                break;
                //STEP5 UPDATE
                case 'step5':
                    if (!$request->filled('estm_parent') && !$request->filled('estm_sort')) {
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수없음 - CODE : 1';
                        break;
                    }
						
                    $position = "{".$p['estm_sort']."}";
					$foreach_title = "";
                    foreach (json_decode($p['estm_title']) as $key => $value) {
                        $foreach_title .= $value.",";
                    }
                    $foreach_title = substr($foreach_title, 0, -1);
					$step5_json = array();
                    $step5_json['estm_title'] = $foreach_title;
                    $step5_json['estm_parent'] = $p['estm_parent'];
                    $step5_json['estm_sort'] = $p['estm_sort'];
                    $step5_json['estm_group'] = $p['estm_group'];

                    $estm_step5 = json_encode($step5_json);

                    $sql = "UPDATE estimate 
                            SET 
                                estm_step5 = jsonb_set(estm_step5, :estm_sort , :estm_step5 , true),
                                updated_at = now()
                            WHERE estm_id = :estm_id AND user_id = :user_id;";

                    $param = array(
                        'estm_id'=>$p['estm_id'],
                        'estm_sort'=>$position, 
                        'estm_step5'=>$estm_step5,
                        'user_id'=>$this->decode_res['uid']
                    );
                    $this->execute_query($sql, $param, 'update');
                    
                break;
                //STEP4 UPDATE
                case 'step5_add':
                    if (!$request->filled('estm_step5_add')) {
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수없음 - CODE : 1';
                        break;
                    }
                    $sql = "UPDATE estimate 
                            SET estm_step5_add = :estm_step5_add, state = 1, updated_at = now()
                            WHERE estm_id = :estm_id AND user_id = :user_id;";

                            $param = array(
                                'estm_id'=>$p['estm_id'], 
                                'estm_step5_add'=>$p['estm_step5_add'],
                                'user_id'=>$this->decode_res['uid']
                            );
                            
                    $this->execute_query($sql, $param, 'update');
                break;
                //견적상태 변경
                case 'state':
                    if (!$request->filled('state')) {
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수없음 - CODE : 1';
                        break;
                    }
                    $sql = "UPDATE estimate 
                    SET state = :state, updated_at = now()
                    WHERE estm_id = :estm_id AND user_id = :user_id;";
                    $param = array(
                        'estm_id'=>$p['estm_id'], 
                        'state'=>$p['state'],
                        'user_id'=>$this->decode_res['uid']
                    );
                    $this->execute_query($sql, $param, 'update');
                break;
				
            }
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  DELETE - URL  : api/estimate
    public function destroy(Request $request, $req)
    {
        switch ($req) {
            case 'step_delete':
                $p = $request->all();
                if (!$request->filled('estm_id')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                }else{
                    $sql = "DELETE FROM estimate WHERE state = 0 and estm_id = :estm_id AND user_id = :user_id;";

                    $param = array('estm_id'=>$p['estm_id'], 'user_id'=>$this->decode_res['uid']);

                    $this->execute_query($sql, $param, 'delete');
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
