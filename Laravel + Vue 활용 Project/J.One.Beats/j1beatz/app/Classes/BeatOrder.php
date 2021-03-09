<?php

namespace App\Classes;

use Auth;
use DB;

class BeatOrder
{
    public function index($params)
    {
        $start_date = data_get($params, 'start_date', null);
        $start_date = $start_date === null ?  null : $start_date . " 00:00:00";
        $end_date = data_get($params, 'end_date', null);
        $end_date = $end_date === null ?  null : $end_date . " 23:59:59";

        $res = DB::select("
        WITH producers AS(
            SELECT
                prdc_id,
                prdc_nick
            FROM
                producer
        ),
        beats AS (
            SELECT
                beat_id,
                prdc_id,
                beat_title,
                beat_price,
                beat_thumb,
                CASE
                    WHEN beat_path->>'mp3' IS NULL THEN 0
                    ELSE 1
                END AS mp3,
                CASE
                    WHEN beat_path->>'wav' IS NULL THEN 0
                    ELSE 1
                END AS wav,
                state
            FROM
                beat
        ) SELECT
            po_id,
            b.beat_title,
            p.prdc_nick,
            o.created_at,
            po_pg_type,
            o.beat_price,
            b.beat_thumb,
            b.wav,
            o.state,
            (o.created_at + INTERVAL '7' DAY >= now()) available
        FROM
            beat_order o,
            producers p,
            beats b
        WHERE
            o.user_id = :user_id
            AND o.beat_id = b.beat_id
            AND b.prdc_id = p.prdc_id
            AND COALESCE(:start_date::timestamp, o.created_at) <= o.created_at
            AND COALESCE(:end_date::timestamp, o.created_at) >= o.created_at
        ORDER BY o.po_id DESC
        OFFSET COALESCE(:offset::int, 0) LIMIT COALESCE(:limit::int, 100)
        ", [
            'user_id' => data_get(auth('api')->user(), 'user_id', null),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'offset' => data_get($params, 'offset', null),
            'limit' => data_get($params, 'limit', null),
        ]);

        return $res;
    }

    public function store($user_id, $cart_id, $lo_pg_type)
    {
        $res = DB::SELECT("
        INSERT INTO beat_order (
            user_id,
            beat_id,
            beat_price,
            po_pg_type,
            state
        )
        SELECT
            c.user_id,
            c.beat_id,
            b.beat_price,
            :lo_pg_type::int AS po_pg_type,
            1 AS state
        FROM cart c, beat b
        WHERE 1 = 1
            AND c.user_id = :user_id
            AND c.cart_id = :cart_id
            AND c.beat_id = b.beat_id
        LIMIT 1
        RETURNING po_id
        ", [
            'user_id' => $user_id,
            'cart_id' => $cart_id,
            'lo_pg_type' => $lo_pg_type
        ]);

        return data_get(data_get($res, 0, []), 'po_id', null);
    }

    public function available($user_id, $beat_id)
    {
        // 결제완료된 유효한 구매내역만 체크
        $res = DB::SELECT("
        SELECT
            po_id
        FROM beat_order
        WHERE 1 = 1
            AND user_id = :user_id
            AND beat_id = :beat_id
            AND created_at + INTERVAL '7' DAY >= now()
            AND state = 2
        LIMIT 1
        ", [
            'user_id' => $user_id,
            'beat_id' => $beat_id
        ]);

        return data_get(data_get($res, 0, []), 'po_id', null);
    }

    public function activate($user_id, $po_id, $pg_info = null)
    {
        DB::update("
        UPDATE beat_order SET
            state = 2,
            pg_info = :pg_info
        WHERE 1 = 1
            AND user_id = :user_id
            AND po_id = :po_id
            AND state = 1
        ", [
            'user_id' => $user_id,
            'po_id' => $po_id,
            'pg_info' => $pg_info
        ]);
        
        return true;
    }

    public function revenue($sub)
    {
        $prdc_id = auth('api')->user()->user_id;
        $where = '';
        if ($sub == 'total') {
            $where='';
        } elseif ($sub == 'posible') {
            $where = 'AND coalesce(po_state, 0) = 0';
        } elseif ($sub == 'already') {
            $where = 'AND po_state = 2';
        }
        $res = DB::select("
        WITH beat_orders AS (
            SELECT
                beat_id,
                COUNT(beat_id) count
            FROM beat_order
            WHERE 1 = 1
            AND state = 2
            ".$where."
            GROUP BY beat_id
        )
        SELECT COALESCE(NULLIF(SUM((beat_price - round(beat_price * 0.20)) * BOTS.count)::TEXT, '')::NUMERIC, 0) AS count
        FROM beat BT
        JOIN beat_orders BOTS ON BT.beat_id = BOTS.beat_id
        WHERE prdc_id = :prdc_id
        ", [
            'prdc_id' => $prdc_id
        ]);
        return $res;
    }

    public function revenue_at_beat($prdc_id)
    {
        $res = DB::select("
        SELECT
            bo.po_id,
            b.beat_id,
            b.beat_title,
            c.cate_title,
            m.mood_title,
            bo.po_pg_type,
            bo.beat_price,
            round(bo.beat_price * 0.20) fee,
            bo.beat_price - round(bo.beat_price * 0.20) total,
            COALESCE(bo.po_state, 0) AS po_state,
            bo.created_at,
            bo.po_reg_dt,
            bo.po_cpl_dt
        FROM beat_order bo
        LEFT JOIN beat b ON bo.beat_id = b.beat_id
        LEFT JOIN category c ON b.cate_id = c.cate_id
        LEFT JOIN mood m ON b.mood_id = m.mood_id
        WHERE 1 = 1
            AND bo.beat_id = b.beat_id
            AND b.prdc_id = :prdc_id
            AND bo.state = 2
        ORDER BY po_id DESC
        ", ['prdc_id' => $prdc_id]);

        return $res;
    }

    public function freeAndPaid_downloadCount()
    {
        $prdc_id = auth('api')->user()->user_id;
        $res = DB::select("
        WITH brhs_free AS (
            SELECT
                COUNT(beat_id) AS free_count,
                beat_id
            FROM
                beat_request_history
            WHERE
                brh_type = 2
            GROUP BY
                beat_id
        ), brhs_paid AS (
            SELECT
                COUNT(beat_id) AS paid_count,
                beat_id
            FROM
                beat_order
            WHERE
                state = 2
            GROUP BY
                beat_id
        )
        SELECT
            b.prdc_id,
            SUM(COALESCE(NULLIF(f.free_count::TEXT, '')::NUMERIC, 0)) AS free_count,
            SUM(COALESCE(NULLIF(p.paid_count::TEXT, '')::NUMERIC, 0)) AS paid_count
        FROM
            beat b
        LEFT JOIN brhs_free f ON
            b.beat_id = f.beat_id
        LEFT JOIN brhs_paid p ON
            b.beat_id = p.beat_id
        WHERE
            prdc_id = :prdc_id
        GROUP BY
            prdc_id
        ", [
            'prdc_id' => $prdc_id
        ]);
        return $res;
    }
    
    public function registRevenue($prdc_id, $po_id)
    {
        $res = DB::update("
        UPDATE beat_order bo SET
            po_state = 1,
            po_reg_dt = NOW()
        FROM beat b
        WHERE 1 = 1
            AND b.beat_id = bo.beat_id
            AND b.prdc_id = :prdc_id
            AND bo.po_id = :po_id
            AND bo.state = 2
            AND coalesce(bo.po_state, 0) = 0
        ", [
            'prdc_id' => $prdc_id,
            'po_id' => $po_id
        ]);
        return $res;
    }
}
