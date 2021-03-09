<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class EstimateBidController extends Controller
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

    // 요청경로  GET - URL  : api/estimate_bid/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            //권한체크 - 로그인한 유저에게만

            //유저가 자신에게 입찰한 플래너(개인 or 업체)리스트를 보는부분 or 전체 입찰건
            case 'estimate_bid_user':
                $params = array();
                
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }

                
                $params['user_id'] = $this->decode_res['uid'];

                //WHERE절 끝
                $sql = "SELECT
                            pln.pln_id,
                            pln.pln_name,
                            pln.pln_thumb,
                            pln.pln_desc,
                            estm.estm_id,
                            estm.estm_area,
                            estm.estm_period
                        FROM
                            estimate_bidding eb
                        INNER JOIN
                            planner pln
                        ON
                            eb.pln_id = pln.pln_id
                        INNER JOIN
                            estimate estm
                        ON
                            eb.estm_id = estm.estm_id
                        WHERE
                            estm.state = 1 AND
                            estm.user_id = :user_id
                        ORDER BY eb.eb_id DESC
                        OFFSET :offset LIMIT 20;
                        "; 

                $this->res = $this->execute_query($sql, $params);

            break;
            
            //플래너가 입찰진행중인 유저리스트를 보는부분
            case 'estimate_bid_planner':

                $params = array();

                $whereClause = '';
                if ($request->filled('pln_id')) {
                    $params['pln_id'] = $p['pln_id'];
                    $whereClause.= " \n AND eb.pln_id = :pln_id ";
                
                
                    //WHERE절 끝
                    $sql = "
                    SELECT
                    eb.estm_asking_price,
                    estm.estm_area,
                    estm.estm_period,
                    estm.estm_group_qtt,
                    estm.estm_step4,
                    estm.estm_step5,
                    estm.state
                    FROM  estimate_bidding eb
                    INNER JOIN estimate estm ON eb.estm_id = estm.estm_id 
                    WHERE 1 = 1 ".$whereClause."
                    ORDER BY eb_id DESC;
                    ";

                    $this->res = $this->execute_query($sql, $params);
                } else {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/estimate_bid
    public function store(Request $request)
    {
        $p = $request->all();

        //pln_id 나중에 auth()->user()->id 로 바꿔야됨
        if ($request->filled('estm_id', 'estm_asking_price','eb_title','eb_desc')) 
        {
            $sql = 'INSERT INTO estimate_bidding
            (
            estm_id, 
            pln_id, 
            estm_asking_price,
            eb_title,
            eb_desc
            )
            VALUES (
            :estm_id, 
            :pln_id, 
            :estm_asking_price,
            :eb_title,
            :eb_desc
            )RETURNING eb_id;';

            $param = array(
                'estm_id' => $p['estm_id'],
                'pln_id' => $this->decode_res['uid'], // auth()->user()->id 로 바꿔야됨
                'estm_asking_price' => $p['estm_asking_price'],
                'eb_title' => $p['eb_title'],
                'eb_desc' => $p['eb_desc']
            );

            $this->execute_query($sql, $param);

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->eb_id > 0) {
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
    
    public function update(Request $request, $req)
    {
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
