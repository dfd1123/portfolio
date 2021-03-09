<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Simulated
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
        $except_email = array(
            "rorososo10000@naver.com",
            "duddn688@gmail.com",
            "sbtr01@gmail.com",
            "dfd1123@naver.com",
            "scissorstail@gmail.com"
        );

        if(Auth::check()){
            if(in_array(Auth::user()->email, $except_email)){
                return $next($request);
            }
        }

        return redirect('home');
    }
}
