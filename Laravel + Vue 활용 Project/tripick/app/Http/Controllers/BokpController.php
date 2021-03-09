<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class BokpController extends Controller
{
    private $regex_date_format = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
    public function __invoke($id)
    {
        return 'Bokp Controller';
    }

    public function index()
    {
        return 'API FOR Bokp';
    }

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

    //플래너 정산페이지
    //조회 - 정산가능건, 요청/완료건
    public function show(Request $request, $req)
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            //거래기록
            case 'list':
            $params['offset']=0;
            if ($request->filled('offset')) {
                $params['offset'] = $p['offset'];
            }

            // 0결제중 1완료 2환불요청 3환불 4정산요청 5정산완료 6취소
            // 1 : 정산신청가능
            $params['state']=1;
            if ($request->filled('state') ) {
                $params['state'] = $p['state'];
            }

                $params['pln_id'] = $this->decode_res['uid'];
           
                $sql = "WITH TL AS(
--TradeList
SELECT
    rsrv_id,
    TR.pln_id,
    TR.prd_id,
    TR.eb_id,
    TR.rsrv_price,
    TR.calc_req_at,
    TR.state,
    TR.calc_at,
    TR.user_id,
    TR.paied_at,
    CASE WHEN eb_id IS NULL 
        THEN (SELECT TP.prd_subtitle  FROM product TP  WHERE TP.prd_id = TR.prd_id)
    ELSE 
        (SELECT TE.estm_area  FROM  estimate TE WHERE TE.estm_id  =  (SELECT estm_id FROM estimate_bidding TEB WHERE TEB.eb_id = TR.eb_id) )
    END AS prd_name
FROM
    reserve TR
WHERE
    pln_id= :pln_id
)
SELECT TL.rsrv_id
    ,TL.prd_id
    ,TL.eb_id
    ,TL.calc_req_at
    ,TL.rsrv_price
    ,TL.state
    ,TL.user_id
    ,TL.prd_name
    ,TL.paied_at --지급일
    ,TL.calc_at  --정산요청일
    ,p.pln_name
FROM TL
JOIN planner p ON
TL.pln_id = p.pln_id
WHERE TL.state =:state
OFFSET :offset LIMIT 10;";
                $this->execute_query($sql, $params);

            break;
            //정산기록
            case 'hstry':
                if ($request->filled('pln_id') && $request->input('pln_id') >= 0) {
                    $params['pln_id'] = $p['pln_id'];
                } else { //prdc_id 없거나 0보다 작은경우
                    break;
                }

                if ($request->filled('start_at', 'end_at')) {
                    $params['end_at'] = $p['end_at'];
                    $params['start_at'] = $p['start_at'];
                }

                if (!preg_match($this->regex_date_format, $params['end_at']) || !preg_match($this->regex_date_format, $params['start_at'])) {
                    break;
                }

                $params['offset'] = 0;
                if ($request->filled('offset')  && $p['offset']>0) {
                    $params['offset'] = $p['offset'];
                }

                $sql = "SELECT rsrv_id
                ,eb_id
                ,user_id 
                ,pln_id 
                ,rsrv_price
                ,state 
                ,rsrv_pg_type
                ,calc_req_at
                ,paied_at
                ,prd_id
                ,calc_at
                FROM reserve
                WHERE pln_id = :pln_id
                    AND paied_at >= :start_at AND paied_at <= :end_at
                ORDER BY rsrv_id DESC
                OFFSET :offset LIMIT 10; ";
                $this->execute_query($sql, $params);
            break;
			case 'estmlist':
				$params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                }
				$sql = "SELECT
							rsrv_id,
							EBT.estm_id,
							RT.prd_id,
							RT.pln_id,
							RT.user_id,
							RT.state,
							RT.created_at at time zone 'KST' as created_at
						FROM
							reserve RT
						JOIN estimate_bidding EBT ON
							RT.eb_id = EBT.eb_id
						WHERE RT.state > 0
						AND RT.prd_id IS NULL
						ORDER BY rsrv_id DESC
						OFFSET :offset LIMIT 10;";
				$this->execute_query($sql, $params);
			break;
			case 'prdlist':
				$params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                }
				$sql = "SELECT
							rsrv_id,
							PT.prd_title,
							RT.prd_id,
							RT.pln_id,
							RT.user_id,
							RT.state,
							RT.created_at at time zone 'KST' as created_at
						FROM
							reserve RT
						JOIN product PT ON
							RT.prd_id = PT.prd_id
						WHERE RT.state > 0
						AND RT.eb_id IS NULL
						ORDER BY rsrv_id DESC
						OFFSET :offset LIMIT 10;";
				$this->execute_query($sql, $params);
			break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //플래너가 정산 요청
    public function store(Request $request){
		$p=  $request->all();
		$params = array();
		$req = $request->req;
		switch($req){
			case 'app':
			if (!$request->filled('rsrvs')) {
                break;
            }
			foreach($p['rsrvs'] as $rsrv){
				$params['rsrv_id'] = $rsrv;
				$params['pln_id'] = $this->decode_res['uid'];
				$sql = 'UPDATE
	reserve
SET
	state = 4,
	calc_req_at = NOW()
WHERE
	pln_id = :pln_id
	AND rsrv_id = :rsrv_id
	AND state = 1';
				$this->execute_query($sql, $params);
			}
			break;
		}
		return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req='state')
    {
        $p=  $request->all();
        $params = array();
        switch ($req) {
            //정산일 업데이트
            default:
            case 'state':

            if (!$request->filled('rsrv_id', 'state', 'calc_at')) {
                break;
            }

            $params['state'] =  $p['state'];
            $params['rsrv_id'] = $p['rsrv_id'];


            //state가 1이면 calc_at을 NULL로 업데이트
            //아닐경우 그대로


            $calc_caluse = " NULL ";
            if ($p['state'] != 1) {
                $params['calc_at'] =  $p['calc_at'];
                $calc_caluse = " :calc_at ";
            }

            $sql ="UPDATE reserve 
            SET state = :state, calc_at = ".$calc_caluse." 
            WHERE rsrv_id = :rsrv_id 
                AND state != :state
            RETURNING rsrv_id;";

            $this->execute_query($sql, $params);


            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
