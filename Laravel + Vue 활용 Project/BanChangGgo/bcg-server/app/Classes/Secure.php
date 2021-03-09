<?php

namespace App\Classes;

use Illuminate\Support\Facades\Mail;
use App\User;
use App\Mail\EmailVerify;
use DB;

class Secure
{
    public function email_verify_code($usr_email)
    {
        $duplicate = DB::table('users')->where('usr_email', $usr_email)->count();

        if ($duplicate > 0) {
            return false;
        } else {
            $rand = $this->generateRandomNumberString(6);

            $res = DB::insert("
            WITH upsert AS
            (
                UPDATE
                    email_verify_temp
                SET
                    verify_code = :verify_code,
                    updated_at = now()
                WHERE 1 = 1
                    AND usr_email = :usr_email
                RETURNING usr_email
            )
            INSERT INTO email_verify_temp (
                usr_email,
                verify_code
            )
            SELECT
                :usr_email,
                :verify_code
            WHERE NOT EXISTS (
                SELECT
                    usr_email
                FROM upsert
            )
            ", [
                'usr_email' => $usr_email,
                'verify_code' => $rand
            ]);

            $data = array(
                'title' => '[반창꼬] 반창꼬 회원가입 인증 메일입니다.',
                'content' => [
                    'message_title' => '회원가입 이메일 인증',
                    'email' => $usr_email,
                    'message' => '반창꼬 계정인증 메일입니다.<br>
                    위의 번호를 복사해서 홈페이지의<br> 입력란에 넣고 다음 버튼을 눌러주세요.<br>
                    문제가 있으시면 고객센터로 연락주시기 바랍니다.',
                    'verify_code' => $rand
                ]
            );

            Mail::to($usr_email)->send(new EmailVerify($data));
            return true;
        }
    }

    public function email_certify_code($usr_email, $verify_code)
    {
        $verify = DB::table('email_verify_temp')->where('usr_email', $usr_email)->first();

        if ($verify != null) {
            $verify_check = $verify->verify_code == $verify_code;
            if (!$verify_check) {
                return 'certify_fail';
            }
            
            return 'certify_ok';
        } else {
            return 'certify_error';
        }
    }

    public function password_find_verify_code($usr_email)
    {
        $user = DB::table('users')->where('usr_email', $usr_email)->first();

        if ($user === null) {
            return false;
        }

        $rand = $this->generateRandomNumberString(6);

        $res = DB::insert("
            WITH upsert AS
            (
                UPDATE
                    email_verify_temp
                SET
                    verify_code = :verify_code,
                    updated_at = now()
                WHERE 1 = 1
                    AND usr_email = :usr_email
                RETURNING usr_email
            )
            INSERT INTO email_verify_temp (
                usr_email,
                verify_code
            )
            SELECT
                :usr_email,
                :verify_code
            WHERE NOT EXISTS (
                SELECT
                    usr_email
                FROM upsert
            )
            ", [
                'usr_email' => $usr_email,
                'verify_code' => $rand
            ]);

        $data = array(
            'title' => '[반창꼬] 반창꼬 비밀번호 찾기 인증 메일입니다.',
            'content' => [
                'message_title' => '비밀번호 찾기',
                'email' => $usr_email,
                'message' => '반창꼬 비밀번호 찾기 이메일 인증 안내 메일입니다.<br>
                위의 번호를 복사해서 홈페이지의<br> 입력란에 넣고 다음 버튼을 눌러주세요.<br>
                문제가 있으시면 고객센터로 연락주시기 바랍니다.',
                'verify_code' => $rand
            ]
        );

        Mail::to($usr_email)->send(new EmailVerify($data));
        return true;
    }

    public function password_find_certify_code($usr_email, $verify_code)
    {
        $verify = DB::table('email_verify_temp')->where('usr_email', $usr_email)->where('verify_code', $verify_code)->first();

        if ($verify !== null) {
            if (date('Y-m-d H:i:s', strtotime('-1 days')) > date('Y-m-d H:i:s', strtotime($verify->updated_at))) {
                return null;
            }

            DB::table('email_verify_temp')->where('usr_email', $verify->usr_email)->where('verify_code', $verify_code)->delete();
            return User::where('usr_email', $verify->usr_email)->first();
        }

        return null;
    }

    private function generateRandomNumberString($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
