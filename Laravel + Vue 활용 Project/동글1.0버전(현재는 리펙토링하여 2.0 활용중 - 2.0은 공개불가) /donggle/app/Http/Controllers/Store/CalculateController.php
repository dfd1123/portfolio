<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class CalculateController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */

    public function __invoke($id)
    {
        return 'Calculate controller';
    }

    public function index()
    {
        return 'Calculate FOR API';
    }

    public function show(Request $request, $req)
    {
        switch ($req) {
            case 'daily':
                DB::enableQueryLog();
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                if (!$request->filled('date')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $date = ($request->filled('date'))?$request->date: date("Y-m-d");
                $start_date = $date." 00:00:00";
                $end_date = $date." 23:59:59";
                $calculates = DB::table('order')
                ->select(
                    'order_id',
                    'item_name',
                    's_name',
                    DB::raw("
                        (CASE
                            WHEN od_status = 'order_cancel' THEN this_price
                            WHEN od_status = 'refund_complete' THEN this_price
                            ELSE 0
                        END) AS cancel_price
                    "),
                    'this_price',
                    'fee_price'
                )
                ->whereBetween('receipt_time', [$start_date,$end_date])
                ->where('store_id', $store->store_id)
                ->where('deleted',0);


                $calculates = $calculates->get();


                $this->res['query'] = $calculates;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'date':
                DB::enableQueryLog();
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                if (!$request->filled('start_date', 'end_date')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                $offset = ($page - 1) * $limit;

                $start_date = $request->start_date." 00:00:00";
                $end_date = $request->end_date." 23:59:59";
                $calculates = DB::table('order')
                ->select(
                    DB::raw("DATE_FORMAT(receipt_time, '%Y-%m-%d') as date"),
                    DB::raw("COUNT(order_no) as od_count"),
                    DB::raw("SUM(this_price) as price"),
                    DB::raw("SUM(fee_price) as fee_price"),
                    DB::raw("
                        SUM(CASE
                            WHEN od_status = 'order_cancel' THEN this_price
                            WHEN od_status = 'refund_complete' THEN this_price
                            ELSE 0
                        END) AS cancel_price
                    ")
                )
                ->where('od_status','order_complete')
                ->whereBetween('receipt_time', [$start_date,$end_date])
                ->where('store_id', $store->store_id)
                ->where('deleted',0)
                ->groupBy(DB::raw("DATE_FORMAT(receipt_time, '%Y-%m-%d')"));

                $count = count($calculates->get());

                if ($request->filled('orderBy')) {
                    if ($request->orderBy == 'desc') {
                        $calculates->orderBy("date", "DESC");
                    } elseif ($request->orderBy == 'asc') {
                        $calculates->orderBy("date", "ASC");
                    } else {
                        $calculates->orderBy("date", "DESC");
                    }
                } else {
                    $calculates->orderBy("date", "DESC");
                }


                $calculates = $calculates
                ->offset($offset)
                ->limit($limit)
                ->get();

                $response = array();
                $response['calculates'] = $calculates;
                $response['count'] = $count;
                $response['page'] = $page;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'month':
                DB::enableQueryLog();
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                if (!$request->filled('start_month', 'end_month')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                $offset = ($page - 1) * $limit;

                $start_month = $request->start_month."-01 00:00:00";
                $end_month = $request->end_month."-31 23:59:59";
                $calculates = DB::table('order')
                ->select(
                    DB::raw("DATE_FORMAT(receipt_time, '%Y-%m') as date"),
                    DB::raw("COUNT(order_no) as od_count"),
                    DB::raw("SUM(this_price) as price"),
                    DB::raw("SUM(fee_price) as fee_price"),
                    DB::raw("
                        SUM(CASE
                            WHEN od_status = 'order_cancel' THEN this_price
                            WHEN od_status = 'refund_complete' THEN this_price
                            ELSE 0
                        END) AS cancel_price
                    ")
                )
                ->where('od_status','order_complete')
                ->whereBetween('receipt_time', [$start_month,$end_month])
                ->where('store_id', $store->store_id)
                ->where('deleted',0)
                ->groupBy(DB::raw("DATE_FORMAT(receipt_time, '%Y-%m')"));

                $count = count($calculates->get());

                if ($request->filled('orderBy')) {
                    if ($request->orderBy == 'desc') {
                        $calculates->orderBy("date", "DESC");
                    } elseif ($request->orderBy == 'asc') {
                        $calculates->orderBy("date", "ASC");
                    } else {
                        $calculates->orderBy("date", "DESC");
                    }
                } else {
                    $calculates->orderBy("date", "DESC");
                }


                $calculates = $calculates
                ->offset($offset)
                ->limit($limit)
                ->get();

                $response = array();
                $response['calculates'] = $calculates;
                $response['count'] = $count;
                $response['page'] = $page;


                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'year':
                DB::enableQueryLog();
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();
                if (!isset($store->store_id)) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                if (!$request->filled('start_year', 'end_year')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                $offset = ($page - 1) * $limit;

                $start_year = $request->start_year."-01-01 00:00:00";
                $end_year = $request->end_year."-12-31 23:59:59";
                $calculates = DB::table('order')
                ->select(
                    DB::raw("DATE_FORMAT(receipt_time, '%Y') as date"),
                    DB::raw("COUNT(order_no) as od_count"),
                    DB::raw("SUM(this_price) as price"),
                    DB::raw("SUM(fee_price) as fee_price"),
                    DB::raw("
                        SUM(CASE
                            WHEN od_status = 'order_cancel' THEN this_price
                            WHEN od_status = 'refund_complete' THEN this_price
                            ELSE 0
                        END) AS cancel_price
                    ")
                )
                ->where('od_status','order_complete')
                ->whereBetween('receipt_time', [$start_year,$end_year])
                ->where('store_id', $store->store_id)
                ->where('deleted',0)
                ->groupBy(DB::raw("DATE_FORMAT(receipt_time, '%Y')"));

                $count = count($calculates->get());

                if ($request->filled('orderBy')) {
                    if ($request->orderBy == 'desc') {
                        $calculates->orderBy("date", "DESC");
                    } elseif ($request->orderBy == 'asc') {
                        $calculates->orderBy("date", "ASC");
                    } else {
                        $calculates->orderBy("date", "DESC");
                    }
                } else {
                    $calculates->orderBy("date", "DESC");
                }


                $calculates = $calculates
                ->offset($offset)
                ->limit($limit)
                ->get();

                $response = array();
                $response['calculates'] = $calculates;
                $response['count'] = $count;
                $response['page'] = $page;


                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
    }

    public function update(Request $request, $req)
    {

    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
