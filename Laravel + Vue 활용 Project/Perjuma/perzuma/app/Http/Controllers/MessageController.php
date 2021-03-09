<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Input;

class MessageController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'Message invoked';
    }

    public function index()
    {
        return 'Message API';
    }

    // $res['query'] =  DB::SELECT(DB::RAW(("UPDATE users
    // SET user_aes ='qw2e'
    // WHERE user_no =1
    // RETURNING user_no;")));
    // update나 delete의경우 해당방법으로 returning 우회

    // 요청경로  GET - URL  : api/User/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();

        switch ($req) {
            //전체메세지 조회, 관리자만 가능
            case 'list':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }

                //안전성을위해 변수를 뽑아서 따로 만들어도 됨.
                //$params = array('offset'=> $p['offset'] );

                $sql = "SELECT msg_no
                ,msg_content
                ,msg_title
                ,msg_type
                ,user_no
                ,send_dt::date
                ,trd_no
                FROM  message
                ORDER BY msg_no DESC
                OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $p, 'select');

                //parent 써도됨
                //$this->res = parent::execute_query($sql, $p, 'select');

            break;
            //특정유저(업체)의 메시지 -  관리자와 본인만 가능
            case 'byuser':
                if ($request->filled('user_no') && $request->input('user_no') >0) {

                    //오프셋 확인
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }

                    //관리자일 경우  user_no를 JWT에서 가져오는게 아닌, 요청한 번호로 쿼리진행.
                    // if ( Passport !== 'admin'){
                    //    $p['user_no'] =  JWT['user_no'];
                    // }

                    $sql ="SELECT msg_no
                    ,msg_content
                    ,msg_title
                    ,msg_type
                    ,user_no
                    ,send_dt
                    ,trd_no
                    FROM message
                    WHERE user_no = :user_no\n";

                    $params = array(
                    'offset'=>$p['offset'] ,
                    'user_no' =>$p['user_no']
                    );

                    if ($request->filled('trd_no')) {
                        $sql.= " AND trd_no = :trd_no\n";
                        $params['trd_no'] = $p['trd_no'];
                    }

                    $sql.= "ORDER BY msg_no DESC\nOFFSET :offset LIMIT 10;";

                    $this->res= $this->execute_query($sql, $params, 'select');
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;

         default:
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : 0';
         break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function store(Request $request)
    {
        $p = $request->all();

        //메시지 등록
        if ($request->filled('user_no') &&  (int)$p['user_no'] >0) {
            if ($request->filled('msg_title', 'msg_content', 'msg_type', 'user_no')
            && (strlen($p['msg_title']) >0 )
            && (strlen($p['msg_content']) >0 && strlen($p['msg_content']) <512)
            && ((int)$p['msg_type'] >=0   && (int)$p['msg_type']  <= 4)
            && ((int)$p['user_no']  >0)
            ) {


                $param['trd_no'] ='NULL';
                 // 특정 거래에대한 메세지
                 if ($request->filled('trd_no') &&  (int)$p['trd_no'] >0 ) {
                    $param['trd_no'] =  (int)$request->input('trd_no');
                }
                
                $sql = "INSERT INTO message (msg_title
                    , msg_content
                    , msg_type
                    , user_no
                    , trd_no)
                VALUES ( :msg_title
                , :msg_content
                , :msg_type
                , :user_no
                , ".$param['trd_no']." )
                RETURNING msg_no; ";

                $param = array('user_no' => (int)$p['user_no'],
                'msg_type' =>(int)$p['msg_type'],
                'msg_title' => $p['msg_title'],
                'msg_content' => $p['msg_content']
                );

              
                $this->res= $this->execute_query($sql, $param, 'select');
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : 0 ';
            }
        }
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '변수 없음 - CODE : TYPE ';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req)
    {
        $p = $request->all();
        switch ($req) {
            //유저가 읽은경우 state 수정
            case 'iread':

            if ($request->filled('msg_no', 'user_no')  && ( (int)$p['msg_no']>0  && (int)$p['user_no'] >0)) {
                $sql ="UPDATE message
                SET state = 1, read_dt = NOW() 
                WHERE msg_no = :msg_no 
                    AND user_no = :user_no
                    RETURNING msg_no ;";
                
                $this->res= $this->execute_query($sql, $p, 'select');
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : TYPE ';
            }

            break;
        }
        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
