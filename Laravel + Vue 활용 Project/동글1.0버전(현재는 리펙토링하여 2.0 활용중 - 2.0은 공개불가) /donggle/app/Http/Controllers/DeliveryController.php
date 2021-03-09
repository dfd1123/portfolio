<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class DeliveryController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __construct(){
    }

    public function __invoke($id)
    {
        return 'Delivery controller';
    }

    public function index()
    {
        return 'Delivery FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $uid = Auth::guard('api')->id();
                $delivery_addrs = DB::table('frequen_delivery')
                ->where('uid',$uid)
                ->get();

                $this->res['query'] = $delivery_addrs;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {

        if(!$request->filled('addr1','addr2','post_num')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $uid = Auth::guard('api')->id();
        $name = Auth::guard('api')->user()->name;
        $phone_num = Auth::guard('api')->user()->mobile_number;
        $addr1 = $request->addr1;
        $addr2 = $request->addr2;
        $post_num = $request->post_num;
        
        try {
            $insert = DB::table('frequen_delivery')->insert([
                'uid' => $uid,
                'name' => $name,
                'phone_num' => $phone_num,
                'addr1' => $addr1,
                'addr2' => $addr2,
                'post_num' => $post_num,
                'frequen_yn' => 'y',
            ]);
            $this->res['query'] = $insert;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        
        } catch(Exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)"; 
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
