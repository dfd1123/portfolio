<?php

namespace App\Classes;

use DB;

class HealthReport
{
    public function index(array $params)
    {
        $res = DB::select("
            SELECT 
                hr.hr_no,
                hr.hr_reg_dt,
                hr.state,
                ubt.ubt_no,
                u.usr_no,
                u.usr_name,
                bt.bt_no,
                bt.bt_order,
                (
                    SELECT
                        COALESCE(jsonb_agg(hrp.dc_no), '[]'::jsonb)
                    FROM health_report_page hrp
                    WHERE 1 = 1
                        AND hrp.hr_no = hr.hr_no
                    LIMIT 3
                ) AS disease_list
            FROM health_report hr
            JOIN usr_batch ubt ON hr.ubt_no = ubt.ubt_no
            JOIN users u ON u.usr_no = ubt.usr_no
            JOIN batch bt ON bt.bt_no = ubt.bt_no
            WHERE 1 = 1
                AND hr.hr_no = coalesce(:hr_no, hr.hr_no)
                AND ubt.ubt_no = coalesce(:ubt_no, ubt.ubt_no)
                AND ubt.usr_no = coalesce(:usr_no, ubt.usr_no)
                AND bt.bt_no = coalesce(:bt_no, bt.bt_no)
                AND bt.bt_order = coalesce(:bt_order, bt.bt_order)
                AND hr.state = coalesce(:state, hr.state)
                AND ubt.state  = coalesce(:ubt_state, ubt.state)
                AND bt.state = coalesce(:bt_state, bt.state)
            ORDER BY hr.hr_no DESC
            OFFSET :offset LIMIT :limit
        ", $params);

        foreach ($res as $row) {
            try {
                $row->completed = $this->complete_percent($row->usr_no);
            } catch (Exception $e) {
                $row->completed = null;
            }
        }

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO health_report (
            ubt_no
        ) VALUES (
            :ubt_no
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE health_report SET
            state = :state
        WHERE 1 = 1
            AND hr_no = :hr_no
        ", $params);
        
        return $cnt > 0;
    }

    public function complete_percent($usr_no)
    {
        $res = DB::select("
        WITH user_batches AS (
            SELECT
                hr.hr_no,
                hr.ubt_no,
                ubt.ubt_start,
                ubt.ubt_end,
                EXTRACT(DAY FROM date_trunc('day', ubt.ubt_end) - date_trunc('day', ubt.ubt_start))::int + 1 AS pt_term
            FROM health_report hr
            JOIN usr_batch ubt ON hr.ubt_no = ubt.ubt_no
            WHERE 1 = 1
                AND ubt.usr_no = :usr_no
                AND ubt.state = 1
            LIMIT 1
        ), user_plans AS (
            SELECT
                upt.upt_no,
                (SELECT ubt_no FROM user_batches) AS ubt_no,
                upt.upt_list,
                (SELECT pt_term FROM user_batches) AS pt_term
            FROM usr_plan upt
            WHERE 1 = 1
                AND date_trunc('day', upt.upt_reg_dt) >= date_trunc('day', (SELECT ubt_start FROM user_batches))
                AND date_trunc('day', upt.upt_reg_dt) <= date_trunc('day', (SELECT ubt_end FROM user_batches))
                AND usr_no = :usr_no
        ), user_activities AS (
            SELECT
                upt_no,
                ubt_no,
                jsonb_array_elements(upt_list) AS item,
                pt_term
            FROM user_plans
        )
        SELECT
            TRUNC((SUM(value::decimal / total::decimal) / pt_term::decimal) * 100::decimal, 2) AS completed
        FROM 
        (
            SELECT
                :usr_no AS usr_no,
                upt_no,
                ubt_no,
                SUM((item->>'result')::int) AS value,
                COUNT(upt_no) AS total,
                pt_term
            FROM user_activities
            GROUP BY upt_no, ubt_no, pt_term
        ) AS results
        GROUP BY pt_term
        ", [
            'usr_no' => $usr_no
        ]);

        return data_get(data_get($res, 0, null), 'completed', null);
    }
}
