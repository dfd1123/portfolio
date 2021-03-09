<?php

namespace App\Classes;

use Auth;
use DB;

class Alarm
{
    public function index($offset, $limit)
    {
        $user = auth('api')->user();
        $user_id = empty($user) ? null : $user->user_id;
        $res = DB::select("
        SELECT
	alarm_id,
	alarm_content,
	created_at
FROM
	alarm
WHERE
	user_id = :user_id
ORDER BY
    alarm_id DESC
OFFSET :offset LIMIT :limit
        ",[
            'user_id' => $user_id,
            'offset' => $offset,
            'limit' => $limit
        ]);

        return $res;
    }
    public function isCurrentMsg()
    {
        $user = auth('api')->user();
        $user_id = empty($user) ? null : $user->user_id;
        $res = DB::select("
        SELECT
	alarm_id,
	alarm_content,
	created_at
FROM
	alarm
WHERE
    user_id = :user_id
AND created_at > now() - INTERVAL '3 DAY'
ORDER BY
    alarm_id DESC
        ",[
            'user_id' => $user_id
        ]);

        return $res;
    }
    
}
