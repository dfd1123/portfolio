<?php

namespace App\Http\Middleware;

use Closure;

use Cookie;
use Crypt;

class PassportByCookies
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
        if ($request->cookie('pass_cookie') && $request->cookie('XSRF-TOKEN')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->cookie('pass_cookie'));
            $request->headers->set('X-CSRF-TOKEN', $request->cookie('XSRF-TOKEN'));
        }
        return $next($request);
    }
}
