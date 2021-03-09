<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\DB;
use Controller\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Utils\JWT;
use Facades\App\Classes\LoginInfo;
use Illuminate\Support\Facades\Hash;
use App\Http\Utils\Email;

Route::post('/payletter/OrderCallback','PayletterController@OrderCallback');


//landing
Route::get('/', function(){
    return view('landing/main');
});

//랜딩
Route::get('/splash', function () {
    $page_css = "intro";

    $view = view('splash');
    $view->page_css = $page_css;

    return $view;
});

//인트로
Route::get('/intro', function () {
    $page_css = "intro";
	
	$user_id = LoginInfo::get();
    if($user_id == ''){

    }else{
        $sql = "SELECT 
                    id
                FROM 
                    users 
                WHERE 
                    id = :user_id AND state = 1";
        $check_login = DB::select($sql, array('user_id'=>$user_id));
        if(isset($check_login[0]->id)){
            return redirect('af_home');
        }
    }
	
    $view = view('intro');
    $view->page_css = $page_css;

    return $view;
});

//서비스이용약관
Route::get('/terms01', function () {
    $page_css = "mypage";
    
    $view = view('mypage/mypageterms01');
    $view->page_css = $page_css;

    return $view;
});

// 개인정보 처리방침
Route::get('/terms02', function () {
    $page_css = "mypage";
    
    $view = view('mypage/mypageterms02');
    $view->page_css = $page_css;

    return $view;
});

Route::prefix('register')->group(function () {

    //회원가입 약관동의
    Route::get('/agree', function () {
        $page_css = "sign-up";

        $view = view('register/agree');
        $view->page_css = $page_css;

        return $view;
    });

    // 회원가입
    Route::get('/step1', function (Request $request) {
        $page_css = "sign-up";
        
        $push_agree = $request->push_agree;

        $view = view('register/step1');
        $view->page_css = $page_css;
        $view->push_agree = $push_agree;

        return $view;
    });

    //네이버가입
    Route::get('naverlogin', function (Request $request) {
        $page_css = "sign-up";
        $push_agree = $request->push_agree;

        $view = view('register/naver_reg');
        $view->page_css = $page_css;
        $view->push_agree = $push_agree;


        return $view;
    });
     //카카오 코드 redirect
     Route::get('kakaologin', function (Request $request) {
        $page_css = "sign-up";
        $push_agree = $request->push_agree;
		
        $view = view('register/kakao_reg');
		$view->page_css = $page_css;
        $view->push_agree = $push_agree;

        //Access 토큰 발급
        $data = array(
            'grant_type' => 'authorization_code'
            ,'client_id' => 'afaf351438a4afb4a66614af56a36dba'
            ,'redirect_uri' => env('APP_URL').'/register/kakaologin'
            ,'code' => $request->input('code')
        );
        
        $url = "https://kauth.kakao.com/oauth/token?". http_build_query($data );
        
        $ch = curl_init();                                 
        curl_setopt($ch, CURLOPT_URL, $url);              
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);       
        curl_setopt($ch, CURLOPT_POST, true);           
        curl_setopt($ch, CURLOPT_HTTPHEADER,   
        array('Content-Type: application/x-www-form-urlencoded;charset=utf-8'));
         
        $response = curl_exec($ch);
        curl_close($ch);
        //정보확인
        $response = json_decode($response);
	
		if(!isset($response->access_token)){
			$check_login = "not-access";
            $view->check_login = $check_login;
			return $view;
		}

	
        //앱가입
        $api_url = 'https://kapi.kakao.com/v1/user/signup';
        $access_token = $response ->access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));

        $response = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);



        //유저정보요청
        $headers = array('Content-Type: application/x-www-form-urlencoded;charset=utf-8',
    'Authorization: Bearer '.$access_token );

        $url2 = "https://kapi.kakao.com/v2/user/me";
                
        $property_keys =  array('properties.nickname',
            'kakao_account.email'); 

        $ch2 = curl_init();                                 
        curl_setopt($ch2, CURLOPT_URL, $url2);              
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);    
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 10);      
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, true);  
        curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($property_keys));       
        curl_setopt($ch2, CURLOPT_POST, true);           
        curl_setopt($ch2, CURLOPT_HTTPHEADER,  $headers);
        $response2 = curl_exec($ch2);
        curl_close($ch2);

        $userinfo = json_decode($response2);



        //카카오 유저정보 없음
        if($userinfo ===null ){
			//로그아웃 시키기(이메일 동의안했거나 없는경우)
		    $url2 = "https://kapi.kakao.com/v1/user/unlink";
		    $headers = array('Authorization: Bearer '.$access_token );
		    $ch2 = curl_init();                                 
		    curl_setopt($ch2, CURLOPT_URL, $url2);              
		    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);    
		    curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 10);      
		    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, true);  
		    curl_setopt($ch2, CURLOPT_POST, true);           
		    curl_setopt($ch2, CURLOPT_HTTPHEADER,  $headers);
		    $response2 = curl_exec($ch2);
		    curl_close($ch2);
			
        	$check_login = "not-info";
            $view->check_login = $check_login;
			return $view;
        }

        if($userinfo->kakao_account->email_needs_agreement ===true){
        	//로그아웃 시키기(이메일 동의안했거나 없는경우)
	        $url2 = "https://kapi.kakao.com/v1/user/unlink";
	        $headers = array('Authorization: Bearer '.$access_token );
	        $ch2 = curl_init();                                 
	        curl_setopt($ch2, CURLOPT_URL, $url2);              
	        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);    
	        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 10);      
	        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, true);  
	        curl_setopt($ch2, CURLOPT_POST, true);           
	        curl_setopt($ch2, CURLOPT_HTTPHEADER,  $headers);
	        $response2 = curl_exec($ch2);
	        curl_close($ch2);
			
        	$check_login = "not-agree";
            $view->check_login = $check_login;
			return $view;
        }

      
        //이메일이 있는경우 가입되어있는지 확인
        $sql = "SELECT id, password FROM users WHERE email = :email;";
		$check_email = DB::select($sql,array('email'=>$userinfo->kakao_account->email));
			
		if(isset($check_email[0])){
			if (isset($check_email[0]->password) && Hash::check($userinfo->id, $check_email[0]->password)) {
				//카카오 회원가입자확인됨, 로그인 시키기
                $check_login = "success";
                
                setcookie('user_id',  rawurldecode($userinfo->kakao_account->email),time()+3600*24*90,'/');
                setcookie('user_pwd', $userinfo->id,time()+3600*24*90,'/');

			}else{
				//일반회원가입자임 로그인페이지로 돌려보내야됨
				$check_login = "not-kakao";
			}
		}else{
			//가입안되어잇다면 INSERT로 가입진행
			$check_login = "not-register";
		}
        $view->check_login = $check_login;
		$view->user_info = $userinfo;
        //KAKAO REST API를 쓰는 이유는, iOS, Android에서는 웹뷰에서 새창이뜨면 보안상 진행이안됨..
        return $view;
    });
 
    
});

