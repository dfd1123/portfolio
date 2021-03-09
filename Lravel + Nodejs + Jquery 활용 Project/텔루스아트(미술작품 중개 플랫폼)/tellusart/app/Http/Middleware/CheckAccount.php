<?php

namespace TLCfund\Http\Middleware;

use Closure;
use Auth;
use URL;

class CheckAccount
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
        if( Auth::user()->account_bank == NULL || Auth::user()->account_number == NULL || Auth::user()->account_user == NULL ){
            return redirect()->route('mypage.account_edit');
        }else{
            return $next($request);
        }
    }
}
