<?php

namespace App\Classes;

use Auth;
use DB;

class Test
{
    public function index()
    {
        $res = DB::select("
        SELECT
            id,
            value
        FROM test
        WHERE 1 = 1
        ORDER BY id DESC
        ");

        return $res;
    }

    public function store($value)
    {
        DB::insert("
        INSERT INTO test (
            value
        ) VALUES (
            :value
        )
        ", ['value' => $value]);
        
        return true;
    }

    public function show($id)
    {
        $res = DB::select("
        SELECT
            id,
            value
        FROM test
        WHERE 1 = 1
        AND id = :id
        ORDER BY id DESC
        ", ['id' => $id]);
        
        // 배열을 리턴하지 않음 값이 없으면 null
        return data_get($res, 0, null);
    }

    public function update($id, $value)
    {
        DB::update("
        UPDATE test SET
            value = :value
        WHERE 1 = 1
        AND id = :id
        ", ['id' => $id, 'value' => $value]);
        
        return true;
    }

    public function destroy($id)
    {
        DB::delete("
        DELETE FROM test
        WHERE 1 = 1
        AND id = :id
        ", ['id' => $id]);

        return true;
    }
}
