<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class BookkeepingController extends Controller
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
        return 'Bookkeeping Controller';
    }

    public function index()
    {
        return 'API FOR Bookkeeping';
    }

    // 요청경로  GET - URL  : api/bookkeeping/{req}
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
                    u.user_id,
                    u.user_name,
                    b.beat_id,
                    b.beat_title,
                    p.prdc_nick,
                    bo.po_id,
                    bo.po_pg_type,
                    (SELECT user_id FROM users WHERE user_id = bo.user_id) buy_user_id,
                    (SELECT user_name FROM users WHERE user_id = bo.user_id) buy_user_name,
                    (SELECT user_nick FROM users WHERE user_id = bo.user_id) buy_user_nick,
                    bo.beat_price,
                    round(bo.beat_price * 0.20) fee,
                    bo.beat_price - round(bo.beat_price * 0.20) total,
                    bo.created_at,
                    bo.po_state,
                    bo.po_reg_dt,
                    bo.po_cpl_dt
                FROM beat_order bo
                LEFT JOIN beat b ON bo.beat_id = b.beat_id
                LEFT JOIN producer p ON b.prdc_id = p.prdc_id
                LEFT JOIN users u ON p.prdc_id = u.user_id
                WHERE 1 = 1
                    AND bo.state = 2
                    AND bo.po_state IN (1,2)
                ORDER BY po_reg_dt DESC, po_id DESC
                OFFSET :offset LIMIT 10";
            break;

			case 'search':
				$params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } 
				if ($request->filled('prdc_nick') && $this->checkLength($p['prdc_nick'], 2, 30)) {
                    $params['prdc_nick'] = '%'.$p['prdc_nick'].'%';
                }
				
                $sql = "SELECT
                    u.user_id,
                    u.user_name,
                    b.beat_id,
                    b.beat_title,
                    p.prdc_nick,
                    bo.po_id,
                    bo.po_pg_type,
                    (SELECT user_id FROM users WHERE user_id = bo.user_id) buy_user_id,
                    (SELECT user_name FROM users WHERE user_id = bo.user_id) buy_user_name,
                    (SELECT user_nick FROM users WHERE user_id = bo.user_id) buy_user_nick,
                    bo.beat_price,
                    round(bo.beat_price * 0.20) fee,
                    bo.beat_price - round(bo.beat_price * 0.20) total,
                    bo.created_at,
                    bo.po_state,
                    bo.po_reg_dt,
                    bo.po_cpl_dt
                FROM beat_order bo
                LEFT JOIN beat b ON bo.beat_id = b.beat_id
                LEFT JOIN producer p ON b.prdc_id = p.prdc_id
                LEFT JOIN users u ON p.prdc_id = u.user_id
                WHERE 1 = 1
                    AND bo.state = 2
                    AND bo.po_state IN (1,2)
                    AND lower(p.prdc_nick) like lower(:prdc_nick)
                ORDER BY po_reg_dt DESC, po_id DESC
                OFFSET :offset limit 10";
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
        }
        $this->execute_query($sql, $params);
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request)
    {

    }
    
    // 요청경로  PUT - URL  : api/bookkeeping/state
    public function update(Request $request, $req='state')
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            case 'state':
            if ($request->filled('prdc_id') && $request->input('prdc_id') >= 0) {
                $params['prdc_id'] = $p['prdc_id'];
            } else { //prdc_id 없거나 0보다 작은경우
                break;
            }
            
            if ($request->filled('po_id') && $request->input('po_id') >= 0) {
                $params['po_id'] = $p['po_id'];
            } else { //po_id 없거나 0보다 작은경우
                break;
            }

            $sql = "UPDATE beat_order bo
            SET
                po_state = 2,
                po_cpl_dt = NOW()
            FROM beat b
            WHERE 1 = 1
                AND b.beat_id = bo.beat_id
                AND b.prdc_id = :prdc_id
                AND bo.po_id = :po_id
                AND bo.state = 2
                AND coalesce(bo.po_state, 0) = 1
            ";
            break;
        }
        $this->execute_query($sql, $params);
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function destroy(Request $request, $req='fav')
    {
    
    }
}
