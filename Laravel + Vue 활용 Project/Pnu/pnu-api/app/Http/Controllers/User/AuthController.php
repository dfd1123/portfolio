<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Facades\App\Classes\CpTestTemplate;
use Facades\App\Classes\UserCpTest;
use Facades\App\Classes\Batch;
use GuzzleHttp\Client;
use App\User;
use Auth;
use DB;
use Storage;
use Hash;

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
        return abort(404);
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
            'user_no' => 'required|string',
            'password' => 'required|string',
        ]);

        $client = new Client([
            'http_errors' => false
        ]);
        
        $response = $client->request(
            'POST',
            'http://onestop.pusan.ac.kr/new_pass/exorgan/exidentify.asp',
            [
                'headers' => [
                    'Accept-Charset' => 'utf-8'
                ],
                'form_params' => [
                    'id' => $request->user_no,
                    'pswd' => $request->password,
                    'dest' => config('app.url')
                ]
            ]
        );
        
        // 유저 정보
        $user_info = [];
        
        // 인증 체크 후 유저정보 들고오기
        if ($response->getStatusCode() == 200) {
            // HTML 문서 파싱
            $text = $response->getBody();
            $regex = '/(\bname\b)\s*=\s*"("[^"]*"|\'[^\']*\'|[^"\'<>\s]+)"\s+(\bvalue\b)\s*="("[^"]*"|\'[^\']*\'|[^"\'<>\s]+)?"/';
            preg_match_all($regex, $text, $matches);

            $i = 0;
            foreach ($matches[2] as $match) {
                $user_info[$match] = mb_convert_encoding($matches[4][$i], 'UTF-8', 'EUC-KR');
                $i++;
            }
        }
    
        // 401
        if (data_get($user_info, 'gbn') == 'False' || !data_get($user_info, 'gbn')) {
            return response()->json([
                'error' => 'unauthorized',
                'message' => '아이디나 비밀번호가 잘못되었습니다. 다시 시도해주시기 바랍니다'
            ], 401);
        }

        // 401
        if (data_get($user_info, 'sta') == '교직원') {
            return response()->json([
                'error' => 'unauthorized',
                'message' => '교직원은 로그인 불가합니다'
            ], 401);
        }

        // 이전에 로그인했던 유저인지 체크
        $user = User::where('user_no', $request->user_no)->first();
        if ($user === null) {
            $user = new User;
        }

        // 유저정보 업데이트
        $user->user_no = $request->user_no;
        $user->name = data_get($user_info, 'name') ?? null;
        $user->gender = data_get($user_info, 'gender') ?? null;
        
        $user->gbn = data_get($user_info, 'gbn') ?? null;
        $user->sta = data_get($user_info, 'sta') ?? null;
        $user->stdyear = data_get($user_info, 'stdt_year') ?? null;
        
        $user->deptcd = data_get($user_info, 'deptcd') ?? null;
        $user->dept = data_get($user_info, 'dept') ?? null;
        $user->collcd = data_get($user_info, 'collcd') ?? null;
        $user->coll = data_get($user_info, 'coll') ?? null;
        $user->majorcd = data_get($user_info, 'majorcd') ?? null;
        $user->major = data_get($user_info, 'major') ?? null;
        $user->save();

        return $this->respondWithToken(Auth::login($user));
    }

    public function detail()
    {
        $user_info = Auth::user();

        // 평가 진행 가능 여부
        $available_batch_id = Batch::available();
        $user_info->available = $available_batch_id === null ? false : true;
        
        if ($user_info->available) {
            // 완료한 마지막 역량평가 순서
            $latest_cpt_order = UserCpTest::latest_cpt_order($user_info->user_id, $available_batch_id);
            if ($latest_cpt_order !== null) {
                $user_info->cpt_order = $latest_cpt_order;
            }

            // 역량평가 총 갯수
            $max = CpTestTemplate::max();
            if ($max !== null) {
                $user_info->max_cpt_order = $max;
            }
        }

        return response()->json($user_info);
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
