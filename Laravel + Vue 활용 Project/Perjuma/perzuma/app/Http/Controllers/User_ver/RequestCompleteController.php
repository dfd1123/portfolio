<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use Test;
use DB;
use DateTime;

class RequestCompleteController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function request_complete(Request $request){
        $trd_no = $request->id;
        $views = view('user_ver.request_complete.request_complete');

        $views->title = '견적서 요청';
        $views->trd_no = $trd_no;

        return $views;
    }

    public function data_load(Request $request){
        $trd_no = $request->trd_no;
        $bidding_dt = DB::table('trades')->where('trd_no', $trd_no)->value('bidding_dt');
        
        $bidding_end_timestamp = strtotime("+15 hours", strtotime($bidding_dt));
        $bidding_end_time = date("Y-m-d H:i:s", $bidding_end_timestamp);
        
        $bidding_time = new DateTime($bidding_dt);
        $bidding_end_time = new DateTime($bidding_end_time);
        
        $diff_day = date_diff($bidding_time, $bidding_end_time);

        if($bidding_end_timestamp < strtotime(date('Y-m-d H:i:s'))){
            $hours = 0;
            $minute = 0;
        }else{
            $hours = $diff_day->h;
            $minute = $diff_day->i;
        }

        $response = array(
            "hour" => $hours,
            "minute" => $minute,
        );

        return response()->json($response);
    }
}
