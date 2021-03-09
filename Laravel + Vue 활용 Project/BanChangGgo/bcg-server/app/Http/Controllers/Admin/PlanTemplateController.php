<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\PlanTemplate;

class PlanTemplateController extends Controller
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
            'pt_type' => $request->get('pt_type'),
            'pt_day' => $request->get('pt_day'),
            'bt_order' => $request->get('bt_order'),
            'state' => $request->get('state')
        ];

        return response()->json(PlanTemplate::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'pt_type' => $request->input('pt_type'),
            'pt_title' => $request->input('pt_title'),
            'pt_time' => $request->input('pt_time'),
            'pt_memo' => $request->input('pt_memo', ''),
            'pt_day' => $request->input('pt_day'),
            'bt_order' => $request->input('bt_order'),
            'state' => $request->input('state', 1)
        ];

        return response()->json(PlanTemplate::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'pt_no' => $id,
            'pt_type' => $request->input('pt_type'),
            'pt_title' => $request->input('pt_title'),
            'pt_time' => $request->input('pt_time'),
            'pt_memo' => $request->input('pt_memo', ''),
            'pt_day' => $request->input('pt_day'),
            'bt_order' => $request->input('bt_order'),
            'state' => $request->input('state')
        ];

        return response()->json(PlanTemplate::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
