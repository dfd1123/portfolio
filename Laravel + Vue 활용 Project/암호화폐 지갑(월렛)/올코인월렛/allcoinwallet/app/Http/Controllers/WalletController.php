<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\Wallet;
use Facades\App\Classes\Settings;
use Facades\App\Classes\Secure;

use App\User;

use DB;
use Auth;
use Input;

class WalletController extends Controller
{
    public function info($coin_type)
    {
        $symbol = strtolower($coin_type);
        $info = Wallet::info(Auth::user()->id);
        $coin = DB::table('btc_coins')->where('api', $symbol)->first();

        if($coin->cointype == 'token' || $coin->api == 'eth') {
            Wallet::refresh_erc_eth_balance();
        }

        $balance = Wallet::balance(Auth::user()->id, $symbol);
        $address = Wallet::get_user_address_allcoin(Auth::user()->id, $symbol);
        $active = $coin->active;

        $response = [
            'symbol' => $symbol,
            'balance' => $balance,
            'address' => $address,
            'active' => $active
        ];

        return response()->json($response);
    }

    public function history()
    {
        $symbol = strtolower(Input::get('symbol'));
        $days = Input::get('days');

        $transactions = Wallet::transaction(Auth::user()->username, $symbol, $days, 0, 200); //최대 200개 제한

        return response()->json($transactions);
    }

    public function asset()
    {
        $krw_balance = Wallet::balance(Auth::user()->id, 'krw'); //보유 원화

        $coins = DB::table('btc_coins')->where('active',1)->orderBy('market','asc')->get(); //보유 코인

        $total_holding = '0'; //총 코인 보유자산 (원화)
        $total_buying = '0'; //총 코인 매수금액
        $total_eval_revenue = '0'; //총 코인 평가수익 (원화)
        $total_eval_percent = '0'; //총 코인 평가수익률

        foreach($coins as $coin){
            $coin_balance = Wallet::balance(Auth::user()->id, $coin->api); //코인별 보유잔액

            if($coin->cointype == 'cash' && $coin->api == 'krw'){
                $balance_krw = bcadd($coin_balance, 0, 0); //원화 보유잔액 (소수점 제거)
                continue;
            }

            $coin_buy_amt = Wallet::buy_amt(Auth::user()->id, $coin->symbol); //코인별 총매수량 (원화만)
            $coin_buy_total = Wallet::buy_total(Auth::user()->id, $coin->symbol); //코인별 총 (가격 * 양) (원화만)

            if($coin_buy_amt != 0){ //코인별 총매수량이 0일때 0으로 대체
                $coin_buy_avg = bcdiv($coin_buy_total, $coin_buy_amt, 8); //코인별 매수 평균
            }else{
                $coin_buy_avg = 0;
            }

            $coin_buying_price = bcmul($coin_balance, $coin_buy_avg, 8); // 코인 보유 양 * 매수평균 가격 = 매수금액
            $coin_balance_price = bcmul($coin_balance, $coin->last_coinmarketcap_price_krw, 8); // 코인 보유 양 * 해당코인 가격(코인마켓캡시세) = 평가금액

            if($coin_buying_price != 0){
                $coin_buy_percent = bcmul(bcdiv(bcsub($coin_balance_price, $coin_buying_price, 8), $coin_buying_price, 2),"100", 2); //코인별 평가손익
            }else{
                $coin_buy_percent = 0;
            }

            $total_buying = bcadd($total_buying, $coin_buying_price, 8);
            $total_holding = bcadd($total_holding, $coin_balance_price, 8);
        }

        if($total_buying != 0){
            $total_eval_revenue = bcsub($total_holding, $total_buying, 0); //총 원화 평가수익 (소수점 제거)
            $total_eval_percent = bcmul(bcdiv($total_eval_revenue, $total_buying, 4),"100",2); //총 평가수익률
        }

        $total_asset = bcadd($total_holding, $balance_krw, 0); //총 보유자산

        $krw = DB::table('btc_coins')->where('api', 'krw')->where('cointype', 'cash')->first();

        $response = [
            'cash_asset' => number_format($balance_krw),
            'total_revenue' => number_format($total_eval_revenue),
            'total_revenue_percent' => number_format($total_eval_percent),
            'total_asset' => number_format($total_asset),
            'cash_withdraw_fee' => number_format($krw->send_fee)
        ];

        return response()->json($response);
    }

