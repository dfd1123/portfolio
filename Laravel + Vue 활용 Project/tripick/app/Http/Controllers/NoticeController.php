<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class NoticeController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'User controller';
    }

    public function index()
    {
        return 'API FOR Notice';
    }

    // 요청경로  GET - URL  : api/User/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            
            //모든유저가 접근가능
            case 'list':
            $params = array();
                
            if ($request->filled('offset') && $request->input('offset') >= 0) {
                $params['offset'] = $p['offset'];
            } else { //start가 없거나 0보다 작은경우
                $params['offset'] =0;
            }

            $sql = "SELECT * FROM notice ORDER BY created_at DESC OFFSET :offset LIMIT 10";

            $params = array('offset'=>$p['offset']);

            $this->res = $this->execute_query($sql, $params);
            
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
    }
    
    public function update(Request $request, $req)
    {
    }

    public function destroy(Request $request)
    {
    }
}
