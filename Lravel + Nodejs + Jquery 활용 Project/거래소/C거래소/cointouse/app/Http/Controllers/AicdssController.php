<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AicdssController extends Controller
{
    public function register_agree(){
    	$views = view('common.'.config('device.device').'.register.register_agree');
		
		return $views;
    }
	
	public function register_complete(){
    	$views = view('common.'.config('device.device').'.register.register_complete');
		
		return $views;
    }
}
