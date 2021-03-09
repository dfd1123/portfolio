<?php

namespace App\Classes;

use App\Mail\Notify;

use DB;
use Mail;
use Nexmo_sms;

class Admin {
    public function withdraw_confirm($request_id)
    {
        $result = DB::table('btc_coin_send_request')->where('id',$request_id)->update([
            "status" => 'withdraw_request_confirm',
            "updated" => time(),
            "updated_dt" => DB::raw('now()'),
        ]);
        
        return $result;
    }

    public function cancel_coin_io($request_id,$status){ 
        $coin_io = DB::table('btc_coin_send_request')->where('id',$request_id)->first();

        DB::table('btc_users_addresses')->where('label',$coin_io->sender_userid)
        ->increment('pending_received_balance_'.strtolower($coin_io->cointype), $coin_io->total_amt);
        
        $result = DB::table('btc_coin_send_request')->where('id',$request_id)->update([
            "status" => $status,
            "updated" => time(),
            "updated_dt" => DB::raw('now()'),
        ]);
        
        return $result;
    }

    public function manual_confirm($tx_id, $id) { 
        $manual = DB::table('btc_coin_send_request')->where('id', $id)->first();

        if($manual != null){
            $userinfo = DB::table('btc_users_addresses')->where('label', $manual->sender_userid);
        

            $cointype = strtolower($manual->cointype);
            $account = $manual->sender_userid;
            $address = $userinfo->{'address_'.strtolower($manual->cointype)};
            $cointxid = strtolower($manual->cointype)."_".$tx_id;
            $amount = $manual->total_amt;
            $category = "send";
            $confirmations = 6;
            $txid = $tx_id;
            $normtxid = "";
            $trtime = time();
            $timereceived = time();
            $precessed = "y";
            $created_dt = date('Y-m-d H:i:s');

            DB::table('btc_coin_send_request')->where('id',$id)->update([
                "tx_id" => $tx_id,
                "status" => 'withdraw_complete',

            ]);

            DB::table('btc_transaction')->insert([
                "cointype" => $cointype,
                "account" => $account,
                "address" => $address,
                "cointxid" => $cointxid,
                "category" => $category,
                "amount" => $amount,
                "confirmations" => $confirmations,
                "txid" => $txid,
                "normtxid" => $normtxid,
                "tr_time" => $trtime,
                "timereceived" => $timereceived,
                "processed" => $precessed,
                "created_dt" => $created_dt,
            ]);

            DB::table('btc_users_addresses')->where('label',$account)->decrement('available_balance_'.$cointype, $amount);
            DB::table('btc_users_addresses')->where('label',$account)->increment('pending_received_balance_'.$cointype, $amount);

            return true;

        }

    }
    
    public function alarm_withdraw_confirm($username, $cointype, $receiver_address, $req_amount) {
        // 출금 처리 시 유저에게 알림 보내기

        $send_user = DB::table('users')->where('username', $username)->first();
        $email = $send_user->email;
        $mobile_number = $send_user->mobile_number;
        $country = $send_user->country;
        $alarm_io_email = $send_user->alarm_io_email;
        $alarm_io_sms = $send_user->alarm_io_sms;

        if($alarm_io_email == 1) {
            $data = array(
                'title' => '출금 알림',
                'content' => trans('alarm.alarm_io_email_withdraw', ['cointype' => $cointype, 'address' => $receiver_address, 'amount' => $req_amount], $country)
            );

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email)->send(new Notify($data));
            }
        }

        if($alarm_io_sms == 1) {
            $message = trans('alarm.alarm_io_sms_withdraw', ['cointype' => $cointype, 'address' => $receiver_address, 'amount' => $req_amount], $country);

            Nexmo_sms::send_sms($country, $mobile_number, $message);
        }
    }

}
