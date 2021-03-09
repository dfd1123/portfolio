<?php

namespace App\Classes;

use Facades\App\Classes\Settings;
use Facades\App\Classes\Secure;
use Facades\App\Classes\EthApi;

use DB;
use Auth;

class Wallet
{
    public function info($uid)
    {
        return DB::table('btc_users_addresses')->where('uid', $uid)->first();
    }

    // 해당 지갑 거래이력
    public function transaction($username, $symbol, $days, $offset = 0, $limit = PHP_INT_MAX)
    {
        $symbol = strtolower($symbol);

        $transactions = DB::select("
            SELECT
                *,
                (SELECT fullname FROM users u WHERE u.username = b.account) as from_fullname,
                (SELECT fullname FROM users u WHERE u.username = b.address) as to_fullname
            FROM btc_transaction b
            WHERE b.account = ?
            AND cointype = ?
            AND created_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ORDER BY created_dt DESC, id DESC
            LIMIT ?
            OFFSET ?
        ", [$username, $symbol, $days, $limit, $offset]);

        $response = [];
        foreach ($transactions as $transaction) {
            $account_name = $transaction->from_fullname == null ? '__external' : $transaction->from_fullname;
            $address_name = $transaction->to_fullname == null ? '__external' : $transaction->to_fullname;

            if ($transaction->category == 'send') {
                $status = 'send';
                $sign = '-';
                $from = $account_name;
                $to = $address_name;
            } elseif ($transaction->category == 'receive') {
                $status = 'receive';
                $sign = '+';
                $from = $address_name;
                $to = $account_name;
            } elseif ($transaction->category == 'trade') {
                if ($transaction->account == $transaction->address) {
                    $status = 'self';
                    $sign = '';
                    $from = $account_name;
                    $to = $address_name;
                } elseif ($username == $transaction->account) {
                    $status = 'buy';
                    $sign = '+';
                    $from = $address_name;
                    $to = $account_name;
                } elseif ($username == $transaction->address) {
                    $status = 'sell';
                    $sign = '-';
                    $from = $address_name;
                    $to = $account_name;
                }
            }

            array_push($response, [
                'id' => $transaction->id,
                'symbol' => $symbol,
                'from' => $from,
                'to' => $to,
                'sign' => $sign,
                'amount' => $transaction->amount,
                'date' => date("Y-m-d h:i:s", $transaction->timereceived),
                'status' => $status,
            ]);
        }

        return $response;
    }

    // 해당 코인 잔액
    public function balance($uid, $coin_type)
    {
        $coin_type = strtolower($coin_type);
        $wallet = $this->info($uid);
        $decimal = $coin_type == 'krw' ? 0 : 8;
        return bcadd($wallet->{"available_balance_$coin_type"}, $wallet->{"pending_received_balance_$coin_type"}, $decimal);
    }

    //거래내역 코인별 구매당시 매수 수량 합 (원화만)
    public function buy_amt($uid, $symbol)
    {
        $sum_amt = DB::table('btc_trades_COIN_btc')->where('currency', 'KRW')->where('buyer_uid', $uid)->where('cointype', strtolower($symbol))->sum(DB::raw("contract_coin_amt"));
        return $sum_amt;
    }

    //거래내역 코인별 (매수수량 * 구매당시) 시세 합 (원화만)
    public function buy_total($uid, $symbol)
    {
        $sum_total = DB::table('btc_trades_COIN_btc')->where('currency', 'KRW')->where('buyer_uid', $uid)->where('cointype', strtolower($symbol))->sum(DB::raw("contract_coin_amt * buy_coin_price"));
        return $sum_total;
    }

    public function check_address($symbol, $address)
    {
        return EthApi::isAddress($address);
    }

    public function check_withdraw_amount($symbol)
    {
        // 전송전 출금한도 체크
        $coin = DB::table('btc_coins')->where('symbol', $symbol)->first();

        if ($symbol != "KRW" && $symbol != "USD") {
            $decimal = 8;
            $security_lv = Secure::secure_short_verified();
            $Settings = Settings::Settings();

            //보안 레벨 별 최대 출금금액 계산
            if ($security_lv == 0) {
                $lv_amt = 0;
            } elseif ($security_lv == 1) {
                $lv_amt = $Settings->lv1_max;
            } elseif ($security_lv == 2 || $security_lv == 2.5) {
                $lv_amt = $Settings->lv2_max;
            } elseif ($security_lv == 3 || $security_lv == 3.5) {
                $lv_amt = $Settings->lv3_max;
            } elseif ($security_lv == 4) {
                $lv_amt = $Settings->lv4_max;
            } elseif ($security_lv == 5) {
                $lv_amt = $Settings->lv_max_max;
            }
            if ($symbol == "USD") {
                $withdraw_limit_amt = $lv_amt;
            } else {
                $withdraw_limit_amt = bcdiv($lv_amt, max($coin->price_krw, 1), 8);
            }
        } else {
            $decimal = 0;
            $first_history_created = $this->first_krw_io_history();

            if ($first_history_created == null) {
                $withdraw_limit_amt = 1000000;
            } else {
                $month3_ago = strtotime("-3 month", time());
                $month1_ago = strtotime("-1 month", time());

                if ($first_history_created < $month3_ago) {
                    $withdraw_limit_amt = 30000000;
                } elseif ($first_history_created < $month1_ago) {
                    $withdraw_limit_amt = 10000000;
                } else {
                    $withdraw_limit_amt = 1000000;
                }
            }
        }
        return $withdraw_limit_amt;
    }

    public function first_krw_io_history()
    {
        $cash = DB::table('btc_krw_io')->where('uid', Auth::user()->id)->orderBy('id', 'asc')->first();

        if (isset($cash)) {
            $created = $cash->created;
        } else {
            $created = null;
        }

        return $created;
    }

    public function krw_requested_or_not()
    {
        $count = DB::table('btc_krw_io')->whereIn('status', array('withdraw_request','deposite_request'))->where('uid', Auth::user()->id)->count();

        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function walletinfo($uid, $value)
    {
        $row = DB::table('btc_users_addresses')->where('uid', $uid)->first();
        return $row->{$value};
    }

    public function get_user_balance_allcoin($uid, $coin_type)
    {
        $balance = bcadd($this->walletinfo($uid, "available_balance_".$coin_type), $this->walletinfo($uid, "pending_received_balance_".$coin_type), 8);

        if ($balance == null || $balance == 'null' || $balance == null) {
            $balance = 0 ;
        }
        return $balance;
    }

    public function get_user_address_allcoin($uid, $coin_type)
    {
        $address = $this->walletinfo($uid, "address_".$coin_type);
        $username = Auth::user()->username;
        $coininfo = DB::table('btc_coins')->where('api', $coin_type)->first();
        $coin_category = $coininfo->cointype;

        if ($address == null) {
            if ($coin_category == 'coin') {
                $address = $this->create_new_address($username, $coin_type);
            } elseif ($coin_category == 'token') {
                $address = $this->get_erc20_address($username);
            }

            DB::table('btc_users_addresses')->where('uid', Auth::user()->id)->update([
                'address_'.$coin_type => $address,
            ]);
        }

        return $address;
    }

    public function get_erc20_address($username)
    {
        $eth_addr = $this->walletinfo(Auth::user()->id, "address_eth");

        if ($eth_addr === null || $eth_addr == 0) {
            // 이더리움 주소가 없는경우 이더리움 주소를 생성해주고, 이더리움 주소를 저장한다.
            $eth_addr = $this->create_new_address($username, 'eth');

            DB::table('btc_users_addresses')->where('uid', Auth::user()->id)->update([
                'address_eth' => $eth_addr,
            ]);
        }
        return $eth_addr;
    }

    private function create_new_address($username, $coin_type)
    {
        return EthApi::newAddress($username);
    }
}
