<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\UserCpTestResultTotal;
use Facades\App\Classes\Batch;
use Auth;

class UserCpTestResultTotalController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $params = [
            'user_no' => $request['user_no'],
            'user_name' => $request['user_name'],
            'batch_id' => $request['batch_id'],
            'sort_by' => $request['sort_by'],
            'order_by' => $request['order_by'],
            'offset' => $request['offset'],
            'limit' => $request['limit']
        ];

        $res = UserCpTestResultTotal::index($params);

        return response()->json($res);
    }

    public function store(Request $request)
    {
        //
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
