<?php

namespace App\Classes;

use DB;
use Log;

class Visitor
{
    public function renew()
    {
        $client_ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $a = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $b = end($a);
            $client_ip = trim($b);
        }

        $this->ip = sprintf('%u', ip2long($client_ip));
        $this->url = addslashes(url()->previous());

        DB::connection('mysql_sub')->table('btc_visitor_live')->upsert(
            ["ip" => $this->ip],
            "ip",
            ["url" => $this->url]
        );
    }
    
    public function sweep()
    {
        DB::connection('mysql_sub')
            ->table('btc_visitor_live')
            ->whereRaw('date < now() - interval 1 minute')
            ->delete();
    }

    public function live($mod)
    {
        switch ($mod) {
            case 'count':
                return DB::connection('mysql_sub')->table('btc_visitor_live')->count();
            case 'list':
                return DB::connection('mysql_sub')->table('btc_visitor_live')->get();
        }
    }
}
