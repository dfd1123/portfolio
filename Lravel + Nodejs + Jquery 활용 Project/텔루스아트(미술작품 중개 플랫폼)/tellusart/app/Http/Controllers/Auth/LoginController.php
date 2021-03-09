<?php

namespace TLCfund\Http\Controllers\Auth;

use Jenssegers\Agent\Agent;

use TLCfund\User;
use TLCfund\Address;
use TLCfund\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Database\Eloquent\Model;
use Facades\App\Classes\EthApi;
use Illuminate\Http\Request;
use Socialite;
use Illuminate\Support\Facades\Auth;
use URL;
use DB;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->title = '로그인';
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $agent = new Agent();

        $device = ($agent->isDesktop()) ? 'pc' : 'mobile';

        $views = view("$device.auth.login");
        $views->title = $this->title;

        return $views;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
		
		$this->register_kind($request);
		
		$this->create_address($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        
        $request->session()->flash('jsAlert', '아이디나 비밀번호가 잘못되었습니다.');
        return $this->sendFailedLoginResponse($request);
    }

    protected function register_kind(Request $request)
	{
		$cs_user = User::where('email',$request->input('email'))->first();
		if($cs_user == NULL){
			return redirect()->route('login')->with('jsAlert','존재하지 않는 계정입니다.');
		}
		if($cs_user->count() > 0){
			if($cs_user->register_kind != 'tellusart'){
				
                $request->session()->regenerate();
                
                $this->clearLoginAttempts($request);
				
				return redirect()->route('login')->with('jsAlert','다른 방법으로 가입된 계정이 존재합니다.');
			}	
		}
    }
    
    protected function create_address(Request $request)
	{
		$cs_user = User::where('email',$request->input('email'))->first();
		if($cs_user != NULL){
			//주소 유무
			$address_cnt = Address::where('user_email',$cs_user->email)->count();
			if($address_cnt == 0){
                $getnewaddress_tlc = EthApi::newAddress($cs_user->email);
				Address::create([
                    'user_id' => $cs_user->id,
                    'user_email' => $cs_user->email,
                    'address_tlc' => $getnewaddress_tlc
                ]);
			}
		}
	}
    
    public function adm_login(Request $request)
    {
        $password = $request->input('password');
    
        if (Auth::attempt([ 'email' => $request->input('email'), 'password' => $password ])) {
            return redirect()->route('admin_home');
        } else {
            return redirect()->route('admin_login');
        }
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        session(['uemail' => $user->email]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
		
		$redirect_url = '/';
		
		if(strpos(URL::previous(),"/adm")  !== false ){
			$redirect_url = '/adm/login';
		}
		
		//dd($redirect_url);

        return $this->loggedOut($request) ?: redirect($redirect_url);
    }
    
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $random_pswd = str_random(10);
        
        $user = Socialite::driver('github')->user();
        
        $user = User::firstOrCreate([
            'name'  => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make($random_pswd),
            'nickname' => $user->getName(),
        ]);

        auth()->login($user, true);

        return redirect(route('home'));
    }
}
