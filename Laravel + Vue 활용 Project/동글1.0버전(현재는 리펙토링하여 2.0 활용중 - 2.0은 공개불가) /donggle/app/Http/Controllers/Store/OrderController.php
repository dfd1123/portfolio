<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class OrderController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */

    public function __invoke($id)
    {
        return 'Order controller';
    }

    public function index()
    {
        return 'Order FOR API';
    }

    public function show(Request $request, $req)
    {
        switch ($req) {
            case 'main':
                DB::enableQueryLog();
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $graph_order = DB::table('order')
                ->select(
                    DB::raw("CONCAT(date_format(created_at, '%Y.%m.%d'),'(',SUBSTR( _UTF8'일월화수목금토', DAYOFWEEK(date_format(created_at, '%Y-%m-%d')), 1),')') AS date"),
                    DB::raw("SUM(this_price) AS total_price"),
                    DB::raw("COUNT(order_no) AS order_count")
                )
                ->whereRaw("created_at >= CURDATE() - INTERVAL 7 DAY")
                ->where('store_id', $store->store_id)
                ->where('deleted',0)
                ->groupBy("date")->get();

                $graph_cancel = DB::table('order')
                ->select(
                    DB::raw("CONCAT(date_format(created_at, '%Y.%m.%d'),'(',SUBSTR( _UTF8'일월화수목금토', DAYOFWEEK(date_format(created_at, '%Y-%m-%d')), 1),')') AS date"),
                    DB::raw("SUM(this_price) AS total_price"),
                    DB::raw("COUNT(order_no) AS cancel_count")
                )
                ->whereRaw("created_at >= CURDATE() - INTERVAL 7 DAY")
                ->where('store_id', $store->store_id)
                ->where('od_status', '<>', 'refund_reject')
                ->where(function ($query) {
                    $query->where('od_status', 'like', '%cancel%')->orwhere('od_status', 'like', '%refund%');
                })
                ->groupBy("date")->get();

                $month_orders = DB::table('order')
                ->select(DB::raw("COUNT(order_no) AS order_count"), "total_price", "od_status")
                ->where('store_id', $store->store_id)
                ->where('deleted',0)
                ->whereRaw("MONTH(created_at) = MONTH(now())")
                ->groupBy("order_id", "od_status", "total_price")
                ->get();

                $month_order_count = 0;
                $month_order_price = 0;
                $month_cancel_count = 0;
                $month_cancel_price = 0;
                foreach ($month_orders as $month_order) {
                    if ($month_order->od_status == 'refund_apply' || $month_order->od_status == 'refund_complete' || $month_order->od_status == 'order_cancel') {
                        $month_cancel_count += $month_order->order_count;
                        $month_cancel_price = bcadd($month_cancel_price, $month_order->total_price, 0);
                    }
                    $month_order_count += $month_order->order_count;
                    $month_order_price = bcadd($month_order_price, $month_order->total_price, 0);
                }

                $response = array();

                $response["graph_order"] = $graph_order;
                $response["graph_cancel"] = $graph_cancel;
                $response["month_order_count"] = $month_order_count;
                $response["month_order_price"] = $month_order_price;
                $response["month_cancel_count"] = $month_cancel_count;
                $response["month_cancel_price"] = $month_cancel_price;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'order_confirm':
                DB::enableQueryLog();
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $store_id = $store->store_id;

                $order_counting = DB::table('order')
                ->select('od_status', DB::raw("COUNT(order_no) AS order_counting"))
                ->where("store_id", $store_id)
                ->where('deleted',0)
                ->where(function ($query) {
                    $query
                    ->where('od_status', 'order_apply')
                    ->orwhere('od_status', 'delivery_wait')
                    ->orwhere('od_status', 'shipping')
                    ->orwhere('od_status', 'delivery_complete')
                    ->orwhere('od_status', 'order_complete');
                })
                ->groupBy('od_status')
                ->orderBy('od_status')
                ->get();
                    
                
                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                $offset = ($page - 1) * $limit;
                
                $orders = DB::table('order')
                ->join('items', 'items.item_id', '=', 'order.item_id')
                ->select('order.*', 'items.title', 'items.images')
                ->where(function ($query) {
                    $query
                    ->where('order.od_status', 'order_apply')
                    ->orwhere('order.od_status', 'delivery_wait');
                })
                ->where('order.deleted',0)
                ->where('order.store_id', $store_id)
                ->where('order.hope_date', '>=', DB::raw('now()'));

                if ($request->filled('searchSelect')) {
                    if ($request->searchSelect == 'title') {
                        $orders = $orders->where("items.title", 'like', '%'.$request->searchKeyword.'%');
                    } elseif ($request->searchSelect == 'item_id') {
                        $orders = $orders->where("items.item_id", '=', $request->searchKeyword);
                    }
                }

                $count = $orders->count();

                if ($request->filled('orderBy')) {
                    if ($request->orderBy == 'remaining') {
                        $orders->orderBy('order.hope_date', 'ASC')->orderBy('order.od_status', 'DESC')->orderBy(DB::raw("TO_DAYS(order.hope_date) - TO_DAYS(now())"), "ASC");
                    } elseif ($request->orderBy == 'itemCounting') {
                        $orders->orderBy('order.hope_date', 'ASC')->orderBy('order.od_status', 'DESC')->orderBy("qty", "DESC");
                    } else {
                        $orders->orderBy('order.hope_date', 'ASC')->orderBy('order.od_status', 'DESC')->orderBy(DB::raw("TO_DAYS(order.hope_date) - TO_DAYS(now())"), "ASC");
                    }
                } else {
                    $orders->orderBy('order.hope_date', 'ASC')->orderBy('order.od_status', 'DESC')->orderBy(DB::raw("TO_DAYS(order.hope_date) - TO_DAYS(now())"), "ASC");
                }
                
                $orders = $orders
                ->offset($offset)
                ->limit($limit)
                ->get();
                
                $response = array();
                $response['orders'] = $orders;
                $response['count'] = $count;
                $response['page'] = $page;
                $response['order_counting'] = $order_counting;
                $response["searchSelect"] = $request->searchSelect;
                $response["searchKeyword"] = $request->searchKeyword;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'order_confirm_group':
                DB::enableQueryLog();
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $store_id = $store->store_id;

                $order_counting = DB::table('order')
                ->select('od_status', DB::raw("COUNT(order_no) AS order_counting"))
                ->where("store_id", $store_id)
                ->where('deleted',0)
                ->where(function ($query) {
                    $query
                    ->where('od_status', 'order_apply')
                    ->orwhere('od_status', 'delivery_wait')
                    ->orwhere('od_status', 'shipping')
                    ->orwhere('od_status', 'delivery_complete')
                    ->orwhere('od_status', 'order_complete');
                })
                ->groupBy('od_status')
                ->orderBy('od_status')
                ->get();
                    
                
                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                $offset = ($page - 1) * $limit;
                
                $orders = DB::table('order')
                ->join('items', 'items.item_id', '=', 'order.item_id')
                ->select(
                    'order.hope_date',
                    'items.title',
                    'order.option',
                    'items.images',
                    'items.item_id',
                    DB::raw("SUM(CASE WHEN `order`.`od_status` = 'order_apply' THEN qty ELSE 0 END) AS no_ready"),
                    DB::raw("SUM(CASE WHEN `order`.`od_status` = 'delivery_wait' THEN qty ELSE 0 END) AS ready")
                )
                ->where(function ($query) {
                    $query
                    ->where('order.od_status', 'order_apply')
                    ->orwhere('order.od_status', 'delivery_wait');
                })
                ->where('order.deleted',0)
                ->where('order.store_id', $store_id)
                ->where('order.hope_date', '>=', DB::raw('now()'));

                if ($request->filled('searchSelect')) {
                    if ($request->searchSelect == 'title') {
                        $orders = $orders->where("items.title", 'like', '%'.$request->searchKeyword.'%');
                    } elseif ($request->searchSelect == 'item_id') {
                        $orders = $orders->where("items.item_id", '=', $request->searchKeyword);
                    }
                }

                $count = $orders->count();

                if ($request->filled('orderBy')) {
                    if ($request->orderBy == 'remaining') {
                        $orders->orderBy('order.hope_date', 'ASC')->orderBy(DB::raw("TO_DAYS(order.hope_date) - TO_DAYS(now())"), "ASC");
                    } elseif ($request->orderBy == 'itemCounting') {
                        $orders->orderBy('order.hope_date', 'ASC')->orderBy(DB::raw("SUM(qty)"), "DESC");
                    } else {
                        $orders->orderBy('order.hope_date', 'ASC')->orderBy(DB::raw("TO_DAYS(order.hope_date) - TO_DAYS(now())"), "ASC");
                    }
                } else {
                    $orders->orderBy('order.hope_date', 'ASC')->orderBy(DB::raw("TO_DAYS(order.hope_date) - TO_DAYS(now())"), "ASC");
                }
                
                $orders = $orders
                ->groupBy('order.hope_date', 'items.item_id', 'order.option', 'items.title', 'items.images')
                ->offset($offset)
                ->limit($limit)
                ->get();
                
                $response = array();
                $response['orders'] = $orders;
                $response['count'] = $count;
                $response['page'] = $page;
                $response['order_counting'] = $order_counting;
                $response["searchSelect"] = $request->searchSelect;
                $response["searchKeyword"] = $request->searchKeyword;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            
            case 'list':
                DB::enableQueryLog();
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

                $store_id = $store->store_id;

                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                $offset = ($page - 1) * $limit;
                $start_date = $request->start_date." 00:00:00";
                $end_date = $request->end_date." 23:59:59";
                $od_status = ($request->filled('od_status'))?$request->od_status:"";

                $orders = DB::table('order')
                ->select('order_id', 'receipt_time', 'od_status', DB::raw("SUM(qty) AS total_qty"), 'total_price', 'coupon_discount', 'level_discount', 'receipt_price')
                ->where('store_id', $store_id)
                ->where('deleted',0)
                ->whereBetween('receipt_time', [$start_date,$end_date])
                ->where('od_status', '<>', 'deposit_wait')
                ->where('od_status', '<>', 'order_cancel')
                ->where('od_status', '<>', 'refund_apply')
                ->where('od_status', 'like', "%".$od_status."%");

                if ($request->filled('searchSelect')) {
                    if ($request->searchSelect == 'order_id') {
                        $orders = $orders->where("order.order_id", '=', $request->searchKeyword);
                    }
                }
                $orders = $orders->groupBy('order.order_id', 'receipt_time', 'od_status', 'total_price', 'coupon_discount', 'level_discount', 'receipt_price');

                $count = count($orders->get());

                if ($request->filled('orderBy')) {
                    if ($request->orderBy == 'desc') {
                        $orders->orderBy("receipt_time", "DESC");
                    } elseif ($request->orderBy == 'asc') {
                        $orders->orderBy("receipt_time", "ASC");
                    } elseif ($request->orderBy == 'qty') {
                        $orders->orderBy("qty", "DESC");
                    } elseif ($request->orderBy == 'price') {
                        $orders->orderBy("total_price", "DESC");
                    } elseif ($request->orderBy == 'receipt') {
                        $orders->orderBy("receipt_price", "DESC");
                    } else {
                        $orders->orderBy("receipt_time", "DESC");
                    }
                } else {
                    $orders->orderBy("receipt_time", "DESC");
                }
                
                $orders = $orders
                ->offset($offset)
                ->limit($limit)
                ->get();

                $response = array();
                $response['orders'] = $orders;
                $response['count'] = $count;
                $response['page'] = $page;
                $response["searchSelect"] = $request->searchSelect;
                $response["searchKeyword"] = $request->searchKeyword;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'view':
                if (!$request->filled('order_id')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $order_id = $request->order_id;
                $orders = DB::table('order')
                ->join('items', 'items.item_id', '=', 'order.item_id')
                ->select('order.*', 'items.title', 'items.images')
                ->where('order.deleted',0)
                ->where('order.order_id', $order_id)
                ->where('order.od_status', '<>', 'deposit_wait')
                ->where('order.od_status', '<>', 'order_cancel')
                ->where('order.od_status', '<>', 'refund_apply')
                ->get();

                $response = array();
                $response['orders'] = $orders;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

                $response = array();
            break;

            case 'cancel_list':
                DB::enableQueryLog();
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

                $store_id = $store->store_id;

                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                $offset = ($page - 1) * $limit;
                $start_date = $request->start_date." 00:00:00";
                $end_date = $request->end_date." 23:59:59";
                $od_status = ($request->filled('od_status'))?$request->od_status:"";

                $orders = DB::table('order')
                ->select('order_id', 'receipt_time', 'od_status', DB::raw("SUM(qty) AS total_qty"), 'total_price', 'coupon_discount', 'level_discount', 'receipt_price')
                ->where('store_id', $store_id)
                ->where('deleted',0)
                ->whereBetween('receipt_time', [$start_date,$end_date])
                ->where('od_status', 'like', "%".$od_status."%")
                ->where(function ($query) {
                    $query
                    ->where('od_status', 'deposit_wait')
                    ->orwhere('od_status', 'order_cancel')
                    ->orwhere('od_status', 'refund_apply');
                });

                if ($request->filled('searchSelect')) {
                    if ($request->searchSelect == 'order_id') {
                        $orders = $orders->where("order.order_id", '=', $request->searchKeyword);
                    }
                }
                $orders = $orders->groupBy('order.order_id', 'receipt_time', 'od_status', 'total_price', 'coupon_discount', 'level_discount', 'receipt_price');

                $count = count($orders->get());

                if ($request->filled('orderBy')) {
                    if ($request->orderBy == 'desc') {
                        $orders->orderBy("receipt_time", "DESC");
                    } elseif ($request->orderBy == 'asc') {
                        $orders->orderBy("receipt_time", "ASC");
                    } elseif ($request->orderBy == 'qty') {
                        $orders->orderBy("qty", "DESC");
                    } elseif ($request->orderBy == 'price') {
                        $orders->orderBy("total_price", "DESC");
                    } elseif ($request->orderBy == 'receipt') {
                        $orders->orderBy("receipt_price", "DESC");
                    } else {
                        $orders->orderBy("receipt_time", "DESC");
                    }
                } else {
                    $orders->orderBy("receipt_time", "DESC");
                }
                
                $orders = $orders
                ->offset($offset)
                ->limit($limit)
                ->get();

                $response = array();
                $response['orders'] = $orders;
                $response['count'] = $count;
                $response['page'] = $page;
                $response["searchSelect"] = $request->searchSelect;
                $response["searchKeyword"] = $request->searchKeyword;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'cancel_view':
                if (!$request->filled('order_id')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $order_id = $request->order_id;
                $orders = DB::table('order')
                ->join('items', 'items.item_id', '=', 'order.item_id')
                ->select('order.*', 'items.title', 'items.images')
                ->where('order.order_id', $order_id)
                ->where('order.deleted',0)
                ->where(function ($query) {
                    $query
                    ->where('od_status', 'deposit_wait')
                    ->orwhere('od_status', 'order_cancel')
                    ->orwhere('od_status', 'refund_apply');
                })
                ->get();

                $response = array();
                $response['orders'] = $orders;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

                $response = array();
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
        switch ($req) {
            case 'order_confirm':
                if (!$request->filled('order_no')) { //order_id 랑 헷갈리면 안됨
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $order_no = $request->order_no;
                try {
                    $update = DB::table('order')->whereIn('order_no', $order_no)->update([
                        "od_status" => 'delivery_wait',
                    ]);
                    if ($update == 0) {
                        $this->res['query'] = $update;
                        $this->res['msg'] = "이미 상태변경이 완료되었거나 해당 order_no 에 맞는 주문내역이 존재하지 않음";
                        $this->res['state'] = config('res_code.OK');
                    } else {
                        $this->res['query'] = $update;
                        $this->res['msg'] = "성공";
                        $this->res['state'] = config('res_code.OK');
                    }
                } catch (Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;

            case 'order_confirm_group':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                if (!$request->filled('object_group')) { //order_id 랑 헷갈리면 안됨
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                
                $store_id = $store->store_id;

                $object = json_decode($request->object_group);

                foreach ($object as $row) {
                    $hope_date = $row->hope_date;
                    $item_id = $row->item_id;
                    $option = $row->option;

                    try {
                        $update = DB::table('order')
                        ->where('hope_date', $hope_date)
                        ->where('item_id', $item_id)
                        ->where('option', $option)
                        ->where('od_status', 'order_apply')
                        ->where('store_id', $store_id)
                        ->update([
                            "od_status" => 'delivery_wait',
                        ]);
                        if ($update == 0) {
                            $this->res['query'] = $update;
                            $this->res['msg'] = "이미 상태변경이 완료되었거나 권한이 존재하지 않음";
                            $this->res['state'] = config('res_code.OK');
                        } else {
                            $this->res['query'] = $update;
                            $this->res['msg'] = "성공";
                            $this->res['state'] = config('res_code.OK');
                        }
                    } catch (Exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }
                
                
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
