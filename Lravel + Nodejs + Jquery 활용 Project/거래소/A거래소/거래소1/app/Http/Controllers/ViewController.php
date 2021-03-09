<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

class ViewController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }


    public function index(Request $request, $page){
        if($request->filled('page')){
            $sm_ca = $request->page;
            return view('theme.basic.'.$this->device.'.'.$page.'_'.$sm_ca);
        }else{
            return view('theme.basic.'.$this->device.'.'.$page);
        }
    }
}
