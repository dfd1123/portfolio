<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;

class ReviewCommentController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __construct(){
        //$this->middleware('auth:api', ['except' => ['show']]); 
        //dd('dd');
    }

    public function __invoke($id)
    {
        return 'Review controller';
    }

    public function index()
    {
        return 'Review FOR STYLE';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                if(!$request->filled('review_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "review_id 값 없음! 잘못된 경로로 접근!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $offset = ($request->filled('offset'))?$request->offset:0;
                $limit = ($request->filled('limit'))?$request->limit:10;
                
                
                
                $review_id = $request->review_id;

                $count = DB::table('review_comment')
                ->where('review_id', $review_id)
                ->where('deleted',0)
                ->join('users', 'review_comment.writer_id', '=', 'users.id')
                ->join('items', 'items.item_id','=','review_comment.item_id')
                ->orderBy('created_at','DESC')
                ->count();
                    
                $review_comments = DB::table('review_comment')
                ->where('review_id', $review_id)
                ->where('deleted',0)
                ->join('users', 'review_comment.writer_id', '=', 'users.id')
                ->join('items', 'items.item_id','=','review_comment.item_id')
                ->select(
                    "review_comment.*",
                    DB::raw("(CASE WHEN items.seller_id = review_comment.writer_id THEN items.company_name ELSE users.nickname END) AS name"),
                    DB::raw("(CASE WHEN items.seller_id = review_comment.writer_id THEN items.company_profile_img ELSE users.profile_img END) AS user_image")
                )
                ->orderBy('created_at','DESC')
                ->offset($offset)->limit($limit)
                ->get();
                
                $response = array();
                $response['review_comments'] = $review_comments;
                $response['count'] = $count;

                $this->res['query'] = $response;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {

        if(!$request->filled('review_id','item_id', 'review_body')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $user = Auth::guard('api')->user();
        
        $review_id = $request->review_id;
        $writer_id = $user->id;
        $writer_name = $user->name;
        $writer_ip = $_SERVER["REMOTE_ADDR"];
        $unickname = NULL;
        $profile_img = NULL;
        $review_title = $request->review_title;
        $review_body = $request->review_body;
        $item_id = $request->item_id;
        
        try {
            $insert = DB::table('review_comment')->insert([
                'review_id' => $review_id,
                'item_id' => $item_id,
                'writer_id' => $writer_id,
                'writer_name' => $writer_name,
                'profile_img' => $profile_img,
                'unickname' => $unickname,
                'review_title' => $review_title,
                'review_body' => $review_body,
                'writer_ip' => $writer_ip,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
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
            case 'update':
                if(!$request->filled('comment_id', 'review_body')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $comment_id = $request->comment_id;
                $review_title = $request->review_title;
                $review_body = $request->review_body;

                try {
                    $insert = DB::table('review_comment')->where('id',$comment_id)->where('writer_id',$uid)->update([
                        'review_title' => $review_title,
                        'review_body' => $review_body,
                        'updated_at' => DB::raw('now()'),
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
            break;

            case 'delete':
                if(!$request->filled('comment_id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $comment_id = $request->comment_id;
                
                $delete = DB::table('review_comment')->where('id',$comment_id)->where('writer_id',$uid)->update([
                    "deleted" => 1,
                ]);
                
        
                if($delete == 0){
                    $this->res['query'] = $delete;
                    $this->res['msg'] = "권한없거나 존재하지 않는 ID";
                    $this->res['state'] = config('res_code.NO_DATA');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $this->res['query'] = $delete;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;
        }

  
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request, $req)
    {
        


        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
