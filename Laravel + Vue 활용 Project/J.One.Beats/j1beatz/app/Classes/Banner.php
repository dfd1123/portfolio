<?php

namespace App\Classes;

use Auth;
use DB;

class Banner
{
    public function index()
    {
        $res = DB::select("
        SELECT
            banner_id,
            banner_title,
            banner_img
        FROM banner
        WHERE 1 = 1
        ORDER BY banner_id ASC
        ");

        return $res;
    }
}