    public function user_address($coin_type, $user_id)
    {
        $symbol = strtolower($coin_type);
        $info = Wallet::info($user_id);

        $address = $info->{"address_$symbol"};

        return response()->json($address);
    }

    public function wallet_coins()
    {
        $info = Wallet::info(Auth::user()->id);

        $coins = DB::table('btc_coins')->where('active', 1)->where('cointype', '<>', 'cash')->orderBy('market','asc')->get();

        $response = [];
        foreach($coins as $coin){
            $coin_balance = Wallet::balance(Auth::user()->id, $coin->api); //코인별 보유잔액

            array_push($response, [
                'symbol' => $coin->api,
                'balance' => $coin_balance
            ]);
        }

        return response()->json($response);
    }

    public function send(Request $request)
    {

        $symbol = $request->symbol; // BTC 같은 심볼
        $amt = $request->amt; // 보낼 수량
        $address = $request->address; // 보내는 주소
        $uid = Auth::user()->id;
        $username = Auth::user()->username;

        if($symbol == 'USD'){
            $check_address_symbol = "USDC";
        }else{
            $check_address_symbol = $symbol;
        }
        // 전송전 주소값 체크
        $Settings = Settings::Settings();

        if(!Wallet::check_address($symbol,$address)){
            $response = array(
                "state" => "check_address",
            );
            return response()->json(['error' => 'check_address'], 422);
        }


        // 전송전 출금한도 체크
        $coin = DB::table('btc_coins')->where('symbol',$symbol)->first();

        $withdraw_limit_amt = Wallet::check_withdraw_amount($symbol);
        info(Secure::secure_short_verified());
        info($withdraw_limit_amt);

        //출금한도 끝

        $coin_balance_available = Wallet::get_user_balance_allcoin($uid, $coin->api); //해당코인 사용가능한 보유잔액
        $send_fee = $coin->send_fee;

        if($coin->cointype == 'cash'){
            $decimal = 0;
        }else{
            $decimal = 8;
        }


        if($withdraw_limit_amt < $coin_balance_available){
            $max_amount = bcsub($withdraw_limit_amt , $send_fee,$decimal);
        }else{
            $max_amount = bcsub($coin_balance_available , $send_fee,$decimal);
        }
        if($amt > $max_amount){
            $response = array(
                "state" => "over_balance",
            );

            return response()->json(['error' => 'over_balance'], 422);
        }
        if($amt <= $send_fee){
            $response = array(
                "state" => "under_fee",
            );

            return response()->json(['error' => 'under_fee'], 422);
        }

        //주소가 내부인지 외부인지 구별
        $internal_external = DB::table('btc_users_addresses')->where('address_'.strtolower($symbol),$address)->first();

        if($internal_external == null){ // null 이면 외부로 출금 아니면 내부로 출금
            DB::table('btc_coin_send_request')->insert([ //출금자 출금 요청정보 삽입
                'cointype' => $symbol,
                'coin_category' => $coin->cointype,
                'type' => 'withdraw',
                'send_type' => 'external',
                'sender_userid' => $username,
                'receiver_address' => $address,
                'req_amount' => $amt,
                'send_fee' => $send_fee,
                'total_amt' => bcadd($amt,$send_fee,8),
                'status' => 'withdraw_request',
                'tx_id' => '',
                'created' => time(),
                'created_dt' => DB::raw('now()'),
                'updated' => time(),
                'updated_dt' => DB::raw('now()'),
                'price_krw' => $coin->last_trade_price_krw,
            ]);


            DB::table('btc_users_addresses')->where('uid',$uid)->update([ //출금자 출금할 금액 거래대기
                'pending_received_balance_'.strtolower($symbol) => DB::raw('pending_received_balance_'.strtolower($symbol).' - '.bcadd($amt,$send_fee,8)),
            ]);

            return response()->json(null, 200);
        }else{

            $tx_id = 'internal_transfer_'.$amt.'_'.$username.'_'.$internal_external->label.'_'.substr(md5(rand()),0,7); // 내부 tx_id 생성

            if(bcmul($amt , $coin->price_krw, 8) > 1000000){ // 100만원 이상일경우 관리자 승인 받고 안받고
                DB::table('btc_coin_send_request')->insert([ // 출금자 출금 요청정보 삽입
                    'cointype' => $symbol,
                    'coin_category' => $coin->cointype,
                    'type' => 'withdraw',
                    'send_type' => 'internal',

                    'sender_userid' => $username,
                    'receiver_address' => $address,
                    'req_amount' => $amt,
                    'send_fee' => $send_fee,
                    'total_amt' => bcadd($amt,$send_fee,8),
                    'status' => 'withdraw_request',
                    'tx_id' => '',
                    'created' => time(),
                    'created_dt' => DB::raw('now()'),
                    'updated' => time(),
                    'updated_dt' => DB::raw('now()'),
                    'price_krw' => $coin->last_trade_price_krw,
                ]);


                DB::table('btc_users_addresses')->where('uid',$uid)->update([ //출금자 출금할 금액 거래대기
                    'pending_received_balance_'.strtolower($symbol) => DB::raw('pending_received_balance_'.strtolower($symbol).' - '.bcadd($amt,$send_fee,8)),
                ]);

                return response()->json(null, 200);
            }else{
                DB::table('btc_coin_send_request')->insert([ // 출금자 트랜잭션 db 삽입
                    'cointype' => $symbol,
                    'coin_category' => $coin->cointype,
                    'type' => 'withdraw',
                    'send_type' => 'internal',

                    'sender_userid' => $username,
                    'receiver_address' => $address,
                    'req_amount' => $amt,
                    'send_fee' => $send_fee,
                    'total_amt' => bcadd($amt,$send_fee,8),
                    'status' => 'withdraw_complete',
                    'tx_id' => $tx_id,
                    'created' => time(),
                    'created_dt' => DB::raw('now()'),
                    'updated' => time(),
                    'updated_dt' => DB::raw('now()'),
                    'price_krw' => $coin->last_trade_price_krw,
                ]);

                DB::table('btc_transaction')->insert([  // 출금자 트랜잭션 db 삽입
                    'cointxid' => strtolower($symbol)."_".$tx_id."_send",
                    'cointype' => strtolower($symbol),

                    'account' => $username,
                    'address' => $address,
                    'category' => 'send',
                    'amount' => $amt,
                    'confirmations' => 999,
                    'txid' => $tx_id,
                    'normtxid' => '',
                    'tr_time' => time(),
                    'timereceived' => time(),
                    'processed' => 'y',
                    'created_dt' => DB::raw('now()'),
                ]);

                DB::table('btc_transaction')->insert([  // 입금자 트랜잭션 db 삽입
                    'cointxid' => strtolower($symbol)."_".$tx_id."_receive",
                    'cointype' => strtolower($symbol),
                    'account' => $internal_external->label,
                    'address' => $address,
                    'category' => 'receive',
                    'amount' => $amt,
                    'confirmations' => 999,
                    'txid' => $tx_id,
                    'normtxid' => '',
                    'tr_time' => time(),
                    'timereceived' => time(),
                    'processed' => 'y',
                    'created_dt' => DB::raw('now()'),
                ]);


                DB::table('btc_users_addresses')->where('uid',$uid)->decrement('available_balance_'.strtolower($symbol),bcadd($amt,$send_fee,8)); // 출금자 코인 차감

                DB::table('btc_users_addresses')->where('uid',$internal_external->uid)->increment('available_balance_'.strtolower($symbol),$amt); // 입금자 코인 더함

                //Admin::alarm_withdraw_confirm($username, $symbol, $address, $amt); // 출금 알림 보내기 나중에 푸시알림으로 대체해야함

                return response()->json(null, 200);
            }
        }
        return response()->json(['error' => 'not_cash_coin'], 422);
    }

