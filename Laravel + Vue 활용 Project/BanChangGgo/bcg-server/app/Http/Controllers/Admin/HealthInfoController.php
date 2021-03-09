<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\FileRequest;
use Facades\App\Classes\HealthInfo;

class HealthInfoController extends Controller
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
            'hlth_no' => $request->get('hlth_no'),
            'hlth_title' => $request->get('hlth_title')
        ];

        return response()->json(HealthInfo::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'hlth_title' => $request->input('hlth_title'),
            'hlth_desc' => $request->input('hlth_desc'),
            'hlth_link' => $request->input('hlth_link'),
            'hlth_thumb' => FileRequest::set($request, 'file1', config('filesystems.health_thumb')),
            'hlth_extra' => $request->input('hlth_extra', '{}'),
            'state' => $request->input('state', 1)
        ];

        return response()->json(HealthInfo::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'hlth_no' => $id,
            'hlth_title' => $request->input('hlth_title'),
            'hlth_desc' => $request->input('hlth_desc'),
            'hlth_link' => $request->input('hlth_link'),
            'hlth_thumb' => FileRequest::set($request, 'file1', config('filesystems.health_thumb'), HealthInfo::show($id)->hlth_thumb),
            'hlth_extra' => $request->input('hlth_extra'),
            'state' => $request->input('state')
        ];

        return response()->json(HealthInfo::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
