<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Input;

class ReviewController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Review controller';
    }

    public function index()
    {
        return 'API FOR REVIEW';
    }

    // 요청경로  GET - URL  : api/review/{$req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            //권한체크 - 로그인한 유저에게만

            case 'product':
                $params = array();
                
                if ($request->filled('prd_id')) {
                    $params['prd_id'] = $p['prd_id'];
                }

                $params['offset'] = 0;
                if($request->filled('offset') && $this->checkRange($p['offset'], 0, 2100000000)){
                    $params['offset'] = $p['offset'];
                }

                //WHERE절 끝
                $sql = "SELECT 
                            revw.revw_id,
                            revw.prd_id,
                            revw.revw_content,
                            revw.user_id,
                            revw.created_at at time zone 'KST' AS created_at,
                            usr.name,
                            usr.user_thumb
                        FROM  review revw
                        INNER JOIN users usr 
                        ON revw.user_id = usr.id
                        WHERE revw.prd_id = :prd_id AND revw.state = 0
                        ORDER BY revw.created_at DESC
                        OFFSET :offset LIMIT 10;
                "; 

                $this->res = $this->execute_query($sql, $params);

            break;

            case 'planner':

                $params = array();
                $params['pln_id'] = $this->decode_res['uid'];
                if ($request->filled('pln_id')) {
                    $params['pln_id'] = $p['pln_id'];
                }

                $params['offset'] = 0;
                if($request->filled('offset') && $this->checkRange($p['offset'], 0, 2100000000)){
                    $params['offset'] = $p['offset'];
                }

                if($request->filled('orderby')){
                    if($p['orderby'] == 'min_score'){
                        $OrderClause = 'TR.revw_score ASC';
                    }else if($p['orderby'] == 'max_score'){
                        $OrderClause = 'TR.revw_score DESC';
                    }else{
                        $OrderClause = 'TR.created_at DESC';
                    }
                }else{
                    $OrderClause = 'created_at DESC';
                }
                $sql = "SELECT TR.revw_id
                ,TR.estm_id
                ,TR.prd_id
                ,TR.revw_content
                ,TR.created_at at time zone 'KST' AS created_at
                ,TR.revw_img
                ,TR.revw_id
                ,TR.revw_score
                ,TR.user_id
                ,TU.user_thumb
                ,TU.name as user_nick
                FROM 
                review TR JOIN users TU
                ON TR.user_id = TU.id
                WHERE TR.pln_id = :pln_id
                ORDER BY $OrderClause
                OFFSET :offset LIMIT 10;"; 

                $this->res = $this->execute_query($sql, $params);

            break;

            case 'user':

                $params = array();
                $params['user_id'] = $this->decode_res['uid'];

                $params['offset'] = 0;
                if($request->filled('offset') && $this->checkRange($p['offset'], 0, 2100000000)){
                    $params['offset'] = $p['offset'];
                }

                $sql = "SELECT 
                            RV.revw_id, 
                            RV.revw_score, 
                            RV.revw_content, 
                            RV.created_at::timestamp(0),
                            USR.user_thumb,
                            USR.name
                        FROM review RV
                        INNER JOIN users USR
                        ON RV.user_id = USR.id
                        WHERE RV.user_id = :user_id
                        ORDER BY RV.created_at DESC
                        OFFSET :offset LIMIT 10;"; 

                $this->res = $this->execute_query($sql, $params);

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/review
    public function store(Request $request)
    {

        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }
        $p = $request->all();

        if ($request->filled('revw_score', 'revw_content')){
            if($request->filled('prd_id')){
                $sql = 'INSERT INTO 
                            review(
                                pln_id,
                                prd_id, 
                                revw_score, 
                                revw_content, 
                                state, 
                                created_at, 
                                user_id
                            )
                        VALUES (
                            :pln_id,
                            :prd_id,
                            :revw_score,
                            :revw_content,
                            0,
                            now(),
                            :user_id
                        )RETURNING revw_id;';

                $param = array(
                    'pln_id' => $p['pln_id'],
                    'prd_id' => $p['prd_id'],
                    'revw_score' => $p['revw_score'],
                    'revw_content' => $p['revw_content'],
                    'user_id' => $this->decode_res['uid']
                );

                $this->execute_query($sql, $param);

                //정상적으로 실행된 경우
                if (count($this->res['query']) >0 &&  $this->res['query'][0]->revw_id > 0) {
                    $sql_is_revw = "UPDATE reserve SET is_revw = 1 WHERE rsrv_id = :rsrv_id";
                    DB::update($sql_is_revw,array('rsrv_id'=>$p['rsrv_id']));
                } else { 
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.NO_DATA');
                    $this->res['msg'] = '쿼리응답에러';
                }
            }else if($request->filled('estm_id')){
                $json_array = array();
                $i = 0;
                $revw_arr = array();
                $ext_err = false;
                $file_valid_err = false;
                if ($request->hasFile('revw_imges')) {
                    foreach ($request->file('revw_imges') as $revw_img) {
                        if (!$revw_img->isvalid()) {
                            $file_valid_err = true;
                            break;
                        }
                        $allowExts = array('jpeg','png','jpg');
                        $sysExtension = $revw_img->extension();
                        $extension = $revw_img->getClientOriginalExtension();
                        $check_ext = false;
                        foreach ($allowExts as $ext) {
                            if ($sysExtension == $ext) {
                                $check_ext = true;
                                break;
                            }
                        }
                        if ($check_ext) {
                            //파일 삽입
                            $revw_arr['path'] = Str::uuid()->toString().".".$sysExtension;
                            array_push($json_array,$revw_arr);
                            $revw_img->storeAs('', config('filesystems.review_planner').$revw_arr['path']);
                        } else {
                            $ext_err = true;
                            break;
                        }
                        $i++;
                    }
                }else{
                    $ext_err = false;
                    $file_valid_err = false;
                    $revw_arr['path'] = '';
                }
                
                if($ext_err || $file_valid_err){
                    if($file_valid_err){
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '파일 유효성 틀림 - CODE : TYPE 277';
                    }else{
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.EXT_ERR');
                        $this->res['msg'] = '확장자 틀림 - CODE : TYPE 277';
                    }
                }else{
                    $sql = 'INSERT INTO 
                                review(
                                    pln_id,
                                    estm_id,
                                    revw_score, 
                                    revw_content,
                                    revw_img,
                                    state, 
                                    created_at, 
                                    user_id
                                )
                            VALUES (
                                :pln_id,
                                :estm_id,
                                :revw_score,
                                :revw_content,
                                :revw_img,
                                0,
                                now(),
                                :user_id
                            )RETURNING revw_id;';

                    $param = array(
                        'pln_id' => $p['pln_id'],
                        'estm_id' => $p['estm_id'],
                        'revw_score' => $p['revw_score'],
                        'revw_content' => $p['revw_content'],
                        'revw_img' => json_encode($json_array),
                        'user_id' => $this->decode_res['uid']
                    );

                    $this->execute_query($sql, $param);

                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->revw_id > 0) {
                        $sql_is_revw = "UPDATE reserve SET is_revw = 1 WHERE rsrv_id = :rsrv_id";
                        DB::update($sql_is_revw, array('rsrv_id'=>$p['rsrv_id']));
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }
            }else{
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
            }    
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    // 요청경로  PUT - URL  : api/review/{$req}
    public function update(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            case 'product':
                if (!$request->filled('revw_id','revw_score','revw_content')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $sql = "UPDATE review
                        SET revw_score = :revw_score, revw_content = :revw_content
                        WHERE revw_id = :revw_id and user_id = :user_id;";

                $params = array(
                    'revw_score'=>$p['revw_score'], 
                    'revw_content'=>$p['revw_content'],
                    'revw_id'=>$p['revw_id'],
                    'user_id'=>$p['user_id']
                );

                $this->execute_query($sql, $params, 'update');
            break;

            case 'planner':
                if (!$request->filled('revw_id','revw_score','revw_content')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $json_array = array();
                $i = 0;
                $revw_arr = array();
                $ext_err = false;
                $file_valid_err = false;
                if ($request->hasFile('revw_imges')) {
                    foreach ($request->file('revw_imges') as $revw_img) {
                        if (!$revw_img->isvalid()) {
                            $file_valid_err = true;
                            break;
                        }
                        $allowExts = array('jpeg','png','jpg');
                        $sysExtension = $revw_img->extension();
                        $extension = $revw_img->getClientOriginalExtension();
                        $check_ext = false;
                        foreach ($allowExts as $ext) {
                            if ($sysExtension == $ext) {
                                $check_ext = true;
                                break;
                            }
                        }
                        if ($check_ext) {
                            //파일 삽입
                            $revw_arr['path'] = Str::uuid()->toString().".".$sysExtension;
                            array_push($json_array,$revw_arr);
                            $revw_img->storeAs('', config('filesystems.review_planner').$revw_arr['path']);
                        } else {
                            $ext_err = true;
                            break;
                        }
                        $i++;
                    }
                }else{
                    $ext_err = false;
                    $file_valid_err = false;
                    $revw_arr['path'] = '';
                }
                if($ext_err || $file_valid_err){
                    if($file_valid_err){
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '파일 유효성 틀림 - CODE : TYPE 277';
                    }else{
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.EXT_ERR');
                        $this->res['msg'] = '확장자 틀림 - CODE : TYPE 277';
                    }
                }else{
                    $sql = "SELECT revw_img 
                            FROM review 
                            WHERE revw_id = :revw_id AND user_id = :user_id";
                    $params = array(
                        'revw_id'=>$p['revw_id'],
                        'user_id'=>$p['user_id']
                    );
                    $this->execute_query($sql, $params, 'select');        

                    $deleted_files = json_decode($this->res['query'][0]->revw_img);
                    
                    foreach($deleted_files as $delete_file){
                        echo $delete_file->path."\n";
                        if(Storage::exists(config('filesystems.review_planner').$delete_file->path)){
                            Storage::delete(config('filesystems.review_planner').$delete_file->path);
                        }
                    }

                    $sql = "UPDATE review
                            SET 
                                revw_score = :revw_score, 
                                revw_content = :revw_content, 
                                revw_img = :revw_img
                            WHERE revw_id = :revw_id AND user_id = :user_id";

                    $params = array(
                        'revw_score'=>$p['revw_score'],
                        'revw_content'=>$p['revw_content'],
                        'revw_img'=>json_encode($json_array),
                        'revw_id'=>$p['revw_id'],
                        'user_id'=>$p['user_id']
                    );

                    $this->execute_query($sql, $params, 'update');

                    
                }
            break;

            case 'state':
                if (!$request->filled('revw_id')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $sql = "UPDATE review
                        SET state = 1
                        WHERE revw_id = :revw_id and user_id = :user_id;";

                $params = array(
                    'revw_id'=>$p['revw_id'],
                    'user_id'=>$p['user_id']
                );

                $this->execute_query($sql, $params, 'delete');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  DELETE - URL  : api/review/{$req}
    public function destroy(Request $request, $req)
    {

    }
}
