<?php

namespace App\Classes;

use Auth;
use DB;

class Batch
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            batch_id,
            batch_name,
            batch_start,
            batch_end,
            (SELECT COUNT(DISTINCT user_id) FROM user_cpt WHERE batch_id = batch.batch_id) AS batch_count,
            admin_id,
            created_at,
            updated_at
        FROM batch
        WHERE 1 = 1
            AND batch_id = COALESCE(:batch_id, batch_id)
            AND batch_name = COALESCE(:batch_name, batch_name)
        ORDER BY batch_id DESC
        LIMIT :limit OFFSET :offset
        ", [
            'batch_id' => data_get($params, 'batch_id'),
            'batch_name' => data_get($params, 'batch_name'),
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ??  PHP_INT_MAX
        ]);
        
        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO batch (
            batch_name,
            batch_start,
            batch_end,
            admin_id,
            created_at,
            updated_at
        ) VALUES (
            :batch_name,
            :batch_start,
            :batch_end,
            :admin_id,
            now(),
            now()
        )", [
            'batch_name' => data_get($params, 'batch_name'),
            'batch_start' => data_get($params, 'batch_start'),
            'batch_end' => data_get($params, 'batch_end'),
            'admin_id' => data_get($params, 'admin_id')
        ]);
        
        return DB::getPdo()->lastInsertId();
    }

    
    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE batch SET
            batch_name = coalesce(:batch_name, batch_name),
            batch_start = coalesce(:batch_start, batch_start),
            batch_end = coalesce(:batch_end, batch_end),
            admin_id = coalesce(:admin_id, admin_id),
            updated_at = now()
        WHERE 1 = 1
            AND batch_id = :batch_id
        ", [
            'batch_id' =>  data_get($params, 'batch_id'),
            'batch_name' => data_get($params, 'batch_name'),
            'batch_start' => data_get($params, 'batch_start'),
            'batch_end' => data_get($params, 'batch_end'),
            'admin_id' => data_get($params, 'admin_id')
        ]);
        
        return $cnt > 0;
    }
    
    public function available()
    {
        $res = DB::select("
        SELECT
            batch_id
        FROM batch
        WHERE 1 = 1
            AND DATE(batch_start) <= DATE(now())
            AND DATE(batch_end) >= DATE(now())
        ORDER BY batch_id DESC
        LIMIT 1
        ", []);
        
        return data_get(data_get($res, 0), 'batch_id');
    }

    public function latest()
    {
        $res = DB::select("
        SELECT
            batch_id
        FROM batch
        ORDER BY batch_id DESC
        LIMIT 1
        ", []);
        
        return data_get(data_get($res, 0), 'batch_id');
    }
}
