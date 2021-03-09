<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\Batch;
use DB;

class BatchController extends Controller
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
            'bt_no' => $request->get('bt_no', null)
        ];

        return response()->json(Batch::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'offset' => $request->get('offset', 0),
            'limit' => $request->get('limit', 1),
            'bt_no' => $request->get('bt_no', null)
        ];
        $batch = data_get(Batch::index($params), 0, null);

        $params = [
            'bt_order' => $request->input('bt_order'),
            'bt_start' => $request->input('bt_start'),
            'bt_end' => $request->input('bt_end'),
            'bt_memo' => $request->input('bt_memo', ''),
            'bt_max' => $request->input('bt_max'),
            'state' => $request->input('state', 0),
        ];
        $bt_no = Batch::store($params);

        if ($batch !== null) {
            $params = [
                'bt_no' => $bt_no,
                'bt_start' => null,
                'bt_end' => null,
                'bt_memo' => null,
                'bt_max' => null,
                'bt_qna_list' => $batch->bt_qna_list,
                'state' => null
            ];
            Batch::update($params);
        }

        return response()->json($bt_no, 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $state = $request->input('state');
        if ($state != null && $state == 1) {
            $res = DB::select("
            SELECT 
                count(state) AS count
            FROM batch
            WHERE 1 = 1
                AND state = 1
            ");

            $count = data_get(data_get($res, 0, null), 'count', 0);
            if ($count > 0) {
                return response()->json([
                    'error' => 'already_exists',
                    'message' => '기존에 진행중인 차수가 존재합니다'
                ], 422);
            }
        }

        $params = [
            'bt_no' => $id,
            'bt_start' => $request->input('bt_start'),
            'bt_end' => $request->input('bt_end'),
            'bt_memo' => $request->input('bt_memo'),
            'bt_max' => $request->input('bt_max'),
            'bt_qna_list' => $request->input('bt_qna_list'),
            'state' => $request->input('state')
        ];

        return response()->json(Batch::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
