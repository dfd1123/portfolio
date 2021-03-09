<?php

namespace TLCfund\Http\Middleware;

//use Closure;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Admin_auth extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin_login');
        }
    }
}
