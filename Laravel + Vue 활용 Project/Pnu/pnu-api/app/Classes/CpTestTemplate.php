<?php

namespace App\Classes;

use Auth;
use DB;

class CpTestTemplate
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            cpt_id,
            cpt_order,
            cpt_title,
            cpt_title_en,
            cpt_desc,
            cpt_question,
            created_at,
            updated_at
        FROM cpt_template
        WHERE 1 = 1
            AND cpt_id = COALESCE(:cpt_id, cpt_id)
            AND cpt_order = COALESCE(:cpt_order, cpt_order)
        ORDER BY cpt_order ASC
        LIMIT :limit OFFSET :offset
        ", [
            'cpt_id' => data_get($params, 'cpt_id'),
            'cpt_order' => data_get($params, 'cpt_order'),
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ?? PHP_INT_MAX
        ]);
        
        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO cpt_template (
            cpt_order,
            cpt_title,
            cpt_title_en,
            cpt_desc,
            cpt_question,
            admin_id,
            created_at,
            updated_at
        ) VALUES (
            :cpt_order,
            :cpt_title,
            :cpt_title_en,
            :cpt_desc,
            :cpt_question,
            :admin_id,
            now(),
            now()
        )", [
            'cpt_order' => data_get($params, 'cpt_order'),
            'cpt_title' => data_get($params, 'cpt_title'),
            'cpt_title_en' => data_get($params, 'cpt_title_en'),
            'cpt_desc' => data_get($params, 'cpt_desc'),
            'cpt_question' => data_get($params, 'cpt_question'),
            'admin_id' => data_get($params, 'admin_id')
        ]);
        
        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE cpt_template SET
            cpt_order = coalesce(:cpt_order, cpt_order),    
            cpt_title = coalesce(:cpt_title, cpt_title),
            cpt_title_en = coalesce(:cpt_title_en, cpt_title_en),
            cpt_desc = coalesce(:cpt_desc, cpt_desc),
            cpt_question = coalesce(:cpt_question, cpt_question),
            admin_id = coalesce(:admin_id, admin_id),
            updated_at = now()
        WHERE 1 = 1
            AND cpt_id = :cpt_id
        ", [
            'cpt_id' =>  data_get($params, 'cpt_id'),
            'cpt_order' => data_get($params, 'cpt_order'),
            'cpt_title' => data_get($params, 'cpt_title'),
            'cpt_title_en' => data_get($params, 'cpt_title_en'),
            'cpt_desc' => data_get($params, 'cpt_desc'),
            'cpt_question' => data_get($params, 'cpt_question'),
            'admin_id' => data_get($params, 'admin_id')
        ]);
        
        return $cnt > 0;
    }
    
    public function max()
    {
        $res = DB::select("
        SELECT
            MAX(cpt_order) AS max
        FROM cpt_template
        WHERE 1 = 1
            AND cpt_question IS NOT NULL
        ", []);
        
        return data_get(data_get($res, 0), 'max', 0);
    }

    public function count_questions()
    {
        $res = DB::select("
        SELECT 
            SUM(JSON_LENGTH(JSON_EXTRACT(cpt_question , '$[*][*]' ))) AS count
        FROM cpt_template
        ", []);
        
        return data_get(data_get($res, 0), 'count', 0);
    }
}
