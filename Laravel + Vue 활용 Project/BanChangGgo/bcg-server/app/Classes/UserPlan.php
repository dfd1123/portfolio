<?php

namespace App\Classes;

use DB;

class UserPlan
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            upt.upt_no,
            upt.upt_type,
            upt.upt_list,
            upt.upt_reg_dt,
            u.usr_no,
            u.usr_name,
            u.usr_extra
        FROM usr_plan upt JOIN users u
            ON upt.usr_no = u.usr_no
        WHERE 1 = 1
            AND u.usr_no = coalesce(:usr_no, u.usr_no)
            AND upt.upt_no = coalesce(:upt_no, upt.upt_no)
            AND upt.upt_type = coalesce(:upt_type, upt.upt_type)
            AND upt.upt_reg_dt::date >= coalesce(:start_dt, upt.upt_reg_dt)::date
            AND upt.upt_reg_dt::date < (coalesce(:end_dt, upt.upt_reg_dt) + INTERVAL '1 day')::date
        ORDER BY upt.upt_no ASC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function show($usr_no, $upt_no)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'usr_no' => $usr_no,
            'upt_no' => $upt_no,
            'upt_type' => null,
            'start_dt' => null,
            'end_dt' => null
        ];

        $user_plan = data_get($this->index($params), 0, null);
        if ($user_plan != null) {
            $user_plan->upt_list = json_decode($user_plan->upt_list);
            $user_plan->usr_extra = json_decode($user_plan->usr_extra);
        }

        return $user_plan;
    }

    public function store($params)
    {
        // >>> psql bcg
        // SELECT * FROM pg_available_extensions;
        // CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

        DB::insert("
        UPDATE usr_plan SET
            upt_list = 
            (
                SELECT 
                    json_agg(value ORDER BY TO_TIMESTAMP(value->>'time', 'HH24:MI')::TIME ASC)
                FROM jsonb_array_elements(upt_list
                    || jsonb_build_array(jsonb_build_object(
                        'id', uuid_generate_v4(),
                        'title', :title::text, 
                        'time', :time::text, 
                        'memo', :memo::text, 
                        'push', :push::int, 
                        'result', 0, 
                        'kind', :kind::int,
                        'updated_dt', now()))
                )
            )
        WHERE 1 = 1
            AND usr_no = :usr_no
            AND upt_no = :upt_no
        ", $params);

        return $params['upt_no'];
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        WITH plan_lists AS (
            SELECT 
                INDEX-1 AS path,
                upt_item AS item
            FROM usr_plan, jsonb_array_elements(upt_list) WITH ORDINALITY arr(upt_item, INDEX)
            WHERE 1 = 1
                AND upt_item->>'id' = :id::text
                AND usr_no = :usr_no
                AND upt_no = :upt_no
        )
        UPDATE usr_plan SET
            upt_list =
            (
                SELECT 
                    json_agg(value ORDER BY value->>'result' ASC, to_timestamp(value->>'time', 'HH24:MI')::time ASC)
                FROM jsonb_array_elements(jsonb_set(
                    upt_list, array[plan_lists.path]::text[],
                    upt_list->plan_lists.path::int || jsonb_build_object(
                        'id', plan_lists.item->>'id'::text,
                        'title', coalesce(:title::text, plan_lists.item->>'title'),
                        'time', coalesce(:time::text, plan_lists.item->>'time'),
                        'memo', coalesce(:memo::text, plan_lists.item->>'memo'),
                        'push', coalesce(:push::int, (plan_lists.item->>'push')::int),
                        'result', coalesce(:result::int, (plan_lists.item->>'result')::int),
                        'kind', coalesce(:kind::int, (plan_lists.item->>'kind')::int),
                        'updated_dt', now()),
                    FALSE
                ))
            )
        FROM plan_lists
        WHERE 1 = 1
            AND usr_no = :usr_no
            AND upt_no = :upt_no        
        ", $params);

        return $cnt > 0;
    }

    public function destroy(array $params)
    {
        $cnt = DB::delete("
        UPDATE usr_plan SET
            upt_list = 
            (
                SELECT coalesce(jsonb_agg(value), '[]')
                FROM usr_plan, jsonb_array_elements(upt_list)
                WHERE 1 = 1
                    AND usr_no = :usr_no
                    AND upt_no = :upt_no
                    AND value->>'id' != :id::TEXT
            )
        WHERE 1 = 1
            AND usr_no = :usr_no
            AND upt_no = :upt_no
        ", $params);
        
        return $cnt > 0;
    }

    public function assert($usr_no, $upt_type)
    {
        $res = DB::SELECT("
        WITH inserts AS (
            INSERT INTO usr_plan (
                usr_no,
                upt_type
            )
            SELECT
                :usr_no,
                :upt_type::text
            WHERE NOT EXISTS
            (
                SELECT upt_no
                FROM usr_plan
                WHERE 1 = 1
                    AND usr_no = :usr_no
                    AND upt_reg_dt >= now()::date
                    AND upt_type = :upt_type::TEXT
            )
            RETURNING upt_no
        )
        SELECT COALESCE
        ( 
            (
                SELECT upt_no 
                FROM inserts
            ), (
                SELECT upt_no 
                FROM usr_plan
                WHERE 1 = 1
                    AND usr_no = :usr_no
                    AND upt_reg_dt >= now()::date
                    AND upt_type = :upt_type::TEXT
            )
        ) AS upt_no
        ", [
            'usr_no' => $usr_no,
            'upt_type' => $upt_type
        ]);
        
        // NULL이 반환되면 안됨(유저타입에 해당되는 컨텐츠가 없을 시 발생)
        return data_get(data_get($res, 0), 'upt_no');
    }

    public function assert_plan($usr_no, $upt_no, $bt_order = null, $pt_day = null)
    {
        $res = DB::update("
        WITH default_values AS (
            SELECT
                m.bt_order AS default_order,
                mod(extract(DAY FROM now())::int, m.pt_day) + 1 AS default_day,
                m.pt_type AS default_type
            FROM 
            (
                SELECT
                    pt.bt_order,
                    pt.pt_day,
                    pt.pt_type
                FROM plan_template pt
                JOIN batch bt ON pt.bt_order = bt.bt_order
                WHERE 1 = 1
                    AND pt.pt_type = (SELECT upt_type AS default_type FROM usr_plan WHERE upt_no = :upt_no)
                    AND bt.state != 0
                ORDER BY bt_order DESC, pt_day DESC
                LIMIT 1
            ) m
        )
        UPDATE usr_plan SET
            upt_list = 
            COALESCE(
                (
                    SELECT
                        array_to_json(array_agg(t))
                    FROM
                    (
                        SELECT
                            pt.pt_no::text AS id,
                            pt.pt_title AS title,
                            pt.pt_time AS time,
                            pt.pt_memo AS memo,
                            0 AS push,
                            0 AS result,
                            pt.pt_kind AS kind,
                            now() AS updated_dt
                        FROM plan_template pt
                        WHERE 1 = 1
                            AND pt.pt_type = (SELECT default_type FROM default_values)
                            AND pt.bt_order = COALESCE(:bt_order, (SELECT default_order FROM default_values))
                            AND pt.pt_day = COALESCE(:pt_day, (SELECT default_day FROM default_values))
                        ORDER BY TO_TIMESTAMP(pt.pt_time, 'HH24:MI')::TIME ASC
                    ) AS t
                ),
                '[]'
            )
        WHERE 1 = 1
            AND usr_no = :usr_no
            AND upt_no = :upt_no
            AND
            (
                upt_list IS NULL OR jsonb_array_length(upt_list) = 0
            )
        ", [
            'usr_no' => $usr_no,
            'upt_no' => $upt_no,
            'bt_order' => $bt_order,
            'pt_day' => $pt_day
        ]);
        
        return $res > 0;
    }
}
