<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;

class MypageController extends Controller
{
    public function security_account_certify(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");

        $account_num = $request->account_num;
        $account_bank = $request->account_bank;
        $account_code = $request->account_code;

        if ($account_num != '' && $account_num != null) {
            $account_certify_code = sprintf('%03d', rand(000, 999));

            $status = DB::table('btc_security_lv')->where('uid', Auth::id())->update([
                "account_certify_code" => $account_certify_code,
                "account_bank" => $account_bank,
                "account_num" => $account_num,
            ]);

            if (!$status) {
                $response = array(
                    "status" => false,
                    "msg" => "",
                );
            } else {
                //$key = "SETTLEBANKISGOOD";
                //$verify_key = "ST1909191739541877346";
                //$url = "https://testpay.settlebank.co.kr/api/ApiMultiAction.do";
                //$store_id = "spowid1t";
                
                // .env 파일에서 환경변수 가져오기
                $key = env('SETTLEBANK_ENCREPT_KEY');
                $verify_key = env('SETTLEBANK_VERIFY_KEY');
                $url = "https://pay.settlebank.co.kr/api/ApiMultiAction.do";
                $store_id = "spowid1r";

                $payGb = "AA";
                $PMid = $store_id;
                $PTrDt = date('Ymd');
                $PTrTime = date('His');
                $PAcctNo = str_replace("-", "", $account_num);
                $PBnkCd = $account_code;
                $PAmt = '1';
                $PBname = '스포' . $account_certify_code;
                $PHash = hash("sha256", $payGb . $PMid . $PTrDt . $PTrTime . $PBnkCd . $PAcctNo . $PAmt . $verify_key);

                $postdata = [
                    '_method' => 'authAcctOwnerShip',
                    'payGb' => $payGb,
                    'PMid' => $PMid,
                    'PTrDt' => $PTrDt,
                    'PTrTime' => $PTrTime,
                    'PAcctNo' => $this->encrypt($PAcctNo, $key),
                    'PBnkCd' => $PBnkCd,
                    'PAmt' => $PAmt,
                    'PBname' => $this->encrypt(urlencode($PBname), $key),
                    'PHash' => $PHash
                ];

                $postvars = [];
                foreach ($postdata as $key => $value) {
                    array_push($postvars, $key . "=" . $value);
                }
                $postvars = implode('&', $postvars);
        
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=UTF-8"));
                $output = curl_exec($ch);
                curl_close($ch);

                info('유저 id: ' . Auth::id() . ' 계좌인증 응답: ' . $output);
                
                $info = json_decode($output);
                if ($info->PStatus == "0021") {
                    $response = array(
                        "status" => $status,
                        "msg" => "",
                    );
                } else {
                    $response = array(
                        "status" => false,
                        "msg" => urldecode($info->PRmesg1)
                    );
                }
            }
        } else {
            $response = array(
                "status" => false,
                "msg" => "계좌번호를 입력하세요.",
            );
        }

        return response()->json($response);
    }

    public function security_account_confirm(Request $request)
    {
        date_default_timezone_set("Asia/Seoul");

        $account_bank = $request->account_bank;
        $account_num = $request->account_num;
        $account_certify_code = $request->account_certify_code;

        $origin_account_certify_code = DB::table('btc_security_lv')->where('uid', Auth::id())->value('account_certify_code');

        if ($account_certify_code == $origin_account_certify_code) {
            DB::table('btc_security_lv')->where('uid', Auth::id())->update([
                "account_verified" => 1,
            ]);

            $response = array(
                "status" => true,
                "icon" => "success",
                "msg" => "성공적으로 인증 되었습니다.",
                "account_bank" => $account_bank,
                "account_num" => $account_num,
            );
        } else {
            $response = array(
                "status" => false,
                "icon" => "warning",
                "msg" => "인증번호가 틀렸습니다."
            );
        }

        return response()->json($response);
    }

    private function encrypt($text, $key)
    {
        return strtoupper(bin2hex(openssl_encrypt($text, 'aes-128-ecb', $key, OPENSSL_RAW_DATA)));
    }

    private function decrypt($text, $key)
    {
        return openssl_decrypt(hex2bin($text), 'aes-128-ecb', $key, OPENSSL_RAW_DATA);
    }
}
