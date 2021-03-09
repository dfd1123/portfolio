<?php

namespace App\Http\Middleware;

use Closure;

class CheckAgent
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
        if($request->cookie('type_cookie') == 2){
            return $next($request);
        }else{
            return redirect()->route('user_ver.home');
        }
    }
}
