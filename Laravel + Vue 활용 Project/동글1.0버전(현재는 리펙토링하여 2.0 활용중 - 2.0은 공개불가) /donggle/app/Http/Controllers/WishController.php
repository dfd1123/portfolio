<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class WishController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Wish controller';
    }

    public function index()
    {
        return 'Wish FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $uid = Auth::guard('api')->id();
                $count = DB::table('wish')->where('uid', $uid)->count();
                $wishes = DB::table('wish')->where('uid', $uid)
                ->join('items', 'wish.item_id','=','items.item_id')
                ->join(
                    DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as color FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id) as color_table")
                    ,'color_table.item_id'
                    ,'='
                    ,'items.item_id'
                )
                ->join(
                    DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as size FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id) as size_table")
                    ,'size_table.item_id'
                    ,'='
                    ,'items.item_id'
                )
                ->select(
                    'items.item_id', 
                    'items.ca_id', 
                    'items.ca_name',
                    'items.company_name',
                    'items.store_id',
                    'items.seller_name', 
                    'items.title', 
                    'items.cust_price',  
                    'items.price', 
                    'items.images', 
                    'items.hash_tag',
                    DB::raw("GROUP_CONCAT(distinct color_table.color) AS color"),
                    DB::raw("GROUP_CONCAT(distinct size_table.size) AS size"),
                    DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                    DB::raw('(SELECT review_body FROM review WHERE item_id = items.item_id ORDER BY review.created_at DESC) AS last_review'),
                    DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                )
                ->where('items.delete_yn',0)
                ->where('items.sell_yn',1)
                ->groupBy('items.item_id')
                ->orderBy(DB::raw('(SELECT COUNT(order_id) FROM `order` WHERE item_id = items.item_id)','DESC'));

                if($request->filled('limit')){
                    $limit = $request->limit;
                    $wishes = $wishes->limit($limit);
                }

                if($request->filled('offset')){
                    $offset = $request->offset;
                    $wishes = $wishes->offset($offset);
                }

                $wishes = $wishes->get();

                $query = array(
                    "wishes" => $wishes,
                    "count" => $count
                );


                if(count($query) == 0){
                    $this->res['query'] = null;
                    $this->res['msg'] = "아직 생성된 문의가 없음";
                    $this->res['state'] = config('res_code.NO_DATA');
                }else{
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if(!$request->filled('item_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $uid = Auth::guard('api')->id();
        $user_id = Auth::guard('api')->user()->email;
        $user_name = Auth::guard('api')->user()->name;
        $item_id = $request->item_id;

        $exist = DB::table('wish')->where('uid',$uid)->where('item_id',$item_id)->exists();
        if($exist){
            try {
                $delete = DB::table('wish')->where('uid', $uid)->where('item_id', $item_id)->delete();
                $this->res['query'] = $delete;
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
        try {
            $insertId = DB::table('wish')->insertGetId([
                'uid' => $uid,
                'user_id' => $user_id,
                'user_name' => $user_name,
                'item_id' => $item_id,
            ]);
            $this->res['query'] = $insertId;
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
        
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {
        if(!$request->filled('item_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $uid = Auth::guard('api')->id();
        $item_id = $request->item_id;

        $exist = DB::table('wish')->where('uid',$uid)->where('item_id',$item_id)->exists();
        if(!$exist){
            $this->res['query'] = null;
            $this->res['msg'] = "이미 삭제되었거나 존재하지 않는 row!";
            $this->res['state'] = config('res_code.DUPLI_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        try {
            $delete = DB::table('wish')->where('uid',$uid)->where('item_id',$item_id)->delete();
            $this->res['query'] = $delete;
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
}
