<?php

namespace App\Http\Middleware;

use Closure;

use Cookie;
use Crypt;

class CheckApi
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
            return $next($request);
        }else{
            return redirect('login');
        }
    }
}
