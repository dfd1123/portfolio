<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Facades\App\Classes\UserCpTest;
use Facades\App\Classes\Batch;
use Auth;

class UserCpTestController extends Controller
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
        $params = [
            'ucpt_answer' => json_encode($request->ucpt_answer),
            'user_id' => Auth::id(),
            'cpt_id' => $request->cpt_id,
        ];

        $latest = Batch::available();
        if($latest === null) {
            return response()->json([
                'error' => 'unavailable',
                'message' => '현재 진행 가능한 평가가 모두 종료되었습니다'
            ], 422);
        }

        $params['batch_id'] = $latest;
        
        $res = UserCpTest::store($params);

        return response()->json($res, 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
