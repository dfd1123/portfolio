<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\SymptomInfo;
use Facades\App\Classes\FileRequest;

class SymptomInfoController extends Controller
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
            'smptm_no' => $request->get('smptm_no'),
            'smptm_title' => $request->get('smptm_title')
        ];

        return response()->json(SymptomInfo::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'smptm_title' => $request->input('smptm_title'),
            'smptm_desc' => $request->input('smptm_desc'),
            'smptm_link' => $request->input('smptm_link'),
            'smptm_thumb' => FileRequest::set($request, 'file1', config('filesystems.symptom_info_thumb')),
            'smptm_extra' => $request->input('smptm_extra', '{}'),
            'state' => $request->input('state', 1)
        ];

        return response()->json(SymptomInfo::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'smptm_no' => $id,
            'smptm_title' => $request->input('smptm_title'),
            'smptm_desc' => $request->input('smptm_desc'),
            'smptm_link' => $request->input('smptm_link'),
            'smptm_thumb' => FileRequest::set($request, 'file1', config('filesystems.symptom_info_thumb'), SymptomInfo::show($id)->smptm_thumb),
            'smptm_extra' => $request->input('smptm_extra'),
            'state' => $request->input('state')
        ];

        return response()->json(SymptomInfo::update($params));
    }

    public function destroy(Request $request, $id)
    {
        $params = [
            'smptm_no' => $id
        ];

        return response()->json(SymptomInfo::destroy($params));
    }
}
