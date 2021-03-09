<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Classes\File_store;

use DB;
use Hash;
use Auth;


class UserController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'User controller';
    }

    public function index()
    {
        return 'API FOR USERS';
    }

    // 요청경로  GET - URL  : api/users/{req}
    public function show(Request $request, $req)
    {
        switch($req){
            case 'user_info':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $user = Auth::guard('api')->user();

                unset($user->password);

                if($user === null){
                    $this->res['query'] = null;
                    $this->res['msg'] = "유저 정보가 존재하지 않습니다.";
                    $this->res['state'] = config('res_code.NO_DATA');
                }else{
                    $this->res['query'] = $user;
                    $this->res['msg'] = "성공";
                    $this->res['state'] = config('res_code.OK');
                }
            break;

            case 'search_id': //id 찾기
                if($request->filled('name','mobile_number')){
                    $name = $request->name;
                    $mobile_number = $request->mobile_number;
                    try {
                        $query = DB::table('users')->select('email')->where('name', $name)->where('mobile_number',$mobile_number)->first();
                        
                        if($query == null){
                            $this->res['query'] = null;
                            $this->res['msg'] = "해당 이름과 휴대폰에 일치하는 ID가 없습니다.";
                            $this->res['state'] = config('res_code.NO_DATA');
                        }else{
                            $this->res['query'] = $query;
                            $this->res['msg'] = "성공";
                            $this->res['state'] = config('res_code.OK');
                        }
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['msg'] = "이름, 휴대폰번호를 모두 입력해야만 합니다.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                }
            break;

            case 'search_pw' :
                if($request->filled('email', 'name','mobile_number')){
                    $email = $request->email;
                    $name = $request->name;
                    $mobile_number = $request->mobile_number;
                    try {
                        $query = DB::table('users')->where('email',$email)->where('name', $name)->where('mobile_number',$mobile_number)->exists();
                        
                        if($query == null){
                            $this->res['query'] = null;
                            $this->res['msg'] = "가입된 유저가 아닙니다. ID찾기를 통해 ID를 다시 확인해주세요. ";
                            $this->res['state'] = config('res_code.NO_DATA');
                        }else{
                            $this->res['query'] = $query;
                            $this->res['msg'] = "성공!";
                            $this->res['state'] = config('res_code.OK');
                        }
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)"; 
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                }
            break;

            case 'email_check':
                try{
                    $query = DB::table('users')->where('email',$request->email)->where('register_kind', '<>', 0)->exists();
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
            break;

            case 'nickname_check':
                try{
                    $query = DB::table('users')->where('nickname',$request->nickname)->where('register_kind', '<>', 0)->exists();
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
            break;

            case 'phone_check':
                try{
                    $query = DB::table('users')->where('mobile_number',$request->mobile_number)->where('register_kind', '<>', 0)->exists();
                    $this->res['query'] = $query;
                    $this->res['msg'] = "성공!";
                    $this->res['state'] = config('res_code.OK');
                }catch(exception $e){
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request) //jwt 쓰기때문에 현재 안씀
    {
        if($request->filled('email', 'password', 'password_c', 'name', 'nickname', 'mobile_number','ad_agree')){
            $email = $request->email;
            $password = $request->password;
            $password_c = $request->password_c;
            $name = $request->name;
            $nickname = $request->nickname;
            $mobile_number = $request->mobile_number;
            $ad_agree = $request->ad_agree;

            if($ad_agree){
                $ad_agree = 1;
            }else{
                $ad_agree = 0;
            }

            if($password === $password_c){

                try {
                    $query = DB::table('users')->select('id')->where('email',$email)->orwhere('nickname', $nickname)->orwhere('mobile_number',$mobile_number)->first();
                    
                    if($query == null){
                        $insert = DB::table('users')->insert([
                            'register_kind' => 1,
                            'email' => $email,
                            'password' => Hash::make($password),
                            'name' => $name,
                            'nickname' => $nickname,
                            'mobile_number' => $mobile_number,
                            'level' => 0,
                            'ad_agree' => $ad_agree,
                            'status' => 1,
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()'),
                        ]);

                        $this->res['query'] = $insert;
                        $this->res['msg'] = "가입 성공!";
                        $this->res['state'] = config('res_code.OK');
                    }else{
                        $this->res['query'] = $query;
                        $this->res['msg'] = "해당 이메일 or 닉네임 or 휴대폰번호 이미 존재!";
                        $this->res['state'] = config('res_code.DUPLI_ERR');
                    }
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)"; 
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }

            }else{
                $this->res['query'] =null;
                $this->res['msg'] = "비밀번호와 확인비밀번호 불일치!"; 
                $this->res['state'] = config('res_code.PWD_ERR');
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
            case 'profile_img':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                if(!$request->hasFile('profile_img')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                $uid = Auth::guard('api')->id();
                $user = DB::table('users')->where('id',$uid)->first();

                $profile_img = File_store::Image_update_profile('user', $request->profile_img, json_decode($user->profile_img), array());
                if($profile_img == 'EXT_ERR'){  //이미지 에러
                    $this->res['query'] =null;
                    $this->res['msg'] = "프로필 이미지 확장자 에러!"; 
                    $this->res['state'] = config('res_code.EXT_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }else if($profile_img == 'VALID_ERR'){
                    $this->res['query'] = null;
                    $this->res['msg'] = "프로필 이미지가 아님!";
                    $this->res['state'] = config('res_code.IMG_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }else if($profile_img == 'PARAM_ERR'){
                    $this->res['query'] = null;
                    $this->res['msg'] = "프로필 이미지 첨부 필수!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }
                
                
                try {
                    $query = DB::table('users')->where('id', $uid)->update([
                        'profile_img' => json_encode($profile_img),
                    ]);

                    if($query){
                        $user = DB::table('users')->where('id',$uid)->first();
                        unset($user->password);
                        
                        $this->res['query'] = $user;
                        $this->res['msg'] = "프로필사진 수정 완료!";
                        $this->res['state'] = config('res_code.OK');
                    }else{
                        $this->res['query'] = null;
                        $this->res['msg'] = "변경사항 없음";
                        $this->res['state'] = config('res_code.OK');
                    }
                    
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }

                
            break;

            case 'style':
                if(!$request->filled('id', 'uniqHashTags')){
                    $this->res['query'] = null;
                    $this->res['msg'] = "필수 정보 부족!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $uid = $request->id;
                $uniqHashTags = $request->uniqHashTags;

                try {
                    $query = DB::table('users')->where('id', $uid)->update([
                        'style_hash' => json_encode($uniqHashTags),
                    ]);
                    
                    $this->res['query'] = $query;
                    $this->res['msg'] = "스타일 수정 완료!";
                    $this->res['state'] = config('res_code.OK');
                    
                } catch(exception $e) {
                    $this->res['query'] =null;
                    $this->res['msg'] = "시스템 에러(쿼리)";
                    $this->res['state'] = config('res_code.QUERY_ERR');
                }

                
            break;

            case 'status': //회원 탈퇴
                if($request->filled('id')){ //id 를 나중에 auth 토큰 id 값으로 변경해야됨
                    $id = $request->id;
                    $status = $request->status;

                    try {
                        $query = DB::table('users')->where('id', $id)->update([
                            'status' => 1,
                        ]);
                        
                        $this->res['query'] = $query;
                        $this->res['msg'] = "회원 탈퇴 성공!";
                        $this->res['state'] = config('res_code.OK');
                        
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['msg'] = "ID값 없음! 로그인 확인필요!";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                }
            break;

            case 'password': // 비밀번호 변경
                if($request->filled('email', 'password', 'password_confirm', 'name', 'mobile_number')){ 
                    $email = $request->email;
                    $password = $request->password;
                    $password_confirm = $request->password_confirm;
                    $name = $request->name;
                    $mobile_number = $request->mobile_number;

                    if($password !==  $password_confirm){
                        $this->res['query'] = null;
                        $this->res['msg'] = "비밀번호가 일치하지 않습니다.";
                        $this->res['state'] = config('res_code.PWD_ERR');

                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }

                    try {
                        $query = DB::table('users')->where('email', $email)->where('name', $name)->where('mobile_number', $mobile_number)->update([
                            'password' => bcrypt($password),
                        ]);
                        
                        $this->res['query'] = $query;
                        $this->res['msg'] = "비밀번호 변경 성공!";
                        $this->res['state'] = config('res_code.OK');
                        
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['msg'] = "정상적인 파라미터로 API를 호출하세요.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                }
            break;

            case 'change_account':
                if($request->filled('account_bank','account_number','account_name')){

                    if(!Auth::guard('api')->check()){
                        $this->res['query'] = null;
                        $this->res['msg'] = "Auth 없음!";
                        $this->res['state'] = config('res_code.NO_AUTH');
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                    $uid = Auth::guard('api')->id();
                    $account_bank = $request->account_bank;
                    $account_number = $request->account_number;
                    $account_name = $request->account_name;

                    try {
                        $query = DB::table('users')->where('id', $uid)->update([
                            'account_bank' => $account_bank,
                            'account_number' => $account_number,
                            'account_name' => $account_name
                        ]);
                        
                        if($query){
                            $user = Auth::guard('api')->user();

                            unset($user->password);

                            $this->res['query'] = $query;
                            $this->res['msg'] = "성공!";
                            $this->res['state'] = config('res_code.OK');
                        }else{
                            $this->res['query'] = $query;
                            $this->res['msg'] = "수정 실패!";
                            $this->res['state'] = config('res_code.API_ERR');
                        }
                        
                    } catch(exception $e) {
                        $this->res['query'] =null;
                        $this->res['msg'] = "시스템 에러(쿼리)";
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
                }else{
                    $this->res['query'] = null;
                    $this->res['msg'] = "정상적인 파라미터로 API를 호출하세요.";
                    $this->res['state'] = config('res_code.PARAM_ERR');
                }
            break;

            case 'store_join_req':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                DB::table('users')->where('id', Auth::guard('api')->id())->update([
                    'register_kind' => 2
                ]);

                DB::table('seller_infor')->insert([
                    'uid' => Auth::guard('api')->id()
                ]);

                $user = DB::table('users')->where('id', Auth::guard('api')->id())->first();
                unset($user->password);

                $this->res['query'] = $user;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'secession':
                if(!Auth::guard('api')->check()){
                    $this->res['query'] = null;
                    $this->res['msg'] = "Auth 없음!";
                    $this->res['state'] = config('res_code.NO_AUTH');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                }

                $user = Auth::guard('api')->user();

                $email = time( );
                $register_type = $user->register_type?$user->register_type:'donggle';
                $email = $user->email.'/'.$email.'/'.$register_type;

                $updated = DB::table('users')->where('id', Auth::guard('api')->id())->update([
                    "register_kind" => 0,
                    "email" => $email,
                    "name" => '탈퇴회원자('.$user->id.')',
                    "password" => '',
                    "profile_img" => null,
                    "mobile_number" => null,
                    "gender" => null,
                    "birthday" => null,
                    "post_num" => null,
                    "address" => null,
                    "extra_addr" => null,
                    "addr_jibeon" => null,
                    "ad_agree" => 0,
                    "account_bank" => null,
                    "account_number" => null,
                    "account_name" => null,
                    "wear_size" => null,
                    "sms_notify" => 0,
                    "register_type" => null,
                    "level" => 0,
                    "email_notify" => 0,
                    "updated_at" => now(),
                    "regular_end" => null,
                    "payple_billingkey" => null
                ]);

                DB::table('seller_infor')->where('uid', Auth::guard('api')->id())->update([
                    "confirm" => 3,
                    "reject_reason" => "회원 탈퇴로 인한 정지"
                ]);

                DB::table("items")->where('seller_id', Auth::guard('api')->id())->update([
                    "delete_yn" => 1
                ]);

                $this->res['query'] = $updated;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;

            case 'secession_social':
                $updated = DB::table('users')->where('id', Auth::guard('api')->id())->update([
                    "register_kind" => 0,
                    "email" => $user->email.'('.time( ).'/'.$user->register_type?$user->register_type:'donggle'.')',
                    "name" => '탈퇴회원자('.$user->id.')',
                    "password" => '',
                    "profile_img" => null,
                    "mobile_number" => null,
                    "gender" => null,
                    "birthday" => null,
                    "post_num" => null,
                    "address" => null,
                    "extra_address" => null,
                    "addr_jibeon" => null,
                    "ad_agree" => 0,
                    "account_bank" => null,
                    "account_number" => null,
                    "account_name" => null,
                    "wear_size" => null,
                    "sms_notify" => 0,
                    "register_type" => null,
                    "email_notify" => 0,
                    "updated_at" => now()
                ]);

                DB::table('seller_infor')->where('uid', Auth::guard('api')->id())->update([
                    "confirm" => 3,
                    "reject_reason" => "회원 탈퇴로 인한 정지"
                ]);

                DB::table("items")->where('seller_id', Auth::guard('api')->id())->update([
                    "delete_yn" => 1
                ]);

                $this->res['query'] = $updated;
                $this->res['msg'] = "성공!";
                $this->res['state'] = config('res_code.OK');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
