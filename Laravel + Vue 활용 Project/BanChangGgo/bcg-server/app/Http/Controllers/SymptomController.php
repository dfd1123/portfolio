<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\Symptom;
use Facades\App\Classes\SymptomInfo;
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

        $symptoms = Symptom::index($params);
        foreach ($symptoms as $symptom) {
            $symptom->spt_contents = SymptomInfo::list_index($symptom->spt_contents);
        }

        return response()->json($symptoms);
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
