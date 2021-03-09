<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;
use File;

class EventController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function __invoke($id)
    {
        return 'Event controller';
    }

    public function index()
    {
        return 'Event FOR API';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $page_size = $request->filled('page_size')?$request->page_size:15;
                $page = $request->filled('page')?$request->page:1;

                if($request->filled('offset')){
                    $offset = $request->offset;
                }else{
                    $offset = ($page - 1)*$page_size;
                }

                $count = DB::table('event')->count();
                $events = DB::table('event')->limit($page_size)->offset($offset)->orderBy('id','DESC')->get();

                $query = array(
                    "count" => $count,
                    "page" => $page,
                    "events" => $events,
                );
                
                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'show':
                $id = $request->id;
                $page = $request->page;
                $event = DB::table('event')->where('id', $id)->first();

                $query = array(
                    "page" => $page,
                    "event" => $event,
                );

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if(!$request->filled('title', 'body')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if($request->hasFile('file1')){
            $file1 = File_store::File_store('event', $request->file1);
            if($file1 == 'SIZE_ERR'){  //사이즈 에러
                $this->res['query'] =null;
                $this->res['msg'] = "최대 파일 사이즈 초과 에러! (최대 40MB)"; 
                $this->res['state'] = config('res_code.SIZE_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($file1 == 'EXT_ERR'){
                $this->res['query'] =null;
                $this->res['msg'] = "확장자 에러!"; 
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($file1 == 'VALID_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "파일 유효성 에러!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($file1 == 'PARAM_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "첨부한 파일 없음!";
                $this->res['state'] = config('res_code.PARAM_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        }else{
            $file1 = array();
        }

        $title = $request->title;
        $body = $request->body;

        try {
            $insert = DB::table('event')->insert([
                'title' => $title,
                'body' => $body,
                'file' => json_encode($file1),
                'hit' => 0,
                'like' => 0,
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
                if(!$request->filled('title', 'body','id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $event_id = $request->id;
                $query = DB::table('event')->where('id', $event_id)->first();

                if($request->hasFile('file1')){
                    $file1 = File_store::File_update('event', $request->file1, json_decode($query->file), $request->index);
                    if($file1 == 'SIZE_ERR'){  //사이즈 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "최대 파일 사이즈 초과 에러! (최대 40MB)"; 
                        $this->res['state'] = config('res_code.SIZE_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($file1 == 'EXT_ERR'){
                        $this->res['query'] =null;
                        $this->res['msg'] = "확장자 에러!"; 
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($file1 == 'VALID_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "파일 유효성 에러!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($file1 == 'PARAM_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "첨부한 파일 없음!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                    
                }else{
                    $file1 = json_decode($query->file1);
                }
        
                $title = $request->title;
                $body = $request->body;
        
                try {
                    $update = DB::table('event')->where('id',$event_id)->update([
                        'title' => $title,
                        'body' => $body,
                        'file' => json_encode($file1),
                        'updated_at' => DB::raw('now()'),
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

    //사용고민중.
    public function destroy(Request $request)
    {

    }
}
