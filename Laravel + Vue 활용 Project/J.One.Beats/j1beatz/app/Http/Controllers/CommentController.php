<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use FFMpeg;
use \Facades\App\Classes\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware([/*'passcookie', */'auth:api'])->except('index');
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
            case 'by_beat_id':

            $beat_id = Input::get('beat_id');
            $res = Comment::by_beat_id($beat_id);
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
        Comment::store(auth()->user()->user_id, $request->beat_id, $request->cmt_content);
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
        Comment::update(auth()->user()->user_id, $id, $request->cmt_content);
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
        Comment::destroy(auth()->user()->user_id, $id);
        return response()->json(null, 200);
    }
}
