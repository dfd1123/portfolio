<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use \Facades\App\Classes\Producer;

class ProducerController extends Controller
{
    public function __construct()
    {
        $this->middleware([/*'passcookie', */'auth:api'])->only('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $req = Input::get('req');

        switch ($req) {
            case 'best_ten':

            $res = Producer::best_ten();

            return response()->json($res);
            break;

            case 'check_prdc_nick_duplicate':

            $prdc_nick = Input::get('prdc_nick');
            $res = Producer::by_prdc_nick($prdc_nick);
            if ($res !== null) {
                return response()->json(null, 422);
            }

            return response()->json(null, 200);
            break;

            case 'orderby_prdc':
                $orderby = Input::get('orderby');
                $offset = Input::get('offset', 0);
                $limit = Input::get('limit', 100);
                $res = Producer::orderby_prdc($orderby, $offset, $limit);
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
        Producer::updateinfo(auth()->user()->user_id, $request);
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
        $prdc_info = Producer::show($id);
        if ($prdc_info !== null) {
            $prdc_info->mood_json = json_decode($prdc_info->mood_json);
            $prdc_info->cate_json = json_decode($prdc_info->cate_json);
        } else {
            return response()->json(null, 404);
        }

        return response()->json($prdc_info);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
