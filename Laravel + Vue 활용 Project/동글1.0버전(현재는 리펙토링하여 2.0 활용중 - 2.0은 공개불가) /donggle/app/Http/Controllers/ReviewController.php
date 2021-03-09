<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;

class ReviewController extends Controller
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

    public function __invoke($id)
    {
        return 'Review controller';
    }

    public function index()
    {
        return 'Review FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'item_review':
                if(!$request->filled('item_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "item_id 값 없음! 잘못된 경로로 접근!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:5;
                
                $offset = ($page - 1) * $limit;
                
                $item_id = $request->item_id;

                $count = DB::table('review')
                ->where('item_id', $item_id)
                ->where('deleted', 0)
                ->orderBy('created_at','DESC')
                ->count();
                    
                $reviews = DB::table('review')
                ->where('item_id', $item_id)
                ->where('deleted', 0)
                ->join('users', 'review.writer_id', '=', 'users.id')
                ->where('users.register_kind', '<>', 0)
                ->select(
                    "review.*",
                    "users.profile_img as user_image",
                    "users.nickname",
                    "users.name",
                    DB::raw("(SELECT count(*) FROM review_like WHERE review_id = review.id and recomend = 1) AS recomend"),
                    DB::raw("(SELECT count(*) FROM review_like WHERE review_id = review.id and unrecomend = 1) AS unrecomend"),
                    DB::raw("(SELECT count(*) FROM review_comment WHERE review_id = review.id) AS comment_cnt")
                )
                ->orderBy('review.created_at','DESC')
                ->offset($offset)->limit($limit)
                ->get();

                $item = DB::table('items')->where('item_id', $item_id)->first();
                
                $response = array();
                $response['reviews'] = $reviews;
                $response['item'] = $item;
                $response['count'] = $count;
                $response['page'] = $page;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'view':
                if(!$request->filled('review_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Review id 값 안넘어옴";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->review_id;
                $query = DB::table('review')
                        ->where('review.id', $id)
                        ->where('deleted', 0)
                        ->join('users', 'review.writer_id', '=', 'users.id')
                        ->join('items', 'review.item_id', '=', 'items.item_id')
                        ->select(
                            "review.*",
                            "users.profile_img as user_image",
                            "users.nickname",
                            "users.name",
                            "items.title as item_name",
                            "items.images as item_images",
                            "items.company_name",
                            "items.seller_id"
                        )
                        ->first();
                if($query == null){
                    $this->res['query'] = null;
                    $this->res['msg'] = "이미 삭제된 후기이거나 해당 id에 맞는 문의가 없음";
                    $this->res['state'] = config('res_code.NO_DATA');
                }else{
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }
            break;

            case 'order_check':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                if(!$request->filled('item_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $order = DB::table('order')
                ->select('order.order_no','review.id')
                ->leftJoin('review','review.order_no','=','order.order_no')
                ->where('order.s_uid',Auth::guard('api')->id())
                ->where('order.item_id',$request->item_id)
                ->where('order.deleted',0)
                ->where(function($query){
                    $query->where('order.od_status','order_complete')->orwhere('order.od_status','delivery_complete');
                })
                ->whereNull('review.id')
                ->first();

                if($order == null || $order->id != null){
                    $this->res['query'] = null;
                    $this->res['msg'] = "리뷰 작성은 주문 한번당 한번만 가능!";
                    $this->res['state'] = config('res_code.INFO_ERR');
                }else{
                    $this->res['query'] = $order;
                    $this->res['msg'] = "리뷰 작성 가능!";
                    $this->res['state'] = config('res_code.OK');
                }
            break;

            case 'possible_review_list':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if($request->filled('limit')){
                    $limit = $request->limit;
                }else{
                    $limit = 15;
                }

                if($request->filled('offset')){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $count = DB::table('order')
                ->select('order.order_no','review.id')
                ->leftJoin('review','review.order_no','=','order.order_no')
                ->leftJoin('users', 'review.writer_id', '=', 'users.id')
                ->where('order.s_uid',Auth::guard('api')->id())
                ->where('order.deleted',0)
                ->where(function($query){
                    $query->where('order.od_status','order_complete')->orwhere('order.od_status','delivery_complete');
                })
                ->whereNull('review.id')
                ->count();

                $orders = DB::table('order')
                ->join('items', 'order.item_id', '=', 'items.item_id')
                ->leftJoin('review','review.order_no','=','order.order_no')
                ->where('order.s_uid',Auth::guard('api')->id())
                ->where('order.deleted',0)
                ->where(function($query){
                    $query->where('order.od_status','order_complete')->orwhere('order.od_status','delivery_complete');
                })
                ->whereNull('review.id')
                ->select(
                    'order.*',
                    'items.images', 
                    'items.store_id', 
                    'items.company_name',
                    'items.title',
                    'items.ca_name',
                    DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating')
                )
                ->limit($limit)
                ->offset($offset)
                ->get();

                $query = array(
                    "count" => $count,
                    "orders" => $orders
                );

                $this->res['query'] = $query;
                $this->res['msg'] = "리뷰 작성 가능!";
                $this->res['state'] = config('res_code.OK');

                break;

            case 'my_review_list':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if($request->filled('limit')){
                    $limit = $request->limit;
                }else{
                    $limit = 15;
                }

                if($request->filled('offset')){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $count = DB::table('review')->where('writer_id', Auth::guard('api')->id())->where('deleted', 0)->count();

                $reviews = DB::table('review')
                ->where('writer_id', Auth::guard('api')->id())
                ->where('review.deleted', 0)
                ->leftJoin('items', 'review.item_id', '=', 'items.item_id')
                ->leftJoin('order', 'review.order_no', '=', 'order.order_no')
                ->select('review.*', 'items.title', 'items.images', 'items.store_id', 'items.company_name', 'order.qty')
                ->limit($limit)
                ->offset($offset)
                ->get();

                $query = array(
                    "count" => $count,
                    "reviews" => $reviews
                );

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

                break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if(!$request->filled('item_id', 'review_body', 'rating')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        //dd($request->images);

        $order = DB::table('order')
        ->select('order.order_no','order.option','order.option_subject','review.id')
        ->leftJoin('review','review.order_no','=','order.order_no')
        ->where('order.s_uid',Auth::guard('api')->id())
        ->where('order.item_id',$request->item_id)
        ->where('order.deleted',0)
        ->where(function($query){
            $query->where('order.od_status','order_complete')->orwhere('order.od_status','delivery_complete');
        })
        ->whereNull('review.id')
        ->first();

        

        if($order == null || $order->id != null){
            $this->res['query'] = null;
            $this->res['msg'] = "주문 한번당 리뷰 횟수는 한번만 가능!";
            $this->res['state'] = config('res_code.INFO_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if($request->hasFile('images')){
            $image = File_store::Image_store('review', $request->images);
            if($image == 'EXT_ERR'){  //이미지 에러
                $this->res['query'] =null;
                $this->res['msg'] = "이미지 확장자 에러!"; 
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($image == 'VALID_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "이미지가 아님!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($image == 'PARAM_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "이미지 첨부 필수!";
                $this->res['state'] = config('res_code.PARAM_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        }else{
            $image = array();
        }
        
        $item_id = $request->item_id;
        $order_no = $order->order_no;
        $item_option = $order->option;
        $option_subject = $order->option_subject;
        $writer_id = Auth::guard('api')->id();
        $writer_name = Auth::guard('api')->user()->name;
        $profile_img = Auth::guard('api')->user()->profile_img;
        $unickname = Auth::guard('api')->user()->nickname;
        $review_title = $request->review_title;
        $review_body = $request->review_body;
        $rating = $request->rating;
        $writer_ip = $_SERVER["REMOTE_ADDR"];
        
        try {
            $insertId = DB::table('review')->insertGetId([
                'item_id' => $item_id,
                'order_no' => $order_no,
                'item_option' => $item_option,
                'option_subject' => $option_subject,
                'writer_id' => $writer_id,
                'writer_name' => $writer_name,
                'profile_img' => $profile_img,
                'unickname' => $unickname,
                'review_title' => $review_title,
                'review_body' => $review_body,
                'image' => json_encode($image),
                'rating' => $rating,
                'writer_ip' => $writer_ip,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
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
        switch($req){
            case 'update':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if(!$request->filled('review_id','review_title', 'review_body', 'rating')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $review_id = $request->review_id;
                $review_title = $request->review_title;
                $review_body = $request->review_body;
                $rating = $request->rating;

                $review = DB::table('review')->where('id', $review_id)->first();

                if($request->hasFile('image')){
                    $image = File_store::Image_update('review', $request->image, json_decode($review->image), $request->index);
                    if($image == 'EXT_ERR'){  //이미지 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "이미지 확장자 에러!"; 
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($image == 'VALID_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "이미지가 아님!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($image == 'PARAM_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "이미지 첨부 필수!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                    
                }else{
                    $image = json_decode($review->image);
                }

                try {
                    $update = DB::table('review')->where('id',$review_id)->where('writer_id',$uid)->update([
                        'review_title' => $review_title,
                        'review_body' => $review_body,
                        'image' => json_encode($image),
                        'rating' => $rating,
                        'updated_at' => DB::raw('now()'),
                    ]);
                    $this->res['query'] = $review_id;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                
                } catch(Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;

            case 'delete':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if(!$request->filled('review_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $review_id = $request->review_id;
                
                $delete = DB::table('review')->where('id',$review_id)->where('writer_id',$uid)->update([
                    "deleted" => 1,
                ]);
                
        
                if($delete == 0){
                    $this->res['query'] = $delete;
                    $this->res['msg'] = "권한없거나 존재하지 않는 ID";
                    $this->res['state'] = config('res_code.NO_DATA');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $this->res['query'] = $delete;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;
        }

  
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {
        


        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
