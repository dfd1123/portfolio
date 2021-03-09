<?php

namespace TLCfund\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TLCfund\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapAdminRoutes() {
        // /admin 으로 들어오는 요청에 대해 처리한다.
        Route::prefix('admin')
        // 이런식으로 한 번에 추가할 수도 있다. 하지만 except 함수를 쓸 수 없는 것 같고,
        // 라우팅 페이지에서 미들웨어를 등록해주는 게 더 편했다.
        // ->middleware(['web', 'admin'])
          ->middleware('web')
          ->namespace($this->namespace)
        // routes/admin.php 파일을 관리자 라우팅 파일로 등록한다.
          ->group(base_path('routes/admin.php'));
      }
}
