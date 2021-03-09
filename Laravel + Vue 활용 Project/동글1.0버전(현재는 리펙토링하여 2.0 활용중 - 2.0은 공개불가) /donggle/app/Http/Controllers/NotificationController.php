<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\FcmPush;

use DB;
use Auth;

class NotificationController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Notification controller';
    }

    public function index()
    {
        return 'Notification FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch ($req) {
            case 'list':
                $uid = Auth::guard('api')->id();
                $count = DB::table('notification')->where('target_mem_id', $uid)->count();
                $notification = DB::table('notification')
                ->where('target_mem_id', $uid)
                ->leftJoin('users', 'notification.mem_id', '=', 'users.id')
                ->leftJoin('seller_infor','seller_infor.uid','=','notification.mem_id')
                ->select('notification.*', 'users.profile_img', 'users.register_type', 'users.nickname as from_name', 'seller_infor.brandname as from_storename', 'seller_infor.profile_img as store_profile_img')
                ->orderBy('not_datetime', 'DESC');

                if ($request->filled('limit')) {
                    $limit = $request->limit;
                    $notification = $notification->limit($limit);
                }

                if ($request->filled('offset')) {
                    $offset = $request->offset;
                    $notification = $notification->offset($offset);
                }

                $notification = $notification->get();

                $query = array(
                    "notification" => $notification,
                    "count" => $count
                );


                if (count($query) == 0) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "존재하는 알림이 없음";
                    $this->res['state'] = config('res_code.NO_DATA');
                } else {
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if (!$request->filled('target_mem_id', 'not_type', 'not_content_id', 'not_message', 'not_url')) {
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $uid = Auth::guard('api')->id();
        $target_mem_id = $request->target_mem_id;
        $not_type = $request->not_type;
        $not_content_id = $request->not_content_id;
        $not_message = $request->not_message;
        $not_url = $request->not_url; //url 통일성 있게해서 not_type과 content_id 이용해서 제작할건지 회의 필요

        if($target_mem_id == $uid){
            $this->res['query'] = 0;
            $this->res['msg'] = "자기자신 후기 작성이라 알림 불가";
            $this->res['state'] = config('res_code.OK');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        try {
            $insert = DB::table('notification')->insert([
                'mem_id' => $uid,
                'target_mem_id' => $target_mem_id,
                'not_type' => $not_type,
                'not_content_id' => $not_content_id,
                'not_message' => $not_message,
                'not_url' => $not_url,
                'not_datetime' => DB::raw('now()'),
            ]);
            $this->res['query'] = $insert;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        } catch (Exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        $user = DB::table('users')->where('id',$target_mem_id)->first();
        $data = array();
        $data['title'] = "알림";
        $data['body'] = $not_message;
        $fcm_return_key = FcmPush::push_one($user->native_token, $data);

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        switch ($req) {
            case 'read':
                if (!$request->filled('not_id')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $not_id = $request->not_id;
                try {
                    $update = DB::table('notification')->where('target_mem_id', $uid)->where('not_id', $not_id)->update([
                        'not_read_datetime' => DB::raw('now()'),
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                } catch (Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {
        if (!$request->filled('not_id')) {
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $uid = Auth::guard('api')->id();
        $not_id = $request->not_id;

        $exist = DB::table('notification')->where('mem_id', $uid)->where('not_id', $not_id)->exists();
        if (!$exist) {
            $this->res['query'] = null;
            $this->res['msg'] = "이미 삭제되었거나 존재하지 않는 row!";
            $this->res['state'] = config('res_code.DUPLI_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        try {
            $delete = DB::table('notification')->where('mem_id', $uid)->where('not_id', $not_id)->delete();
            $this->res['query'] = $delete;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        } catch (Exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
