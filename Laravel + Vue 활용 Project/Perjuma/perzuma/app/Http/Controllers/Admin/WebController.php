<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facades\App\Classes\Flushing;

class WebController extends Controller
{

    //차후 컨트롤러 분할하고 파사드관리   (FlushingController)
    private function flushing($request)
    {
        $p = $request->all();

        return Flushing::test();
    }
    
    public function index(Request $request, $page='login')
    {
        $page = strtolower($page);

        $query = null;
        switch ($page) {
            default:
            case 'login':
            $view = "admin.auth.login";
            break;

            case 'main':
            $view = "admin.main.main";
            break;

            case 'users':
            $view = "admin.main.users";
            break;

            case 'user-detail':
            $view = "admin.main.user-detail";
            $query =  $this->user_detail($request);
            break;

            case 'agents':
            $view = "admin.main.agents";
            break;

            case 'agent-detail':
            $view = "admin.main.agent-detail";
            $query =  $this->agent_detail($request);
            break;

            case 'superv':
            $view = "admin.main.superv";
            break;

            case 'superv-detail':
            $view = "admin.main.superv-detail";
            $query = $this->superv_detail($request);
            break;

            case 'superv-regist':
            $view = "admin.main.superv-regist";
            break;

            case 'manager':
            $view = "admin.main.manager";
            break;

            case 'manager-detail':
            $view = "admin.main.manager-detail";
            $query = $this->manager_detail($request);
            break;

            case 'manager-regist':
            $view = "admin.main.manager-regist";
            break;

            case 'logs':
            $view = "admin.main.logs";
            break;

            case 'trades':
            $view = "admin.main.trades";
            break;

            case 'trade-detail':
            $view = "admin.main.trade-detail";
            $query = $this->trade_detail($request);
            break;

            case 'escrow':
            $view = "admin.main.escrow";
            break;

            case 'bl':
            $view = "admin.main.bl";
            //업종
            break;

            case 'bl-regist':
            $view = "admin.main.bl-reigst";
            break;

            case 'bl-update':
            $view = "admin.main.bl-update";
            break;

            case 'notice':
            $view = "admin.main.notice";
            break;

            case 'notice-regist':
            $view = "admin.main.notice-regist";
            break;

            case 'notice-update':
            $view = "admin.main.notice-update";
            $query = $this->notice_detail($request);
            break;
            
            case 'bbs':
            $view = "admin.main.bbs";
            //1:1문의
            break;

            case 'bbs-reply':
            $view = "admin.main.bbs-reply";
            break;

            case 'bbs-detail':
            $view = "admin.main.bbs-detail";
            $query = $this->bbs_detail($request);
            break;
            case 'msg':
            $view = "admin.main.msg";
            break;

            case 'msg-detail':
            $view = "admin.main.msg-detail";
            $query = $this->msg_detail($request);
            break;

            case 'review':
            $view = "admin.main.review";
            break;
            
            case 'review-detail':
            $view = "admin.main.review-detail";
            $query = $this->review_detail($request);
            break;

            case 'flushing':

            return $this ->flushing($request);
        }

        $returnView = view($view);
        $returnView->query = $query;

        return  $returnView;
    }

