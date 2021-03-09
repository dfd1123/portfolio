<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use \Facades\App\Classes\Follow;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $req = Input::get('req');
        switch ($req) {
            case 'following':

            $prdc_id = Input::get('prdc_id');
            $res = Follow::following(auth()->user()->user_id, $prdc_id);
            return response()->json($res);

            break;
            case 'leftheader':
                $res = Follow::leftheader(auth()->user()->user_id);
                return response()->json($res);
            break;
        }

        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Follow::store(auth()->user()->user_id, $request->prdc_id);
        return response()->json(null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Follow::destroy(auth()->user()->user_id, $id);
        return response()->json(null, 200);
    }
}
