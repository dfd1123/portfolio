<?php

namespace App\Classes;

use Stevebauman\Location\Facades\Location;

use Auth;
use DB;

class Settings {
	public function Settings()
	{
		
		$Settings = DB::table('btc_settings')->where('id','1')->first();
 
		return $Settings;
		
	}
	
}
