<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

use Auth;
use Secure;
use DB;
use App;

class MoneySignalController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function new_history(Request $request)
    {
        if (!isset($request['msdata'])) {
            return abort(403);
        }
        $msdata = mb_convert_encoding($request['msdata'], "UTF-8", "CP949");
        $list = explode(';', $msdata);

        for ($i = 1; $i < count($list) - 1; $i++) {
            $data = explode(",", $list[$i]);
            $info = [
                'aldis_key' => preg_replace('/\s+/', '', data_get($data, 0, null)),
                'bank_code' => data_get($data, 1, null),
                'bankaccount' => str_replace('-', '', data_get($data, 2, null)),
                'created_dt' => data_get($data, 3, null),
                'dt_order' => data_get($data, 4, null),
                'kind_name' => data_get($data, 5, null),
                'user_name' => preg_replace('/\s+/', '', data_get($data, 6, null)),
                'in_amount' => data_get($data, 7, null),
                'out_amount' => data_get($data, 8, null),
                'balance' => data_get($data, 9, null),
                'bank_name' => data_get($data, 10, null),
                'time' => data_get($data, 11, null)
            ];

            info($info);

            // 입출금 내역 조회
            $krw_io = DB::table('btc_krw_io')
                ->where('type', 'deposite')
                ->where('status', 'deposite_request')
                ->where('amount', $info['in_amount'])
                ->where('bankowner', mb_substr($info['user_name'], 0, -3))
                ->where('verification_code', mb_substr($info['user_name'], -3))
                ->first();
                
            if ($krw_io !== null) {
                // 요청 승인
                $status = DB::table('btc_krw_io')
                    ->where('id', $krw_io->id)
                    ->where('status', 'deposite_request')
                    ->update([
                        'status' => 'confirm'
                    ]);
                    
                        
                // 잔액반영
                if ($status > 0) {
                    DB::table('btc_users_addresses')
                        ->where('uid', $krw_io->uid)
                        ->increment('available_balance_krw', $krw_io->amount);
                }
            }
        }
    }
}
