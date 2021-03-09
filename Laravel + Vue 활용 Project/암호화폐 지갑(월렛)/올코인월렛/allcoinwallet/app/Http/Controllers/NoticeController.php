<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $notices = DB::connection('mysql_sub')->table("btc_notice_$locale")->select('id', 'title', 'created')->orderBy('id', 'desc')->get();
        return response()->json($notices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $notice = DB::connection('mysql_sub')->table("btc_notice_$locale")->where('id', $id)->first();
        return response()->json($notice);
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
        //
    }
}
