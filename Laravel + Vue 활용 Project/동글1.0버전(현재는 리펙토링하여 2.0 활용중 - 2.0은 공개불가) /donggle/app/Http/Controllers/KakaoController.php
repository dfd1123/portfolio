<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Str;
use Auth;
use DB;
use Socialite;

class KakaoController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        session_start();
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        $_SESSION['originalURL'] = $request->url;
        return Socialite::with('kakao')->redirect();
    }

    
    public function handleProviderCallback()
    {
        session_start();
		$random_pswd = bcrypt(Str::random(12));
        $kakaoInfo = Socialite::driver('kakao')->stateless()->user();
        $user = User::where('email',$kakaoInfo->email)->first();
        $originalURL = $_SESSION['originalURL'];

        if($user == NULL){
			$user_cnt = 0;
		}else{
			$user_cnt = 1;
        }
        
        if($user_cnt > 0){
			if($user->register_type !== 'kakao'){
                return redirect($originalURL.'/oauth/register?register_type=already');
            }

            if (! $token = Auth::guard('api')->attempt(['email' => $kakaoInfo->email, 'password' => 'awcjckl;awmk12k13'])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            return redirect($originalURL.'/oauth/login?email='.$kakaoInfo->email.'&access_token='.$token.'&register_type=kakao');
		}else{
            $nickname = $kakaoInfo->nickname?$kakaoInfo->nickname:$kakaoInfo->name;

            return redirect($originalURL.'/oauth/register?name='.$kakaoInfo->name.'&email='.$kakaoInfo->email.'&profile_img='.$kakaoInfo->avatar.'&nickname='.$nickname.'&register_kind=1&register_type=kakao');
        }
    }

    public function secession(Request $request){
        $user = DB::table('users')->where('id', $request->user_id)->first();

        if($user){
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

        if($updated){
            return response()->json(['status'=>'200 OK']);
        }else{
            return response()->json(['status'=>'fail']);
        }
        }else{
            return response()->json(['status'=>'200 OK']);
        }

    }
}
