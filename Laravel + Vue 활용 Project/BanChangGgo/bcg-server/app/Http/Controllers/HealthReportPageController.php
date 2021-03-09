<?php

namespace App\Http\Controllers;

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
            'ubt_no' => null,
            'bt_no' => null,
            'bt_order' => null,
            'usr_no' => Auth::id()
        ];

        $pages = HealthReportPage::index($params);
        foreach ($pages as $page) {
            $page->hrp_extra = json_decode($page->hrp_extra);
            $page->mdcn_info = MedicineInfo::list_index($page->mdcn_info);
            $page->ntrcn_info = NutritionInfo::list_index($page->ntrcn_info);
            $page->health_info = HealthInfo::list_index($page->health_info);
        }

        return response()->json($pages);
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
