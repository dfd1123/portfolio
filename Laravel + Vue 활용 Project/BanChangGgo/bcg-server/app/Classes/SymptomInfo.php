<?php

namespace App\Classes;

use DB;

class SymptomInfo
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            smptm_no,
            smptm_title,
            smptm_desc,
            smptm_thumb,
            smptm_link,
            smptm_extra,
            state
        FROM symptom_info
        WHERE 1 = 1
            AND smptm_no = coalesce(:smptm_no, smptm_no)
            AND smptm_title like concat('%', :smptm_title::text, '%')
        ORDER BY smptm_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO symptom_info (
            smptm_title,
            smptm_desc,
            smptm_thumb,
            smptm_link,
            smptm_extra,
            state
        ) VALUES (
            :smptm_title,
            :smptm_desc,
            :smptm_thumb,
            :smptm_link,
            :smptm_extra,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function show($smptm_no)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'smptm_no' => $smptm_no,
            'smptm_title' => null
        ];

        return data_get($this->index($params), 0, null);
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE symptom_info SET
            smptm_title = coalesce(:smptm_title, smptm_title),
            smptm_desc = coalesce(:smptm_desc, smptm_desc),
            smptm_thumb = coalesce(:smptm_thumb, smptm_thumb),
            smptm_link = coalesce(:smptm_link, smptm_link),
            smptm_extra = coalesce(:smptm_extra, smptm_extra),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND smptm_no = :smptm_no
        ", $params);
        
        return $cnt > 0;
    }

    public function list_index($smptm_no_list)
    {
        info($smptm_no_list);
        $res = DB::select("
        SELECT 
            smptm_no,
            smptm_title,
            smptm_desc,
            smptm_thumb,
            smptm_link,
            smptm_extra,
            state
        FROM jsonb_array_elements_text(:smptm_no_list) j
        LEFT JOIN symptom_info si ON j.value::int = si.smptm_no
        WHERE 1 = 1
            AND state = 1
        ", [
            'smptm_no_list' => $smptm_no_list
        ]);

        return $res;
    }

    public function destroy(array $params){
        $cnt = DB::update("
        DELETE FROM symptom_info
        WHERE smptm_no = :smptm_no
        ", $params);
        
        return $cnt > 0;
    }
}
