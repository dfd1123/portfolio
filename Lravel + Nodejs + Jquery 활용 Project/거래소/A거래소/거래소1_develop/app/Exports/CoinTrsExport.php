<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class CoinTrsExport implements FromCollection, ShouldAutoSize
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
            'btc_transaction.id',
            'btc_transaction.created_dt',
            'users.id as uid',
            'users.fullname',
            'users.email',
            'users.mobile_number',
            'btc_transaction.cointype',
            'btc_transaction.category',
            'btc_transaction.amount',
            'btc_transaction.address',
            'btc_transaction.confirmations',
            'btc_transaction.txid'
        ];
        $columns_name = [
            '아이디',
            '입금일시',
            'UID',
            '사용자계정',
            'E-mail' ,
            'Mobile Number',
            '코인',
            '구분',
            '수량',
            '주소',
            '컨펌수',
            'TX ID'
        ];

        $transactions = DB::table('btc_transaction')
            ->join('users','btc_transaction.account','=','users.username')
            ->select($columns)
            ->where('btc_transaction.category', '<>', 'trade');
        
        if(!empty($this->from)) {
            $transactions = $transactions->whereDate('created_dt', '>=', $this->from." 00:00:00");
        }
        if(!empty($this->to)) {
            $transactions = $transactions->whereDate('created_dt', '<=', $this->to." 23:59:59");
        }

        return $transactions->orderBy('btc_transaction.id','desc')->get()->prepend($columns_name);
    }
}