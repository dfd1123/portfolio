<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Utils\JWT;
use App\Http\Utils\Email;
use Mail;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function show(Request $request, $req='def'){

        switch($req){
            case 'navercallback':
            return $this->doNaverLogin($request);

            case 'naver':
            break;
            case 'kakao':
            break;
            case 'facebook':
            break;
            default:
            break;
        }
    }
	
	public function check_naver_login(Request $request){
		$p = $request->all();
		
		//이메일이 있는경우 가입되어있는지 확인
        $sql = "SELECT id, password FROM users WHERE email = :email;";
		$check_email = DB::select($sql,array('email'=>$p['user_email']));
		$check_login = "error";
		if(isset($check_email[0])){
			if (isset($check_email[0]->password) && Hash::check($p['uniqid'], $check_email[0]->password)) {
				//카카오 회원가입자확인됨, 로그인 시키기
				$check_login = "success";
			}else{
				//일반회원가입자임 로그인페이지로 돌려보내야됨
				$check_login = "not-naver";
			}
		}else{
			//가입안되어잇다면 INSERT로 가입진행
			$check_login = "not-register";
		}
		
		return response(json_encode($check_login), 200)->header('Content-Type', 'application/json');
	}

    public function store(Request $request)
    {
        $p = $request->all();
        $tokens = array('refresh_token' => null,'access_token' => null);
        
        if($request->filled('email','password') 
        && $this->checkLength($p['email'], 8,64)
        && $this->checkLength($p['password'], 6,16)){
            //아이디, 비밀번호 체크
            $check_login = $this->checkLogin($p['email'], $p['password']);
            if($check_login){
            	$check_state = $this->checkState($p['email'], $p['password']);
                if (isset($p['push_token']) && $p['push_token'] != '' && $p['push_token'] != null) {
                    $this->update_push_token($check_login, $p['push_token']);
                }

                $JWTObject=  JWT::get_instance();

                $iss_tkn = $JWTObject->iss_tkn($check_login);

                $r_token = $iss_tkn['refresh_token'];
                $a_token = $iss_tkn['access_token'];
            
                $r_token  = urlencode($r_token);
                $a_token  = urlencode($a_token);

                
                
                setrawcookie('user_id',  rawurldecode($p['email']),time()+3600*24*90,'/');
                setrawcookie('user_pwd', $p['password'],time()+3600*24*90,'/');

                //$token = $JWTObject->iss_tkn($check_login);
                //API용 응답
                $tokens['refresh_token'] = $r_token;
                $tokens['access_token'] = $a_token;
                $this->res['query'] = $tokens;
                $this->res['state'] =1;
				$this->res['user_state'] = $check_state;
            }else{
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.NO_DATA');
                $this->res['msg'] = '해당 이메일과 비밀번호에 일치하는 계정이 없거나<br> 탈퇴한 계정입니다.';
            }
        }else{
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '이메일이나 비밀번호를 입력해주세요.<br>비밀번호는 6~16자리입니다.';
        }
        return response(json_encode($this->res), 200)
        ->header('Content-Type', 'application/json')
        //브라우저용 응답
        ->cookie('Authorization', $tokens['access_token'])
        ->cookie('Refresh', $tokens['refresh_token']);
    }

    public function refreshTkn(Request $request){
        $JWTObject=  JWT::get_instance();

        $riss_tkn =  $JWTObject->refresh_tkn($request);

        //echo $riss_tkn;
        $riss_tkn = urlencode($riss_tkn);
        $this->res['query']  = $riss_tkn;
        
        return response(json_encode($this->res), 200)
        ->header('Content-Type', 'application/json')
        //브라우저용 응답
        ->cookie('Authorization', $riss_tkn);

    }

    //계정존재여부확인
    private function checkLogin($email, $password)
    {
        
        $sql = "SELECT id, password
                FROM users 
                WHERE email = :email AND (state = 1 OR state = 2)";

        $params = array('email'=> $email );
        
        $this->res= $this->execute_query($sql, $params);

        if (isset($this->res['query'][0]->password) && Hash::check($password, $this->res['query'][0]->password)) {
            return $this->res['query'][0]->id;
        }else{
            return false;
        }
    }
	//계정상태 체크(0: 비활성화 1:활성화 2:전화번호 미인증)
	private function checkState($email, $password)
    {
        
        $sql = "SELECT id, password, state
                FROM users 
                WHERE email = :email";

        $params = array('email'=> $email );
        
        $this->res= $this->execute_query($sql, $params);

        if (isset($this->res['query'][0]->password) && Hash::check($password, $this->res['query'][0]->password)) {
            return $this->res['query'][0]->state;
        }else{
            return false;
        }
    }
	
    //FCM토큰업데이트
    private function update_push_token($user_id, $push_token)
    {
        
        $sql = "UPDATE users
                SET fcm_token = :push_token
                WHERE id = :user_id";

        $params = array('user_id'=> $user_id, 'push_token' => $push_token);
        
        $this->res= $this->execute_query($sql, $params, 'update');
    }

    public function decodetoken(Request $request)
    {
		$JWTObject=  JWT::get_instance();

        $token = $request->cookie('Authorization');
        if ($request->headers->has('Authorization')) {
            $token = $request->header('Authorization');
        }
        $decodejwt = $JWTObject->decode_tkn($token,config('constant.JWT_SECRET_A_KEY'));
		return $decodejwt;        
    }

    public function logout(Request $request)
    {
        setcookie('Authorization', '', time(), '/');
        setcookie('Refresh', '', time(), '/');

        return response(json_encode(true), 200)->header('Content-Type', 'application/json');
    }

    public function password_find_link(Request $request)
    {
        $user_email = $request->email;
        $user = DB::table('users')->where('email', $user_email)->first();
        if ($user === null) {
            return response(json_encode("reg-no"), 200)->header('Content-Type', 'application/json');
        }else{
            if($user->reg_type == 'naver'){
                return response(json_encode("reg-naver"), 200)->header('Content-Type', 'application/json');
            }else if($user->reg_type == 'kakao'){
                return response(json_encode("reg-kakao"), 200)->header('Content-Type', 'application/json');
            }
        }
        
        $verify_code = $this->generateRandomString(128);
        
        $res = DB::insert("
            WITH upsert AS
            (
                UPDATE
                    password_resets
                SET
                    token = :verify_code,
                    created_at = now()
                WHERE 1 = 1
                    AND email = :user_email
                RETURNING email
            )
            INSERT INTO password_resets (
                email,
                token,
                created_at
            )
            SELECT
                :user_email,
                :verify_code,
                now()
            WHERE NOT EXISTS (
                SELECT
                    email
                FROM upsert
            )
            ", [
                'user_email' => $user_email,
                'verify_code' => $verify_code
            ]);
        $url = env('APP_URL')."/settingpw";
        $data = array(
                'title' => '[TRIPICK] 트리픽 비밀번호 찾기 인증 메일입니다.',
                'content' => [
                    'message_title' => '비밀번호 찾기',
                    'email' => $user_email,
                    'name' => $user->name,
                    'message' => '트리픽 비밀번호 찾기 이메일 인증 안내 메일입니다.<br>
                    이메일 인증이 완료되어야 비밀번호 찾기가 가능합니다.<br>
                    아래버튼을 클릭하셔서 이메일 인증을 완료해주세요.<br>',
                    'verify_link' => $url.'?verify=' . base64_encode($verify_code)
                ]
            );
        Mail::to($user_email)->send(new Email($data));
        return response(json_encode("reg-app"), 200)->header('Content-Type', 'application/json');
    }

    public function password_change(Request $request)
    {
        $p = $request->all();
        
        if($request->filled('n_password','c_password') 
        && $this->checkLength($p['n_password'], 6,16)
        && $this->checkLength($p['c_password'], 6,16)){
            if($p['n_password'] === $p['c_password']){
                $sql = "UPDATE users
                        SET updated_at = now(),
                            password = :password
                        WHERE email = :email";

                $params = array('password'=> Hash::make($p['n_password']), 'email'=> $p['email'] );
                
                $this->res= $this->execute_query($sql, $params);
            }else{
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '2개의 비밀번호가 일치하지 않습니다. 확인 후 다시 시도해주세요.';
            }
        }else{
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '비밀번호는 6~16자리로 입력해주세요.';
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
