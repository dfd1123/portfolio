<?php
namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
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
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers'     => 'Content-Type, Accept, Authorization, X-Requested-With',
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS',
            'Access-Control-Max-Age'           => '86400'
        ];
        
        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach ($headers as $key => $value) {
            if (method_exists($response, 'header')) {
                $response->header($key, $value);
            } else {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}