    public function verify_address(Request $request)
    {
        $symbol = strtolower($request->symbol);
        $address = $request->address;
        if (Wallet::check_address($symbol, $address)) {
            return response()->json(null, 200);
        } else {
            return response()->json(['error' => 'invalid address'], 422);
        }
    }

    public function cash_deposite(Request $request){
        if(Wallet::krw_requested_or_not()){
            return response()->json(['error' => 'already_one_transaction'], 422);
        }else{
            $amount = $request->amount; // 입금한 금액

            $Settings = Settings::Settings(); // 마켓정보

            $user_info = DB::table('users')->where('id',Auth::user()->id)->first(); // 유저정보
            $user_addresses = DB::table('btc_users_addresses')->where('uid',Auth::user()->id)->first(); //krw 잔액 체크
            $user_security_info = DB::table('btc_security_lv')->where('uid',Auth::user()->id)->first(); //보안레벨의 은행정보 확인

            $memo = "입금계좌 : ( ".$user_security_info->account_bank." | ".$user_security_info->account_num." | ".$user_info->fullname." )";

            $check_status = DB::table('btc_krw_io')->insert([  // 입금자 트랜잭션 db 삽입
                'uid' => $user_info->id,
                'username' => $user_info->username,
                'type' => 'deposite',
                'status' => 'deposite_request',
                'plus' => $amount,
                'minus' => 0,
                'amount' => $amount,
                'balance_before' => $user_addresses->available_balance_krw,
                'balance_after' => bcadd($user_addresses->available_balance_krw, $amount, 0),
                'memo' => $memo,
                'created' => time(),
                'bankname' => $user_security_info->account_bank,
                'bankaccount' => $user_security_info->account_num,
                'bankowner' => $user_info->fullname,
                'rand_plus' => 0,
                'web_type' => $Settings->id,
            ]);

            if($check_status > 0){

                return response()->json(null, 200);

            }else{
                return response()->json(['error' => 'Network_error'], 422);
            }
        }

        return response()->json($response);
    }

