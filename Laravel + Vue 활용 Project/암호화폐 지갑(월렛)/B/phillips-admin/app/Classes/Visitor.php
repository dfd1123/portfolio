<?php

namespace App\Classes;

use DB;
use Log;

class Visitor {
	public function __construct() {
		$this->ip = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        $this->url = addslashes($_SERVER['REQUEST_URI']);
        
        DB::connection('mysql_sub')->table('btc_visitor_live')->whereRaw('date < date_add(now(), interval -5 minute)')->delete();

        $result = DB::connection('mysql_sub')->table('btc_visitor_live')->updateOrInsert(
            ["ip" => $this->ip],
            ["url" => $this->url]
        );

    }
    
    public function set(){
        return true;
    }

	public function live($mod) {
		switch($mod) {
            case 'count':
				return DB::connection('mysql_sub')->table('btc_visitor_live')->count();
			case 'list':
				return DB::connection('mysql_sub')->table('btc_visitor_live')->get();
		}
	}

}
