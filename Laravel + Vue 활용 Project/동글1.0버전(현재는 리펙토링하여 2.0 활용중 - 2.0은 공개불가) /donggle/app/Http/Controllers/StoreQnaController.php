<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;

class StoreQnaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kind = $request->kind;

        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if(!$request->filled('store_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        $store_id = $request->store_id;
        $uid = Auth::guard('api')->id();
        $limit = $request->filled('limit')?$request->limit:20;
        $offset = $request->filled('offset')?$request->offset:0;

        switch($kind){
            case 'question':
                $qnas = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->select('store_qna.*', 'users.nickname', 'users.profile_img')
                ->where('store_id', $store_id)->where('q_id', $uid)->limit($limit)->offset($offset)->orderBy('q_datetime','DESC')->get();
                $count = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->where('store_id', $store_id)->where('q_id', $uid)->count();

                $query = array(
                    "qnas" => $qnas,
                    "count" => $count
                );

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

            break;

            case 'answer':
                $qnas = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->select('store_qna.*', 'users.nickname', 'users.profile_img')
                ->where('store_id', $store_id)->where('a_id', $uid)->limit($limit)->offset($offset)->orderBy('q_datetime','DESC')->get();
                $count = DB::table('store_qna')
                ->join('users','users.id','=','store_qna.q_id')
                ->select('store_qna.*', 'users.nickname', 'users.profile_img')
                ->where('store_id', $store_id)->where('a_id', $uid)->count();

                $query = array(
                    "qnas" => $qnas,
                    "count" => $count
                );

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if(!$request->filled('subject', 'question', 'store_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $store_id = $request->store_id;
        $user = Auth::guard('api')->user();
        $uid = $user->id;
        $q_email = $user->email;
        $q_name = $user->nickname;
        $q_hp = $user->mobile_number;
        $q_ip = $_SERVER["REMOTE_ADDR"];
        $subject = $request->subject;
        $question = $request->question;

        try {
            $insert = DB::table('store_qna')->insert([
                'store_id' => $store_id,
                'q_id' => $uid,
                'q_email' => $q_email,
                'q_name' => $q_name,
                'q_hp' => $q_hp,
                'q_ip' => $q_ip,
                'subject' => $subject,
                'question' => $question,
                'q_datetime' => DB::raw('now()'),
                'status' => 0,
                'secret' => 0,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        try{
        $qna = DB::table('store_qna')->where('id', $id)->first();

            $this->res['query'] = $qna;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        }catch(Exception $e){
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)"; 
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $req)
    {
        switch($req){
            case 'question':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                if(!$request->filled('subject', 'question', 'id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                
                $id = $request->id;
                $subject = $request->subject;
                $question = $request->question;
        
                try {
                    $update = DB::table('store_qna')->where('id',$id)->update([
                        'subject' => $subject,
                        'question' => $question
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                
                } catch(Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;

            case 'answer':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                if(!$request->filled('answer','id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                
                $id = $request->id;
                $answer = $request->answer;
        
                try {
                    $update = DB::table('store_qna')->where('id',$id)->update([
                        'answer' => $answer,
                        'a_datetime' => DB::raw('now()'),
                        'status' => 1,
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                
                } catch(Exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
            break;
        }
  
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $delete = DB::table('store_qna')->where('id',$id)->delete();

        if($delete){
            $this->res['query'] = $delete;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        }else{
            $this->res['query'] = $delete;
            $this->res['msg'] = "실패";
            $this->res['state'] = config('res_code.QUERY_ERR');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
