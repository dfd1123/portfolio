<?php

namespace App\Classes;

use Auth;
use DB;

class Qna
{
    public function index($user_id)
    {
        $res = DB::select("
        SELECT
			qna_id,
			qna_title,
			qna_content,
			qna_answer,
			created_at::date
		FROM
			qna
		WHERE
			user_id = :user_id
		ORDER BY
			qna_id DESC OFFSET 0
		LIMIT 20
        ", [
            'user_id' => $user_id
        ]);

        return $res;
    }
    public function store($user_id, $qna_title, $qna_content)
    {
        DB::insert("
        INSERT
		INTO
			qna (
				user_id,
				qna_title,
				qna_content
			)
		VALUES (
			:user_id,
			:qna_title,
			:qna_content
		);
        ", [
            'user_id' => $user_id,
            'qna_title' => $qna_title,
            'qna_content' => $qna_content
        ]);
    }
}
