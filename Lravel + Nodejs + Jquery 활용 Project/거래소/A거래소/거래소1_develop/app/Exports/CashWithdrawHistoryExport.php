<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class CashWithdrawHistoryExport implements FromCollection, ShouldAutoSize
{
    public function __construct($srch) {
        $this->srch = $srch;
    }

    public function collection()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','2048M');

        $columns = [
            DB::raw('FROM_UNIXTIME(btc_krw_io.created) AS date'),
            DB::raw('users.fullname as name'),
            'users.email',
            'users.mobile_number',
            'btc_krw_io.type',
            'btc_krw_io.status',
            'btc_krw_io.plus',
            'btc_krw_io.minus',
            'btc_krw_io.memo',
        ];

        $columns_name = [
            '날짜', 
            '이름',
            '이메일',
            '전화번호',
            '구분' ,
            '상태',
            '금액',
            '수수료',
            '계좌정보',
        ];

        $srch = $this->srch != null ? $this->srch : '';

        $krw_ios = DB::table('btc_krw_io')
            ->join('users', 'users.id', '=', 'btc_krw_io.uid')
            ->select($columns)
            ->orwhere('users.id', $srch)
            ->orwhere('users.fullname', 'like', '%'.$srch.'%')
            ->orwhere('users.email', 'like', '%'.$srch.'%')
            ->orwhere('users.mobile_number', 'like', '%'.$srch.'%');
        
       

        return $krw_ios->orderBy('btc_krw_io.id','desc')->get()->prepend($columns_name);
    }
}