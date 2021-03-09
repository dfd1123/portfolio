<?php

namespace App\Classes;

use App\User;
use App\Mail\EmailVerify;

use DB;
use Mail;

class Secure
{
    public function email_verify_link($user_email)
    {
        $user = DB::table('users')->where('user_email', $user_email)->first();

        if ($user === null) {
            return false;
        }

        if ($user->email_verified_at === null) {
            $verify_code = $this->generateRandomString(128);

            $res = DB::insert("
            WITH upsert AS
            (
                UPDATE
                    email_verify_temp
                SET
                    verify_code = :verify_code,
                    updated_at = now()
                WHERE 1 = 1
                    AND user_email = :user_email
                RETURNING user_email
            )
            INSERT INTO email_verify_temp (
                user_email,
                verify_code
            )
            SELECT
                :user_email,
                :verify_code
            WHERE NOT EXISTS (
                SELECT
                    user_email
                FROM upsert
            )
            ", [
                'user_email' => $user_email,
                'verify_code' => $verify_code
            ]);

            $url = config('app.url') . "/email/verify";
            $data = array(
                'title' => '[J1BEATZ] 제이원비츠 회원가입 인증 메일입니다.',
                'content' => [
                    'message_title' => '회원가입 이메일 인증',
                    'email' => $user_email,
                    'name' => $user->user_name,
                    'message' => '제이원비츠 회원가입 이메일 인증 안내 메일입니다.<br>
                    이메일 인증이 완료되어야 제이원비츠 서비스 이용이 가능합니다.<br>
                    아래버튼을 클릭하셔서 이메일 인증을 완료해주세요.<br>',
                    'verify_link' => $url . '?verify=' . base64_encode($verify_code)
                ]
            );

            Mail::to($user_email)->send(new EmailVerify($data));
            return true;
        } else {
            return false;
        }
    }

    public function email_certify_link($verify_code)
    {
        $verify_code = base64_decode($verify_code);
        $verify = DB::table('email_verify_temp')->where('verify_code', $verify_code)->first();

        if ($verify !== null) {
            if (date('Y-m-d H:i:s', strtotime('-7 days')) > date('Y-m-d H:i:s', strtotime($verify->updated_at))) {
                return false;
            }

            $user = DB::table('users')->where('user_email', $verify->user_email)->first();
            if ($user->email_verified_at !== null) {
                return false;
            }

            DB::table('users')->where('user_email', $verify->user_email)->update(['email_verified_at' => DB::raw('now()')]);
            DB::table('email_verify_temp')->where('user_email', $verify->user_email)->where('verify_code', $verify_code)->delete();

            return true;
        }

        return false;
    }

    public function password_find_link($user_email)
    {
        $user = DB::table('users')->where('user_email', $user_email)->first();

        if ($user === null) {
            return false;
        }

        $verify_code = $this->generateRandomString(128);

        $res = DB::insert("
            WITH upsert AS
            (
                UPDATE
                    email_verify_temp
                SET
                    verify_code = :verify_code,
                    updated_at = now()
                WHERE 1 = 1
                    AND user_email = :user_email
                RETURNING user_email
            )
            INSERT INTO email_verify_temp (
                user_email,
                verify_code
            )
            SELECT
                :user_email,
                :verify_code
            WHERE NOT EXISTS (
                SELECT
                    user_email
                FROM upsert
            )
            ", [
                'user_email' => $user_email,
                'verify_code' => $verify_code
            ]);

        $url = config('app.url') . "/change-pw";
        $data = array(
                'title' => '[J1BEATZ] 제이원비츠 비밀번호 찾기 인증 메일입니다.',
                'content' => [
                    'message_title' => '비밀번호 찾기',
                    'email' => $user_email,
                    'name' => $user->user_nick,
                    'message' => '제이원비츠 비밀번호 찾기 이메일 인증 안내 메일입니다.<br>
                    이메일 인증이 완료되어야 비밀번호 찾기가 가능합니다.<br>
                    아래버튼을 클릭하셔서 이메일 인증을 완료해주세요.<br>',
                    'verify_link' => $url . '?verify=' . base64_encode($verify_code)
                ]
            );

        Mail::to($user_email)->send(new EmailVerify($data));
        return true;
    }

    public function password_find_verify($verify_code)
    {
        $verify_code = base64_decode($verify_code);
        $verify = DB::table('email_verify_temp')->where('verify_code', $verify_code)->first();

        if ($verify !== null) {
            if (date('Y-m-d H:i:s', strtotime('-1 days')) > date('Y-m-d H:i:s', strtotime($verify->updated_at))) {
                return null;
            }

            DB::table('email_verify_temp')->where('user_email', $verify->user_email)->where('verify_code', $verify_code)->delete();
            return User::where('user_email', $verify->user_email)->first();
        }

        return null;
    }

    public function mobile_auth_temp()
    {
        $verify_code = $this->generateRandomString(128);

        $res = DB::insert("
        WITH upsert AS
        (
            UPDATE
                mobile_verify_temp
            SET
                updated_at = now()
            WHERE 1 = 1
                AND verify_code = :verify_code
            RETURNING verify_code
        )
        INSERT INTO mobile_verify_temp (
            verify_code
        )
        SELECT
            :verify_code
        WHERE NOT EXISTS (
            SELECT
                verify_code
            FROM upsert
        )
        ", [
            'verify_code' => $verify_code
        ]);
        
        return $verify_code;
    }

    public function mobile_auth_verify($verify_code)
    {
        $verify = DB::table('mobile_verify_temp')->where('verify_code', $verify_code)->first();

        if ($verify !== null) {
            if (date('Y-m-d H:i:s', strtotime('-7 days')) > date('Y-m-d H:i:s', strtotime($verify->updated_at))) {
                return null;
            }

            return $verify->verify_info;
        }

        return null;
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
