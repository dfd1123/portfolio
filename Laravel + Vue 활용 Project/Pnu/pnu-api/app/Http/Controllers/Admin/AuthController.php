<?php

namespace App\Http\Controllers\Admin;

use Facades\App\Classes\FileRequest;
use Illuminate\Http\Request;
use App\Admin;
use Hash;
use Auth;
use DB;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'admin_no' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'admin_no' => $request->admin_no,
            'password' => $request->password
        ];

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'error' => 'unauthorized',
                'message' => '아이디나 비밀번호가 잘못되었습니다. 다시 시도해주시기 바랍니다'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function detail()
    {
        $detail = Auth::user();

        return response()->json($detail, 200);
    }

    public function detail_update(Request $request)
    {
        $this->validate($request, [
            'password' => 'min:6',
        ]);
        
        $admin = Admin::where('admin_no', Auth::user()->admin_no)->first();
        
        if ($request->has('name')) {
            $admin->name = $request->name;
        }

        if ($request->has('password')) {
            $admin->password = Hash::make($request->password);
        }

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
