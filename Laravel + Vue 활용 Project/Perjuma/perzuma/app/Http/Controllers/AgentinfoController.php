<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as InterventionImage;

class AgentinfoController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'abcd';
    }

    public function index()
    {
        return 'User API
        LIST : ~ 
        wjrjf : ~';
    }
    public function show(Request $request, $req)
    {
        $p = $request->all();

        //권한체크 - 최고관리자만 이하코드 실행가능

        switch ($req) {
            case 'list':
            
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    $p['offset'] =0;
                }

                $sql =   "SELECT agent_no
                ,agent_name
                ,agent_addr
                ,agent_contact
                ,agent_construction_cnt
                ,agent_review_cnt
                ,agent_profile_img
                ,agent_rating
                ,agent_tel_number
                ,state
                ,created_at::date
                FROM  agent_info
                ORDER BY agent_no DESC
                OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $p, 'select');

            break;
         default:
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 0';
         break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function store(Request $request)
    {
        $p = $request->all();
        $p['agent_no'] = auth()->user()->user_no;
        if ($request->filled('step') && (int)$p['step'] > 0) {
            if($p['step'] == 1){
                if ($request->filled('agent_name','agent_contact', 'agent_addr','agent_detailaddr') 
                && strlen($p['agent_name']) >0
                && strlen($p['agent_contact']) >0
                && strlen($p['agent_addr']) >0 && strlen($p['agent_addr']) <=64
                && strlen($p['agent_detailaddr']) >0 && strlen($p['agent_detailaddr']) <=64
                ) {
                    $imgarray = array();
                    if ($request->hasFile('business_paper_img') && $request->file('business_paper_img')->isValid()) {
                        $file = $request->file('business_paper_img');
                        $img = InterventionImage::make($file)->orientate();
                        if ($img->width() >= 1000) {
                            $img->resize(700, null, function ($constraint) {
                                $constraint->aspectRatio(); //비율유지
                            })->encode('jpg');
                        } else {
                            $img->encode('jpg');
                        }
                        $hash = '/'.md5($img->__toString(). time());
                        $file_name = $hash.'.jpg';
                        $path = "../storage/app/".config('filesystems.agent_business').$hash.".jpg";
                        $img->save($path);
                        $imgarray['business_paper_img'] = $file_name;
                    }
                    if ($request->hasFile('profile_img') && $request->file('profile_img')->isValid()) {
                        $file = $request->file('profile_img');
                        $img = InterventionImage::make($file)->orientate();
                        if ($img->width() >= 1000) {
                            $img->resize(700, null, function ($constraint) {
                                $constraint->aspectRatio(); //비율유지
                            })->encode('jpg');
                        } else {
                            $img->encode('jpg');
                        }
                        $hash = '/'.md5($img->__toString(). time());
                        $file_name = $hash.'.jpg';
                        $path = "../storage/app/".config('filesystems.agent_thumb').$hash.".jpg";
                        $img->save($path);
                        $imgarray['profile_img'] = $file_name;
                    }
                    $sql = 'INSERT INTO agent_info
                    (
                        agent_no
                        ,agent_name
                        ,agent_contact
                        ,agent_addr
                        ,agent_detailaddr
                        ,agent_profile_img
                        ,extra_info
                        ,agent_tel_number
                    )
                    VALUES (
                        :agent_no
                        ,:agent_name
                        ,:agent_contact
                        ,:agent_addr
                        ,:agent_detailaddr
                        ,:agent_profile_img
                        ,:extra_info
                        ,:agent_tel_number
                        )
                    RETURNING agent_no ;';
    
                    $param = array(
                        'agent_no' => $p['agent_no']
                        ,'agent_name' => $p['agent_name']
                        ,'agent_contact' => $p['agent_contact']
                        ,'agent_addr' => $p['agent_addr']
                        ,'agent_detailaddr' => $p['agent_detailaddr']
                        ,'agent_profile_img' => json_encode($imgarray)
                        ,'extra_info' =>$p['extra_info']
                        ,'agent_tel_number'=>$p['agent_tel_number']
                    );

                    $this->execute_query($sql, $param, 'select');

                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->agent_no > 0) {
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1 ';
                }
            }
            elseif($p['step']==2){
                if ($request->filled('agent_bl','bl_name') 
                && ((int)$p['agent_no']) >0
                ) {
                    $blarray = array();
                    for($i = 0; $i < count($p['agent_bl']);$i++){
                        array_push($blarray, $p['agent_bl'][$i]);
                    }
                    $bllist = array('agent_bl' =>$blarray ,'bl_name'=>$p['bl_name']);
                    $sql = 'UPDATE
                    agent_info
                SET
                    extra_info = subquery.udt_extra
                FROM
                    (
                        SELECT
                            extra_info || :extra_info AS udt_extra
                        FROM
                            agent_info
                        WHERE
                            agent_no = :agent_no
                    ) AS subquery
                WHERE agent_info.agent_no = :agent_no
                    RETURNING agent_no;';
    
                    $param = array(
                        'agent_no' => $p['agent_no']
                        ,'extra_info' => json_encode($bllist)
                    );
                    
                    $this->execute_query($sql, $param, 'select');
                    
                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->agent_no > 0) {
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1 ';
                }
            }
            elseif($p['step']==3){
                if ($request->filled('agent_distance') 
                && ((int)$p['agent_no']) >0
                && strlen($p['agent_distance']) >0
                ) {
                    $sql = 'UPDATE
                    agent_info
                SET
                    extra_info = subquery.udt_extra
                FROM
                    (
                        SELECT
                            extra_info || :extra_info AS udt_extra
                        FROM
                            agent_info
                        WHERE
                            agent_no = :agent_no
                    ) AS subquery
                WHERE agent_info.agent_no = :agent_no
                    RETURNING agent_no;';
    
                    $param = array(
                        'agent_no' => $p['agent_no']
                        ,'extra_info' => json_encode(array('agent_distance'=>$p['agent_distance']))
                    );
                    $this->execute_query($sql, $param, 'select');
                    
                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->agent_no > 0) {
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1 ';
                }
            }
            elseif($p['step']==4){
                if ($request->filled('agent_career') 
                && ((int)$p['agent_no']) >0
                && strlen($p['agent_career']) >0
                ) {
                    $sql = 'UPDATE
                    agent_info
                SET
                    extra_info = subquery.udt_extra
                FROM
                    (
                        SELECT
                            extra_info || :extra_info AS udt_extra
                        FROM
                            agent_info
                        WHERE
                            agent_no = :agent_no
                    ) AS subquery
                WHERE agent_info.agent_no = :agent_no
                    RETURNING agent_no;';
    
                    $param = array(
                        'agent_no' => $p['agent_no']
                        ,'extra_info' => json_encode(array('agent_career'=>$p['agent_career']))
                    );
                    $this->execute_query($sql, $param, 'select');
                    
                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->agent_no > 0) {
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1 ';
                }
                
            }
            elseif($p['step']==5){
                if (((int)$p['agent_no']) >0
                ) {
                    $imgarray = array();
                    
                    for($i = 0; $i <5; $i++){
                        if ($request->hasFile('construction_img'.$i) && $request->file('construction_img'.$i)->isValid()) {
                            $file = $request->file('construction_img'.$i);
                            $img = InterventionImage::make($file)->orientate();
                            if ($img->width() >= 1000) {
                                $img->resize(700, null, function ($constraint) {
                                    $constraint->aspectRatio(); //비율유지
                                })->encode('jpg');
                            } else {
                                $img->encode('jpg');
                            }
                            $hash = '/'.md5($img->__toString(). time());
                            $file_name = $hash.'.jpg';
                            $path = "../storage/app/".config('filesystems.agent_construct_popol').$hash.".jpg";
                            $img->save($path);
                            $imgarray['construction_img'.$i] = $path;
                        }
                    }

                    $sql = 'UPDATE
                    agent_info
                SET
                    extra_info = subquery.udt_extra
                FROM
                    (
                        SELECT
                            extra_info || :extra_info AS udt_extra
                        FROM
                            agent_info
                        WHERE
                            agent_no = :agent_no
                    ) AS subquery
                WHERE agent_info.agent_no = :agent_no
                    RETURNING agent_no;';
    
                    $param = array(
                        'agent_no' => $p['agent_no']
                        ,'extra_info' => json_encode($imgarray)
                    );
                    $this->execute_query($sql, $param, 'select');
                    
                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->agent_no > 0) {
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1 ';
                }
            }
            elseif($p['step']==6){
                if (((int)$p['agent_no']) >0
                ) {
                    $imgarray = array();
                    //dd($request->all());
                    if ($request->hasFile('profile_img') && $request->file('profile_img')->isValid()) {
                        $file = $request->file('profile_img');
                        $img = InterventionImage::make($file)->orientate();
                        if ($img->width() >= 1000) {
                            $img->resize(700, null, function ($constraint) {
                                $constraint->aspectRatio(); //비율유지
                            })->encode('jpg');
                        } else {
                            $img->encode('jpg');
                        }
                        $hash = '/'.md5($img->__toString(). time());
                        $file_name = $hash.'.jpg';
                        $path = "../storage/app/".config('filesystems.agent_thumb').$hash.".jpg";
                        $img->save($path);
                        $imgarray['profile_img'] = $file_name;
                    }

                    $sql = 'UPDATE
                    agent_info
                SET
                agent_profile_img = subquery.udt_extra
                FROM
                    (
                        SELECT
                            agent_profile_img || :agent_profile_img AS udt_extra
                        FROM
                            agent_info
                        WHERE
                            agent_no = :agent_no
                    ) AS subquery
                WHERE agent_info.agent_no = :agent_no
                    RETURNING agent_no;';
    
                    $param = array(
                        'agent_no' => $p['agent_no']
                        ,'agent_profile_img' => json_encode($imgarray)
                    );
                    $this->execute_query($sql, $param, 'select');
                    
                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->agent_no > 0) {
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1 ';
                }
            }
            else{
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = 'Parameter step error';
            }
        }else{
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 0 ';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        $p = $request->all();
        $p['agent_no'] = auth()->user()->user_no;
        switch ($req) {
            //개인정보수정
            case 'normalinfo':
                //정보 전체 수정
                if (!$request->filled('user_no','name', 'user_contact','email','agent_name','agent_tel_number','agent_addr','agent_detailaddr','agent_contact')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql1 ="UPDATE
                users
            SET
                name = :name ,
                user_contact = :user_contact ,
                email = :email
            WHERE
                user_no = :user_no;";
                $param1 = array(
                    'name'=>$p['name']
                    ,'user_contact'=>$p['user_contact']
                    ,'email'=>$p['email']
                    ,'user_no'=>$p['user_no']
                );
                $this->execute_query($sql1, $param1, 'update');

                $sql2 = "UPDATE
                agent_info
            SET
                agent_name = :agent_name ,
                agent_tel_number = :agent_tel_number,
                agent_addr = :agent_addr,
                agent_detailaddr = :agent_detailaddr,
                agent_contact = :agent_contact
            WHERE
                agent_no = :user_no";
                $param2 = array(
                    'agent_name'=>$p['agent_name']
                    ,'agent_tel_number'=>$p['agent_tel_number']
                    ,'agent_addr'=>$p['agent_addr']
                    ,'agent_detailaddr'=>$p['agent_detailaddr']
                    ,'agent_contact'=>$p['agent_contact']
                    ,'user_no'=>$p['user_no']
                );
                $this->execute_query($sql2, $param2, 'update');

            break;
        }

        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    public function destroy(Request $request)
    {
        /* $p = $request->all();
            //관리자만 삭제 가능
                if (!$request->filled('sp_no')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $sql = "DELETE FROM manager WHERE sp_no = :sp_no;";
                $param = array('sp_no'=>$p['sp_no']);
              
                //정상실행일경우 state 1 query 1
                $this->execute_query($sql, $param, 'delete');


        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json'); */
    }
}
