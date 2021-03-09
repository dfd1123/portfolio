<?php

namespace App\Http\Controllers\company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
use Auth;

class BiddingController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function company_bidding(){
        $views = view('company_ver.company_bidding.company_bidding');
        $views->title = '입찰';

        $sql = "SELECT
        user_no,
        ARRAY_TO_JSON(
            ARRAY( 
                SELECT 
                    ROW_TO_JSON(tmp2) 
                FROM 
                    ( WITH CTE AS
                        ( SELECT 
                            atb.trd_no,
                            atb.asking_price
                        FROM 
                            agent_trades_bidding atb 
                        WHERE 
                            agt_no = :user_no) 
                    SELECT 
                        TT.trd_no, 
                        TT.trd_name, 
                        TT.trd_area, 
                        TT.trd_budget, 
                        TT.address,
                        TT.is_premium,
                        TT.state,
                        BLT.bl_name,
                        CTE.asking_price,
                        TT.created_at::date
                    FROM 
                        trades TT 
                    JOIN 
                        CTE 
                    ON 
                        CTE.trd_no = TT.trd_no
                    LEFT JOIN
                        business_list BLT
                    ON
                        BLT.bl_no = TT.bl_no
                    WHERE 
                        TT.trd_no = CTE.trd_no
                    AND 
                        TT.state = 1
                    OR 
                        TT.state = 2
                    ORDER BY TT.trd_no DESC
                    OFFSET 0 LIMIT 10)tmp2)) 
                    AS 
                        bidding_info
    FROM
        users
    WHERE
        user_no = :user_no";
        $p['user_no'] = auth()->user()->user_no;
        $res = $this->execute_query($sql, $p);
        $info = $res['query'];
        
        if(count(json_decode($info[0]->bidding_info)) > 0){
            $views->bidding_info = json_decode($info[0]->bidding_info);
        }else{
            $views->bidding_info = array();
        }
        
        return $views;
    }
    public function company_bidding_detail(Request $request){
        $views = view('company_ver.company_bidding.company_bidding_detail');
        $views->title = '견적사항 자세히보기';

        $sql = "SELECT
        trd_name,
        trd_area,
        trd_budget,
        address,
        trd_img,
        other_remark_txt,
        UT.name,
        BL.bl_name,
        is_premium
    FROM
        trades
    LEFT JOIN users UT
    ON user_no = client_no
    LEFT JOIN business_list BL
    ON BL.bl_no = trades.bl_no
    WHERE
        trd_no = :trd_no";
        $res = $this->execute_query($sql, array('trd_no'=>$request->trd_no));
        $views->info = $res['query'][0];
        return $views;
    }
    public function company_bidding_regist(){
        $views = view('company_ver.company_bidding.company_bidding_regist');
        $views->title = '입찰신청';
        return $views;
    }
}
