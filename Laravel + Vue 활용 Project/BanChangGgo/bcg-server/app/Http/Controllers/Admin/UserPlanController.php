<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\UserPlan;

class UserPlanController extends Controller
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
            'upt_no' => $request->get('upt_no', null),
            'upt_type' => $request->get('upt_type', null),
            'start_dt' => $request->get('start_dt', null),
            'end_dt' => $request->get('end_dt', null)
        ];

        return response()->json(UserPlan::index($params));
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
