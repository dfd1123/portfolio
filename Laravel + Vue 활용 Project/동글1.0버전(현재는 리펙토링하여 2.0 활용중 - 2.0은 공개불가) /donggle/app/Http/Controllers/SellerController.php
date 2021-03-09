<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;
use File;

class SellerController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Seller controller';
    }

    public function index()
    {
        return 'API FOR Seller';
    }

    public function show(Request $request, $req)
    {
        switch($req){
            case 'list':
                $limit = $request->filled('limit')?$request->limit:5;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                $query = DB::table('seller_infor')
                ->select(
                    '*',
                    DB::raw("IFNULL((SELECT AVG(review.rating) FROM items INNER JOIN review ON items.item_id = review.item_id WHERE items.store_id = seller_infor.store_id),0) AS rating"),
                    DB::raw("(IFNULL((SELECT count(id) FROM `seller_like` WHERE store_id = seller_infor.store_id AND uid = '".$uid."'),0)) AS like_yn")
                )
                ->where('confirm',1)
                ->orderBy(DB::raw('rating'),'DESC')
                ->limit($limit)->get();

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;
            
            case 'recomend':
                $limit = $request->filled('limit')?$request->limit:10;
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;
                $query = DB::table('seller_infor')
                ->select(
                    '*',
                    DB::raw("IFNULL((SELECT AVG(review.rating) FROM items INNER JOIN review ON items.item_id = review.item_id WHERE items.store_id = seller_infor.store_id),0) AS rating"),
                    DB::raw("(IFNULL((SELECT count(id) FROM `seller_like` WHERE store_id = seller_infor.store_id AND uid = '".$uid."'),0)) AS like_yn")
                )
                ->where('confirm',1)
                ->inRandomOrder()
                ->limit($limit)
                ->get();

                $this->res['query'] = $query;
                $this->res['msg'] = "성공";
                $this->res['state'] = config('res_code.OK');
                
            break;

            case 'view':
                $uid = isset(Auth::guard('api')->user()->id)?Auth::guard('api')->user()->id:null;

                if(!$request->filled('id')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "잘못된 경로로 접근! 스토어 ID 없음!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $store_id = $request->id;
                $query = DB::table('seller_infor')
                ->select(
                    '*',
                    DB::raw("IFNULL((SELECT AVG(review.rating) FROM items INNER JOIN review ON items.item_id = review.item_id WHERE items.store_id = seller_infor.store_id),0) AS rating"),
                    DB::raw("(SELECT COUNT(id) FROM seller_like WHERE store_id = seller_infor.store_id) AS likes"),
                    DB::raw("(IFNULL((SELECT count(id) FROM `seller_like` WHERE store_id = seller_infor.store_id AND uid = '".$uid."'),0)) AS like_yn")
                )
                ->where('store_id', $store_id)
                ->first();
                
                if($query == null){
                    $this->res['query'] = null;
                    $this->res['msg'] = "아직 생성된 매장이 없음";
                    $this->res['state'] = config('res_code.NO_DATA');
                }else{
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }
            break; 

            case 'private_info': 
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $query = DB::table('seller_infor')->where('uid', $uid)->first();
                if($query == null){
                    $this->res['query'] = null;
                    $this->res['msg'] = "아직 생성된 매장이 없음";
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
        
        if(!$request->filled('company_name', 'cp_type', 'cp_sectors', 'cp_number', 'ceo_name', 'email', 'account_bank_number', 'account_bank', 'account_number', 'account_name', 'post_num', 'addr1', 'addr2')
        || !$request->hasFile('cp_file')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        $cp_files = File_store::Image_store('seller/document', $request->cp_file);

        if($cp_files == 'EXT_ERR'){  //이미지 에러
            $this->res['query'] =null;
            $this->res['msg'] = "사업자등록증사본 이미지 확장자 에러!"; 
            $this->res['state'] = config('res_code.EXT_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($cp_files == 'VALID_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "사업자등록증사본 이미지가 아님!";
            $this->res['state'] = config('res_code.IMG_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($cp_files == 'PARAM_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "사업자등록증사본 이미지 첨부 필수!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        //friday 사업자 사본 이미지파일 검사 api
        $url = 'https://ocr.api.friday24.com/business-license?url='.config('app.url').'/storage/image/seller/document'.$cp_files[0];        
        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: Bearer '.config('app.friday_key');
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = json_decode(curl_exec($ch));
        

        //api 에러 확인
        if($result->message !== ""){
            if(File::exists('../storage/app/public/image/seller/document'.$cp_files[0])) {
                File::delete('../storage/app/public/image/seller/document'.$cp_files[0]);
            }
            $this->res['query'] =null;
            $this->res['msg'] = $result->message; 
            $this->res['state'] = config('res_code.API_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        //입력한 사업자번호와 이미지의 사업자번호가 일치한지 확인
        if($request->cp_number != $result->license->bizNum){
            if(File::exists('../storage/app/public/image/seller/document'.$cp_files[0])) {
                File::delete('../storage/app/public/image/seller/document'.$cp_files[0]);
            }
            $this->res['query'] =null;
            $this->res['msg'] = "입력한 사업자등록번호와 이미지의 사업자 번호가 일치하지 않음"; 
            $this->res['state'] = config('res_code.INFO_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $uid = Auth::guard('api')->id();
        $company_name = $request->company_name;
        $cp_type = $request->cp_type;
        $cp_sectors = $request->cp_sectors;
        $cp_number = $request->cp_number;
        $ceo_name = $request->ceo_name;
        $email = $request->email;
        $account_bank_number = $request->account_bank_number;
        $account_bank = $request->account_bank;
        $account_number = $request->account_number;
        $account_name = $request->account_name;
        $tel = $request->tel;
        $fax_num = $request->fax_num;
        $post_num = $request->post_num;
        $addr1 = $request->addr1;
        $addr2 = $request->addr2;
        $extra_addr = $request->extra_addr;
        $addr_jibeon = $request->addr_jibeon;
        $intro = $request->intro;
        $profile_img = Auth::guard('api')->user()->profile_img;
        
        if($request->hasFile('image')){
            $images = File_store::Image_store('seller/image', $request->image);
            if($images == 'EXT_ERR'){  //이미지 에러
                $this->res['query'] =null;
                $this->res['msg'] = "업체 대표 이미지 이미지 확장자 에러!"; 
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($images == 'VALID_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "업체 대표 이미지 이미지가 아님!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else if($images == 'PARAM_ERR'){
                $this->res['query'] = null;
                $this->res['msg'] = "업체 대표 이미지 이미지 첨부 필수!";
                $this->res['state'] = config('res_code.PARAM_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
            
            try {
                $insert = DB::table('seller_infor')->insert([
                    'uid' => $uid,
                    'company_name' => $company_name,
                    'cp_type' => $cp_type,
                    'cp_sectors' => $cp_sectors,
                    'cp_number' => $cp_number,
                    'cp_file' => json_encode($cp_files),
                    'ceo_name' => $ceo_name,
                    'email' => $email,
                    'account_bank_number' => $account_bank_number,
                    'account_bank' => $account_bank,
                    'account_number' => $account_number,
                    'account_name' => $account_name,
                    'tel' => $tel,
                    'fax_num' => $fax_num,
                    'post_num' => $post_num,
                    'addr1' => $addr1,
                    'addr2' => $addr2,
                    'extra_addr' => $extra_addr,
                    'addr_jibeon' => $addr_jibeon,
                    'image' => json_encode($images),
                    'intro' => $intro,
                    'profile_img' => $profile_img,
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
        }else{
            
            try {
                $insert = DB::table('seller_infor')->insert([
                    'uid' => $uid,
                    'company_name' => $company_name,
                    'cp_type' => $cp_type,
                    'cp_sectors' => $cp_sectors,
                    'cp_number' => $cp_number,
                    'cp_file' => json_encode($cp_files),
                    'ceo_name' => $ceo_name,
                    'email' => $email,
                    'account_bank_number' => $account_bank_number,
                    'account_bank' => $account_bank,
                    'account_number' => $account_number,
                    'account_name' => $account_name,
                    'tel' => $tel,
                    'fax_num' => $fax_num,
                    'post_num' => $post_num,
                    'addr1' => $addr1,
                    'addr2' => $addr2,
                    'extra_addr' => $extra_addr,
                    'addr_jibeon' => $addr_jibeon,
                    'intro' => $intro,
                    'profile_img' => $profile_img,
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
        }
        

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        switch($req){
            case 'update': 
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if(!$request->filled('company_name', 'cp_type', 'cp_sectors', 'cp_number', 'ceo_name', 'email', 'account_bank_number', 'account_bank', 'account_number', 'account_name', 'post_num', 'addr1', 'addr2')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $uid = Auth::guard('api')->id();
                $query = DB::table('seller_infor')->where('uid', $uid)->first();

                if($request->hasFile('cp_file')){
                    $cp_files = File_store::Image_store('seller/document', $request->cp_file);

                    if($cp_files == 'EXT_ERR'){  //이미지 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "사업자등록증사본 이미지 확장자 에러!"; 
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($cp_files == 'VALID_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "사업자등록증사본 이미지가 아님!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($cp_files == 'PARAM_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "사업자등록증사본 이미지 첨부 필수!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else{
                        if(File::exists('../storage/app/public/image/seller/document'.json_decode($query->cp_file)[0])) {
                            File::delete('../storage/app/public/image/seller/document'.json_decode($query->cp_file)[0]);
                        }
                        //friday 사업자 사본 이미지파일 검사 api
                        $url = 'https://ocr.api.friday24.com/business-license?url='.config('app.url').'/storage/image/seller/document'.$cp_files[0];        
                        $header = array();
                        $header[] = 'Content-type: application/json';
                        $header[] = 'Authorization: Bearer '.config('app.friday_key');
                        $ch = curl_init($url);                                                                      
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                 
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        $result = json_decode(curl_exec($ch));
                        

                        //api 에러 확인
                        if($result->message !== ""){
                            if(File::exists('../storage/app/public/image/seller/document'.$cp_files[0])) {
                                File::delete('../storage/app/public/image/seller/document'.$cp_files[0]);
                            }
                            $this->res['query'] =null;
                            $this->res['msg'] = $result->message; 
                            $this->res['state'] = config('res_code.API_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }

                        //입력한 사업자번호와 이미지의 사업자번호가 일치한지 확인
                        if($request->cp_number != $result->license->bizNum){
                            if(File::exists('../storage/app/public/image/seller/document'.$cp_files[0])) {
                                File::delete('../storage/app/public/image/seller/document'.$cp_files[0]);
                            }
                            $this->res['query'] =null;
                            $this->res['msg'] = "입력한 사업자등록번호와 이미지의 사업자 번호가 일치하지 않음"; 
                            $this->res['state'] = config('res_code.INFO_ERR');
                            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                        }
                    }
                    
                }else{
                    $cp_files = json_decode($query->cp_file);
                }
                
                $company_name = $request->company_name;
                $cp_type = $request->cp_type;
                $cp_sectors = $request->cp_sectors;
                $cp_number = $request->cp_number;
                $ceo_name = $request->ceo_name;
                $email = $request->email;
                $account_bank_number = $request->account_bank_number;
                $account_bank = $request->account_bank;
                $account_number = $request->account_number;
                $account_name = $request->account_name;
                $tel = $request->tel;
                $fax_num = $request->fax_num;
                $post_num = $request->post_num;
                $addr1 = $request->addr1;
                $addr2 = $request->addr2;
                $extra_addr = $request->extra_addr;
                $addr_jibeon = $request->addr_jibeon;
                $intro = $request->intro;
                $profile_img = Auth::guard('api')->user()->profile_img;
                
                if($request->hasFile('image')){
                    $images = File_store::Image_store('seller/image', $request->image);
                    if($images == 'EXT_ERR'){  //이미지 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "업체 대표 이미지 이미지 확장자 에러!"; 
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($images == 'VALID_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "업체 대표 이미지 이미지가 아님!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($images == 'PARAM_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "업체 대표 이미지 이미지 첨부 필수!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else{
                        if(File::exists('../storage/app/public/image/seller/image'.json_decode($query->image)[0])) {
                            File::delete('../storage/app/public/image/seller/image'.json_decode($query->image)[0]);
                        }
                    }
                    
                    try {
                        $update = DB::table('seller_infor')->where('uid',$uid)->update([
                            'company_name' => $company_name,
                            'cp_type' => $cp_type,
                            'cp_sectors' => $cp_sectors,
                            'cp_number' => $cp_number,
                            'cp_file' => json_encode($cp_files),
                            'ceo_name' => $ceo_name,
                            'email' => $email,
                            'account_bank_number' => $account_bank_number,
                            'account_bank' => $account_bank,
                            'account_number' => $account_number,
                            'account_name' => $account_name,
                            'tel' => $tel,
                            'fax_num' => $fax_num,
                            'post_num' => $post_num,
                            'addr1' => $addr1,
                            'addr2' => $addr2,
                            'extra_addr' => $extra_addr,
                            'addr_jibeon' => $addr_jibeon,
                            'image' => json_encode($images),
                            'intro' => $intro,
                            'profile_img' => $profile_img,
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
                }else{
                    try {
                        $update = DB::table('seller_infor')->where('uid',$uid)->update([
                            'company_name' => $company_name,
                            'cp_type' => $cp_type,
                            'cp_sectors' => $cp_sectors,
                            'cp_number' => $cp_number,
                            'cp_file' => json_encode($cp_files),
                            'ceo_name' => $ceo_name,
                            'email' => $email,
                            'account_bank_number' => $account_bank_number,
                            'account_bank' => $account_bank,
                            'account_number' => $account_number,
                            'account_name' => $account_name,
                            'tel' => $tel,
                            'fax_num' => $fax_num,
                            'post_num' => $post_num,
                            'addr1' => $addr1,
                            'addr2' => $addr2,
                            'extra_addr' => $extra_addr,
                            'addr_jibeon' => $addr_jibeon,
                            'intro' => $intro,
                            'profile_img' => $profile_img,
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
