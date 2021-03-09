<?php

namespace App\Classes;

use Auth;
use DB;

class Notice
{
    public function index($offset, $limit)
    {
        $res = DB::select("
        SELECT
	notice_id,
	notice_title,
	notice_content
FROM
	NOTICE
ORDER BY
	notice_id DESC OFFSET :offset
LIMIT :limit
        ",[
            'offset' => $offset,
            'limit' => $limit
        ]);

        return $res;
    }
}
