<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;
use DateTime;

class EstimateManageController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function estimate_manage(Request $request)
    {
        $trd_no = $request->id;
        $views = view('user_ver.estimate_manage.estimate_manage');
        $views->title = '계약현황';
        $views->trd_no = $trd_no;

        return $views;
    }

    public function data_load(Request $request)
    {
        $trd_no = $request->trd_no;

        $estimate_lists = DB::table('agent_trades_bidding')
        ->where('trd_no', $trd_no)
        ->join('agent_info', 'agent_trades_bidding.agt_no', '=', 'agent_info.agent_no')
        ->orderBy('atb_no','desc')
        ->orderBy('agent_trades_bidding.state', 'desc')
        ->limit(3)->get();
        $trade = DB::table('trades')
        ->where('trd_no', $trd_no)
        ->select('agent_no', 'state', 'bidding_dt')
        ->first();

        //dd($estimate_lists);
        $bidding_end_timestamp = strtotime("+15 hours", strtotime($trade->bidding_dt));
        $bidding_end_time = date("Y-m-d H:i:s", $bidding_end_timestamp);
        
        $bidding_time = new DateTime($trade->bidding_dt);
        $bidding_end_time = new DateTime($bidding_end_time);
        
        $diff_day = date_diff($bidding_time, $bidding_end_time);

        if ($bidding_end_timestamp < strtotime(date('Y-m-d H:i:s'))) {
            $hours = 0;
            $minute = 0;
        } else {
            $hours = $diff_day->h;
            $minute = $diff_day->i;
        }

        $response = array(
            "estimate_lists" => $estimate_lists,
            "trade" => $trade,
            "hours" => $hours,
            "minute" => $minute,
        );

        return response()->json($response);
    }
}
