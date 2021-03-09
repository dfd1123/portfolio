<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ComunityVerification
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

        if(Auth::check()){
            if(Auth::user()->comunity_status == 0){
                return redirect()->back()->with('comunity_reject', '커뮤니티 사용이 금지되셨습니다.\n운영자에게 문의하세요.');
            }
        }

        return $next($request);
    }
}
