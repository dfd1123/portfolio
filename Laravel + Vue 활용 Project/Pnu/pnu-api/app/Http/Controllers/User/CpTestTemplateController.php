<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Facades\App\Classes\CpTestTemplate;
use Facades\App\Classes\UserCpTest;
use Auth;

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
        $count_questions = CpTestTemplate::count_questions();
        $count_answered = UserCpTest::count_answered(Auth::id());

        foreach ($res as $row) {
            if ($row->cpt_question) {
                $row->cpt_question = json_decode($row->cpt_question);
            }

            $row->count_questions = (int) $count_questions;
            $row->count_answered = (int) $count_answered;
        }

        return response()->json($res);
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
