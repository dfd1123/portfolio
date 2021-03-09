<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

class GameController extends Controller
{
    
    public function __construct()
    {
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
	public function index(){
        
        $views = view(session('theme').'.'.$this->device.'.'.'game.game_1');
        
		return $views;
    }
    
}
