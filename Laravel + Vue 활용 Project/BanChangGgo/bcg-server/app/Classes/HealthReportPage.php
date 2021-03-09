<?php

namespace App\Classes;

use DB;

class HealthReportPage
{
    public function index(array $params)
    {
        $res = DB::select("
        SELECT 
            hrp.hrp_no,
            hrp.mdcn_info,
            hrp.ntrcn_info,
            hrp.health_info,
            hrp.hrp_reg_dt,
            hrp.hrp_comment,
            hrp.hrp_comment_detail,
            hrp.hrp_comment_med,
            hrp.hrp_extra,
            hr.hr_no,
            hr.ubt_no,
            dc.dc_no,
            dc.dc_cat_name,
            ubt.ubt_start,
            ubt.ubt_end,
            bt.bt_no,
            bt.bt_order,
            u.usr_no,
            u.usr_name,
            a.adm_no,
            a.adm_name,
            a.adm_thumb
        FROM health_report_page hrp
        JOIN health_report hr ON hrp.hr_no = hr.hr_no 
        JOIN disease_cat dc ON hrp.dc_no = dc.dc_no
        JOIN usr_batch ubt ON hr.ubt_no = ubt.ubt_no
        JOIN batch bt ON ubt.bt_no = bt.bt_no
        JOIN users u ON ubt.usr_no = u.usr_no
        JOIN admins a ON hrp.adm_no = a.adm_no
        WHERE 1 = 1
            AND hrp.hrp_no = coalesce(:hrp_no, hrp.hrp_no)
            AND hr.hr_no = coalesce(:hr_no, hr.hr_no)
            AND ubt.ubt_no = coalesce(:ubt_no, ubt.ubt_no)
            AND bt.bt_no = coalesce(:bt_no, bt.bt_no)
            AND bt.bt_order = coalesce(:bt_order, bt.bt_order)
            AND u.usr_no = coalesce(:usr_no, u.usr_no)
        ORDER BY hrp.hrp_no DESC
        OFFSET :offset LIMIT :limit
        ", $params);

        return $res;
    }

    public function valid(array $params)
    {
        $res = DB::select("
        with ds as (
            select jsonb_array_elements_text(disease_list)::int as dc_no
            from health_report
            where hr_no = :hr_no 
        ) 
        select h.dc_no
        from health_report_page h join ds 
            on h.dc_no = ds.dc_no
        where h.hr_no = :hr_no", $params);
    
        return $res;
    }

    public function store(array $params)
    {
        DB::insert("
        INSERT INTO health_report_page (
            hrp_comment,
            hrp_comment_detail,
            hrp_comment_med,
            mdcn_info,
            ntrcn_info,
            health_info,
            dc_no,
            hr_no,
            adm_no
        ) VALUES (
            :hrp_comment,
            :hrp_comment_detail,
            :hrp_comment_med,
            :mdcn_info,
            :ntrcn_info,
            :health_info,
            :dc_no,
            :hr_no,
            :adm_no
        )", $params);

        return DB::getPdo()->lastInsertId();
    }

    public function update(array $params)
    {
        $cnt = DB::update("
        UPDATE health_report_page SET
            hrp_comment = coalesce(:hrp_comment, hrp_comment),
            hrp_comment_detail = coalesce(:hrp_comment_detail, hrp_comment_detail),
            hrp_comment_med = coalesce(:hrp_comment_med, hrp_comment_med),
            mdcn_info = coalesce(:mdcn_info, mdcn_info),
            ntrcn_info = coalesce(:ntrcn_info, ntrcn_info),
            health_info = coalesce(:health_info, health_info),
            dc_no = coalesce(:dc_no, dc_no),
            hr_no = coalesce(:hr_no, hr_no)
        WHERE 1 = 1
            AND hrp_no = :hrp_no
        ", $params);
        
        return $cnt > 0;
    }

    public function destroy(array $params)
    {
        $cnt = DB::update("
        DELETE FROM health_report_page
        WHERE hrp_no = :hrp_no
        ", $params);
        
        return $cnt > 0;
    }
}
