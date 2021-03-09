<?php

namespace App\Classes;

use Auth;
use DB;

class Beat
{
    public function realtime_top($params = [])
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
                AND user_id = COALESCE(:user_id::int, NULL)
        ),
        tops AS
        (
            SELECT
                r.beat_rank,
                r.beat_id,
                b.beat_title,
                b.beat_thumb,
                b.beat_price,
                b.beat_path->>'clip' AS beat_url,
                r.beat_hit,
                r.beat_like,
                r.beat_order,
                p.prdc_id,
                p.prdc_nick,
                m.mood_id,
                m.mood_title,
                c.cate_id,
                c.cate_title
            FROM beat b, ranks r, producer p, mood m, category c
            WHERE 1 = 1
                AND b.beat_id = r.beat_id
                AND b.prdc_id = p.prdc_id
                AND b.mood_id = m.mood_id
                AND b.cate_id = c.cate_id
                AND p.state = 1
                AND b.state = 1
                AND m.mood_id = COALESCE(:mood_id::int, m.mood_id)
                AND c.cate_id = COALESCE(:cate_id::int, c.cate_id)
            ORDER BY r.beat_rank DESC, r.beat_id ASC
            OFFSET COALESCE(:offset::int, 0) LIMIT COALESCE(:limit::int, 100)
        )
        SELECT
            tops.*,
            bls.bl_id,
            bls.bl_state
        FROM tops
        LEFT JOIN bls ON tops.beat_id = bls.beat_id
        ORDER BY tops.beat_rank DESC, tops.beat_id ASC
        ", [
            'user_id' => data_get(auth('api')->user(), 'user_id', null),
            'mood_id' => data_get($params, 'mood_id', null),
            'cate_id' => data_get($params, 'cate_id', null),
            'offset' => data_get($params, 'offset', null),
            'limit' => data_get($params, 'limit', null),
        ]);

        return $res;
    }

    public function realtime_latest($params = [])
    {
        $user = auth('api')->user();
        $user_id = empty($user) ? null : $user->user_id;

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
                AND user_id = COALESCE(:user_id::int, NULL)
        ),
        tops AS
        (
            SELECT
                r.beat_rank,
                r.beat_id,
                b.beat_title,
                b.beat_thumb,
                b.beat_price,
                b.beat_path->>'clip' AS beat_url,
                r.beat_hit,
                r.beat_like,
                r.beat_order,
                p.prdc_id,
                p.prdc_nick,
                m.mood_id,
                m.mood_title,
                c.cate_id,
                c.cate_title
            FROM beat b, ranks r, producer p, mood m, category c
            WHERE 1 = 1
                AND b.beat_id = r.beat_id
                AND b.prdc_id = p.prdc_id
                AND b.mood_id = m.mood_id
                AND b.cate_id = c.cate_id
                AND p.state = 1
                AND b.state = 1
                AND m.mood_id = COALESCE(:mood_id::int, m.mood_id)
                AND c.cate_id = COALESCE(:cate_id::int, c.cate_id)
            ORDER BY b.beat_id DESC
            OFFSET COALESCE(:offset::int, 0) LIMIT COALESCE(:limit::int, 100)
        )
        SELECT
            tops.*,
            bls.bl_id,
            bls.bl_state
        FROM tops
        LEFT JOIN bls ON tops.beat_id = bls.beat_id
        ORDER BY tops.beat_id DESC
        ", [
            'user_id' => data_get(auth('api')->user(), 'user_id', null),
            'mood_id' => data_get($params, 'mood_id', null),
            'cate_id' => data_get($params, 'cate_id', null),
            'offset' => data_get($params, 'offset', null),
            'limit' => data_get($params, 'limit', null),
        ]);

        return $res;
    }

    public function by_mood_top_50($mood1, $mood2, $mood3)
    {
        $user = auth('api')->user();
        $user_id = empty($user) ? null : $user->user_id;

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
        tops AS
        (
            SELECT
                r.beat_rank,
                r.beat_id,
                b.beat_title,
                b.beat_thumb,
                b.beat_price,
                b.beat_path->>'clip' AS beat_url,
                r.beat_hit,
                r.beat_like,
                r.beat_order,
                p.prdc_id,
                p.prdc_nick,
                m.mood_id,
                m.mood_title,
                c.cate_id,
                c.cate_title
            FROM beat b, ranks r, producer p, mood m, category c
            WHERE 1 = 1
                AND b.beat_id = r.beat_id
                AND b.prdc_id = p.prdc_id
                AND b.mood_id = m.mood_id
                AND b.cate_id = c.cate_id
                AND p.state = 1
                AND b.state = 1
                AND
                (
                    1 <> 1
                    OR (:mood1::int IS NOT NULL AND m.mood_id = :mood1::int)
                    OR (:mood2::int IS NOT NULL AND m.mood_id = :mood2::int)
                    OR (:mood3::int IS NOT NULL AND m.mood_id = :mood3::int)
                )
            ORDER BY r.beat_rank DESC, r.beat_id ASC
            LIMIT 50
        )
        SELECT
            tops.*,
            bls.bl_id,
            bls.bl_state
        FROM tops
        LEFT JOIN bls ON tops.beat_id = bls.beat_id
        ORDER BY tops.beat_rank DESC, tops.beat_id ASC
        ", [
            'user_id' => $user_id,
            'mood1' => ($mood1) ? $mood1 : null,
            'mood2' => ($mood2) ? $mood2 : null,
            'mood3' => ($mood3) ? $mood3 : null
        ]);

        return $res;
    }

    public function prdc_list($prdc_id, $offset, $limit)
    {
        $res = DB::select("
        WITH CTE AS (
            SELECT
                beat_id,
                COUNT(beat_id) AS beat_like
            FROM
                beat_like
            GROUP BY
                beat_id
            ORDER BY
                beat_id DESC
        ) SELECT
            BT.beat_id ,
            BT.beat_title ,
            MT.mood_title ,
            CT.cate_title ,
            CTE.beat_like ,
            beat_price,
            beat_tag,
            beat_free
        FROM
            beat BT
        JOIN mood MT ON
            BT.mood_id = MT.mood_id
        JOIN category CT ON
            BT.cate_id = CT.cate_id
        LEFT JOIN CTE ON
            BT.beat_id = CTE.beat_id
        WHERE
            BT.prdc_id = :prdc_id
        AND
            BT.state = 1
        OFFSET :offset LIMIT :limit;
        ", [
            'prdc_id' => $prdc_id,
            'offset' => $offset,
            'limit' => $limit
        ]);

        return $res;
    }

    public function prdc_detail($prdc_id, $beat_id)
    {
        $res = DB::select("
        SELECT
            beat_title,
            CT.cate_title,
            MT.mood_title,
            beat_time,
            beat_tag,
            beat_price,
            BT.created_at,
            (
                SELECT
                    COUNT(beat_id)
                FROM
                    beat_like
                WHERE
                    beat_id = :beat_id
                GROUP BY
                    beat_id
            )AS beat_like
        FROM
            beat BT
        JOIN category CT ON
            BT.cate_id = CT.cate_id
        JOIN mood MT ON
            BT.mood_id = MT.mood_id
        WHERE
            BT.beat_id = :beat_id
	    AND BT.prdc_id = :prdc_id
        ", ['prdc_id' => $prdc_id, 'beat_id' => $beat_id]);

        return $res;
    }

    public function by_prdc_id($prdc_id)
    {
        $user = auth('api')->user();
        $user_id = empty($user) ? null : $user->user_id;

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
        tops AS
        (
            SELECT
                r.beat_rank,
                r.beat_id,
                b.beat_title,
                b.beat_thumb,
                b.beat_price,
                b.beat_tag,
                b.beat_path->>'clip' AS beat_url,
                r.beat_hit,
                r.beat_like,
                r.beat_order,
                p.prdc_id,
                p.prdc_nick,
                m.mood_id,
                m.mood_title,
                c.cate_id,
                c.cate_title
            FROM beat b, ranks r, producer p, mood m, category c
            WHERE 1 = 1
                AND b.beat_id = r.beat_id
                AND b.prdc_id = p.prdc_id
                AND b.mood_id = m.mood_id
                AND b.cate_id = c.cate_id
                AND b.prdc_id = :prdc_id
                AND p.state = 1
                AND b.state = 1
            ORDER BY r.beat_rank DESC, r.beat_id ASC
        )
        SELECT
            tops.*,
            bls.bl_id,
            bls.bl_state
        FROM tops
        LEFT JOIN bls ON tops.beat_id = bls.beat_id
        ORDER BY tops.beat_rank DESC, tops.beat_id ASC
        ", [
            'user_id' => $user_id,
            'prdc_id' => $prdc_id
        ]);

        return $res;
    }

    public function by_prdc_id_top_10($prdc_id)
    {
        $res = DB::select("
        SELECT
            r.beat_rank,
            r.beat_id,
            b.beat_title,
            b.beat_thumb,
            b.beat_tag,
            p.prdc_id,
            p.prdc_nick
        FROM beat b, producer p, ranks r
        WHERE 1 = 1
            AND b.prdc_id = :prdc_id::int
            AND b.prdc_id = p.prdc_id
            AND b.beat_id = r.beat_id
            AND b.state = 1
            AND p.state = 1
        ORDER BY r.beat_rank DESC, r.beat_id ASC
        LIMIT 10
        ", [
            'prdc_id' => $prdc_id
        ]);

        return $res;
    }

    public function by_mood_id_top_10($mood_id)
    {
        $res = DB::select("
        SELECT
            r.beat_rank,
            r.beat_id,
            b.beat_title,
            b.beat_thumb,
            b.beat_tag,
            b.mood_id,
            m.mood_title,
            p.prdc_id,
            p.prdc_nick
        FROM beat b, producer p, ranks r, mood m
        WHERE 1 = 1
            AND b.mood_id = :mood_id::int
            AND b.mood_id = m.mood_id
            AND b.prdc_id = p.prdc_id
            AND b.beat_id = r.beat_id
            AND p.state = 1
            AND b.state = 1
        ORDER BY r.beat_rank DESC, r.beat_id ASC
        LIMIT 10
        ", [
            'mood_id' => $mood_id
        ]);

        return $res;
    }

    public function store($prdc_id, $mood_id, $cate_id, $beat_title, $beat_price, $beat_tag, $beat_thumb, $beat_path, $beat_time)
    {
        DB::insert("
        INSERT INTO beat (
            prdc_id,
            mood_id,
            cate_id,
            beat_title,
            beat_price,
            beat_tag,
            beat_thumb,
            beat_path,
            beat_time
        ) VALUES (
            :prdc_id,
            :mood_id,
            :cate_id,
            :beat_title,
            :beat_price,
            :beat_tag,
            :beat_thumb,
            :beat_path,
            :beat_time
        )
        ", [
            'prdc_id' => $prdc_id->prdc_id,
            'mood_id' => $mood_id,
            'cate_id' => $cate_id,
            'beat_title' => $beat_title,
            'beat_price' => $beat_price,
            'beat_tag' => $beat_tag,
            'beat_thumb' => $beat_thumb === null ? 'noimage.jpg' : $beat_thumb,
            'beat_path' => $beat_path,
            'beat_time' =>$beat_time
        ]);

        return true;
    }

    public function update($beat_id, $params = [])
    {
        DB::update("
        UPDATE beat SET
            cate_id = COALESCE(:cate_id::int, cate_id),
            mood_id = COALESCE(:mood_id::int, mood_id),
            beat_title = COALESCE(:beat_title, beat_title),
            beat_tempo = COALESCE(:beat_tempo::int, beat_tempo),
            beat_tag = COALESCE(:beat_tag, beat_tag),
            beat_price = COALESCE(:beat_price::int, beat_price),
            beat_hit = COALESCE(:beat_hit::int, beat_hit),
            beat_path = COALESCE(:beat_path, beat_path),
            beat_thumb = COALESCE(:beat_thumb, beat_thumb),
            beat_free = COALESCE(:beat_free::int, beat_free),
            updated_at = now()
        WHERE 1 = 1
            AND beat_id = :beat_id
        ", [
            'beat_id' => $beat_id,
            'cate_id' => data_get($params, 'cate_id', null),
            'mood_id' => data_get($params, 'mood_id', null),
            'beat_title' => data_get($params, 'beat_title', null),
            'beat_tempo' => data_get($params, 'beat_tempo', null),
            'beat_tag' => data_get($params, 'beat_tag', null),
            'beat_price' => data_get($params, 'beat_price', null),
            'beat_hit' => data_get($params, 'beat_hit', null),
            'beat_path' => data_get($params, 'beat_path', null),
            'beat_thumb' => data_get($params, 'beat_thumb', null),
            'beat_free' => data_get($params, 'beat_free', null)
        ]);

        return true;
    }
    public function beatfree($prdc_id, $beat_id, $beat_free)
    {
        DB::update("
        UPDATE
            beat
        SET
            beat_free = :beat_free
        WHERE
            prdc_id = :prdc_id
            AND beat_id = :beat_id
            AND beat_free != :beat_free
        ", [
            'prdc_id' => $prdc_id,
            'beat_id' => $beat_id,
            'beat_free' => $beat_free
        ]);
        return true;
    }

    public function show($beat_id)
    {
        $user = auth('api')->user();
        $user_id = empty($user) ? null : $user->user_id;

        $beat_info = DB::select("
        WITH bls AS
        (
            SELECT
                bl_id,
                user_id,
                beat_id,
                state AS bl_state
            FROM beat_like
            WHERE 1 <> 1
                OR
                (
                    1 = 1
                    AND :user_id::int IS NOT NULL
                    AND user_id = :user_id::int
                    AND beat_id = :beat_id::int
                )
            LIMIT 1
        ),
        tops AS
        (
            SELECT
                r.beat_rank,
                r.beat_id,
                b.beat_title,
                b.beat_time,
                b.beat_tempo,
                b.beat_tag,
                b.beat_thumb,
                b.beat_price,
                b.beat_path->>'clip' AS beat_url,
                r.beat_hit,
                r.beat_like,
                r.beat_order,
                p.prdc_id,
                m.mood_id,
                c.cate_id,
                b.beat_free
            FROM beat b, ranks r, producer p, mood m, category c
            WHERE 1 = 1
                AND b.beat_id = :beat_id
                AND b.beat_id = r.beat_id
                AND b.prdc_id = p.prdc_id
                AND b.mood_id = m.mood_id
                AND b.cate_id = c.cate_id
                AND p.state = 1
                AND b.state = 1
            LIMIT 1
        )
        SELECT
            tops.*,
            bls.bl_id,
            bls.bl_state
        FROM tops
        LEFT JOIN bls ON tops.beat_id = bls.beat_id
        LIMIT 1
        ", [
            'user_id' => $user_id,
            'beat_id' => $beat_id
        ]);

        return data_get($beat_info, 0, null);
    }

    public function list_by_keyword($params = [])
    {
        $res = DB::select("
        WITH
        bls AS
        ( SELECT
                bl_id,
                user_id,
                beat_id,
                state AS bl_state
            FROM beat_like
            WHERE 1 = 1
                AND user_id = COALESCE(:user_id::int, NULL)
        ),
        tops AS
        ( SELECT
                r.beat_rank,
                r.beat_id,
                b.beat_title,
                b.beat_thumb,
                b.beat_price,
                b.beat_path->>'clip' AS beat_url,
                b.beat_tag,
                r.beat_hit,
                r.beat_like,
                r.beat_order,
                p.prdc_id,
                p.prdc_nick,
                m.mood_id,
                m.mood_title,
                c.cate_id,
                c.cate_title
            FROM beat b, ranks r, producer p, mood m, category c
            WHERE 1 = 1
                AND b.beat_id = r.beat_id
                AND b.prdc_id = p.prdc_id
                AND b.mood_id = m.mood_id
                AND b.cate_id = c.cate_id
                AND p.state = 1
                AND b.state = 1
                AND
                (
                    1 <> 1
                    OR lower(b.beat_title) LIKE ('%' || lower(COALESCE(:word, '')) || '%')
                    OR lower(p.prdc_nick) LIKE ('%' ||  lower(COALESCE(:word, '')) || '%')
                )
                AND lower(b.beat_tag) LIKE '%' || lower(COALESCE(:tag, ''))  || '%'
            ORDER BY r.beat_rank DESC, r.beat_id ASC
            OFFSET COALESCE(:offset::int, 0) LIMIT COALESCE(:limit::int, 100)
        )
        SELECT
            tops.*,
            bls.bl_id,
            bls.bl_state
        FROM tops
        LEFT JOIN bls ON tops.beat_id = bls.beat_id
        ORDER BY tops.beat_rank DESC, tops.beat_id ASC
        ", [
            'user_id' => data_get(auth('api')->user(), 'user_id', null),
            'tag' => data_get($params, 'tag', null),
            'word' => data_get($params, 'word', null),
            'offset' => data_get($params, 'offset', null),
            'limit' => data_get($params, 'limit', null)
        ]);

        return $res;
    }
    public function by_user_folder($pf_id)
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
                AND user_id = COALESCE(:user_id::int, NULL)
        ),
        playfolders AS
        (
            SELECT
                ORDINALITY AS json_array_order,
                value::int4 AS beat_id
            FROM json_array_elements_text(
                (
                    SELECT
                        beat_json::json
                    FROM playfolder
                    WHERE 1 = 1
                    AND user_id = :user_id
                    AND pf_id = :pf_id
                )
            ) with ORDINALITY
        ),
        beats AS
        (
            SELECT
                r.beat_id,
                b.beat_title,
                b.beat_thumb,
                b.beat_price,
                b.beat_path->>'clip' AS beat_url,
                r.beat_like,
                p.prdc_id,
                p.prdc_nick,
                m.mood_id,
                m.mood_title,
                c.cate_id,
                c.cate_title
            FROM beat b, ranks r, producer p, mood m, category c
            WHERE 1 = 1
                AND b.beat_id = r.beat_id
                AND b.prdc_id = p.prdc_id
                AND b.mood_id = m.mood_id
                AND b.cate_id = c.cate_id
                AND p.state = 1
                AND b.state = 1
        )
        SELECT
            beats.*,
            bls.bl_id,
            bls.bl_state
        FROM playfolders
        JOIN beats ON playfolders.beat_id = beats.beat_id
        LEFT JOIN bls ON playfolders.beat_id = bls.beat_id
        ORDER BY playfolders.json_array_order ASC
        ", [
            'pf_id'=>$pf_id,
            'user_id'=>auth('api')->user()->user_id
        ]);
        return $res;
    }
    public function toDeleteState($user_id, $beat_id)
    {
        $res = DB::update("
        UPDATE
            beat
        SET
            state = 0
        WHERE
            prdc_id = :prdc_id
            AND beat_id = :beat_id
            AND state = 1
        ", [
            'prdc_id' => $user_id,
            'beat_id' => $beat_id
        ]);
        return $res;
    }

    public function hit($beat_id)
    {
        DB::update("
        UPDATE beat SET
            beat_hit = coalesce(beat_hit, 0) + 1,
            updated_at = now()
        WHERE 1 = 1
            AND beat_id = :beat_id
        ", [
            'beat_id' => $beat_id
        ]);

        return true;
    }
}
