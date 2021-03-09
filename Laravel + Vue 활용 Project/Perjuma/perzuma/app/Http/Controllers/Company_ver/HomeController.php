<?php

namespace App\Http\Controllers\Company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;

class HomeController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function index(){
        $views = view('company_ver.main.main');
        $views->title = '퍼주마 업체 메인';

        $sql = "SELECT trd_no
        ,trd_name
        ,state
        ,bl_no
        ,created_at::date
        ,updated_at::date
        FROM  trades
        WHERE agent_no = :user_no
        AND state = 3 OR state = 4 OR state = 5
        OFFSET 0 LIMIT 10;";

        $p['user_no'] = auth()->user()->user_no;//업체 token 받아서 넣기
        $res = $this->execute_query($sql, $p);
        $views->agent = $res['query'];
        return $views;
    }
    public function main2(Request $request, $trd_no){
        $views = view('company_ver.main.main2');

        $trade = DB::table('trades')->where('trades.trd_no',$trd_no)->where('trades.agent_no',auth()->user()->user_no)->leftJoin('supervison', 'trades.supervison_no', '=', 'supervison.sp_no')->first();
        $client = DB::table('users')->where('user_no',$trade->client_no)->first();

        $agent_comments = DB::table('user_trd_comment')->where('trd_no',$trd_no)->where('client_no', NULL)->orderBy('reg_dt', 'desc')->limit(20)->get();
        $client_comments = DB::table('user_trd_comment')->where('trd_no',$trd_no)->where('agent_no', NULL)->orderBy('reg_dt', 'desc')->limit(20)->get();

        $views->title = $trade->trd_name;
        $views->is_premium = $trade->is_premium;
        $views->client_name = $client->name;
        $views->client_contact = $client->user_contact;
        $views->client_email = $client->email;
        $views->updated_at = date("Y.m.d H:i:s", strtotime($trade->updated_at)).' Updated';
        $views->trd_no = $trd_no;
        $views->trade = $trade;
        $views->agent_comments = $agent_comments;
        $views->client_comments = $client_comments;
        return $views;
    }
}
