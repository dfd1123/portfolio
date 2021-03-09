<?php

namespace App\Http\Controllers\Store;

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

    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['show']]);
        //dd('dd');
    }

    public function index()
    {
        return 'API FOR ITEM';
    }

    public function show(Request $request, $req)
    {
        switch ($req) {
            case 'list':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $store_id = $store->store_id;

                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                if ($request->filled('offset')) {
                    $offset = $request->offset;
                } else {
                    $offset = ($page - 1)*$limit;
                }
                
                try {
                    $items = DB::table('items')->where('store_id', $store_id)->where('delete_yn', 0);

                    if ($request->filled('searchSelect')) {
                        if ($request->searchSelect == 'item_id') {
                            $items = $items->where("item_id", '=', $request->searchKeyword);
                        } elseif ($request->searchSelect == 'category') {
                            $items = $items->where("ca_name", 'like', "%".$request->searchKeyword."%");
                        } elseif ($request->searchSelect == 'title') {
                            $items = $items->where("title", 'like', "%".$request->searchKeyword."%");
                        }
                    }

                    $count = $items->count();

                    if ($request->filled('orderBy')) {
                        if ($request->orderBy == 'desc') {
                            $items->orderBy("created_at", "DESC");
                        } elseif ($request->orderBy == 'asc') {
                            $items->orderBy("created_at", "ASC");
                        } elseif ($request->orderBy == 'price') {
                            $items->orderBy("price", "DESC");
                        } elseif ($request->orderBy == 'hit') {
                            $items->orderBy("hit", "DESC");
                        } elseif ($request->orderBy == 'rating') {
                            $items->orderBy(DB::raw("SELECT (IFNULL((SELECT AVG(rating) FROM review WHERE item_id = items.item_id),0))"), 'DESC');
                        } else {
                            $items->orderBy("created_at", "DESC");
                        }
                    } else {
                        $items->orderBy("created_at", "DESC");
                    }
                    $items = $items->offset($offset)->limit($limit)->get();

                    $response = array();
                    $response['items'] = $items;
                    $response["page"] = $page;
                    $response["count"] =  $count;
                    $response["searchSelect"] = $request->searchSelect;
                    $response["searchKeyword"] = $request->searchKeyword;

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                } catch (exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
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
                $item_id = $request->item_id;

                try {
                    $items = DB::table('items')->where('store_id', $store_id)->where('item_id', $item_id)->where('items.delete_yn',0)->get();
                    $options = DB::table('item_option')
                        ->select('name AS op_name', 'tax_mny AS op_tax_mny', 'notax AS op_notax', 'sold_out')
                        ->where('item_id', $item_id)
                        ->orderBy('id','ASC')
                        ->get();

                    $response = array();
                    if(isset($items[0])) {
                        $items[0]->options = json_encode($options);
                    }

                    $response["items"] = $items;

                    $this->res['query'] = $response;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                } catch (exception $e) {
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
        if (!$request->filled("ca_id", "ca_name", "age", "gender", "title", "simple_intro", "introduce_pc", "introduce_mobile", "make_company", "orgin_range", "info_gubun", "max_size", "min_size", "color", "tax_mny", "option_subject", "hash_tag", "how_clean", "material", "etc_detail_info")
        || !$request->hasFile('images')) {
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

        $store_id = $store->store_id;
        try {
            // 매장정보 가져오기
            $seller = DB::table('seller_infor')->where('uid', Auth::guard('api')->user()->id)->first();

            if ($seller == null) {
                $this->res['query'] = null;
                $this->res['msg'] = "판매자 정보가 없음!";
                $this->res['state'] = config('res_code.NO_DATA');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        } catch (exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "판매자 정보 조회 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try {
            // 플랫폼 회사 정보 가져오기
            $company = DB::table('company')->first();
            if ($company == null) {
                $this->res['query'] = null;
                $this->res['msg'] = "플랫폼 회사 정보가 없음!";
                $this->res['state'] = config('res_code.NO_DATA');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        } catch (exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "플랫폼 정보 조회 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        try {
            /*상품 field 정리 및 상품 등록 START*/
            $images = File_store::Image_store('items', $request->images);
            if ($images == 'EXT_ERR') {  //이미지 에러
                $this->res['query'] =null;
                $this->res['msg'] = "상품 이미지 확장자 에러!";
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            } elseif ($images == 'VALID_ERR') {
                $this->res['query'] = null;
                $this->res['msg'] = "상품 이미지가 아님!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            } elseif ($images == 'PARAM_ERR') {
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
                "brand" => $request->brand,
                "images" => json_encode($images),
                "simple_intro" => $request->simple_intro,
                "introduce_pc" => $request->introduce_pc,
                "introduce_mobile" => $request->introduce_mobile,
                "make_company" => $request->make_company,
                "orgin_range" => $request->orgin_range,
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
                "self_type" => $request->self_type,
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
                "how_clean" => $request->how_clean,
                "material" => $request->material,
                "etc_detail_info" => $request->etc_detail_info
            ]);
        } catch (exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "상품 등록 시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try {
            /*옵션 field 정리 및 옵션 등록 START*/
            
            foreach (json_decode($request->options) as $option) {
                //$op_image = File_store::Image_store('items', $request->op_images);
                //$optionArr = $this->OptionFieldArray($option, $item_id, $company, 'create');
                $status = DB::table('item_option')->insert([
                    "item_id" => $item_id,
                    "name" => $option->op_name,
                    "subject" => $request->option_subject,
                    //"image" => json_encode($op_image),
                    "type" => 0,
                    "tax_mny" => $option->op_tax_mny, //
                    "vat_mny" => bcmul($option->op_tax_mny, 0.1, 0),
                    "price" => $this->MakeDongglePrice($option->op_tax_mny, $company),
                    "fee_price" => $this->FeePrice($option->op_tax_mny, $company),
                    "notax" => $option->op_notax,
                    "stock_qty" => 9999999,
                    "noti_qty" => 0,
                    "sold_out" => $option->sold_out
                ]);
                if (!$status) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "옵션 등록 실패";
                    $this->res['state'] = config('res_code.NO_DATA');
                }
            }
        } catch (exception $e) {
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
        switch ($req) {
            case 'item_update':
                if (!$request->filled("item_id", "ca_id", "ca_name", "title", "simple_intro", "introduce_pc", "introduce_mobile", "make_company", "orgin_range", "info_gubun", "max_size", "min_size", "color", "tax_mny", "option_subject", "hash_tag", "how_clean", "material", "etc_detail_info")) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                try {
                    // 매장정보 가져오기
                    $seller = DB::table('seller_infor')->where('uid', Auth::guard('api')->user()->id)->first();
        
                    if ($seller == null) {
                        $this->res['query'] = null;
                        $this->res['msg'] = "판매자 정보가 없음!";
                        $this->res['state'] = config('res_code.NO_DATA');
        
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                } catch (exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "판매자 정보 조회 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                try {
                    // 플랫폼 회사 정보 가져오기
                    $company = DB::table('company')->first();
                    if ($company == null) {
                        $this->res['query'] = null;
                        $this->res['msg'] = "플랫폼 회사 정보가 없음!";
                        $this->res['state'] = config('res_code.NO_DATA');
        
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                } catch (exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "플랫폼 정보 조회 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $item_id = $request->item_id;
                try {
                    /*상품 field 정리 및 상품 수정 START*/
                    $item = DB::table('items')->where('item_id', $request->item_id)->first();
                    if ($request->hasFile('images')) {
                        $images = File_store::Image_update('items', $request->images, json_decode($item->images), $request->index);
                        if ($images == 'EXT_ERR') {  //이미지 에러
                            $this->res['query'] =null;
                            $this->res['msg'] = "상품 이미지 확장자 에러!";
                            $this->res['state'] = config('res_code.EXT_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        } elseif ($images == 'VALID_ERR') {
                            $this->res['query'] = null;
                            $this->res['msg'] = "상품 이미지가 아님!";
                            $this->res['state'] = config('res_code.IMG_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        } elseif ($images == 'PARAM_ERR') {
                            $this->res['query'] = null;
                            $this->res['msg'] = "상품 이미지 첨부 필수!";
                            $this->res['state'] = config('res_code.PARAM_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }
                    } else {
                        if (isset($request->index)) {
                            $images = $request->index;
                        } else {
                            $images = []; // index[] 배열이 없으면 모든 이미지 삭제
                        }
                    }
                    
                    $status = DB::table('items')->where('item_id', $request->item_id)->where('store_id', $seller->store_id)->update([
                        "ca_id" => $request->ca_id,
                        "ca_name" => $request->ca_name,
                        "seller_id" => $seller->uid,
                        "store_id" => $seller->store_id,
                        "company_name" => $seller->brandname,
                        "company_profile_img" => $seller->image,
                        "gender" => $request->gender,
                        "age" => $request->age,
                        "title" => $request->title,
                        "brand" => $request->brand,
                        "images" => json_encode($images),
                        "simple_intro" => $request->simple_intro,
                        "introduce_pc" => $request->introduce_pc,
                        "introduce_mobile" => $request->introduce_mobile,
                        "make_company" => $request->make_company,
                        "orgin_range" => $request->orgin_range,
                        "info_gubun" => $request->info_gubun,
                        "max_size" => $request->max_size,
                        "min_size" => $request->min_size,
                        "color" => $request->color,
                        "cust_price" => bcmul($request->tax_mny, 2, 0),
                        "tax_mny" => $request->tax_mny,
                        "vat_mny" => bcmul($request->tax_mny, 0.1, 0),
                        "price" => $this->MakeDongglePrice($request->tax_mny, $company),
                        "fee_price" => $this->FeePrice($request->tax_mny, $company),
                        "self_type" => $request->self_type,
                        "option_subject" => $request->option_subject,
                        "hash_tag" => $request->hash_tag,
                        "how_clean" => $request->how_clean,
                        "material" => $request->material,
                        "etc_detail_info" => $request->etc_detail_info
                    ]);

                    if ($status == 0) {
                        $this->res['query'] = $status;
                        $this->res['msg'] = "딱히 변경된 점이 없거나 해당 item_id에 맞는 상품이 존재하지 않음 또는 해당 상품에 대한 권한이 없음";
                        $this->res['state'] = config('res_code.NO_DATA');
                    }
                } catch (exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "상품 수정 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                try {
                    /*옵션 field 정리 및 옵션 수정 START*/
                    DB::table('item_option')->where('item_id', $request->item_id)->delete();
                    foreach (json_decode($request->options) as $option) {
                        $status = DB::table('item_option')->insert([
                            "item_id" => $item_id,
                            "name" => $option->op_name,
                            "subject" => $request->option_subject,
                            //"image" => json_encode($op_image),
                            "type" => 0,
                            "tax_mny" => $option->op_tax_mny, //
                            "vat_mny" => bcmul($option->op_tax_mny, 0.1, 0),
                            "price" => $this->MakeDongglePrice($option->op_tax_mny, $company),
                            "fee_price" => $this->FeePrice($option->op_tax_mny, $company),
                            "notax" => $option->op_notax,
                            "stock_qty" => 9999999,
                            "noti_qty" => 0,
                            "sold_out" => $option->sold_out
                        ]);
                        if (!$status) {
                            $this->res['query'] = $status;
                            $this->res['msg'] = "옵션 등록 실패";
                            $this->res['state'] = config('res_code.NO_DATA');
                        }
                    }
                } catch (exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "옵션 등록 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                $this->res['query'] = $item_id;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');

            break;

            case 'change_state':
                if (!$request->filled("item_id", "sell_yn")) {
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

                $store_id = $store->store_id;
                $item_id = $request->item_id;
                $sell_yn = $request->sell_yn;
                try {
                    $status = DB::table('items')->whereIn('item_id', $item_id)->where('store_id', $store_id)->update([
                        "sell_yn" => $sell_yn,
                    ]);

                    if ($status == 0) {
                        $this->res['query'] = $status;
                        $this->res['msg'] = "동일한 상태를 변경하셨거나 해당 item_id에 맞는 상품이 존재하지 않음 또는 해당 상품에 대한 권한이 없음";
                        $this->res['state'] = config('res_code.NO_DATA');
                    }
                } catch (exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "상품 상태변경 시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
        
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $this->res['query'] = $status;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            
            break;

            case 'item_delete':
                try {
                    $status = DB::table('items')->where('item_id', $request->item_id)->update([
                        "delete_yn" => 1
                    ]);

                    $delete_review = DB::table('review')->where('item_id',$request->item_id)->update([
                        'deleted' => 1,
                        'deleted_reason' => '상품삭제로 인한 관련리뷰 삭제',
                        'deleted_detail' => '상품삭제로 인한 관련리뷰 삭제'
                    ]);

                    if (!$status) {
                        $this->res['query'] = $status;
                        $this->res['msg'] = "상품 삭제 실패";
                        $this->res['state'] = config('res_code.NO_DATA');
                    }
                } catch (exception $e) {
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

    

    public function MakeDongglePrice($price, $company)
    {
        $vat = bcmul($price, 0.1, 0);
        $totalPrice = bcadd($price, $vat);
        return bcadd($this->FeePrice($price, $company), $totalPrice);
    }

    public function FeePrice($price, $company)
    {
        $vat = bcmul($price, 0.1, 0);
        $price = bcadd($price, $vat);
        $fee = bcdiv($company->broker_fee, 100, 2);
        return round(bcmul($price, $fee, 2));
    }
}
