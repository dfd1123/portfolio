<?php

namespace App\Http\Controllers\Company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class RequestController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }
    public function company_request(){
        $views = view('company_ver.company_request.company_request');
        $views->title = '시공요청';

        $sql = "SELECT
        user_no,
        ARRAY_TO_JSON(
            ARRAY( 
                SELECT 
                    ROW_TO_JSON(tmp1) 
                FROM 
                    ( SELECT
	TT.trd_no,
    TT.trd_name,
    TT.trd_area,
    TT.trd_budget,
    TT.address ,
    TT.is_premium,
    BLT.bl_name,
    created_at::DATE
FROM
	trades TT
LEFT JOIN business_list BLT ON
    BLT.bl_no = TT.bl_no
WHERE
	(
		SELECT
			COUNT(TBT.trd_no)
		FROM
			agent_trades_bidding TBT
		WHERE
			TBT.trd_no = TT.trd_no
	) < 3

    offset 0 limit 10)tmp1)) 
                    AS 
                        staff_info 
    FROM
        users
    WHERE
        user_no = :user_no";
        $p['user_no'] = auth()->user()->user_no;//업체 token 받아서 넣기
        $res = $this->execute_query($sql, $p);
        $info = $res['query'];

        $staff_info = json_decode($info[0]->staff_info);

        if(count($staff_info) == 0){
            $views->staff_no = array();
        }else{
            $views->staff_no = $staff_info;
        }

        return $views;
    }
}
