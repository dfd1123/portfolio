<?php

namespace TLCfund\Http\Controllers;

use TLCfund\User;
use TLCfund\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use TLCfund\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Facades\App\Classes\EthApi;
use Socialite;

class NaverController extends Controller
{
    //
    public function redirectToProvider()
    {
        return Socialite::with('naver')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
		$random_pswd = str_random(10);
    	$user = Socialite::driver('naver')->user();
		
		$user = User::where('email',$user['email'])->first();
		
		if($user == NULL){
			$user_cnt = 0;
		}else{
			$user_cnt = $user->count();
		}
		
		
		if($user_cnt > 0){
			
			if($user->register_kind != 'naver'){
				return redirect()->route('login')->with('jsAlert','이미 가입이 되어 있는 메일주소 입니다. ');
			}
		}else{
			$user = User::firstOrCreate([
				'name'  => $user['name'],
				'email' => $user['email'],
				'password' => $random_pswd, 
				'nickname' => $user['nickname'],
				'register_kind' => 'naver',   
			]);	
		}
		
		$address_cnt = Address::where('user_email',$user->email)->count();
		if($address_cnt == 0){
			$getnewaddress_tlc = EthApi::newAddress($user->email);
			
			Address::create([
				'user_id' => $user->id,
				'user_email' => $user->email,
				'address_tlc' => $getnewaddress_tlc
			]);
		}

        auth()->login($user, true);

        return redirect(route('home'));
    }
}
