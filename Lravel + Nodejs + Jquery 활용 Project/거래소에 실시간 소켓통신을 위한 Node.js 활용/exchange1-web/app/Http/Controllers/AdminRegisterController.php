<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class AdminRegisterController extends Controller
{

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/register_complete';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
      public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
    	
		$level = 5; // 관리자 기본 level
		
    	if(isset($data['title']) || $data['title'] != NULL){
    		DB::table('btc_settings')->insert([
    			'title' => $data['title'],
    			'description' => $data['description'],
    			'keywords' => $data['keywords'],
    			'infoemail' => $data['infoemail'],
    			'supportemail' => $data['supportemail'],
    			'url' => $data['url'],
    			'url_trade' => $data['url'],
    			'url_support' => $data['url_support'],
    			'url_io' => 'https://io.sharebits.info/',
    			'url_contents' => $data['url'],
    		]);
			
			$btc_settings = DB::table('btc_settings')->orderBy('id','desc')->first();
			
			$market_type = $btc_settings->id;
			
			$level = 10;
    	}else{
    		$market_type = $$data['market_type'];
    	}
		

		
        $admin =  Admin::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'fullname' => $data['fullname'],
            'mobile_number' => $data['mobile_number'],
            'market_type' => $market_type,
            'level' => $level,
            'time_signup' =>  date("Y-m-d H:i:s"),
            'ip' => $_SERVER['REMOTE_ADDR'],
		]);
		
		
		return $admin;
    }

    public function showRegistrationForm(Request $request)
    {
    	$views = view('admin.auth.register');
		$views->register_agree3 = $request->input('register_agree3');
		
		if($request->input('register_agree1') != NULL){
			if($request->input('register_agree1') != 1 || $request->input('register_agree2') != 1){
				return redirect()->route('admin.register_agree')->with('jsAlert','가입 필수 동의를 하지 않으셨습니다.');
			}else{
				return $views;	
			}
		}else{
			return redirect()->route('admin.register_agree');
		}
		
		return $views;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);
		
		return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
	}

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
