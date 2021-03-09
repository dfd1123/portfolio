<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

//use Illuminate\Support\Facades\Input;

class PortfolioController extends Controller
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
        return 'Portfolio controller';
    }

    public function index()
    {
        return 'API FOR PORTFOLIO';
    }

    // 요청경로  GET - URL  : api/portfolio/{$req}
    public function show(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            
            //권한체크 - 유저로그인

            //추천상품
            case 'list':
                $params = array();
                

                $params['pln_id'] = $this->decode_res['uid'];
                if ($request->filled('pln_id')) {
                    $params['pln_id'] = $request->input('pln_id');
                }

                $params['offset'] = 0;
                if($request->filled('offset') && $this->checkRange($p['offset'], 0, 2100000000)){
                    $params['offset'] = $p['offset'];
                }

                $sql = "SELECT  portf_id
	                        ,portf_file::JSONB AS protfo
                            ,portf_title
                        FROM  portfolio
                        WHERE pln_id = :pln_id
                        ORDER BY portf_id DESC
                        OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $params);

            break;

            case 'detail':
            $params = array();

            if (!$request->filled('portf_id')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
                break;
            }

            $params['portf_id'] = $p['portf_id'];
            $sql = "SELECT
                        portf_id,
                        portf_title,
                        portf_file                              
                    FROM  portfolio
                    WHERE portf_id = :portf_id";

            $this->res = $this->execute_query($sql, $params);
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/portfolio
    public function store(Request $request)
    {
        $p = $request->all();

        if ($request->filled('portf_title') && $request->hasFile('portf_files'))
        {
            $i = 0;
            $portf_arr = array();
            $ext_err = false;
            $file_valid_err = false;
            $json_array = array();

            $index_video = 0;
            $index_slides = 0;
            
            foreach ($request->file('portf_files') as $portf_file) {
                if (!$portf_file->isvalid()) {
                    $file_valid_err = true;
                    break;
                }
                // 확장자체크
                $allowExts = array('jpeg','png','jpg','mp4','avi');
                $sysExtension = $portf_file->extension();
                $extension = $portf_file->getClientOriginalExtension();
                $check_ext = false;
                
                foreach ($allowExts as $ext) {
                    if ($sysExtension == $ext) {
                        $check_ext = true;
                        break;
                    }
                }
                if ($check_ext) {
                    $portf_arr['path'] = Str::uuid()->toString().".".$sysExtension;
                    $portf_arr['desc'] = 'no-desc';
                    
                    array_push($json_array,$portf_arr);
                    $portf_file->storeAs('',config('filesystems.planner_portfolio').$portf_arr['path']);
                    
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
                    $this->res['msg'] = '파일 유효성 틀림 - CODE : TYPE 143';
                }else{
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.EXT_ERR');
                    $this->res['msg'] = '확장자 틀림 - CODE : TYPE 147';
                }
            }else{
                $sql = 'INSERT INTO 
                            portfolio(
                                pln_id,
                                portf_file,
                                portf_title,
                                created_at,
                                updated_at
                            )
                        VALUES (
                                :pln_id,
                                :portf_file,
                                :portf_title,
                                now(),
                                now()
                            ) RETURNING portf_id;';

                $params = array(
                    'pln_id' => $this->decode_res['uid'],
                    'portf_file' => json_encode($json_array),
                    'portf_title' => $p['portf_title']
                );

                $this->execute_query($sql, $params);

                //정상적으로 실행된 경우
                if (count($this->res['query']) >0 &&  $this->res['query'][0]->portf_id > 0) {
                } else { 
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.NO_DATA');
                    $this->res['msg'] = '쿼리응답에러';
                }
            }
            
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE 186';
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
            if ($request->filled('portf_id')) {
                $json_array = array();
                if($request->filled('delete_files')){
                    foreach($request->delete_files as $delete_file){
                        if(Storage::exists(config('filesystems.planner_portfolio').$delete_file)){
                            Storage::delete(config('filesystems.planner_portfolio').$delete_file);
                        }
                    }
                }
                if ($request->hasFile('portf_files')) {
                    $i = 0;
                    $portf_arr = array();
                    $ext_err = false;
                    $file_valid_err = false;
                    
                    foreach ($request->file('portf_files') as $portf_file) {
                        if (!$portf_file->isvalid()) {
                            $file_valid_err = true;
                            break;
                        }
                        $allowExts = array('jpeg','png','jpg','mp4','avi');
                        $sysExtension = $portf_file->extension();
                        $extension = $portf_file->getClientOriginalExtension();
                        $check_ext = false;
                        foreach ($allowExts as $ext) {
                            if ($sysExtension == $ext) {
                                $check_ext = true;
                                break;
                            }
                        }
                        if ($check_ext) {
                            //파일 삽입
                            $portf_arr['path'] = Str::uuid()->toString().".".$sysExtension;
                            $portf_arr['desc'] = '';
                            array_push($json_array,$portf_arr);
                            $portf_file->storeAs('', config('filesystems.planner_portfolio').$portf_arr['path']);
                        } else {
                            $ext_err = true;
                            break;
                        }
                        $i++;
                    }
                    
                }else{
                    $ext_err = false;
                    $file_valid_err = false;
                    $portf_arr['path'] = '';
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
                    $deleteClause = "";
                    if($request->filled('delete_sorts')){
                        foreach(array_reverse(array_sort($request->delete_sorts)) as $delete_sort){
                            $deleteClause .= " -".$delete_sort;
                        }
                    }
                    $sql = "UPDATE 
                                portfolio
                            SET 
                                portf_file = (portf_file::jsonb ".$deleteClause.") || :portf_file ,
                                portf_title = :portf_title,
                                updated_at = now()
                            WHERE 
                                pln_id = :pln_id AND
                                portf_id = :portf_id;";

                    $params = array(
                        'portf_file'=>json_encode($json_array),
                        'portf_title'=>$p['portf_title'], 
                        'pln_id'=>$p['pln_id'], 
                        'portf_id'=>$p['portf_id']
                    );

                    $this->execute_query($sql, $params, 'update');
                }
            }else{
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : TYPE 277';
            }
            break;

            case 'desc':
                if (!$request->filled('portf_sort','portf_desc','portf_id')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 308';
                    break;
                }

                $position = "{".$p['portf_sort'].",desc}";

                
                $sql = "UPDATE portfolio
                        SET portf_file = jsonb_set(portf_file, :position, :portf_desc, true)
                        WHERE portf_id = :portf_id AND pln_id = :pln_id;";
                $params = array(
                    'portf_id'=>$p['portf_id'],
                    'position'=>$position,
                    'portf_desc'=>json_encode($p['portf_desc']),
                    'pln_id'=>$this->decode_res['uid']
                );

                $this->execute_query($sql, $params, 'update');
            break;

        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  DELETE - URL  : api/portfolio/{$req}
    public function destroy(Request $request, $req)
    {
        switch($req){
            case 'delete':
                $p = $request->all();
                if (!$request->filled('portf_id')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                }else{
                    $sql = "WITH deleted AS (
                        DELETE FROM portfolio WHERE pln_id = :pln_id and portf_id = :portf_id RETURNING portf_file
                    )
                    SELECT portf_file FROM deleted;";
                    
                    $param = array(
                        'portf_id'=>$p['portf_id'],
                        'pln_id'=>$p['pln_id']
                    );
                    
                    $this->execute_query($sql, $param, 'select');

                    $deleted_files = json_decode($this->res['query'][0]->portf_file);

                    foreach($deleted_files as $delete_file){
                        if(Storage::exists(config('filesystems.planner_portfolio').$delete_file->path)){
                            Storage::delete(config('filesystems.planner_portfolio').$delete_file->path);
                        }
                    }
                }
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
