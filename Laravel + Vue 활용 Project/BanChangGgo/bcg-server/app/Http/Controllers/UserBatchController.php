<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\Batch;
use Facades\App\Classes\UserBatch;
use Facades\App\Classes\HealthReport;
use Auth;

class UserBatchController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        $usr_no = Auth::id();
        $bt_no = $request->input('bt_no');

        if (empty($bt_no)) {
            return abort(500);
        }

        $ubt_no = UserBatch::avaliable($usr_no);
        if ($ubt_no->hr_state == 0) {
            return response()->json([
                'error' => 'fail',
                'message' => '이미 진행중인 차수가 존재합니다'
            ], 422);
        }

        $ubt_no = UserBatch::activate($usr_no, $bt_no);
        if ($ubt_no == null) {
            return response()->json([
                'error' => 'fail',
                'message' => '차수 등록에 실패하였습니다'
            ], 422);
        }

        $params = [
            'ubt_no' => $ubt_no
        ];

        HealthReport::store($params);

        
        return response()->json($ubt_no, 201);
    }

    public function show(Request $request, $id)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'usr_no' => Auth::id(),
            'ubt_no' => $id,
            'ubt_state' => 1,
            'bt_order' => null,
            'bt_state' => null
        ];
        
        return response()->json(data_get(UserBatch::index($params), 0, null));
    }

    public function update(Request $request, $id)
    {
        if (empty($id)) {
            return abort(500);
        }

        $params = [
            'ubt_no' => $id,
            'bt_no' => null,
            'usr_no' => Auth::id(),
            'ubt_start' => null,
            'ubt_end' => null,
            'ubt_qna_list' => $request->input('ubt_qna_list'),
            'state' => null
        ];

        return response()->json(UserBatch::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
