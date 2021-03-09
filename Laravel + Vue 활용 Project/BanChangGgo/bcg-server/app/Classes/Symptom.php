<?php

namespace App\Classes;

use DB;

class Symptom
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            spt_no,
            spt_title,
            spt_thumb,
            spt_contents,
            state
        FROM symptom
        WHERE 1 = 1
            AND spt_no = coalesce(:spt_no, spt_no)
        ORDER BY spt_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO symptom (
            spt_title,
            spt_thumb,
            spt_contents,
            state
        ) VALUES (
            :spt_title,
            :spt_thumb,
            :spt_contents,
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function show($spt_no)
    {
        $params = [
            'offset' => 0,
            'limit' => 1,
            'spt_no' => $spt_no,
        ];

        return data_get($this->index($params), 0, null);
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE symptom SET
            spt_title = coalesce(:spt_title, spt_title),
            spt_thumb = coalesce(:spt_thumb, spt_thumb),
            spt_contents = coalesce(:spt_contents, spt_contents),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND spt_no = :spt_no
        ", $params);
        
        return $cnt > 0;
    }
}
