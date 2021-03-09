<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\FileRequest;
use Facades\App\Classes\MedicineInfo;

class MedicineInfoController extends Controller
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
            'mdcn_no' => $request->get('mdcn_no'),
            'mdcn_title' => $request->get('mdcn_title')
        ];

        return response()->json(MedicineInfo::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'mdcn_title' => $request->input('mdcn_title'),
            'mdcn_desc' => $request->input('mdcn_desc'),
            'mdcn_link' => $request->input('mdcn_link'),
            'mdcn_thumb' => FileRequest::set($request, 'file1', config('filesystems.medicine_thumb')),
            'mdcn_extra' => $request->input('mdcn_extra', '{}'),
            'state' => $request->input('state', 1)
        ];

        return response()->json(MedicineInfo::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'mdcn_no' => $id,
            'mdcn_title' => $request->input('mdcn_title'),
            'mdcn_desc' => $request->input('mdcn_desc'),
            'mdcn_link' => $request->input('mdcn_link'),
            'mdcn_thumb' => FileRequest::set($request, 'file1', config('filesystems.medicine_thumb'), MedicineInfo::show($id)->mdcn_thumb),
            'mdcn_extra' => $request->input('mdcn_extra'),
            'state' => $request->input('state')
        ];
        return response()->json(MedicineInfo::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
