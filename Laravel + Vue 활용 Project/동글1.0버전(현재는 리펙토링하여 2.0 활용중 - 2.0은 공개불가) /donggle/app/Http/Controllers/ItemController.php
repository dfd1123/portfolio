<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use App;
use DB;
use Auth;

class ItemController extends Controller
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
        switch($req){
            case 'best_list': 
                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $limit = ($request->filled('limit'))?$request->limit:100;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                try {
                    //아이템정보
                    $items = DB::table('items')->where('items.sell_yn', 1)->where('items.delete_yn', 0);
                    $items = $items->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_id',  
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->where('items.hit','>',30)
                    ->groupBy('items.item_id');

                    $count = count($items->get());

                    if($request->filled('orderBy')){
                        if($request->orderBy == 'popular'){
                            $items->orderBy('items.hit','DESC');
                        }else if($request->orderBy == 'new'){
                            $items->orderBy('items.created_at','DESC');
                        }else if($request->orderBy == 'lowPrice'){
                            $items->orderBy('items.price','ASC');
                        }else if($request->orderBy == 'highPrice'){
                            $items->orderBy('items.price','DESC');
                        }else if($request->orderBy == 'rating'){
                            $items->orderBy('rating','DESC');
                        }else{
                            $items->orderBy('items.hit','DESC');
                        }
                    }else{
                        $items->orderBy('items.hit','DESC');
                    }

                    $items = $items->offset($offset)->limit($limit)->get();
                    
                    $response = array();
                    $response['items'] = $items;
                    $response['count'] =  $count;


                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 
            
            case 'popular_list': 
                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $limit = ($request->filled('limit'))?$request->limit:100;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                try {
                    //아이템정보
                    $items = DB::table('items')
                    ->where('items.sell_yn', 1)
                    ->where('items.delete_yn', 0)
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_id',  
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->where('items.delete_yn', 0)
                    ->where('items.hit')
                    ->where('items.type4',1)
                    ->groupBy('items.item_id');
                    
                    $count = count($items->get());

                    if($request->filled('orderBy')){
                        if($request->orderBy == 'popular'){
                            $items->orderBy('items.hit','DESC');
                        }else if($request->orderBy == 'new'){
                            $items->orderBy('items.created_at','DESC');
                        }else if($request->orderBy == 'lowPrice'){
                            $items->orderBy('items.price','ASC');
                        }else if($request->orderBy == 'highPrice'){
                            $items->orderBy('items.price','DESC');
                        }else if($request->orderBy == 'rating'){
                            $items->orderBy('rating','DESC');
                        }else{
                            $items->orderBy('items.hit','DESC');
                        }
                    }else{
                        $items->orderBy('items.hit','DESC');
                    }

                    $items = $items->offset($offset)->limit($limit)->get();
                    $response = array();
                    $response['items'] = $items;
                    $response['count'] =  $count;

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'week_list': 
                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $limit = ($request->filled('limit'))?$request->limit:100;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                try {
                    //아이템정보
                    //$items = DB::table('items')->where('items.created_at','>=',DB::raw('date_add( NOW(), INTERVAL -7 DAY)'));
                    $items = DB::table('items')
                    ->where('items.sell_yn', 1)
                    ->where('items.delete_yn', 0)
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_id',  
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->where('items.created_at','>=',DB::raw('date_add( NOW(), INTERVAL -7 DAY)'))
                    ->groupBy('items.item_id');
                    
                    $count = count($items->get());

                    if($request->filled('orderBy')){
                        if($request->orderBy == 'popular'){
                            $items->orderBy('items.hit','DESC');
                        }else if($request->orderBy == 'new'){
                            $items->orderBy('items.created_at','DESC');
                        }else if($request->orderBy == 'lowPrice'){
                            $items->orderBy('items.price','ASC');
                        }else if($request->orderBy == 'highPrice'){
                            $items->orderBy('items.price','DESC');
                        }else if($request->orderBy == 'rating'){
                            $items->orderBy('rating','DESC');
                        }else{
                            $items->orderBy('items.hit','DESC');
                        }
                    }else{
                        $items->orderBy('items.created_at','DESC');
                    }

                    $items = $items->offset($offset)->limit($limit)->get();

                    $response = array();
                    $response['items'] = $items;
                    $response['count'] =  $count;


                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'self_list': 
                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $limit = ($request->filled('limit'))?$request->limit:100;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                try {
                    //아이템정보
                    $items = DB::table('items')->where('items.sell_yn', 1)->where('items.delete_yn', 0)->where('items.self_type',1);
                    $items = $items
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_id',  
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->groupBy('items.item_id');

                    $count = count($items->get());

                    if($request->filled('orderBy')){
                        if($request->orderBy == 'popular'){
                            $items->orderBy('items.hit','DESC');
                        }else if($request->orderBy == 'new'){
                            $items->orderBy('items.created_at','DESC');
                        }else if($request->orderBy == 'lowPrice'){
                            $items->orderBy('items.price','ASC');
                        }else if($request->orderBy == 'highPrice'){
                            $items->orderBy('items.price','DESC');
                        }else if($request->orderBy == 'rating'){
                            $items->orderBy('rating','DESC');
                        }else{
                            $items->orderBy('items.hit','DESC');
                        }
                    }else{
                        $items->orderBy('items.hit','DESC');
                    }

                    $items = $items->offset($offset)->limit($limit)->get();

                    $response = array();
                    $response['items'] = $items;
                    $response['count'] =  $count;


                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'new_list': 
                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $limit = ($request->filled('limit'))?$request->limit:100;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                try {
                    //아이템정보
                    $items = DB::table('items')->where('items.sell_yn', 1)->where('items.delete_yn', 0)->where('items.created_at','>=',DB::raw('date_add( NOW(), INTERVAL -3 DAY)'));
                    $count = $items->count();
                    $items = $items
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_id',  
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->groupBy('items.item_id')
                    ->orderBy('items.created_at','DESC')
                    ->offset($offset)->limit($limit)->get();
                    $response = array();
                    $response['items'] = $items;
                    $response['count'] =  $count;


                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'store_list': 
                if(!$request->filled('store_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $store_id = $request->store_id;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }

                $limit = ($request->filled('limit'))?$request->limit:100;

                $orderBy = $request->orderBy;
                
                try {
                    //아이템정보
                    $count = DB::table('items')->where('items.sell_yn', 1)->where('items.delete_yn', 0)->where('items.store_id',$store_id)->count();
                    $items = DB::table('items')
                    ->where('items.sell_yn', 1)
                    ->where('items.delete_yn', 0)
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name',
                        'items.seller_id',  
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->where('items.store_id',$store_id)
                    ->groupBy('items.item_id');

                    if($request->filled('orderBy')){
                        if($request->orderBy == 'popular'){
                            $items->orderBy('items.hit','DESC');
                        }else if($request->orderBy == 'new'){
                            $items->orderBy('items.created_at','DESC');
                        }else if($request->orderBy == 'lowPrice'){
                            $items->orderBy('items.price','ASC');
                        }else if($request->orderBy == 'highPrice'){
                            $items->orderBy('items.price','DESC');
                        }else if($request->orderBy == 'rating'){
                            $items->orderBy('rating','DESC');
                        }else{
                            $items->orderBy('items.hit','DESC');
                        }
                    }else{
                        $items->orderBy('items.hit','DESC');
                    }

                    $items = $items->offset($offset)->limit($limit)->get();

                    $response = array(
                        "items" => $items,
                        "count" => $count
                    );

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'search_list': 

                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }
                $limit = ($request->filled('limit'))?$request->limit:15;
                
                $searchKeyword = ($request->filled('searchKeyword'))?'%'.$request->searchKeyword.'%':'%%';

                $ca_name = ($request->filled('ca_name'))?'%'.$request->ca_name.'%':'%%';
                $gender = $request->gender;
                
                $min_price = ($request->filled('min_price'))?$request->min_price:0;
                $max_price = ($request->filled('max_price'))?$request->max_price:9999999999;
                $min_size = ($request->filled('min_size'))?$request->min_size:0;
                $max_size = ($request->filled('max_size'))?$request->max_size:9999999999;
                
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                try {
                    DB::enableQueryLog();
                    //아이템정보
                    $items = DB::table('items')->where('items.sell_yn', 1)->where('items.delete_yn', 0)
                    ->where('items.price','>=',$min_price)
                    ->where('items.price','<=',$max_price)
                    ->where(function($query) use ($min_size,$max_size){
                        $query->where(function($q) use ($min_size,$max_size){
                            $q->where('items.min_size','>=',$min_size)->where('items.min_size','<=',$max_size);
                        })->orwhere(function($q) use ($min_size,$max_size){
                            $q->where('items.max_size','>=',$min_size)->where('items.max_size','<=',$max_size);
                        });
                    });

                    if($request->filled('gender')){
                        $items = $items->where('items.gender',$gender);
                    }
                    

                    if($searchKeyword != '%%'){
                        $items->where(function($query) use ($searchKeyword, $request){
                            $query->where('title','like',$searchKeyword)->orwhere('ca_name','like',$searchKeyword);
                        });
                    }

                    if($request->filled('hash_tag')){
                        $hash_tags = $request->hash_tag;
                        $items->where(function($query) use($hash_tags){
                            foreach($hash_tags as $hash_tag){
                                $query->orwhere('items.hash_tag','like','%'.$hash_tag.'%');
                            }
                        });
                    }
                    

                    if($request->filled('item_id')){
                        $items = $items->where('items.item_id', $request->item_id);
                    }

                    if($request->filled('age')){
                        $ages = $request->age;
                        $items->where(function($query) use($ages){
                            foreach($ages as $age){
                                $query->orwhere('items.age','like','%'.$age.'%');
                            }
                        });
                    }

                    if($request->filled('color')){
                        $colors = $request->color;
                        $items->where(function($query) use($colors){
                            
                            foreach($colors as $color){
                                if($color == '기타색상'){
                                    $etc_colors = DB::table("colors")->select("color_name")->where("kind",2)->get();
                                    foreach($etc_colors as $etc_color){
                                        $query->orwhere('items.color','like','%,'.$etc_color->color_name.',%')
                                        ->orwhere('items.color','like','%,'.$etc_color->color_name)
                                        ->orwhere('items.color','like',$etc_color->color_name.',%')
                                        ->orwhere('items.color',$etc_color->color_name);
                                    }
                                    //$query->orwhereRaw("items.color IN (SELECT color_name FROM colors WHERE kind = 2)");
                                }else{
                                    $query->orwhere('items.color','like','%,'.$color.',%')
                                    ->orwhere('items.color','like','%,'.$color)
                                    ->orwhere('items.color','like',$color.',%')
                                    ->orwhere('items.color',$color);
                                }
                            }
                        });
                    }
                    
                    if($request->filled('orderBy')){
                        if($request->orderBy == 'popular'){
                            $items->orderBy('items.hit','DESC');
                        }else if($request->orderBy == 'new'){
                            $items->orderBy('items.created_at','DESC');
                        }else if($request->orderBy == 'lowPrice'){
                            $items->orderBy('items.price','ASC');
                        }else if($request->orderBy == 'highPrice'){
                            $items->orderBy('items.price','DESC');
                        }else if($request->orderBy == 'rating'){
                            $items->orderBy('rating','DESC');
                        }else{
                            $items->orderBy('items.hit','DESC');
                        }
                    }else{
                        $items->orderBy('items.hit','DESC');
                    }

                    $count = $items->count();
                    $items = $items
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->groupBy('items.item_id')->offset($offset)->limit($limit)->get();
                    //dd(DB::getQueryLog());
                    $response = array();
                    $response['items'] = $items;
                    $response["offset"] = $offset;
                    $response["searchKeyword"] = $request->searchKeyword;
                    $response["count"] =  $count;

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'search_cateby':  
                if(!$request->filled('ca_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "카테고리id 값 없음! 잘못된 경로로 접근!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                $ca_id = $request->ca_id;
                $length = strlen($ca_id);

                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }
                $limit = ($request->filled('limit'))?$request->limit:15;
                
                $searchKeyword = ($request->filled('searchKeyword'))?'%'.$request->searchKeyword.'%':'%%';

                $ca_name = ($request->filled('ca_name'))?'%'.$request->ca_name.'%':'%%';
                $gender = $request->gender;
                
                $min_price = ($request->filled('min_price'))?$request->min_price:0;
                $max_price = ($request->filled('max_price'))?$request->max_price:9999999999;
                $min_size = ($request->filled('min_size'))?$request->min_size:0;
                $max_size = ($request->filled('max_size'))?$request->max_size:9999999999;
                

                try {
                    DB::enableQueryLog();
                    //아이템정보
                    $items = DB::table('items')
                    ->where('items.sell_yn', 1)
                    ->where('items.delete_yn', 0)
                    ->whereRaw("SUBSTRING(ca_id,1,".$length.") = '".$ca_id."'")
                    ->where('items.price','>=',$min_price)
                    ->where('items.price','<=',$max_price)
                    ->where(function($query) use ($min_size,$max_size){
                        $query->where(function($q) use ($min_size,$max_size){
                            $q->where('items.min_size','>=',$min_size)->where('items.min_size','<=',$max_size);
                        })->orwhere(function($q) use ($min_size,$max_size){
                            $q->where('items.max_size','>=',$min_size)->where('items.max_size','<=',$max_size);
                        });
                    });

                    if($request->filled('gender')){
                        $items = $items->where('items.gender',$gender);
                    }

                    if($searchKeyword != '%%'){
                        $items->where(function($query) use ($searchKeyword, $request){
                            $query->where('title','like',$searchKeyword)->orwhere('ca_name','like',$searchKeyword);
                        });
                    }

                    if($request->filled('hash_tag')){
                        $hash_tags = $request->hash_tag;
                        $items->where(function($query) use($hash_tags){
                            foreach($hash_tags as $hash_tag){
                                $query->orwhere('items.hash_tag','like','%'.$hash_tag.'%');
                            }
                        });
                    }
                    

                    if($request->filled('age')){
                        $ages = $request->age;
                        $items->where(function($query) use($ages){
                            foreach($ages as $age){
                                $query->orwhere('items.age','like','%'.$age.'%');
                            }
                        });
                    }
                    if($request->filled('color')){
                        $colors = $request->color;
                        $items->where(function($query) use($colors){
                            foreach($colors as $color){
                                if($color == '기타색상'){
                                    $etc_colors = DB::table("colors")->select("color_name")->where("kind",2)->get();
                                    foreach($etc_colors as $etc_color){
                                        $query->orwhere('items.color','like','%,'.$etc_color->color_name.',%')
                                        ->orwhere('items.color','like','%,'.$etc_color->color_name)
                                        ->orwhere('items.color','like',$etc_color->color_name.',%')
                                        ->orwhere('items.color',$etc_color->color_name);
                                    }
                                    //$query->orwhereRaw("items.color IN (SELECT color_name FROM colors WHERE kind = 2)");
                                }else{
                                    $query->orwhere('items.color','like','%,'.$color.',%')
                                    ->orwhere('items.color','like','%,'.$color)
                                    ->orwhere('items.color','like',$color.',%')
                                    ->orwhere('items.color',$color);
                                }
                            }
                        });
                    }
                    if($request->filled('orderBy')){
                        if($request->orderBy == 'popular'){
                            $items->orderBy('items.hit','DESC');
                        }else if($request->orderBy == 'new'){
                            $items->orderBy('items.created_at','DESC');
                        }else if($request->orderBy == 'lowPrice'){
                            $items->orderBy('items.price','ASC');
                        }else if($request->orderBy == 'highPrice'){
                            $items->orderBy('items.price','DESC');
                        }else if($request->orderBy == 'rating'){
                            $items->orderBy('rating','DESC');
                        }else{
                            $items->orderBy('items.hit','DESC');
                        }
                    }else{
                        $items->orderBy('items.hit','DESC');
                    }
                    
                    $count = $items->where('items.sell_yn', 1)->where('items.delete_yn', 0)->count();
                    $items = $items
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->groupBy('items.item_id')
                    ->offset($offset)->limit($limit)->get();

                    $response = array();
                    $response['items'] = $items;
                    $response["offset"] = $offset;
                    $response["searchKeyword"] = $request->searchKeyword;
                    $response["count"] =  $count;

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'search_idby':  
                $item_id = $request->item_id;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;

                if($request->filled('offset') && $request->offset >= 0){
                    $offset = $request->offset;
                }else{
                    $offset = 0;
                }
                $limit = ($request->filled('limit'))?$request->limit:16;
                

                try {
                    DB::enableQueryLog();
                    //아이템정보
                    $items = DB::table('items')
                    ->where('items.sell_yn', 1)
                    ->where('items.delete_yn', 0)
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1) as color, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 2),',',-1), item_id, id) as color_table")
                        ,'color_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->join(
                        DB::raw("(SELECT item_id, SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1) as size, id FROM donggle.item_option group by SUBSTRING_INDEX(SUBSTRING_INDEX(name,',', 1),',',-1), item_id, id) as size_table")
                        ,'size_table.item_id'
                        ,'='
                        ,'items.item_id'
                    )
                    ->select(
                        'items.item_id', 
                        'items.ca_id', 
                        'items.ca_name', 
                        'items.seller_name', 
                        'items.company_name', 
                        'items.title', 
                        'items.possible_ready_term',
                        'items.cust_price',  
                        'items.price', 
                        'items.images', 
                        'items.hash_tag',
                        'items.store_id',
                        DB::raw("GROUP_CONCAT(distinct color_table.color ORDER BY color_table.id) AS color"), 
                        DB::raw("GROUP_CONCAT(distinct size_table.size ORDER BY size_table.id) AS size"), 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->whereIn('items.item_id', $item_id)
                    ->groupBy('items.item_id')
                    ->offset($offset)->limit($limit);
                    
                    $items = $items->get();
                    $response = array();
                    $response['items'] = $items;
                    $response["offset"] = $offset;

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'view': 
                if(!$request->filled('item_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "item_id 값 없음! 잘못된 경로로 접근!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $item_id = $request->item_id;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                try {
                    //아이템정보
                    $item = DB::table('items')
                    ->select(
                        'items.*', 
                        DB::raw('(IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0)) AS rating'), 
                        DB::raw('(IFNULL((SELECT count(order_no) FROM `order` WHERE item_id = items.item_id and od_status = \'order_complete\'),0)) AS ordering'),
                        DB::raw('(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id),0)) AS wishes'),
                        DB::raw("(IFNULL((SELECT count(wish_id) FROM `wish` WHERE item_id = items.item_id AND uid = '".$uid."'),0)) AS zzim")
                    )
                    ->where('items.item_id', $item_id)
                    ->groupBy('items.item_id')
                    ->limit(1)->first();
                    
                    $item_option = DB::table('item_option')
                    ->where('item_id', $item_id)->get();

                    $response = array();
                    $response['item'] = $item;
                    $response['item_option'] = $item_option;

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                        
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if(!$request->filled("ca_id","ca_name","age","gender","title", "possible_ready_term", "simple_intro","introduce_pc","introduce_mobile","make_company","orgin_range","brand","info_gubun","max_size","min_size","color","tax_mny","option_subject","hash_tag","how_clean","material","etc_detail_info")
        || !$request->hasFile('images')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try{
            // 매장정보 가져오기 
            $seller = DB::table('seller_infor')->where('uid',Auth::guard('api')->user()->id)->first();

            if($seller == null){
                $this->res['query'] = null;
                $this->res['msg'] = "판매자 정보가 없음!";
                $this->res['state'] = config('res_code.NO_DATA');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        }catch(exception $e){
            $this->res['query'] =null;
            $this->res['msg'] = "판매자 정보 조회 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try{
            // 플랫폼 회사 정보 가져오기 
            $company = DB::table('company')->first();
            if($company == null){
                $this->res['query'] = null;
                $this->res['msg'] = "플랫폼 회사 정보가 없음!";
                $this->res['state'] = config('res_code.NO_DATA');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        }catch(exception $e){
            $this->res['query'] =null;
            $this->res['msg'] = "플랫폼 정보 조회 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        try{
            /*상품 field 정리 및 상품 등록 START*/
            $images = File_store::Image_store('items', $request->images);
            if($images == 'EXT_ERR'){  //이미지 에러
                $this->res['query'] =null;
                $this->res['msg'] = "상품 이미지 확장자 에러!"; 
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($images == 'VALID_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "상품 이미지가 아님!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($images == 'PARAM_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "상품 이미지 첨부 필수!";
                $this->res['state'] = config('res_code.PARAM_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
            //$itemArr = $this->ItemFieldArray($request, $seller, $company, 'create');
            $item_id = DB::table('items')->insertGetId([
                "ca_id" => $request->ca_id,
                "ca_name" => $request->ca_name,
                "seller_id" => $seller->uid,
                "seller_name" => $seller->ceo_name,
                "seller_img" => $seller->profile_img,
                "store_id" => $seller->store_id,
                "company_name" => $seller->brandname,
                "company_profile_img" => $seller->image,
                "gender" => $request->gender,
                "age" => $request->age,
                "title" => $request->title,
                "images" => json_encode($images),
                "simple_intro" => $request->simple_intro,
                "possible_ready_term" => $request->possible_ready_term,
                "introduce_pc" => $request->introduce_pc,
                "introduce_mobile" => $request->introduce_mobile,
                "make_company" => $request->make_company,
                "orgin_range" => $request->orgin_range,
                "brand" => $request->brand,
                "model" => $request->model,
                "info_gubun" => $request->info_gubun,
                "max_size" => $request->max_size,
                "min_size" => $request->min_size,
                "color" => $request->color,
                "cust_price" => bcmul($request->tax_mny, 2, 0),
                "tax_mny" => $request->tax_mny,
                "vat_mny" => bcmul($request->tax_mny, 0.1, 0),
                "price" => $this->MakeDongglePrice($request->tax_mny, $company),
                "fee_price" => $this->FeePrice($request->tax_mny, $company),
                "buy_min_qty" => 1,
                "buy_max_qty" => 9999999,
                "stock_qty" => 9999999,
                "noti_qty" => 0,
                "sell_yn" => 1,
                "type1" => 0, // 히트상품(0 or 1)
                "type2" => 0, // 추천상품(0 or 1)
                "type3" => 0, // 신상품(0 or 1)
                "type4" => 0, // 인기상품(0 or 1)
                "type5" => 0, // 할인상품(0 or 1)
                "option_subject" => $request->option_subject,
                "order" => 0,
                "coupon_yn" => 1,
                "soldout_yn" => 0,
                "delete_yn" => 0,
                "sc_type" => $company->send_cost_case, //
                "sc_method" => 0, //
                "sc_price" => $company->send_cost, //
                "sc_minimum" => 0, //
                "sc_qty" => 0, //
                "hash_tag" => $request->hash_tag,
                "how_clean" => json_encode($request->how_clean),
                "material" => json_encode($request->material),
                "etc_detail_info" => json_encode($request->etc_detail_info)
            ]);

        }catch(exception $e){
            $this->res['query'] =null;
            $this->res['msg'] = "상품 등록 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try{
            /*옵션 field 정리 및 옵션 등록 START*/
            
            foreach(json_decode($request->options) as $option){
                //$op_image = File_store::Image_store('items', $request->op_images);
                //$optionArr = $this->OptionFieldArray($option, $item_id, $company, 'create');
                $status = DB::table('item_option')->insert([
                    "item_id" => $item_id,
                    "name" => $option->op_name,
                    //"image" => json_encode($op_image),
                    "type" => 0,
                    "tax_mny" => $option->op_tax_mny, // 
                    "vat_mny" => bcmul($option->op_tax_mny, 0.1, 0),
                    "price" => $this->MakeDongglePrice($option->op_tax_mny, $company),
                    "fee_price" => $this->FeePrice($option->op_tax_mny, $company),
                    "notax" => $option->op_notax,
                    "stock_qty" => 9999999,
                    "noti_qty" => 0,
                ]);
                if(!$status){
                    $this->res['query'] =null;
                    $this->res['msg'] = "옵션 등록 실패";
                    $this->res['state'] = config('res_code.NO_DATA');
                }
            }
        }catch(exception $e){
            $this->res['query'] =null;
            $this->res['msg'] = "옵션 등록 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $this->res['query'] = $item_id;
        $this->res['msg'] = "성공!";
        $this->res['state'] = config('res_code.OK');
       
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        switch($req){
            case 'item_update':
                if(!$request->filled("item_id","ca_id","ca_name","title", "possible_ready_term", "simple_intro","introduce_pc","introduce_mobile","make_company","orgin_range","brand","info_gubun","max_size","min_size","color","tax_mny","option_subject","hash_tag","how_clean","material","etc_detail_info")){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                try{
                    // 매장정보 가져오기 
                    $seller = DB::table('seller_infor')->where('uid',Auth::guard('api')->user()->id)->first();
        
                    if($seller == null){
                        $this->res['query'] = null;
                        $this->res['msg'] = "판매자 정보가 없음!";
                        $this->res['state'] = config('res_code.NO_DATA');
        
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "판매자 정보 조회 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                
                try{
                    /*상품 field 정리 및 상품 수정 START*/
                    $item = DB::table('items')->where('item_id',$request->item_id)->first();
                    if($request->hasFile('images')){
                        $images = File_store::Image_update('items', $request->images, json_decode($item->images), $request->index);
                        if($images == 'EXT_ERR'){  //이미지 에러
                            $this->res['query'] =null;
                            $this->res['msg'] = "상품 이미지 확장자 에러!"; 
                            $this->res['state'] = config('res_code.EXT_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }else if($images == 'VALID_ERR'){
                            $this->res['query'] = null;
                            $this->res['msg'] = "상품 이미지가 아님!";
                            $this->res['state'] = config('res_code.IMG_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }else if($images == 'PARAM_ERR'){
                            $this->res['query'] = null;
                            $this->res['msg'] = "상품 이미지 첨부 필수!";
                            $this->res['state'] = config('res_code.PARAM_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }
                    }else{
                        $images = json_decode($item->images);
                    }
                    
                    $status = DB::table('items')->where('item_id', $request->item_id)->update([
                        "ca_id" => $request->ca_id,
                        "ca_name" => $request->ca_name,
                        "seller_id" => $seller->uid,
                        "store_id" => $seller->store_id,
                        "company_name" => $seller->brandname,
                        "company_profile_img" => $seller->image,
                        "gender" => $request->gender,
                        "age" => $request->age,
                        "title" => $request->title,
                        "images" => json_encode($images),
                        "simple_intro" => $request->simple_intro,
                        "possible_ready_term" => $request->possible_ready_term,
                        "introduce_pc" => $request->introduce_pc,
                        "introduce_mobile" => $request->introduce_mobile,
                        "make_company" => $request->make_company,
                        "orgin_range" => $request->orgin_range,
                        "brand" => $request->brand,
                        "model" => $request->model,
                        "info_gubun" => $request->info_gubun,
                        "max_size" => $request->max_size,
                        "min_size" => $request->min_size,
                        "color" => $request->color,
                        "cust_price" => bcmul($request->tax_mny, 2, 0),
                        "tax_mny" => $request->tax_mny,
                        "vat_mny" => bcmul($request->tax_mny, 0.1, 0),
                        "price" => $this->MakeDongglePrice($request->tax_mny, $company),
                        "fee_price" => $this->FeePrice($request->tax_mny, $company),
                        "option_subject" => $request->option_subject,
                        "hash_tag" => $request->hash_tag,
                        "how_clean" => json_encode($request->how_clean),
                        "material" => json_encode($request->material),
                        "etc_detail_info" => json_encode($request->etc_detail_info)
                    ]);
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "상품 수정 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                try{
                    /*옵션 field 정리 및 옵션 수정 START*/
                    foreach(json_decode($request->options) as $option){
                        //$optionArr = $this->OptionFieldArray($option, $item_id, $company, 'create');
                        DB::table('item_option')->where('item_id',  $request->item_id)->delete();
                        $status = DB::table('item_option')->insert([
                            "item_id" => $item_id,
                            "name" => $option->op_name,
                            //"image" => json_encode($op_image),
                            "type" => 0,
                            "tax_mny" => $option->op_tax_mny, // 
                            "vat_mny" => bcmul($option->op_tax_mny, 0.1, 0),
                            "price" => $this->MakeDongglePrice($option->op_tax_mny, $company),
                            "fee_price" => $this->FeePrice($option->op_tax_mny, $company),
                            "notax" => $option->op_notax,
                            "stock_qty" => 9999999,
                            "noti_qty" => 0,
                        ]);
                        if(!$status){
                            $this->res['query'] = $status;
                            $this->res['msg'] = "옵션 등록 실패";
                            $this->res['state'] = config('res_code.NO_DATA');
                        }
                    }
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "옵션 등록 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                $this->res['query'] = $item_id;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');

            break;

            case 'item_delete':
                try{
                    $status = DB::table('items')->where('item_id', $request->item_id)->update([
                        "delete_yn" => 1
                    ]);

                    if(!$status){
                        $this->res['query'] = $status;
                        $this->res['msg'] = "상품 삭제 실패";
                        $this->res['state'] = config('res_code.NO_DATA');
                    }
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "상품 삭제 시스템 에러(쿼리)";
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

    

    public function MakeDongglePrice($price, $company){
        $vat = bcmul($price, 0.1, 0);
        $totalPrice = bcadd($price, $vat);
        return bcadd($this->FeePrice($price, $company), $price);
    }

    public function FeePrice($price, $company){
        $vat = bcmul($price, 0.1, 0);
        $price = bcadd($price, $vat);
        $fee = bcdiv($company->broker_fee, 100, 2);
        return round(bcmul($price, $fee, 2));
    }
}
