<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;

class UserController extends Controller
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

        if($request->filled('type')){
            if($request->input('type') == 'usr_no'){
                $res = DB::table('users')
                ->where($request->input('type'), $request->input('keyword'))
                ->orderBy('usr_no', 'desc')
                ->offset($params['offset'])
                ->limit($params['limit'])
                ->get();
            }else{
                $res = DB::table('users')
                ->where($request->input('type'),'like', '%'.$request->input('keyword').'%')
                ->orderBy('usr_no', 'desc')
                ->offset($params['offset'])
                ->limit($params['limit'])
                ->get();
            }
            
        }else{
            $res = DB::table('users')
            ->orderBy('usr_no', 'desc')
            ->offset($params['offset'])
            ->limit($params['limit'])
            ->get();
        }

        return response()->json($res);
    }

    public function search(Request $request)
    {
        $params = [
            'offset' => $request->get('offset', 0),
            'limit' => $request->get('limit', null)
        ];
        

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
            'state' => $request->input('state', DB::raw('state'))
        ];

        $res = DB::table('users')
            ->where('usr_no', $id)
            ->limit(1)
            ->update($params);

        return response()->json($res > 0);
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
