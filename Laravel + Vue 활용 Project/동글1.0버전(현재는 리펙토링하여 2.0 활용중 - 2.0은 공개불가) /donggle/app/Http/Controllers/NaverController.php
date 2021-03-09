<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Str;
use Auth;
use DB;
use Socialite;

class NaverController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        session_start();
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        $_SESSION['originalURL'] = $request->url;
        return Socialite::with('naver')->redirect();
    }

    
    public function handleProviderCallback()
    {
        session_start();
		$random_pswd = bcrypt(Str::random(12));
        $naverInfo = Socialite::driver('naver')->stateless()->user();
        $user = User::where('email',$naverInfo->email)->first();
        $originalURL = $_SESSION['originalURL'];

        if($user == NULL){
			$user_cnt = 0;
		}else{
			$user_cnt = 1;
        }
        
        if($user_cnt > 0){
			if($user->register_type !== 'naver'){
                return redirect($originalURL.'/oauth/register?register_type=already');
            }

            if (! $token = Auth::guard('api')->attempt(['email' => $naverInfo->email, 'password' => 'awcjckl;awmk12k13'])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            return redirect($originalURL.'/oauth/login?email='.$naverInfo->email.'&access_token='.$token.'&register_type=naver');

		}else{

            if($naverInfo->user['gender'] === 'M'){
                $gender = 'man';
            }else{
                $gender = 'woman';
            }

            $nickname = $naverInfo->nickname?$naverInfo->nickname:$naverInfo->name;

            return redirect($originalURL.'/oauth/register?name='.$naverInfo->name.'&email='.$naverInfo->email.'&profile_img='.$naverInfo->avatar.'&nickname='.$nickname.'&register_kind=1&register_type=kakao');
        }
    }
}
