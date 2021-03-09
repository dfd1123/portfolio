<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;
use Auth;

class CorporationStatusController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function corporation_status(Request $request, $trd_no){

        $views = view('user_ver.corporation_status.corporation_status');

        $trade = DB::table('trades')->where('trades.trd_no',$trd_no)->leftJoin('supervison', 'trades.supervison_no', '=', 'supervison.sp_no')->first();

        $agent_comments = DB::table('user_trd_comment')->where('trd_no',$trd_no)->where('client_no', NULL)->orderBy('reg_dt', 'desc')->limit(20)->get();
        $client_comments = DB::table('user_trd_comment')->where('trd_no',$trd_no)->where('agent_no', NULL)->orderBy('reg_dt', 'desc')->limit(20)->get();

        $views->title = $trade->trd_name;
        $views->updated_at = date("Y.m.d H:i:s", strtotime($trade->updated_at)).' Updated';
        $views->trd_no = $trd_no;
        $views->trade = $trade;
        $views->agent_comments = $agent_comments;
        $views->client_comments = $client_comments;

        //$headers = apache_request_headers();
        //dd($headers);

        return $views;
    }
}
