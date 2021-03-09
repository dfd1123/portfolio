<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Auth;
use File;

class StoreController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Store controller';
    }

    public function index()
    {
        return 'API FOR Store';
    }

    public function show(Request $request, $req)
    {
        switch ($req) {

            case 'view':
                $uid = Auth::guard('api')->user()->id;
                $store = DB::table('seller_infor')->where('uid', $uid)->first();

                /*if(!isset($store->store_id)){
                    $this->res['query'] = null;
                    $this->res['msg'] = "해당 USERID 에 존재하는 스토어가 없습니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }*/
                
                $store_id = $store->store_id;

                $query = DB::table('seller_infor')
                ->where('store_id', $store_id)
                ->first();
                
                if ($query == null) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "아직 생성된 매장이 없음";
                    $this->res['state'] = config('res_code.NO_DATA');
                } else {
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }
            break;

            case 'brandname_check':
                if (!$request->filled('brand_name')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $uid = Auth::guard('api')->user()->id;

                try{
                    $exists = DB::table('seller_infor')->where('uid', '<>', $uid)->where('brandname', $request->brand_name)->exists();

                    $this->res['query'] = !$exists;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }

            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        if (!$request->filled('company_name', 'cp_type', 'cp_sectors', 'cp_number', 'ceo_name', 'email', 'account_bank_number', 'account_bank', 'account_number', 'account_name', 'post_num')
        || !$request->hasFile('cp_file')) {
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        $cp_files = File_store::File_store('seller', $request->cp_file);

        if($cp_files == 'SIZE_ERR'){  //사이즈 에러
            $this->res['query'] =null;
            $this->res['msg'] = "최대 파일 사이즈 초과 에러! (최대 40MB)"; 
            $this->res['state'] = config('res_code.SIZE_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($cp_files == 'EXT_ERR'){
            $this->res['query'] =null;
            $this->res['msg'] = "확장자 에러!"; 
            $this->res['state'] = config('res_code.EXT_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($cp_files == 'VALID_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "파일 유효성 에러!";
            $this->res['state'] = config('res_code.IMG_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else if($cp_files == 'PARAM_ERR'){
            $this->res['query'] = null;
            $this->res['msg'] = "첨부한 파일 없음!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        //friday 사업자 사본 이미지파일 검사 api
        /*$url = 'https://ocr.api.friday24.com/business-license?url='.config('app.url').'/storage/image/seller/document'.$cp_files[0];
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
        }*/

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
        $address = $request->address;
        $extra_addr = $request->extra_addr;
        $addr_jibeon = $request->addr_jibeon;
        $intro = $request->intro;
        $kakaoid = $request->kakaoid;
        $brandname = $request->brandname;
        $profile_img = Auth::guard('api')->user()->profile_img;
        
        if ($request->hasFile('image')) {
            $images = File_store::Image_store('seller/image', $request->image);
            if ($images == 'EXT_ERR') {  //이미지 에러
                $this->res['query'] =null;
                $this->res['msg'] = "업체 대표 이미지 이미지 확장자 에러!";
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            } elseif ($images == 'VALID_ERR') {
                $this->res['query'] = null;
                $this->res['msg'] = "업체 대표 이미지 이미지가 아님!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            } elseif ($images == 'PARAM_ERR') {
                $this->res['query'] = null;
                $this->res['msg'] = "업체 대표 이미지 이미지 첨부 필수!";
                $this->res['state'] = config('res_code.PARAM_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        } else {
            $images = array();
        }

        if ($request->hasFile('profile_img')) {
            $profile_img = File_store::Image_store('seller/image', $request->profile_img);
            if ($profile_img == 'EXT_ERR') {  //이미지 에러
                $this->res['query'] =null;
                $this->res['msg'] = "업체 대표 이미지 이미지 확장자 에러!";
                $this->res['state'] = config('res_code.EXT_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            } elseif ($profile_img == 'VALID_ERR') {
                $this->res['query'] = null;
                $this->res['msg'] = "업체 대표 이미지 이미지가 아님!";
                $this->res['state'] = config('res_code.IMG_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            } elseif ($profile_img == 'PARAM_ERR') {
                $this->res['query'] = null;
                $this->res['msg'] = "업체 대표 이미지 이미지 첨부 필수!";
                $this->res['state'] = config('res_code.PARAM_ERR');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }
        } else {
            $profile_img = array();
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
                'kakaoid' => $kakaoid,
                'brandname' => $brandname,
                'account_bank_number' => $account_bank_number,
                'account_bank' => $account_bank,
                'account_number' => $account_number,
                'account_name' => $account_name,
                'tel' => $tel,
                'fax_num' => $fax_num,
                'post_num' => $post_num,
                'address' => $address,
                'extra_addr' => $extra_addr,
                'addr_jibeon' => $addr_jibeon,
                'image' => json_encode($images),
                'intro' => $intro,
                'profile_img' => json_encode($profile_img),
            ]);
            $this->res['query'] = $insert;
            $this->res['msg'] = "성공";
            $this->res['state'] = config('res_code.OK');
        } catch (Exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $req)
    {
        switch ($req) {
            case 'update':
                if (!$request->filled('company_name', 'cp_type', 'cp_sectors', 'cp_number', 'ceo_name', 'email', 'account_bank_number', 'account_bank', 'account_number', 'account_name', 'post_num')) {
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $uid = Auth::guard('api')->id();
                $query = DB::table('seller_infor')->where('uid', $uid)->first();

                if ($request->hasFile('cp_file')) {
                    $not_delete_files = [];
                    $cp_files = File_store::File_update('seller', $request->cp_file, json_decode($query->cp_file), $not_delete_files);
                    if($cp_files == 'SIZE_ERR'){  //사이즈 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "최대 파일 사이즈 초과 에러! (최대 40MB)"; 
                        $this->res['state'] = config('res_code.SIZE_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($cp_files == 'EXT_ERR'){
                        $this->res['query'] =null;
                        $this->res['msg'] = "확장자 에러!"; 
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($cp_files == 'VALID_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "파일 유효성 에러!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }else if($cp_files == 'PARAM_ERR'){
                        $this->res['query'] = null;
                        $this->res['msg'] = "첨부한 파일 없음!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                } else {
                    $cp_files = json_decode($query->cp_file);
                }
                
                $company_name = $request->company_name;
                $cp_type = $request->cp_type;
                $cp_sectors = $request->cp_sectors;
                $cp_number = $request->cp_number;
                $ceo_name = $request->ceo_name;
                $email = $request->email;
                $kakaoid = $request->kakaoid;
                $brandname = $request->brandname;
                $account_bank_number = $request->account_bank_number;
                $account_bank = $request->account_bank;
                $account_number = $request->account_number;
                $account_name = $request->account_name;
                $tel = $request->tel;
                $fax_num = $request->fax_num;
                $post_num = $request->post_num;
                $address = $request->address;
                $extra_addr = $request->extra_addr;
                $addr_jibeon = $request->addr_jibeon;
                $intro = $request->intro;
                
                if ($request->hasFile('image')) {
                    $not_delete_files = [];
                    $images = File_store::Image_update('seller/image', $request->image, json_decode($query->image), $not_delete_files);
                    if ($images == 'EXT_ERR') {  //이미지 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "업체 대표 이미지 이미지 확장자 에러!";
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    } elseif ($images == 'VALID_ERR') {
                        $this->res['query'] = null;
                        $this->res['msg'] = "업체 대표 이미지 이미지가 아님!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    } elseif ($images == 'PARAM_ERR') {
                        $this->res['query'] = null;
                        $this->res['msg'] = "업체 대표 이미지 이미지 첨부 필수!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    } else {
                        if (isset(json_decode($query->image)[0])) {
                            if (File::exists('../storage/app/public/image/seller/image'.json_decode($query->image)[0])) {
                                File::delete('../storage/app/public/image/seller/image'.json_decode($query->image)[0]);
                            }
                        }
                    }
                } else {
                    $images = json_decode($query->image);
                }

                if ($request->hasFile('profile_img')) {
                    $not_delete_files = [];
                    $profile_img = File_store::Image_update('seller/profile_img', $request->profile_img, json_decode($query->profile_img), $not_delete_files);
                    if ($profile_img == 'EXT_ERR') {  //이미지 에러
                        $this->res['query'] =null;
                        $this->res['msg'] = "업체 대표 이미지 이미지 확장자 에러!";
                        $this->res['state'] = config('res_code.EXT_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    } elseif ($profile_img == 'VALID_ERR') {
                        $this->res['query'] = null;
                        $this->res['msg'] = "업체 대표 이미지 이미지가 아님!";
                        $this->res['state'] = config('res_code.IMG_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    } elseif ($profile_img == 'PARAM_ERR') {
                        $this->res['query'] = null;
                        $this->res['msg'] = "업체 대표 이미지 이미지 첨부 필수!";
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }

                    DB::table('items')->where('seller_id', $uid)->update([
                        "seller_name" => $ceo_name,
                        "company_profile_img" => $profile_img,
                        "company_name" => $brandname
                    ]);
                    
                } else {
                    $profile_img = json_decode($query->profile_img);

                    DB::table('items')->where('seller_id', $uid)->update([
                        "seller_name" => $ceo_name,
                        "company_name" => $brandname
                    ]);
                }
                DB::table('order')->where('seller_uid',$uid)->update([
                    "company_name" => $brandname
                ]);

                try {
                    $update = DB::table('seller_infor')->where('uid', $uid)->update([
                        'company_name' => $company_name,
                        'cp_type' => $cp_type,
                        'cp_sectors' => $cp_sectors,
                        'cp_number' => $cp_number,
                        'cp_file' => json_encode($cp_files),
                        'ceo_name' => $ceo_name,
                        'email' => $email,
                        'kakaoid' => $kakaoid,
                        'brandname' => $brandname,
                        'account_bank_number' => $account_bank_number,
                        'account_bank' => $account_bank,
                        'account_number' => $account_number,
                        'account_name' => $account_name,
                        'tel' => $tel,
                        'fax_num' => $fax_num,
                        'post_num' => $post_num,
                        'address' => $address,
                        'extra_addr' => $extra_addr,
                        'addr_jibeon' => $addr_jibeon,
                        'image' => json_encode($images),
                        'intro' => $intro,
                        'profile_img' => json_encode($profile_img),
                    ]);
                    $this->res['query'] = $update;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                } catch (Exception $e) {
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
