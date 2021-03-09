<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;

class PopupController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function __invoke($id)
    {
        return 'Popup controller';
    }

    public function index()
    {
        return 'Popup FOR API';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $date = date("Y-m-d");
                $count = DB::table('popup')->where('start_time','<=',$date)->where('end_time','>=',$date)->count();
                $popups = DB::table('popup')->where('start_time','<=',$date)->where('end_time','>=',$date)->orderBy('id','DESC')->get();

                $query = array(
                    "count" => $count,
                    "popups" => $popups,
                );
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        
    }

    public function update(Request $request, $req)
    {
       
     
    }

    //사용고민중.
    public function destroy(Request $request)
    {

    }
}
