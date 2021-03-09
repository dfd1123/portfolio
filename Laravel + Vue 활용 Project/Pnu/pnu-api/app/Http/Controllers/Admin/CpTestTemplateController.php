<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\CpTestTemplate;
use Auth;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CpTestTemplateExport;
use App\Imports\CpTestTemplateImport;

class CpTestTemplateController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $params = [
            'cpt_id' => $request['cpt_id'],
            'cpt_order' => $request['cpt_order'],
            'offset' => $request['offset'],
            'limit' => $request['limit']
        ];

        $res = CpTestTemplate::index($params);

        foreach ($res as $row) {
            if ($row->cpt_question) {
                $row->cpt_question = json_decode($row->cpt_question);
            }
        }

        return response()->json($res);
    }

    public function store(Request $request)
    {
        $params = [
            'cpt_order' => $request->cpt_order,
            'cpt_title' => $request->cpt_title,
            'cpt_title_en' => $request->cpt_title_en,
            'cpt_desc' => $request->cpt_desc,
            'cpt_title' => $request->cpt_title,
            'cpt_question' => $request->cpt_question,
            'admin_id' => Auth::id(),
        ];
        
        $res = CpTestTemplate::store($params);

        return response()->json($res, 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'cpt_id' => $id,
            'cpt_order' => $request->cpt_order,
            'cpt_title' => $request->cpt_title,
            'cpt_title_en' => $request->cpt_title_en,
            'cpt_desc' => $request->cpt_desc,
            'cpt_title' => $request->cpt_title,
            'cpt_question' => json_encode($request->cpt_question ?? null),
            'admin_id' => Auth::id(),
        ];

        $res = CpTestTemplate::update($params);

        return response()->json($res);
    }

    public function destroy(Request $request, $id)
    {
        //
    }

    public function export(Request $request)
    {
        return Excel::download(new CpTestTemplateExport, 'template.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new CpTestTemplateImport, request()->file('file'));

        return response()->json(null, 200);
    }
}
