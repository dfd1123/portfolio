<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\Input;

class AccompanyController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function __invoke($id)
    {
        return 'Accompany controller';
    }

    public function index()
    {
        return 'API FOR Accompany';
    }

    // 요청경로  GET - URL  : api/estimate_bid/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            
            case 'list':
					$where = '';
                    $p['offset'] =0;
                    if ($request->filled('offset') && $request->input('offset') >0) {
                        $p['offset'] = $request->input('offset');
                    }
					$params = array('offset'=>$p['offset']);
					
                    if ($request->filled('acmp_content')) {
                        $p['acmp_content'] = $request->input('acmp_content');
						$params['acmp_content'] = '%'.$p['acmp_content'].'%';
						$where = 'WHERE TA.acmp_content LIKE :acmp_rep_content';
                    }

                    $sql ="WITH accompany_replys AS (
	SELECT
		acmp_id,
		COUNT(acmp_id) AS reply_qtt
	FROM
		accompany_reply
	GROUP BY
		acmp_id
) SELECT
	TA.acmp_id ,
	TA.acmp_title ,
	TA.acmp_content ,
	TO_CHAR (
		TA.created_at ,
		'YYYY.MM.DD HH24:MI'
	) AS created_at ,
	TA.user_id ,
	TA.view_cnt ,
	TU.user_thumb ,
	TU.name ,
	COALESCE(NULLIF((TARS.reply_qtt)::TEXT, '')::NUMERIC, 0) AS reply_qtt
FROM
	accompany TA
JOIN users TU ON
	TA.user_id = TU.id
JOIN accompany_replys TARS ON
	TA.acmp_id = TARS.acmp_id ".$where."
ORDER BY
	acmp_id DESC OFFSET :offset
LIMIT 20;";

                    $this->res = $this->execute_query($sql, $params);

            break;
            case 'detail':
                $params = array();

           
                if ($request->filled('acmp_id') && $request->input('acmp_id') >= 0) {
                    $params['acmp_id'] = $p['acmp_id'];

                    //조회수 1올려주며 내용 가져오기
                    $sql= "WITH VIEW_UPDATER AS(
	UPDATE
		accompany
	SET
		view_cnt = view_cnt + 1
	WHERE
		acmp_id = :acmp_id RETURNING acmp_id
) SELECT
	TA.acmp_id ,
	TA.acmp_title ,
	TA.acmp_content ,
	TO_CHAR (
		TA.created_at ,
		'YYYY.MM.DD HH24:MI'
	) AS created_at ,
	TA.user_id ,
	TA.view_cnt ,
	TU.user_thumb ,
	TU.name ,
	(
		SELECT
			COALESCE(NULLIF((COUNT(acmp_id))::TEXT, '')::NUMERIC, 0) AS reply_qtt
		FROM
			accompany_reply
		GROUP BY
			acmp_id
		HAVING
			acmp_id = :acmp_id
	)
FROM
	accompany TA
JOIN users TU ON
	TA.user_id = TU.id
WHERE
	acmp_id = (
		SELECT
			acmp_id
		FROM
			VIEW_UPDATER
	);";
                    
                    $this->res = $this->execute_query($sql, $params);
                } else {
                    $this->res['query']=null;
                    $this->res['state']= config('res_code.PARAM_ERR');  //2
                    $this->res['msg']='CODE 106: 변수없음 ';
                }


            break;
            
            //댓글목록
            case 'replist':
            if ($request->filled('acmp_id') && $request->input('acmp_id') >= 0) {
                if ($request->filled('offset') && $request->input('offset') >0) {
                    $p['offset'] = $request->input('offset');
                }

                $params = array('offset'=>$p['offset']
                ,'acmp_id' =>$p['acmp_id']);


                $sql ="WITH rereply AS (
	SELECT
		acmp_rep_parent,
		COUNT(acmp_rep_parent)
	FROM
		accompany_reply
	GROUP BY
		acmp_rep_parent
) SELECT
	TAC.acmp_rep_content ,
	TO_CHAR (
		TAC.acmp_rep_created_at ,
		'YYYY.MM.DD HH24:MI'
	) AS created_at ,
	TAC.acmp_rep_id ,
	TAC.acmp_rep_parent ,
	TAC.acmp_rep_user ,
	TAC.acmp_id ,
	TU.name ,
	TU.user_thumb,
	COALESCE(NULLIF((TRR.count)::TEXT, '')::NUMERIC, 0) AS rereply_qtt
FROM
	accompany_reply TAC
JOIN users TU ON
	TAC.acmp_rep_user = TU.id
LEFT JOIN rereply TRR ON
	TAC.acmp_rep_id = TRR.acmp_rep_parent
WHERE
	TAC.acmp_id = :acmp_id
ORDER BY
	TAC.acmp_id DESC OFFSET :offset
