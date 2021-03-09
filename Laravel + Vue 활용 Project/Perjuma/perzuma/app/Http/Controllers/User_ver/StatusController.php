<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;

class StatusController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function construct_status(){
        $views = view('user_ver.construct_status.construct_status');

        $views->title = '시공 중인 리스트';

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

        $trades_cnt = DB::table('trades')->where('client_no',auth()->user()->user_no)->where('state', 4)->count();
        $trades = DB::table('trades')
                ->where('client_no',auth()->user()->user_no)
                ->where('state', 4)
                ->join('business_list', 'trades.bl_no', '=', 'business_list.bl_no')
                ->orderBy('created_at','desc')
                ->limit($limit)->get();

        $response = array(
            "trades" => $trades,
            "trades_cnt" => $trades_cnt,
            "offset" => count($trades),
        );

        return response()->json($response);
    }

    private function more_data_load(Request $request){

        $offset = $request->offset;
        $limit = 20;

        $trades = DB::table('trades')
                ->where('client_no',auth()->user()->user_no)
                ->where('state', 4)
                ->join('business_list', 'trades.bl_no', '=', 'business_list.bl_no')
                ->orderBy('created_at','desc')
                ->offset($offset)->limit($offset)->get();

        $offset += count($trades);

        $response = array(
            "trades" => $trades,
            "offset" => $offset,
        );

        return response()->json($response);
    }
}
