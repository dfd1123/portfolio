<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Auth;
use Secure;
use DB;
use App;

class ChartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $chart_json = array();

        $coins = DB::table('btc_coins')->where('active',1)->where('cointype','<>','cash')->orderBy('id','asc')->get();
        foreach($coins as $coin){
            $chart_datas = DB::table('btc_trades_COIN_btc')->select('buy_coin_price','created')->where('currency','krw')->where('cointype',$coin->api)->where('created', '>', DB::raw('UNIX_TIMESTAMP( NOW() - INTERVAL 24 HOUR )'))->get();
            foreach($chart_datas as $chart_data){
                $chart_json[$coin->api][] = array((int) bcmul($chart_data->created,1000,0), (int) bcmul($chart_data->buy_coin_price,1,0));
                //$chart_json[$coin->api] = json_encode($chart_data);
            }
            if(count($chart_datas) == 0){
                $days_ago = strtotime('-1 day',time());
                $chart_json[$coin->api][] = array((int) bcmul(time(),1000,0), (int) bcmul($coin->last_trade_price_krw,1,0));
                $chart_json[$coin->api][] = array((int) bcmul($days_ago,1000,0), (int) bcmul($coin->last_trade_price_krw,1,0));
            }
            
        }

        return json_encode($chart_json);
    }

}
