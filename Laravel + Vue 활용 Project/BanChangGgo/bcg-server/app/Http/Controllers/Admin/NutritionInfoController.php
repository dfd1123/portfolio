<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\FileRequest;
use Facades\App\Classes\NutritionInfo;

class NutritionInfoController extends Controller
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
            'ntrcn_no' => $request->get('ntrcn_no'),
            'ntrcn_title' => $request->get('ntrcn_title')
        ];

        return response()->json(NutritionInfo::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'ntrcn_title' => $request->input('ntrcn_title'),
            'ntrcn_desc' => $request->input('ntrcn_desc'),
            'ntrcn_link' => $request->input('ntrcn_link'),
            'ntrcn_thumb' => FileRequest::set($request, 'file1', config('filesystems.nutrition_thumb')),
            'ntrcn_extra' => $request->input('ntrcn_extra', '{}'),
            'state' => $request->input('state', 1)
        ];

        return response()->json(NutritionInfo::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'ntrcn_no' => $id,
            'ntrcn_title' => $request->input('ntrcn_title'),
            'ntrcn_desc' => $request->input('ntrcn_desc'),
            'ntrcn_link' => $request->input('ntrcn_link'),
            'ntrcn_thumb' => FileRequest::set($request, 'file1', config('filesystems.nutrition_thumb'), NutritionInfo::show($id)->ntrcn_thumb),
            'ntrcn_extra' => $request->input('ntrcn_extra'),
            'state' => $request->input('state')
        ];

        return response()->json(NutritionInfo::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
