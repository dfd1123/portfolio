<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\UserBatch;

class UserBatchController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $params = [
            'offset' => $request->get('offset', 0),
            'limit' => $request->get('limit', null),
            'usr_no' => $request->get('usr_no', null),
            'ubt_no' => $request->get('ubt_no', null),
            'ubt_state' => $request->get('ubt_state', 1),
            'bt_order' => $request->get('bt_order', null),
            'bt_state' => $request->get('bt_state', 1)
        ];
        
        return response()->json(UserBatch::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'bt_no' => $request->input('bt_no'),
            'usr_no' => $request->input('usr_no'),
            'ubt_start' => $request->input('ubt_start'),
            'ubt_end' => $request->input('ubt_end'),
            'ubt_qna_list' => $request->input('ubt_qna_list'),
            'state' => $request->input('state')
        ];

        return response()->json(UserBatch::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'ubt_no' => $id,
            'bt_no' => $request->input($params, 'bt_no'),
            'usr_no' => $request->input($params, 'usr_no'),
            'ubt_start' => $request->input($params, 'ubt_start'),
            'ubt_end' => $request->input($params, 'ubt_end'),
            'ubt_qna_list' => $request->input($params, 'ubt_qna_list'),
            'state' => $request->input($params, 'state')
        ];

        return response()->json(UserBatch::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
