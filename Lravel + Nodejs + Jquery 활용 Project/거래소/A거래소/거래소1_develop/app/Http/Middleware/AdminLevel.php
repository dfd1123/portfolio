<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $level)
    {
        if(Auth::guard('admin')->user()->level > $level){
            return redirect('/admin')->with('jsAlert', '보안등급이 낮아 접근할 수 없습니다.');
        }
        return $next($request);
    }
}