    private function user_detail($request)
    {
        $p = $request->all();
        if ($request->filled('user_no') && ((int)$request->input('user_no')) >0) {
            $params = array('user_no'=>$p['user_no']);

            $sql ="SELECT
            user_no,
            name ,
            user_contact ,
            email ,
            email_verified_at::date ,
            user_grade ,
            user_thumb,
            state ,
            extra_info ,
            created_at::date ,
            updated_at::date ,
            last_vt_dt::date ,
            array_to_json(array(SELECT row_to_json(tmp1) 
                FROM (SELECT trd_no, trd_name, construct_dt::date, construct_end_dt::date, client_no 
                    FROM trades 
                    WHERE client_no =:user_no 
                    OFFSET 0 
                    LIMIT 10)tmp1))
                AS trade_info ,
            array_to_json(array(SELECT row_to_json(tmp2) 
                FROM (SELECT bbs_no, title, content, ans, reg_dt::date
                    FROM bbs 
                    WHERE user_no =:user_no
                    OFFSET 0 
                    LIMIT 10)tmp2))
                AS bbs_info
        FROM
            users
        WHERE
            user_no = :user_no
        AND
            user_grade = 1;";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }

        return $this->res;
    }
    private function agent_detail($request)
    {
        $p = $request->all();
        if ($request->filled('user_no') && ((int)$request->input('user_no')) >0) {
            $params = array('user_no'=>$p['user_no']);

            $sql ="SELECT
            user_no,
            name ,
            user_contact ,
            email ,
            email_verified_at::date ,
            user_grade ,
            user_thumb,
            state ,
            extra_info ,
            created_at::date ,
            updated_at::date ,
            last_vt_dt::date ,
            array_to_json(array(SELECT row_to_json(tmp1) 
                FROM (SELECT trd_no, trd_name, construct_dt::date, construct_end_dt::date, agent_no 
                    FROM trades 
                    WHERE agent_no =:user_no 
                    OFFSET 0 
                    LIMIT 10)tmp1))
                AS trade_info ,
            array_to_json(array(SELECT row_to_json(tmp2) 
                FROM (SELECT bbs_no, title, content, ans, reg_dt::date
                    FROM bbs 
                    WHERE user_no =:user_no
                    OFFSET 0 
                    LIMIT 10)tmp2))
                AS bbs_info,
            array_to_json(array(SELECT row_to_json(tmp3) 
                FROM (SELECT agent_name, agent_addr, agent_contact,created_at,state
                    FROM agent_info 
                    WHERE agent_no =:user_no)tmp3))
                AS agent_info
        FROM
            users
        WHERE
            user_no = :user_no;";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }

        return $this->res;
    }
    private function superv_detail($request)
    {
        $p = $request->all();
        if ($request->filled('sp_no') && ((int)$request->input('sp_no')) >0) {
            $params = array('sp_no'=>$p['sp_no']);

            $sql ="SELECT
            sp_no,
            sp_name ,
            sp_contact ,
            reg_dt::date ,
            state ,
            array_to_json(array(SELECT row_to_json(tmp1) 
                FROM (SELECT trd_no,
                trd_name, 
                construct_dt::date, 
                construct_end_dt::date, 
                u1.name AS client_name , 
                u2.name AS agent_name
                    FROM trades 
                    LEFT JOIN users u1
                    ON u1.user_no = client_no
                    AND u1.user_grade = 1
                    LEFT JOIN users u2
                    ON u2.user_no = agent_no
                    AND u2.user_grade = 2
                    WHERE supervison_no =:sp_no
                    OFFSET 0 
                    LIMIT 10
                    )tmp1
                ))
                AS trade_info
        FROM
            supervison
        WHERE
            sp_no = :sp_no";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }

        return $this->res;
    }
    private function manager_detail($request)
    {
        $p = $request->all();
        if ($request->filled('sp_no') && ((int)$request->input('sp_no')) >0) {
            $params = array('sp_no'=>$p['sp_no']);

            $sql ="SELECT
            sp_no,
            sp_name ,
            sp_contact ,
            reg_dt::date ,
            state ,
            array_to_json(array(SELECT row_to_json(tmp1) 
                FROM (SELECT trd_no, 
                trd_name, 
                construct_dt::date, 
                construct_end_dt::date, 
                u1.name AS client_name , 
                u2.name AS agent_name
                    FROM trades 
                    LEFT JOIN users u1
                    ON u1.user_no = client_no
                    AND u1.user_grade = 1
                    LEFT JOIN users u2
                    ON u2.user_no = agent_no
                    AND u2.user_grade = 2
                    WHERE staff_no =:sp_no 
                    OFFSET 0 
                    LIMIT 10
                    )tmp1
                ))
                AS trade_info 
        FROM
            manager
        WHERE
            sp_no = :sp_no";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }

        return $this->res;
    }
    private function trade_detail($request)
    {
        $p = $request->all();
        if ($request->filled('trd_no') && ((int)$request->input('trd_no')) >0) {
            $params = array('trd_no'=>$p['trd_no']);

            $sql ="SELECT
            trd_no,
            trd_name ,
            bidding_dt::date ,
            bidding_end_dt::date ,
            construct_dt::date,
            construct_end_dt::date,
            trades.state ,
            staff_no,
            supervison_no,
            view_cnt,
            bl.bl_name,
            trd_budget,
            trd_area,
            trd_draw,
            trd_file,
            u1.name AS client_name,
            u1.user_contact AS client_contact,
            u1.email AS client_email,
            u2.name AS agent_name,
            u2.user_contact AS agent_contact,
            u2.email AS agent_email,
            array_to_json(array(
                SELECT row_to_json(tmp1) 
                FROM (
                    SELECT sp_no, sp_name, sp_contact
                    FROM manager)tmp1)) AS staff_info ,
            array_to_json(array(
                SELECT row_to_json(tmp2) 
                FROM (
                    SELECT sp_no, sp_name, sp_contact
                    FROM manager)tmp2)) AS superv_info ,
            array_to_json(array(
                SELECT row_to_json(tmp3) 
                FROM (
                    SELECT client_no, agent_no, ucc_comment, ucc_imgs, reg_dt::date
                    FROM user_trd_comment WHERE trd_no = :trd_no ORDER BY reg_dt ASC)tmp3)) AS comment_info
        FROM
            trades 
        LEFT JOIN users u1
        ON u1.user_no = trades.client_no 
        LEFT JOIN users u2
        ON u2.user_no = trades.agent_no 
        LEFT JOIN business_list bl
        ON bl.bl_no = trades.bl_no
        WHERE
            trd_no = :trd_no;";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }

        return $this->res;
    }
    public function notice_detail($request)
    {
        $p = $request->all();
        if ($request->filled('notice_no') && ((int)$request->input('notice_no')) > 0) {
            $params = array('notice_no'=>$p['notice_no']);
            $sql =   "SELECT notice_no
            ,notice_title
            ,notice_content
            ,reg_dt
            ,view_cnt
            FROM  bbs_notice
            WHERE notice_no = :notice_no;";
            $this->res = $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE ';
        }
        return $this->res;
    }
    public function review_detail($request)
    {
        $p = $request->all();
        if ($request->filled('rv_no') && ((int)$request->input('rv_no')) >0) {
            $params = array('rv_no'=>$p['rv_no']);

            $sql ="SELECT rv_title
            ,rv_content
            ,rv_imgs
            ,rv_point
            ,u1.name AS client_name
            ,u2.name AS agent_name
            ,agent_no
            ,ctrt_no
            ,review.reg_dt::date
            FROM  review
            LEFT JOIN users u1
            ON u1.user_no = client_no
            LEFT JOIN users u2
            ON u2.user_no = agent_no
            WHERE rv_no = :rv_no
            AND review.state <> 1";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }
        return $this->res;
    }
    public function bbs_detail($request)
    {
        $p = $request->all();
        if ($request->filled('bbs_no') && ((int)$request->input('bbs_no')) >0) {
            $params = array('bbs_no'=>$p['bbs_no']);

            $sql ="SELECT bbs.title
            ,bbs.content
            ,bbs.trade_no
            ,u1.name AS user_name
            ,bbs.reg_dt::date
            ,bbs.ans_dt::date
            ,bbs.ans
            ,bbs.state
            FROM  bbs
            LEFT JOIN users u1
            ON u1.user_no = bbs.user_no
            WHERE bbs_no = :bbs_no";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }
        return $this->res;
    }
    public function msg_detail($request)
    {
        $p = $request->all();
        if ($request->filled('msg_no') && ((int)$request->input('msg_no')) >0) {
            $params = array('msg_no'=>$p['msg_no']);

            $sql ="SELECT message.msg_title
            ,message.msg_content
            ,message.msg_type
            ,u1.name AS user_name
            ,message.send_dt::date
            ,message.read_dt::date
            ,message.trd_no
            ,message.state
            FROM  message
            LEFT JOIN users u1
            ON u1.user_no = message.user_no
            WHERE msg_no = :msg_no";

            $this->res= $this->execute_query($sql, $params, 'select');
        } else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 1';
        }
        return $this->res;
    }
}
