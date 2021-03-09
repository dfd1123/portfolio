<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
		$agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }
    
    public function index() {
        return view($this->device.'.'.'index');
    }
}
