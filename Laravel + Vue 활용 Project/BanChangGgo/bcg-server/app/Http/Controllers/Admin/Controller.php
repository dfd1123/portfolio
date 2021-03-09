<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $perPage = 10;

    public function __construct()
    {
        Auth::setDefaultDriver('admin');
    }

    public function paginate(Request $request)
    {
        if (!method_exists($this, 'index')) {
            return abort(404);
        }

        $request->merge([
            'offset' => 0,
            'limit' => null,
        ]);

        $items = collect($this->index($request)->original);
        $perPage = $this->perPage;
        $currentPage = Illuminate\Pagination\Paginator::resolveCurrentPage();
        
        return new Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($currentPage, $perPage)->values(),
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
    }
}
