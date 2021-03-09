<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/nicecheck/checkplus_success',
        '/nicecheck/checkplus_fail',
        '/auth/checkplus_success',
        '/auth/checkplus_fail',
        '/api/call_orderbook',
        '/api/payment_history',
        '/api/payment_refund',
        '/api/payment_window',
    ];
}
