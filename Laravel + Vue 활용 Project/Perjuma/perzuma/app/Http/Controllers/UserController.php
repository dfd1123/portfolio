<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __invoke($id)
    {
        return 'abcd';
    }

    public function index()
    {
        return 'User API
        LIST : ~ 
        wjrjf : ~';
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

        //권한체크 - 최고관리자만 이하코드 실행가능

        switch ($req) {
            case 'userlist':
                if (auth()->guard('admin')->check()) {
                    info('true');
                //return response(null, 200)->header('Content-Type', 'application/json');
                } else {
                    info('false');
                    //return response(null, 401)->header('Content-Type', 'application/json');
                    /* $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1'; */
                }
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }

                //안전성을위해 변수를 뽑아서 따로 만들어도 됨.
                //$params = array('offset'=> $p['offset'] );

                $sql =   "SELECT user_no
                ,name
                ,email
                ,user_aes
                ,user_contact
                ,state
                ,created_at
                ,updated_at
                ,user_thumb
                ,extra_info
                FROM  users
                WHERE user_grade = 1
                ORDER BY user_no DESC
                OFFSET :offset LIMIT 10;";

                //DB::table('users')->DB::raw('awdawdawdawdawdawdawdawdawd')->get();

                $this->res = $this->execute_query($sql, $p, 'select');

                //parent 써도됨
                //$this->res = parent::execute_query($sql, $p, 'select');

            break;
            case 'agentlist':
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                } else {
                    //start가 없거나 0보다 작은경우
                    $p['offset'] =0;
                }

                $sql =   "SELECT
	TU.user_contact
    , TU.user_no
    , TU.name
	, TU.email
	, TAI.agent_addr
	, TAI.state
	, TAI.agent_name
	, TAI.agent_contact
	, TAI.agent_rating
	, TAI.agent_tel_number
	, TAI.create_dt
FROM
	users TU
LEFT JOIN agent_info TAI ON
    TU.user_no = TAI.agent_no
