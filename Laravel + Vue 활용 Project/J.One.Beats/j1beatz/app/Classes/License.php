<?php

namespace App\Classes;

use Auth;
use DB;

class License
{
    public function index()
    {
        $res = DB::select("
        SELECT
            lcens_id,
            lcens_name,
            lcens_type,
            lcens_days,
            lcens_price,
            lcens_desc
        FROM license
        WHERE 1 = 1
            AND state = 1
        ORDER BY lcens_type ASC
        ");

        return $res;
    }

    public function show($lcens_id)
    {
        $license_info = DB::select("
        SELECT
            lcens_id,
            lcens_name,
            lcens_type,
            lcens_days,
            lcens_price,
            lcens_desc
        FROM license
        WHERE 1 = 1
            AND lcens_id = :lcens_id
        LIMIT 1
        ", [
            'lcens_id' => $lcens_id
        ]);

        return data_get($license_info, 0, null);
    }
}
