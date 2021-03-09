<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Cookie;

class TrimPassportInvalidCookies
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
            $client = new Client(['http_errors' => false]);
            $response = $client->request('GET', env('APP_URL').'/api/heartbeat', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$request->cookie('pass_cookie'),
                ],
            ]);

            if ($response->getStatusCode() == 401) {
                return redirect('/api/disable/passcookie');
            }
        }
        return $next($request);
    }
}
