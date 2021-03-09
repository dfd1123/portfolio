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

Route::view('/', 'login');


Route::group(['middleware' => ['checkadmin']], function(){
	
		Route::get('dashboard', function () {
			$view = "dashboard";

			$sql = "SELECT
			(
				SELECT COUNT(*) 
				FROM users
				WHERE state = 1
			) AS user,
			(
				SELECT COUNT(*) 
				FROM producer
				WHERE state = 1
			) AS prdc,
			(
				SELECT COUNT(*)
				FROM beat
				WHERE state = 1
			) AS beat,
			(
				SELECT COUNT(*)
				FROM qna
				WHERE answered_at IS NULL
			) AS qna,
			(
				SELECT COUNT(*)
				FROM producer
				WHERE state = 0
			) AS wait
			";

			$res = data_get(DB::select($sql, []), 0, null);

			$returnview = view($view);
			$returnview->counts = $res;
			return $returnview;
		});
		
    //1:1문의
    Route::prefix('bbs')->group(function () {
        Route::get('/', function () {
            $view = "settings/bbs/main";
                
            $sql1 = "SELECT
					qna_id,
					users.user_id,
					users.user_name,
					users.user_nick,
					users.user_mobile,
					qna_title,
					qna_content,
					qna_answer,
					qna.state,
					qna.created_at::DATE
				FROM
					qna
				LEFT JOIN users ON
					qna.user_id = users.user_id
				WHERE
					qna_answer IS NULL
				ORDER BY
					created_at DESC OFFSET 0
				limit 20";
            $res1 = DB::select($sql1);
                
            $sql2 = "SELECT
					qna_id,
					users.user_id,
					users.user_name,
					users.user_nick,
					users.user_mobile,
					qna_title,
					qna_content,
					qna_answer,
					qna.state,
					qna.created_at::DATE
				FROM
					qna
				LEFT JOIN users ON
					qna.user_id = users.user_id
				WHERE
					qna_answer IS NOT NULL
				ORDER BY
					created_at DESC OFFSET 0
				limit 20";
            $res2 = DB::select($sql2);

            $returnview = view($view);
            $returnview->query1 = $res1;
            $returnview->query2 = $res2;
            return $returnview;
        });
        Route::view('list', 'bbs/list');
        //세부사항
        Route::view('detail', 'bbs/detail');
    });
    //음악관리
    Route::prefix('beat')->group(function () {
        Route::get('/', function () {
            $view = "beat/main";
            $sql = "SELECT
							BT.beat_id,
							PT.prdc_id,
							PT.prdc_nick,
							BT.beat_title,
							BT.beat_time,
							BT.state
						FROM
							beat BT
						LEFT JOIN producer PT ON
							BT.prdc_id = PT.prdc_id
						ORDER BY BT.beat_id DESC
						OFFSET 0 limit 20;";
            $res = DB::select($sql);

            $returnview = view($view);
            $returnview->query = $res;
            return $returnview;
        });

        Route::get('detail', function (Request $request) {
            $view = "beat/detail";
            $sql ="SELECT
								b.beat_id,
								b.beat_title,
								b.beat_time,
								b.created_at::DATE,
								coalesce(b.beat_path->>'mp3', '') mp3,
								b.state,
								p.prdc_id,
								p.prdc_nick,
								m.mood_title,
								c.cate_title,
								r.beat_hit,
								r.beat_like,
								COALESCE((
										SELECT
												count(beat_id)
										FROM cart
										WHERE 1 = 1
												AND beat_id = :beat_id
										GROUP BY beat_id
								), 0) beat_wish, --장바구니
								COALESCE((
										SELECT
												count(beat_id)
										FROM beat_request_history
										WHERE 1 = 1
												AND beat_id = :beat_id
												AND brh_type IN (2, 3)
										GROUP BY beat_id
								), 0) beat_free, --무료
								COALESCE((
										SELECT count(beat_id)
										FROM beat_order
										WHERE 1 = 1
												AND beat_id = :beat_id
												AND state = 2
										GROUP BY beat_id
								), 0) beat_buy, --구매
								COALESCE((
										SELECT jsonb_agg(t)
										FROM 
										(
												SELECT 
														c.cmt_id, u.user_name, c.cmt_content, c.created_at::DATE 
												FROM comment c
												LEFT JOIN users u ON c.user_id = u.user_id
												WHERE 1 = 1
														AND beat_id = :beat_id
														AND c.state = 1
												OFFSET 0 limit 100
										) t
								), '[]') comment_info
						FROM beat b
						LEFT JOIN mood m ON b.mood_id = m.mood_id
						LEFT JOIN category c ON b.cate_id = c.cate_id
						LEFT JOIN producer p ON b.prdc_id = p.prdc_id
						LEFT JOIN ranks r ON b.beat_id = r.beat_id
						WHERE b.beat_id = :beat_id";

						$res = DB::select($sql, [
							'beat_id' => $request->beat_id
						]);
						
						// 월별 판매 수
						$sql2 ="
						WITH months AS (
								select date_part('month', date)::int AS beat_month
								from generate_series(
										(now() - INTERVAL '11 month'), now(), '1 month'::INTERVAL
								) date
						), counts AS (
								SELECT
										date_part('month', created_at) AS beat_month, 
										count(bo.beat_id) beat_count
								FROM beat_order bo
								WHERE 1 = 1
										AND bo.beat_id = :beat_id
										AND bo.state = 2
										AND created_at BETWEEN date_trunc('month', now() - INTERVAL '11 month') AND now()
								GROUP BY date_part('month', created_at), bo.beat_id
						)
						SELECT
								m.beat_month,
								COALESCE(c.beat_count, 0) beat_count
						FROM months m
						LEFT JOIN counts c ON m.beat_month = c.beat_month";

						$res2 = DB::select($sql2, [
							'beat_id' => $request->beat_id
						]);
						
            $returnview = view($view);
						$returnview->query = $res;
						$returnview->buys_for_month = $res2;
						$returnview->comment_info = json_decode(data_get($res[0]->comment_info, []));
            return $returnview;
        });
    });

    //FAQ
    Route::prefix('faq')->group(function () {
        Route::get('/', function () {
            $view = "settings/faq/main";
            $sql = "SELECT
	faq_id,
	faq_question,
	faq_answer,
	reg_at::date
FROM
	faq
ORDER BY
	reg_at DESC OFFSET 0
limit 20;";
            $res = DB::select($sql);

            $returnview = view($view);
            $returnview->query = $res;
            return $returnview;
        });
        Route::view('list', 'faq/list');
        //세부사항
        Route::view('detail', 'faq/detail');
    });

//카테고리
Route::prefix('genre')->group(function () {
    Route::get('/', function () {
			$view = "genre/main";	
			$sql = "SELECT
				cate_id,
				cate_title,
				state
			FROM
				category
			ORDER BY
				cate_id DESC OFFSET 0
			limit 20;";
			$res = DB::select($sql);

            $returnview = view($view);
            $returnview->query = $res;
            return $returnview;
        });
        Route::view('list', 'genre/list');
        //세부사항
        Route::view('detail', 'genre/detail');
        //등록, 수정
        Route::view('edit', 'genre/edit');
    });

    //프로듀서
    Route::prefix('maker')->group(function () {
        Route::get('/', function () {
            $view = "maker/main";
            $sql = "SELECT
			prdc_id,
			prdc_nick,
			created_at::DATE,
			state,
			ARRAY_TO_JSON(ARRAY(SELECT ROW_TO_JSON(tmp1) FROM (
			SELECT 
				cate_title 
			FROM 
				producer TP
			JOIN 
				category TC 
			ON TP.cate_json @> TC.cate_id::TEXT::JSONB
			AND TP.prdc_id = PT.prdc_id
			)tmp1)) AS cate_info,
			ARRAY_TO_JSON(ARRAY(SELECT ROW_TO_JSON(tmp1) FROM (
			SELECT 
				mood_title 
			FROM 
				producer TP
			JOIN 
				mood ATT 
			ON TP.mood_json @> ATT.mood_id::TEXT::JSONB
			AND TP.prdc_id = PT.prdc_id
			)tmp1)) AS atmo_info
		FROM
			producer PT
		ORDER BY created_at DESC, prdc_id desc
		OFFSET 0 limit 20;";
            $res = DB::select($sql);

            $returnview = view($view);
            $returnview->query = $res;
            return $returnview;
        });
        //세부 화면
        Route::get('detail', function (Request $request) {
						$view = "maker/detail";
						
						$sql ="SELECT
								p.prdc_id,
								p.prdc_img,
								p.prdc_nick,
								p.prdc_sample,
								p.state,
								COALESCE((
										SELECT jsonb_agg(t)
										FROM
										(
												SELECT 
														c.cate_title 
												FROM producer p, category c, jsonb_array_elements_text(cate_json)
												WHERE 1 = 1
														AND p.prdc_id = :prdc_id
														AND value = c.cate_id::text
										) t
								), '[]') AS cate_info,
								COALESCE((
										SELECT jsonb_agg(t)
										FROM 
										(
												SELECT 
														m.mood_title 
												FROM producer p, mood m, jsonb_array_elements_text(mood_json)
												WHERE 1 = 1
														AND p.prdc_id = :prdc_id
														AND value = m.mood_id::text
										) t
								), '[]') AS atmo_info,
								COALESCE((
										SELECT
												count(p.prdc_id)
										FROM beat_request_history brh, beat b, producer p
										WHERE 1 = 1
												AND brh.beat_id = b.beat_id
												AND b.prdc_id = p.prdc_id
												AND p.prdc_id = :prdc_id
												AND brh.brh_type IN (2, 3)
										GROUP BY p.prdc_id
								), 0) prdc_free, --무료
								COALESCE(b.prdc_like, 0) AS prdc_like,
								COALESCE(b.prdc_follow, 0) AS prdc_follow,
								COALESCE(b.prdc_buy, 0) AS prdc_buy 
						FROM producer p
						LEFT JOIN bests b ON p.prdc_id = b.prdc_id
						WHERE p.prdc_id = :prdc_id";
        
						$res = DB::select($sql, [
							'prdc_id' => $request->prdc_id
						]);
						
						$sql2 ="
						WITH months AS (
								select date_part('month', date)::int AS beat_month
								from generate_series(
										(now() - INTERVAL '11 month'), now(), '1 month'::INTERVAL
								) date
						), counts AS (
								SELECT
										date_part('month', bo.created_at) AS beat_month, 
										count(bo.beat_id) beat_count
								FROM beat_order bo 
								JOIN beat b ON bo.beat_id = b.beat_id
								JOIN producer p ON b.prdc_id = p.prdc_id
								WHERE 1 = 1
										AND p.prdc_id = :prdc_id
										AND bo.state = 2
										AND bo.created_at BETWEEN date_trunc('month', now() - INTERVAL '11 month') AND now()
								GROUP BY date_part('month', bo.created_at), bo.beat_id
						)
						SELECT
								m.beat_month,
								COALESCE(c.beat_count, 0) beat_count
						FROM months m
						LEFT JOIN counts c ON m.beat_month = c.beat_month
						";

						$res2 = DB::select($sql2, [
							'prdc_id' => $request->prdc_id
						]);

						info($res2);

            $returnview = view($view);
						$returnview->query = $res;
						$returnview->buys_for_month = $res2;
            return $returnview;
        });

        //활성 비활성 목록
        Route::view('state', 'maker/state');
    });
    //공지
    Route::prefix('notice')->group(function () {
        //목록
        Route::get('/', function () {
            $view = "settings/notice/main";
            $sql = "SELECT
	notice_id,
	notice_title,
	notice_content,
	created_at::date
FROM
	notice 
ORDER BY
	notice_id desc OFFSET 0
limit 20;";
            $res = DB::select($sql);

            $returnview = view($view);
            $returnview->query = $res;
            return $returnview;
        });
        //등록 삭제
        Route::view('edit', 'notice/list');
        //세부사항
        Route::view('detail', 'notice/detail');
    });

    //분위기
    Route::prefix('theme')->group(function () {
        Route::get('/', function () {
            $view = "theme/main";
            $sql = "SELECT
	mood_id,
	mood_title,
	mood_thumb,
	state
FROM
	mood
ORDER BY
	mood_id DESC OFFSET 0
limit 20;";
            $res = DB::select($sql);

            $returnview = view($view);
            $returnview->query = $res;
            return $returnview;
        });
        //세부사항
        Route::view('detail', 'theme/detail');
        //등록, 수정
        Route::view('edit', 'theme/edit');
    });

    //유저
    Route::prefix('users')->group(function () {
        Route::get('/', function () {
            $view = "users/main";
            $sql = "SELECT
			state,
			user_id,
			user_nick,
			user_email,
			user_name,
			user_agr_email_prom,
			created_at::DATE
		FROM
			users
		ORDER BY user_id DESC
		OFFSET 0 limit 20;";
            $res = DB::select($sql);

            $returnview = view($view);
            $returnview->query = $res;
            return $returnview;
        });
        //세부사항
        Route::get('detail', function (Request $request) {
            $view = "users/detail";
            $sql ="SELECT
			state,
			user_nick,
			user_email,
			user_name,
			user_mobile,
			created_at::DATE,
			(
				SELECT
					COUNT(user_id)
				FROM
					beat_order
				WHERE
					user_id = :user_id
					AND beat_price IS NOT NULL
					AND lo_id IS NULL
					AND download_type = 0
				GROUP BY
					user_id
			) AS mp3_down,
			(
				SELECT
					COUNT(user_id)
				FROM
					beat_order
				WHERE
					user_id = :user_id
					AND beat_price IS NOT NULL
					AND lo_id IS NULL
					AND download_type = 1
				GROUP BY
					user_id
			) AS wav_down,
			(
				SELECT
					COUNT(user_id)
				FROM
					beat_order
				WHERE
					user_id = :user_id
					AND beat_price IS NULL
					AND lo_id IS NOT NULL
				GROUP BY
					user_id
			) AS license_down
		FROM
			users
		WHERE user_id = :user_id";
        
            $res = DB::select($sql, array('user_id'=>$request->input()['user_id']));
            $returnview = view($view);
            $returnview->query = $res;
        
            //count null값 error 방지
            if ($res[0]->license_down == null) {
                $returnview->license_cnt = 0;
            } else {
                $returnview->license_cnt = $res[0]->license_down;
            }
            if ($res[0]->mp3_down == null) {
                $returnview->mp3_cnt = 0;
            } else {
                $returnview->mp3_cnt = $res[0]->mp3_down;
            }
            if ($res[0]->wav_down == null) {
                $returnview->wav_cnt = 0;
            } else {
                $returnview->wav_cnt = $res[0]->wav_down;
            }
        
            $sql2 = "SELECT
			BT.beat_title,
			BT.beat_thumb,
			PT.prdc_nick,
			BT.created_at::date
		FROM
			beat_order BOT
		LEFT JOIN beat BT ON
			BOT.beat_id = BT.beat_id
		LEFT JOIN producer PT ON
			BT.prdc_id = PT.prdc_id
		WHERE user_id = :user_id";

            $res2 = DB::select($sql2, array('user_id'=>$request->input()['user_id']));
            $returnview->query2 = $res2;
            return $returnview;
        });
        //활성 비활성 목록
        Route::view('state', 'users/state');
    });

    Route::prefix('settings')->group(function () {
        Route::view('/', 'settings/main');

        //배너
        Route::prefix('banner')->group(function () {
            //목록
            Route::get('/', function () {
                $view = "settings/banner/main";
                $sql = "SELECT
	banner_id,
	banner_title,
	banner_img,
	banner_content
FROM
	banner
ORDER BY
	banner_id DESC OFFSET 0
limit 20;";
                $res = DB::select($sql);

                $returnview = view($view);
                $returnview->query = $res;
                return $returnview;
            });
            //등록 삭제
            Route::view('edit', 'settings/banner/list');
            //세부사항
            Route::view('detail', 'settings/banner/detail');
        });

        //이용권
        Route::prefix('license')->group(function () {
            //목록
            Route::get('/', function () {
                $view = "settings/license/main";
                $sql = "SELECT
	lcens_id,
	lcens_name,
	lcens_price,
	lcens_type,
	lcens_days,
	lcens_desc,
	state
FROM
	license
ORDER BY
	lcens_id DESC OFFSET 0
limit 20;";
                $res = DB::select($sql);

                $returnview = view($view);
                $returnview->query = $res;
                return $returnview;
            });
            //등록 삭제
            Route::view('edit', 'license/list');
            //세부사항
            Route::view('detail', 'license/detail');
        });

        //정산
        Route::prefix('bookkeeping')->group(function () {
            //목록
            Route::get('/', function () {
                $view = 'settings/bookkeeping/main';
                $sql = "SELECT
						u.user_id,
						u.user_name,
						b.beat_id,
						b.beat_title,
						p.prdc_nick,
						p.prdc_bnk_accnt,
						bo.po_id,
						bo.po_pg_type,
						(SELECT user_id FROM users WHERE user_id = bo.user_id) buy_user_id,
						(SELECT user_name FROM users WHERE user_id = bo.user_id) buy_user_name,
						(SELECT user_nick FROM users WHERE user_id = bo.user_id) buy_user_nick,
						bo.beat_price,
						round(bo.beat_price * 0.20) fee,
						bo.beat_price - round(bo.beat_price * 0.20) total,
						bo.created_at,
						bo.po_state,
						bo.po_reg_dt,
						bo.po_cpl_dt
					FROM beat_order bo
					LEFT JOIN beat b ON bo.beat_id = b.beat_id
					LEFT JOIN producer p ON b.prdc_id = p.prdc_id
					LEFT JOIN users u ON p.prdc_id = u.user_id
					WHERE 1 = 1
						AND bo.state = 2
						AND bo.po_state IN (1,2)
					ORDER BY po_reg_dt DESC, po_id DESC
					OFFSET 0 LIMIT 20";
                $res = DB::select($sql, []);

                $returnview = view($view);
                $returnview->query = $res;
                return $returnview;
            });
            //세부사항
            Route::view('detail', 'settings/bookkeeping/detail');
        });
        //푸쉬 메세지
        Route::prefix('push')->group(function () {
            //목록
            Route::get('/', function () {
                return view('settings/push/main');
            });
        });
    });
});
