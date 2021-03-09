<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    protected $device;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }
}
