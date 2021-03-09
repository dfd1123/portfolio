<?php

namespace App\Classes;

use DB;

class NutritionInfo
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            ntrcn_no,
            ntrcn_title,
            ntrcn_desc,
            ntrcn_thumb,
            ntrcn_link,
            ntrcn_extra,
            state
        FROM nutrition_info
        WHERE 1 = 1
            AND ntrcn_no = coalesce(:ntrcn_no, ntrcn_no)
            AND ntrcn_title like concat('%', :ntrcn_title::text, '%')
        ORDER BY ntrcn_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO nutrition_info (
            ntrcn_title,
            ntrcn_desc,
            ntrcn_thumb,
            ntrcn_link,
            ntrcn_extra,
            state
        ) VALUES (
            :ntrcn_title,
            :ntrcn_desc,
            :ntrcn_thumb,
            :ntrcn_link,
            :ntrcn_extra,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function show($ntrcn_no)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'ntrcn_no' => $ntrcn_no,
            'ntrcn_title' => null
        ];

        return data_get($this->index($params), 0, null);
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE nutrition_info SET
            ntrcn_title = coalesce(:ntrcn_title, ntrcn_title),
            ntrcn_desc = coalesce(:ntrcn_desc, ntrcn_desc),
            ntrcn_thumb = coalesce(:ntrcn_thumb, ntrcn_thumb),
            ntrcn_link = coalesce(:ntrcn_link, ntrcn_link),
            ntrcn_extra = coalesce(:ntrcn_extra, ntrcn_extra),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND ntrcn_no = :ntrcn_no
        ", $params);
        
        return $cnt > 0;
    }

    public function list_index($ntcrn_no_list)
    {
        $res = DB::select("
        SELECT 
            j.value AS ntrcn_no,
            n.ntrcn_title,
            n.ntrcn_desc,
            n.ntrcn_thumb,
            n.ntrcn_link,
            n.ntrcn_extra
        FROM jsonb_array_elements_text(:ntcrn_no_list) j
        JOIN nutrition_info n ON j.value::int = n.ntrcn_no
        WHERE 1 = 1
            AND n.state = 1
        ", [
            'ntcrn_no_list' => $ntcrn_no_list
        ]);

        foreach ($res as $row) {
            if (isset($row->ntrcn_extra)) {
                $row->ntrcn_extra = json_decode($row->ntrcn_extra);
            }
        }

        return $res;
    }
}
