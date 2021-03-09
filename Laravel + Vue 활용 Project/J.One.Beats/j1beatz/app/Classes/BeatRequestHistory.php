<?php

namespace App\Classes;

use DB;

class BeatRequestHistory
{
    public function store($brh_type, $user_id, $beat_id, $lo_id = null, $po_id = null)
    {
        DB::insert("
        INSERT INTO beat_request_history (
            brh_type,
            user_id,
            beat_id,
            lo_id,
            po_id,
            created_at
        )
        VALUES (
            :brh_type,
            :user_id,
            :beat_id,
            :lo_id,
            :po_id,
            now()
        )", [
            'brh_type' => $brh_type,
            'user_id' => $user_id,
            'beat_id' => $beat_id,
            'lo_id' => $lo_id,
            'po_id' => $po_id
        ]);

        return true;
    }
}
