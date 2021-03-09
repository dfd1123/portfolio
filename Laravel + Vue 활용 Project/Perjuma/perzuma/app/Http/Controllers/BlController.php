<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class BlController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    /*
    public function __invoke($id)
    {
        return 'BLcontroller';
    }*/

    public function index()
    {
        return 'API FOR BlController';
    }
    public function show(Request $request,$req)
    {
        $p = $request->all();
        switch ($req) {
            case 'list':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    $p['offset'] =0;
                }
                $sql =   "SELECT bl_no
                ,bl_name
                ,bl_thumb
                ,reg_dt::date
                FROM  business_list
                ORDER BY bl_no DESC
                OFFSET :offset LIMIT 10;";
                $this->res = $this->execute_query($sql, $p, 'select');
            break;
            case 'detail':
                if($request->filled('bl_no') && ((int)$request->input('bl_no')) > 0)
                {
                    $sql =   "SELECT bl_no
                    ,bl_name
                    ,bl_thumb
                    ,reg_dt::date
                    FROM  business_list
                    WHERE bl_no = :bl_no;";
                    $this->res = $this->execute_query($sql, array('bl_no'=>$p['bl_no']), 'select');
                }
                else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : TYPE ';
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request){
        $p = $request->all();
        $params = array();
        if ($request->filled('bl_name')
        && (strlen($p['bl_name'])>0)
       ) {
            $sql = 'INSERT INTO business_list
            (bl_name
            ,bl_thumb)
            VALUES (:bl_name
            , :bl_thumb)
            RETURNING bl_no ;';
            $params['bl_name'] = $p['bl_name'];

            //썸네일 있을경우 변경저장
            if ($request->hasFile('bl_thumb') && $request->file('bl_thumb')->isValid()) {

                $extension = $request->bl_thumb->extension();
                $path = $request->bl_thumb->storeAs(
                    config('filesystems.blist_photo'), 'bllist.'.$extension
                );
                $params['bl_thumb'] = $path;
            }
            
            $this->execute_query($sql, $params, 'select');

            //정상적으로 실행된 경우
            if (count($this->res['query']) >0 &&  $this->res['query'][0]->bl_no > 0) {
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

    public function update(Request $request){
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

    public function destroy(Request $request){
        $p = $request->all();
        if (!$request->filled('bl_no')) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수없음 - CODE : 1';
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $sql = "DELETE FROM business_list WHERE bl_no = :bl_no;";
        $param = array('bl_no'=>$p['bl_no']);
        $this->execute_query($sql, $param, 'delete');

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
