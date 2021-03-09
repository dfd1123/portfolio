<?php

namespace App\Classes;

use DB;

class PlanTemplate
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            pt_no,
            pt_type,
            pt_title,
            pt_time,
            pt_memo,
            pt_day,
            bt_order,
            state
        FROM plan_template
        WHERE 1 =1 
            AND pt_type = coalesce(:pt_type, pt_type)
            AND pt_day = coalesce(:pt_day, pt_day)
            AND bt_order = coalesce(:bt_order, bt_order)
            AND state = coalesce(:state, state)
        ORDER BY TO_TIMESTAMP(pt_time, 'HH24:MI')::TIME ASC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO plan_template (
            pt_type,
            pt_title,
            pt_time,
            pt_memo,
            pt_day,
            bt_order,
            state
        ) VALUES (
            :pt_type,
            :pt_title,
            :pt_time,
            :pt_memo,
            :pt_day,
            :bt_order,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE plan_template SET
            pt_type = coalesce(:pt_type, pt_type),
            pt_title = coalesce(:pt_title, pt_title),
            pt_time = coalesce(:pt_time, pt_time),
            pt_memo = coalesce(:pt_memo, pt_memo),
            pt_day = coalesce(:pt_day, pt_day),
            bt_order = coalesce(:bt_order, bt_order),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND pt_no = :pt_no
        ", $params);
        
        return $cnt > 0;
    }
}
