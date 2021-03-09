<?php

namespace App\Classes;

use DB;

class HealthInfo
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            hlth_no,
            hlth_title,
            hlth_desc,
            hlth_thumb,
            hlth_link,
            hlth_extra,
            state
        FROM health_info
        WHERE 1 = 1
            AND hlth_no = coalesce(:hlth_no, hlth_no)
            AND hlth_title like concat('%', :hlth_title::text, '%')
        ORDER BY hlth_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO health_info (
            hlth_title,
            hlth_desc,
            hlth_thumb,
            hlth_link,
            hlth_extra,
            state
        ) VALUES (
            :hlth_title,
            :hlth_desc,
            :hlth_thumb,
            :hlth_link,
            :hlth_extra,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function show($hlth_no)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'hlth_no' => $hlth_no,
            'hlth_title' => null
        ];

        return data_get($this->index($params), 0, null);
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE health_info SET
            hlth_title = coalesce(:hlth_title, hlth_title),
            hlth_desc = coalesce(:hlth_desc, hlth_desc),
            hlth_thumb = coalesce(:hlth_thumb, hlth_thumb),
            hlth_link = coalesce(:hlth_link, hlth_link),
            hlth_extra = coalesce(:hlth_extra, hlth_extra),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND hlth_no = :hlth_no
        ", $params);
        
        return $cnt > 0;
    }

    public function list_index($hlth_no_list)
    {
        $res = DB::select("
        SELECT 
            j.value AS hlth_no,
            h.hlth_title,
            h.hlth_desc,
            h.hlth_thumb,
            h.hlth_link,
            h.hlth_extra
        FROM jsonb_array_elements_text(:hlth_no_list) j
        JOIN health_info h ON j.value::int = h.hlth_no
        ", [
            'hlth_no_list' => $hlth_no_list
        ]);

        foreach ($res as $row) {
            if (isset($row->hlth_extra)) {
                $row->hlth_extra = json_decode($row->hlth_extra);
            }
        }

        return $res;
    }
}
