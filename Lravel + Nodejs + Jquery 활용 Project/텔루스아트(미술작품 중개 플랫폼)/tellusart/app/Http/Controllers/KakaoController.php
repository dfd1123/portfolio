<?php

namespace TLCfund\Http\Controllers;

use TLCfund\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use TLCfund\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Socialite;

class KakaoController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::with('kakao')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
		
    	$user = Socialite::driver('kakao')->user();
			
		$user = User::firstOrCreate([
            'name'  => $user['properties']['nickname'],
            'email' => (rand()*1).date('y',time())."@tellusart.com", //array_get($user, 'kakao_account.email')
            'password' => '', 
            'nickname' => $user['properties']['nickname'],   
        ]);

        auth()->login($user, true);

        return redirect(route('home'));
    }
}
