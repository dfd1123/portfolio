<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use \Facades\App\Classes\Beat;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        // get 요청의 쿼리스트링 값 받기
        $value_name = Input::get('value_name');
        // 값이 없으면 기본값 지정가능
        $value_name = Input::get('value_name', 'default_value');
        */

        $res = Test::index();
        return response()->json($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Test::store($request->value);
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
        $res = Test::show($request->value);
        // 배열을 리턴하지 않음 값이 없으면 null
        return response()->json($res);
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
        Test::update($id, $request->value);
        return response()->json(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Test::destroy($id);
        return response()->json(null, 200);
        //return response()->json(null, 204); //해당 엔티티가 없으면
    }
}
