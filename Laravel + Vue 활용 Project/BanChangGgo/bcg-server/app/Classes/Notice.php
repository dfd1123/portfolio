<?php

namespace App\Classes;

use DB;

class Notice
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            ntc_no,
            ntc_title,
            ntc_content,
            to_char(ntc_reg_dt, 'YYYY-MM-DD') AS ntc_reg_dt
        FROM notice
        ORDER BY ntc_reg_dt DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO notice (
            ntc_title,
            ntc_content
        ) VALUES (
            :ntc_title,
            :ntc_content
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE notice SET
            ntc_title = coalesce(:ntc_title, ntc_title),
            ntc_content = coalesce(:ntc_content, ntc_content)
        WHERE 1 = 1
            AND ntc_no = :ntc_no
        ", $params);
        
        return $cnt > 0;
    }

    public function destroy(array $params){
        $cnt = DB::delete("
        DELETE FROM notice
        WHERE 1 = 1
            AND ntc_no = :ntc_no
        ", $params);
        
        return $cnt > 0;
    }
}
