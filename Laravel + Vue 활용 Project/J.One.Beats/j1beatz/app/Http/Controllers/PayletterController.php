<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\Cart;
use Facades\App\Classes\Beat;
use Facades\App\Classes\BeatOrder;
use Facades\App\Classes\License;
use Facades\App\Classes\LicenseOrder;
use Facades\App\Classes\Secure;
use App\User;

use Auth;

// 반환된 결제결과
// $strUserID              = $objJsonData->user_id;                    //--가맹점 결제자(회원) 아이디(email, 영문 및 숫자 가능)
// $strUserName            = $objJsonData->user_name;                  //--가맹점 결제자(회원) 이름
// $strOrderNo             = $objJsonData->order_no;                   //--가맹점의 주문 번호
// $strServiceName         = $objJsonData->service_name;               //--결제 서비스명
// $strProductName         = $objJsonData->product_name;               //--결제상품명

// $strCustomParameter     = $objJsonData->custom_parameter;           //--주문요청시 가맹점이 전송한 값
// $strTID                 = $objJsonData->tid;                        //--결제고유번호
// $strCID                 = $objJsonData->cid;                        //--승인번호
// $strAmt                 = $objJsonData->amount;                     //--결제요청 금액
// $strPayInfo             = $objJsonData->pay_info;                   //--결제 부가정보

// $strPGCode              = $objJsonData->pgcode;                     //--결제요청한 pg명
// $strDomesticFlag        = $objJsonData->domestic_flag;              //--국내 / 해외 신용카드 구분 (Y : 국내, N : 해외)
// $strBillKey             = $objJsonData->billkey;                    //--자동결제 재결제용 빌키
// $strTransactionDate     = $objJsonData->transaction_date;           //--거래일시(YYYY-MM-DD HH:MM:SS)
// $strCardInfo            = $objJsonData->card_info;                  //--카드 번호 (중간 4자리 masking 처리)

// $strPayHash             = $objJsonData->payhash;                    //--파라메터 검증을 위한 SHA256 hash 값 SHA256(user_id +amount + tid +결제용 API Key) * 일부 결제 수단은 전달되지 않습니다.(가상계좌 등)
// $strReceiptCID          = $objJsonData->cash_receipt->cid;          //--현금영수증 CID
// $strReceiptCode         = $objJsonData->cash_receipt->code;         //--현금영수증 Code
// $strReceiptDealNo       = $objJsonData->cash_receipt->deal_no;      //--현금영수증 Deal No
// $strReceiptIssueType    = $objJsonData->cash_receipt->issue_type;   //--현금영수증 ISSUE TYPE

// $strReceiptMsg          = $objJsonData->cash_receipt->message;      //--현금영수증 Message
// $strReceiptPayerSID     = $objJsonData->cash_receipt->payer_sid;    //--현금영수증 Payer SID
// $strReceiptType         = $objJsonData->cash_receipt->type;         //--현금영수증 Type

