<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Facades\App\Classes\FileRequest;
use App\Admin;
use Auth;
use DB;

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
            'adm_name' => 'required|string',
            'adm_email' => 'required|email|unique:admins',
            'adm_pwd' => 'required|min:6',
            'adm_contact' => 'required',
        ]);

        try {
            $admin = new Admin;
            $admin->adm_name = $request->adm_name;
            $admin->adm_email = $request->adm_email;
            $admin->adm_pwd = Hash::make($request->adm_pwd);
            $admin->adm_level = $request->input('adm_level', 1);
            $admin->adm_contact = $request->adm_contact;
            $admin->adm_memo = $request->input('adm_memo', null);
            $admin->adm_thumb = FileRequest::set($request, 'file1', config('filesystems.admin_thumb'));
            if($request->has('state')) {
                $admin->state = $request->state;
            }
            $admin->save();

            return response()->json(null, 201);
        } catch (\Exception $e) {
            info($e);
            return response()->json(null, 409);
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
            'adm_email' => 'required|string',
            'adm_pwd' => 'required|string',
        ]);

        $credentials = [
            'adm_email' => $request->adm_email,
            'password' => $request->adm_pwd
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

        return response()->json($detail, 200);
    }

    public function detail_update(Request $request)
    {
        $this->validate($request, [
            'adm_pwd' => 'min:6',
        ]);
        
        $admin = Admin::where('adm_email', Auth::user()->adm_email)->first();
        
        if ($request->has('adm_name')) {
            $admin->adm_name = $request->adm_name;
        }

        if ($request->has('adm_pwd')) {
            $admin->adm_pwd = Hash::make($request->adm_pwd);
        }
        
        $admin->adm_thumb = FileRequest::set($request, 'file1', config('filesystems.admin_thumb'), $admin->adm_thumb);

        $admin->save();

        return response()->json(null);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60 // seconds
        ]);
    }
}