    public function cash_withdraw(Request $request){
        if(Wallet::krw_requested_or_not()){
            return response()->json(['error' => 'already_one_transaction'], 422); //이미 대기중인 현금 입출금 내역이 있음
        }else{
            $amount = $request->amount; // 입금한 금액
            $symbol = strtoupper($request->symbol); // KRW 일거지만 넣어줘야됨

            $Settings = Settings::Settings(); // 마켓정보

            $decimal = 0;
            $first_history_created = Wallet::first_krw_io_history();

            if($first_history_created == null){
                $withdraw_limit_amt = 1000000;
            }else{
                $month3_ago = strtotime("-3 month", time());
                $month1_ago = strtotime("-1 month", time());

                if($first_history_created < $month3_ago){
                    $withdraw_limit_amt = 30000000;
                }else if($first_history_created < $month1_ago){
                    $withdraw_limit_amt = 10000000;
                }else{
                    $withdraw_limit_amt = 1000000;
                }
            }



            $user_info = DB::table('users')->where('id',Auth::user()->id)->first(); // 유저정보
            $user_addresses = DB::table('btc_users_addresses')->where('uid',Auth::user()->id)->first(); //krw 잔액 체크
            $user_security_info = DB::table('btc_security_lv')->where('uid',Auth::user()->id)->first(); //보안레벨의 은행정보 확인

            if($amount > bcadd($user_addresses->available_balance_krw + $user_addresses->pending_received_balance_krw,0) || $amount <= 0){
                return response()->json(['error' => 'over_balance'], 422);
            }else if($amount > $withdraw_limit_amt){
                return response()->json(['error' => 'over_limit'], 422);
            }else{
                $coin = DB::table('btc_coins')->where('symbol',$symbol)->first();
                $memo = "출금계좌 : ( ".$user_security_info->account_bank." | ".$user_security_info->account_num." | ".$user_info->fullname." )";

                DB::table('btc_users_addresses')->where('uid',Auth::id())->decrement('pending_received_balance_'.strtolower($symbol), bcadd($amount,$coin->send_fee,0));

                $check_status = DB::table('btc_krw_io')->insert([  // 입금자 트랜잭션 db 삽입
                    'uid' => $user_info->id,
                    'username' => $user_info->username,
                    'type' => 'withdraw',
                    'status' => 'withdraw_request',
                    'plus' => $amount,
                    'minus' => $coin->send_fee,
                    'amount' => bcadd($amount,$coin->send_fee,0),
                    'balance_before' => $user_addresses->available_balance_krw,
                    'balance_after' => bcsub($user_addresses->available_balance_krw, bcadd($amount,$coin->send_fee,0), 0),
                    'memo' => $memo,
                    'created' => time(),
                    'bankname' => $user_security_info->account_bank,
                    'bankaccount' => $user_security_info->account_num,
                    'bankowner' => $user_info->fullname,
                    'rand_plus' => 0,
                    'web_type' => $Settings->id,
                ]);

                if($check_status > 0){
                    return response()->json(null, 200);
                }else{
                    return response()->json(['error' => 'Network_error'], 422);
                }
            }
        }

        return response()->json($response);
    }

