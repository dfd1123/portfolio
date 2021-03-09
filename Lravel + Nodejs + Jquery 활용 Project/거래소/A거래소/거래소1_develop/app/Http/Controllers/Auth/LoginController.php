<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Jenssegers\Agent\Agent;

use Facades\App\Classes\LoginTrace;
use AirDrop;
use Auth;
use DB;
use PragmaRX\Google2FA\Google2FA;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function showLoginForm()
    {
        $views = view(session('theme').'.'.$this->device.'.'.'auth.login');

        return $views;
    }

    protected function authenticated(Request $request, $user)
    {
        if($request->filled('secret')){
            $secret = $request->input('secret');
            if(empty($secret)) {
                return redirect('/otp')
                ->with('request',$request->all())
                ->with('jsAlert','otp 번호를 입력해주세요.');
            }

            $google2fa_secret = DB::table('btc_security_lv')->where('uid', $user->id)->first()->google_pin;
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($google2fa_secret, $secret, 2);
            
            if($valid) {
                
            } else {
                return redirect('/otp')
                ->with('request',$request->all())
                ->with('jsAlert','otp 번호가 틀렸습니다. 다시 입력해 주세요.');
            }
        }else{
            $security_google = DB::table('btc_security_lv')->where('uid',$user->id)->first();

            if(isset($security_google->google_verified)){
                if($security_google->google_verified == 1){
                Auth::logout();
                return redirect('/otp')
                ->with('request',$request->all());
                }
            }
        }
        //LoginTrace::trace($request);
    }

    public function login(Request $request)
    {
        $status = DB::table('users')->where('email','like','%'.$request->email.'%')->first();
        if(isset($status)){
            if($status->status == 3){
                return redirect('/login')->with('jsAlert', '탈퇴한 계정입니다.');
            }
        }

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            
            if($this->device == 'mobile'){
                if(isset($request->push_token) && $request->push_token != '' && $request->push_token != null){
                	DB::table('users')->where('id',Auth::id())->update([
                		"push_token" => $request->push_token,
					]);
				}
            }
            LoginTrace::trace($request);
            AirDrop::drop(Auth::id(), 'everybody');
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
}