WHERE user_grade = 2
ORDER BY user_no DESC
OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $p, 'select');

            break;
            //이름으로 조회
            case 'byusername':
                //이름2자이상
                if ($request->filled('name') && strlen($request->input('name')) >2) {

                    //오프셋 확인
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params = array('name'=>
                    //와일드카드 사용
                    '%'.$p['name'].'%',
                     'offset'=>$p['offset'] );

                    $sql ="SELECT user_no
                    ,name
                    ,email
                    ,user_contact
                    ,created_at
                    FROM  users
                    WHERE name LIKE :name
                    AND user_grade = 1
                    ORDER BY user_no DESC
                    OFFSET :offset LIMIT 10;";

                    $this->res= $this->execute_query($sql, $params, 'select');
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;

            case 'byagentname':
                if ($request->filled('name') && strlen($request->input('name')) >2) {

                    //오프셋 확인
                    if ($request->filled('offset') && $request->input('offset') >= 0) {
                    } else {
                        $p['offset'] = 0;
                    }
                    $params = array('name'=>
                    //와일드카드 사용
                    '%'.$p['name'].'%',
                    'offset'=>$p['offset'] );

                    $sql ="SELECT user_no
                    ,user_aes
                    ,name
                    ,user_contact
                    ,email
                    ,state
                    ,reg_dt
                    ,user_grade
                    ,last_vt_dt
                    ,user_thumb
                    ,extra_info
                    FROM  users
                    WHERE name LIKE :name
                    AND user_grade = 2
                    ORDER BY user_no DESC
                    OFFSET :offset LIMIT 10;";

                    $this->res= $this->execute_query($sql, $params, 'select');
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
            break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //유저가입과 업체가입으로 나뉨
    //업체로 가입한 경우 유저로그인이 가능하지만,
    //유저로가입 한 경우 업체로그인은 동일아이디로 사용원할경우 인증받아야함
    public function store(Request $request)
    {
        $p = $request->all();
        //유저가입
        if ($request->filled('type') && $p['type'] ==='user') {
            if ($request->filled('user_email') && strlen($p['user_email']) >8) {
                $this->checkId($p['user_email']);

                //아이디 존재
                if (count($this->res['query']) >= 1) {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.OK');
                    $this->res['msg'] = '중복된 아이디';
                } else {
                    //가입가능
                    //입력된 정보가 적합한지 확인 후 가입진행

                    if ($request->filled('user_name', 'user_contact', 'user_email', 'user_pwd')
                    && (strlen($p['user_name'])>2  && strlen($p['user_name']) < 16)
                    && (strlen($p['user_contact'])>9  && strlen($p['user_contact']) < 16)
                    && (strlen($p['user_email'])>8  && strlen($p['user_email']) < 64)
                    && (strlen($p['user_pwd'])>6  && strlen($p['user_pwd']) < 16)
                   ) {
                        $sql = 'INSERT INTO users
                    (user_name
                    ,user_contact
                    ,user_email
                    ,state
                    ,user_grade
                    ,user_pwd )
                    VALUES (:user_name
                    , :user_contact
                    , :user_email
                    , 0
                    , 1
                    , :user_pwd )
                    RETURNING user_no ;';

                        $param = array('user_name' => $p['user_name'],
                        'user_contact' => $p['user_contact'],
                        'user_email' => $p['user_email'],
                        'user_pwd' => $p['user_pwd']
                        );

                        $this->execute_query($sql, $param, 'select');

                        //정상적으로 실행된 경우
                        if (count($this->res['query']) >0 &&  $this->res['query'][0]->user_no > 0) {
                            //메일발송
                            /*
                            $to_name = 'TO_NAME';
                            $to_email = 'ghkrwkd3@naver.com';
                            $data = array('name'=>"Sam Jose", "body" => "Test mail");
                    
                            Mail::send('emails.mail', $data, function ($message) use ($to_name, $to_email) {
                                $message->to($to_email, $to_name)   ->subject('Artisans Web Testing Mail');
                                $message->from('FROM_EMAIL_ADDRESS', 'perzuma777@gmail.com');
                            });
                            */
                        } else {
                            $this->res['query'] = null;
                            $this->res['state'] = config('res_code.NO_DATA');
                            $this->res['msg'] = '쿼리응답에러';
                        }
                    } else {
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '변수 없음 - CODE : 1';
                    }
                }
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 - CODE : 0 ';
            }
        }
        //업체가입
        elseif ($request->filled('type') && $p['type'] ==='agent') {
            //유저가입 보고 처리한뒤 이 주석 지우고.
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
    
    //계정존재여부확인
    private function checkId($id)
    {
        $sql = "SELECT user_email
        FROM users
        WHERE user_email LIKE  :user_email; ";

        $params = array('user_email'=> $id );
        
        $this->res= $this->execute_query($sql, $params, 'select');
    }
    
    public function update(Request $request, $req)
    {
        $p = $request->all();

        //var_dump($request->all());

        switch ($req) {
            //관리자만 수정가능
            // 1.토큰 혹은 키로 관리자검증
            // 2.쿼리날려서 state수정.


            //개인정보수정
            case 'normalinfo':
           
                      
            // 1.토큰 혹은 키로 유저 검증
            // 2.자신의 정보만 수정가능하게.

            if (!$request->filled('user_name', 'user_contact', 'user_email', 'user_pwd')) {
                $this->res['query'] =null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수없음 - CODE : 1';
                break;
            }
            
            $sql ="UPDATE users SET last_vt_dt = NOW() ";

            $params = array();
            if ($request->filled('user_name')    && (strlen($p['user_name'])>2  && strlen($p['user_name'])  < 16)) {
                $sql .=" , user_name = :user_name ";
                $params['user_name'] = $p['user_name'];
            }
            if ($request->filled('user_contact') && (strlen($p['user_contact'])>9  && strlen($p['user_contact']) < 16)) {
                $sql .=" , user_contact = :user_contact ";
                $params['user_contact'] = $p['user_contact'];
            }
            if ($request->filled('user_email')   &&  (strlen($p['user_email'])>8  && strlen($p['user_email']) < 64)) {
                $sql .=" , user_email = :user_email ";
                $params['user_email'] = $p['user_email'];
            }
            if ($request->filled('user_pwd')  &&  (strlen($p['user_pwd'])>6  && strlen($p['user_pwd']) < 16)) {
                $sql .=" , user_pwd = :user_pwd ";
                $params['user_pwd'] = $p['user_pwd'];
            }

            //썸네일 있을경우 변경저장
            if ($request->hasFile('thumb') && $request->file('thumb')->isValid()) {
                $extension = $request->thumb->extension();
                $path = $request->thumb->storeAs(
                    config('filesystems.user_thumb'),
                    'profile_'.$p['user_no'].'.'.$extension
                );
                $sql .=" , user_thumb =  :user_thumb";
                $params['user_thumb'] = $path;
            }

            $params['user_no']  = $p['user_no'];   //차후 JWT로 변경
            $sql .=" WHERE user_no = :user_no ;";

            //정상실행일경우 state 1 query 1
            $this->execute_query($sql, $params, 'update');

            break;

            case 'state':
                if (!$request->filled('agent_no') && !$request->filled('state')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - CODE : 1';
                    break;
                }
                $sql = "UPDATE users 
                SET state = :state
                WHERE user_no = :agent_no;";
                $param = array('agent_no'=>$p['agent_no'] , 'state'=>$p['state']);
                $this->execute_query($sql, $param, 'update');
            break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
}
