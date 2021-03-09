<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Jenssegers\Agent\Agent; 

use DB;

class FaqController extends Controller
{
	public function __construct()
    {
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
	public function index(){
		$views = view(session('theme').'.'.$this->device.'.'.'faq.faq');
		$faqs = DB::connection('mysql_sub')->table('btc_faq_'.config('app.country'))->get();

		$views->faqs = $faqs;
		return $views;
	}
	
}
