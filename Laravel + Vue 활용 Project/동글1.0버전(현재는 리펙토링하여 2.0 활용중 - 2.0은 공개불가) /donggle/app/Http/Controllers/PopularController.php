<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class PopularController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __construct(){
    }

    public function __invoke($id)
    {
        return 'Popular controller';
    }

    public function index()
    {
        return 'Popular FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $limit = ($request->filled('limit'))?$request->limit:10;
                $today = date("Y-m-d H:i:s");
                $start_date = date("Y-m-d H:i:s", strtotime("-1 week")); // 일주일 전

                $populars = DB::table('popular')
                ->select('pp_word',DB::raw('count(*) AS cnt'))
                ->whereBetween('pp_date', [$start_date, $today])
                ->groupBy('pp_word')
                ->orderBy('cnt','DESC')
                ->offset(0)->limit($limit)
                ->get();

                $this->res['query'] = $populars;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {

        if(!$request->filled('pp_word')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        $pp_word = $request->pp_word;
        $pp_ip = $_SERVER["REMOTE_ADDR"];
        
        try {
            $insert = DB::table('popular')->insert([
                'pp_word' => $pp_word,
                'pp_ip' => $pp_ip,
                'pp_date' => DB::raw('now()'),
            ]);
            $this->res['query'] = $insert;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        
        } catch(Exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)"; 
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
