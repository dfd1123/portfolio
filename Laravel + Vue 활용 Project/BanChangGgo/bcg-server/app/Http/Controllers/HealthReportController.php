<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\HealthReport;
use Facades\App\Classes\DiseaseCategory;
use Auth;

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
            'usr_no' => Auth::id(),
            'ubt_no' => $request->get('ubt_no'),
            'hr_no' => null,
            'bt_no' => null,
            'bt_order' => null,
            'state' => 1,
            'ubt_state' => 1,
            'bt_state' => 1
        ];

        $reports = HealthReport::index($params);
        foreach ($reports as $report) {
            $report->disease_list = DiseaseCategory::list_index($report->disease_list);
        }

        return response()->json($reports);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request, $id)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'usr_no' => Auth::id(),
            'ubt_no' => null,
            'hr_no' => $id,
            'bt_no' => null,
            'bt_order' => null,
            'state' => null,
            'ubt_state' => 1,
            'bt_state' => 1
        ];

        $report = data_get(HealthReport::index($params), 0, null);
        if ($report != null) {
            $report->disease_list = DiseaseCategory::list_index($report->disease_list);
        }

        return response()->json($report);
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
