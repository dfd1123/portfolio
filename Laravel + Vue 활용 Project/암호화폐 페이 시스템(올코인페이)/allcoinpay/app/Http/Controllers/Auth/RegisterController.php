<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;

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
    protected $redirectTo = '/company';

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
            
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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

        $user = User::create([
            'username' => $username,
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'country' => 'kr',
            'password' => Hash::make($data['password']),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'time_signup' => date("Y-m-d H:i:s"),
            'email_verified_at' => date("Y-m-d H:i:s"),
            'mobile_number' => $data['mobile_number'],
            'referral_id' => $data['referral_id'],
            'market_type' => Session('market_type'),
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
			"email_verified" => 1,
            "mobile_verified" => 1,
        ]);
		
		return $user;
    }
}
