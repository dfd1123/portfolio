<?php

namespace App\Classes;

use Auth;
use DB;

class Comment
{
    public function by_beat_id($beat_id)
    {
        $res = DB::select("
        SELECT
            c.cmt_id,
            u.user_id,
            u.user_nick,
            c.cmt_content,
            c.created_at,
            c.updated_at
        FROM comment c, users u
        WHERE 1 = 1
            AND c.user_id = u.user_id
            AND c.beat_id = :beat_id
            AND c.state = 1
        ORDER BY cmt_id ASC
        ", ['beat_id' => $beat_id]);

        return $res;
    }

    public function store($user_id, $beat_id, $cmt_content)
    {
        DB::insert("
        INSERT INTO comment (
            user_id,
            beat_id,
            cmt_content,
            updated_at
        ) VALUES (
            :user_id,
            :beat_id,
            :cmt_content,
            now()
        )
        ", [
            'user_id' => $user_id,
            'beat_id' => $beat_id,
            'cmt_content' => $cmt_content
        ]);

        return true;
    }

    public function update($user_id, $cmt_id, $cmt_content)
    {
        DB::update("
        UPDATE comment SET
            cmt_content = :cmt_content,
            updated_at = now()
        WHERE 1 = 1
            AND user_id = :user_id
            AND cmt_id = :cmt_id
        ", [
            'user_id' => $user_id,
            'cmt_id' => $cmt_id,
            'cmt_content' => $cmt_content,
        ]);

        return true;
    }

    public function destroy($user_id, $cmt_id)
    {
        DB::update("
        UPDATE comment SET
            state = 0
        WHERE 1 = 1
            AND user_id = :user_id
            AND cmt_id = :cmt_id
        ", [
            'user_id' => $user_id,
            'cmt_id' => $cmt_id
        ]);

        return true;
    }
}
