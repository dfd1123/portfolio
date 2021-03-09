<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

class GuideController extends Controller
{
    
    public function __construct()
    {
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
        if($this->device == 'pc'){
            abort(403);
        }

    }
	
	public function guide_cash(){
        
        $views = view(session('theme').'.'.$this->device.'.'.'guide.guide_cash');
        
		return $views;
    }

    public function guide_coin(){
        
        $views = view(session('theme').'.'.$this->device.'.'.'guide.guide_coin');
        
		return $views;
    }

    public function guide_trade(){
        
        $views = view(session('theme').'.'.$this->device.'.'.'guide.guide_trade');
        
		return $views;
    }
    
}
