<?php

namespace App\Classes;

use Auth;
use DB;

class Mood
{
    public function index()
    {
        $res = DB::select("
        SELECT
            mood_id,
            mood_title,
            mood_thumb
        FROM mood
        WHERE 1 = 1
            AND state = 1
        ORDER BY mood_id ASC
        ");

        return $res;
    }
}
