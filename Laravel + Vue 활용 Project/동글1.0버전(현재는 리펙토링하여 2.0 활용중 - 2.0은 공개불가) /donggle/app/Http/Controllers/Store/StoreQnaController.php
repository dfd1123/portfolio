<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use DB;

class StoreQnaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        return 'StoreQna controller';
    }

    public function index()
    {
        return 'StoreQna FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch ($req) {
            case 'list_main':
                
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();

                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $limit = ($request->filled('limit'))?$request->limit:5;
                
                $store_id = $store->store_id;

                $count = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->select('store_qna.*', 'users.nickname', 'users.profile_img')
                ->where('store_id', $store_id)
                ->count();

                $store_qna = DB::table('store_qna')
                ->where('store_id', $store_id)
                ->orderBy('q_datetime', 'DESC')
                ->limit($limit)
                ->get();
                
                $response = array();
                $response['store_qna'] = $store_qna;
                $response['count'] = $count;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;

            case 'store_qa':
                if (!$request->filled('start_date', 'end_date')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();

                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:5;
                
                $offset = ($page - 1) * $limit;
                
                $store_id = $store->store_id;
                $start_date = $request->start_date." 00:00:00";
                $end_date = $request->end_date." 23:59:59";

                if ($request->filled('orderby')) {
                    if ($request->orderby == 'desc') {
                        $orderby = $request->orderby;
                    } else {
                        $orderby = $request->orderby;
                    }
                } else {
                    $orderby = 'desc';
                }

                if ($request->filled('searchSelect')) {
                    $search_select = $request->searchSelect;
                } else {
                    $search_select = 'q_name';
                }

                if ($request->filled('searchKeyword')) {
                    $search_text = '%'.$request->searchKeyword.'%';
                } else {
                    $search_text = '%%';
                }
                


                $count = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->where('store_id', $store_id)
                ->where($search_select, 'like', $search_text)
                ->whereBetween('q_datetime', [$start_date,$end_date])
                ->count();

                $store_qna = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->select('store_qna.*', 'users.nickname', 'users.profile_img')
                ->where('store_id', $store_id)
                ->where($search_select, 'like', $search_text)
                ->whereBetween('q_datetime', [$start_date,$end_date])
                ->orderBy('q_datetime', $orderby)
                ->offset($offset)->limit($limit)
                ->get();
                
                $response = array();
                $response['store_qna'] = $store_qna;
                $response['count'] = $count;
                $response['page'] = $page;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'view':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();

                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $store_id = $store->store_id;

                if (!$request->filled('id')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "store_Qna id 값 안넘어옴";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $query = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->select('store_qna.*', 'users.nickname', 'users.profile_img')
                ->where('store_id', $store_id)
                ->where('store_qna.id', $id)->first();

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $req)
    {
        switch ($req) {
            case 'answer':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();

                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $store_id = $store->store_id;
        
                if (!$request->filled('id', 'answer')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $a_email = $store->email;
                $a_name = $store->brandname;
                $a_hp = $store->tel;
                $a_ip = $_SERVER["REMOTE_ADDR"];
                $answer = $request->answer;
        
                try {
                    $update = DB::table('store_qna')->where('id', $id)->where('store_id', $store_id)->update([
                        'a_id' => $store->store_id,
                        'a_email' => $a_email,
                        'a_name' => $a_name,
                        'a_hp' => $a_hp,
                        'a_ip' => $a_ip,
                        'answer' => $answer,
                        'a_datetime' => DB::raw('now()'),
                        'status' => 1,
                    ]);
                    $this->res['query'] = $id;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                } catch (Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;
        }
  
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $req)
    {
        $uid = Auth::guard('api')->user()->id;
        $store = DB::table('seller_infor')->where('uid', $uid)->first();

        if (!isset($store->store_id)) {
            $this->res['query'] = null;
            $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $store_id = $store->store_id;

        if (!$request->filled('id')) {
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $id = $request->id;
        $delete = DB::table('store_qna')->where('store_id', $store_id)->whereIn('id', $id)->delete();

        if ($delete > 0) {
            $this->res['query'] = $delete;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        } else {
            $this->res['query'] = $delete;
            $this->res['msg'] = "실패";
            $this->res['state'] = config('res_code.NO_DATA');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
