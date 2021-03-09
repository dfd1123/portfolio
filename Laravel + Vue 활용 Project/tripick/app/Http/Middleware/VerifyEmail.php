<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Facades\App\Classes\LoginInfo;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_id = LoginInfo::get();
        
        $sql = 'SELECT
                    email_verified_at, reg_type
                FROM
                    users
                WHERE
                    id = :user_id';
        $email_state = DB::select($sql, array('user_id'=>$user_id));
        if (isset($email_state[0]->email_verified_at) || $email_state[0]->reg_type != 'app') {
            
            if ($email_state[0]->email_verified_at == NULL && $email_state[0]->reg_type == 'app') {
                return redirect('/emailconfirm')->with('jsAlert', '이메일 인증이 필요한 서비스입니다.');
            }
        }else{
            return redirect('/emailconfirm')->with('jsAlert', '이메일 인증이 필요한 서비스입니다.');
        }

        return $next($request);
    }
}