// 로그인
Route::get('/login', function () {
    $page_css = "login";
    
    $view = view('login');
    $view->page_css = $page_css;

    return $view;
});

// 비밀번호 찾기
Route::get('/findpw', function () {
    $page_css = "findpw";
    
    $view = view('password');
    $view->page_css = $page_css;

    return $view;
});



//이메일 인증페이지
Route::get('/emailverify', function (Request $request) {
    $verify_code = base64_decode($request->verify);
    $verify = DB::table('email_verified')->where('token', $verify_code)->first();
    info($verify_code);
    if ($verify !== null) {
        if (date('Y-m-d H:i:s', strtotime('-1 days')) > date('Y-m-d H:i:s', strtotime($verify->created_at))) {
            return redirect('/emailconfirm')->with('jsClose','인증메일이 하루가 지난 메일입니다. 다시 시도해 주세요.');
        }
        DB::table('email_verified')->where('email', $verify->email)->where('token', $verify_code)->delete();
        DB::table('users')->where('email', $verify->email)->update(["email_verified_at" => DB::raw('now()')]);
        return redirect('/be_home')->with('jsClose','이메일 인증이 완료되었습니다. 앱에서 완료버튼을 눌러주세요.');
    }else{
        return redirect('/emailconfirm')->with('jsClose','페이지 새로고침을 하셨거나 인증되지 않은 경로로 접근하셨습니다. 다시 시도해 주세요.');
    }
});



// 비밀번호 찾기
Route::get('/settingpw', function (Request $request) {
    $verify_code = base64_decode($request->verify);
    $verify = DB::table('password_resets')->where('token', $verify_code)->first();
    info($verify_code);
    if ($verify !== null) {
        if (date('Y-m-d H:i:s', strtotime('-1 days')) > date('Y-m-d H:i:s', strtotime($verify->created_at))) {
            return redirect('/login')->with('jsAlert','인증메일이 하루가 지난 메일입니다. 다시 시도해 주세요.');
        }
        DB::table('password_resets')->where('email', $verify->email)->where('token', $verify_code)->delete();
        
    }else{
        return redirect('/login')->with('jsAlert','페이지 새로고침을 하셨거나 인증되지 않은 경로로 접근하셨습니다. 다시 시도해 주세요.');
    }
    $page_css = "findpw";
    
    $view = view('password_setting');
    $view->page_css = $page_css;
    $view->email = $verify->email;

    return $view;
});
// 동행객 찾기
Route::get('find', function () {
    $page_css = "find";
    
    $view = view('find/find');
    $view->page_css = $page_css;

    return $view;
});

// 홈 - 로그인 이전
Route::get('/be_home', function () {
    $user_id = LoginInfo::get();
    
    if($user_id == ''){

    }else{
        $sql_estm = "SELECT 
                    estm_id
                FROM 
                    estimate 
                WHERE 
                    user_id = :user_id AND state = 1
                ORDER BY updated_at DESC
                LIMIT 1";
        $check_estm = DB::select($sql_estm, array('user_id'=>$user_id));
        if(isset($check_estm[0]->estm_id)){
            return redirect('af_home');
        }
    }
    $sql = "SELECT 
                pln_id,
                pln_type,
                pln_thumb,
                pln_name,
                pln_desc
            FROM
                planner
            ORDER BY
                random()
            LIMIT
                10";
    $planners = DB::select($sql);

    $page_css = "home";

    $view = view('home/homebefore');
    $view->page_css = $page_css;
    $view->planners = $planners;
    $view->user_id = $user_id;

    return $view;
});

