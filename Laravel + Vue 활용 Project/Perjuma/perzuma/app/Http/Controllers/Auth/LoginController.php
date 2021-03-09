<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Cookie;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/main';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function showLoginForm(Request $request)
    {
        if($request->filled('expire')){
            Cookie::queue(Cookie::forget('pass_cookie'));
            Cookie::queue(Cookie::forget('type_cookie'));
        }else{
            if($request->cookie('pass_cookie') && $request->cookie('type_cookie')){
                return redirect('/user_ver');
            }
        }
        return view('auth.login');
    }

    public function username()
    {
        return 'email';
    }
}
