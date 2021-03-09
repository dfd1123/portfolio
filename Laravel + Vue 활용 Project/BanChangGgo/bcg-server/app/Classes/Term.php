<?php

namespace App\Classes;

use Auth;
use DB;

class Term
{
    public function index()
    {
        $res = DB::select("
        SELECT
            settings.version,
            terms_service,
            privacy_policy,
            pay_terms_service
        FROM
            settings
        ORDER BY version DESC
        LIMIT 1
        ");

        foreach ($res as $row) {
            $row->terms_service = nl2br($row->terms_service);
            $row->privacy_policy = nl2br($row->privacy_policy);
            $row->pay_terms_service = nl2br($row->pay_terms_service);
        }

        return $res;
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE settings SET
            version = coalesce(:version, version),
            terms_service = coalesce(:terms_service, privacy_policy),
            privacy_policy = coalesce(:privacy_policy, privacy_policy),
            pay_terms_service = coalesce(:pay_terms_service, pay_terms_service)
        ", $params);
        
        return $cnt > 0;
    }
}
