<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use \Facades\App\Classes\Playfolder;

class PlayfolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Playfolder::index();
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
        $req = $request->req;
        switch($req){
            case 'addnewfolder':
            Playfolder::newfolder();
            break;
            case 'addbeat_at_newplayfolder':
            if(!$request->has('pf_title')||!$request->has('beats')){
                return response()->json(null, 422);
            }
            $pf_title = $request->pf_title;
            $beats = $request->beats;
            Playfolder::newfolderaddbeat($pf_title, $beats);
            break;
        }
        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $req = Input::get('req');
        switch($req){
            case 'detail':
            $res = Playfolder::detail($id);
            break;
        }
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
        $req = $request->req;
        switch($req){
            case 'titleupdate':
            Playfolder::titleupdate($request, $id);
            break;
            case 'addbeat_at_playfolder':
            if(!$request->has('beats')){
                return response()->json(null, 422);
            }
            $beats = $request->beats;
            Playfolder::currentfolderaddbeat($beats, $id);
            break;
            case 'deletebeat_at_playfolder':
            if(!$request->has('beats')){
                return response()->json(null, 422);
            }
            $beats = $request->beats;
            Playfolder::PlayfolderBeatDelete($beats, $id);
            break;
        }
        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Playfolder::PlayfolderDelete(auth('api')->user()->user_id, $id);
        return response()->json(null, 200);
    }
}
