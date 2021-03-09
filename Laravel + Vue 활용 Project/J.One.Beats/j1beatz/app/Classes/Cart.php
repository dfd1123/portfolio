<?php

namespace App\Classes;

use Auth;
use DB;

class Cart
{
    public function store($user_id, $beat_id)
    {
        DB::insert("
        INSERT INTO cart (
            user_id,
            beat_id
        )
        VALUES (
            :user_id,
            :beat_id
        );
        ", [
            'user_id' => $user_id,
            'beat_id' => $beat_id
        ]);

        return true;
    }


    public function show($user_id, $cart_id)
    {
        $cart_info = DB::select("
        SELECT
            cart_id,
            user_id,
            beat_id,
            lo_id
        FROM cart
        WHERE 1 = 1
            AND user_id = :user_id
            AND cart_id = :cart_id
        LIMIT 1
        ", [
            'user_id' => $user_id,
            'cart_id' => $cart_id
        ]);

        return data_get($cart_info, 0, null);
    }

    public function destroy($user_id, $cart_id)
    {
        DB::delete("
        DELETE FROM cart
        WHERE 1 = 1
            AND user_id = :user_id
            AND cart_id = :cart_id
        ", [
            'user_id' => $user_id,
            'cart_id' => $cart_id
        ]);

        return true;
    }

    public function by_beat_id($user_id, $beat_id)
    {
        $res = DB::select("
        SELECT
           cart_id
        FROM cart
        WHERE 1 = 1
            AND user_id = :user_id
            AND beat_id = :beat_id
        LIMIT 1
        ", [
            'user_id' => $user_id,
            'beat_id' => $beat_id
        ]);

        return data_get($res, 0, null);
    }

    public function cartlist($user_id)
    {
        $res = DB::select("
        WITH producers AS(
            SELECT
                prdc_id,
                prdc_nick
            FROM producer
            WHERE 1 = 1
                AND state = 1
        ), beats AS (
            SELECT
                beat_id,
                prdc_id,
                beat_title,
                beat_price,
                beat_thumb,
                CASE WHEN beat_path->>'mp3' IS NULL THEN 0 ELSE 1 END AS mp3,
                CASE WHEN beat_path->>'wav' IS NULL THEN 0 ELSE 1 END AS wav
            FROM beat
            WHERE 1 = 1
                AND state = 1
        )
        SELECT
            c.cart_id,
            c.beat_id,
            b.beat_title,
            b.beat_price,
            b.beat_thumb,
            b.mp3,
            b.wav,
            p.prdc_nick
        FROM cart c, producers p, beats b
        WHERE 1 = 1
            AND c.user_id = :user_id
            AND c.beat_id = b.beat_id
            AND b.prdc_id = p.prdc_id
        ORDER BY c.cart_id ASC
        ", [
            'user_id' => $user_id
        ]);

        return $res;
    }
    public function leftheader($user_id){
        $res = DB::select("
        SELECT
            CT.cart_id,
            BT.beat_title,
            BT.beat_thumb,
            PT.prdc_nick
        FROM
            cart CT
        JOIN beat BT ON
            CT.beat_id = BT.beat_id
        JOIN producer PT ON
            BT.prdc_id = PT.prdc_id
        WHERE
            user_id = :user_id
            AND BT.state = 1
            AND PT.state = 1
        ORDER BY
            cart_id DESC OFFSET 0
        LIMIT 5
        ", [
            'user_id' => $user_id
        ]);

        return $res;
    }
}
