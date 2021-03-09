<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
            // 코인마켓캡 시세 업데이트
            $url = "https://api.coinmarketcap.com/v1/ticker/?convert=KRW";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $output = curl_exec ($ch);
            curl_close ($ch);
            $obj = json_decode($output);
            
            for($i=0;$i<100;$i++){
                $usd_price = floatval($obj[$i]->price_usd) * 1;
                $btc_price = floatval($obj[$i]->price_btc) * 1;
                $krw_price = floatval($obj[$i]->price_krw) * 1;
                
                DB::table('btc_coins')->where('api', strtolower($obj[$i]->symbol))
                ->update([
                    "last_coinmarketcap_price_usd" => $usd_price,
                    "last_coinmarketcap_price_btc" => $btc_price,
                    "last_coinmarketcap_price_krw" => $krw_price,
                ]);
            }
        })->everyMinute();
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
