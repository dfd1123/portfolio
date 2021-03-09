<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Hash;

class ItemQnaController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'ItemQna controller';
    }

    public function index()
    {
        return 'ItemQna FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list_q':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $uid = Auth::guard('api')->id();
                $query = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->join('users','users.id','=','item_qna.q_id')
                ->select('item_qna.*', 'users.nickname', 'users.profile_img')
                ->where('items.delete_yn',0)
                ->where('q_id', $uid)->orderBy('q_datetime','DESC')->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;

            case 'list_a':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if($request->filled('orderby')){
                    if($request->orderby == 'desc'){
                        $orderby = $request->orderby;
                    }else{
                        $orderby = $request->orderby;
                    }
                }else{
                    $orderby = 'desc';
                }

                if($request->filled('search_select')){
                    $search_select = $request->search_select;
                }else{
                    $search_select = 'items.title';
                }

                if($request->filled('search_text')){
                    $search_text = '%'.$request->search_text.'%';
                }else{
                    $search_text = '%%';
                } 

                $uid = Auth::guard('api')->id();

                $query = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->join('users','users.id','=','item_qna.q_id')
                ->select('item_qna.*', 'users.nickname', 'users.profile_img')
                ->where('a_id', $uid)
                ->where($search_select, 'like', $search_text)
                ->where('items.delete_yn',0)
                ->orderBy('q_datetime',$orderby)
                ->get();
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;

            case 'item_qa':
                if(!$request->filled('item_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "item_id 값 없음! 잘못된 경로로 접근!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $page = ($request->filled('page'))?$request->page:1;
                $limit = ($request->filled('limit'))?$request->limit:5;
                
                $offset = ($page - 1) * $limit;
                                
                $item_id = $request->item_id;

                $count = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->join('users','users.id','=','item_qna.q_id')
                ->where('items.delete_yn',0)
                ->where('items.item_id', $item_id)
                ->count();

                $item_qna = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->join('users','users.id','=','item_qna.q_id')
                ->select('item_qna.*', 'users.nickname', 'users.profile_img')
                ->where('items.delete_yn',0)
                ->where('items.item_id', $item_id)
                ->orderBy('q_datetime','DESC')
                ->offset($offset)->limit($limit)
                ->get();
                
                $response = array();
                $response['item_qna'] = $item_qna;
                $response['count'] = $count;
                $response['page'] = $page;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'view':
                if(!$request->filled('id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Qna id 값 안넘어옴";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $query = DB::table('item_qna')
                ->join('items','items.item_id','=','item_qna.item_id')
                ->join('users','users.id','=','item_qna.q_id')
                ->select('item_qna.*', 'users.nickname', 'users.profile_img')
                ->where('items.delete_yn',0)
                ->where('item_qna.id', $id)
                ->first();

                if($query->secret == 1){
                    if(!Hash::check($request->password, $query->password, [])){
                        $this->res['query'] = null;
                        $this->res['msg'] = "문의 비밀번호 틀림!";
                        $this->res['state'] = config('res_code.PWD_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
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

        if(!$request->filled('subject', 'question','item_id')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $item_id = $request->item_id;
        $user = Auth::guard('api')->user();
        $uid = $user->id;
        $q_email = $user->email;
        $q_name = $user->nickname;
        $q_hp = $user->mobile_number;
        $q_ip = $_SERVER["REMOTE_ADDR"];
        $subject = $request->subject;
        $question = $request->question;

        if($request->secret == 1){
            $secret = 1;
            $password = bcrypt($request->password);
        }else{
            $secret = 0;
            $password = null;
        }

        try {
            $insertId = DB::table('item_qna')->insertGetId([
                'item_id' => $item_id,
                'q_id' => $uid,
                'q_email' => $q_email,
                'q_name' => $q_name,
                'q_hp' => $q_hp,
                'q_ip' => $q_ip,
                'subject' => $subject,
                'question' => $question,
                'q_datetime' => DB::raw('now()'),
                'status' => 0,
                'secret' => $secret,
                'password' => $password,
            ]);
            $this->res['query'] = $insertId;
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
            case 'answer':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
        
                if(!$request->filled('answer')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $id = $request->id;
                $uid = Auth::guard('api')->id();
                $a_email = Auth::guard('api')->user()->email;
                $a_name = Auth::guard('api')->user()->name;
                $a_hp = Auth::guard('api')->user()->mobile_number;
                $a_ip = $_SERVER["REMOTE_ADDR"];
                $answer = $request->answer;
        
                try { 
                    $update = DB::table('item_qna')->where('id',$id)->update([
                        'a_email' => $a_email,
                        'a_name' => $a_name,
                        'a_hp' => $a_hp,
                        'a_ip' => $a_ip,
                        'answer' => $answer,
                        'a_datetime' => DB::raw('now()'),
                        'status' => 1,
                    ]);
                    $this->res['query'] = $id;
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

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
