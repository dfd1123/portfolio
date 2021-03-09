<?php

namespace App\Classes;

use DB;

class MedicineInfo
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            mdcn_no,
            mdcn_title,
            mdcn_desc,
            mdcn_thumb,
            mdcn_link,
            mdcn_extra,
            state
        FROM medicine_info
        WHERE 1 = 1
            AND mdcn_no = coalesce(:mdcn_no, mdcn_no)
            AND mdcn_title like concat('%', :mdcn_title::text, '%')
        ORDER BY mdcn_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO medicine_info (
            mdcn_title,
            mdcn_desc,
            mdcn_thumb,
            mdcn_link,
            mdcn_extra,
            state
        ) VALUES (
            :mdcn_title,
            :mdcn_desc,
            :mdcn_thumb,
            :mdcn_link,
            :mdcn_extra,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function show($mdcn_no)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'mdcn_no' => $mdcn_no,
            'mdcn_title' => null
        ];

        return data_get($this->index($params), 0, null);
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE medicine_info SET
            mdcn_title = coalesce(:mdcn_title, mdcn_title),
            mdcn_desc = coalesce(:mdcn_desc, mdcn_desc),
            mdcn_thumb = coalesce(:mdcn_thumb, mdcn_thumb),
            mdcn_link = coalesce(:mdcn_link, mdcn_link),
            mdcn_extra = coalesce(:mdcn_extra, mdcn_extra),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND mdcn_no = :mdcn_no
        ", $params);
        
        return $cnt > 0;
    }

    public function list_index($mdcn_no_list)
    {
        $res = DB::select("
        SELECT 
            j.value AS mdcn_no,
            m.mdcn_title,
            m.mdcn_desc,
            m.mdcn_thumb,
            m.mdcn_link,
            m.mdcn_extra
        FROM jsonb_array_elements_text(:mdcn_no_list) j
        JOIN medicine_info m ON j.value::int = m.mdcn_no
        WHERE 1 = 1
            AND m.state = 1
        ", [
            'mdcn_no_list' => $mdcn_no_list
        ]);

        foreach ($res as $row) {
            if (isset($row->mdcn_extra)) {
                $row->mdcn_extra = json_decode($row->mdcn_extra);
            }
        }

        return $res;
    }
}
