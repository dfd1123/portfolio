<?php

namespace App\Classes;

use Auth;
use DB;

class DiseaseCategory
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            dc_no,
            dc_cat_name,
            coalesce(dc_cat_etc, '') AS dc_cat_etc,
            state
        FROM disease_cat
        WHERE 1 = 1
            AND dc_no = coalesce(:dc_no, dc_no)
            AND dc_cat_name like concat('%', :dc_cat_name::text, '%')
            AND state = coalesce(:state, state)
        ORDER BY dc_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO disease_cat (
            dc_cat_name,
            dc_cat_etc,
            state
        ) VALUES (
            :dc_cat_name,
            coalesce(:dc_cat_etc, ''),
            :state
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE disease_cat SET
            dc_cat_name = coalesce(:dc_cat_name, dc_cat_name),
            dc_cat_etc = coalesce(:dc_cat_etc, dc_cat_etc),
            state = coalesce(:state, state)
        WHERE 1 = 1
            AND dc_no = :dc_no
        ", $params);
        
        return $cnt > 0;
    }

    public function list_index($dc_no_list)
    {
        $res = DB::select("
        SELECT 
            j.value AS dc_no,
            dc_cat_name,
            coalesce(dc_cat_etc, '') AS dc_cat_etc
        FROM jsonb_array_elements_text(:dc_no_list) j
        LEFT JOIN disease_cat dc ON j.value::int = dc.dc_no
        WHERE 1 = 1
            AND state = 1
        ", [
            'dc_no_list' => $dc_no_list
        ]);

        return $res;
    }
}
