<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\App\Classes\Coolsms;

use DB;
use Auth;

class PaypleController extends Controller
{
    public function __construct(){
        $this->cst_id = "donggle2";
        $this->custKey = "122862243e51b6c01663125a5fd7b94c1ff26946a9aea1faa732995d52563309";
        $this->payple_curl = "https://cpay.payple.kr";
        $this->PCD_REFUND_KEY = "06d3ef3ed1b65bd8f7b747c773b917e40506f2664d0264a2bf6f6f721dd84daf";
        
        /*
        $this->cst_id = "test";
        $this->custKey = "abcd1234567890";
        $this->payple_curl = "https://testcpay.payple.kr";
        $this->PCD_REFUND_KEY = "a41ce010ede9fcbfb3be86b24858806596a9db68b79d138b147c3e563e1829a0";
        */

        $this->referer = env('APP_URL');
    }

    public function auth(Request $request) //결제후 DB 처리
    {
        header("Expires: Mon 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d, M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0; pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-type: application/json; charset=utf-8");
        header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');

        $referer = $request->referer;
        $url = $this->payple_curl."/php/auth.php";

        $headers = array( 
            "Expires: 0",
            "Last-Modified: ".gmdate("D, d, M Y H:i:s")." GMT",
            "Cache-Control: no-store, no-cache, must-revalidate",
            "Pragma: no-cache"
        );

        $data = array(
            "cst_id" => $this->cst_id,
            "custKey" => $this->custKey
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);
        $result = json_decode($response);

        if($result->result != 'success'){
            $this->res['query'] = $result;
            $this->res['msg'] = $result->result_msg;
            $this->res['state'] = config('res_code.API_ERR');

            return response(json_encode($result), 200)->header('Content-Type', 'application/json');
        }

        return response(json_encode($result), 200)
                    ->header("Expires","0")
                    ->header("Last-Modified" , gmdate("D, d, M Y H:i:s") . " GMT")
                    ->header("Cache-Control","no-store, no-cache, must-revalidate")
                    ->header("Cache-Control","post-check=0; pre-check=0",false)
                    ->header("Pragma","no-cache")
                    ->header("Content-type","application/json; charset=utf-8");
    }

