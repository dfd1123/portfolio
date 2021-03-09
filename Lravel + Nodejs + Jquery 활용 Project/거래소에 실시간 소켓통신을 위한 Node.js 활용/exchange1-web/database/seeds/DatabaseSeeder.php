<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $origin_users = DB::table('btc_users')->get();

        $data = array();
        $data2 = array();

        foreach($origin_users as $origin_user){ //btc_users -> users 테이블로 데이터 이동

            if(substr($origin_user->mobile_number,0,3) == '+82' || substr($origin_user->mobile_number,0,3) == '+81'){
                $mobile_number = substr($origin_user->mobile_number, 3);
            }

            array_push($data, array(
                'id' => $origin_user->id,
                'username' => $origin_user->username,
                'fullname' => $origin_user->fullname,
                'password' => $origin_user->real_password,
                'secret_pin' => $origin_user->secret_pin,
                'level' => $origin_user->level,
                'email' => $origin_user->email,
                'email_verified_at' => NULL,
                'country' => $origin_user->country,
                'mobile_number' => $mobile_number,
                'status' => $origin_user->status,
                'hash' => $origin_user->hash,
                'wallet' => $origin_user->wallet,
                'ip' => $origin_user->ip,
                'time_signup' => $origin_user->time_signup,
                'time_signin' => $origin_user->time_signin,
                'time_activity' => $origin_user->time_activity,
                'referral_id' => $origin_user->referral_id,
                'market_type' => 1,
                'alarm_email' => 1,
                'alarm_sms' => 1,
                'alarm_io_email' => 1,
                'alarm_io_sms' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')

            ));

            array_push($data2, array(
                'uid' => $origin_user->id,
                'email_verified' => $origin_user->email_verified,
                'mobile_verfied' => $origin_user->mobile_verfied,
                'document_1' => $origin_user->document_1,
                'document_2' => $origin_user->document_2,
                'document_verified' => $origin_user->document_verified,
                'document_reject' => $origin_user->document_text,
                'account_1' => $origin_user->account_1,
                'account_2' => $origin_user->account_2,
                'account_verified' => $origin_user->account_verified,
                'account_num' => $origin_user->account_number,
                'account_bank' => $origin_user->account_bank,
                'account_reject' => $origin_user->account_text,
                'google_verified' => $origin_user->google_verified,
                'google_pin' => $origin_user->google_pin
            ));
        }
        DB::table('users')->insert($data);
        DB::table('btc_security_lv')->insert($data2);
    }
}
