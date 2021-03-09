<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\Batch;
use Auth;

class BatchController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $params = [
            'batch_id' => $request['batch_id'],
            'batch_name' => $request['batch_name'],
            'offset' => $request['offset'],
            'limit' => $request['limit']
        ];

        $res = Batch::index($params);

        return response()->json($res);
    }

    public function store(Request $request)
    {
        $params = [
            'batch_name' => $request->batch_name,
            'batch_start' => $request->batch_start,
            'batch_end' => $request->batch_end,
            'admin_id' => Auth::id(),
        ];

        $latest = Batch::available();
        if($latest !== null) {
            return response()->json([
                'error' => 'already_exists',
                'message' => '현재 진행중인 차수가 이미 존재합니다. 한번에 여러 차수를 진행할 수 없습니다'
            ], 422);
        }
        
        $res = Batch::store($params);

        return response()->json($res, 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'batch_id' => $id,
            'batch_name' => $request->batch_name,
            'batch_start' => $request->batch_start,
            'batch_end' => $request->batch_end,
            'admin_id' => Auth::id()
        ];

        $res = Batch::update($params);

        return response()->json($res);
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