LIMIT 10;";

                $this->res = $this->execute_query($sql, $params);
            } else {
                $this->res['query']=null;
                $this->res['state']= config('res_code.PARAM_ERR');  //2
                $this->res['msg']='CODE 106: 변수없음 ';
            }

            break;  //대댓글목록
            case 'rereplist':

            if ($request->filled('acmp_rep_id') && $request->input('acmp_rep_id') >= 0) {
            	$p['offset'] = 0;
                if ($request->filled('offset') && $request->input('offset') >0) {
                    $p['offset'] = $request->input('offset');
                }

                $params = array('offset'=>$p['offset']
                ,'acmp_rep_id' =>$p['acmp_rep_id']);


                $sql ="SELECT TAC.acmp_rep_content
                , TO_CHAR (TAC.acmp_rep_created_at , 'YYYY.MM.DD HH24:MI') AS created_at 
                , TAC.acmp_rep_id
                , TAC.acmp_rep_parent
                , TAC.acmp_rep_user
                , TAC.acmp_id
                , TU.name
                , TU.user_thumb
                FROM accompany_reply TAC JOIN users TU 
                ON TAC.acmp_rep_user =  TU.id
                WHERE TAC.acmp_rep_parent  = :acmp_rep_id
                ORDER BY TAC.acmp_id DESC 
                OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $params);
            } else {
                $this->res['query']=null;
                $this->res['state']= config('res_code.PARAM_ERR');  //2
                $this->res['msg']='CODE 106: 변수없음 ';
            }

            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    // 요청경로  POST - URL  : api/estimate_bid
    public function store(Request $request)
    {
        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }

        $p = $request->all();
       
        if ($request->filled('req')) {
            switch ($p['req']) {
                //게시글작성
                case  'acmp':
                    if ($request->filled('acmp_title', 'acmp_content')) {
                        $sql ="INSERT INTO accompany(acmp_title, acmp_content, user_id)
                        VALUES(:acmp_title, :acmp_content, :user_id)
                        RETURNING acmp_id;";

                        $params = array(
                            'acmp_title'=> $p['acmp_title'],
                            'acmp_content'=> $p['acmp_content'],
                            'user_id'=> (int)$this->decode_res['uid']
                        );
                        $this->res = $this->execute_query($sql, $params);
                    }

                break;
                //댓글작성 OR 대댓글 작성
                case  'acmp_rep':
                    if ($request->filled('acmp_rep_content', 'acmp_id')) {
                        $sql ="INSERT INTO accompany_reply
                        (acmp_rep_user, acmp_rep_content, acmp_rep_parent, acmp_id)
                        VALUES(:acmp_rep_user, :acmp_rep_content, NULLIF(:acmp_rep_parent,0) ,:acmp_id )
                        RETURNING acmp_rep_id;";

                        //acmp_rep_parent이 있으면 대댓글, 없으면 댓글
                        $params = array(
                            'acmp_id'=> (int)$p['acmp_id'],
                            'acmp_rep_parent'=> isset($p['acmp_rep_parent']) ? $p['acmp_rep_parent'] : 0,
                            'acmp_rep_content'=> $p['acmp_rep_content'],
                            'acmp_rep_user'=> (int)$this->decode_res['uid']
                        );
                        $this->res = $this->execute_query($sql, $params);
                    }

                break;
              
                //변수없음 예외처리
                default:
                break;
            }
        }
                    
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req ='state')
    {
        //권한체크
        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }

        switch ($req) {
            case 'acstate':
                $sql ="UPDATE accompany
                SET  state = :state
                WHERE acmp_id = :acmp_id AND user_id = :user_id
                RETURNING acmp_id, state;";

                $params = array('acmp_id'=>$p['acmp_id']   //댓글ID
                ,'state'=>$p['state']   //댓글 state
                ,'user_id'=>(int)$this->decode_res['uid']  //소유자
                );

                $this->res = $this->execute_query($sql, $params);
            break;
            case 'acrpstate':
                $sql ="UPDATE accompany_reply
                SET  state = :state
                WHERE acmp_rep_id = :acmp_rep_id AND acmp_rep_user = :acmp_rep_user
                RETURNING acmp_rep_id, state;";

                $params = array('acmp_rep_id'=>$p['acmp_rep_id']   //댓글ID
                ,'state'=>$p['state']   //댓글 state
                ,'user_id'=>(int)$this->decode_res['uid']  //소유자
                );

                $this->res = $this->execute_query($sql, $params);
            break;
            case 'content':

                $sql ="UPDATE accompany
                SET acmp_title = :acmp_title
                    ,acmp_content = :acmp_content
                WHERE acmp_id = :acmp_id AND user_id = :user_id
                RETURNING acmp_id;";

                $params = array('acmp_title'=>$p['acmp_title']
                ,'acmp_content'=>$p['acmp_content']
                ,'acmp_id' =>$p['acmp_id']
                ,'user_id'=>(int)$this->decode_res['uid'] );
                $this->res = $this->execute_query($sql, $params);
            break;
            
            case 'acrpcontent':
                $sql ="UPDATE accompany_reply
                SET acmp_rep_content = :acmp_rep_content
                WHERE acmp_rep_id = :acmp_rep_id AND acmp_rep_user = :acmp_rep_user
                RETURNING acmp_rep_id;";

                $params = array('acmp_rep_content'=>$p['acmp_rep_content']
                ,'acmp_rep_id' =>$p['acmp_rep_id']
                ,'acmp_rep_user'=>(int)$this->decode_res['uid'] );
                $this->res = $this->execute_query($sql, $params);
            break;
        }
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
