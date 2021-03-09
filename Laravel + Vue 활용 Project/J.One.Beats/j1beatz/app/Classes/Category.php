<?php

namespace App\Classes;

use Auth;
use DB;

class Category
{
    public function index()
    {
        $res = DB::select("
        SELECT
            cate_id,
            cate_title
        FROM category
        WHERE 1 = 1
            AND state = 1
        ORDER BY cate_id ASC
        ");

        return $res;
    }
}
