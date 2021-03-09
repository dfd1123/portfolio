<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }
    }

    public function __invoke($id)
    {
        return 'Product controller';
    }

    public function index()
    {
        return 'API FOR PRODUCT';
    }

    // 요청경로  GET - URL  : api/product/{$req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            //권한체크 - 유저로그인

            //추천상품
            case 'list_recommand':
                $params = array();
                
                $sql = "SELECT 
                            prd_id,
                            prd_thumb,
                            prd_title,
                            prd_subtitle,
                            (SELECT avg(revw_score) FROM review WHERE prd_id = product.prd_id AND state = 0) as prd_score,
                            (SELECT count(revw_id) FROM review WHERE prd_id = product.prd_id AND state = 0) as prd_count                                    
                        FROM  product
                        WHERE prd_is_recmd = 1 AND state = 0
                        ORDER BY RANDOM();";

                $this->res = $this->execute_query($sql, $params);

            break;

            

            case 'list_planner':
                $params = array();
                
                $params['pln_id'] = $this->decode_res['uid'];
                if ($request->filled('pln_id')) {
					$params['pln_id'] = $request->pln_id;
                }

                $params['offset'] = 0;
                if($request->filled('offset') && $this->checkRange($p['offset'], 0, 2100000000)){
                    $params['offset'] = $p['offset'];
                }

                $sql = "SELECT 
                            prd_id,
                            prd_thumb,
                            prd_title,
                            prd_subtitle,
                            COALESCE((SELECT avg(revw_score) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_score,
                            COALESCE((SELECT count(revw_id) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_count                                    
                        FROM  product
                        WHERE pln_id = :pln_id AND state = 0
                        ORDER BY prd_id DESC
                        OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $params);

            break;

            case 'detail':
            $params = array();

            if (!$request->filled('prd_id')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
                break;
            }

            $params['prd_id'] = $p['prd_id'];
            $sql = "SELECT 
                        prd_id,
                        pln_id,
                        prd_slides,
                        prd_title,
                        prd_subtitle,
                        prd_desc,
                        prd_course,
                        prd_schedule,
                        prd_manual,
                        COALESCE((SELECT avg(revw_score) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_score,
                        COALESCE((SELECT count(revw_id) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_count
                    FROM  product
                    WHERE prd_id = :prd_id AND state = 0";

            $this->res = $this->execute_query($sql, $params);
            break;

        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/product
    public function store(Request $request)
    {
        $p = $request->all();
        if ($request->filled('prd_title','prd_subtitle','prd_desc','prd_course','prd_schedule','prd_place_time','prd_manual') && $request->hasFile('prd_slides')) 
        {
            $i = 0;
            $thumb_arr = array();
            $ext_err = false;
            $file_valid_err = false;
            if (count($request->prd_slides) < 9) {
                foreach ($request->file('prd_slides') as $prd_slide) {
                    if (!$prd_slide->isvalid()) {
                        $file_valid_err = true;
                        break;
                    }
                    $allowExts = array('jpeg','png','jpg');
                    $sysExtension = $prd_slide->extension();
                    $extension = $prd_slide->getClientOriginalExtension();
                    $check_ext = false;
                    foreach ($allowExts as $ext) {
                        if ($sysExtension == $ext) {
                            $check_ext = true;
                            break;
                        }
                    }
                    if ($check_ext) {
                        $thumb_arr['path'][$i] = Str::uuid()->toString().".".$sysExtension;
                        $thumb_arr['sort'][$i] = $i;
                        $prd_slide->storeAs('', config('filesystems.product_slides').$thumb_arr['path'][$i]);
                    } else {
                        $ext_err = true;
                        break;
                    }
                    $i++;
                }
                if($ext_err || $file_valid_err){
                    if($file_valid_err){
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '파일 유효성 틀림 - CODE : TYPE 202';
                    }else{
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.EXT_ERR');
                        $this->res['msg'] = '확장자 틀림 - CODE : TYPE 206';
                    }
                }else{
                    $sql = 'INSERT INTO 
                                product(
                                    pln_id,
                                    prd_title,
                                    prd_subtitle,
                                    prd_desc,
                                    prd_course,
                                    prd_schedule,
                                    prd_place_time,
                                    prd_manual,
                                    prd_thumb, 
                                    prd_slides,
                                    created_at,
                                    updated_at
                                )
                            VALUES (
                                :pln_id,
                                :prd_title, 
                                :prd_subtitle, 
                                :prd_desc, 
                                :prd_course, 
                                :prd_schedule, 
                                :prd_place_time, 
                                :prd_manual, 
                                :prd_thumb, 
                                :prd_slides,
                                now(),
                                now()
                                ) RETURNING prd_id;';
                    $params = array(
                        'pln_id' => $this->decode_res['uid'], //auth()->user id 로 바꿔야됨
                        'prd_title' => $p['prd_title'],
                        'prd_subtitle' => $p['prd_subtitle'],
                        'prd_desc' => $p['prd_desc'],
                        'prd_course' => $p['prd_course'],
                        'prd_schedule' => $p['prd_schedule'],
                        'prd_place_time' => $p['prd_place_time'],
                        'prd_manual' => $p['prd_manual'],
                        'prd_thumb' => $thumb_arr['path'][0],
                        'prd_slides' => json_encode($thumb_arr)
                    );

                    $this->execute_query($sql, $params);

                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->prd_id > 0) {
                    } else { 
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.NO_DATA');
                        $this->res['msg'] = '쿼리응답에러';
                    }
                }
            }else{
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '파일 개수 많음 - CODE : TYPE 265';
            }
        }
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 271';
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    // 요청경로  PUT - URL  : api/product/{$req}
    public function update(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //관리자만 수정가능
            //테마 제목 수정
            case 'update':
            if ($request->filled('prd_id','prd_title', 'prd_subtitle', 'prd_desc', 'prd_course', 'prd_schedule', 'prd_place_time', 'prd_manual')) {
                $imgClause = "";
                if ($request->hasFile('prd_slides')) {
                    $delete_img_sql = "SELECT prd_slides FROM product WHERE prd_id = :prd_id";
                    $product = DB::select($delete_img_sql, array( 'prd_id'=>$p['prd_id']));
                    if (json_decode($product[0]->prd_slides, true) != []) {
                        foreach (json_decode($product[0]->prd_slides)->path as $item) {
                            if (Storage::exists(config('filesystems.product_slides').$item)) {
                                Storage::delete(config('filesystems.product_slides').$item);
                            }
                        }
                    }
                    $i = 0;
                    $thumb_arr = array();
                    $ext_err = false;
                    $file_valid_err = false;
                    if (count($request->prd_slides) < 9) {
                        foreach ($request->file('prd_slides') as $prd_slide) {
                            if (!$prd_slide->isvalid()) {
                                $file_valid_err = true;
                                break;
                            }
                            
                            $allowExts = array('jpeg','png','jpg');
                            $sysExtension = $prd_slide->extension();
                            $extension = $prd_slide->getClientOriginalExtension();
                            $check_ext = false;
                            foreach ($allowExts as $ext) {
                                if ($sysExtension == $ext) {
                                    $check_ext = true;
                                    break;
                                }
                            }
                            if ($check_ext) {
                                $thumb_arr['path'][$i] = Str::uuid()->toString().".".$sysExtension;
                                $thumb_arr['sort'][$i] = $i;
                                $prd_slide->storeAs('', config('filesystems.product_slides').$thumb_arr['path'][$i]);
                            } else {
                                $ext_err = true;
                                break;
                            }
                            
                            $i++;
                        }
                    }
                }else{
                    $ext_err = false;
                    $file_valid_err = false;
                    $thumb_arr['path'][0] = '';
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
                    $sql = "UPDATE 
                                product
                            SET 
                                prd_title = :prd_title,
                                prd_subtitle = :prd_subtitle,
                                prd_desc = :prd_desc,
                                prd_course = :prd_course,
                                prd_schedule = :prd_schedule,
                                prd_place_time = :prd_place_time,
                                prd_manual = :prd_manual,
                                prd_thumb = :prd_thumb,
                                prd_slides = :prd_slides,
                                updated_at = now()
                            WHERE 
                                pln_id = :pln_id AND
                                prd_id = :prd_id;";

                    $params = array(
                        'prd_title'=>$p['prd_title'], 
                        'prd_subtitle'=>$p['prd_subtitle'], 
                        'prd_desc'=>$p['prd_desc'], 
                        'prd_course'=>$p['prd_course'], 
                        'prd_schedule'=>$p['prd_schedule'], 
                        'prd_place_time'=>$p['prd_place_time'], 
                        'prd_manual'=>$p['prd_manual'], 
                        'prd_thumb'=>$thumb_arr['path'][0], 
                        'prd_slides'=>json_encode($thumb_arr), 
                        'pln_id'=>$this->decode_res['uid'], 
                        'prd_id'=>$p['prd_id']
                    );

                    $this->execute_query($sql, $params, 'update');
                }
            }else{
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
            }
            break;

            case 'state':
                if (!$request->filled('prd_id','state')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $sql = "UPDATE product
                SET state = :state
                WHERE prd_id = :prd_id AND pln_id = :pln_id;";

                $params = array('prd_id'=>$p['prd_id'] , 'state'=>$p['state'], 'pln_id'=>$this->decode_res['uid']);

                $this->execute_query($sql, $params, 'update');
            break;

            case 'recommand':
                if (!$request->filled('prd_id','prd_is_recmd')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }

                $sql = "UPDATE product
                SET prd_is_recmd = :prd_is_recmd
                WHERE prd_id = :prd_id;";

                $params = array('prd_id'=>$p['prd_id'], 'prd_is_recmd'=>$p['prd_is_recmd']);

                $this->execute_query($sql, $params, 'update');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  DELETE - URL  : api/product
    public function destroy(Request $request, $req)
    {

    }
}
