<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\User;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        if($request['without_no']){
            $params = [
                'user_no' => $request['without_no'],
                'offset' => $request['offset'],
                'limit' => $request['limit']
            ];
            $res = User::without($params);
        }else{
            $params = [
                'user_id' => $request['user_id'],
                'user_no' => $request['user_no'],
                'name' => $request['user_name'],
                'stdyear' => $request['stdyear'],
                'name' => $request['user_name'],
                'offset' => $request['offset'],
                'limit' => $request['limit']
            ];
            $res = User::index($params);
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
