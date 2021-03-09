<?php

namespace App\Http\Controllers\User_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use DB;

class HomeController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function index(){
        $views = view('user_ver.main.main');
        
        return $views;
    }

    private function urlc(){
        $client = new Client(['base_uri' => 'http://perzuma.local']);

        $response = $client->request('GET', '/api/user_no', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ]);

        $user_no = (string) $response->getBody();

        return $user_no;
    }

    public function recomend_company(Request $request){
        $recommend_agents = DB::table('agent_info')->orderBy('agent_construction_cnt', 'desc')->orderBy('agent_review_cnt','desc')->limit(9)->get();
        
        $response = array(
            "recommend_agents" => $recommend_agents,
        );

        return response()->json($response);
    }
}
