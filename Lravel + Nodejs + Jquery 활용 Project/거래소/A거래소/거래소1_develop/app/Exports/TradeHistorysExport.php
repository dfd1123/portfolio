<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class TradeHistorysExport implements FromCollection, ShouldAutoSize
{
    public function __construct($from, $to, $srch) {
        $this->from = $from;
        $this->to = $to;
        $this->srch = $srch;
    }

    public function collection()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','2048M');

        $columns = [
            'btc_trades_COIN_btc.id',
            'btc_trades_COIN_btc.created_dt',
            'buyer.fullname AS buyer_fullname',
            'seller.fullname AS seller_fullname',
            'btc_trades_COIN_btc.cointype',
            'btc_trades_COIN_btc.contract_coin_amt',
            'btc_trades_COIN_btc.buy_coin_price',
            'btc_trades_COIN_btc.trade_total_buy',
        ];
        $columns_name = [
            '번호', 
            '체결시간',
            '구매자이름',
            '판매자이름',
            '코인타입' ,
            '체결양',
            '거래된 시세',
            '거래된 가격',
        ];

        $srch = $this->srch != null ? $this->srch : '';
        
        $trade_historys = DB::table('btc_trades_COIN_btc')
        ->join('users AS buyer','btc_trades_COIN_btc.buyer_uid','=','buyer.id')
        ->join('users AS seller','btc_trades_COIN_btc.seller_uid','=','seller.id')
        ->select($columns)
        ->where(function($qry) use ($srch){
            $qry->where('buyer.email','like','%'.$srch.'%')
            ->orWhere(DB::raw("REPLACE(buyer.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
            ->orWhere('buyer.mobile_number','like','%'.$srch.'%')
            ->orWhere('buyer.id',$srch)
            ->orwhereRaw("LOWER(btc_trades_COIN_btc.cointype) like LOWER('%$srch%')")
            ->orwhere('seller.email','like','%'.$srch.'%')
            ->orWhere(DB::raw("REPLACE(seller.fullname, ' ', '')"), 'like', preg_replace('/\s+/', '', '%'.$srch.'%'))
            ->orWhere('seller.mobile_number','like','%'.$srch.'%')
            ->orWhere('seller.id',$srch)
            ->orwhereRaw("LOWER(btc_trades_COIN_btc.cointype) like LOWER('%$srch%')");
        })
        ->orderBy('btc_trades_COIN_btc.id','desc');
        

        //$trade_historys = DB::table('btc_ads_btc')->join('users','btc_ads_btc.userid','=','users.username')->select($columns);

        if(!empty($this->from)) {
            $trade_historys = $trade_historys->whereDate('btc_trades_COIN_btc.created_dt', '>=', $this->from." 00:00:00");
        }
        if(!empty($this->to)) {
            $trade_historys = $trade_historys->whereDate('btc_trades_COIN_btc.created_dt', '<=', $this->to." 23:59:59");
        }
        
        return $trade_historys->orderBy('btc_trades_COIN_btc.id','desc')->get()->prepend($columns_name);
    }
}