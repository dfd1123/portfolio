<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TradeController extends Controller
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

    // $res['query'] =  DB::SELECT(DB::RAW(("UPDATE users
    // SET user_aes ='qw2e'
    // WHERE user_no =1
    // RETURNING user_no;")));
    // update나 delete의경우 해당방법으로 returning 우회

    // 요청경로  GET - URL  : api/User/{req}
    public function show(Request $request, $req)
    {
        
        //권한체크 - 최고관리자만 이하코드 실행가능
        $p = $request->all();
        
        switch ($req) {
            case 'list'://관리자가 보는 모든 리스트
            
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }
                $sql =   "SELECT trd_no
                ,trd_name
                ,state
                ,bl_no
                ,created_at::date
                FROM  trades
                ORDER BY trd_no DESC
                OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $p, 'select');
            break;
            //업체페이지 시공요청
            case 'req':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }
                $sql =   "SELECT
                TT.trd_no,
                TT.trd_name,
                TT.trd_area,
                TT.trd_budget,
                TT.address ,
                TT.is_premium,
                BLT.bl_name,
                created_at::DATE
            FROM
                trades TT
            LEFT JOIN business_list BLT ON
                BLT.bl_no = TT.bl_no
            WHERE
                (
                    SELECT
                        COUNT(TBT.trd_no)
                    FROM
                        agent_trades_bidding TBT
                    WHERE
                        TBT.trd_no = TT.trd_no
                ) < 3
            
                offset :offset limit 10";

                $this->res = $this->execute_query($sql, $p, 'select');
            break;
            //업체의 입찰 진행중 목록
        
            case 'trdbidding':
                $p['user_no'] = auth()->user()->user_no;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }
                $sql ="WITH CTE AS
                ( SELECT 
                    atb.trd_no 
                FROM 
                    agent_trades_bidding atb 
                WHERE 
                    agt_no = :user_no) 
            SELECT 
                TT.trd_no, 
                TT.trd_name, 
                TT.trd_area, 
                TT.trd_budget,
                TT.address,
                TT.is_premium,
                TT.state,
                BLT.bl_name,
                TT.created_at::date
            FROM 
                trades TT 
            JOIN 
                CTE 
            ON 
                CTE.trd_no = TT.trd_no
            LEFT JOIN
                business_list BLT
            ON
                BLT.bl_no = TT.bl_no
            WHERE 
                TT.trd_no = CTE.trd_no
            AND state = 1
            OR state = 2
            ORDER BY trd_no DESC
            OFFSET :offset LIMIT 10";

                $this->res = $this->execute_query($sql, $p, 'select');
            break;
            //이름으로 조회
            case 'search':
                //이름2자이상
                if ($request->filled('search_keyword','search_type') 
                && ((int)$request->input('search_type')) >= 0) {
                    $params = array();
                    //오프셋 확인
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params['offset'] = $p['offset'];
                     $sql =   "SELECT trd_no
                     ,trd_name
                     ,state
                     ,bl_no
                     ,created_at::date
                     FROM  trades";
                     if(((int)$p['search_type']) ==0){
                         //거래명으로 검색
                         $sql .= "\nWHERE trd_name LIKE :search_keyword";
                         $params['search_keyword'] = '%'.$p['search_keyword'].'%';
                     }
                     else if(((int)$p['search_type']) ==1){
                         //유저 번호로 검색
                         $sql .= "\nWHERE client_no = :search_keyword";
                         $params['search_keyword'] = $p['search_keyword'];
                     }
                     else if(((int)$p['search_type']) ==2){
                         //업체 번호로 검색
                         $sql .= "\nWHERE agent_no = :search_keyword";
                         $params['search_keyword'] = $p['search_keyword'];
                     }
                     else if(((int)$p['search_type']) ==3){
                        //감리 번호로 검색
                        $sql .= "\nWHERE supervison_no = :search_keyword";
                        $params['search_keyword'] = $p['search_keyword'];
                    }
                    else if(((int)$p['search_type']) ==4){
                        //매니저 번호로 검색
                        $sql .= "\nWHERE staff_no = :search_keyword";
                        $params['search_keyword'] = $p['search_keyword'];
                    }
                    else if(((int)$p['search_type']) ==5){
                        //거래 번호로 검색
                        $sql .= "\nWHERE trd_no = :search_keyword";
                        $params['search_keyword'] = $p['search_keyword'];
                    }
                    else if(((int)$p['search_type']) ==6){
                        $p['user_no'] = auth()->user()->user_no;
                        if(!isset($p['user_no'])){
                            $this->res['query'] = null;
                            $this->res['state'] = config('res_code.PARAM_ERR');
                            $this->res['msg'] = '변수 없음 - CODE : 2';
                            break;
                        }
                        //거래명으로 검색(업체 - 자기 내역만)
                        $sql .= "\nWHERE trd_name LIKE :search_keyword
                        AND agent_no = :user_no";
                        $params['search_keyword'] = '%'.$p['search_keyword'].'%';
                        $params['user_no'] = $p['user_no'];
                    }
                     
                     $sql .= " AND state<>0 ORDER BY trd_no DESC OFFSET :offset LIMIT 10;";

                    $this->res= $this->execute_query($sql, $params, 'select');
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;
             case 'detail':
             if ($request->filled('trd_no') && ((int)$request->input('trd_no')) >0
             ) {
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }
                $sql =   "SELECT trd_no
                ,trd_name
                ,bidding_dt
                ,bidding_end_dt
                ,construct_dt
                ,construct_end_dt
                ,client_no
                ,agent_no
                ,supervison_no
                ,staff_no
                ,trd_draw
                ,view_cnt
                ,trd_budget
                ,trd_area
                ,address
                ,detail_address
                ,post_num
                ,state
                ,bl_no
                ,created_at
                FROM  trades
                WHERE trd_no = :trd_no
                OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $p, 'select');
            }
            else{
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
        if ($request->filled('type') && $p['type'] ==='tradebidding') {
            if ($request->filled('trd_no', 'agt_no', 'asking_price')
            && (((int)$p['trd_no'])>0)
            && (((int)$p['agt_no'])>0)
            && (((int)$p['asking_price'])>0)
            ) {
                $sql = 'INSERT
                INTO
                    agent_trades_bidding (
                        trd_no ,
                        agt_no ,
                        asking_price ,
                        agt_others
                    ) SELECT
                        :trd_no ,
                        :agt_no ,
                        :asking_price ,
                        :agt_others
                    WHERE
                        NOT EXISTS (
                            SELECT
                                1
                            FROM
                                agent_trades_bidding
                            WHERE
                                trd_no = :trd_no
                                AND agt_no = :agt_no
                        ) 
                    AND (SELECT count(*) FROM agent_trades_bidding WHERE trd_no = :trd_no) < 3
                    RETURNING atb_no ;';
                $param = array('trd_no' => $p['trd_no'],
                'agt_no' => $p['agt_no'],
                'asking_price' => $p['asking_price'],
                'agt_others' => $p['agt_others']
                );

                $this->execute_query($sql, $param, 'select');

                //정상적으로 실행된 경우
                if (count($this->res['query']) >0 &&  $this->res['query'][0]->atb_no > 0) {
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.NO_DATA');
                    $this->res['msg'] = '이미 입찰신청 됨';
                }
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : 0 ';
            }
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE ';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req)
    {
        $p = $request->all();

        //var_dump($request->all());

        switch ($req) {
            //감리 배정
            case 'superv_in_state':
                if (!$request->filled('supervison_no') && !$request->filled('trd_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE trades 
                SET supervison_no = :supervison_no
                WHERE trd_no = :trd_no;";
                $param = array('supervison_no'=>$p['supervison_no']
                , 'trd_no'=>$p['trd_no']);
                $this->execute_query($sql, $param , 'update');
            break;
            case 'superv_out_state':
                if (!$request->filled('trd_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE trades 
                SET supervison_no = null
                WHERE trd_no = :trd_no;";
                $param = array('trd_no'=>$p['trd_no']);
                $this->execute_query($sql, $param , 'update');
            break;
            case 'manager_in_state':
                if (!$request->filled('staff_no') && !$request->filled('trd_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE trades 
                SET staff_no = :staff_no
                WHERE trd_no = :trd_no;";
                $param = array('staff_no'=>$p['staff_no']
                , 'trd_no'=>$p['trd_no']);
                $this->execute_query($sql, $param , 'update');
            break;
            case 'manager_out_state':
                if (!$request->filled('staff_no') && !$request->filled('trd_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE trades 
                SET staff_no = null
                WHERE trd_no = :trd_no;";
                $param = array('trd_no'=>$p['trd_no']);
                $this->execute_query($sql, $param , 'update');
            break;
            case 'trdbidding':
                if (!$request->filled('user_no') && !$request->filled('trd_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql1 = "UPDATE
                trades
            SET
                agent_no = :user_no,
                state = 3,
                updated_at = now()
            WHERE
                trd_no = :trd_no;";
                $param = array('trd_no'=>$p['trd_no'], 'user_no'=>$p['user_no']);
                $this->execute_query($sql1, $param , 'update');

                $sql2 = "UPDATE agent_trades_bidding
                SET 
                    state = 1
                WHERE
                    trd_no = :trd_no
                AND
                    agt_no = :user_no";
                $param2 = array('trd_no'=>$p['trd_no'],'user_no'=>auth()->user()->user_no);
                $this->execute_query($sql2, $param2 , 'delete');
            break;
        }

        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
