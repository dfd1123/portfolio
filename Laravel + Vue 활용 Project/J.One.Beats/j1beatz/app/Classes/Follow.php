<?php

namespace App\Classes;

use Auth;
use DB;

class Follow
{
    public function index()
    {
    }

    public function store($user_id, $prdc_id)
    {
        $this->destroy($user_id, $prdc_id);

        DB::insert("
        INSERT INTO follow (
            user_id,
            prdc_id
        ) VALUES (
            :user_id,
            :prdc_id
        )
        ", [
            'user_id' => $user_id,
            'prdc_id' => $prdc_id
        ]);

        return true;
    }

    public function destroy($user_id, $follow_id)
    {
        DB::delete("
        DELETE FROM follow
        WHERE 1 = 1
            AND user_id = :user_id
            AND follow_id = :follow_id
        ", [
            'user_id' => $user_id,
            'follow_id' => $follow_id
        ]);

        return true;
    }

    public function following($user_id, $prdc_id)
    {
        $res = DB::select("
        SELECT
            follow_id,
            user_id,
            prdc_id
        FROM follow
        WHERE 1 = 1
            AND user_id = :user_id
            AND prdc_id = :prdc_id
        LIMIT 1
        ", [
            'user_id' => $user_id,
            'prdc_id' => $prdc_id
        ]);

        return data_get($res, 0, null);
    }
    public function leftheader($user_id)
    {
        $res = DB::select("
        SELECT
            f.follow_id,
            p.prdc_id,
            p.prdc_nick,
            p.prdc_img
        FROM follow f
        JOIN producer p ON f.prdc_id = p.prdc_id
        WHERE 1 = 1
            AND user_id = :user_id
            AND p.state = 1
        ORDER BY f.follow_id DESC
        ", ['user_id' => $user_id]);

        return $res;
    }
}
