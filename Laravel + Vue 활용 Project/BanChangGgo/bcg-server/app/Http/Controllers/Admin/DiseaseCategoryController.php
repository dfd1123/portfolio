<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\DiseaseCategory;

class DiseaseCategoryController extends Controller
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
            'dc_no' => $request->get('dc_no', null),
            'dc_cat_name' => $request->get('dc_cat_name', null),
            'state' => $request->get('state', 1)
        ];

        return response()->json(DiseaseCategory::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'dc_cat_name' => $request->input('dc_cat_name'),
            'dc_cat_etc' => $request->input('dc_cat_etc'),
            'state' => $request->input('state', 0),
        ];

        return response()->json(DiseaseCategory::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'dc_no' => $id,
            'dc_cat_name' => $request->input('dc_cat_name'),
            'dc_cat_etc' => $request->input('dc_cat_etc'),
            'state' => $request->input('state')
        ];

        return response()->json(DiseaseCategory::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
