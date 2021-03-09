<?php

namespace App\Classes;

use Auth;
use DB;

class MoodSelect
{
    public function index($user_id)
    {
        $res = DB::select("
        SELECT
            mood_s_select
        FROM mood_select
        WHERE 1 = 1
            AND user_id = :user_id
        ORDER BY mood_s_select ASC
        LIMIT 3
        ", ['user_id' => $user_id]);

        return $res;
    }

    public function store($user_id, $mood_s_selects)
    {
        DB::delete("
        DELETE FROM mood_select
        WHERE 1 = 1
            AND user_id = :user_id
        ", ['user_id' => $user_id]);

        foreach ($mood_s_selects as $mood_s_select) {
            DB::insert("
            INSERT INTO mood_select (
                user_id,
                mood_s_select,
                created_at,
                updated_at
            ) VALUES (
                :user_id,
                :mood_s_select,
                now(),
                now()
            )
            ", [
                'user_id' => $user_id,
                'mood_s_select' => $mood_s_select
            ]);
        }

        return true;
    }
}
