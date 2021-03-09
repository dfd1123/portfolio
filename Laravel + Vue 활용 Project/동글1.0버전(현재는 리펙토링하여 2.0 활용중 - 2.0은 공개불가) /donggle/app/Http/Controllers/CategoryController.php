<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;

class CategoryController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
     /**
	 * @OA\Get(
	 *     path="/category/main_list",
	 *     @OA\Response(response="200", description="Display a listing of projects.")
	 * )
	 */
	 
    public function __construct(){
        // $this->middleware('apiauthcheck', ['except' => 'show']); 
    }

    public function index()
    {
        return 'API FOR CATEGORY';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            //main 카테고리 리스트 최적화
            case 'main_list': 
                try {
                    $first_categorys = DB::table('category')->where('ca_use',1)->whereRaw('LENGTH(id) = 2')->orderBy('ca_order','ASC')->get();
                    $categorys = array();
                    $i = 0;
                    foreach($first_categorys as $first_category){
                        $second_categorys = DB::table('category')
                        ->select('id', 'ca_name')
                        ->where('ca_use',1)
                        ->whereRaw('LENGTH(id) = 4')
                        ->whereRaw("SUBSTRING(id,1,2) = '".$first_category->id."'")
                        ->orderBy('ca_order','ASC')
                        ->get();
                        $categorys[$i]['ca_first'] = json_encode($first_category);
                        $categorys[$i]['ca_second'] = json_encode($second_categorys);
                        $i++;
                    }

                    if($categorys == null){
                        $this->res['query'] = null;
                        $this->res['msg'] = "더 이상의 자료가 없음!";
                        $this->res['state'] = config('res_code.NO_DATA');
                    }else{
                        $this->res['query'] = $categorys; 
                        $this->res['msg'] = "성공";
                        $this->res['state'] = config('res_code.OK');
                    }
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
                
            break; 

            case 'level_search': 
                    $ca_id = $request->id;
                    $length = strlen($ca_id);

                    $up_id_array = array();

                    for($i=1;$i<$length/2;$i++){
                        $up_id_array[] = substr($ca_id,0,$i*2);
                    }

                    $response = array();
                    try {
                        $up_category = DB::table('category')->select('id','ca_name')->where('ca_use',1)->whereIn('id', $up_id_array)->orderBy('ca_order','ASC')->get(); //상위카테고리 정보
                        $now_categorys = DB::table('category')->select('id','ca_name','ca_use')->where('ca_use',1)->where('id', $ca_id)->first(); //현재 카테고리 정보
                        $next_categorys = DB::table('category')->select('id','ca_name')->where('ca_use',1)->whereRaw('LENGTH(id) = '.($length + 2))->whereRaw("SUBSTRING(id,1,".$length.") = '".$ca_id."'")->orderBy('ca_order','ASC')->get(); //하위카테고리정보

                        $response['up_category'] = $up_category;
                        $response['now_categorys'] = $now_categorys;
                        $response['next_categorys'] = $next_categorys;

                        $this->res['query'] = $response; 
                        $this->res['msg'] = "성공";
                        $this->res['state'] = config('res_code.OK');
                        
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                

            break;

            case 'search_cate': 
                if(!$request->filled('searchKeyword') || !isset($request->searchKeyword) || $request->searchKeyword == null || $request->searchKeyword == ''){
                    $response = array();
                    try {
                        $categorys = DB::table('category')->select('id','ca_name')->where('ca_use',1)->whereRaw('LENGTH(id) = 2');
                        
                        $categorys = $categorys->orderBy('ca_order','ASC')->limit(30)->get();
                        $response["categorys"] = $categorys;

                        $this->res['query'] = $response; 
                        $this->res['msg'] = "성공";
                        $this->res['state'] = config('res_code.OK');
                        
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }else{
                    $searchKeyword = ($request->filled('searchKeyword'))?'%'.$request->searchKeyword.'%':'%%';
                    
                    $response = array();
                    try {
                        $categorys = DB::table('category')->select('id','ca_name')->where('ca_use',1)->where('ca_name','like', $searchKeyword);
                        $categorys->where(function($query) use ($searchKeyword, $request){
                            $query->where('ca_name','like',$searchKeyword);
                        });

                        $categorys = $categorys->orderBy('ca_order','ASC')->limit(30)->get();
                        
                        $response["categorys"] = $categorys;

                        $this->res['query'] = $response; 
                        $this->res['msg'] = "성공";
                        $this->res['state'] = config('res_code.OK');
                        
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }
            break; 
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if($request->filled('ca_name', 'ca_order')){
            $ca_id = $request->ca_id;

            // 카테고리 id 자동 생성 알고리즘
            $len = strlen($ca_id);
            $len2 = $len + 1;
            $row = DB::table('category')->select(DB::raw("MAX(SUBSTRING(id,".$len2.",2)) AS max_subid"))->whereRaw("SUBSTRING(id,1,".$len.") = '".$ca_id."'")->first();
            $subid = base_convert($row->max_subid,36,10);
            $subid += 36;
            $subid = base_convert($subid,10,36);
            $subid = substr("00".$subid, -2);
            $subid = $ca_id.$subid;
            $ca_id = $subid;

            $ca_name = $request->ca_name;
            $ca_order = $request->ca_order;

            $length = strlen($ca_id);

            if($length > 2){
                $up_id_array = array();

                for($i = $length/2 - 1; $i <= $length/2; $i++){
                    $up_id_array[] = substr($ca_id,0,$i*2);
                }
                
                $up_category = DB::table('category')->select(DB::raw("GROUP_CONCAT(ca_name separator ' > ') AS ca_name"))->whereIn('id', $up_id_array)->orderBy('id','ASC')->first(); 
                
                $ca_name = $up_category->ca_name." > ".$ca_name;
            }

            if($request->hasFile('style_img')){
                $images = File_store::Image_store('style', $request->ca_icon);
                if($images == 'EXT_ERR'){
                    $this->res['query'] =null;
                    $this->res['msg'] = "이미지 확장자 에러!"; 
                    $this->res['state'] = config('res_code.EXT_ERR');
                }else if($images == 'VALID_ERR'){
                    $this->res['query'] = null;
                    $this->res['msg'] = "이미지가 아님!";
                    $this->res['state'] = config('res_code.IMG_ERR');
                }else if($images == 'PARAM_ERR'){
                    $this->res['query'] = null;
                    $this->res['msg'] = "이미지 첨부 필수!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                }else{
                    try {
                        $insert = DB::table('category')->insert([
                            'id' => $ca_id,
                            'ca_name' => $ca_name,
                            'ca_order' => $ca_order,
                            'ca_icon' => json_encode($images),
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()'),
                        ]);
                        $this->res['query'] = $insert;
                        $this->res['msg'] = "성공";
                        $this->res['state'] = config('res_code.OK');
                    
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)"; 
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }
            }else{
                try {
                    $insert = DB::table('category')->insert([
                        'id' => $ca_id,
                        'ca_name' => $ca_name,
                        'ca_order' => $ca_order,
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()'),
                    ]);
                    $this->res['query'] = $insert;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
            }
        }else{
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        switch($req){
            case 'edit':
                if($request->filled('ca_id','ca_name', 'ca_order')){

                    $ca_id = $request->ca_id;
                    $ca_name = $request->ca_name;
                    $ca_order = $request->ca_order;

                    $length = strlen($ca_id);

                    if($length > 2){
                        $ca_info = DB::table('category')->where('id',$ca_id)->first();
                        
                        $explode_cate = explode(" > ",$ca_info->ca_name);

                        $cate_temp = $explode_cate[0];
                        for($i=1; $i<count($explode_cate) - 1; $i++){
                            $cate_temp = $cate_temp." > ".$explode_cate[$i];
                        }
                        $ca_name = $cate_temp." > ".$ca_name;
                    }


                    //$ca_explode = explode(' > ',$ca_info->)
                    
                    $update = DB::table('category')->where('id',$ca_id)->update([
                        'ca_name' => $ca_name,
                        'ca_order' => $ca_order,
                        'updated_at' => DB::raw('now()'),
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }else{
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                }
            break;

            case 'use':
                if($request->filled('ca_id','ca_use')){
                    $ca_id = $request->ca_id;
                    $ca_name = $request->ca_use;
                    
                    $update = DB::table('category')->where('id',$ca_id)->update([
                        'ca_use' => $ca_use,
                        'updated_at' => DB::raw('now()'),
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }else{
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
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
