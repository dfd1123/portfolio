<?php

namespace TLCfund\Http\Controllers\Auth;

use Jenssegers\Agent\Agent;

use TLCfund\User;
use TLCfund\Address;
use TLCfund\Contents;
use TLCfund\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Facades\App\Classes\EthApi;
use Facades\App\Classes\NiceCheck;
use Socialite;


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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['checkplus_main', 'checkplus_success', 'checkplus_fail']]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $nice_info = NiceCheck::NiceCheck_main();
        $agent = new Agent();

        $device = ($agent->isDesktop()) ? 'pc' : 'mobile';

        $views = view("$device.auth.register");
        
        $privacy = Contents::where('title','개인정보취급방침')->first();

        $policy = Contents::where('title','서비스 이용약관')->first();
        
        $views->privacy = $privacy;
        $views->policy = $policy;
        $views->title = '회원가입';

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
    	//dd($data['email_certify']);
		
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \TLCfund\User
     */
    protected function create(array $data)
    {
        $getnewaddress_tlc  = EthApi::newAddress($data['email']);
		
		if(!isset($data['agree4'])){
			$data['agree4'] = 0;
		}
		
		User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nickname' => $data['nickname'],
            'mobile_number' => str_replace("-","", $data['mobile']),
            'post_num' => $data['post_num'],
            'addr1' => $data['address1'],
            'addr2' => $data['address2'],
            'extra_addr' => $data['extra_addr'],
            'ad_agree' => $data['agree4'],
            //'address' => $data['address1'].$data['address2'],
        ]);
        
        $user_info = User::latest()->first();
		Address::create([
            'user_id' => $user_info->id,
            'user_email' => $data['email'],
            'address_tlc' => $getnewaddress_tlc
        ]);
        return $user_info;
    }

    public function checkplus_main(Request $request){
        $nice_info = NiceCheck::NiceCheck_main();

        return response()->json(['enc_data' => $nice_info['enc_data']]);
    }

    public function checkplus_success(Request $request){
        $agent = new Agent();

        $device = ($agent->isDesktop()) ? 'pc' : 'mobile';

		$nice_info = NiceCheck::NiceCheck_success();
		if(is_array($nice_info)){
			$message = $nice_info['returnMsg'];
			$mobile_number = $nice_info['mobileno'];
			$name = $nice_info['name'];
			$name_utf8 = rawurldecode($nice_info['name_utf8']);
			$status = 1;
		}else{
			$message = $nice_info;
			$mobile_number = '';
			$name = '';
			$name_utf8 = '';
			$status = 0;
		}
		$views = view($device.'.auth.nicecheck_return');
		
		$views->message = $message;
		$views->name = $name;
		$views->name_utf8 = $name_utf8;
		$views->mobile_number = $mobile_number;
        $views->status = $status;
        $views->title = 'NICECHECK 인증 페이지';
		
		return $views;
	}
	
	public function checkplus_fail(Request $request){
        $agent = new Agent();

        $device = ($agent->isDesktop()) ? 'pc' : 'mobile';

		$nice_info = NiceCheck::NiceCheck_fail();
		
		$status = 0;
		$message = $nice_info;	
		
		$views = view($device.'.auth.nicecheck_return');
		
		$views->message = $message;
        $views->name = '';
        $views->name_utf8 = '';
		$views->mobile_number = '';
		$views->status = $status;
		
		return $views;
    }
	
}