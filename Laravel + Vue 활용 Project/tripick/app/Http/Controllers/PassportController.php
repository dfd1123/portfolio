<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Hash;

class PassportController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $this->sweep_access_tokens(auth()->user()->user_id);
            $token = auth()->user()->createToken('tripick')->accessToken;

            return response()->json(['token' => $token,'type'=>$type], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    public function refresh(Request $request)
    {
        $this->sweep_access_tokens(auth()->user()->user_no);
        $token = auth()->user()->createToken('tripick')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(null, 204);
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
}
