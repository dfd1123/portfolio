<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\HealthReport;
use Facades\App\Classes\DiseaseCategory;

class HealthReportController extends Controller
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
            'ubt_no' => $request->get('ubt_no'),
            'usr_no' => $request->get('usr_no'),
            'hr_no' => $request->get('hr_no'),
            'bt_no' => $request->get('bt_no'),
            'bt_order' => $request->get('bt_order'),
            'state' => $request->get('state'),
            'ubt_state' => $request->get('ubt_state', 1),
            'bt_state' => $request->get('bt_state', 1)
        ];

        $reports = HealthReport::index($params);
        foreach ($reports as $report) {
            $report->disease_list = DiseaseCategory::list_index($report->disease_list);
        }

        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $params = [
            'disease_list' => $request->input('disease_list'),
            'ubt_no' => $request->input('ubt_no')
        ];

        return response()->json(HealthReport::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'hr_no' => $id,
            'state' => $request->input('state')
        ];

        return response()->json(HealthReport::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
