<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\Symptom;
use Facades\App\Classes\FileRequest;

class SymptomController extends Controller
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
            'spt_no' => $request->get('spt_no'),
        ];

        return response()->json(Symptom::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'spt_title' => $request->input('spt_title'),
            'spt_thumb' => FileRequest::set($request, 'file1', config('filesystems.symptom_thumb')),
            'spt_contents' => $request->input('spt_contents', '[]'),
            'state' => $request->input('state', 1),
        ];

        return response()->json(Symptom::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'spt_no' => $id,
            'spt_title' => $request->input('spt_title'),
            'spt_thumb' => FileRequest::set($request, 'file1', config('filesystems.symptom_thumb'), Symptom::show($id)->spt_thumb),
            'spt_contents' => $request->input('spt_contents'),
            'state' => $request->input('state'),
        ];

        return response()->json(Symptom::update($params));
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
