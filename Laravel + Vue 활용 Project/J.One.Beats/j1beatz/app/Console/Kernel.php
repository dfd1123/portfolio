<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use \Facades\App\Classes\LicenseOrder;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            DB::delete("
            DELETE FROM email_verify_temp
            WHERE 1 = 1
                AND updated_at <= (now() - INTERVAL '1 DAY')
            ");

            info("email_verify_temp clear");
        })->timezone('Asia/Seoul')->daily();

        $schedule->call(function () {
            DB::delete("
            DELETE FROM mobile_verify_temp
            WHERE 1 = 1
                AND updated_at <= (now() - INTERVAL '1 DAY')
            ");

            info("mobile_verify_temp clear");
        })->timezone('Asia/Seoul')->daily();

        $schedule->call(function () {
            DB::delete("
            DELETE FROM audio_verify_temp
            WHERE 1 = 1
                AND expires_at <= now()
            ");
        })->timezone('Asia/Seoul')->everyFifteenMinutes();

        $schedule->call(function () {
            DB::update("
            UPDATE beat_order SET
                state = 0
            WHERE 1 = 1
                AND created_at <= (now() - INTERVAL '20 MINUTE')
                AND po_pg_type != 1
                AND state = 1
            ");

            DB::update("
            UPDATE beat_order SET
                state = 0
            WHERE 1 = 1
                AND created_at <= date_trunc('day', now() - INTERVAL '8 DAY')
                AND po_pg_type = 1
                AND state = 1
            ");
        })->timezone('Asia/Seoul')->everyMinute();

        $schedule->call(function () {
            DB::update("
            UPDATE license_order SET
                state = 0
            WHERE 1 = 1
                AND created_at <= (now() - INTERVAL '20 MINUTE')
                AND lo_pg_type != 1
                AND state = 1
            ");
        })->timezone('Asia/Seoul')->everyMinute();

        $schedule->call(function () {
            // 라이센스 갱신
            info('라이센스 갱신 처리 시작');

            // 갱신 대상 라이센스 조회 (만료일이 내일인 자동결제)
            $renews = DB::select("
            WITH renews AS (
                SELECT 
                    lo.user_id, 
                    max(lo.lo_id) AS lo_id
                FROM license_order lo, license l
                WHERE 1 = 1
                    AND lo.lo_pg_type = 0
                    AND lo.autopay = 1
                    AND lo.pg_info IS NOT NULL
                    AND lo.end_at >= now()
                    AND lo.state = 2
                    AND l.state = 1
                GROUP BY lo.user_id
                ORDER BY lo.user_id DESC
            )
            SELECT 
                lo.lo_id, 
                lo.user_id,
                lo.lcens_id,
                lo.lo_pg_type,
                lo.pg_info,
                lo.reg_at,
                lo.end_at,
                u.user_name,
                l.lcens_id,
                l.lcens_name,
		l.lcens_desc,
                l.lcens_type,
                l.lcens_price
            FROM renews r
            JOIN license_order lo ON r.lo_id = lo.lo_id
            JOIN license l ON lo.lcens_id = l.lcens_id
            JOIN users u ON lo.user_id = u.user_id
            WHERE 1 = 1
                AND date_trunc('day', lo.end_at - INTERVAL '1 DAY') = date_trunc('day', now())
            ");

            foreach ($renews as $renew) {
                // 실행 시간 초기화
                set_time_limit(300);

                // 자동결제 처리
                info('[자동결제 갱신 시작] lo_id: ' . $renew->lo_id . ', user_id: ' . $renew->user_id);

                // pg_info, 로그 찍기
                info($renew->pg_info);
                $pg_info = json_decode($renew->pg_info);

                $pgcode = ["creditcard"][$renew->lo_pg_type];
                $billkey = $pg_info->billkey;

                // 결제방식에 따라 클라이언트 id 분기처리
                $client_id = null;
                $api_key = null;
                if ($pgcode == "creditcard") {
                    $client_id = 'j1beatz2';
                    $api_key = config('p1q.POQ_KEY_AUTO_PAY');
                } else {
                    continue;
                }

                // 사용자 결제요청 상품명
                $product_name = $renew->lcens_name . ' ' . mb_substr($renew->lcens_desc, 0, 20) . '...';

                // 결제 요청 보내기
                $fParam = [
                    'pgcode' => $pgcode,
                    'client_id' => $client_id,
                    'service_name' =>'J1Beatz',
                    'user_id' => $renew->user_id,
                    'user_name' => $renew->user_name,
                    'amount' => $renew->lcens_price,
                    'product_name' => $product_name,
                    'billkey' => $billkey,
                    'ip_addr' => gethostbyname(gethostname())
                ];
                
                //결제요청전 로그찍기
                $fParam = json_encode($fParam);
                info('결제요청전: ' . $fParam);

                //결제요청후 로그찍기
                $result = $this->execAutoCURL($fParam, 'autopay', $api_key);
                info('결제요청후: ' . $result);

                $new_pg_info = json_decode($result);
                if (isset($new_pg_info->code) && $new_pg_info->code != 0) {
                    // 자동결제 실패
                    info('[자동결제 갱신 에러] lo_id: ' . $renew->lo_id . ', user_id: ' . $renew->user_id);
                } else {
                    // 자동결제 성공
                    $lo_id = LicenseOrder::renew($renew->user_id, $renew->lcens_id, $renew->lo_pg_type, $result);

                    // 이용권 갱신
                    info('License renewed: ' . $renew->lo_id . ' -> ' . $lo_id . ', user_id: ' . $renew->user_id);
                }
            }

            info('라이센스 갱신 처리 완료');
        })
        ->name('renew')
        ->withoutOverlapping()
        ->timezone('Asia/Seoul')
        ->hourly()
        ->between('10:00', '18:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    private function execAutoCURL($fParam, $URL='request', $api_key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pgapi.payletter.com/v1.0/payments/'.$URL);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization: PLKEY '.$api_key
            , 'Content-Type:application/json')
        );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fParam);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
