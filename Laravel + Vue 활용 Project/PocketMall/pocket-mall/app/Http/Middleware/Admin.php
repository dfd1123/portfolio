<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
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
        // admin session이 있는 경우만 request를 진행한다.
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }
    
        // 아닐경우 관리자 로그인 페이지로 넘긴다.
        return redirect()->route('admin.login');
          
    }
}