    public function cash_cancel(Request $request){
        $id = $request->id; //btc_send_coin_request 의 id
        $type = $request->type; //0이면 입금, 1이면 출금

        if($type == 0){ //입글일대
            $check_status = DB::table('btc_krw_io')->where('id',$id)->update([
                'status' => 'deposite_cancel',
            ]);
        }else{ //출금일때
            $this_cash_info = DB::table('btc_krw_io')->where('id',$id)->first();

            DB::table('btc_users_addresses')->where('uid',Auth::id())->increment('pending_received_balance_krw', $this_cash_info->amount);
            $check_status = DB::table('btc_krw_io')->where('id',$id)->update([
                'status' => 'withdraw_cancel',
            ]);
        }

        if($check_status > 0){
            $response = array(
                "status" => "success",
            );
        }else{
            $response = array(
                "status" => "error",
            );
        }

        return response()->json($response);
    }

    public function cash_history(Request $request){
        $username = Auth::user()->username;
        $send_transaction = DB::table("btc_krw_io")
            ->select(
                "id",
                "type",
                "amount",
                "status",
                DB::raw("FROM_UNIXTIME(created) as updated"),
                DB::raw("'KRW' as cointype ")
            )
            ->where('uid',Auth::id())->orderBy('id','desc')->get();

        return response()->json($send_transaction);
    }

    public function buy_estimate(Request $request){
        $coin = $request->coin;
        $amount = $request->amount;

        $url = "http://localhost:9600/orderbook"; // Where you want to post data
        $postdata = array(
            'coin' => strtoupper($coin),
            'type' => 'buy',
            'amount' => $amount
        );

        $ch = curl_init();                    // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle

        $obj = json_decode($output);

        return response()->json($obj);
    }

    public function buy_execute(Request $request){
        $coin = strtolower($request->coin);
        $amount = $request->amount;

        //값 검증
        if(!is_numeric($amount) && (float) $amount > 0) {
            return response()->json(["status" => 'error', "error" => 'Invalid Amount'], 422);
        }

        // 사용자 KRW 검증
        $balance = Wallet::balance(Auth::user()->id, 'krw');
        if(bccomp($amount, $balance, 0) == 1) { //$amount가 $balance보다 클 때
            return response()->json(["status" => 'error', "error" => 'Invalid Request'], 422);
        }

        // 주문 요청
        $url = "http://localhost:9601/order"; // Where you want to post data
        $postdata = array(
            'type' => 'buy',
            'coin' => strtoupper($coin),
            'amount' => $amount,
            'username' => Auth::user()->username
        );

        $ch = curl_init();                    // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle

        $obj = json_decode($output);
        if($obj->status == 'error') {
            return response()->json($obj, 500);
        } else if($obj->status == 'rejected') {
            return response()->json($obj, 422);
        } else if($obj->status == 'accepted') {
            return response()->json($obj);
        }
    }

    public function sell_estimate(Request $request){
        $coin = $request->coin;
        $amount = $request->amount;

        $url = "http://localhost:9600/orderbook"; // Where you want to post data
        $postdata = array(
            'coin' => strtoupper($coin),
            'type' => 'sell',
            'amount' => $amount
        );

        $ch = curl_init();                    // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle

        $obj = json_decode($output);

        return response()->json($obj);
    }

    public function sell_execute(Request $request){
        $coin = strtolower($request->coin);
        $amount = $request->amount;

        //값 검증
        if(!is_numeric($amount) && (float) $amount > 0) {
            return response()->json(["status" => 'error', "error" => 'Invalid Amount'], 422);
        }

        // 사용자 코인 검증
        $balance = Wallet::balance(Auth::user()->id, $coin);
        if(bccomp($amount, $balance, 8) == 1) { //$amount가 $balance보다 클 때
            return response()->json(["status" => 'error', "error" => 'Invalid Request'], 422);
        }

        // 주문 요청
        $url = "http://localhost:9601/order"; // Where you want to post data
        $postdata = array(
            'type' => 'sell',
            'coin' => strtoupper($coin),
            'amount' => $amount,
            'username' => Auth::user()->username
        );

        $ch = curl_init();                    // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec ($ch); // Execute
        curl_close ($ch); // Close cURL handle

        $obj = json_decode($output);
        if($obj->status == 'error') {
            return response()->json($obj, 500);
        } else if($obj->status == 'rejected') {
            return response()->json($obj, 422);
        } else if($obj->status == 'accepted') {
            return response()->json($obj);
        }
    }

