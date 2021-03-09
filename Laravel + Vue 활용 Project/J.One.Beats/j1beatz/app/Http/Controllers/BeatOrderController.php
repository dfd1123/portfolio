<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use \Facades\App\Classes\Cart;
use \Facades\App\Classes\BeatOrder;

class BeatOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $req = Input::get('req');
        switch ($req) {
            case 'list':

            $params = [
                'start_date' => Input::get('start_date', null),
                'end_date' => Input::get('end_date', null),
                'offset' => Input::get('offset', null),
                'limit' => Input::get('limit', null)
            ];
            $res = BeatOrder::index($params);
            return response()->json($res);

            case 'revenue':
            $sub = $request->sub;
            $res = BeatOrder::revenue($sub);
            return response()->json($res);

            case 'revenue_at_beat':
            $prdc_id = Input::get('prdc_id');
            $res = BeatOrder::revenue_at_beat($prdc_id);
            return response()->json($res);

            case 'fp_downloadCnt':
            $res = BeatOrder::freeAndPaid_downloadCount();
            return response()->json($res);
        }

        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id == "registRevenue") {
            if (!$request->has('po_id')) {
                return response()->json(null, 422);
            }
            $prdc_id = auth()->user()->user_id;
            $po_id = $request->po_id;
            $res = BeatOrder::registRevenue($prdc_id, $po_id);
            return response()->json($res, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