    public function pay(Request $request) //결제후 DB 처리
    {
        if($request->PCD_PAY_RST != 'success'){
            $this->res['query'] = null;
            $this->res['msg'] = $request->PCD_PAY_MSG ;
            $this->res['state'] = config('res_code.API_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        //결제승인 필요한 parameters
        $PCD_CST_ID = $this->cst_id;
        $PCD_CUST_KEY = $this->custKey;
        $PCD_AUTH_KEY = $request->PCD_AUTH_KEY;
        $PCD_PAY_REQKEY = $request->PCD_PAY_REQKEY;
        $PCD_PAYER_ID = $request->PCD_PAYER_ID;

        $uid = Auth::guard('api')->user()->id;
        $user = DB::table('users')->where('id',$uid)->first();

        $PCD_PAY_RST = $request->PCD_PAY_RST;
        $PCD_PAY_MSG = $request->PCD_PAY_MSG;
        $PCD_PAY_OID = $request->PCD_PAY_OID;
        $PCD_PAY_TYPE = $request->PCD_PAY_TYPE;
        $PCD_PAYER_NO = $request->PCD_PAYER_NO;
        $PCD_PAYER_EMAIL = $request->PCD_PAYER_EMAIL;
        $PCD_REGULER_FLAG = $request->PCD_REGULER_FLAG;
        $PCD_PAY_YEAR = $request->PCD_PAY_YEAR;
        $PCD_PAY_MONTH = $request->PCD_PAY_MONTH;
        $PCD_PAY_GOODS = $request->PCD_PAY_GOODS;
        $PCD_PAY_TOTAL = $request->PCD_PAY_TOTAL;
        $PCD_PAY_TAXTOTAL = $request->PCD_PAY_TAXTOTAL;
        $PCD_PAY_ISTAX = $request->PCD_PAY_ISTAX;
        $PCD_PAY_CARDNAME = $request->PCD_PAY_CARDNAME;
        $PCD_PAY_CARDNUM = $request->PCD_PAY_CARDNUM;
        $PCD_PAY_CARDTRADENUM = $request->PCD_PAY_CARDTRADENUM;
        $PCD_PAY_CARDAUTHNO = $request->PCD_PAY_CARDAUTHNO;
        $PCD_PAY_CARDRECEIPT = $request->PCD_PAY_CARDRECEIPT;
        $PCD_PAY_BANK = $request->PCD_PAY_BANK;
        $PCD_PAY_BANKNAME = $request->PCD_PAY_BANKNAME;
        $PCD_PAY_BANKNUM = $request->PCD_PAY_BANKNUM;
        $PCD_TAXSAVE_RST = $request->PCD_TAXSAVE_RST;
        $PCD_PAY_TIME = $request->PCD_PAY_TIME;

        $PCD_PAY_WORK = $request->PCD_PAY_WORK;

        $PCD_PAY_TOTAL_AUTH = $request->item_price;
        $PCD_PAY_COFURL = $request->PCD_PAY_COFURL;

        if($PCD_PAY_WORK == 'AUTH' || $PCD_PAY_WORK == 'AUTHREG'){ //결제등록만할때
            if($PCD_PAY_TOTAL_AUTH == 5500){ 
                $regular_item = 'buyer';
                $level = 2;
            }else if($PCD_PAY_TOTAL_AUTH == 11000){
                $regular_item = 'special';
                $level = 3;
            }
        }else{ //결제할때
            //가격 맞는지 확인하는부분
            if($PCD_PAY_TOTAL == 5500){ 
                $regular_item = 'buyer';
                $level = 2;
            }else if($PCD_PAY_TOTAL == 11000){
                $regular_item = 'special';
                $level = 3;
            }else{
                $this->res['query'] = null;
                $this->res['msg'] = '잘못된 접근입니다. 새로고침 후 다시 시도해 주세요.(실 가격과 결제한 가격이 맞지 않음)';
                $this->res['state'] = config('res_code.API_ERR');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }

            $url = $PCD_PAY_COFURL;

            $data = array(
                "PCD_CST_ID" => $PCD_CST_ID,
                "PCD_CUST_KEY" => $PCD_CUST_KEY,
                "PCD_AUTH_KEY" => $PCD_AUTH_KEY,
                "PCD_PAY_REQKEY" => $PCD_PAY_REQKEY,
                "PCD_PAYER_ID" => $PCD_PAYER_ID
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response);

            if($result->PCD_PAY_RST != 'success'){
                $this->res['query'] = null;
                $this->res['msg'] = $result->PCD_PAY_MSG;
                $this->res['state'] = config('res_code.API_ERR');

                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            }

            $insert_personalpay = DB::table('personalpay')->insert([
                'order_id' => $PCD_PAY_OID,
                'uid' => $uid,
                'pp_method' => 'pay',
                'pp_resultcode' => $PCD_PAY_RST,
                'pp_name' => $user->name,
                'pp_email' => $PCD_PAYER_EMAIL,
                'pp_hp' => $user->mobile_number,
                'pp_content' => $PCD_PAY_GOODS,
                'pp_price' => $PCD_PAY_TOTAL,
                'pp_pg' => "PAYPLE",
                'pp_tno' => $PCD_PAYER_ID,
                'pp_receipt_price' => $PCD_PAY_TOTAL,
                'pp_settle_case' => $PCD_PAY_TYPE,
                'pp_deposit_name' => $user->name,
                'pp_receipt_time' => $PCD_PAY_TIME,
                'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                'pp_account_number' => $PCD_PAY_BANKNAME,
                'pp_bank_account' => $PCD_PAY_BANKNUM,
                'pp_ip' => $_SERVER["REMOTE_ADDR"],
                'created_at' => DB::raw("now()"),
                'updated_at' => DB::raw("now()"),
                'pp_result' => json_encode($result),
            ]);
            
        }

        DB::table('users')->where('id',$uid)->update([
            "payple_billingkey" => $PCD_PAYER_ID,
            "receipt_date" => DB::raw("now()"),
            "regular_end" => DB::raw("DATE_ADD(CURDATE(), INTERVAL 1 MONTH)"),
            "regular_item" => $regular_item,
            "regular_method" => $PCD_PAY_TYPE,
            "level" => $level
        ]);

        $this->res['query'] = $PCD_PAY_OID;
        $this->res['msg'] = $PCD_PAY_MSG;
        $this->res['state'] = config('res_code.OK');

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        
    }

    public function pay_mobile(Request $request) //결제후 DB 처리
    {
        $before_url = $request->PCD_USER_DEFINE2;
        $redirect_url = env('APP_ENV') === 'production'? "https://m.dong-gle.co.kr".$before_url : ( env('APP_ENV') === 'local' ? "http://localhost:8081".$before_url : "https://devm.dong-gle.co.kr".$before_url);
        if($request->PCD_PAY_RST != 'success'){
            $this->res['query'] = null;
            $this->res['msg'] = $request->PCD_PAY_MSG ;
            $this->res['state'] = config('res_code.API_ERR');
            return redirect($redirect_url."?message=".$this->res['msg']);
        }
        
        //결제승인 필요한 parameters
        $PCD_CST_ID = $this->cst_id;
        $PCD_CUST_KEY = $this->custKey;
        $PCD_AUTH_KEY = $request->PCD_AUTH_KEY;
        $PCD_PAY_REQKEY = $request->PCD_PAY_REQKEY;
        $PCD_PAYER_ID = $request->PCD_PAYER_ID;



        $PCD_PAY_RST = $request->PCD_PAY_RST;
        $PCD_PAY_MSG = $request->PCD_PAY_MSG;
        $PCD_PAY_OID = $request->PCD_PAY_OID;
        $PCD_PAY_TYPE = $request->PCD_PAY_TYPE;
        $PCD_PAYER_NO = $request->PCD_PAYER_NO;
        $PCD_PAYER_EMAIL = $request->PCD_PAYER_EMAIL;
        $PCD_REGULER_FLAG = $request->PCD_REGULER_FLAG;
        $PCD_PAY_YEAR = $request->PCD_PAY_YEAR;
        $PCD_PAY_MONTH = $request->PCD_PAY_MONTH;
        $PCD_PAY_GOODS = $request->PCD_PAY_GOODS;
        $PCD_PAY_TOTAL = $request->PCD_PAY_TOTAL;
        $PCD_PAY_TAXTOTAL = $request->PCD_PAY_TAXTOTAL;
        $PCD_PAY_ISTAX = $request->PCD_PAY_ISTAX;
        $PCD_PAY_CARDNAME = $request->PCD_PAY_CARDNAME;
        $PCD_PAY_CARDNUM = $request->PCD_PAY_CARDNUM;
        $PCD_PAY_CARDTRADENUM = $request->PCD_PAY_CARDTRADENUM;
        $PCD_PAY_CARDAUTHNO = $request->PCD_PAY_CARDAUTHNO;
        $PCD_PAY_CARDRECEIPT = $request->PCD_PAY_CARDRECEIPT;
        $PCD_PAY_BANK = $request->PCD_PAY_BANK;
        $PCD_PAY_BANKNAME = $request->PCD_PAY_BANKNAME;
        $PCD_PAY_BANKNUM = $request->PCD_PAY_BANKNUM;
        $PCD_TAXSAVE_RST = $request->PCD_TAXSAVE_RST;
        $PCD_PAY_TIME = $request->PCD_PAY_TIME;

        $PCD_PAY_WORK = $request->PCD_PAY_WORK;

        $PCD_PAY_TOTAL_AUTH = $request->PCD_USER_DEFINE1;
        
        $PCD_PAY_COFURL = $request->PCD_PAY_COFURL;

        $before_url = $request->PCD_USER_DEFINE2;
        if($before_url == '/register'){
            $redirect_url = env('APP_ENV') === 'production'? "https://m.dong-gle.co.kr/register/style" : ( env('APP_ENV') === 'local' ? "http://localhost:8081/register/style" : "https://devm.dong-gle.co.kr/register/style");
        }else{
            $redirect_url = env('APP_ENV') === 'production'? "https://m.dong-gle.co.kr".$before_url : ( env('APP_ENV') === 'local' ? "http://localhost:8081".$before_url : "https://devm.dong-gle.co.kr".$before_url);
        }
        $uid = $PCD_PAYER_NO;
        $user = DB::table('users')->where('id',$uid)->first();

        if($PCD_PAY_WORK == 'AUTH' || $PCD_PAY_WORK == 'AUTHREG'){ //결제등록만할때
            if($PCD_PAY_TOTAL_AUTH == 5500){ 
                $regular_item = 'buyer';
                $level = 2;
            }else if($PCD_PAY_TOTAL_AUTH == 11000){
                $regular_item = 'special';
                $level = 3;
            }
        }else{ //결제할때
            //가격 맞는지 확인하는부분
            if($PCD_PAY_TOTAL == 5500){ 
                $regular_item = 'buyer';
                $level = 2;
            }else if($PCD_PAY_TOTAL == 11000){
                $regular_item = 'special';
                $level = 3;
            }else{
                $this->res['query'] = null;
                $this->res['msg'] = '잘못된 접근입니다. 새로고침 후 다시 시도해 주세요.(실 가격과 결제한 가격이 맞지 않음)';
                $this->res['state'] = config('res_code.API_ERR');

                return redirect($redirect_url."?message=".$this->res['msg']);
            }

            $url = $PCD_PAY_COFURL;

            $data = array(
                "PCD_CST_ID" => $PCD_CST_ID,
                "PCD_CUST_KEY" => $PCD_CUST_KEY,
                "PCD_AUTH_KEY" => $PCD_AUTH_KEY,
                "PCD_PAY_REQKEY" => $PCD_PAY_REQKEY,
                "PCD_PAYER_ID" => $PCD_PAYER_ID
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response);

            if($result->PCD_PAY_RST != 'success'){
                $this->res['query'] = null;
                $this->res['msg'] = $result->PCD_PAY_MSG;
                $this->res['state'] = config('res_code.API_ERR');

                return redirect($redirect_url."?message=".$this->res['msg']);
            }

            $insert_personalpay = DB::table('personalpay')->insert([
                'order_id' => $PCD_PAY_OID,
                'uid' => $uid,
                'pp_method' => 'pay',
                'pp_resultcode' => $PCD_PAY_RST,
                'pp_name' => $user->name,
                'pp_email' => $PCD_PAYER_EMAIL,
                'pp_hp' => $user->mobile_number,
                'pp_content' => $PCD_PAY_GOODS,
                'pp_price' => $PCD_PAY_TOTAL,
                'pp_pg' => "PAYPLE",
                'pp_tno' => $PCD_PAYER_ID,
                'pp_receipt_price' => $PCD_PAY_TOTAL,
                'pp_settle_case' => $PCD_PAY_TYPE,
                'pp_deposit_name' => $user->name,
                'pp_receipt_time' => $PCD_PAY_TIME,
                'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
                'pp_account_number' => $PCD_PAY_BANKNAME,
                'pp_bank_account' => $PCD_PAY_BANKNUM,
                'pp_ip' => $_SERVER["REMOTE_ADDR"],
                'created_at' => DB::raw("now()"),
                'updated_at' => DB::raw("now()"),
                'pp_result' => json_encode($result),
            ]);
            
        }

        DB::table('users')->where('id',$uid)->update([
            "payple_billingkey" => $PCD_PAYER_ID,
            "receipt_date" => DB::raw("now()"),
            "regular_end" => DB::raw("DATE_ADD(CURDATE(), INTERVAL 1 MONTH)"),
            "regular_item" => $regular_item,
            "regular_method" => $PCD_PAY_TYPE,
            "level" => $level
        ]);

        $this->res['query'] = $PCD_PAY_OID;
        $this->res['msg'] = $PCD_PAY_MSG;
        $this->res['state'] = config('res_code.OK');

        return redirect($redirect_url."?message=".$this->res['msg']);
        
    }

    public function terminate(Request $request){ //해지
        $uid = Auth::guard('api')->user()->id;
        $user = DB::table('users')->select('id','payple_billingkey')->where('id',$uid)->first();

        $url = $this->payple_curl."/php/auth.php";
        $cst_id = $this->cst_id;
        $custKey = $this->custKey;
        $PCD_PAY_WORK = "PUSERDEL";

        $referer = env('APP_URL');

        $data = array(
            "cst_id" => $cst_id,
            "custKey" => $custKey,
            "PCD_PAY_WORK" => $PCD_PAY_WORK
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);

        if($result->result != 'success'){
            $this->res['query'] = null;
            $this->res['msg'] = $result->result_msg;
            $this->res['state'] = config('res_code.API_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $headers = array( 
            "Content-Type: application/json;"
        );

        $url = $result->return_url;
        $PCD_CST_ID = $result->cst_id;
        $PCD_CUST_KEY = $result->custKey;
        $PCD_AUTH_KEY = $result->AuthKey;
        $PCD_PAYER_ID = $user->payple_billingkey;
        $PCD_PAYER_NO = $user->id;

        $data = array(
            "PCD_CST_ID" => $PCD_CST_ID,
            "PCD_CUST_KEY" => $PCD_CUST_KEY,
            "PCD_AUTH_KEY" => $PCD_AUTH_KEY,
            "PCD_PAYER_ID" => $PCD_PAYER_ID,
            "PCD_PAYER_NO" => $PCD_PAYER_NO
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $terminate_result = json_decode($response);

        if($terminate_result->PCD_PAY_RST != 'success'){
            $this->res['query'] = null;
            $this->res['msg'] = $terminate_result->PCD_PAY_MSG;
            $this->res['state'] = config('res_code.API_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $PCD_PAY_RST = $terminate_result->PCD_PAY_RST;
        $PCD_PAY_MSG = $terminate_result->PCD_PAY_MSG;
        $PCD_PAY_TYPE = $terminate_result->PCD_PAY_TYPE;
        $PCD_PAY_WORK = $terminate_result->PCD_PAY_WORK;
        $PCD_PAYER_ID = $terminate_result->PCD_PAYER_ID;
        $PCD_PAYER_NO = $terminate_result->PCD_PAYER_NO;

        DB::table('payple_terminate')->insert([
            "uid" => $uid,
            "PCD_PAY_RST" => $PCD_PAY_RST,
            "PCD_PAY_MSG" => $PCD_PAY_MSG,
            "PCD_PAY_TYPE" => $PCD_PAY_TYPE,
            "PCD_PAY_WORK" => $PCD_PAY_WORK,
            "PCD_PAYER_ID" => $PCD_PAYER_ID,
            "PCD_PAYER_NO" => $PCD_PAYER_NO
        ]);

        DB::table('users')->where('id',$uid)->update([
            "payple_billingkey" => null,
            "level" => 1
            
        ]);

        $this->res['query'] = null;
        $this->res['msg'] = $PCD_PAY_MSG;
        $this->res['state'] = config('res_code.OK');

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    public function order(Request $request){ //주문

        $redirect_url = env('APP_ENV') === 'production'? config('app.nice_complete_mobile_url') : ( env('APP_ENV') === 'local' ? "http://localhost:8081/order/paying" : "https://devm.dong-gle.co.kr/order/paying");
        if($request->PCD_PAY_RST != 'success'){
            $this->res['query'] = null;
            $this->res['msg'] = $request->PCD_PAY_MSG ;
            $this->res['state'] = config('res_code.API_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        //결제승인 필요한 parameters
        $PCD_CST_ID = $this->cst_id;
        $PCD_CUST_KEY = $this->custKey;
        $PCD_AUTH_KEY = $request->PCD_AUTH_KEY;
        $PCD_PAY_REQKEY = $request->PCD_PAY_REQKEY;
        $PCD_PAYER_ID = $request->PCD_PAYER_ID;

        

        $PCD_PAY_RST = $request->PCD_PAY_RST;
        $PCD_PAY_MSG = $request->PCD_PAY_MSG;
        $PCD_PAY_OID = $request->PCD_PAY_OID;
        $PCD_PAY_TYPE = $request->PCD_PAY_TYPE;
        $PCD_PAYER_NO = $request->PCD_PAYER_NO;
        $PCD_PAYER_EMAIL = $request->PCD_PAYER_EMAIL;
        $PCD_REGULER_FLAG = $request->PCD_REGULER_FLAG;
        $PCD_PAY_YEAR = $request->PCD_PAY_YEAR;
        $PCD_PAY_MONTH = $request->PCD_PAY_MONTH;
        $PCD_PAY_GOODS = $request->PCD_PAY_GOODS;
        $PCD_PAY_TOTAL = $request->PCD_PAY_TOTAL;
        $PCD_PAY_TAXTOTAL = $request->PCD_PAY_TAXTOTAL;
        $PCD_PAY_ISTAX = $request->PCD_PAY_ISTAX;
        $PCD_PAY_CARDNAME = $request->PCD_PAY_CARDNAME;
        $PCD_PAY_CARDNUM = $request->PCD_PAY_CARDNUM;
        $PCD_PAY_CARDTRADENUM = $request->PCD_PAY_CARDTRADENUM;
        $PCD_PAY_CARDAUTHNO = $request->PCD_PAY_CARDAUTHNO;
        $PCD_PAY_CARDRECEIPT = $request->PCD_PAY_CARDRECEIPT;
        $PCD_PAY_BANK = $request->PCD_PAY_BANK;
        $PCD_PAY_BANKNAME = $request->PCD_PAY_BANKNAME;
        $PCD_PAY_BANKNUM = $request->PCD_PAY_BANKNUM;
        $PCD_TAXSAVE_RST = $request->PCD_TAXSAVE_RST;
        $PCD_PAY_TIME = $request->PCD_PAY_TIME;

        $PCD_PAY_COFURL = $request->PCD_PAY_COFURL;

        $order_id = $PCD_PAY_OID;

        //가격 맞는지 확인하는부분
        $carts = DB::table('cart')
        ->where('order_id',$PCD_PAY_OID)
        ->get();

        $total_price = 0;
        $send_cost = 0;

        $level = Auth::guard('api')->user()->level;

        //등급할인
        $level_discount = 0;

        $orders = DB::table('order')->where('order_id', $order_id)->get();
        $cart_count = count($orders);

        $company = DB::table('company')->first();
        
        $total_cp_id = '';
        $mobile_yn = '';
        $item_name = '';
        $order_count = count($orders);
        $hope_day = '';
        $uid = '';

        if(count($orders) == 0){
            DB::table('cart')->where('order_id',$order_id)->update([
                "cp_id" => null,
                "cp_price" => 0
            ]);
            $this->res['query'] = null;
            $this->res['msg'] = "결제하기 버튼누를때 주문정보가 제대로 삽입되지 않았습니다. 새로고침 후 다시 시도해주세요.";
            $this->res['state'] = config('res_code.NO_DATA');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }
        
        DB::beginTransaction();
        foreach($orders as $key => $order){
            $uid = $order->s_uid;
            $hope_day = $order->hope_day;
            $item_name = $order->item_name;
            $total_cp_id = $order->total_cp_id;
            $mobile_yn = $order->mobile_yn;
            $personal_cp_id = $order->coupon_id;
            
            

            //쿠폰처리
            $coupon = DB::table('coupon')->where('cp_id',$personal_cp_id)->first();
            if(isset($coupon->cp_id)){
                $coupon_update = DB::table('coupon')->where('cp_id',$coupon->cp_id)->update([
                    "cp_use" => 1,
                ]);
                if($coupon_update == 0){
                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    $this->res['query'] = null;
                    $this->res['msg'] = "쿠폰 이미 사용되었거나 존재하지 않는쿠폰";
                    $this->res['state'] = config('res_code.NO_DATA');
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    
                    
                }
                $coupon_log_insert = DB::table('coupon_use_log')->insert([
                    "cp_id" => $coupon->cp_id,
                    "cp_subject" => $coupon->cp_subject,
                    "mb_id" => $order->s_uid,
                    "od_id" => $order_id,
                    "item_id" => $order->item_id,
                    "cp_price" => $coupon->cp_price,
                    "cl_datetime" => DB::raw('now()'),
                ]);
                if($coupon_log_insert == 0){
                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    $this->res['query'] = null;
                    $this->res['msg'] = "쿠폰 사용이력 작성 실패";
                    $this->res['state'] = config('res_code.NO_DATA');
                    
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    
                }
            }
            $option = DB::table('item_option')->where('item_id',$order->item_id)->where('name',$order->option)->first();
            if(!isset($option->id)){
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "옵션정보 없음";
                $this->res['state'] = config('res_code.NO_DATA');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                
            }

            $stock_decrese = DB::table('item_option')->where('id',$option->id)->where('stock_qty','>',0)->decrement('stock_qty',$order->qty);
            if($stock_decrese == 0){
            
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "재고량 감소 실패 (품절됨)";
                $this->res['state'] = config('res_code.NO_DATA');
                
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                
            }
        }

        $user = DB::table('users')->where('id',$uid)->first();

        $cart_update = DB::table('cart')->where('order_id',$order_id)->update([
            "buy_yn" => 1,
        ]);

        /*if($cart_update == 0){
            DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "장바구니 상태값 변경 실패";
                $this->res['state'] = config('res_code.NO_DATA');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }*/

        $days = $hope_day;

        for($i=0;$i<$days;$i++){
            $day = date('N',strtotime("+".($i+1)."day"));
            if($day>5)
                $days++;
        }




        foreach($carts as $cart){
            $item_price = $cart->price;
            $item_qty = $cart->qty;
            $option_price = $cart->option_price;
            $coupon_price = $cart->cp_price;
            
            $object_price = bcsub(bcmul(bcadd($item_price,$option_price,0),$item_qty,0),$coupon_price,0);
            $total_price = bcadd($total_price,$object_price,0);

            $send_cost = $cart->send_cost;
        }

        

        //$total_cp_id = $request->total_cp_id;
        $total_coupon = DB::table('coupon')->where('cp_id',$total_cp_id)->first();
        $total_coupon_price = isset($total_coupon->cp_price)?$total_coupon->cp_price:0;
        $total_price = bcsub($total_price,$total_coupon_price,0); //전체쿠폰할인
        $total_price = bcsub($total_price,$level_discount,0); //등급할인
        $total_price = bcadd($total_price,$send_cost,0);

        if($total_price != $PCD_PAY_TOTAL){
            DB::table('cart')->where('order_id',$order_id)->update([
                "cp_id" => null,
                "cp_price" => 0
            ]);
            $this->res['query'] = $total_price;
            $this->res['msg'] = "위변조 데이터 에러 (가격부분)";
            $this->res['state'] = config('res_code.API_ERR');
            
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        
        }

        //전체쿠폰 작성이력
        if(isset($total_coupon->cp_id)){
            $total_coupon_update = DB::table('coupon')->where('cp_id',$total_coupon->cp_id)->update([
                "cp_use" => 1,
            ]);
            if($total_coupon_update == 0){
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "전체쿠폰 이미 사용되었거나 존재하지 않는쿠폰";
                $this->res['state'] = config('res_code.NO_DATA');
          
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                
            }
            $coupon_log_insert = DB::table('coupon_use_log')->insert([
                "cp_id" => $total_coupon->cp_id,
                "cp_subject" => $total_coupon->cp_subject,
                "mb_id" => $order->s_uid,
                "od_id" => $order_id,
                "cp_price" => $total_coupon->cp_price,
                "cl_datetime" => DB::raw('now()'),
            ]);
            if($coupon_log_insert == 0){
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "전체쿠폰 사용이력 작성 실패";
                $this->res['state'] = config('res_code.NO_DATA');
                
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
               
            }
        }
        //주문승인
        $url = $PCD_PAY_COFURL;

        $data = array(
            "PCD_CST_ID" => $PCD_CST_ID,
            "PCD_CUST_KEY" => $PCD_CUST_KEY,
            "PCD_AUTH_KEY" => $PCD_AUTH_KEY,
            "PCD_PAY_REQKEY" => $PCD_PAY_REQKEY,
            "PCD_PAYER_ID" => $PCD_PAYER_ID
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);

        if($result->PCD_PAY_RST != 'success'){
            DB::rollBack();
            DB::table('cart')->where('order_id',$order_id)->update([
                "cp_id" => null,
                "cp_price" => 0
            ]);
            $this->res['query'] = null;
            $this->res['msg'] = $result->PCD_PAY_MSG;
            $this->res['state'] = config('res_code.API_ERR');

            
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
            
        }
        $insert_personalpay = DB::table('personalpay')->insert([
            'order_id' => $PCD_PAY_OID,
            'uid' => $uid,
            'pp_method' => 'pay',
            'pp_resultcode' => $PCD_PAY_RST,
            'pp_name' => $user->name,
            'pp_email' => $PCD_PAYER_EMAIL,
            'pp_hp' => $user->mobile_number,
            'pp_content' => $PCD_PAY_GOODS,
            'pp_price' => $PCD_PAY_TOTAL,
            'pp_pg' => "PAYPLE",
            'pp_tno' => $PCD_PAYER_ID,
            'pp_receipt_price' => $PCD_PAY_TOTAL,
            'pp_settle_case' => $PCD_PAY_TYPE,
            'pp_deposit_name' => $user->name,
            'pp_receipt_time' => $PCD_PAY_TIME,
            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
            'pp_ip' => $_SERVER["REMOTE_ADDR"],
            'created_at' => DB::raw("now()"),
            'updated_at' => DB::raw("now()"),
            'pp_result' => json_encode($result),
        ]);
        

        $update_pg_result = DB::table('order')->where('order_id',$order_id)->update([
            "od_status" => "order_apply",
            "hope_date" => date( 'Y-m-d',strtotime("+".$days." day")),
            "pg_result" => json_encode($result),
            "settle_case" => $PCD_PAY_TYPE,
            "receipt_time" => $PCD_PAY_TIME,
            "deleted" => 0,
            "misu" => 0,
            "created_at" => DB::raw('now()'),
        ]);

        DB::commit();

        if($order_count > 1){
            $txt = "주문하신 (".$item_name.") 외 ".bcsub($order_count,1,0)."개를 결제하셨습니다.";    
        }else{
            $txt = "주문하신 (".$item_name.") 을 결제하셨습니다.";    
        }
        
        $sms = Coolsms::send_sms($user->mobile_number, $txt);

        $this->res['query'] = $PCD_PAY_OID;
        $this->res['msg'] = $PCD_PAY_MSG;
        $this->res['state'] = config('res_code.OK');
        
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        
    }

    public function order_mobile(Request $request){ //주문

        $redirect_url = env('APP_ENV') === 'production'? config('app.nice_complete_mobile_url') : ( env('APP_ENV') === 'local' ? "http://localhost:8081/order/paying" : "https://devm.dong-gle.co.kr/order/paying");
        if($request->PCD_PAY_RST != 'success'){
            $this->res['query'] = null;
            $this->res['msg'] = $request->PCD_PAY_MSG ;
            $this->res['state'] = config('res_code.API_ERR');
            return redirect($redirect_url."?message=".$this->res['msg']);
        }
        
        //결제승인 필요한 parameters
        $PCD_CST_ID = $this->cst_id;
        $PCD_CUST_KEY = $this->custKey;
        $PCD_AUTH_KEY = $request->PCD_AUTH_KEY;
        $PCD_PAY_REQKEY = $request->PCD_PAY_REQKEY;
        $PCD_PAYER_ID = $request->PCD_PAYER_ID;

        

        $PCD_PAY_RST = $request->PCD_PAY_RST;
        $PCD_PAY_MSG = $request->PCD_PAY_MSG;
        $PCD_PAY_OID = $request->PCD_PAY_OID;
        $PCD_PAY_TYPE = $request->PCD_PAY_TYPE;
        $PCD_PAYER_NO = $request->PCD_PAYER_NO;
        $PCD_PAYER_EMAIL = $request->PCD_PAYER_EMAIL;
        $PCD_REGULER_FLAG = $request->PCD_REGULER_FLAG;
        $PCD_PAY_YEAR = $request->PCD_PAY_YEAR;
        $PCD_PAY_MONTH = $request->PCD_PAY_MONTH;
        $PCD_PAY_GOODS = $request->PCD_PAY_GOODS;
        $PCD_PAY_TOTAL = $request->PCD_PAY_TOTAL;
        $PCD_PAY_TAXTOTAL = $request->PCD_PAY_TAXTOTAL;
        $PCD_PAY_ISTAX = $request->PCD_PAY_ISTAX;
        $PCD_PAY_CARDNAME = $request->PCD_PAY_CARDNAME;
        $PCD_PAY_CARDNUM = $request->PCD_PAY_CARDNUM;
        $PCD_PAY_CARDTRADENUM = $request->PCD_PAY_CARDTRADENUM;
        $PCD_PAY_CARDAUTHNO = $request->PCD_PAY_CARDAUTHNO;
        $PCD_PAY_CARDRECEIPT = $request->PCD_PAY_CARDRECEIPT;
        $PCD_PAY_BANK = $request->PCD_PAY_BANK;
        $PCD_PAY_BANKNAME = $request->PCD_PAY_BANKNAME;
        $PCD_PAY_BANKNUM = $request->PCD_PAY_BANKNUM;
        $PCD_TAXSAVE_RST = $request->PCD_TAXSAVE_RST;
        $PCD_PAY_TIME = $request->PCD_PAY_TIME;

        $PCD_PAY_COFURL = $request->PCD_PAY_COFURL;

        $order_id = $PCD_PAY_OID;

        //가격 맞는지 확인하는부분
        $carts = DB::table('cart')
        ->where('order_id',$PCD_PAY_OID)
        ->get();

        $total_price = 0;
        $send_cost = 0;

        //$level = Auth::guard('api')->user()->level;

        //등급할인
        $level_discount = 0;

        $orders = DB::table('order')->where('order_id', $order_id)->get();
        $cart_count = count($orders);

        $company = DB::table('company')->first();
        
        $total_cp_id = '';
        $mobile_yn = '';
        $item_name = '';
        $order_count = count($orders);
        $hope_day = '';
        $uid = '';

        if(count($orders) == 0){
            DB::table('cart')->where('order_id',$order_id)->update([
                "cp_id" => null,
                "cp_price" => 0
            ]);
            $this->res['query'] = null;
            $this->res['msg'] = "결제하기 버튼누를때 주문정보가 제대로 삽입되지 않았습니다. 새로고침 후 다시 시도해주세요.";
            $this->res['state'] = config('res_code.NO_DATA');
            return redirect($redirect_url."?message=".$this->res['msg']);
        }
        
        DB::beginTransaction();
        foreach($orders as $key => $order){
            $uid = $order->s_uid;
            $hope_day = $order->hope_day;
            $item_name = $order->item_name;
            $total_cp_id = $order->total_cp_id;
            $mobile_yn = $order->mobile_yn;
            $personal_cp_id = $order->coupon_id;
            
            

            //쿠폰처리
            $coupon = DB::table('coupon')->where('cp_id',$personal_cp_id)->first();
            if(isset($coupon->cp_id)){
                $coupon_update = DB::table('coupon')->where('cp_id',$coupon->cp_id)->update([
                    "cp_use" => 1,
                ]);
                if($coupon_update == 0){
                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    $this->res['query'] = null;
                    $this->res['msg'] = "쿠폰 이미 사용되었거나 존재하지 않는쿠폰";
                    $this->res['state'] = config('res_code.NO_DATA');

                    return redirect($redirect_url."?message=".$this->res['msg']);
                    
                    
                }
                $coupon_log_insert = DB::table('coupon_use_log')->insert([
                    "cp_id" => $coupon->cp_id,
                    "cp_subject" => $coupon->cp_subject,
                    "mb_id" => $order->s_uid,
                    "od_id" => $order_id,
                    "item_id" => $order->item_id,
                    "cp_price" => $coupon->cp_price,
                    "cl_datetime" => DB::raw('now()'),
                ]);
                if($coupon_log_insert == 0){
                    DB::rollBack();
                    DB::table('cart')->where('order_id',$order_id)->update([
                        "cp_id" => null,
                        "cp_price" => 0
                    ]);
                    $this->res['query'] = null;
                    $this->res['msg'] = "쿠폰 사용이력 작성 실패";
                    $this->res['state'] = config('res_code.NO_DATA');
                    return redirect($redirect_url."?message=".$this->res['msg']);
                    
                }
            }
            $option = DB::table('item_option')->where('item_id',$order->item_id)->where('name',$order->option)->first();
            if(!isset($option->id)){
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "옵션정보 없음";
                $this->res['state'] = config('res_code.NO_DATA');
                return redirect($redirect_url."?message=".$this->res['msg']);
                
            }

            $stock_decrese = DB::table('item_option')->where('id',$option->id)->where('stock_qty','>',0)->decrement('stock_qty',$order->qty);
            if($stock_decrese == 0){
            
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "재고량 감소 실패 (품절됨)";
                $this->res['state'] = config('res_code.NO_DATA');
                return redirect($redirect_url."?message=".$this->res['msg']);
                
            }
        }

        $user = DB::table('users')->where('id',$uid)->first();

        $cart_update = DB::table('cart')->where('order_id',$order_id)->update([
            "buy_yn" => 1,
        ]);

        /*if($cart_update == 0){
            DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "장바구니 상태값 변경 실패";
                $this->res['state'] = config('res_code.NO_DATA');
                return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }*/

        $days = $hope_day;

        for($i=0;$i<$days;$i++){
            $day = date('N',strtotime("+".($i+1)."day"));
            if($day>5)
                $days++;
        }




        foreach($carts as $cart){
            $item_price = $cart->price;
            $item_qty = $cart->qty;
            $option_price = $cart->option_price;
            $coupon_price = $cart->cp_price;
            
            $object_price = bcsub(bcmul(bcadd($item_price,$option_price,0),$item_qty,0),$coupon_price,0);
            $total_price = bcadd($total_price,$object_price,0);

            $send_cost = $cart->send_cost;
        }

        

        //$total_cp_id = $request->total_cp_id;
        $total_coupon = DB::table('coupon')->where('cp_id',$total_cp_id)->first();
        $total_coupon_price = isset($total_coupon->cp_price)?$total_coupon->cp_price:0;
        $total_price = bcsub($total_price,$total_coupon_price,0); //전체쿠폰할인
        $total_price = bcsub($total_price,$level_discount,0); //등급할인
        $total_price = bcadd($total_price,$send_cost,0);

        if($total_price != $PCD_PAY_TOTAL){
            DB::table('cart')->where('order_id',$order_id)->update([
                "cp_id" => null,
                "cp_price" => 0
            ]);
            $this->res['query'] = $total_price;
            $this->res['msg'] = "위변조 데이터 에러 (가격부분)";
            $this->res['state'] = config('res_code.API_ERR');

            return redirect($redirect_url."?message=".$this->res['msg']);
            
        }

        //전체쿠폰 작성이력
        if(isset($total_coupon->cp_id)){
            $total_coupon_update = DB::table('coupon')->where('cp_id',$total_coupon->cp_id)->update([
                "cp_use" => 1,
            ]);
            if($total_coupon_update == 0){
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "전체쿠폰 이미 사용되었거나 존재하지 않는쿠폰";
                $this->res['state'] = config('res_code.NO_DATA');

                return redirect($redirect_url."?message=".$this->res['msg']);
                
            }
            $coupon_log_insert = DB::table('coupon_use_log')->insert([
                "cp_id" => $total_coupon->cp_id,
                "cp_subject" => $total_coupon->cp_subject,
                "mb_id" => $order->s_uid,
                "od_id" => $order_id,
                "cp_price" => $total_coupon->cp_price,
                "cl_datetime" => DB::raw('now()'),
            ]);
            if($coupon_log_insert == 0){
                DB::rollBack();
                DB::table('cart')->where('order_id',$order_id)->update([
                    "cp_id" => null,
                    "cp_price" => 0
                ]);
                $this->res['query'] = null;
                $this->res['msg'] = "전체쿠폰 사용이력 작성 실패";
                $this->res['state'] = config('res_code.NO_DATA');

                return redirect($redirect_url."?message=".$this->res['msg']);
                
            }
        }
        //주문승인
        $url = $PCD_PAY_COFURL;

        $data = array(
            "PCD_CST_ID" => $PCD_CST_ID,
            "PCD_CUST_KEY" => $PCD_CUST_KEY,
            "PCD_AUTH_KEY" => $PCD_AUTH_KEY,
            "PCD_PAY_REQKEY" => $PCD_PAY_REQKEY,
            "PCD_PAYER_ID" => $PCD_PAYER_ID
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);

        if($result->PCD_PAY_RST != 'success'){
            DB::rollBack();
            DB::table('cart')->where('order_id',$order_id)->update([
                "cp_id" => null,
                "cp_price" => 0
            ]);
            $this->res['query'] = null;
            $this->res['msg'] = $result->PCD_PAY_MSG;
            $this->res['state'] = config('res_code.API_ERR');


            return redirect($redirect_url."?message=".$this->res['msg']);
            
        }
        $insert_personalpay = DB::table('personalpay')->insert([
            'order_id' => $PCD_PAY_OID,
            'uid' => $uid,
            'pp_method' => 'pay',
            'pp_resultcode' => $PCD_PAY_RST,
            'pp_name' => $user->name,
            'pp_email' => $PCD_PAYER_EMAIL,
            'pp_hp' => $user->mobile_number,
            'pp_content' => $PCD_PAY_GOODS,
            'pp_price' => $PCD_PAY_TOTAL,
            'pp_pg' => "PAYPLE",
            'pp_tno' => $PCD_PAYER_ID,
            'pp_receipt_price' => $PCD_PAY_TOTAL,
            'pp_settle_case' => $PCD_PAY_TYPE,
            'pp_deposit_name' => $user->name,
            'pp_receipt_time' => $PCD_PAY_TIME,
            'pp_receipt_ip' => $_SERVER["REMOTE_ADDR"],
            'pp_ip' => $_SERVER["REMOTE_ADDR"],
            'created_at' => DB::raw("now()"),
            'updated_at' => DB::raw("now()"),
            'pp_result' => json_encode($result),
        ]);
        

        $update_pg_result = DB::table('order')->where('order_id',$order_id)->update([
            "od_status" => "order_apply",
            "hope_date" => date( 'Y-m-d',strtotime("+".$days." day")),
            "pg_result" => json_encode($result),
            "settle_case" => $PCD_PAY_TYPE,
            "receipt_time" => $PCD_PAY_TIME,
            "deleted" => 0,
            "misu" => 0,
            "created_at" => DB::raw('now()'),
        ]);

        DB::commit();

        if($order_count > 1){
            $txt = "주문하신 (".$item_name.") 외 ".bcsub($order_count,1,0)."개를 결제하셨습니다.";    
        }else{
            $txt = "주문하신 (".$item_name.") 을 결제하셨습니다.";    
        }
        
        $sms = Coolsms::send_sms($user->mobile_number, $txt);

        $this->res['query'] = $PCD_PAY_OID;
        $this->res['msg'] = $PCD_PAY_MSG;
        $this->res['state'] = config('res_code.OK');

        return redirect($redirect_url."?order_id=".$PCD_PAY_OID."&message=".$this->res['msg']."&state=".$this->res['state']);
        
    }
}
