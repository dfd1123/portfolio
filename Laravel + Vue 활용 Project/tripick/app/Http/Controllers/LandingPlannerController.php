<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use Facades\App\Classes\FcmPush;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Input;

class LandingPlannerController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        
    }
    public function __invoke($id)
    {
        return 'PUSH Controller';
    }

    public function index()
    {
        return 'API FOR PUSH';
    }
    public function show(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //push메시지 목록 혹은 특정 push메세지
            case 'list':
                $params = array();

                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                }
                $sql = "SELECT name
                ,email
                ,phone
                ,active_area
                ,reg_dt at time zone 'KST' AS reg_dt
                FROM landing_planner
                ORDER BY reg_dt DESC
                OFFSET :offset LIMIT 10;";
                
                $this->execute_query($sql, $params);
            break;
    
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //메시지발송
    public function store(Request $request)
    {
    	
        $p = $request->all();

        $params = array();
        if ($request->filled('name', 'email', 'phone', 'active_area')
        && $this->checkLength($p['name'], 1, 10)
		&& $this->checkLength($p['email'], 1, 30)
		&& $this->checkLength($p['phone'], 1, 15)
		&& $this->checkLength($p['active_area'], 1, 30)
         ) {
         	
            $sql = "INSERT INTO landing_planner( 
            name
            ,email
            ,phone
            ,active_area
			,reg_dt)
            VALUES(
            :name
            ,:email
            ,:phone
            ,:active_area
            ,now())
            RETURNING reg_dt;";

            $params['name'] = $p['name'];
            $params['email'] = $p['email'];
            $params['phone'] = $p['phone'];
			$params['active_area'] = $p['active_area'];
			
            $this->execute_query($sql, $params);
        }else{
        	$this->res['query'] =null;
            $this->res['state'] = config('rescode.NO_PARAM_0');
            $this->res['msg'] ='값 누락 - BRANCH';
			//echo "<script>alert('정확한 정보를 입력해주세요');history.go(-1)</script>";
		}

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}