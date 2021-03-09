<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App;
use DB;
use Auth;

class ItemHitController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */

    public function __construct(){
        //$this->middleware('auth:api', ['except' => ['show']]); 
        //dd('dd');
    }

    public function index()
    {
        return 'API FOR ITEM';
    }

    public function show(Request $request, $req)
    {

    }


    public function store(Request $request)
    {

    }

    public function update(Request $request, $req)
    {
        switch($req){

            case 'hit':
                if(!$request->filled("item_id")){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                try{
                    $status = DB::table('items')->where('item_id', $request->item_id)->increment('hit');

                    if(!$status){
                        $this->res['query'] = $status;
                        $this->res['msg'] = "조회수 증가 성공";
                        $this->res['state'] = config('res_code.NO_DATA');
                    }
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "조회수 증가 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $this->res['query'] = $status;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            
            break;

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }

}
