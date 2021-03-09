<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\Batch;
use Facades\App\Classes\DiseaseCategory;
use Auth;

class BatchController extends Controller
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
            'usr_no' => Auth::id()
        ];

        $batches = Batch::user_index($params);
        foreach ($batches as $batch) {
            if (isset($batch->disease_list)) {
                $batch->disease_list = DiseaseCategory::list_index($batch->disease_list);
            }
        }

        return response()->json($batches);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request, $id)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'bt_no' => $id
        ];

        $batch = data_get(Batch::index($params), 0, null);
        if($batch != null) {
            $batch->bt_qna_list = json_decode($batch->bt_qna_list, true);
        }

        return response()->json($batch);
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
