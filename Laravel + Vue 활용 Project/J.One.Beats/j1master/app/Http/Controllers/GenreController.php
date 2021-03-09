<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class GenreController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        if ($this->JWTClaims ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('rescode.NO_AUTH_100');
            $this->res['msg'] ='no-auth';
            die($this->res);
        }
    }
    public function __invoke($id)
    {
        return 'Genre Controller';
    }

    public function index()
    {
        return 'API FOR Genre';
    }

    // 요청경로  GET - URL  : api/Genre/{req}
    public function show(Request $request, $req='list')
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            default:
            case 'list':

                $params['offset'] =0;
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                }
                
                $sql = "SELECT 
                    cate_id,
                    cate_title
                FROM category
                ORDER BY cate_id  DESC
                OFFSET :offset LIMIT 50;";
                $this->execute_query($sql, $params);

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        $p = $request->all();

        $params = array();
        if ($request->filled('req')) {
            switch ($request->input('req')) {
            default:
            case 'reg':

                if (
                    $request->filled('cate_title')
                    && $this->checkLength($p['cate_title'], 2, 32)
                ) {
                    $sql = "INSERT INTO category (
                            cate_title
                        ) VALUES (
                            :cate_title
                        )
                        RETURNING cate_id;";

                    $params['cate_title']  = $p['cate_title'];
                    $this->execute_query($sql, $params);
                } else {
                    $this->res['msg']='입력오류';
                    $this->res['state']= config('rescode.NO_PARAM_0');
                }
            break;
            }
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req='state')
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
                default:
                case 'state':
                if ($request->filled('cate_id')) {
                    $sql="UPDATE 
                    category SET 
                        state = :state
                    WHERE cate_id = :cate_id";

                    $params = array('cate_id'=> $p['cate_id'], 'state'=>$p['state']);

                    $this->execute_query($sql, $params);
                }
                
                break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
