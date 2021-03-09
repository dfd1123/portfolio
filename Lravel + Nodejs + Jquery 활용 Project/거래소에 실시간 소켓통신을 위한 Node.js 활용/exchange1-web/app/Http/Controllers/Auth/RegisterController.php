<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jenssegers\Agent\Agent; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use DB;
use AirDrop;
use Settings;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register_complete';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
		$agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function showRegistrationForm(Request $request)
    {
    	$views = view(session('theme').'.'.$this->device.'.'.'auth.register');
        $views->register_agree3 = $request->input('register_agree3');
		
		if($request->input('register_agree1') == null){
			if($request->input('register_agree1') != 1 || $request->input('register_agree2') != 1){
				return redirect()->route('register_agree')->with('jsAlert','가입 필수 동의를 하지 않으셨습니다.');
			}else{
				return $views;	
			}
        }
        
        $setting = Settings::Settings();

        $views->recommend = $setting->recommender_yn;
		
		return $views;
    }

    public function mail_complete(){
        $views = view(session('theme').'.'.$this->device.'.'.'auth.verify_complete');

        return $views;
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
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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

        if($data['email_cv']==NULL || $data['email_cv']==0){
            return redirect()->route('register')->with('jsAlert','이메일 중복검사를 하지 않으셔서 처음으로 돌아갑니다.');
        }

        if(!isset($data['referral_id'])) {
            $data['referral_id'] = null;
        }

        $temp_arr = explode('@',$data['email']);

        $username = $temp_arr[0].'_'.time().str_random(20);

        $user =  User::create([
            'username' => $username,
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'country' => $data['country'],
            'password' => Hash::make($data['password']),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'time_signup' => date("Y-m-d H:i:s"),
            'referral_id' => $data['referral_id'],
            'market_type' => session('market_type'),
        ]);
		
		$time = time();

		
		DB::table('btc_users_addresses')->insert([
			"uid" => $user->id,
			"label" => $user->username,
			"status" => '1',
			"created" => $time,
			"updated" => $time,
		]);
		
		DB::table('btc_security_lv')->insert([
			"uid" => $user->id,
			"email_verified" => 0,
			"mobile_verified" => 0,
        ]);
        
        AirDrop::drop($user->id, 'register');

        $setting = Settings::Settings();

        if($setting->recommender_yn == 1){
            $refid = $data['referral_id'];
            $ref_user = DB::table('btc_users')->where('email',$refid)->first();
            if(!empty($ref_user)){
                DB::table('btc_users_addresses')->where('label', $ref_user->username)->increment('available_balance_usd',$setting->recommender_point);
            }
        }
		
		
		return $user;
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
                        ?: redirect('/register_complete');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
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
	
	public function register_agree(){
        $setting = Settings::Settings();
        $term = DB::connection('mysql_sub')->table('btc_term_service')->where('market_type', $setting->id)->first();
        $views = view(session('theme').'.'.$this->device.'.'.'auth.register_agree');
        $views->term = $term;
		
		return $views;
    }
	
	public function register_complete(){
    	$views = view(session('theme').'.'.$this->device.'.'.'auth.register_complete');
		
		return $views;
    }
}
