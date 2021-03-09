<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Hash;

class ItemQnaController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'ItemQna controller';
    }

    public function index()
    {
        return 'ItemQna FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list_main':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid',$uid)->first();

                if(!isset($store->store_id)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $limit = ($request->filled('limit'))?$request->limit:5;
                
                $store_id = $store->store_id;

                $count = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->where('items.store_id', $store_id)
                ->where('items.delete_yn',0)
                ->count();

                $item_qna = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->select('item_qna.*','items.title','items.images')
                ->where('items.store_id', $store_id)
                ->where('items.delete_yn',0)
                ->orderBy('q_datetime','DESC')
                ->limit($limit)
                ->get();
                
                $response = array();
                $response['item_qna'] = $item_qna;
                $response['count'] = $count;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;

            case 'store_qa':
                if(!$request->filled('start_date','end_date')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid',$uid)->first();

                if(!isset($store->store_id)){
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

                if($request->filled('orderby')){
                    if($request->orderby == 'desc'){
                        $orderby = $request->orderby;
                    }else{
                        $orderby = $request->orderby;
                    }
                }else{
                    $orderby = 'desc';
                }

                if($request->filled('searchSelect')){
                    $search_select = $request->searchSelect;
                }else{
                    $search_select = 'q_name';
                }

                if($request->filled('searchKeyword')){
                    $search_text = '%'.$request->searchKeyword.'%';
                }else{
                    $search_text = '%%';
                } 
                


                $count = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->where('items.store_id', $store_id)
                ->where('items.delete_yn',0)
                ->where($search_select, 'like', $search_text)
                ->whereBetween('q_datetime',[$start_date,$end_date])
                ->count();

                $item_qna = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->select('item_qna.*','items.title','items.images')
                ->where('items.store_id', $store_id)
                ->where('items.delete_yn',0)
                ->where($search_select, 'like', $search_text)
                ->whereBetween('q_datetime',[$start_date,$end_date])
                ->orderBy('q_datetime',$orderby)
                ->offset($offset)->limit($limit)
                ->get();
                
                $response = array();
                $response['item_qna'] = $item_qna;
                $response['count'] = $count;
                $response['page'] = $page;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'view':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid',$uid)->first();

                if(!isset($store->store_id)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $store_id = $store->store_id;

                if(!$request->filled('id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Qna id 값 안넘어옴";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $query = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->select('item_qna.*','items.title','items.images')
                ->where('items.store_id', $store_id)
                ->where('items.delete_yn',0)
                ->where('id', $id)->first();

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        switch($req){
            case 'answer':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid',$uid)->first();

                if(!isset($store->store_id)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $store_id = $store->store_id;
        
                if(!$request->filled('id','answer')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $uid = Auth::guard('api')->id();
                $a_email = Auth::guard('api')->user()->email;
                $a_name = Auth::guard('api')->user()->name;
                $a_hp = Auth::guard('api')->user()->mobile_number;
                $a_ip = $_SERVER["REMOTE_ADDR"];
                $answer = $request->answer;
        
                try { 
                    $update = DB::table('item_qna')->where('id',$id)->update([
                        'a_id' => $uid,
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
                
                } catch(Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;
        }
  
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function destroy(Request $request, $req)
    {
        $uid = Auth::guard('api')->user()->id;
        $store = DB::table('seller_infor')->where('uid',$uid)->first();

        if(!isset($store->store_id)){
            $this->res['query'] = null;
            $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $store_id = $store->store_id;

        if(!$request->filled('id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $id = $request->id;
        $delete = DB::table('item_qna')->whereIn('id',$id)->delete();

        if($delete > 0){
            $this->res['query'] = $delete;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        }else{
            $this->res['query'] = $delete;
            $this->res['msg'] = "실패";
            $this->res['state'] = config('res_code.NO_DATA');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
