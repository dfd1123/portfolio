<?php

namespace App\Classes;

use Auth;
use DB;

class Faq
{
    public function index()
    {
        $res = DB::select("
        SELECT
	faq_id,
	faq_question,
	faq_answer
FROM
	faq
ORDER BY
	faq_id DESC OFFSET 0
LIMIT 20
        ");

        return $res;
    }
}
