<?php

namespace App\Classes;

use DB;

class Batch
{
    public function index(array $params)
    {
        $res = DB::select("
        WITH usr_count AS (
            SELECT
                bt_no,
                count(*) AS bt_use
            FROM usr_batch
            GROUP BY bt_no
        )
        SELECT
            b.bt_no,
            b.bt_order,
            to_char(b.bt_start, 'YYYY-MM-DD') AS bt_start,
            to_char(b.bt_end, 'YYYY-MM-DD') AS bt_end,
            b.bt_memo,
            b.bt_max,
            uc.bt_use,
            to_char(b.bt_reg_dt, 'YYYY-MM-DD') AS bt_reg_dt,
            b.bt_qna_list,
            b.state
        FROM batch b
        LEFT JOIN usr_count uc ON uc.bt_no = b.bt_no
        WHERE 1 = 1
            AND b.bt_no = coalesce(:bt_no, b.bt_no)
        ORDER BY b.bt_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO batch (
            bt_order,
            bt_start,
            bt_end,
            bt_memo,
            bt_max,
            state
        ) VALUES (
            :bt_order,
            :bt_start,
            :bt_end,
            :bt_memo,
            :bt_max,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE batch SET
            bt_start = coalesce(:bt_start, bt_start),
            bt_end = coalesce(:bt_end, bt_end),
            bt_memo = coalesce(:bt_memo, bt_memo),
            bt_max = coalesce(:bt_max, bt_max),
            bt_qna_list = coalesce(:bt_qna_list, bt_qna_list),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND bt_no = :bt_no
        ", $params);
        
        return $cnt > 0;
    }

    public function user_index(array $params)
    {
        $res = DB::select("
        SELECT
            bt.bt_no,
            bt.bt_order,
            to_char(bt.bt_start, 'YYYY-MM-DD') AS bt_start,
            to_char(bt.bt_end, 'YYYY-MM-DD') AS bt_end,
            bt.bt_memo,
            bt.state AS bt_state,
            ubt.ubt_no,
            ubt.usr_no,
            to_char(ubt.ubt_start, 'YYYY-MM-DD') AS ubt_start,
            to_char(ubt.ubt_end, 'YYYY-MM-DD') AS ubt_end,
            EXTRACT(DAY FROM date_trunc('day', now()) - date_trunc('day', ubt.ubt_start))::int + 1 AS pt_day,
            EXTRACT(DAY FROM date_trunc('day', ubt.ubt_end) - date_trunc('day', ubt.ubt_start))::int + 1 AS pt_term,
            ubt.state AS ubt_state,
            hr.hr_no,
            (
                SELECT max(mhr.ubt_no) = ubt.ubt_no
                FROM health_report mhr
                WHERE 1 = 1
                    AND mhr.state = 1
            ) AS ubt_latest,
            (
                SELECT
                    COALESCE(jsonb_agg(hrp.dc_no), '[]'::jsonb)
                FROM health_report_page hrp
                WHERE 1 = 1
                    AND hrp.hr_no = hr.hr_no
                LIMIT 3
            ) AS disease_list
        FROM batch bt
            LEFT JOIN usr_batch ubt ON bt.bt_no = ubt.bt_no AND ubt.usr_no = :usr_no
            LEFT JOIN health_report hr ON hr.ubt_no = ubt.ubt_no
            LEFT JOIN users u ON u.usr_no = ubt.usr_no
        ORDER BY bt.bt_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }
}
