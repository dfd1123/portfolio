<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

//use Illuminate\Support\Facades\Input;

class BannerController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
	 public function __construct(Request $request){
        parent::__construct($request);

        if( $this->JWTClaims ===null){
            
            $this->res['query'] =null;
            $this->res['state'] = config('rescode.NO_AUTH_100');
            $this->res['msg'] ='no-auth';
            die($this->res);
        }
    }
    public function index()
    {
        return 'API FOR Banner';
    }

    // 요청경로  GET - URL  : api/makers/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();

        $params = array();
        switch ($req) {
            case 'list':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }

                $sql = "SELECT banner_id
                ,banner_title
                ,banner_img
                ,created_at
                FROM banner
                ORDER BY banner_id DESC
                OFFSET :offset LIMIT 10;";

            break;
            case 'detail':
                if ($request->filled('banner_id') && $request->input('banner_id') >= 0) {
                    $params['banner_id'] = $p['banner_id'];
                } else { //prdc_id 없거나 0보다 작은경우
                    break;
                }

                $sql = "SELECT banner_id
                ,banner_title
                ,banner_img
                ,banner_content
                ,created_at
                FROM banner
                WHERE banner_id = :banner_id
                ORDER BY banner_id DESC
                OFFSET :offset LIMIT 10;";
            break;
        }
        $this->execute_query($sql, $params);
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        $p = $request->all();


        $params = array();
        if ($request->filled('req')) {
            switch ($p['req']) {
                    case 'reg':
                    
                    if (!$request->filled('banner_title', 'banner_content') 
                        || !$this->checkLength($p['banner_title'], 2,  64)
                        || !$this->checkLength($p['banner_content'], 2, 4000)
                    ) {
                        $this->res['msg']='입력오류';
                        $this->res['query']=null;
                        $this->res['state']=config('rescode.NO_PARAM_0');
                        break;
                    }
                    if ($this->checkFile('banner', $request)) {
                        if ($ext = $this->checkExtension('banner', $request, array('jpeg','png','jpg'))) {

                            $p['banner_img'] = basename($this->saveFile(
                                'banner',  //FORM 이름
                                $request,  //request 변수
                                config('filesystems.banner_thumb'),  //파일저장경로
                                $ext       //확장자
                            ));

                        }
                        $sql = "INSERT INTO banner(
                        banner_title
                        ,banner_content
                        ,banner_img)
                        VALUES(
                         :banner_title
                        ,:banner_content
                        ,:banner_img
                        )RETURNING banner_id;";

                        $params['banner_title']    = $p['banner_title'];
                        $params['banner_content']  = $p['banner_content'];
                        $params['banner_img']      = $p['banner_img'];
                        $this->execute_query($sql, $params);

                    }
                    
                    break;

                    case 'update':
                    
                    
                    break;
                }
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req='def')
    {
        $p=  $request->all();
        $params = array();
        switch ($req) {
            //내용수정
            default:
            case 'def':

            if (!$request->filled('banner_title', 'banner_content','banner_id')) {
                break;
            }

            $sql ="UPDATE banner 
            SET banner_title = :banner_title
            ,banner_content =  :banner_content
            WHERE banner_id = :banner_id
            RETURNING banner_id;";

            $params['banner_title'] = $p['banner_title'];
            $params['banner_content'] = $p['banner_content'];
            $params['banner_id'] = $p['banner_id'];
            $this->execute_query($sql, $params);


            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function destroy(Request $request, $req='def')
    { 

        $p=  $request->all();
        $params = array();
        switch ($req) {
            //내용수정
            default:
            case 'def':

                if (!$request->filled('banner_id')) {
                    break;
                }

                $sql ="DELETE FROM banner
                WHERE banner_id = :banner_id
                RETURNING banner_img;";

                $params['banner_id'] = $p['banner_id'];
                $res = $this->execute_query($sql, $params);

                //정상삭제
                if( count($res['query']) >0){
                    File::delete( config('filesystems.banner_thumb').$res['query'][0]->banner_img);
                }else{
                    //삭제오류
                    //로그남기기
                }


            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
