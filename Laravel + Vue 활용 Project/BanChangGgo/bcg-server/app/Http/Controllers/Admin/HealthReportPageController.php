<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\HealthReportPage;
use Facades\App\Classes\MedicineInfo;
use Facades\App\Classes\NutritionInfo;
use Facades\App\Classes\HealthInfo;
use Auth;

class HealthReportPageController extends Controller
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
            'hrp_no' => $request->get('hrp_no'),
            'hr_no' => $request->get('hr_no'),
            'ubt_no' => $request->get('ubt_no'),
            'bt_no' => $request->get('bt_no'),
            'bt_order' => $request->get('bt_order'),
            'usr_no' => $request->get('usr_no')
        ];

        $pages = HealthReportPage::index($params);
        foreach ($pages as $page) {
            $page->mdcn_info = MedicineInfo::list_index($page->mdcn_info);
            $page->ntrcn_info = NutritionInfo::list_index($page->ntrcn_info);
            $page->health_info = HealthInfo::list_index($page->health_info);
        }

        return response()->json($pages);
    }

    public function valid(Request $request)
    {
        $params = [
            'hr_no' => $request->input('hr_no')
        ];

        return response()->json(HealthReportPage::valid($params));
    }

    public function store(Request $request)
    {
        $params = [
            'mdcn_info' => $request->input('mdcn_info', '[]'),
            'ntrcn_info' => $request->input('ntrcn_info', '[]'),
            'health_info' => $request->input('health_info', '[]'),
            'hrp_comment' => $request->input('hrp_comment', ''),
            'hrp_comment_detail' => $request->input('hrp_comment_detail', ''),
            'hrp_comment_med' => $request->input('hrp_comment_med', ''),
            'dc_no' => $request->input('dc_no'),
            'hr_no' => $request->input('hr_no'),
            'adm_no' => $request->input('adm_no', Auth::id())
        ];
        return response()->json(HealthReportPage::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'hrp_no' => $id,
            'hrp_comment' => $request->input('hrp_comment'),
            'hrp_comment_detail' => $request->input('hrp_comment_detail'),
            'hrp_comment_med' => $request->input('hrp_comment_med'),
            'mdcn_info' => $request->input('mdcn_info'),
            'ntrcn_info' => $request->input('ntrcn_info'),
            'health_info' => $request->input('health_info'),
            'dc_no' => $request->input('dc_no'),
            'hr_no' => $request->input('hr_no')
        ];

        return response()->json(HealthReportPage::update($params));
    }

    public function destroy(Request $request, $id)
    {
        $params = [
            'hrp_no' => $id
        ];

        return response()->json(HealthReportPage::destroy($params));
    }
}