class PayletterController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $p = $request->all();

        if ($request->filled('req')) {
            switch ($p['req']) {

                //라이센스 자동결제
                case 'license':
                if (!Auth::check()) {
                    return abort(403);
                }
                return response()->json($this->requestLicensePayment($p), 201);

                //라이센스 갱신
                /**
                 * 라라벨 스케쥴러에서 처리
                 */

                //비트구매
                case 'beat':
                if (!Auth::check()) {
                    return abort(403);
                }
                return response()->json($this->requestBeatPayment($p), 201);

                //휴대폰인증 요청
                case 'mobile_request':
                return response()->json($this->requestMobileAuth($p), 201);

                //휴대폰인증 확인
                case 'mobile_verify':
                return response()->json($this->verifyMobileAuth($p), 201);
            }
        }

        return response()->json(null, 500);
    }

    public function show($req = 'def')
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function requestBeatPayment($p)
    {
        //비트구매
        info('requestBeatPayment 시작');

        // 요청 파라메터 로그 찍기
        info(json_encode($p));

        //선택된 비트정보를 DB에서 가져온뒤. 상품명, 가격 산출
        $user = auth()->user();
        $pgcode = ["creditcard", "virtualaccount", "mobile"][$p['po_pg_type']];
        $amount = '0';
        $product_name = '';
        $po_ids = [];
        
        // 결제방식에 따라 클라이언트 id 분기처리
        $client_id = null;
        $api_key = null;
        if ($pgcode == "creditcard" || $pgcode == "virtualaccount") {
            $client_id = 'j1beatz';
            $api_key = config('p1q.POQ_KEY');
        } elseif ($pgcode == "mobile") {
            $client_id = 'j1beatz1';
            $api_key = config('p1q.AUTH_KEY');
        } else {
            return abort(500);
        }

        foreach ($p['cart_ids'] as $cart_id) {
            // 장바구니 정보가 있는지 체크
            $cart_info = Cart::show($user->user_id, $cart_id);
            if ($cart_info === null) {
                info('requestBeatPayment 중단. 장바구니 정보 없음: ' . $cart_id);
                return abort(500);
            }

            // 비트 정보가 있는지 체크
            $beat_info = Beat::show($cart_info->beat_id);
            if ($beat_info === null) {
                info('requestBeatPayment 중단. 비트 정보 없음: ' . $cart_info->beat_id);
                return abort(500);
            }

            // 유효한 구매내역이 있는 비트인지 체크
            $order_check = BeatOrder::available($user->user_id, $cart_info->beat_id);
            if ($order_check !== null) {
                info('requestBeatPayment 중단. 유효한 구매내역 있음: ' . $cart_info->beat_id);
                return abort(500);
            }
        }

        foreach ($p['cart_ids'] as $cart_id) {
            // 장바구니 정보
            $cart_info = Cart::show($user->user_id, $cart_id);
            if ($cart_info === null) {
                return abort(500);
            }

            // 비트 정보
            $beat_info = Beat::show($cart_info->beat_id);
            if ($beat_info === null) {
                return abort(500);
            }

            // 장바구니 값이 유효하면 비트 구매대기 처리 후 해당 장바구니 값 삭제
            $po_id = BeatOrder::store($user->user_id, $cart_id, $p['po_pg_type']);
            info('BeatOrder inserted: ' . $po_id);
            Cart::destroy($user->user_id, $cart_id);
            info('Cart destroyed: ' . $cart_id);

            $amount = bcadd($amount, $beat_info->beat_price, 0);

            if (mb_strlen($product_name) < 45) {
                $product_name = $product_name . mb_substr($beat_info->beat_title, 0, 20) . '... ';
            }

            array_push($po_ids, $po_id);
        }

        // 사용자 결제요청 창에 보여줄 상품명
        $product_name = $product_name . '총 ' . count($po_ids) . '곡';

        // 상품명 로그 찍기
        info($product_name);

        // 인증 파라메터
        $custom_parameter = [
            'user_id' => $user->user_id,
            'pg_type' => $p['po_pg_type'],
            'po_ids' => $po_ids
        ];

        // 인증 파라메터 로그 찍기
        info(json_encode($custom_parameter));

        // 결제 요청 보내기
        $fParam = [
            'pgcode' => $pgcode,
            'user_id' => $user->user_id,
            'user_name' => $user->user_name,
            'service_name' =>'J1Beatz',
            'client_id' => $client_id,
            'amount' => $amount,
            'product_name' => $product_name,
            'email_flag' => 'Y',
            'email_addr' => $user->user_email,
            'autopay_flag' => 'N',
            'receipt_flag' => 'Y',
            'custom_parameter' => base64_encode(json_encode($custom_parameter)),
            'return_url' => 'https://j1beatz.com/perchaced-popup?value='. $amount,
            'callback_url' => 'https://j1beatz.com/payletter/beatCB'
        ];
        
        // 모바일에서 요청시 파라메터 변경
        if ($this->isMobile()) {
            $this->addParam4Mobile($fParam);
            info('모바일에서 요청됨');
        }
        
        //결제요청전 로그찍기
        $fParam = json_encode($fParam);
        info('결제요청전: ' . $fParam);

        //결제요청후 로그찍기
        $result = $this->execCURL($fParam, 'request', $api_key);
        info('결제요청후: ' . $result);

        info('requestBeatPayment 완료');
        
        return $result;
    }

    public function beatOrderCallback()
    {
        //비트구매 Callback
        info("beatOrderCallback 시작");

        $this->checkClient();
        info("IP 체크 통과");
        
        //-- Callback URL로 반환된 결제결과 (JSON 파싱)
        $pg_info = file_get_contents('php://input');
        $p = json_decode($pg_info, true);
        
        // 결제결과 로그 찍기
        info(json_encode($p));
        
        //Payletter에 보내는 응답
        $res = [];
        if (empty($p)) {
            info('paymentCallback Param is empty ');
            $res = ['code' => 0, 'msg' => 'FAILED : NO objJsonData'];
            return response()->json($res);
        }

        if (!isset($p['user_id'], $p['amount'] ,$p['tid'] , $p['payhash'])) {
            $res = ['state' => 0, 'msg' => '변수없음'];
            return response()->json($res);
        }
        
        // payhash 정보
        $userid = $p['user_id'];
        $amount = $p['amount'];
        $tid = $p['tid'];
        $pgcode = $p['pgcode'];
        $pkey = null;
        
        if ($pgcode == "creditcard" || $pgcode == "virtualaccount") {
            $pkey = config('p1q.POQ_KEY');
        } elseif ($pgcode == "mobile") {
            $pkey = config('p1q.AUTH_KEY');
        } else {
            $res = ['state' => 0, 'msg' => '미지원결제수단사용'];
            return response()->json($res);
        }

        // payhash 데이터 로그 찍기
        info($userid . ' ' . $amount . ' ' . $tid . ' ' . $pkey);

        $payhash = strtoupper(hash("sha256", $userid.$amount.$tid.$pkey, false));

        // key값 비교
        if ($p['payhash'] === $payhash) {
            info("Payhash 일치");

            // 구매요청 불러오기
            $data = json_decode(base64_decode($p['custom_parameter']));

            info("비트 구매처리 시작");

            // 비트 구매처리
            foreach ($data->po_ids as $po_id) {
                BeatOrder::activate($data->user_id, $po_id, $pg_info);
                info('BeatOrder activated: ' . $po_id);
            }

            info("비트 구매처리 종료");

            $res = ['code' => 0, 'msg' => '성공'];
        } else {
            info("Payhash 불일치");
            info("생성된 Payhash: " . $payhash);
            info("전송된 Payhash: " . $p['payhash']);
            
            $res = ['code' => 1, 'msg' => 'HASH키값불일치'];
        }

        info(json_encode($res));
        info("beatOrderCallback 완료");

        return response()->json($res);
    }

    public function requestLicensePayment($p)
    {
        //라이센스구매
        info('requestLicensePayment 시작');

        // 요청 파라메터 로그 찍기
        info(json_encode($p));

        //선택된 라이센스 정보를 DB에서 가져온뒤. 상품명, 가격 산출
        $user = auth()->user();
        $pgcode = ["creditcard", "virtualaccount", "mobile"][$p['lo_pg_type']];
        $lcens_id = $p['lcens_id'];
        $amount = '0';
        $product_name = '';

        // 결제방식에 따라 클라이언트 id 분기처리
        $client_id = null;
        $api_key = null;
        if ($pgcode == "creditcard") {
            $client_id = 'j1beatz';
            $api_key = config('p1q.POQ_KEY_AUTO_PAY');
        } else {
            return abort(500);
        }

        // 유효한 라이센스가 이미 있는지 체크
        $license = LicenseOrder::available($user->user_id);
        if ($license !== null && $license->lcens_id == $lcens_id) {
            return abort(500);
        }

        // 라이센스 정보 가져오기
        $license_info = License::show($lcens_id);
        if ($license_info === null) {
            return abort(500);
        }

        // 구매대기 상태로 이용권 등록
        $autopay = 1; // 0없음 1자동결제 2자동결제해지
        LicenseOrder::store($user->user_id, $lcens_id, $p['lo_pg_type'], $autopay);

        // 사용자 결제요청 창에 보여줄 상품명
        $product_name = $license_info->lcens_name . ' ' . mb_substr($license_info->lcens_desc, 0, 20) . '...';

        $amount = bcadd($amount, $license_info->lcens_price, 0);

        // 상품명 로그 찍기
        info($product_name);

        // 인증 파라메터
        $custom_parameter = [
            'user_id' => $user->user_id,
            'pg_type' => $p['lo_pg_type'],
            'lcens_id' => $lcens_id
        ];

        // 인증 파라메터 로그 찍기
        info(json_encode($custom_parameter));

        // 결제 요청 보내기
        $fParam = [
            'pgcode' => $pgcode,
            'user_id' => $user->user_id,
            'user_name' => $user->user_name,
            'service_name' =>'J1Beatz',
            'client_id' => 'j1beatz2',
            'amount' => $amount,
            'product_name' => $product_name,
            'email_flag' => 'Y',
            'email_addr' => $user->user_email,
            'autopay_flag' => $autopay === 1 ? 'Y' : 'N',
            'receipt_flag' => 'Y',
            'custom_parameter' => base64_encode(json_encode($custom_parameter)),
            'return_url' => 'https://j1beatz.com/perchaced-popup?value='. $amount,
            'callback_url' => 'https://j1beatz.com/payletter/licenseCB'
        ];
        
        // 모바일에서 요청시 파라메터 변경
        if ($this->isMobile()) {
            $this->addParam4Mobile($fParam);
            info('모바일에서 요청됨');
        }
        
        //결제요청전 로그찍기
        $fParam = json_encode($fParam);
        info('결제요청전: ' . $fParam);

        //결제요청후 로그찍기
        $result = $this->execAutoCURL($fParam, 'request', $api_key);
        info('결제요청후: ' . $result);

        info('requestLicensePayment 완료');
        
        return $result;
    }

    public function licenseOrderCallback()
    {
        //라이센스구매 Callback
        info("licenseOrderCallback 시작");

        $this->checkClient();
        info("IP 체크 통과");
        
        //-- Callback URL로 반환된 결제결과 (JSON 파싱)
        $pg_info = file_get_contents('php://input');
        $p = json_decode($pg_info, true);
        
        // 결제결과 로그 찍기
        info(json_encode($p));
        
        //Payletter에 보내는 응답
        $res = [];
        if (empty($p)) {
            info('paymentCallback Param is empty ');
            $res = ['code' => 0, 'msg' => 'FAILED : NO objJsonData'];
            return response()->json($res);
        }

        if (!isset($p['user_id'], $p['amount'] ,$p['tid'] , $p['payhash'])) {
            $res = ['state' => 0, 'msg' => '변수없음'];
            return response()->json($res);
        }
        
        // payhash 정보
        $userid = $p['user_id'];
        $amount = $p['amount'];
        $tid = $p['tid'];
        $pgcode = $p['pgcode'];
        $pkey = null;

        if ($pgcode == "creditcard") {
            $pkey = config('p1q.POQ_KEY_AUTO_PAY');
        } else {
            $res = ['state' => 0, 'msg' => '미지원결제수단사용'];
            return response()->json($res);
        }

        // payhash 데이터 로그 찍기
        info($userid . ' ' . $amount . ' ' . $tid . ' ' . $pkey);

        $payhash = strtoupper(hash("sha256", $userid.$amount.$tid.$pkey, false));

        // key값 비교
        if ($p['payhash'] === $payhash) {
            info("Payhash 일치");

            // 구매요청 불러오기
            $data = json_decode(base64_decode($p['custom_parameter']));

            info("라이센스 구매처리 시작");

            // 구매대기 상태인 이용권 찾기
            $lo_info = LicenseOrder::find($data->user_id, $data->lcens_id, 1);
            if ($lo_info === null) {
                info('License order invalid');
                $res = ['code' => 1, 'msg' => '라이센스구매정보없음'];
            } else {
                // 구매완료 시 이용권 유효기간 갱신 후 구매완료 처리
                LicenseOrder::activate($data->user_id, $lo_info->lo_id, $pg_info);
                info('License activated: ' . $lo_info->lo_id);
                $res = ['code' => 0, 'msg' => '성공'];
            }

            info("라이센스 구매처리 종료");
        } else {
            info("Payhash 불일치");
            info("생성된 Payhash: " . $payhash);
            info("전송된 Payhash: " . $p['payhash']);
            
            $res = ['code' => 1, 'msg' => 'HASH키값불일치'];
        }

        info(json_encode($res));
        info("licenseOrderCallback 완료");

        return response()->json($res);
    }

    public function requestMobileAuth($p)
    {
        //모바일인증
        info('requestMobileAuth 시작');

        //요청 파라메터 로그 찍기
        info(json_encode($p));

        $verify_code = Secure::mobile_auth_temp();

        //인증 파라메터
        $custom_parameter = [
            'verify_code' => $verify_code
        ];
        
        //인증 파라메터 로그 찍기
        info(json_encode($custom_parameter));

        $fParam = [
            'client_id' => 'j1beatz1',
            'user_id' => base64_encode($verify_code),
            'custom_parameter' => base64_encode(json_encode($custom_parameter)),
            'return_url' => 'https://j1beatz.com/mobile/auth'
        ];
        
        //모바일에서 요청시 파라메터 변경
        if ($this->isMobile()) {
            $this->addParam4Mobile($fParam);
            info('모바일에서 요청됨');
        }
        
        //인증요청전 로그찍기
        $fParam = json_encode($fParam);
        info('인증요청전: ' . $fParam);

        //인증요청후 로그찍기
        $result = $this->execAuthCURL($fParam);
        info('인증요청후: ' . $result);

        info('requestMobileAuth 완료');
        
        $data = json_decode($result);
        $data->verify_code = $verify_code;

        return json_encode($data);
    }

    public function verifyMobileAuth($p)
    {
        info('verifyMobileAuth 시작');

        $verify_info = Secure::mobile_auth_verify($p['verify_code']);
        if ($verify_info == null) {
            return abort(500);
        }
        $verify_info = json_decode($verify_info);

        $user = User::where('user_mobile', $verify_info->mobile_no)->first();
        $res = [
            'real_name' => $verify_info->real_name,
            'mobile_no' => $verify_info->mobile_no
        ];
        
        if ($user != null) {
            $res['user_email'] = $user->user_email;
        }

        info('verifyMobileAuth 완료');

        return json_encode($res);
    }

    private function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    private function addParam4Mobile(&$fparam)
    {
        $fparam['inapp_flag']= 'Y';
        $fparam['app_return_url']= 'j1beatz://ISPSuccess/';
        $fparam['app_cancel_url']= 'j1beatz://ISPCancel/';
    }

    private function execCURL($fParam, $URL='request', $api_key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pgapi.payletter.com/v1.0/payments/'.$URL);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization: PLKEY '.$api_key
            , 'Content-Type:application/json')
        );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fParam);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    private function execAutoCURL($fParam, $URL='request', $api_key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pgapi.payletter.com/v1.0/payments/'.$URL);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization: PLKEY '.$api_key
            , 'Content-Type:application/json')
        );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fParam);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    private function execAuthCURL($fParam, $auth_method='mobile')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pgapi.payletter.com/v1.0/auth/$auth_method/request");
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization: PLKEY '.config('p1q.AUTH_KEY')
            , 'Content-Type:application/json')
        );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fParam);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
    
    //페이레터에서만 접근가능하게.
    private function checkClient()
    {
        /*
        $allowlist = array(
        "172.26.4.26",  //테스트1
        "121.254.205.166",  //테스트
        "211.115.72.37", "211.115.72.38", "211.115.117.11" //라이브
        );

        if (!in_array($_SERVER['REMOTE_ADDR'], $allowlist)) {
            info('CheckClient : 허가되지 않은 IP');
            info($_SERVER['REMOTE_ADDR']);
            die('This controller cannot be accessed from your location. ::'. $_SERVER['REMOTE_ADDR']);
        }
        */
    }
}
