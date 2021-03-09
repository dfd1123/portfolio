<?php

namespace App\Http\Middleware;

use Closure;

class CheckForLocalhost
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
        if ($request->ip() != "localhost" && $request->ip() != "127.0.0.1" && $request->ip() != "::1") {
            info("[abort] ".$request->ip());
            return abort(404);
        }

        return $next($request);
    }
}
