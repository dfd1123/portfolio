<?php

namespace App\Classes;

use Auth;
use DB;

class BeatLike
{
    public function upsert($user_id, $beat_id, $state)
    {
        DB::insert("
        WITH upsert AS
        (
            UPDATE
                beat_like
            SET
                state = :state,
                reg_dt = now()
            WHERE 1 = 1
                AND user_id = :user_id
                AND beat_id = :beat_id
            RETURNING bl_id
        )
        INSERT INTO beat_like (
            user_id,
            beat_id,
            state
        )
        SELECT
            :user_id,
            :beat_id,
            :state
        WHERE NOT EXISTS
        (
            SELECT
                bl_id
            FROM upsert
        )
        ", [
            'user_id' => $user_id,
            'beat_id' => $beat_id,
            'state' => $state
        ]);

        return true;
    }
}
