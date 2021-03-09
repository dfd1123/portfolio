<?php

namespace App\Classes;

use Auth;
use DB;

class Example
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT
            id,
            value
        FROM test
        WHERE 1 = 1
            AND value = coalesce(:param, value)
        ORDER BY id DESC
        ", [
            'param' => data_get($params, 'param')
        ]);
        
        // 배열 리턴
        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO test (
            value
        ) VALUES (
            :param
        )", [
            'param' => data_get($params, 'param')
        ]);
        
        // lastInsertId
        return DB::getPdo()->lastInsertId();
    }

    public function show(/* $user_id, */ $id /*, $param1, $param2... */)
    {
        $res = DB::select("
        SELECT
            id,
            value
        FROM test
        WHERE 1 = 1
            AND id = :id
        ORDER BY id DESC
        ", [
            'id' => $id
        ]);
        
        // 배열을 리턴하지 않음 값이 없으면 null
        return data_get($res, 0, null);
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE test SET
            value = coalesce(:value, value)
        WHERE 1 = 1
            AND id = :id
        ", [
            /* 'user_id' => data_get($params, 'user_id'), */
            'id' => data_get($params, 'id'),
            'param' => data_get($params, 'param')
        ]);
        
        // 업데이트된 열이 있는지 체크
        return $cnt > 0;
    }

    public function destroy(array $params)
    {
        $cnt = DB::delete("
        DELETE FROM test
        WHERE 1 = 1
            AND id = :id
        ", [
            'id' => data_get($params, 'id')
        ]);
        
        // 삭제된 열이 있는지 체크
        return $cnt > 0;
    }

    public function any($param)
    {
        // 기타
    }
}
