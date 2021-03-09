<?php

namespace App\Classes;

use Auth;
use DB;

class Settings
{
    public function Settings()
    {
        $setting_id = Session('market_type');
        
        $Settings = DB::table('btc_settings')->where('id', $setting_id)->first();
 
        return $Settings;
    }
}
