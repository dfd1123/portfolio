<?php

namespace App\Classes;

use Auth;
use DB;

class User
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            user_id,
            user_no,
            gbn,
            sta,
            name,
            deptcd,
            dept,
            majorcd,
            major,
            collcd,
            coll,
            stdyear,
            created_at,
            updated_at
        FROM users
        WHERE 1 = 1
            AND user_id = COALESCE(:user_id, user_id)
            AND user_no = COALESCE(:user_no, user_no)
            AND stdyear = COALESCE(:stdyear, stdyear)
            AND name like CONCAT('%', COALESCE(:name, name) ,'%')
        ORDER BY user_id DESC
        LIMIT :limit OFFSET :offset
        ", [
            'user_id' => data_get($params, 'user_id'),
            'user_no' => data_get($params, 'user_no'),
            'name' => data_get($params, 'name'),
            'stdyear' => data_get($params, 'stdyear'),
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ?? PHP_INT_MAX
        ]);
        
        return $res;
    }

    public function without(array $params)
    {
        $res = DB::select("
        SELECT
            user_id,
            user_no,
            gbn,
            sta,
            name,
            deptcd,
            dept,
            majorcd,
            major,
            stdyear,
            created_at,
            updated_at
        FROM users
        WHERE 1 = 1
            AND user_no != COALESCE(:user_no, user_no)
        ORDER BY user_id DESC
        LIMIT :limit OFFSET :offset
        ", [
            'user_no' => data_get($params, 'user_no'),
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ?? PHP_INT_MAX
        ]);
        
        return $res;
    }
}
