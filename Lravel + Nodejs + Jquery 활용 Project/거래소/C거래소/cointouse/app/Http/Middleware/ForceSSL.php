<?php

namespace App\Http\Middleware;

use Closure;

class ForceSSL
{
    // HTTPS 로 제공하지 않을 URI. legacy 로 시작되는 URI 일 경우 SSL 을 강제 적용하지 않음
    protected $except = [
        'legacy/*',
    ];
    // HTTPS 로 제공하지 않을 애플리케이션 환경. 개발자 PC 에서 구동(local)할 경우와 PHPUnit 을 구동(testing)할 경우는 강제 Https 를 사용하지 않음
    protected $exceptEnv = [
        'testing',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Https 가 아니고 제외되는 조건이 아닐 경우 강제로 HTTPS 로 포워딩
         if (!$request->secure() && !$this->shouldPassThrough($request) && !$this->envPassThrough()) {
             
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
  
    // 제외할 URI 인지 확인
    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }
      
        return false;
    }
  
    // 제외할 환경인지 확인
    protected function envPassThrough()
    {
        $appEnv = \App::environment();
        foreach ($this->exceptEnv as $except) {
            if ($appEnv === $except)
                return true;
        }
        return false; 
    }
}
