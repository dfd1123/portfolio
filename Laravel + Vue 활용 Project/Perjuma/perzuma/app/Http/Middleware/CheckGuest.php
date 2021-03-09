<?php

namespace App\Http\Middleware;

use Closure;

class CheckGuest
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
        if($request->cookie('pass_cookie') && $request->cookie('type_cookie')){
            return redirect()->back();
        }else{
            return $next($request);
        }
    }
}
