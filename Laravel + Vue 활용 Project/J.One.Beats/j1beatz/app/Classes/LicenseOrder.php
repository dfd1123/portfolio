<?php

namespace App\Classes;

use Auth;
use DB;

class LicenseOrder
{
    public function store($user_id, $lcens_id, $lo_pg_type, $autopay = 0)
    {
        $res = DB::SELECT("
        INSERT INTO license_order (
            user_id,
            lcens_id,
            lo_pg_type,
            autopay,
            state,
            created_at
        ) VALUES (
            :user_id,
            :lcens_id,
            :lo_pg_type,
            :autopay,
            1,
            now()
        )
        RETURNING lo_id
        ", [
            'user_id' => $user_id,
            'lcens_id' => $lcens_id,
            'lo_pg_type' => $lo_pg_type,
            'autopay' => $autopay
        ]);

        return data_get(data_get($res, 0, []), 'lo_id', null);
    }

    public function update($user_id, $lo_id, $autopay)
    {
        DB::update("
        UPDATE license_order SET
            autopay = :autopay
        WHERE 1 = 1
            AND user_id = :user_id
            AND lo_id = :lo_id
        ", [
            'user_id' => $user_id,
            'lo_id' => $lo_id,
            'autopay' => $autopay,
        ]);

        return true;
    }

    public function find($user_id, $lcens_id, $state)
    {
        $res = DB::select("
        SELECT
            lo_id,
            lo_pg_type,
            autopay,
            pg_info
        FROM license_order
        WHERE 1 = 1
            AND user_id = :user_id
            AND lcens_id = :lcens_id
            AND state = :state
        ORDER BY lo_id DESC
        LIMIT 1
        ", [
            'user_id' => $user_id,
            'lcens_id' => $lcens_id,
            'state' => $state
        ]);

        return data_get($res, 0, null);
    }

    public function activate($user_id, $lo_id, $pg_info = null)
    {
        DB::update("
        UPDATE license_order lo SET
            state = 2,
            pg_info = :pg_info,
            reg_at = now(),
            end_at = now() + (INTERVAL '1' DAY * l.lcens_days)
        FROM license l
        WHERE 1 = 1
            AND lo.user_id = :user_id
            AND lo.lo_id = :lo_id
            AND lo.lcens_id = l.lcens_id
            AND l.state = 1
        ", [
            'user_id' => $user_id,
            'lo_id' => $lo_id,
            'pg_info' => $pg_info
        ]);
        
        return true;
    }

    public function available($user_id)
    {
        $license_info = DB::select("
        SELECT
            lo.lo_id,
            lo.lcens_id,
            l.lcens_name,
            l.lcens_type,
            lo.lo_pg_type,
            lo.autopay,
            lo.reg_at,
            lo.end_at
        FROM license_order lo, license l
        WHERE 1 = 1
            AND lo.user_id = :user_id
            AND lo.lcens_id = l.lcens_id
            AND lo.state = 2
            AND l.state = 1
            AND lo.end_at >= now()
        ORDER BY lo.lo_id DESC
        LIMIT 1
        ", [
            'user_id' => $user_id
        ]);

        return data_get($license_info, 0, null);
    }

    public function renew($user_id, $lcens_id, $lo_pg_type, $pg_info)
    {
        $res = DB::SELECT("
        INSERT INTO license_order (
            user_id,
            lcens_id,
            reg_at,
            end_at,
            lo_pg_type,
            autopay,
            pg_info,
            state,
            created_at
        ) SELECT
            :user_id,
            :lcens_id,
            now(),
            now() + (INTERVAL '1' DAY * (l.lcens_days + 1)),
            :lo_pg_type,
            1,
            :pg_info,
            2,
            now()
        FROM license l
        WHERE 1 = 1
            AND l.lcens_id = :lcens_id
            AND l.state = 1
        LIMIT 1
        RETURNING lo_id
        ", [
            'user_id' => $user_id,
            'lcens_id' => $lcens_id,
            'lo_pg_type' => $lo_pg_type,
            'pg_info' => $pg_info
        ]);
        
        return data_get(data_get($res, 0, []), 'lo_id', null);
    }
}
