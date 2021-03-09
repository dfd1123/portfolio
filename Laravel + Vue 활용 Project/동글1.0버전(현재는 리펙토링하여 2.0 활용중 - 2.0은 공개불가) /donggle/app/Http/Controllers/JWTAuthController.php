<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

use Facades\App\Classes\File_store;

use Auth;
use JWTAuth;
use DB;

class JWTAuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users',
        ]);

        if(DB::table('users')->where('email', $request->email)->exists()){
            $this->res['query'] = null;
            $this->res['msg'] = '존재하는 이메일 주소로 가입을 시도하셔서 가입에 실패하셨습니다.';
            $this->res['state'] = config('res_code.PARAM_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $password = $request->password;
        $password_c = $request->password_confirm;

        if($password !== $password_c){
            $this->res['query'] = null;
            $this->res['msg'] = "비밀번호 다름!";
            $this->res['state'] = config('res_code.PWD_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if($validator->fails()) {
            $this->res['query'] = null;
            $this->res['msg'] = $validator->messages();
            $this->res['state'] = config('res_code.PARAM_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $insert = DB::table('users')->insert([
            'name'  => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => bcrypt($request->password), 
            'nickname' => $request->nickname,
            'gender' => $request->filled('gender')?$request->gender:NULL,
            'birthday' => $request->filled('birthday')?$request->birthday:NULL,
            'wear_size' => $request->filled('wear_size')?$request->wear_size:NULL,
            'ad_agree' => $request->ad_agree,
            'level' => 1
        ]);

        if (! $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $user = Auth::guard('api')->user();
        unset($user->password);

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'data' => $user
        ], 200);
    }

    public function social_register(Request $request){
        if(!$request->filled('name', 'nickname', 'email', 'mobile_number', 'ad_agree', 'register_type')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if(DB::table('users')->orWhere('email', $request->email)->orWhere('nickname', $request->nickname)->exists()){
            $this->res['query'] = null;
            $this->res['msg'] = '존재하는 이메일 주소이거나 닉네임이라 가입에 실패하셨습니다.';
            $this->res['state'] = config('res_code.PARAM_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        if($request->profile_img){
            $profile_img = array();
            array_push($profile_img, $request->profile_img);
            $profile_img = json_encode($profile_img);
        }else{
            $profile_img = null;
        }

        $insert = DB::table('users')->insert([
            'name'  => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'profile_img'=>$profile_img,
            'password' => bcrypt('awcjckl;awmk12k13'), 
            'nickname' => $request->nickname,
            'gender' => $request->filled('gender')?$request->gender:NULL,
            'wear_size' => $request->filled('wear_size')?$request->wear_size:NULL,
            'ad_agree' => $request->ad_agree,
            'level' => 1,
            'register_type' => $request->register_type
        ]);
        
        if($insert){
            if (! $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => 'awcjckl;awmk12k13'])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = Auth::guard('api')->user();
            unset($user->password);

            return response()->json([
                'status' => 'success',
                'access_token' => $token,
                'data' => $user
            ], 200);
        }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);
    
        if($validator->fails()) {
            $this->res['query'] = null;
            $this->res['msg'] = $validator->messages();
            $this->res['state'] = config('res_code.PARAM_ERR');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        //Auth::guard('api')->factory()->setTTL(60 * 24 * 6);
        if (! $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if($request->filled('native_token')){
            DB::table('users')->where('email', $request->email)->update([
                "native_token" => $request->native_token
            ]);
        }
    
        return $this->respondWithToken($token);
    }

    public function info_change(Request $request){
        if(!Auth::guard('api')->check()){
            $this->res['query'] = null;
            $this->res['msg'] = '인증 권한이 없습니다.';
            $this->res['state'] = config('res_code.NO_AUTH');

            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $update = false;

        $user = $request->user;

        $mobile_number = $user['mobile_number'];
        $nickname = $user['nickname'];
        $gender = $user['gender'];
        $birthday = $user['birthday'];
        $wear_size = $user['wear_size'];
        $sms_notify = $user['sms_notify'];
        $email_notify = $user['email_notify'];

        if($request->filled('nickname')){
            $nickname = $request->nickname;
        }

        if($request->filled('mobile_number')){
            $mobile_number = $request->mobile_number;
        }

        if($request->filled('nickname')){
            $nickname = $request->nickname;
        }

        if($request->filled('gender')){
            $gender = $request->gender;
        }

        if($request->filled('birthday')){
            $birthday = $request->birthday;
        }

        if($request->filled('wear_size')){
            $wear_size = $request->wear_size;
        }

        if($request->filled('sms_notify')){
            $sms_notify = $request->sms_notify;
        }

        if($request->filled('email_notify')){
            $email_notify = $request->email_notify;
        }

        if($request->filled('password')){
            if($request->password !== $request->password_confirm){
                $this->res['query'] = null;
                $this->res['msg'] = '비밀번호가 불일치 합니다.';
                $this->res['state'] = config('res_code.PWD_ERR');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }else{
                $password = bcrypt($request->password);

                $update = DB::table('users')->where('id', Auth::guard('api')->id())->update([
                    "nickname" => $nickname,
                    "password" => $password,
                    "mobile_number" => $mobile_number,
                    "gender" => $gender,
                    "birthday" => $birthday,
                    "wear_size" => $wear_size,
                    "sms_notify" => $sms_notify,
                    "email_notify" => $email_notify
                ]);
            }
        }else{
            $update = DB::table('users')->where('id', Auth::guard('api')->id())->update([
                "nickname" => $nickname,
                "mobile_number" => $mobile_number,
                "gender" => $gender,
                "birthday" => $birthday,
                "wear_size" => $wear_size,
                "sms_notify" => $sms_notify,
                "email_notify" => $email_notify
            ]);
        }

        if($update){
            $user = DB::table('users')->where('id', Auth::guard('api')->id())->first();
            $user->password = null;
            $this->res['query'] = $user;
            $this->res['msg'] = '성공!';
            $this->res['state'] = config('res_code.OK');
        }else{
            $this->res['query'] = $update;
            $this->res['msg'] = '회원정보 변경 실패';
            $this->res['state'] = config('res_code.QUERY_ERR');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function ProfileImgChange(Request $request){
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

        $profile_img = File_store::Image_update_profile('user', $request->profile_img, json_decode($user->profile_img), array(0));
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
            
            $this->res['query'] = $query;
            $this->res['msg'] = "프로필사진 수정 완료!";
            $this->res['state'] = config('res_code.OK');
            
        } catch(exception $e) {
            $this->res['query'] =null;
            $this->res['msg'] = "시스템 에러(쿼리)";
            $this->res['state'] = config('res_code.QUERY_ERR');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    protected function respondWithToken($token) {
        $delivery_addrs = $this->delivery_load();
        
        return response()->json([
            'access_token' => $token,
            'user' => Auth::guard('api')->user(),
            'deliverys' => $delivery_addrs,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ])->cookie('access_token', $token, Auth::guard('api')->factory()->getTTL() * 60, '/', 'localhost:8080', false, false);
    }

    private function delivery_load(){
        $uid = Auth::guard('api')->id();
        $delivery_addrs = DB::table('frequen_delivery')
                        ->where('uid',$uid)
                        ->orderBy('delivery_index', 'asc')
                        ->get();

        for($i = 0; $i < 3; $i++){
            if(count($delivery_addrs) < $i + 1){
                $delivery_addrs[$i] = array(
                    "name" => null,
                    "phone_num" => null,
                    "addr1" => null,
                    "addr2" =>null,
                    "extra_addr" => null,
                    "addr_jibeon" => null,
                    "post_num" => null,
                    "delivery_index" => $i + 1
                );
            }
        }

        return $delivery_addrs;
    }

    public function user() {
        return response()->json(Auth::guard('api')->user());
    }

    public function refresh() {
        $token = JWTAuth::fromUser(Auth::guard('api')->user());
        return $this->respondWithToken($token);
        //return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    public function native_token(Request $request){
        if(!$request->filled('native_token')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }else{
            DB::table('users')->where('id', Auth::guard('api')->id())->update([
                "native_token" => $request->native_token
            ]);
        }

        $this->res['query'] = null;
        $this->res['msg'] = "success";
        $this->res['state'] = config('res_code.OK');

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function logout() {
        Auth::guard('api')->logout();
    
        return response()->json([
            'status' => 'success',
            'message' => 'logout'
        ], 200);
    }
}
