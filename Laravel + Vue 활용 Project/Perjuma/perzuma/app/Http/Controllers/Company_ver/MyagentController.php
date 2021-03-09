<?php

namespace App\Http\Controllers\company_ver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;

class MyagentController extends Controller
{
    public function __construct(){
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function company_myagent(){
        $views = view('company_ver.company_myagent.company_myagent');

        $sql = "SELECT
        UT.user_no,
        AIT.agent_name,
        AIT.agent_construction_cnt,
        AIT.agent_review_cnt,
        AIT.agent_rating,
        UT.name,
        UT.user_contact,
        UT.email,
        AIT.agent_tel_number,
        AIT.agent_contact,
        AIT.agent_addr,
        AIT.agent_detailaddr,
        AIT.agent_profile_img,
        AIT.extra_info,
        array_to_json(array(SELECT row_to_json(tmp1) 
            FROM (SELECT rv_no
            , rv_title
            , rv_content
            , rv_imgs
            , review.reg_dt::date
            , rv_point
            , users.name
            , trades.trd_name
                FROM review 
                LEFT JOIN users
                ON users.user_no = review.client_no 
                LEFT JOIN trades
                ON trades.trd_no = ctrt_no
                WHERE review.agent_no =:user_no )tmp1))
            AS review_info 
    FROM
        users UT
    LEFT JOIN agent_info AIT ON
        AIT.agent_no = UT.user_no
    WHERE
        user_no = :user_no
        AND user_grade = 2";

        $p['user_no'] = auth()->user()->user_no;//업체 token 받아서 넣기
        $res = $this->execute_query($sql, $p);
        $views->agent = $res['query'];
        $views->profileimg = json_decode($res['query'][0]->agent_profile_img);
        $views->info = json_decode($res['query'][0]->extra_info);
        $views->review = json_decode($res['query'][0]->review_info);
        //dd(json_decode($res['query'][0]->review_info)[0]->rv_imgs[0]);
        return $views;
    }
}
