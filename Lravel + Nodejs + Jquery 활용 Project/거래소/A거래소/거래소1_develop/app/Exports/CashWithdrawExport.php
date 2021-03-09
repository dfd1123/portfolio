<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class CashWithdrawExport implements FromCollection, ShouldAutoSize
{
    public function __construct() {
  
    }

    public function collection()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','2048M');

        $columns = [
            'bankname',
            DB::raw("CONCAT(bankaccount,' ') as bankaccount"),
            'plus',
            'bankowner',
            DB::raw("'스포홀딩스' as withdraw"),
        ];

        $krw_request = DB::table('btc_krw_io')
            ->select($columns)
            ->where('status', 'withdraw_request');
        
       

        return $krw_request->orderBy('id','asc')->get();
    }
}