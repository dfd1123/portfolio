<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Facades\App\Classes\Secure;
use Facades\App\Classes\Settings;
use DB;
use Hash;
use App;

class PassportController extends Controller
{
    public function register(Request $request)
    {
        if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
            $protocol = 'https://';
            if (App::environment(['local'])) {
                $protocol = 'http://';
            }

            $url = $protocol.$_SERVER["HTTP_HOST"]."/";

            if($url === 'http://trustorn.local/'){
                $url = 'https://wallet.trustorn.com/';
            }
        }else{
            $url = env('APP_URL')."/";
        }

        $setting = DB::table('btc_settings')->where('url', $url)->first();
        $market_type = $setting->id;

        $temp_arr = explode('@', $request->email);
        $username = $temp_arr[0].'_'.time().str_random(20);

        $user =  User::create([
            'username' => $username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'country' => $request->country,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'time_signup' => date("Y-m-d H:i:s"),
            'referral_id' => null,
            'market_type' => $market_type,
            'secret_key' => hash("sha256", $request->secret_key),
            'email_verified_at' => date('Y-m-d H:i:s'),
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

        $this->sweep_access_tokens($user->id);
        $token = $user->createToken('user')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function register_existing(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!empty($request->secret_key) && auth()->attempt($credentials)) {
            User::where('id', auth()->user()->id)->update([
                'secret_key' => hash("sha256", $request->secret_key),
            ]);

            $user = User::where('id', auth()->user()->id)->first();
            $this->sweep_access_tokens(auth()->user()->id);
            $token = $user->createToken('user')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Reset Password Needed Or UnAuthorised'], 422);
        }
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $user = DB::table('users')->select('secret_key')->where('id', auth()->user()->id)->first();
            if(empty($user->secret_key)) {
                return response()->json(['error' => 'Register Required'], 401);
            } else {
                $this->sweep_access_tokens(auth()->user()->id);
                $token = auth()->user()->createToken('user')->accessToken;
                return response()->json(['token' => $token], 200);
            }
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    public function refresh(Request $request)
    {
        $this->sweep_access_tokens(auth()->user()->id);
        $token = auth()->user()->createToken('user')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function detail()
    {
        $user = User::select(
                'id',
                'country',
                'created_at',
                'email',
                'fullname',
                'level',
                'market_type',
                'mobile_number',
                'status',
                'username'
            )
            ->where('id', auth()->user()->id)
            ->first();

        $security = Secure::info();

        if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
            $protocol = 'https://';
            if (App::environment(['local'])) {
                $protocol = 'http://';
            }

            $url = $protocol.$_SERVER["HTTP_HOST"]."/";

            if($url === 'http://trustorn.local/'){
                $url = 'https://wallet.trustorn.com/';
            }
        }else{
            $url = env('APP_URL')."/";
        }

        $setting = DB::table('btc_settings')->where('url', $url)->first();

        $response = [
            'user' => $user,
            'security' =>  [
                'status' => Secure::secure_short_verified(),
                'account_bank' => $security->account_bank,
                'account_num' => $security->account_num,
            ],
            'tru_per_eth' => $setting->tru_per_eth
        ];

        return response()->json($response);
    }

    public function detail_update(Request $request)
    {
        // 값 검증
        if($request->has('password_old')) {
            $password_old_len = strlen($request->password_old);
            if($password_old_len < 8 || $password_old_len > 15) {
                return response()->json([
                    'code' => '-1',
                    'message' => 'invalid password'
                ], 422);
            }
        }

        if($request->has('password_new')) {
            $password_new_len = strlen($request->password_new);
            if($password_new_len < 8 || $password_new_len > 15) {
                return response()->json([
                    'code' => '-1',
                    'message' => 'invalid password'
                ], 422);
            }
        }

        if($request->has('secret_key')) {
            $secret_key_len = strlen($request->secret_key);
            if($secret_key_len != 6) {
                return response()->json([
                    'code' => '-2',
                    'message' => 'invalid secret key'
                ], 422);
            }
        }

        if($request->has('push_token')) {
            if($request->push_token == '') {
                return response()->json([
                    'code' => '-3',
                    'message' => 'invalid push token'
                ], 422);
            }
        }

        $user = User::where('id', auth()->user()->id)->first();

        // 비밀번호 업데이트
        if($request->has('password_old') && $request->has('password_new') && Hash::check($request->password_old, $user->password)) {
            User::where('id', auth()->user()->id)->update([
                'password' => Hash::make($request->password_new),
            ]);
        }

        // 보안코드 업데이트
        if($request->has('secret_key')){
            User::where('id', auth()->user()->id)->update([
                'secret_key' => hash("sha256", $request->secret_key)
            ]);
        }

        // 푸시토큰 업데이트
        if($request->has('push_token')){
            User::where('id', auth()->user()->id)->update([
                'push_token' => $request->push_token
            ]);
        }

        return response()->json(null, 200);
    }

    public function detail_confirm_secret(Request $request) {
        // 값 검증
        if($request->has('secret_key')) {
            $secret_key_len = strlen($request->secret_key);
            if($secret_key_len != 6) {
                return response()->json([
                    'code' => '-1',
                    'message' => 'invalid secret key'
                ], 422);
            }
        }

        $user = User::where('id', auth()->user()->id)->first();

        // 비밀번호 체크
        if($request->has('secret_key') && ($user->secret_key == hash("sha256", $request->secret_key))){
            return response()->json(null, 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(null, 204);
    }

    public function heartbeat() {
        return response()->json(true);
    }

    private function sweep_access_tokens($user_id){
        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->skip(2)
            ->take(PHP_INT_MAX) // 나머지 row 전부 가져오기
            ->get();

        $ids = [];
        foreach($tokens as $token){
            $ids[] = $token->id;
        }

        DB::table('oauth_access_tokens')->whereIn('id', $ids)->delete();
    }
}
