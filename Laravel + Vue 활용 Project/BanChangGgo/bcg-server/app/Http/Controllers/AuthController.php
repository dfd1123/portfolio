<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Facades\App\Classes\Secure;
use Facades\App\Classes\FileRequest;
use Facades\App\Classes\UserBatch;
use Facades\App\Classes\HealthReport;
use App\User;
use Auth;
use DB;
use Storage;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'usr_name' => 'required|string',
            'usr_email' => 'required|email',
            'usr_pwd' => 'required|min:6',
            'usr_reg_type' => 'required',
            'usr_extra' => 'required',
            'verify_code' => 'required'
        ]);

        try {
            $cnt = DB::table('email_verify_temp')
                ->where('usr_email', $request->usr_email)
                ->where('verify_code', $request->verify_code)
                ->delete();
            
            if ($cnt == 0) {
                return response()->json([
                    'error' => 'email_verify_fail',
                    'message' => '인증 오류입니다. 회원가입을 다시 진행해주시기 바랍니다'
                ], 422);
            }

            $user = new User;
            $user->usr_name = $request->usr_name;
            $user->usr_email = $request->usr_email;
            $user->usr_pwd = Hash::make($request->usr_pwd);
            $user->usr_reg_type = $request->usr_reg_type;
            $user->usr_extra = $request->usr_extra;
            $user->usr_thumb = FileRequest::set($request, 'file1', config('filesystems.user_thumb'));
            $user->save();

            // 회원가입 시 참여가능한 진행중인 차수로 자동 신청 시도
            $ubt_no = UserBatch::activate($user->usr_no);
            if ($ubt_no == null) {
                $user->delete();

                return response()->json([
                    'error' => 'user_batch_unavailable',
                    'message' => '현재 회원가입이 가능한 상태가 아닙니다. 나중에 다시 시도해주시기 바랍니다'
                ], 422);
            }

            $params = [
                'ubt_no' => $ubt_no
            ];
    
            HealthReport::store($params);
        
            return $this->respondWithToken(Auth::login($user), 201);
        } catch (\Exception $e) {
            info($e);

            return response()->json([
                'error' => 'conflict',
                'message' => '이미 존재하는 사용자거나 인증 오류입니다'
            ], 409);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'usr_email' => 'required|string',
            'usr_pwd' => 'required|string',
        ]);

        $credentials = [
            'usr_email' => $request->usr_email,
            'password' => $request->usr_pwd
        ];

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'error' => 'unauthorized',
                'message' => '아이디나 비밀번호가 잘못되었습니다. 다시 시도해주시기 바랍니다'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout(false);

        return response()->json(null);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    public function detail()
    {
        $detail = Auth::user();
        if ($detail->state == 2) {
            return response()->json([
                'error' => 'unavailable',
                'message' => '탈퇴처리된 유저토큰입니다'
            ], 401);
        }

        if (isset($detail->usr_extra)) {
            $detail->usr_extra = json_decode($detail->usr_extra);
        }

        $detail->usr_batch = UserBatch::avaliable($detail->usr_no) ?? (object)[];
        
        return response()->json($detail);
    }

    public function detail_update(Request $request)
    {
        $this->validate($request, [
            'usr_pwd' => 'min:6',
        ]);
        
        $user = User::where('usr_email', Auth::user()->usr_email)->first();
        
        if ($request->has('usr_pwd')) {
            $user->usr_pwd = Hash::make($request->usr_pwd);
        }

        if ($request->has('usr_extra')) {
            $user->usr_extra = $request->usr_extra;
        }
        
        $user->usr_thumb = FileRequest::set($request, 'file1', config('filesystems.user_thumb'), $user->usr_thumb);

        $user->save();

        return response()->json(null);
    }

    public function check_email_duplicate(Request $request)
    {
        $count = User::where('usr_email', $request->usr_email)->count();
        if ($count > 0) {
            return response()->json([
                'error' => 'already_exists',
                'message' => '이미 존재하는 이메일입니다'
            ], 422);
        }

        return response()->json(false);
    }

    public function email_verify_request(Request $request)
    {
        if (!filter_var($request->usr_email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'error' => 'invalid_format',
                'message' => '이메일 형식이 잘못되었습니다'
            ], 422);
        }

        $user = DB::table('users')->where('usr_email', $request->usr_email)->first();
        if ($user != null) {
            return response()->json([
                'error' => 'already_exists',
                'message' => '이미 존재하는 이메일입니다'
            ], 422);
        }

        Secure::email_verify_code($request->usr_email);
        return response()->json(true);
    }

    public function email_verify_certify(Request $request)
    {
        $result = Secure::email_certify_code($request->usr_email, $request->verify_code);
        if ($result == 'certify_ok') {
            return response()->json(null);
        } elseif ($result == 'certify_fail') {
            return response()->json([
                'error' => 'invalid_code',
                'message' => '인증코드가 일치하지 않습니다'
            ], 422);
        }

        return response()->json([
            'error' => 'expired_request',
            'message' => '만료되었거나 없는 요청입니다'
        ], 422);
    }

    public function password_find_request(Request $request)
    {
        if (!filter_var($request->usr_email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'error' => 'invalid_format',
                'message' => '이메일 형식이 잘못되었습니다'
            ], 422);
        }

        if (Secure::password_find_verify_code($request->usr_email)) {
            return response()->json(true);
        }

        return response()->json([
            'error' => 'invalid_request',
            'message' => '이메일을 찾을 수 없습니다'
        ], 422);
    }

    public function password_find_certify(Request $request)
    {
        $user = Secure::password_find_certify_code($request->usr_email, $request->verify_code);
        if ($user !== null) {
            return $this->respondWithToken(Auth::login($user));
        }

        return response()->json([
            'error' => 'invalid_request',
            'message' => '인증코드가 일치하지 않거나 만료되었습니다'
        ], 422);
    }
    
    public function secession(Request $request)
    {
        $this->validate($request, [
            'unreg_info' => 'required|string',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            User::destroy(Auth::id());
            
            DB::insert("
            INSERT INTO users (
                usr_no,
                usr_name,
                usr_email,
                usr_pwd,
                usr_reg_type,
                usr_extra,
                state
            ) values (
                :usr_no,
                :usr_name,
                :usr_email,
                '',
                :usr_reg_type,
                :usr_extra,
                :state
            )", [
                'usr_no' => $user->usr_no,
                'usr_name' => $user->usr_name,
                'usr_email' => $user->usr_email . "__" . Hash::make(time()),
                'usr_reg_type' => $user->usr_reg_type,
                'usr_extra' => json_encode(["unreg_info" => $request->unreg_info]),
                'state' => 2
            ]);
        }, 1);

        FileRequest::remove(config('filesystems.user_thumb'), $user->usr_thumb);

        return response()->json(null);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $code = 200)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60 // seconds
        ], $code);
    }
}
