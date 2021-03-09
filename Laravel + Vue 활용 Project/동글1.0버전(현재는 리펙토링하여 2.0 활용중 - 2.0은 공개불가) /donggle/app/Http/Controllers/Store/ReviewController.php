<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Hash;

class ReviewController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Review controller';
    }

    public function index()
    {
        return 'Review FOR STORE';
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

                $count = DB::table('review')
                ->join('items','items.item_id','=','review.item_id')
                ->join('users','users.id','=','review.writer_id')
                ->where('items.store_id', $store_id)
                ->count();

                $review = DB::table('review')
                ->join('items','items.item_id','=','review.item_id')
                ->join('users','users.id','=','review.writer_id')
                ->select('review.*','items.title','items.images','users.nickname','users.profile_img')
                ->where('items.store_id', $store_id)
                ->orderBy('review.created_at','DESC')
                ->limit($limit)
                ->get();
                
                $response = array();
                $response['review'] = $review;
                $response['count'] = $count;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;

            case 'list':
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
                    $search_select = 'title';
                }

                if($request->filled('searchKeyword')){
                    $search_text = '%'.$request->searchKeyword.'%';
                }else{
                    $search_text = '%%';
                } 
                


                $count = DB::table('review')
                ->join('items','items.item_id','=','review.item_id')
                ->join('users','users.id','=','review.writer_id')
                ->where('items.store_id', $store_id)
                ->where($search_select, 'like', $search_text)
                ->whereBetween('review.created_at',[$start_date,$end_date])
                ->count();

                $review = DB::table('review')
                ->join('items','items.item_id','=','review.item_id')
                ->join('users','users.id','=','review.writer_id')
                ->select('review.*','items.title','users.nickname','users.profile_img')
                ->where('items.store_id', $store_id)
                ->where($search_select, 'like', $search_text)
                ->whereBetween('review.created_at',[$start_date,$end_date])
                ->orderBy('review.created_at',$orderby)
                ->offset($offset)->limit($limit)
                ->get();
                
                $response = array();
                $response['review'] = $review;
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
                $query = DB::table('review')
                ->join('items','items.item_id','=','review.item_id')
                ->join('users','users.id','=','review.writer_id')
                ->select('review.*','items.title','users.nickname','users.profile_img')
                ->where('items.store_id', $store_id)
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
            case 'deleted_request':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid',$uid)->first();

                if(!isset($store->store_id)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $store_id = $store->store_id;
        
                if(!$request->filled('id','deleted_reason','deleted_detail')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $deleted_reason = $request->deleted_reason;
                $deleted_detail = $request->deleted_detail;
        
                try { 
                    $update = DB::table('review')->where('id',$id)->update([
                        'deleted_reason' => $deleted_reason,
                        'deleted_detail' => $deleted_detail,
                        'deleted' => 2,
                        'updated_at' => DB::raw('now()'),
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
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
