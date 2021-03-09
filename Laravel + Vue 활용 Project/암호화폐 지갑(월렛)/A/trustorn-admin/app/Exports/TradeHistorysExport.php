<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class TradeHistorysExport implements FromCollection, ShouldAutoSize
{
    public function __construct($from, $to) {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','2048M');

        $columns = [
            'btc_ads_btc.id',
            'users.id as uid',
            'btc_ads_btc.created_dt',
            'btc_ads_btc.updated_dt',
            'users.email',
            'users.mobile_number',
            'users.fullname',
            'btc_ads_btc.cointype',
            'btc_ads_btc.currency',
            'btc_ads_btc.sell_COIN_amt_total',
            'btc_ads_btc.sell_COIN_amt',
            'btc_ads_btc.sell_COIN_amt_finished',
            'btc_ads_btc.sell_coin_price',
            'btc_ads_btc.buy_COIN_amt_total',
            'btc_ads_btc.buy_COIN_amt',
            'btc_ads_btc.buy_COIN_amt_finished',
            'btc_ads_btc.buy_coin_price',
            DB::raw("
                CASE 
                    WHEN btc_ads_btc.status='OnProgress' AND btc_ads_btc.type='sell' AND btc_ads_btc.sell_COIN_amt=0 THEN '판매 완료'
                    WHEN btc_ads_btc.status='OnProgress' AND btc_ads_btc.type='buy' AND btc_ads_btc.buy_COIN_amt=0 THEN '구매 완료'
                ELSE
                    CASE
                        WHEN btc_ads_btc.status='OnProgress' THEN '거래 진행중'
                        WHEN btc_ads_btc.status='Cancel' THEN '취소된 거래'
                    ELSE
                        ''
                    END
                END
            ")
        ];
        $columns_name = [
            '번호', 
            'UID',
            '최초 거래',
            '마지막 체결',
            'E-mail' ,
            'Mobile Number',
            '사용자계정',
            '코인타입',
            '구분',
            '판매요청량',
            '판매 잔량',
            '판매 완료량',
            '판매 가격',
            '구매요청량',
            '구매 잔량',
            '구매 완료량',
            '구매가격',
            '상태'
        ];
        $trade_historys = DB::table('btc_ads_btc')->join('users','btc_ads_btc.userid','=','users.username')->select($columns);

        if(!empty($this->from)) {
            $trade_historys = $trade_historys->whereDate('created_dt', '>=', $this->from." 00:00:00");
        }
        if(!empty($this->to)) {
            $trade_historys = $trade_historys->whereDate('created_dt', '<=', $this->to." 23:59:59");
        }
        
        return $trade_historys->orderBy('btc_ads_btc.id','desc')->get()->prepend($columns_name);
    }
}