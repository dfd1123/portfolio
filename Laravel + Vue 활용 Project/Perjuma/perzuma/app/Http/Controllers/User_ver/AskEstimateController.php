<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class AskEstimateController extends Controller
{
    public function ask_estimate_list(){
        $views = view('user_ver.ask_estimate.ask_estimate_list');

        $views->title = '요청 견적들';

        return $views;
    }

    public function data_load(Request $request, $req){
        switch($req){
            case 'default':
                return $this->default_data_load($request);
            case 'more':
                return $this->more_data_load($request);
        }
    }

    private function default_data_load(Request $request){

        $limit = 20;

        $trades_cnt = DB::table('trades')->where('client_no',auth()->user()->user_no)->count();
        $trades = DB::table('trades')
                ->where('client_no',auth()->user()->user_no)
                ->join('business_list', 'trades.bl_no', '=', 'business_list.bl_no')
                ->orderBy('state','desc')
                ->orderBy('created_at','desc')
                ->limit($limit)->get();

        $response = array(
            "trades" => $trades,
            "trades_cnt" => count($trades),
            "offset" => count($trades),
        );

        return response()->json($response);
    }

    private function more_data_load(Request $request){

        $offset = $request->offset;
        $limit = 20;

        $trades = DB::table('trades')
                ->where('client_no',auth()->user()->user_no)
                ->join('business_list', 'trades.bl_no', '=', 'business_list.bl_no')
                ->orderBy('state','desc')
                ->orderBy('created_at','desc')
                ->offset($offset)->limit($limit)->get();

        $offset += count($trades);

        $response = array(
            "trades" => $trades,
            "offset" => $offset,
        );

        return response()->json($response);
    }
}
