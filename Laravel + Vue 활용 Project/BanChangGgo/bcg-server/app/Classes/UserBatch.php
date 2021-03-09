<?php

namespace App\Classes;

use DB;

class UserBatch
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            ubt.ubt_no,
            to_char(ubt.ubt_start, 'YYYY-MM-DD') AS ubt_start,
            to_char(ubt.ubt_end, 'YYYY-MM-DD') AS ubt_end,
            to_char(ubt.ubt_reg_dt, 'YYYY-MM-DD') AS ubt_reg_dt,
            EXTRACT(DAY FROM date_trunc('day', now()) - date_trunc('day', ubt.ubt_start))::int + 1 AS pt_day,
            EXTRACT(DAY FROM date_trunc('day', ubt.ubt_end) - date_trunc('day', ubt.ubt_start))::int + 1 AS pt_term,
            ubt.ubt_qna_list,
            ubt.state,
            bt.bt_no,
            bt.bt_order,
            u.usr_no,
            u.usr_name,
            (SELECT hr_no FROM health_report WHERE ubt_no = ubt.ubt_no) AS hr_no
        FROM usr_batch ubt 
            JOIN batch bt ON ubt.bt_no = bt.bt_no
            JOIN users u ON ubt.usr_no = u.usr_no
        WHERE 1 = 1
            AND u.usr_no = coalesce(:usr_no, u.usr_no)
            AND ubt.ubt_no = coalesce(:ubt_no, ubt.ubt_no)
            AND bt.bt_order = coalesce(:bt_order, bt.bt_order)
            AND bt.state = coalesce(:bt_state, bt.state)
            AND ubt.state = coalesce(:ubt_state, ubt.state)
        ORDER BY ubt.ubt_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        foreach ($res as $row) {
            if ($row->pt_day <= 0) {
                $row->pt_day = null;
            }

            $row->ubt_qna_list = json_decode($row->ubt_qna_list);
        }

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO usr_batch (
            bt_no,
            usr_no,
            ubt_start,
            ubt_end,
            ubt_qna_list,
            state
        ) VALUES (
            :bt_no,
            :usr_no,
            :ubt_start,
            :ubt_end,
            :ubt_qna_list,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE usr_batch SET
            bt_no = coalesce(:bt_no, bt_no),
            usr_no = coalesce(:usr_no, usr_no),
            ubt_start = coalesce(:ubt_start, ubt_start),
            ubt_end = coalesce(:ubt_end, ubt_end),
            ubt_qna_list = coalesce(:ubt_qna_list, ubt_qna_list),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND ubt_no = :ubt_no
        ", $params);
        
        return $cnt > 0;
    }

    public function avaliable($usr_no)
    {
        $res = DB::select("
        SELECT
            ubt.ubt_no,
            jsonb_array_length(ubt.ubt_qna_list) > 0 AS qna_answered,
            to_char(ubt.ubt_start, 'YYYY-MM-DD') AS ubt_start,
            to_char(ubt.ubt_end, 'YYYY-MM-DD') AS ubt_end,
            to_char(ubt.ubt_reg_dt, 'YYYY-MM-DD') AS ubt_reg_dt,
            EXTRACT(DAY FROM date_trunc('day', now()) - date_trunc('day', ubt.ubt_start))::int + 1 AS pt_day,
            EXTRACT(DAY FROM date_trunc('day', ubt.ubt_end) - date_trunc('day', ubt.ubt_start))::int + 1 AS pt_term,
            bt.bt_no,
            bt.bt_order,
            hr.hr_no,
            hr.state AS hr_state
        FROM usr_batch ubt 
            JOIN batch bt ON ubt.bt_no = bt.bt_no
            LEFT JOIN health_report hr ON hr.ubt_no = ubt.ubt_no AND hr.state = 1
        WHERE 1 = 1
            AND ubt.usr_no = :usr_no
            AND ubt.state = 1
        ORDER BY ubt.ubt_no DESC
        LIMIT 1
        ", [
            'usr_no' => $usr_no
        ]);

        $user_batch = data_get($res, 0, null);

        if (!empty($user_batch)) {
            if ($user_batch->pt_day <= 0) {
                $user_batch->pt_day = null;
            }
        }

        return $user_batch;
    }

    public function activate($usr_no, $bt_no = null)
    {
        $res = DB::select("
        WITH days AS (
            SELECT
                CASE
                WHEN extract('hour' from now()) >= 12 THEN
                    now() + INTERVAL '1 day'
                ELSE
                    now()
                END AS start_date
        )
        INSERT INTO usr_batch (
            bt_no,
            usr_no,
            ubt_start,
            ubt_end,
            ubt_reg_dt,
            ubt_qna_list,
            state
        ) 
        SELECT
            bt.bt_no,
            :usr_no,
            d.start_date,
            d.start_date + INTERVAL '9 day',
            now(),
            '[]',
            1
        FROM batch bt, days d
        WHERE 1 = 1
            AND bt.bt_no = coalesce(:bt_no, bt.bt_no)
            AND date_trunc('day', bt.bt_start) <= date_trunc('day', now())
            AND date_trunc('day', bt.bt_end) >= date_trunc('day', now())
            AND 
            (
                (bt.bt_max = 0) OR ((SELECT count(*) FROM usr_batch WHERE bt_no = :bt_no) < bt.bt_max)
            )
            AND
            (
                (SELECT count(*) FROM usr_batch WHERE bt_no = :bt_no AND usr_no = :usr_no) = 0
            )
            AND bt.state = 1
        ORDER BY bt.bt_no ASC LIMIT 1
        RETURNING ubt_no
        ", [
            'usr_no' => $usr_no,
            'bt_no' => $bt_no
        ]);

        return data_get(data_get($res, 0, null), 'ubt_no', null);
    }
}
