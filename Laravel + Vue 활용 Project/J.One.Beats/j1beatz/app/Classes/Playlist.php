<?php

namespace App\Classes;

use Auth;
use DB;

class Playlist
{
    public function index()
    {
        $res = DB::select("
        WITH bls AS
        (
            SELECT
                bl_id,
                user_id,
                beat_id,
                state AS bl_state
            FROM beat_like
            WHERE 1 = 1
                AND
                (
                    1 <> 1
                    OR (:user_id::int IS NOT NULL AND user_id = :user_id::int)
                )
        ),
        pls AS
        (
            SELECT
                b.beat_id,
                b.beat_title,
                b.beat_thumb,
                b.beat_path->>'mp3' AS beat_url,
                p.prdc_id,
                p.prdc_nick,
                COALESCE(b.beat_price, 0) AS beat_price,
                bt.json_array_order
            FROM
            (
                SELECT
                    ORDINALITY AS json_array_order,
                    value::int4 AS beat_id
                FROM json_array_elements_text(
                    (
                        SELECT
                            beat_json::json
                        FROM playlist
                        WHERE 1 = 1
                            AND user_id = :user_id
                    )
                ) with ordinality
            ) bt, beat b, producer p
            WHERE 1 = 1
                AND bt.beat_id = b.beat_id
                AND b.prdc_id = p.prdc_id
                AND p.state = 1
                AND b.state = 1
        )
        SELECT
            pls.beat_id,
            pls.beat_title,
            pls.beat_thumb,
            pls.beat_url,
            pls.prdc_id,
            pls.prdc_nick,
            bls.bl_id,
            bls.bl_state,
            pls.beat_price
        FROM pls
        LEFT JOIN bls ON pls.beat_id = bls.beat_id
        ORDER BY pls.json_array_order ASC
        ", ['user_id' => auth()->user()->user_id]);

        return $res;
    }

    public function upsert($user_id, $playlist)
    {
        DB::insert("
        WITH upsert AS
        (
            UPDATE
                playlist
            SET
                beat_json = :playlist
            WHERE 1 = 1
                AND user_id = :user_id
            RETURNING user_id
        )
        INSERT INTO playlist (
            beat_json,
            user_id
        )
        SELECT
            '[]',
            :user_id
        WHERE NOT EXISTS
        (
            SELECT
                user_id
            FROM upsert
        )
        ", [
            'user_id' => $user_id,
            'playlist' => $playlist
        ]);

        return true;
    }
}
