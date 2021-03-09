<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\UserPlan;
use Facades\App\Classes\UserBatch;
use Auth;

class UserPlanController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $usr_extra = json_decode(Auth::user()->usr_extra);
        $upt_no = UserPlan::assert(Auth::id(), $usr_extra->user_type);

        $params = [
            'offset' => 0,
            'limit' => 1,
            'usr_no' => Auth::id(),
            'ubt_no' => null,
            'ubt_state' => 1,
            'bt_order' => null,
            'bt_state' => 1
        ];

        $user_batch = data_get(UserBatch::index($params), 0, null);
        if ($user_batch != null) {
            if ($user_batch->pt_day != null && $user_batch->pt_day <= $user_batch->pt_term) {
                UserPlan::assert_plan(Auth::id(), $upt_no, $user_batch->bt_order, $user_batch->pt_day);
                $user_plan = UserPlan::show(Auth::id(), $upt_no);
    
                if (count($user_plan->upt_list) != 0) {
                    return response()->json($user_plan);
                }
            }
        }

        UserPlan::assert_plan(Auth::id(), $upt_no);
        $user_plan = UserPlan::show(Auth::id(), $upt_no);

        return response()->json($user_plan);
    }

    public function store(Request $request)
    {
        $params = [
            'usr_no' => Auth::id(),
            'upt_no' => $request->input('upt_no'),
            'title' => $request->input('title', ''),
            'time' => $request->input('time', '00:00'),
            'memo' => $request->input('memo', ''),
            'push' => $request->input('push', 0),
            'kind' => $request->input('kind'),
        ];

        if (!in_array((string)$params['push'], ['0','1'], true)) {
            return abort(500);
        }

        if (!$this->validTime($params['time'], "H:i")) {
            return abort(500);
        }
        
        return response()->json(UserPlan::store($params));
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $item_id = $request->input('id');
        $check = is_numeric($item_id);

        $params = [
            'usr_no' => Auth::id(),
            'upt_no' => $id,
            'id' => $item_id,
            'result' => $request->input('result'),
            'title' => $check ? null : $request->input('title'),
            'time' => $check ? null : $request->input('time'),
            'memo' => $check ? null : $request->input('memo'),
            'push' => $check ? null : $request->input('push'),
            'kind' => $check ? null : $request->input('kind'),
        ];

        if (isset($params['result']) && !in_array((string)$params['result'], ['0','1'], true)) {
            return abort(500);
        }

        if (isset($params['push']) && !in_array((string)$params['push'], ['0','1'], true)) {
            return abort(500);
        }

        if (isset($params['time']) && !$this->validTime($params['time'], "H:i")) {
            return abort(500);
        }
        
        return response()->json(UserPlan::update($params));
    }

    public function destroy(Request $request, $id)
    {
        $params = [
            'usr_no' => Auth::id(),
            'upt_no' => $id,
            'id' => $request->input('id')
        ];

        if (empty($params['id'])) {
            return abort(500);
        }
        
        return response()->json(UserPlan::destroy($params));
    }
}
