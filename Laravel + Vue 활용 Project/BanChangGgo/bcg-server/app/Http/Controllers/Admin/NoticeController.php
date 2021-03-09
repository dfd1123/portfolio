<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\Notice;

class NoticeController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $params = [
            'offset' => $request->get('offset', 0),
            'limit' => $request->get('limit', null)
        ];

        return response()->json(Notice::index($params));
    }

    public function store(Request $request)
    {
        $params = [
            'ntc_title' => $request->input('ntc_title'),
            'ntc_content' => $request->input('ntc_content')
        ];
        return response()->json(Notice::store($params), 201);
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $params = [
            'ntc_no' => $id,
            'ntc_title' => $request->input('ntc_title'),
            'ntc_content' => $request->input('ntc_content')
        ];

        return response()->json(Notice::update($params));
    }

    public function destroy(Request $request, $id)
    {
        $params = [
            'ntc_no' => $id
        ];

        return response()->json(Notice::destroy($params));
    }
}
