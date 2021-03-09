<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
    public function __construct(Request $request)
    {
        parent::__construct($request);

        
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }
        $p = $request->all();

        if ($request->filled('rsrv_id', 'pg_type')) {
            info('requestPayment 시작');
            
            //선택된 정보를 DB에서 가져온뒤. 상품명, 가격 산출
            $user_id = $this->decode_res['uid'];
            $pgcode = $p['pg_type'];
            $amount = '0';
            $product_name = '';

            $params = array();
            
            $sql = "SELECT 
                        rsrv.rsrv_id,
                        rsrv.rsrv_price,
                        rsrv.state,
                        rsrv.prd_id,
                        pln.pln_id,
                        estm.estm_area,
                        estm.estm_period,
                        prd.prd_title,
                        prd.prd_schedule,
                        usr.id,
                        usr.name,
                        usr.email
                    FROM  reserve rsrv
                    INNER JOIN users usr ON rsrv.user_id = usr.id
                    INNER JOIN planner pln ON rsrv.pln_id = pln.pln_id
                    LEFT JOIN 
                        estimate_bidding eb
                            INNER JOIN estimate estm
                            ON eb.estm_id = estm.estm_id
                    ON rsrv.eb_id = eb.eb_id
                    LEFT JOIN product prd ON rsrv.prd_id = prd.prd_id
                    WHERE rsrv.rsrv_id = :rsrv_id and usr.id = :user_id;";

            $rsrv_info = DB::select($sql, array('rsrv_id' => $p['rsrv_id'], 'user_id' => $user_id));

            

            if(!isset($rsrv_info[0]->rsrv_id)) {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '존재하지 않는 예약내역이거나 권한이 없습니다.';

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }

            if($rsrv_info[0]->estm_area == null){
                $product_name = $rsrv_info[0]->prd_title." ".$rsrv_info[0]->prd_schedule;
            }else{
                $product_name = $rsrv_info[0]->estm_area." ".$rsrv_info[0]->estm_period;
            }
            $amount = $rsrv_info[0]->rsrv_price - $p['use_point'];

            


            // 상품명 로그 찍기
            info($product_name);

            // 인증 파라메터
            $custom_parameter = [
                'rsrv_id' => $rsrv_info[0]->rsrv_id,
                'pg_type' => $p['pg_type'],
            ];

            // 인증 파라메터 로그 찍기
            info(json_encode($custom_parameter));
			
			if($pgcode == 'creditcard' || $pgcode == 'payco' || $pgcode == 'kakaopay'){
				$client_id = "azeta";	
			}else{
				$client_id = "azeta1";	
			}
            // 결제 요청 보내기
            $fParam = [
                'pgcode' => $pgcode,
                'user_id' => $user_id,
                'user_name' => $rsrv_info[0]->name,
                'service_name' =>'페이레터',
                'client_id' => $client_id,
                'amount' => $amount,
                'product_name' => $product_name,
                'email_flag' => 'Y',
                'email_addr' => $rsrv_info[0]->email,
                'autopay_flag' => 'N',
                'receipt_flag' => 'Y',
                'custom_parameter' => base64_encode(json_encode($custom_parameter)),
                //return_url 차후 모바일과 pc 분리해서 진행해야함.
                'return_url' => 'https://xn--oy2b117blyb.com/perchaced-popup',
                'callback_url' => 'https://xn--oy2b117blyb.com/payletter/OrderCallback',
                'cancel_url' => 'https://xn--oy2b117blyb.com/pay?rsrv_id='.$p['rsrv_id']
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
            $result = $this->execCURL($fParam, $pgcode);
            info('결제요청후: ' . $result);

            info('requestBeatPayment 완료');
        }

        return response()->json($result, 201);
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

    public function OrderCallback()
    {
        info("OrderCallback 시작");
        $this->checkClient();
        info("IP 체크 통과");
        
        //-- Callback URL로 반환된 결제결과 (JSON 파싱)
        $p = json_decode(file_get_contents('php://input'), true);
        
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
		
		$data = json_decode(base64_decode($p['custom_parameter']));

        // payhash 정보
        $userid = $p['user_id'];
        $amount = $p['amount'];
        $tid = $p['tid'];
		if($data->pg_type == 'creditcard' || $data->pg_type == 'payco' || $data->pg_type == 'kakaopay'){
			$pkey = config('p1q.POQ_KEY1_WO');	
		}else{
			$pkey = config('p1q.POQ_KEY2_WO');	
		}
        

        // payhash 데이터 로그 찍기
        info($userid . ' ' . $amount . ' ' . $tid . ' ' . $pkey);

        $payhash = strtoupper(hash("sha256", $userid.$amount.$tid.$pkey, false));

        // key값 비교
        if ($p['payhash'] === $payhash) {
            info("Payhash 일치");

            // 구매요청 불러오기
            $data = json_decode(base64_decode($p['custom_parameter']));
            info(base64_decode($p['custom_parameter']));

            $rsrv_id = $data->rsrv_id;

            $sql = "SELECT 
                        rsrv.rsrv_id,
                        estm.estm_id
                    FROM  reserve rsrv
                    LEFT JOIN 
                        estimate_bidding eb
                            INNER JOIN estimate estm
                            ON eb.estm_id = estm.estm_id
                    ON rsrv.eb_id = eb.eb_id
                    WHERE rsrv.state = 0 and rsrv.rsrv_id = :rsrv_id;";

            $rsrv_info = DB::select($sql, array('rsrv_id' => $rsrv_id));

            if ($rsrv_info[0]->estm_id != null) {
                $estm_udt_sql = "UPDATE estimate SET state = 2 WHERE estm_id = :estm_id";
                DB::update($estm_udt_sql, array('estm_id' => $rsrv_info[0]->estm_id));
            }
            $rsrv_udt_sql = "UPDATE reserve SET state = 1 , rsrv_pg_type = :pg_type , paied_at = now(), rsrv_pay_info = :rsrv_pay_info WHERE rsrv_id = :rsrv_id and state = 0";
            $rsrv_success = DB::update($rsrv_udt_sql, array('rsrv_id' => $rsrv_id, 'pg_type' => $data->pg_type, 'rsrv_pay_info' => json_encode($p)));

            info("구매완료처리 시작");
            if($rsrv_success > 0){
                $res = ['code' => 0, 'msg' => '성공'];
            }else{
                $res = ['code' => 1, 'msg' => '데이터처리 실패'];
            }

            
        } else {
            info("Payhash 불일치");
            info("생성된 Payhash: " . $payhash);
            info("전송된 Payhash: " . $p['payhash']);
            
            $res = ['code' => 1, 'msg' => 'HASH키값불일치'];
        }

        info(json_encode($res));

        return response()->json($res);
    }

    private function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    private function addParam4Mobile(&$fparam)
    {
        $fparam['inapp_flag']= 'Y';
        $fparam['app_return_url']= 'tripick://ISPSuccess/';
        $fparam['app_cancel_url']= 'tripick://ISPCancel/';
    }

    private function execCURL($fParam, $pgcode, $URL='request')
    {
    	if($pgcode == 'creditcard' || $pgcode == 'payco' || $pgcode == 'kakaopay'){
			$pkey = config('p1q.POQ_KEY1_WO');	
		}else{
			$pkey = config('p1q.POQ_KEY2_WO');	
		}
		info('https://pgapi.payletter.com/v1.0/payments/'.$URL);
		info($pkey);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pgapi.payletter.com/v1.0/payments/'.$URL);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization:PLKEY '.$pkey
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
        $allowlist = array("172.26.4.26",  //테스트1
        "121.254.205.166",  //테스트
        "211.115.72.37", "211.115.72.38", "211.115.117.11" //라이브
        );

        if (!in_array($_SERVER['REMOTE_ADDR'], $allowlist)) {
            info('CheckClient : 허가되지 않은 IP');
            info($_SERVER['REMOTE_ADDR']);
            die('This controller cannot be accessed from your location. ::'. $_SERVER['REMOTE_ADDR']);
        }*/
    }
}