Route::group(['middleware' => 'jwt'], function () {
    //이메일 인증확인페이지
    Route::get('/emailconfirm', function () {
        $user_id = LoginInfo::get();
        
        $sql = 'SELECT
                    email_verified_at
                FROM
                    users
                WHERE
                    id = :user_id';
        $email_state = DB::select($sql, array('user_id'=>$user_id));
        if (isset($email_state[0]->email_verified_at)) {
            if ($email_state[0]->email_verified_at != NULL) {
                return redirect('/be_home');
            }
        }
        $page_css = "findpw";
        
        $view = view('email_setting');
        $view->page_css = $page_css;

        return $view;
    });

    Route::group(['middleware' => 'email'], function () {
    
        //홈 - 로그인직후
        Route::get('/af_home', function () {
            $user_id = LoginInfo::get();

            $sql_estm = "SELECT 
                            estm_id
                        FROM 
                            estimate 
                        WHERE 
                            user_id = :user_id AND state = 1
                        ORDER BY updated_at DESC
                        LIMIT 1";
            $check_estm = DB::select($sql_estm, array('user_id'=>$user_id));
            if(!isset($check_estm[0]->estm_id)){
                return redirect('be_home');
            }
            $sql_eb = "SELECT
                        pln.pln_id,
                        pln.pln_name,
                        pln.pln_thumb,
                        pln.pln_desc,
                        estm.estm_id,
                        estm.estm_area,
                        estm.estm_period,
                        eb.eb_id,
                        eb.eb_title,
                        eb.eb_desc,
                        eb.estm_asking_price
                    FROM
                        estimate_bidding eb
                    INNER JOIN
                        planner pln
                    ON
                        eb.pln_id = pln.pln_id
                    INNER JOIN
                        estimate estm
                    ON
                        eb.estm_id = estm.estm_id
                    WHERE
                        estm.state = 1 AND
                        estm.user_id = :user_id
                    ORDER BY eb.eb_id DESC
                    LIMIT 20";
            
            $ebs = DB::select($sql_eb, array('user_id'=>$user_id));

            $page_css = "home";
            
            $view = view('home/homeafter');
            $view->page_css = $page_css;
            $view->ebs = $ebs;
            $view->check_estm = $check_estm;
        
            return $view;
        });
        //홈 - 로그인직후
        Route::get('/contact_verify', function () {
            $page_css = "sign-up";
            
            $view = view('/contact_verify');
            $view->page_css = $page_css;
        
            return $view;
        });
        //홈 - 상품
        Route::get('/pr_home', function () {
            $sql_pr = "SELECT 
                        prd_id,
                        prd_thumb,
                        prd_title,
                        prd_subtitle,
                        (SELECT avg(revw_score) FROM review WHERE prd_id = product.prd_id AND state = 0) as prd_score,
                        (SELECT count(revw_id) FROM review WHERE prd_id = product.prd_id AND state = 0) as prd_count                                    
                    FROM  product
                    WHERE prd_is_recmd = 1
                    ORDER BY RANDOM();";
            $products = DB::select($sql_pr);

            $page_css = "home";
            
            $view = view('home/homeproduct');
            $view->page_css = $page_css;
            $view->products = $products;

            return $view;
        });

        Route::prefix('estimate')->group(function () {
            //견적 - 작성1
            Route::get('/step1', function () {
                $user_id = LoginInfo::get();

                $sql_estm = "SELECT 
                                estm_id, 
                                estm_area, 
                                estm_area_type, 
                                estm_period, 
                                estm_group_qtt 
                            FROM 
                                estimate 
                            WHERE 
                                user_id = :user_id AND state = 0";
                $estimate_check = DB::select($sql_estm, array('user_id'=>$user_id));
                
                $page_css = "recommend";
            
                $view = view('estimate/step1');
                $view->page_css = $page_css;
                $view->estm_step = $estimate_check;
        
                return $view;
            });

            //견적 - 작성2
            Route::get('/step2', function () {
                $user_id = LoginInfo::get();

                $page_css = "recommend";

                $sql_estm = "SELECT 
                                estm_id, 
                                estm_area, 
                                estm_area_type, 
                                estm_period, 
                                estm_group_qtt,
                                estm_budget_max,
                                estm_budget_min
                            FROM 
                                estimate 
                            WHERE 
                                user_id = :user_id AND state = 0";
                $estimate_check = DB::select($sql_estm, array('user_id'=>$user_id));
            
                $view = view('estimate/step2');
                $view->page_css = $page_css;
                $view->estm_step = $estimate_check;
        
                return $view;
            });

            //견적 - 작성3
            Route::get('/step3', function () {
                $user_id = LoginInfo::get();

                $page_css = "recommend";

                $sql_estm = "SELECT 
                                estm_id, 
                                estm_area, 
                                estm_area_type, 
                                estm_period, 
                                estm_group_qtt,
                                estm_budget_max,
                                estm_budget_min,
                                estm_theme
                            FROM 
                                estimate 
                            WHERE 
                                user_id = :user_id AND state = 0";
                $estimate_check = DB::select($sql_estm, array('user_id'=>$user_id));

                $sql_theme = "SELECT * FROM estimate_theme ORDER BY theme_id ASC";
                $themes = DB::select($sql_theme);

                $view = view('estimate/step3');
                $view->page_css = $page_css;
                $view->estm_step = $estimate_check;
                $view->themes = $themes;
        
                return $view;
            });

            //견적 - 작성4
            Route::get('/step4', function () {
                $user_id = LoginInfo::get();

                $page_css = "recommend";

                $sql_estm = "SELECT 
                                estm_id, 
                                estm_area, 
                                estm_area_type, 
                                estm_period, 
                                estm_group_qtt,
                                estm_budget_max,
                                estm_budget_min,
                                estm_theme,
                                estm_step4
                            FROM 
                                estimate 
                            WHERE 
                                user_id = :user_id AND state = 0";
                $estimate_check = DB::select($sql_estm, array('user_id'=>$user_id));
                
                $sql_stepparent = "SELECT sp_id, sp_title, state FROM step_parent ORDER BY sp_id";
                $step_parent = DB::select($sql_stepparent);
                
                
                $view = view('estimate/step4');
                $view->page_css = $page_css;
                $view->estm_step = $estimate_check;
                $view->stepparent = $step_parent;
        
                return $view;
            });

            //견적 - 작성5
            Route::get('/step5/{parent}/{sort}', function ($parent, $sort) {
                $user_id = LoginInfo::get();

                $page_css = "recommend";
                $sql_estm = "SELECT 
                                estm_id, 
                                estm_area, 
                                estm_area_type, 
                                estm_period, 
                                estm_group_qtt,
                                estm_budget_max,
                                estm_budget_min,
                                estm_theme,
                                estm_step4,
                                estm_step5
                            FROM 
                                estimate 
                            WHERE 
                                user_id = :user_id AND state = 0";
                $estimate_check = DB::select($sql_estm, array('user_id'=>$user_id));

                if(isset($estimate_check[0]->estm_step5)){
                    $step5_detail = json_decode($estimate_check[0]->estm_step5);
                }


                $sql_group = "SELECT step_sort, step_group FROM estimate_step WHERE step_parent = :step_parent GROUP BY step_sort, step_group ORDER BY step_sort ASC";
                $groups = DB::select($sql_group, array('step_parent' => $parent));

                $sql_step5 = "SELECT * FROM estimate_step WHERE step_parent = :step_parent AND step_sort = :step_sort ORDER BY step_id ASC";
                $step5_lists = DB::select($sql_step5, array('step_parent' => $parent, 'step_sort' => $sort ));

                $view = view('estimate/step5');
                $view->page_css = $page_css;
                $view->estm_step = $estimate_check;
                $view->groups = $groups;
                $view->step5_lists = $step5_lists;
                $view->parent = $parent;
                $view->sort = $sort;
                $view->step5_detail = $step5_detail;
        
                return $view;
            });

            Route::get('/step5_add', function () {
                $user_id = LoginInfo::get();

                $page_css = "recommend";
                $sql_estm = "SELECT 
                                estm_id, 
                                estm_area, 
                                estm_area_type, 
                                estm_period, 
                                estm_group_qtt,
                                estm_budget_max,
                                estm_budget_min,
                                estm_theme,
                                estm_step4,
                                estm_step5
                            FROM 
                                estimate 
                            WHERE 
                                user_id = :user_id AND state = 0";
                $estimate_check = DB::select($sql_estm, array('user_id'=>$user_id));
            
                $view = view('estimate/step5_add');
                $view->page_css = $page_css;
                $view->estm_step = $estimate_check;
            
                return $view;
            });

            Route::get('/step_finish/{estm_id}', function ($estm_id) {
                $user_id = LoginInfo::get();

                $sql_estm = "SELECT 
                                estm_id, 
                                updated_at at time zone 'KST' AS updated_at
                            FROM 
                                estimate 
                            WHERE 
                                estm_id = :estm_id AND user_id = :user_id AND state = 1";
                $estimate = DB::select($sql_estm, array('estm_id'=>$estm_id, 'user_id'=>$user_id));
                if(!isset($estimate[0]->updated_at)){
                    return redirect()->back()->with('jsAlert','해당 추천은 종료되었습니다.');
                }
                $duration = 86400 - (time() - strtotime($estimate[0]->updated_at)) ;

                $page_css = "recommend";
                
                $view = view('estimate/stepfinish');
                $view->page_css = $page_css;
                $view->estm_id = $estimate[0]->estm_id;
                $view->duration = $duration;
            
                return $view;
            });

            Route::get('/match/{estm_id}', function ($estm_id) {
                $user_id = LoginInfo::get();

                $sql_eb_count = "SELECT
                                (SELECT
                                    count(*)                                
                                FROM
                                    estimate_bidding eb
                                INNER JOIN
                                    estimate estm
                                ON
                                    eb.estm_id = estm.estm_id
                                WHERE
                                    estm.state = 1 AND
                                    estm.user_id = :user_id AND
                                    estm.estm_id = :estm_id) AS total_count,
                                (SELECT
                                    count(*)                                
                                FROM
                                    estimate_bidding eb
                                INNER JOIN
                                    estimate estm
                                ON
                                    eb.estm_id = estm.estm_id
                                INNER JOIN
                                    planner pln
                                ON
                                    eb.pln_id = pln.pln_id
                                WHERE
                                    estm.state = 1 AND
                                    estm.user_id = :user_id AND
                                    estm.estm_id = :estm_id AND
                                    pln.pln_type = 0) AS per_count,
                                (SELECT
                                    count(*)                                
                                FROM
                                    estimate_bidding eb
                                INNER JOIN
                                    estimate estm
                                ON
                                    eb.estm_id = estm.estm_id
                                INNER JOIN
                                    planner pln
                                ON
                                    eb.pln_id = pln.pln_id
                                WHERE
                                    estm.state = 1 AND
                                    estm.user_id = :user_id AND
                                    estm.estm_id = :estm_id AND
                                    pln.pln_type = 1) AS com_count";
                
                $ebs_count = DB::select($sql_eb_count, array('user_id'=>$user_id, 'estm_id'=>$estm_id));
                
                $sql_eb = "SELECT
                                estm.estm_id,
                                estm.estm_area,
                                estm.estm_period,
                                estm.updated_at at time zone 'KST' AS updated_at,
                                pln.pln_id,
                                pln.pln_type,
                                pln.pln_name,
                                pln.pln_thumb,
                                pln.pln_desc,
                                pln_score,
                                eb.eb_id,
                                eb.estm_asking_price,
                                eb.eb_title,
                                eb.eb_desc
                            FROM
                                estimate estm
                            LEFT JOIN
                                estimate_bidding eb
                                INNER JOIN
                                    planner pln
                                ON
                                    eb.pln_id = pln.pln_id
                            ON
                                estm.estm_id = eb.estm_id
                            WHERE
                                estm.state = 1 AND
                                estm.user_id = :user_id AND
                                estm.estm_id = :estm_id
                            ORDER BY eb.eb_id DESC";
            
                $ebs = DB::select($sql_eb, array('user_id'=>$user_id, 'estm_id'=>$estm_id));
                if(!isset($ebs[0]->updated_at)){
                    return redirect()->back()->with('jsAlert','해당 추천은 종료되었습니다.');
                }

                $duration = 86400 - (time() - strtotime($ebs[0]->updated_at));

                $page_css = "recommend";

                $view = view('estimate/matchinglist');
                $view->page_css = $page_css;
                $view->ebs_count = $ebs_count;
                $view->ebs = $ebs;
                $view->duration = $duration;

            
                return $view;
            });
        });

        Route::get('/product/{prd_id}', function ($prd_id) {
            $pln_id = LoginInfo::get();

            $sql_prd = "SELECT 
                        prd_id,
                        pln_id,
                        prd_slides,
                        prd_title,
                        prd_subtitle,
                        prd_desc,
                        prd_course,
                        prd_schedule,
                        prd_place_time,
                        prd_manual,
                        COALESCE((SELECT avg(revw_score) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_score,
                        COALESCE((SELECT count(revw_id) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_count,
                        state
                    FROM  product
                    WHERE prd_id = :prd_id;";

            $product = DB::select($sql_prd, array('prd_id'=>$prd_id));

            if($product[0]->state == 1){
                return redirect()->back()->with('jsAlert','해당 상품은 삭제되었습니다.');
            }

            $sql_revw = "SELECT 
                            revw.revw_id,
                            revw.prd_id,
                            revw.revw_content,
                            revw.user_id,
                            revw.created_at,
                            usr.name,
                            usr.user_thumb
                        FROM  review revw
                        INNER JOIN users usr 
                        ON revw.user_id = usr.id
                        WHERE revw.prd_id = :prd_id AND revw.state = 0
                        ORDER BY revw.created_at DESC
                        LIMIT 10";

            $reviews = DB::select($sql_revw, array('prd_id'=>$prd_id));
            
            $page_css = "product";

            $view = view('product/productview');
            $view->page_css = $page_css;
            $view->product = $product[0];
            $view->reviews = $reviews;

            return $view;
        });

        Route::get('/favorite', function () {
            $user_id = LoginInfo::get();

            $sql_like = "SELECT
                            pln.pln_id,
                            pln_type,
                            pln_name,
                            pln_thumb,
                            pln_desc
                        FROM
                            favorite fa
                        INNER JOIN
                            planner pln
                        ON
                            fa.pln_id = pln.pln_id
                        WHERE
                            user_id = :user_id";
            $likes = DB::select($sql_like, array('user_id'=>$user_id));
            
            
            
            $page_css = "like";
            
            $view = view('like/like');
            $view->page_css = $page_css;
            $view->likes = $likes;
        
            return $view;
        });

        Route::prefix('mypage')->group(function () {

            Route::get('/mypage', function () {
                $user_id = LoginInfo::get();

                $sql = "SELECT * FROM users WHERE id = :user_id";
                $userinfo = DB::select($sql,array('user_id'=>$user_id));

                $page_css = "mypage";
                
                $view = view('mypage/mypage');
                $view->page_css = $page_css;
                $view->userinfo = $userinfo;

                return $view;
            });

            //리뷰관리
            Route::get('/review', function () {
                $user_id = LoginInfo::get();

                $sql_review = "SELECT 
                                    RV.revw_id, 
                                    RV.revw_score, 
                                    RV.revw_content, 
                                    RV.created_at::timestamp(0),
                                    USR.user_thumb,
                                    USR.name
                                FROM review RV
                                INNER JOIN users USR
                                ON RV.user_id = USR.id
                                WHERE RV.user_id = :user_id
                                ORDER BY RV.created_at DESC
                                LIMIT 10;";
                $reviews = DB::select($sql_review,array('user_id'=>$user_id));

                $page_css = "mypage";
                
                $view = view('mypage/mypagereview');
                $view->page_css = $page_css;
                $view->reviews = $reviews;

                return $view;
            });

            //공지사항
            Route::get('/notice', function () {
                $sql_notice = "SELECT * FROM notice ORDER BY created_at DESC LIMIT 10";
                
                $notices = DB::select($sql_notice);

                $page_css = "mypage";
                
                $view = view('mypage/mypagenotice');
                $view->page_css = $page_css;
                $view->notices = $notices;

                return $view;
            });

            

            // 회원탈퇴
            Route::get('/withdraw', function () {
                $page_css = "mypage";
                
                $view = view('mypage/mypagewithdraw');
                $view->page_css = $page_css;

                return $view;
            });

        });

        // 정산 페이지
        Route::get('/calcul', function () {
            $page_css = "calculate";
            
            $view = view('calcul/calcul');
            $view->page_css = $page_css;

            return $view;
        });

        // 동행객 찾기 뷰
        Route::get('findview', function () {
            $page_css = "find";
            
            $view = view('find/view');
            $view->page_css = $page_css;

            return $view;
        });
        
        // 동행객 찾기 등록
        Route::get('findregist', function () {
            $page_css = "find";
            
            $view = view('find/regist');
            $view->page_css = $page_css;

            return $view;
        });
        
        //임시로 김보건이 만든 pay 페이지
        Route::get('/pay', function (Request $request) {
            if(isset($request->rsrv_id)){
                $rsrv_id = $request->rsrv_id;
            }else{
                return redirect('/mypage/mypage')->with('jsAlert','잘못된 경로로 접근하였습니다. 다시 시도해 주세요.');
            }
            $user_id = LoginInfo::get();
            
            $sql_rsrv = "SELECT 
                            rsrv.rsrv_id,
                            rsrv.eb_id,
                            rsrv.pln_id,
                            rsrv.rsrv_price,
                            rsrv.state,
                            rsrv.prd_id,
                            usr.user_point
                        FROM reserve rsrv 
                        INNER JOIN users usr
                        ON rsrv.user_id = usr.id
                        WHERE rsrv_id = :rsrv_id and user_id = :user_id and rsrv.state = 0";
                
            $pay_info = DB::select($sql_rsrv,array("rsrv_id"=>$rsrv_id, "user_id"=>$user_id));
            
            if(!isset($pay_info[0])){
                return redirect('/mypage/mypage')->with('jsAlert','이미 결제가 완료되었거나 취소된 상품입니다. 마이페이지에서 확인해주세요.');
            }

            $page_css = "pay";
            
            $view = view('pay/pay');
            $view->page_css = $page_css;
            $view->pay_info = $pay_info;
        
            return $view;
        });

        //페이 return_url
        Route::post('/perchaced-popup', function (Request $request) {
            $page_css = "sign-up";
            $data = json_decode(base64_decode($request->custom_parameter));

            
            $view = view('pay/paypopup');
            $view->page_css = $page_css;
			$view->pg_type = $data->pg_type;
			$view->message = $request->message;
        
            return $view;
        });

        

        //플래너란?
        Route::get('/aboutgrade', function () {
            $page_css = "grade";
            
            $view = view('planner/plannergrade');
            $view->page_css = $page_css;
        
            return $view;
        });


        //KJS Planner 페이지 작성시작

        Route::prefix('planner')->group(function () {
            //플래너 신청 페이지 (거절사유 공용)
            Route::get('intro', function () {
                $user_id = LoginInfo::get();
                $sql = "SELECT
                            state
                        FROM
                            planner
                        WHERE
                            pln_id = :user_id;";
                $pln_state = DB::select($sql, array('user_id'=>$user_id));
                $page_css = "planner";
                if (isset($pln_state[0]->state)) {
                    if ($pln_state[0]->state == 1) {
                        return redirect('/pln_ver/touristready');
                    }
                }
                
                $view = view('planner/plannerstatus');
                $view->page_css = $page_css;
                $view->pln_state = $pln_state;
                
                return $view;
            });

            Route::get('reg', function () {
                $user_id = LoginInfo::get();
                $page_css = "planner";
            
                $view = view('planner/plannerreg');
                $view->page_css = $page_css;
                
                return $view;
            });

            Route::get('/view/{pln_id}/{eb_id}', function ($pln_id,$eb_id = 0) {
                $user_id = LoginInfo::get();
                $sql_estm = "WITH TT AS(
                                SELECT count(pln_id) AS trades
                                FROM reserve
                                WHERE (state = 1 OR state =4)
                                AND reserve.pln_id = :pln_id 
                            ),TPD AS(
                                SELECT count(pln_id) AS products
                                FROM product
                                WHERE state = 0
                                AND pln_id = :pln_id 
                            ),TPF AS(
                                SELECT count(pln_id) AS portfolios
                                FROM portfolio
                                WHERE pln_id = :pln_id 
                            ),TR AS(
                                SELECT count(pln_id) AS reviews
                                FROM review
                                WHERE state = 0
                                AND pln_id = :pln_id 
                            )SELECT TP.pln_id
                            ,TP.pln_type
                            ,TP.pln_name
                            ,TP.pln_bg_photo
                            ,TP.pln_thumb
                            ,TP.pln_desc
                            ,TP.pln_info
                            ,TP.pln_trip_style
                            ,TP.pln_id_card
                            ,TP.pln_docs
                            ,TP.pln_mobile_ver_at
                            ,TP.pln_score
                            ,TP.pln_grade
                            ,TP.jurisdiction_area
                            ,fav.fav_id
                            , (SELECT trades FROM TT)
                            , (SELECT products FROM TPD)
                            , (SELECT portfolios FROM TPF)
                            , (SELECT reviews FROM TR)
                            FROM planner TP
                            LEFT JOIN favorite fav
                            ON TP.pln_id = fav.pln_id
                            AND fav.user_id = :user_id
                            WHERE TP.pln_id = :pln_id";
                $query = DB::select($sql_estm, array('pln_id'=>$pln_id,'user_id'=>$user_id));

                $page_css = "planner";
        
                $view = view('user-ver/plannerprofile');
                $view->page_css = $page_css;
                $view->query = $query[0];
                $view->eb_id = $eb_id;

                return $view;
            });
        });

        Route::prefix('msg')->group(function () {

            Route::get('list', function () {
                
                $page_css = "message";
            
                $view = view('message/msglist');
                $view->page_css = $page_css;
        
                return $view;
            });

            Route::get('view', function () {
                $page_css = "message";
                
                $view = view('message/msgview');
                $view->page_css = $page_css;
                
                return $view;
            });
        });
        
        //KJS Planner 페이지 작성끝
        //planner_ver
        Route::group(['middleware' => 'planner'], function () {
            Route::prefix('pln_ver')->group(function () {
                Route::get('profile', function () {
                    $pln_id = LoginInfo::get();

                    $sql_estm = "WITH TT AS(
                                    SELECT count(pln_id) AS trades
                                    FROM reserve
                                    WHERE (state = 1 OR state =4)
                                    AND pln_id = :pln_id 
                                ),TPD AS(
                                    SELECT count(pln_id) AS products
                                    FROM product
                                    WHERE state = 0
                                    AND pln_id = :pln_id 
                                ),TPF AS(
                                    SELECT count(pln_id) AS portfolios
                                    FROM portfolio
                                    WHERE pln_id = :pln_id 
                                ),TR AS(
                                    SELECT count(pln_id) AS reviews
                                    FROM review
                                    WHERE state = 0
                                    AND pln_id = :pln_id 
                                )SELECT TP.pln_id
                                ,TP.pln_type
                                ,TP.pln_name
                                ,TP.pln_bg_photo
                                ,TP.pln_thumb
                                ,TP.pln_desc
                                ,TP.pln_info
                                ,TP.pln_trip_style
                                ,TP.pln_id_card
                                ,TP.pln_docs
                                ,TP.pln_mobile_ver_at
                                ,TP.pln_score
                                ,TP.pln_grade
                                ,TP.jurisdiction_area
                                , (SELECT trades FROM TT)
                                , (SELECT products FROM TPD)
                                , (SELECT portfolios FROM TPF)
                                , (SELECT reviews FROM TR)
                                FROM planner TP
                                WHERE pln_id = :pln_id;";

                    $query = DB::select($sql_estm, array('pln_id'=>$pln_id));
                    $page_css = "planner";
                    $view = view('planner-ver/planneredit');
                    $view->page_css = $page_css;
                    $view->query = $query[0];
                                    
                    return $view;
                });
                Route::get('touristready', function () {
                    $pln_id = LoginInfo::get();

                    $sql_juri = "SELECT jsonb_array_elements_text(jurisdiction_area::jsonb) as juri FROM planner WHERE pln_id = :pln_id";
                    $juris = DB::select($sql_juri, array('pln_id'=>$pln_id));

                    $whereClause = '';
                    if(isset($juris[0]->juri)){
                        $whereClause = 'AND ( 1 != 1';
                        foreach($juris as $juri){
                            $juri_explode = explode(" ",$juri->juri);
                            $whereClause .= " OR estm_area like '%".$juri_explode[count($juri_explode)-1]."%'";
                        }
                        $whereClause .= ' )';
                    }
                    
                    $sql_estm = "SELECT 
                                estm.estm_id,
                                estm.user_id,
                                estm.estm_area,
                                estm.estm_area_type,
                                estm.estm_period,
                                estm.estm_group_qtt,
                                estm.estm_budget_min,
                                estm.estm_budget_max,
                                estm.estm_theme,
                                estm.estm_step4,
                                estm.estm_step5,
                                estm.updated_at at time zone 'KST' AS updated_at,
                                usr.name,
                                usr.user_thumb
                                FROM estimate estm
                                INNER JOIN users usr
                                ON estm.user_id = usr.id
                                WHERE estm.state = 1  AND user_id != :user_id AND
                                COALESCE((SELECT eb_id FROM estimate_bidding WHERE pln_id = :pln_id AND estm_id = estm.estm_id),0) = 0
                                ".$whereClause."
                                ORDER BY estm.estm_id DESC
                                OFFSET 0 LIMIT 10";
                    
                    $estms = DB::select($sql_estm, array('pln_id'=>$pln_id, 'user_id'=>$pln_id));
                    $sql_eb = "SELECT
                                    u.name,
                                    estm.estm_area,
                                    estm.estm_period,
                                    estm.estm_group_qtt,
                                    estm.estm_budget_min,
                                    estm.estm_budget_max,
                                    eb.estm_asking_price,
                                    eb.created_at at time zone 'KST' AS created_at
                                FROM
                                    estimate_bidding eb
                                JOIN estimate estm ON
                                    eb.estm_id = estm.estm_id
                                JOIN users u ON
                                    estm.user_id = u.id
                                WHERE
                                    eb.pln_id = :pln_id";
                    $ebs = DB::select($sql_eb, array('pln_id'=>$pln_id));
                    
                    $page_css = "plnr-ver";
            
                    $view = view('planner-ver/plannertouristready');
                    $view->page_css = $page_css;
                    $view->estms = $estms;
                    $view->ebs = $ebs;
                    
                    return $view;
                });
                Route::prefix('msg')->group(function () {
                    Route::get('list', function () {
                        $pln_id = LoginInfo::get();
        
                        $sql_estm = "";
        
                        //$query = DB::select($sql_estm, array('pln_id'=>$pln_id));
        
                        $page_css = "message";
                
                        $view = view('planner-ver/plannermsglist');
                        $view->page_css = $page_css;
                        //$view->query = $query[0];
            
                        return $view;
                    });
                    Route::get('detail', function (Request $request) {
        
        
                        $page_css = "message";
                
                        $view = view('planner-ver/plannermsgdetail');
                        $view->page_css = $page_css;
            
                        return $view;
                    });
                });
                Route::get('/product/{prd_id}', function ($prd_id) {
                    $pln_id = LoginInfo::get();

                    $sql_prd = "SELECT 
                                prd_id,
                                pln_id,
                                prd_slides,
                                prd_title,
                                prd_subtitle,
                                prd_desc,
                                prd_course,
                                prd_schedule,
                                prd_place_time,
                                prd_manual,
                                COALESCE((SELECT avg(revw_score) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_score,
                                COALESCE((SELECT count(revw_id) FROM review WHERE prd_id = product.prd_id AND state = 0),0) as prd_count,
                                state
                            FROM  product
                            WHERE prd_id = :prd_id AND pln_id = :pln_id";

                    $product = DB::select($sql_prd, array('pln_id'=>$pln_id, 'prd_id'=>$prd_id));

                    if(!isset($product[0])){
                        return redirect()->back()->with('jsAlert','해당 페이지에 대한 권한이 없습니다.');
                    }
                    if($product[0]->state == 1){
                        return redirect()->back()->with('jsAlert','해당 상품은 삭제되었습니다.');
                    }

                    $sql_revw = "SELECT 
                                    revw.revw_id,
                                    revw.prd_id,
                                    revw.revw_content,
                                    revw.user_id,
                                    revw.created_at at time zone 'KST' AS created_at,
                                    usr.name,
                                    usr.user_thumb
                                FROM  review revw
                                INNER JOIN users usr 
                                ON revw.user_id = usr.id
                                WHERE revw.prd_id = :prd_id AND revw.state = 0
                                ORDER BY revw.created_at DESC
                                LIMIT 10";

                    $reviews = DB::select($sql_revw, array('prd_id'=>$prd_id));
                    
                    $page_css = "product";
            
                    $view = view('product/productedit');
                    $view->page_css = $page_css;
                    $view->product = $product[0];
                    $view->reviews = $reviews;
        
                    return $view;
                });
            });
        });
    });

});