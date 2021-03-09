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
        if ($request->cookie('laravel_token')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->cookie('laravel_token'));
        }
        
        return $next($request);
    }
}
