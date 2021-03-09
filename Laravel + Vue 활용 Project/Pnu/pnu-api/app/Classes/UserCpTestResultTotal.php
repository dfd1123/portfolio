<?php

namespace App\Classes;

use Auth;
use DB;

class UserCpTestResultTotal
{
    public function index(array $params)
    {
        $order_by = data_get($params, 'order_by');
        if ($order_by === 'desc') {
            $order_by = 'DESC';
        } elseif ($order_by === 'asc') {
            $order_by = 'ASC';
        } else {
            $order_by = 'DESC';
        }

        $sort_by = data_get($params, 'sort_by');
        if ($sort_by === 'user_no') {
            $sort_by = 'user_no';
        } elseif ($sort_by === 'user_name') {
            $sort_by = 'name';
        } elseif ($sort_by === 'total') {
            $sort_by = 'total';
        } else {
            $sort_by = 'user_no';
        }

        $totals = DB::select("
        SELECT
            u.user_id,
            u.user_no,
            u.sta,
            u.stdyear,
            u.dept,
            u.major,
            u.name,
            sum(sum_array_cells(JSON_EXTRACT( ucpt.ucpt_answer, '$[*][*].value' ))) AS total
        FROM user_cpt ucpt
        JOIN users u ON ucpt.user_id = u.user_id
        WHERE 1 = 1
            AND ucpt.batch_id = coalesce(:batch_id, ucpt.batch_id)
            AND u.user_no = coalesce(:user_no, u.user_no)
            AND u.name like CONCAT('%',coalesce(:user_name, u.name), '%')
        GROUP BY ucpt.user_id
        ORDER BY $sort_by $order_by
        LIMIT :limit OFFSET :offset
        ", [
            'user_no' => data_get($params, 'user_no'),
            'user_name' => data_get($params, 'user_name'),
            'batch_id' => data_get($params, 'batch_id'),
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ?? PHP_INT_MAX
        ]);

        return $totals;
    }
}
