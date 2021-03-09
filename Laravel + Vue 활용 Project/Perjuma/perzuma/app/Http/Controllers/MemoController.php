<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class MemoController extends Controller
{
    public function index()
    {
        echo(File::get(storage_path('logs/monitoring/monitor_20190717.log')));
        return 'API FOR Memo Controller';
    }
    public function show(Request $request, $req)
    {
        $p = $request->all();
        switch ($req) {
            //특정 거래의 메모
            case 'trdof':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    $p['offset'] =0;
                }
                $sql =   "SELECT tm_no
                ,tm_memo
                ,trd_no
                ,reg_dt::date
                FROM  trade_memo
                WHERE trd_no = :trd_no
                OFFSET :offset LIMIT 10;";
                $this->res = $this->execute_query($sql, $p);
            break;
         
            //유저의 메모목록
            case 'userof':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    $p['offset'] =0;
                }
                if ($request->filled('client_no') && ((int)$request->input('client_no')) > 0) {
                    $sql =   "SELECT
                    TTM.tm_no
                    , TTM.tm_memo
                    , TTM.trd_no
                    , TTM.reg_dt::DATE
                    , TT.trd_name
                    , TT.trd_budget
                FROM
                    trade_memo TTM JOIN trades TT
                ON TTM.trd_no = TT.trd_no
                    WHERE
                    TT.trd_no IN (
                    SELECT
                        trd_no
                    FROM
                        trades
                    WHERE
                        client_no = :client_no 
                        OFFSET :offset LIMIT 10);";
                    $this->res = $this->execute_query($sql, $p);
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : TYPE ';
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request)
    {
        $p = $request->all();
        $params = array();
        
        //거래메모 등록 (최고관리자만 접근가능)
        if ($request->filled('trd_no') &&  $p['trd_no'] > 0) {
            $sql = 'INSERT INTO trade_memo (tm_memo, trd_no )
            VALUES (:tm_memo,  :trd_no)
            RETURNING tm_no;';
            $params['tm_memo'] = $p['tm_memo'];
            $params['trd_no'] = $p['trd_no'];

           
            $this->execute_query($sql, $params, 'select');

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->tm_no > 0) {
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '쿼리응답에러';
            }
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request)
    {
        $p = $request->all();
        if (!$request->filled('bl_no', 'bl_name')) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수없음 - CODE : 1';
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $sql = "UPDATE business_list SET bl_name = :bl_name
        WHERE bl_no = :bl_no;";

        $param = array('bl_name'=>$p['bl_name']
        , 'bl_no'=>$p['bl_no']);
        $this->execute_query($sql, $param, 'update');
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function destroy(Request $request)
    {
        //최고관리자만 접근가능
        $p = $request->all();
        if ($request->filled('tm_no') &&  $p['tm_no'] > 0) {
            $sql = "DELETE FROM trade_memo WHERE tm_no = :tm_no;";
            $param = array('tm_no'=>$p['tm_no']);
            $this->execute_query($sql, $param, 'delete');
        } else {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수없음 - CODE : 1';
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
