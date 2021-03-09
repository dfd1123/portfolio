<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\VerifiesEmails;
use \Firebase\JWT\JWT;

use DB;
use Hash;
use Cookie;
use Mail;

class PassportController extends Controller
{

    use VerifiesEmails;


    public function register(Request $request)
    {
        /*$this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);*/
        $ver = $request->ver;
        $count = DB::table('users')->where('email',$request->email)->count();

        if($count == 0){
            if($ver == 'user_ver'){
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'user_grade' => 1,
                ]);
            }else{
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'user_grade' => 2,
                ]);
            }

            $jwt_token = $this->getToken($user);

            DB::table('users')->where('user_no', $user->user_no)->update([
                "remember_token" => $jwt_token,
            ]);
            
            /*
            $this->sweep_access_tokens($user->user_no);
            $token = $user->createToken('perzuma')->accessToken;
            $type = $this->getusertype($user->user_no);
            Cookie::queue('pass_cookie', $token, Carbon::now()->diffInMinutes(Carbon::now()->addMinutes(60 * 3)));
            Cookie::queue('type_cookie', $type, Carbon::now()->diffInMinutes(Carbon::now()->addMinutes(60 * 3)));
            return response()->json(['token' => $token,'type' =>$type], 200);
            */

            return response()->json(['status' => 1]);
        }
    }

    public function check_email(Request $request){
        $count = DB::table('users')->where('email',$request->email)->count();

        if( $count>0 ){
            return response()->json(['status' => 0]);
        }else{
            return response()->json(['status' => 1]);
        }
    }

    public function login(Request $request)
    {  
         
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        //type = 1:유저 페이지, 2:업체페이지,
        //3:업체정보등록st1, 4:업체정보등록st2, 5:업체정보등록st3, 6:업체정보등록st4, 7:업체정보등록st5
        
        if (auth()->attempt($credentials)) {

            if(auth()->user()->state == 0){
                auth()->logout();
                return response()->json(['not_verify' => true], 200);
            }
            else if(auth()->user()->state == 3){
                auth()->logout();
                return response()->json(['unregist_wait' => true], 200);
            }
            else if(auth()->user()->state == 4){
                auth()->logout();
                return response()->json(['unregist_complete' => true], 200);
            }
            
            $type = $this->getusertype(auth()->user()->user_no);
            $this->sweep_access_tokens(auth()->user()->user_no);
            $token = auth()->user()->createToken('perzuma')->accessToken;
            /*
            $client = new Client(['base_uri' => 'http://perzuma.local']);

            $response = $client->request('GET', '/api/user_as', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ],
            ]);
            */

            if($type ==2){
                $agentchk = $this->agentinfo_check(auth()->user()->user_no);
                if(!$agentchk){
                    $type=3;
                }
                else{
                    $check = $this->agentinfo_step(auth()->user()->user_no);
                    if(!isset(json_decode(json_decode($check)->extra_info)->bl_name)){
                        $type = 4;
                    }
                    else if(!isset(json_decode(json_decode($check)->extra_info)->agent_distance)){
                        $type = 5;
                    }
                    else if(!isset(json_decode(json_decode($check)->extra_info)->agent_career)){
                        $type = 6;
                    }
                    else if(!isset(json_decode(json_decode($check)->extra_info)->construction_img1)){
                        $type = 7;
                    }
                }
            }
            Cookie::queue('pass_cookie', $token, Carbon::now()->diffInMinutes(Carbon::now()->addMinutes(60 * 3)));
            Cookie::queue('type_cookie', $type, Carbon::now()->diffInMinutes(Carbon::now()->addMinutes(60 * 3)));

            return response()->json(['token' => $token,'type'=>$type], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    public function disable_passcookie()
    {
        Cookie::queue(Cookie::forget('pass_cookie'));
        return response()->json(null, 401);
    }

    public function refresh(Request $request)
    {
        $this->sweep_access_tokens(auth()->user()->user_no);
        $token = auth()->user()->createToken('perzuma')->accessToken;

        Cookie::queue('pass_cookie', $token, Carbon::now()->diffInMinutes(Carbon::now()->addMinutes(60 * 3)));
        Cookie::queue('type_cookie', auth()->user()->user_grade, Carbon::now()->diffInMinutes(Carbon::now()->addMinutes(60 * 3)));

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        Cookie::queue(Cookie::forget('pass_cookie'));
        Cookie::queue(Cookie::forget('type_cookie'));

        return response()->json(null, 204);
    }


    public function heartbeat() {
        return response()->json(true);
    }

    private function sweep_access_tokens($user_id){
        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->skip(20)
            ->take(PHP_INT_MAX) // 나머지 row 전부 가져오기
            ->get();
        
        $ids = [];
        foreach($tokens as $token){
            $ids[] = $token->id;
        }

        DB::table('oauth_access_tokens')->whereIn('id', $ids)->delete();
    }
    public function getusertype($user_no){
        $getdb = DB::table('users')->where('user_no',$user_no)->first();
        return $getdb->user_grade;
    }

    public function agentinfo_check($user_no){
        return DB::table('agent_info')->where('agent_no',$user_no)->exists();
    }
    public function agentinfo_step($user_no){
        return json_encode(DB::table('agent_info')->select('extra_info')->where('agent_no',$user_no)->first());
    }

    private function getToken($user){
        $key = "secretkey";
        $token = array(
            'iss' => config('app.url').'/login',
            'sub' => "{$user->user_no}",
            'email' => $user->email,
            'name' => $user->name,
            'iat' => time(),
        );

        $jwt = JWT::encode($token, base64_decode(strtr($key, '-_', '+/')), 'HS256');

        $temp_token = substr($jwt, 0, 100);


        $data = array( 'detail'=>'Your awesome detail here', 'user_name' => $user->name, 'jwt' => $jwt, 'url' => config('app.url') ); 

        Mail::send('email.view', $data, function($message) use ($user) { 
            $message->from('perzuma777@gmail.com', '퍼주마'); 
            $message->to($user->email, $user->name)->subject('Welcome!'); 
        });

        return $temp_token;
    }

    public function verify_email($token){
        $jwt = $token;
        $key = "secretkey";

        $temp_token = substr($jwt, 0, 100);

        $decoded = JWT::decode($jwt, base64_decode(strtr($key, '-_', '+/')), ['HS256']);

        $redirect_url = $decoded->iss;
        $user_no = $decoded->sub;

        $user = DB::table('users')->where('user_no', $user_no)->first();
        
        if($user->remember_token == $temp_token){
            $status = DB::table('users')->where('user_no', $user_no)->update([
                "email_verified_at" => DB::raw('now()'),
                "state" => 1,
            ]);
        }

        return redirect($redirect_url)->with('verified', true);;
    }
    public function unregist(Request $request){
        $param = array();
        $param['user_no'] = auth()->user()->user_no;
        Cookie::queue(Cookie::forget('pass_cookie'));
        Cookie::queue(Cookie::forget('type_cookie'));

        $sql = 'UPDATE
        users
    SET
        state = 3
    WHERE
        user_no = :user_no 
        RETURNING user_no;';
        $this->res = $this->execute_query($sql, $param, 'select');
        //정상적으로 실행된 경우
        if (count($this->res['query']) >0 &&  $this->res['query'][0]->user_no > 0) {
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.NO_DATA');
            $this->res['msg'] = '쿼리응답에러';
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}

