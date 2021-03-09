<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class BeatController extends Controller
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
        return 'User controller';
    }

    public function index()
    {
        return 'API FOR USERS';
    }

    // $res['query'] =  DB::SELECT(DB::RAW(("UPDATE users
    // SET user_aes ='qw2e'
    // WHERE user_no =1
    // RETURNING user_no;")));
    // update나 delete의경우 해당방법으로 returning 우회

    // 요청경로  GET - URL  : api/User/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            //권한체크 - 최고관리자만 이하코드 실행가능
            //유저목록 조회
            case 'byprdc':
            $params = array();

            $params['offset'] =0;
            if ($request->filled('offset') && $request->input('offset') >= 0) {
                $params['offset'] = $p['offset'];
            }

            $sql = "";
            
            //닉네임으로 찾을경우
            if ($request->filled('prdc_nick') && $request->input('prdc_nick') >= 0) {
                $params['prdc_nick'] = '%'.$p['prdc_nick'].'%';
                
                $sql ="SELECT
                    TB.beat_id
                    , TB.prdc_id
                    , TB.cate_id
                    , TB.mood_id
                    , TB.beat_title
                    , TB.beat_time
                    , TB.beat_tempo
                    , TB.beat_tag
                    , TB.beat_price
                    , TB.beat_hit
                    , TB.state
                    , TB.created_at
                    , TB.updated_at
                    , TP.prdc_nick
                FROM
                    beat TB
                LEFT JOIN producer TP ON
                    TB.prdc_id = TP.prdc_id
                WHERE
                    TP.prdc_nick LIKE :prdc_nick
                ORDER BY TB.beat_id DESC
                OFFSET :offset LIMIT 10 ;";
            }

            //prdc_id로 찾을경우
            if ($request->filled('prdc_id') && $request->input('prdc_id') >= 0) {
                $params['prdc_id'] = $p['prdc_id'];
                    
                $sql ="SELECT
                    TB.beat_id
                    , TB.prdc_id
                    , TB.cate_id
                    , TB.mood_id
                    , TB.beat_title
                    , TB.beat_time
                    , TB.beat_tempo
                    , TB.beat_tag
                    , TB.beat_price
                    , TB.beat_hit
                    , TB.state
                    , TB.created_at
                    , TB.updated_at
                FROM
                    beat TB
                WHERE prdc_id = :prdc_id
                ORDER BY TB.beat_id DESC
                OFFSET :offset LIMIT 10 ;";
            }



            $this->execute_query($sql, $params);

            break;
            case 'list':

                $params = array();

                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                }
                
                if ($request->filled('state') && $request->input('state') >= 0) {
                    $params['state'] = $p['state'];
                    $whereClause = " WHERE state = :state  ";
                }

                $whereClause = " ";

                if ($request->filled('beat_title') && $this->checkLength($p['beat_title'], 2, 30)) {
                    $params['beat_title'] = '%'.$p['beat_title'].'%';
                    
                    $whereClause = " WHERE lower(beat_title) LIKE lower(:beat_title) ";
                }

                $sql ="SELECT TB.beat_id
                ,TB.prdc_id
                ,TB.cate_id
                ,TB.mood_id
                ,TB.beat_title
                ,TB.beat_time
                ,TB.beat_tempo
                ,TB.beat_tag
                ,TB.beat_price
                ,TB.beat_hit
                ,TB.state
                ,TB.created_at
                ,TB.updated_at
                ,PT.prdc_nick
                FROM beat TB
                LEFT JOIN producer PT
                ON TB.prdc_id = PT.prdc_id ".
                $whereClause."
                ORDER BY TB.beat_id DESC
                OFFSET :offset LIMIT 10 ;";

                $this->execute_query($sql, $params);

            break;

            case 'detail':

                $params = array();

                if ($request->filled('beat_id') && $request->input('beat_id') >= 0) {
                    $params['beat_id'] = $p['beat_id'];
                } else {
                    $this->res['msg'] = "입력오류";
                    $this->res['state'] = config('rescode.NO_PARAM_0');
                    break;
                }
            
                
                $sql ="WITH DOWNLOAD_CNT AS(
                    SELECT 
                        beat_id
                        ,count(download_type) FILTER (WHERE download_type = 1)  AS mp3
                        ,count(download_type) FILTER (WHERE download_type = 2)  AS wav 
                        ,count(download_type) FILTER (WHERE download_type = 3)  AS license
                        FROM beat_order
                        WHERE beat_id =:beat_id   --여기도 id를 넣어주는게 30%빠름 , 22ms  29ms
                        GROUP BY beat_id)
                    SELECT
                        TB.beat_id
                        , TB.prdc_id
                        , TB.cate_id
                        , TB.mood_id
                        , TB.beat_title
                        , TB.beat_time
                        , TB.beat_tempo
                        , TB.beat_tag
                        , TB.beat_price
                        , TB.beat_hit
                        , TB.state
                        , TB.created_at
                        , TB.updated_at
                        , TB.beat_path
                        , (SELECT COUNT(beat_id) FROM favorite WHERE beat_id =12) AS fav_cnt
                        , (SELECT COUNT(beat_id) FROM beat_like WHERE beat_id =12) AS like_cnt
                        , WDC.mp3
                        , WDC.wav
                        , WDC.license
                    FROM beat TB LEFT JOIN DOWNLOAD_CNT WDC
                    ON TB.beat_id = WDC.beat_id
                    WHERE
                        TB.beat_id = :beat_id ;";
                //좋아요, 찜, 노래경로 반환
                $this->execute_query($sql, $params);
            break;
       
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    //관리자는 비트등록을 하지않음.
    public function store(Request $request)
    {
    }
    
    public function update(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //개인정보수정
            default:
            case 'state':
           
            if (!$request->filled('state', 'beat_id')
            || !$this->checkRange($p['state'], 0, 2)) {
                $this->res['query'] =null;
                $this->res['state'] = config('rescode.NO_PARAM_0');
                $this->res['msg'] = '변수없음 -userinfo  CODE : 1';
                break;
            }

            $params = array();
         
            $sql ="UPDATE beat SET 
            state = :state
            WHERE beat_id = :beat_id
            AND state != :state
            RETURNING beat_id ;";

            $params['beat_id']  = $p['beat_id'];   //차후 JWT로 변경
            $params['state']  = $p['state'];   //차후 JWT로 변경

            //정상실행일경우 state 1 query 1
            $this->execute_query($sql, $params);

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function destroy(Request $request, $id)
    {
        $p = $request->all();
        $req = $request->req;

        switch ($req) {
            case 'comment':
            $params = array();

            $sql ="UPDATE comment SET 
            state = 0
            WHERE cmt_id = :cmt_id
            ";
            
            $params = [
                'cmt_id' => $id
            ];

            //정상실행일경우 state 1 query 1
            $this->execute_query($sql, $params);
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
