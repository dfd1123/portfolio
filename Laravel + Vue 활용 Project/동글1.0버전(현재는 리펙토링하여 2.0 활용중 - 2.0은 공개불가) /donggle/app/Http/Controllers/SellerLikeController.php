<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class SellerLikeController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'SellerLike controller';
    }

    public function index()
    {
        return 'SellerLike FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $uid = Auth::guard('api')->id();
                $count = DB::table('seller_like')->where('uid',$uid)->count();
                $seller = DB::table('seller_like')
                ->join('seller_infor','seller_like.store_id','=','seller_infor.store_id')
                ->select(
                    'seller_infor.*',
                    DB::raw("1 AS like_yn"),
                    DB::raw("IFNULL((SELECT AVG(review.rating) FROM items INNER JOIN review ON items.item_id = review.item_id WHERE items.store_id = seller_infor.store_id),0) AS rating")
                )
                ->where('seller_like.uid',$uid)
                ->orderBy('seller_like.created_at','DESC');

                if($request->filled('limit')){
                    $limit = $request->limit;
                    $seller = $seller->limit($limit);
                }

                if($request->filled('offset')){
                    $offset = $request->offset;
                    $seller = $seller->offset($offset);
                }

                $seller = $seller->get();

                $query = array(
                    "seller" => $seller,
                    "count" => $count
                );


                if(count($query) == 0){
                    $this->res['query'] = null;
                    $this->res['msg'] = "아직 즐겨찾기한 스토어가 없음";
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
        if(!$request->filled('store_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $uid = Auth::guard('api')->id();
        $store_id = $request->store_id;

        $exist = DB::table('seller_like')->where('uid',$uid)->where('store_id',$store_id)->exists();
        if($exist){
            try {
                $delete = DB::table('seller_like')->where('uid', $uid)->where('store_id', $store_id)->delete();
                $this->res['query'] = $delete;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            
            } catch(Exception $e) {
                $this->res['query'] =null;
                $this->res['msg'] = "시스템 에러(쿼리)"; 
                $this->res['state'] = config('res_code.QUERY_ERR');
            }
        }
        try {
            $insert = DB::table('seller_like')->insert([
                'uid' => $uid,
                'store_id' => $store_id,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
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
        
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {
        if(!$request->filled('store_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $uid = Auth::guard('api')->id();
        $store_id = $request->store_id;

        $exist = DB::table('seller_like')->where('uid',$uid)->where('store_id',$store_id)->exists();
        if(!$exist){
            $this->res['query'] = null;
            $this->res['msg'] = "이미 삭제되었거나 존재하지 않는 row!";
            $this->res['state'] = config('res_code.DUPLI_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        try {
            $delete = DB::table('seller_like')->where('uid',$uid)->where('store_id',$store_id)->delete();
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
