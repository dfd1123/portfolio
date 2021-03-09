<?php

namespace App\Classes;

use Auth;
use DB;

class UserCpTestResult
{
    public function index(array $params)
    {
        //
    }

    public function store(array $params)
    {
        //
    }

    public function result_1($user_id, $batch_id)
    {
        $res = DB::select("
        SELECT
            cpt.cpt_title,
            ucpt.cpt_id,
            SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS VALS,
            COUNT(ucpt.cpt_id) AS count
        FROM user_cpt ucpt
        JOIN users u ON ucpt.user_id = u.user_id 
        LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
        WHERE 1 = 1
            AND ucpt.user_id = :user_id
            AND ucpt.batch_id = :batch_id
        GROUP BY ucpt.cpt_id, cpt.cpt_title
        ", [
            'user_id' => $user_id,
            'batch_id' => $batch_id
        ]);

        return $res;
    }

    public function result_2($user_id)
    {
        $res = DB::select("
        SELECT
            ucpt.ucpt_id,
            ucpt.ucpt_answer,
            ucpt.user_id,
            ucpt.cpt_id,
            ucpt.batch_id,
            ucpt.created_at,
            ucpt.updated_at,
            cpt.cpt_id,
            cpt.cpt_order,
            cpt.cpt_title,
            cpt.cpt_title_en,
            cpt.cpt_desc,
            bt.batch_name
        FROM user_cpt ucpt
        JOIN cpt_template cpt ON ucpt.cpt_id = cpt.cpt_id
        JOIN batch bt ON ucpt.batch_id = bt.batch_id
        WHERE 1 = 1
            AND ucpt.user_id = :user_id
        ORDER BY ucpt.ucpt_id DESC
        ", [
            'user_id' => $user_id
        ]);

        foreach ($res as $row) {
            if ($row->ucpt_answer) {
                $row->ucpt_answer = json_decode($row->ucpt_answer);
            }
        }

        return $res;
    }
    public function result_3()
    {
        $res = DB::select("
        SELECT
	TCT.cpt_title,
	TUC.cpt_id ,
	SUM(sum_array_cells(JSON_EXTRACT( ucpt_answer, '$[*][*].value' ))) AS VALS,
	COUNT(TUC.cpt_id) AS count
FROM
	user_cpt TUC
LEFT JOIN cpt_template TCT ON
	TUC.cpt_id = TCT.cpt_id
WHERE
	batch_id = :batch_id
GROUP BY
	cpt_id
        ", [
            'batch_id' => 1
        ]);

        return $res;
    }

    public function result_4($collcd, $batch_id)
    {
        $res = DB::select("
        SELECT
            cpt.cpt_title,
            ucpt.cpt_id,
            u.coll,
            u.collcd,
            SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS VALS,
            COUNT(ucpt.cpt_id) AS count
        FROM user_cpt ucpt
        JOIN users u ON ucpt.user_id = u.user_id 
        LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
        WHERE 1 = 1
            AND ucpt.batch_id = :batch_id
            AND u.collcd = :collcd
        GROUP BY ucpt.cpt_id, u.coll, u.collcd
        ", [
            'collcd' => $collcd,
            'batch_id' => $batch_id
        ]);

        return $res;
    }

    public function result_5($deptcd, $batch_id)
    {
        $res = DB::select("
        SELECT
            cpt.cpt_title,
            ucpt.cpt_id,
            u.dept,
            u.deptcd,
            SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS VALS,
            COUNT(ucpt.cpt_id) AS count
        FROM user_cpt ucpt
        JOIN users u ON ucpt.user_id = u.user_id 
        LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
        WHERE 1 = 1
            AND ucpt.batch_id = :batch_id
            AND u.deptcd = :deptcd
        GROUP BY ucpt.cpt_id, u.dept, u.deptcd
        ", [
            'deptcd' => $deptcd,
            'batch_id' => $batch_id
        ]);

        return $res;
    }

    public function result_6($stdyear, $batch_id)
    {
        $res = DB::select("
        SELECT
            cpt.cpt_title,
            ucpt.cpt_id,
            u.stdyear,
            SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS VALS,
            COUNT(ucpt.cpt_id) AS count
        FROM user_cpt ucpt
        JOIN users u ON ucpt.user_id = u.user_id 
        LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
        WHERE 1 = 1
            AND ucpt.batch_id = :batch_id
            AND u.stdyear = :stdyear
        GROUP BY ucpt.cpt_id, u.stdyear
        ", [
            'stdyear' => $stdyear,
            'batch_id' => $batch_id
        ]);

        return $res;
    }

    public function raw_total($batch_id)
    {
        $users = DB::select("
        SELECT
            user_id,
            user_no
        FROM
            users
        ");
        
        $result = collect([]);
        foreach ($users as $user) {
            $raws = DB::select("
            SELECT
                ucpt.user_id,
                cpt.cpt_order,
                ucpt.ucpt_answer
            FROM user_cpt ucpt
            JOIN cpt_template cpt ON ucpt.cpt_id = cpt.cpt_id
            WHERE 1 = 1
                AND user_id = :user_id
                AND batch_id = :batch_id
            ORDER BY cpt.cpt_order ASC
            ", [
                'user_id' => $user->user_id,
                'batch_id' => $batch_id
            ]);

            if (!empty($raws)) {
                $datas = collect([]);
                foreach ($raws as $raw) {
                    $datas = $datas->concat(json_decode($raw->ucpt_answer));
                }

                $totals = $datas->flatten(2)->map(function ($item, $key) {
                    return $item->value;
                });

                $result->push(collect([(string) $user->user_no])->concat($totals));
            }
        }

        return $result;
    }
}
