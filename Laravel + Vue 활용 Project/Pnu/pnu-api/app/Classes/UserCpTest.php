<?php

namespace App\Classes;

use Auth;
use DB;

class UserCpTest
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            ucpt.ucpt_id,
            ucpt.ucpt_answer,
            ucpt.user_id,
            ucpt.cpt_id,
            ucpt.created_at,
            ucpt.updated_at
            cpt.cpt_id,
            cpt.cpt_order,
            cpt.cpt_title,
            cpt.cpt_title_en,
            cpt.cpt_desc
        FROM user_cpt ucpt
        JOIN cpt_template cpt ON ucpt.cpt_id = cpt.cpt_id
        JOIN batch bt ON ucpt.batch_id = bt.batch_id
        WHERE 1 = 1
            AND ucpt.ucpt_id = COALESCE(:ucpt_id, ucpt.ucpt_id)
            AND ucpt.user_id = COALESCE(:user_id, ucpt.user_id)
            AND ucpt.batch_id = COALESCE(:batch_id, ucpt.batch_id)
        ORDER BY ucpt.ucpt_id DESC
        LIMIT :limit OFFSET :offset
        ", [
            'ucpt_id' => data_get($params, 'ucpt_id'),
            'user_id' => data_get($params, 'user_id'),
            'batch_id' => data_get($params, 'batch_id'),
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ?? PHP_INT_MAX
        ]);
        
        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO user_cpt (
            user_id,
            cpt_id,
            batch_id,
            ucpt_answer,
            created_at,
            updated_at
        ) VALUES (
            :user_id,
            :cpt_id,
            :batch_id,
            :ucpt_answer,
            now(),
            now()
        )", [
            'user_id' => data_get($params, 'user_id'),
            'cpt_id' => data_get($params, 'cpt_id'),
            'batch_id' => data_get($params, 'batch_id'),
            'ucpt_answer' => data_get($params, 'ucpt_answer')
            ]);
        
        return DB::getPdo()->lastInsertId();
    }

    public function latest_cpt_order($user_id, $batch_id)
    {
        $latest = DB::select("
        SELECT
            MAX(cpt.cpt_order) cpt_order
        FROM user_cpt ucpt
        JOIN cpt_template cpt ON ucpt.cpt_id = cpt.cpt_id
        WHERE 1 = 1
            AND ucpt.user_id = :user_id
            AND ucpt.batch_id = :batch_id
        LIMIT 1
        ", [
            'user_id' => $user_id,
            'batch_id' => $batch_id
        ]);

        return data_get(data_get($latest, 0), 'cpt_order', 0);
    }

    public function count_answered($user_id)
    {
        $res = DB::select("
        SELECT 
            SUM(JSON_LENGTH(JSON_EXTRACT(ucpt_answer , '$[*][*].value' ))) AS count
        FROM user_cpt
        WHERE 1 = 1
            AND user_id = :user_id 
        ", [
            'user_id' => $user_id
        ]);
        
        return data_get(data_get($res, 0), 'count', 0);
    }

    public function delete_record($user_id, $batch_id)
    {
        $cnt = DB::delete("
        DELETE FROM user_cpt
        WHERE 1 = 1
            AND user_id = :user_id
            AND batch_id = :batch_id
        ", [
            'user_id' => $user_id,
            'batch_id' => $batch_id
        ]);
        
        // 삭제된 열이 있는지 체크
        return $cnt > 0;
    }
}
