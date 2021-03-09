<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\DB;

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
        //플래너 평점,등급 갱신
        $schedule->call(function(){
            $sql = "UPDATE 
                        planner pln 
                    SET 
                        pln_score = coalesce((SELECT avg(revw_score) FROM review WHERE pln_id = pln.pln_id),0), 
                        pln_grade = CASE WHEN pln_score < 1.66 THEN '브론즈' ELSE (CASE WHEN pln_score < 3.33 THEN '실버' ELSE '골드' END) END
                    WHERE
                        pln_id >0 ;";
            $check = DB::update($sql);
        })->timezone('Asia/Seoul')->hourly();
        
        //시간지난견적종료
        $schedule->call(function(){
            $sql = "UPDATE estimate
                    SET state = 3
                    WHERE updated_at <= (NOW() - '1 day'::interval)::timestamp AND estm_id > 0 AND state = 1";
            $check = DB::update($sql);
        })->timezone('Asia/Seoul')->everyMinute();
        
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