    public function pay(Request $request){
        $coin = strtolower($request->coin);
        $amount = $request->amount;
        $pay_order_id = $request->pay_order_id;

        // 값 검증
        if(!is_numeric($amount) && (float) $amount > 0) {
            return response()->json(["status" => 'error', "error" => 'Invalid Amount'], 422);
        }

        // 사용자 코인 검증
        $balance = Wallet::balance(Auth::user()->id, $coin);
        if(bccomp($amount, $balance, 8) == 1) { //$amount가 $balance보다 클 때
            return response()->json(["status" => 'error', "error" => 'Insufficient Balance'], 422);
        }

        // 사용자 지갑 정보
        $info = Wallet::info(Auth::user()->id);
        $address = $info->{"address_$coin"};

        // 요청중 상태인 요청 업데이트
        $result = DB::table("btc_payment")->where('id', $pay_order_id)->where('status', 'requested')->update([
            'status' => 'complete',
            'buyer_address' => $address,
            'buyer_username' => Auth::user()->username,
            'buyer_fullname' => Auth::user()->fullname,
            'updated_dt' => DB::raw('now()')
        ]);

        // 해당 요청이 존재하지 않음
        if($result == 0) {
            return response()->json(["status" => 'error', "error" => 'Order Not Found'], 422);
        }

        // 출금자 트랜잭션 db 삽입
        $tx_id = 'pay_execute_send'.$amount.'_'.strtolower($coin).'_'.Auth::user()->username.'_aicdss_1566196438irfoLLgZ3NHFsEDOkXI3_'.substr(md5(rand()),0,7); // 내부 tx_id 생성
        DB::table('btc_transaction')->insert([
            'cointxid' => strtolower($coin)."_".$tx_id."_send",
            'cointype' => strtolower($coin),
            'account' => Auth::user()->username,
            'address' => $address,
            'category' => 'send',
            'amount' => $amount,
            'confirmations' => 999,
            'txid' => $tx_id,
            'normtxid' => '',
            'tr_time' => time(),
            'timereceived' => time(),
            'processed' => 'y',
            'created_dt' => DB::raw('now()'),
        ]);

        $tx_id = 'pay_execute_receive'.$amount.'_'.strtolower($coin).'_'.Auth::user()->username.'_aicdss_1566196438irfoLLgZ3NHFsEDOkXI3_'.substr(md5(rand()),0,7); // 내부 tx_id 생성
        DB::table('btc_transaction')->insert([
            'cointxid' => strtolower($coin)."_".$tx_id."_receive",
            'cointype' => strtolower($coin),
            'account' => 'aicdss_1566196438irfoLLgZ3NHFsEDOkXI3',
            'address' => $address,
            'category' => 'send',
            'amount' => $amount,
            'confirmations' => 999,
            'txid' => $tx_id,
            'normtxid' => '',
            'tr_time' => time(),
            'timereceived' => time(),
            'processed' => 'y',
            'created_dt' => DB::raw('now()'),
        ]);

        // 사용자 잔고 감액
        DB::table('btc_users_addresses')->where('uid', Auth::user()->id)->decrement("available_balance_$coin", $amount);

        // 관리자 계정에 결제액 추가 aicdss@naver.com (aicdss_1566196438irfoLLgZ3NHFsEDOkXI3)
        DB::table('btc_users_addresses')->where('label', 'aicdss_1566196438irfoLLgZ3NHFsEDOkXI3')->increment("available_balance_$coin", $amount);

        // 받은 amount 타거래소 자동판매
        $url = "http://localhost:9601/order";
        $postdata = array(
            'type' => 'sell',
            'coin' => strtoupper($coin),
            'amount' => $amount,
            'username' => 'aicdss_1566196438irfoLLgZ3NHFsEDOkXI3'
        );

        $ch = curl_init(); // Initiate cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Tell cURL you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // Define what you want to post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($ch); // Execute
        curl_close($ch); // Close cURL handle

        $obj = json_decode($output);
        info('Allcoinpay sell order result: ' . $obj->status);

        return response()->json(null, 200);
    }
}

