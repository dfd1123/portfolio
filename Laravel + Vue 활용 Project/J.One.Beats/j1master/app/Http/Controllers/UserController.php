<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class UserController extends Controller
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
        return 'User Controller';
    }

    public function index()
    {
        return 'API FOR User';
    }

    // 요청경로  GET - URL  : api/makers/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            case 'list':

                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } 

                $sql = "SELECT
                    state,
                    user_id,
                    user_nick,
                    user_email,
                    user_name,
                    user_agr_email_prom,
                    created_at::DATE
                FROM
                    users
                ORDER BY user_id DESC
                OFFSET :offset LIMIT 10;";

            break;
            case 'detail':
                if ($request->filled('prdc_id') && $request->input('prdc_id') >= 0) {
                    $params['prdc_id'] = $p['prdc_id'];
                } else { //prdc_id 없거나 0보다 작은경우
                    break;
                }
                $sql = "SELECT prdc_id
                ,mood_json
                ,cate_json
                ,prdc_nick
                ,state
                ,created_at
                ,updated_at
                ,prdc_img
                FROM producer
                prdc_id = :prdc_id;";
            break;
			
			case 'search':
				$params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } 
				if ($request->filled('user_nick') && $this->checkLength($p['user_nick'], 2, 30)) {
                    $params['user_nick'] = '%'.$p['user_nick'].'%';
                }
				
                $sql = "SELECT
				state,
				user_id,
				user_nick,
				user_email,
				user_name,
                user_agr_email_prom,
				created_at::DATE
			FROM
				users
			WHERE
				user_nick like :user_nick
			ORDER BY user_id desc
			OFFSET :offset LIMIT 10;";
			break;

/*
WITH BEAT_LIST AS (
	SELECT TB.prdc_id 
	,TB.beat_price 
	,TB.beat_tag
	,TB.beat_title 
	,TB.state
	,TB.beat_hit
	,TB.beat_tempo
	FROM beat TB
	WHERE TB.prdc_id = 13
	OFFSET 0 LIMIT 10
),BEAT_RES AS(
SELECT  json_agg (json_build_object(
'id',BEAT_LIST.prdc_id ,
'price',BEAT_LIST.beat_price ,
'tag',BEAT_LIST.beat_tag ,
'state',BEAT_LIST.state ,
'hit',BEAT_LIST.beat_hit ,
'beat_tempo',BEAT_LIST.beat_tempo ,
'title',BEAT_LIST.beat_title 
) ) AS res 
FROM BEAT_LIST)
SELECT TBR.res
FROM BEAT_RES TBR;
*/ 
            break;
        }
        $this->execute_query($sql, $params);
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {

    }
    
    public function update(Request $request, $req='state')
    {

        $p=  $request->all();
        $params = array();
        switch($req){
            default:
            case 'state':
            if(!$request->filled('user_id', 'state')){
                break;
            }

            $sql ="UPDATE users 
            SET state = :state 
            WHERE user_id = :user_id
            AND state !=:state 
            RETURNING user_id;";

            $params['user_id'] = $p['user_id'];
            $params['state'] = $p['state'];
			$this->execute_query($sql, $params);

            break;
        }  
        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');

    }


    public function destroy(Request $request, $req='fav')
    {
     
    }
}
