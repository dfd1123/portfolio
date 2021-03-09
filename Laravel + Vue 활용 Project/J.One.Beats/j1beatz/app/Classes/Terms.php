<?php

namespace App\Classes;

use Auth;
use DB;

class Terms
{
    public function index()
    {
        $res = DB::select("
        SELECT *
        FROM
            settings
        LIMIT 1
        ");

        return $res;
    }
}
