<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Facades\App\Classes;
use Facades\App\Classes\Beat;
use Facades\App\Classes\Secure;
use Facades\App\Classes\Producer;
use Facades\App\Classes\MoodSelect;
use Facades\App\Classes\License;
use Facades\App\Classes\BeatRequestHistory;
use Facades\App\Classes\LicenseOrder;

use DB;
use Hash;
use App;
use Cookie;
use Storage;

class PassportController extends Controller
{
    // 쿠키에 Passport 인증 토큰 추가할지 선택. 쿠키인증은 passcookie 미들웨어 필요. 기간 유지가 약간 불안정함.
    private $use_cookie_token = false;

    public function register(Request $request)
    {
        // 유저 생성
        $user = User::create([
            'user_email' => $request->user_email,
            'user_pw' => Hash::make($request->user_pw),
            'user_nick' => $request->user_nick,
            'user_name' => $request->user_name,
            'user_mobile' => $request->user_mobile,
            'user_agr_email_prom' => $request->check_info_email_send ? 1 : 0, // 0수신거부, 1허용
            'pg_info' => Secure::mobile_auth_verify($request->verify_code)
        ]);

        // 신규회원이 선택한 분위기 추가
        MoodSelect::store($user->user_id, $request->mood_s_selects);

        // 신규회원 1개월 무료 스트리밍 체크
        if ($request->check_free_month_for_new === true) {
            // 이용권 목록에 1개월 무료 이용권 추가하기

            $lcens_id = 1; // 스트리밍
            $lo_pg_type = 3; // 무료이용권
            
            // 이용권 등록
            $lo_id = LicenseOrder::store($user->user_id, $lcens_id, $lo_pg_type);

            // 이용권 활성화
            LicenseOrder::activate($user->user_id, $lo_id);

            info('Register License activated: ' . $lo_id);
        }

        // $token = $user->createToken('user')->accessToken;
        // $this->add_laravel_token_cookie($user->user_id, $token);
        return response()->json(200); //회원가입 시 토큰 생성안함
    }

    public function register_producer(Request $request)
    {
        $producer = Producer::by_user_id(auth()->user()->user_id);
        if ($producer !== null) {
            return response()->json(null, 422);
        }

        $storage_image_save_path = config('filesystems.maker_thumb');
        $storage_audio_save_path = config('filesystems.maker_sample');

        $path1 = null; // image
        $path2 = null; // audio

        if ($request->hasFile('file1')) {
            if ($request->file('file1')->isValid()) {
                $path1 = str_replace($storage_image_save_path.'/', "", $request->file1->store($storage_image_save_path));
            }
        }
        if ($request->hasFile('file2')) {
            if ($request->file('file2')->isValid()) {
                $md5_name = md5_file($request->file('file2')->getRealPath());
                $extension = $request->file('file2')->getClientOriginalExtension();
                $path2 = str_replace($storage_audio_save_path.'/', "", $request->file2->storeAs($storage_audio_save_path, $md5_name . "." . $extension));
            }
        } else {
            return response()->json(null, 422);
        }

        Producer::store(
            auth()->user()->user_id,
            $request->mood_json,
            $request->cate_json,
            $request->prdc_nick,
            $path1,
            $path2,
            $request->prdc_bnk_accnt
        );

        return response()->json(null, 200);
    }

