<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class QnaController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Qna controller';
    }

    public function index()
    {
        return 'QNA FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $limit = $request->filled('limit')?$request->limit:20;
                $offset = $request->filled('offset')?$request->offset:0;
                $qnas = DB::table('qna')
                ->join('users','users.id','=','qna.q_id')
                ->select('qna.*', 'users.nickname', 'users.profile_img')
                ->where('type',0)
                ->where('q_id', $uid)
                ->limit($limit)
                ->offset($offset)
                ->orderBy('q_datetime','DESC')
                ->get();
                $count = DB::table('qna')->where('q_id', $uid)->count();

                $query = array(
                    "qnas" => $qnas,
                    "count" => $count
                );

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');

            break;

            case 'view':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if($request->filled('id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Qna id 값 안넘어옴";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $query = DB::table('qna')
                ->join('users','users.id','=','qna.q_id')
                ->select('qna.*', 'users.nickname', 'users.profile_img')
                ->where('id', $id)->first();
                if($query == null){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 id에 맞는 문의가 없음";
                    $this->res['state'] = config('res_code.NO_DATA');
                }else{
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
        
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if(!$request->filled('subject', 'question')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $uid = Auth::guard('api')->id();
        $user = Auth::guard('api')->user();
        $uid = $user->id;
        $q_email = $user->email;
        $q_name = $user->name;
        $q_hp = $user->mobile_number;
        $q_ip = $_SERVER["REMOTE_ADDR"];
        $subject = $request->subject;
        $question = $request->question;

        try {
            $insert = DB::table('qna')->insert([
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
                'type' => 0,
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
                    $update = DB::table('qna')->where('id',$id)->update([
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
                    $update = DB::table('qna')->where('id',$id)->update([
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

    public function destroy($id)
    {
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = "Auth 없음!";
            $this->res['state'] = config('res_code.NO_AUTH');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $delete = DB::table('qna')->where('id',$id)->delete();

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
