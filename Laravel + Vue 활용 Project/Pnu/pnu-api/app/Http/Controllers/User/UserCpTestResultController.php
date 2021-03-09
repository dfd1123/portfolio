<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Facades\App\Classes\UserCpTestResult;
use Facades\App\Classes\Batch;
use Auth;
use DB;

class UserCpTestResultController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $user_id = Auth::id();
        //연간변화
        if ($request->filled('req')) {
            $req =$request->req;
            $params = array();
            $inClause = "";
            switch ($req) {
                case 'cpd_hsty':
                    //연간변화량
                    
                    $sql ="SELECT	 batch_id, JSON_ARRAYAGG(JSON_EXTRACT(ucpt_answer , '$[*][*].value')) as val
                    FROM	pnu.user_cpt
                    WHERE	user_id = :user_id
                    GROUP BY batch_id;";

              $params['user_id'] = $user_id;
              $this->res['query'] = DB::select($sql, $params);
              $this->res['state'] =1;


                break;
                case 'cpd_dept_avg':
                case 'cpd_major_avg':
                case 'cpd_avg':

                    if ($request->filled('batch_id')) {
                        $params['batch_id'] = $request->batch_id;
                    } else {
                        $this->res['state']=0;
                        $this->res['msg'] = 'no-params';
                        $this->res['query'] =null;
                        return response()->json($this->res);
                    }

                    if ($request->filled('majorcd') || $request->filled('deptcd')) {
                        $inClause = " AND user_id IN   (SELECT user_id	FROM users WHERE deptcd LIKE :cd OR majorcd LIKE :cd1)  ";
                        $params['cd1'] =  $request->filled('majorcd') ? $request->majorcd : $request->deptcd;
                        $params['cd'] =  $request->filled('majorcd') ? $request->majorcd : $request->deptcd;
                    }

                    $sql ="SELECT	JSON_EXTRACT(ucpt_answer , '$[*][*].value') as val
FROM	pnu.user_cpt
WHERE	batch_id = :batch_id ". $inClause;

              $this->res['query'] = DB::select($sql, $params);
              $this->res['state'] =1;
                break;
            }
            
            return response()->json($this->res);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        switch ($id) {
            case 1:
                $latest_batch_id = Batch::latest();
                return response()->json(UserCpTestResult::result_1($user->user_id, $latest_batch_id));
            case 2:
                return response()->json(UserCpTestResult::result_2($user->user_id));
            case 3:
                return response()->json(UserCpTestResult::result_3());
            case 4:
                $latest_batch_id = Batch::latest();
                return response()->json(UserCpTestResult::result_4($user->collcd, $latest_batch_id));
            case 5:
                $latest_batch_id = Batch::latest();
                return response()->json(UserCpTestResult::result_5($user->deptcd, $latest_batch_id));
            case 6:
                $latest_batch_id = Batch::latest();
                return response()->json(UserCpTestResult::result_6($user->stdyear, $latest_batch_id));
            break;
        }

        return abort(404);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
