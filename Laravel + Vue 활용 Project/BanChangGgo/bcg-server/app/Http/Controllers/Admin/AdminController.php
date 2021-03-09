<?php

namespace App\Http\Controllers\Admin;

use Facades\App\Classes\FileRequest;
use Illuminate\Http\Request;
use DB;

class AdminController extends Controller
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

        $res = DB::table('admins')
            ->orderBy('adm_no', 'desc')
            ->offset($params['offset'])
            ->limit($params['limit'])
            ->get();

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
        $params = [
            'adm_name' => $request->input('adm_name', DB::raw('adm_name')),
            'adm_contact' => $request->input('adm_contact', DB::raw('adm_contact')),
            'adm_memo' => $request->input('adm_memo', DB::raw('adm_memo')),
            'adm_thumb' => FileRequest::set($request, 'file1', config('filesystems.admin_thumb')),
            'state' => $request->input('state', DB::raw('state'))
        ];

        $res = DB::table('admins')
            ->where('adm_no', $id)
            ->limit(1)
            ->update($params);

        return response()->json($res > 0);
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
