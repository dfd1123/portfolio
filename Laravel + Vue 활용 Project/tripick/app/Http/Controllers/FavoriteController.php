<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class FavoriteController extends Controller
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
        return 'API FOR Favorite';
    }

    // 요청경로  GET - URL  : api/favorite/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            //권한체크 - 자기자신의 찜만 조회가능
            case 'list':
                $params = array();

                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }

                //WHERE절 끝
                $sql = "SELECT TF.fav_id
                ,TF.pln_id
                ,TP.created_at at time zone 'KST' as created_at
                ,TP.pln_type
                ,TP.pln_desc
                ,TP.pln_thumb
                FROM
                favorite TF LEFT JOIN planner TP
                ON TF.pln_id = TP.pln_id 
                WHERE TF.user_id = :user_id
                ORDER BY fav_id DESC
                OFFSET :offset  LIMIT 10 ;  ";

                $params['user_id'] =  $this->decode_res['uid'];
                $this->res = $this->execute_query($sql, $params);

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        //user_id 나중에 auth()->user()->id 로 바꿔야됨
        $p = $request->all();
        if ($request->filled('pln_id')) {
            $sql = 'INSERT INTO 
favorite ( user_id, pln_id)
SELECT  :user_id, :pln_id 
WHERE EXISTS (SELECT pln_id  --플래너가 존재하며 
    FROM planner 
    WHERE pln_id = :pln_id)
AND NOT EXISTS (SELECT fav_id    --기존에 등록안되어있으면
    FROM favorite 
    WHERE user_id = :user_id  
    AND  pln_id = :pln_id)
RETURNING fav_id ;';

            $params = array(
            'user_id' => $this->decode_res['uid']
            ,'pln_id' => (int)$p['pln_id']);


            $this->res = $this->execute_query($sql, $params);
            
        
            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->fav_id > 0) {
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '등록오류 CODE :97';
            }
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 105';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req)
    {
    }


    public function destroy(Request $request, $req='fav')
    {
        $p = $request->all();
        switch ($req) {
            default:
            case 'fav':

            if (!$request->filled('fav_id', 'pln_id')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
                break;
            }
            $sql = "DELETE FROM favorite 
                    WHERE fav_id = :fav_id 
                    AND user_id = :user_id
                    AND pln_id = :pln_id
                    RETURNING fav_id;";
    
            $params = array('fav_id'=>$p['fav_id']
            , 'user_id'=>$this->decode_res['uid']
            , 'pln_id'=>$p['pln_id']);

            $this->res = $this->execute_query($sql, $params, 'select');
            
            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->fav_id > 0) {
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] .= '등록오류 CODE :146';
            }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