    public function leave_producer(Request $request)
    {
        $producer = Producer::by_user_id(auth()->user()->user_id);
        if ($producer === null) {
            return response()->json(null, 422);
        }

        $storage_image_save_path = config('filesystems.maker_thumb');
        $storage_audio_save_path = config('filesystems.maker_sample');

        $delete_path1 = $storage_image_save_path.'/'.$producer->prdc_img;
        $delete_path2 = $storage_audio_save_path.'/'.$producer->prdc_sample;

        if (Storage::exists($delete_path1)) {
            Storage::delete($delete_path1);
        }

        if (Storage::exists($delete_path2)) {
            Storage::delete($delete_path2);
        }

        // 프로듀서 정보 삭제
        Producer::destroy($producer->prdc_id);

        // 나중에 곡 + 팔로워 + 좋아요 등등 다 삭제할지 논의

        return response()->json(null, 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'user_email' => $request->user_email,
            'password' => $request->user_pw
        ];

        if (auth()->attempt($credentials)) {
            $user = DB::table('users')->where('user_id', auth()->user()->user_id)->first();

            // 이메일 인증 안한 유저는 로그인 불가 (email_verified_at 컬럼이 NULL인지 체크)
            if ($user->email_verified_at == null) {
                // 이메일 인증 안한 유저가 로그인 할 때마다 인증메일 전송
                Secure::email_verify_link(auth()->user()->user_email);
                return response()->json([
                    'code' => '-1',
                    'message' => 'email not verified'
                ], 422);
            }

            $token = auth()->user()->createToken('user')->accessToken;
            $this->add_laravel_token_cookie(auth()->user()->user_id, $token);
            $this->sweep_access_tokens(auth()->user()->user_id);
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }

    public function refresh(Request $request)
    {
        $token = auth()->user()->createToken('user')->accessToken;
        $this->add_laravel_token_cookie(auth()->user()->user_id, $token);
        $this->sweep_access_tokens(auth()->user()->user_id);
        return response()->json(['token' => $token], 200);
    }

    public function info()
    {
        $user_id = auth()->user()->user_id;

        $user_info = data_get(DB::select("
        SELECT
            user_id,
            user_email,
            user_nick,
            stream_start_at,
            state,
            created_at,
            updated_at
        FROM users
        WHERE 1 = 1
        AND user_id = :user_id
        LIMIT 1
        ", ['user_id' => $user_id]), 0, null);

        $prdc_info = Producer::by_user_id($user_id);
        if ($prdc_info !== null) {
            $prdc_info->mood_json = json_decode($prdc_info->mood_json);
            $prdc_info->cate_json = json_decode($prdc_info->cate_json);
        } else {
            $prdc_info = (object)[];
        }

        $license_info = LicenseOrder::available($user_id);
        if ($license_info !== null) {
            $user_info->license = $license_info;
        } else {
            $user_info->license = (object)[];
        }

        $response = [
            'user' => $user_info,
            'producer' => $prdc_info
        ];

        return response()->json($response);
    }
    public function info_change(Request $request)
    {
        $params = array();
        // 값 검증
        if ($request->has('user_pw')) {
            $user_pw_len = strlen($request->user_pw);
            if ($user_pw_len < 8 || $user_pw_len > 15) {
                return response()->json([
                    'code' => '-1',
                    'message' => 'invalid password'
                ], 422);
            } else {
                $params['user_pw'] = Hash::make($request->user_pw);
            }
        }

        if ($request->has('user_nick')) {
            $user_nick_len = strlen($request->user_nick);
            if ($user_nick_len <= 0) {
                return response()->json([
                    'code' => '-1',
                    'message' => 'invalid password'
                ], 422);
            } else {
                $params['user_nick'] = $request->user_nick;
            }
        }

        if ($request->has('user_mobile')) {
            $user_mobile_len = strlen($request->user_mobile);
            if ($user_mobile_len <= 0) {
                return response()->json([
                    'code' => '-1',
                    'message' => 'invalid mobile no'
                ], 422);
            } else {
                $params['user_mobile'] = $request->user_mobile;
            }
        }

        if ($request->has('verify_code')) {
            $verify_info = Secure::mobile_auth_verify($request->verify_code);
            if ($verify_info != null) {
                $params['pg_info'] = $verify_info;
            }
        }

        User::where('user_id', auth()->user()->user_id)->update($params);

        return response()->json(null, 200);
    }

    public function check_email_duplicate(Request $request)
    {
        $count = User::where('user_email', $request->user_email)->count();
        if ($count > 0) {
            return response()->json(true, 422);
        }

        return response()->json(false, 200);
    }

    public function password_find_request(Request $request)
    {
        if (!filter_var($request->user_email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(null, 500);
        }

        if (Secure::password_find_link($request->user_email)) {
            return response()->json(null, 200);
        }

        return response()->json(null, 422);
    }

    public function password_find_verify(Request $request)
    {
        $user = Secure::password_find_verify($request->verify_code);
        if ($user !== null) {
            $token = $user->createToken('user')->accessToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(null, 422);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $this->remove_laravel_token_cookie(auth()->user()->user_id);
        return response()->json(null, 204);
    }

    public function request_url(Request $request)
    {
        $license = LicenseOrder::available(auth()->user()->user_id);
        if ($license === null) {
            return abort(403);
        }


        if ($license->lcens_type === 1) {
            // 스트리밍 basic 이용권
            $res = DB::SELECT("
            INSERT INTO audio_verify_temp (
                id,
                user_id,
                beat_id,
                expires_at
            )
            VALUES (
                :id,
                :user_id,
                :beat_id,
                now() + INTERVAL '1' HOUR
            )
            RETURNING id
            ", [
                'id' => hash("sha256", openssl_random_pseudo_bytes(256)),
                'user_id' => auth()->user()->user_id,
                'beat_id' => $request->beat_id
            ]);
            $url = data_get($res[0], 'id', null);

            Beat::hit($request->beat_id);
            BeatRequestHistory::store(0, auth()->user()->user_id, $request->beat_id, $license->lo_id);
            return response()->json(['url' => $url . '.mp3'], 200);
        }

        return abort(403);
    }

    public function heartbeat()
    {
        return response()->json(true);
    }

    private function add_laravel_token_cookie($user_id, $token)
    {
        if ($this->use_cookie_token) {
            Cookie::queue('laravel_token', $token, Carbon::now()->diffInMinutes(Carbon::now()->addDays(7)));
        }
    }

    private function remove_laravel_token_cookie($user_id)
    {
        if ($this->use_cookie_token) {
            Cookie::queue(Cookie::forget('laravel_token'));
        }
    }

    private function sweep_access_tokens($user_id)
    {
        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->skip(3)
            ->take(PHP_INT_MAX) // 나머지 row 전부 가져오기
            ->get();

        $ids = [];
        foreach ($tokens as $token) {
            $ids[] = $token->id;
        }

        DB::table('oauth_access_tokens')->whereIn('id', $ids)->delete();
    }
}
