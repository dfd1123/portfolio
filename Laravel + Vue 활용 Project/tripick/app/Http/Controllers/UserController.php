<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Utils\Email;
use Mail;

use App\Http\Utils\Nexmo;
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
        return 'User controller';
    }

    public function fdata(Request $request)
    {
        if ($this->checkFile('fdata', $request)) {
            $ext = $this->checkExtension('fdata', $request, array('jpg','jpeg','png'));
            $this->saveFile('fdata'
            , $request
            , config('filesystems.user_thumb')
            , $ext);

        }
    }
    public function index()
    {
        //$res = Nexmo::send_code(821072128994);
        //$res = Nexmo::send_code(821022842506);

        
        return 'API FOR USERS';
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
            
            //권한체크 - 최고관리자만 이하코드 실행가능
            //유저목록 조회
            case 'userlist':

            //반드시 $param이라는 array 생성후 쿼리에서 사용되는 변수만 넣어줘야함
            //그렇지 않으면 사용하지 않는 column을 서버에 전송할경우 오류페이지가 반환됨.
            
                $params = array();

                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else { //start가 없거나 0보다 작은경우
                    $params['offset'] =0;
                }
                if ($request->filled('state') && $request->input('state') >= 0) {
                    $params['state'] = $p['state'];
                } else { //state 없거나 0보다 작은경우
                    $params['state'] =1;
                }

                //WHERE절 시작
                $whereClause = " ";
                if ($request->filled('user_name') && strlen($request->input('user_name')) >= 2) {
                    $params['user_name'] = '%'.$p['user_name'].'%';
                    $whereClause.= " \n AND name LIKE :user_name ";
                }
                if ($request->filled('user_email') && strlen($request->input('user_email')) >= 2) {
                    $params['user_email'] = '%'.$p['user_email'].'%';
                    $whereClause.= " \n AND email LIKE :user_email ";
                }
                //WHERE절 끝
                $sql = "SELECT id
,email
,name
,created_at at time zone 'KST' AS created_at
,updated_at at time zone 'KST' AS updated_at
,state
FROM  users
WHERE state = :state ";

                $sql.= $whereClause;

                $sql.="
ORDER BY id DESC
OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $params);

            break;
            //권한체크 - 최고관리자만 이하코드 실행가능
            //찜한 플래너 목록 검색
			case 'favotie':
				if ($request->filled('pln_name') && strlen($request->input('pln_name')) >= 2) {
                    $params['pln_name'] = '%'.$p['pln_name'].'%';
					$sql = "SELECT
						p.pln_name,
						p.pln_type,
						p.pln_desc
					FROM
						favorite f
					JOIN planner p ON
						f.pln_id = p.pln_id
					WHERE
						user_id = :id
						AND p.pln_name LIKE :pln_name";
					$params['id']  = $this->decode_res['uid'];   //차후 JWT로 변경
					$this->execute_query($sql, $params);
                }else{
                	$this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수 없음 - CODE : 1';
                }
				
			break;
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        $p = $request->all();
        //유저가입
        if ($request->filled('req') && $p['req'] ==='user') {
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
(email
,password
,name
,user_contact
,push_agree
,created_at
,reg_type
)
VALUES (:user_email
,:user_pwd
,:user_name
,:user_contact
,:push_agree
,NOW()
,:reg_type
)RETURNING id, reg_type ;';

                        $param = array('user_name' => $p['user_name'],
                        'user_email' => $p['user_email'],
                        'user_pwd' => Hash::make($p['user_pwd']),
                        'user_contact' => $p['user_contact'],
                        'push_agree' => $p['push_agree'],
                        'reg_type' => $p['reg_type']
                        );

                        $this->execute_query($sql, $param);

                        //정상적으로 실행된 경우
                        if (count($this->res['query']) >0 &&  $this->res['query'][0]->id > 0) {
                        	$reg_type = $this->res['query'][0]->reg_type;
                            $sql ="UPDATE users SET updated_at = NOW() ";
                            if ($this->checkFile('thumb', $request)) {
                                if ($ext = $this->checkExtension('thumb', $request, array('jpeg','png','jpg'))) {
                                    $thumbPath = $this->saveFileNameUnder(
                                        'thumb',                           //FORM 이름
                                        $request,                          //request 변수
                                        config('filesystems.user_thumb')   //파일저장경로
                                    .'thumb_'.$this->res['query'][0]->id                     //파일명
                                    .'.'.$ext                              //확인된 확장자
                                    );
                                }
                
                                $sql .=" , user_thumb =  :user_thumb";
                                $params['user_thumb'] = 'thumb_'.$this->res['query'][0]->id.'.'.$ext;
                            }else if($request->filled('user_thumb_another')){
                                $sql .=" , user_thumb =  :user_thumb";
                                $params['user_thumb'] = $p['user_thumb_another'];
                            }
                
                            $params['id']  = $this->res['query'][0]->id;   //차후 JWT로 변경
                            $sql .=" WHERE id = :id ;";
                
                            //정상실행일경우 state 1 query 1
                            $this->execute_query($sql, $params, 'update');
                            if($reg_type == 'app'){
	                            $user_email = $p['user_email'];
	                            $verify_code = $this->generateRandomString(128);
	                            $res = DB::insert("
	                                WITH upsert AS
	                                (
	                                    UPDATE
	                                        email_verified
	                                    SET
	                                        token = :verify_code,
	                                        created_at = now()
	                                    WHERE 1 = 1
	                                        AND email = :user_email
	                                    RETURNING email
	                                )
	                                INSERT INTO email_verified (
	                                    email,
	                                    token,
	                                    created_at
	                                )
	                                SELECT
	                                    :user_email,
	                                    :verify_code,
	                                    now()
	                                WHERE NOT EXISTS (
	                                    SELECT
	                                        email
	                                    FROM upsert
	                                )
	                                ", [
	                                    'user_email' => $user_email,
	                                    'verify_code' => $verify_code
	                                ]);
	                            $url = env('APP_URL')."/emailverify";
	                            $data_content = array(
	                                    'title' => '[TRIPICK] 트리픽 이메일 인증 메일입니다.',
	                                    'content' => [
	                                        'message_title' => '이메일 인증',
	                                        'email' => $user_email,
	                                        'name' => $p['user_name'],
	                                        'message' => '트리픽 이메일 인증 안내 메일입니다.<br>
	                                        이메일 인증이 완료되어야 트리픽 인증이 가능합니다.<br>
	                                        아래버튼을 클릭하셔서 이메일 인증을 완료해주세요.<br>',
	                                        'verify_link' => $url.'?verify=' . base64_encode($verify_code)
	                                    ]
	                                );
	                            Mail::to($user_email)->send(new Email($data_content));
                            }

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
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '분기 없음 - CODE : TYPE 320';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    //계정존재여부확인
    private function checkId($id)
    {
        $sql = "SELECT email
        FROM users
        WHERE email LIKE  :user_email; ";

        $params = array('user_email'=> $id );
        
        $this->res= $this->execute_query($sql, $params);
    }
    
    public function email_verified(Request $request){
        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = '로그인이 만료되었습니다. 재로그인 해주세요.';
            die(json_encode($this->res));
        }
        //유저가입

        $user_id = $this->decode_res['uid'];
        $user = DB::table('users')->where('id', $user_id)->first();
        $user_email = $user->email;
        $verify_code = $this->generateRandomString(128);
        $sql = 'WITH upsert AS
        (
            UPDATE
                email_verified
            SET
                token = :verify_code,
                created_at = now()
            WHERE 1 = 1
                AND email = :user_email
            RETURNING email
        )
        INSERT INTO email_verified (
            email,
            token,
            created_at
        )
        SELECT
            :user_email,
            :verify_code,
            now()
        WHERE NOT EXISTS (
            SELECT
                email
            FROM upsert
        );';
        
        $param = array(
            'user_email' => $user_email,
            'verify_code' => $verify_code
        );
        $this->execute_query($sql, $param);
        $url = env('APP_URL')."/emailverify";
        $data_content = array(
            'title' => '[TRIPICK] 트리픽 이메일 인증 메일입니다.',
            'content' => [
                'message_title' => '이메일 인증',
                'email' => $user_email,
                'name' => $user->name,
                'message' => '트리픽 이메일 인증 안내 메일입니다.<br>
                이메일 인증이 완료되어야 트리픽 인증이 가능합니다.<br>
                아래버튼을 클릭하셔서 이메일 인증을 완료해주세요.<br>',
                'verify_link' => $url.'?verify=' . base64_encode($verify_code)
            ]
        );
        Mail::to($user_email)->send(new Email($data_content));
        
        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //랜덤 스트링 출력
    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function update(Request $request, $req)
    {
        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }
        $p = $request->all();
        //var_dump($request->all());

        switch ($req) {
            //개인정보수정
            case 'usrinfo':
            
            $sql ="UPDATE users SET updated_at = NOW() ";

            $params = array();
            if ($request->filled('ori_password')  && (strlen($p['ori_password'])>2  && strlen($p['ori_password'])  < 16)) {
                $sql_check = "SELECT id, password FROM users WHERE id = :user_id";
                $check_password = DB::select($sql_check,array('user_id' => $this->decode_res['uid']));
                
                if (isset($check_password[0]->password) && Hash::check($p['ori_password'], $check_password[0]->password)) {
                    $sql .=" , password = :password ";
                    $params['password'] = Hash::make($p['new_password']);
                }else{
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '기존 비밀번호가 틀렸습니다.';
                    break;
                }
            }

            //썸네일 있을경우 변경저장
            //파일업로드의경우 RFC1837에 의해 POST에서 진행되어야하는데.. 차후수정,  form field에 _method : PUT 으로 주면 작동합니다.
            if ($this->checkFile('thumb', $request)) {
                if ($ext = $this->checkExtension('thumb', $request, array('jpeg','png','jpg'))) {
                    $thumbPath = $this->saveFileNameUnder(
                        'thumb',                           //FORM 이름
                        $request,                          //request 변수
                        config('filesystems.user_thumb')   //파일저장경로
                    .'thumb_'.$this->decode_res['uid']                     //파일명
                    .'.'.$ext                              //확인된 확장자
                    );
                }else{
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '이미지 확장자는 jpeg, png, jpg 만 가능합니다.';
                    break;
                }

                $sql .=" , user_thumb =  :user_thumb";
                $params['user_thumb'] = 'thumb_'.$this->decode_res['uid'].'.'.$ext;
            }

            $params['id']  = $this->decode_res['uid'];   //차후 JWT로 변경
            $sql .=" WHERE id = :id ;";

            //정상실행일경우 state 1 query 1
            $this->execute_query($sql, $params, 'update');

            break;

            //본인만 접근가능
            case 'usrstate':
                if (!$request->filled('user_withdraw_reason')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - state CODE : 1';
                    break;
                }
                $sql = "UPDATE users 
                        SET state = 0, user_withdraw_reason = :user_withdraw_reason
                        WHERE id = :id;";
                
                $param = array('id'=>$this->decode_res['uid'], 'user_withdraw_reason'=>json_encode($p['user_withdraw_reason']));
                $this->execute_query($sql, $param, 'update');
                
            break;

			case 'msg_verify':
				if ($request->filled('user_contact') && (strlen($p['user_contact'])>9  && strlen($p['user_contact']) < 16)){
					$global_number = "82".substr($p['user_contact'], 1);
					$verify_code = Nexmo::send_code($global_number);
					$sql = 'UPDATE
						users
					SET
						user_contact = :user_contact,
						user_contact_verify_number = :verify_code
					WHERE
						id = :id';
					$param = array('id'=>$this->decode_res['uid'], 'user_contact'=>$p['user_contact'], 'verify_code'=>$verify_code['msg']);
					$this->execute_query($sql, $param, 'update');
				}else{
					$this->res['query'] = null;
	                $this->res['state'] = config('res_code.PARAM_ERR');
	                $this->res['msg'] = '변수 없음 - CODE : 1';
				}
			break;
			
			case 'msg_verify_check':
				$sql = "UPDATE
					users
				SET
					user_contact_verify_number = '',
					state = 1
				WHERE
					id = :id
					AND user_contact_verify_number LIKE :verify_code
					AND state = 2";
				$param = array('id'=>$this->decode_res['uid'],'verify_code'=>$p['verify_code']);
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
