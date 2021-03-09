<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Schedule\Lock_coin;
use App\Schedule\Chart_history;
use App\Schedule\Auto_trade;
use Visitor;
use Trade_new;

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
        /*$schedule->call(function(){
            Auto_trade::auto_recovery();
        })->everyFiveMinutes();*/
        
        /*$schedule->call(function () {
            // 코인락 기능
            // 매일 18시마다 락 중인 금액 스냅샷
            Lock_coin::lock_snapshot_daily();
        })->timezone('Asia/Seoul')->dailyAt('18:00');

        $schedule->call(function () {
            // 코인락 기능
            // 1시간마다 락 해제요청된 금액 중에서 24시간이 지난 금액 반환
            Lock_coin::lock_release_unlocking();
        })->timezone('Asia/Seoul')->hourly();

        $schedule->call(function () {
            // 코인락 기능
            // 금요일 17시마다 배당액 계산 후 배당 1(월요일) ~ 5(금요일) ~ 7(일요일)
            Lock_coin::lock_dividend();
        })->timezone('Asia/Seoul')->weeklyOn(5, '17:00');*/
        
        $schedule->call(function () {
            // 에러사항 취소 후 잔액 복구 or 봇 체결 쌓인거 취소
            Auto_trade::auto_cancel_new();
        })->timezone('Asia/Seoul')->daily();

        /*$schedule->call(function () {
            // 사전판매 물량 풀기
            $lock_start_date = strtotime("2019-10-07 00:00:00");
            $lock_end_date = strtotime("2019-11-01 00:00:00");
            $now_date = strtotime(date("Y-m-d H:i:s"));

            if($now_date >= $lock_start_date && $now_date < $lock_end_date){
                Lock_coin::lock_precoin_release();
            }
            
        })->timezone('Asia/Seoul')->dailyAt('12:00');*/

        $schedule->call(function () {
            // 5분마다 코인마켓캡 시세 업데이트
            Chart_history::reset_coinmarketcap_price_all();
        })->everyMinute();
        
        /*$schedule->call(function(){
            // 1분마다 자동거래
            Auto_trade::auto_trading_usd();
        })->everyTenMinutes();
        
        $schedule->call(function(){
            // 1분마다 자동거래
            Auto_trade::auto_trading_btc();
        })->everyTenMinutes();

        $schedule->call(function(){
            // 1분마다 자동거래
            Auto_trade::auto_trading_eth();
        })->everyTenMinutes();*/

        /*$schedule->call(function () {
            // 1분마다 자동거래
            Auto_trade::auto_trading_krw();
        })->everyTenMinutes();*/

        $schedule->call(function () {
            // 1분마다 상태 변경
            Chart_history::update_market_info_new();
        })->everyMinute();

        $schedule->call(function () {
            // 10분마다 방문자수 갱신
            Visitor::sweep();
        })->everyTenMinutes();
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
}
