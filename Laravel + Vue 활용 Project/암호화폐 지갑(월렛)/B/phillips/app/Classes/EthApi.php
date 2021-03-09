<?php

namespace App\Classes;

use GuzzleHttp\Client;
use DB;

class EthApi
{
    private $host;
    private $port;
    private $token;

    public function __construct()
    {
        $this->host = env('ETHER_IO_HOST', 'localhost');
        $this->port = env('ETHER_IO_PORT', 8080);
        $this->token = 'tlg';
    }

    // 새 주소 생성
    public function newAddress($user_id) // $user_id는 이메일
    {
        $client = new Client();
        $res = $client->get("http://$this->host:$this->port/newAddr/$user_id");

        return $res->getBody();
    }

    // 주소 유효성 검사
    public function isAddress($address)
    {
        $client = new Client();
        $res = $client->get("http://$this->host:$this->port/isAccount/$address");

        return $res->getBody() == 'true';
    }

    // 특정 유저의 주소 가져오기
    public function getAddress($user_id)
    {
        $info = DB::table('eth_io_account')
            ->select('address')
            ->where('user_id', $user_id)
            ->first();

        return $info->address;
    }

    // 특정 유저의 입출금 내역 불러오기
    public function listTransactions($user_id, $count, $skip)
    {
        $transactions = DB::table('eth_io_request')
            ->where('request_user_id', $user_id)
            ->whereIn('in_progress', [0, 1, 2])
            ->where('coin_kind', $this->token)
            ->orderBy('id', 'desc')
            ->skip($skip)
            ->limit($count)
            ->get();

        return $transactions;
    }

    // 유저 출금 요청
    public function newWithdrawRequest($request_user_id, $request_address, $request_amount)
    {
        DB::table('eth_io_request')
            ->insert([
                'in_progress' => 0,
                'request_type' => 'withdraw',
                'coin_kind' => $this->token,
                'request_user_id' => $request_user_id,
                'request_address' => $request_address,
                'request_amount' => $request_amount,
                'request_status' => 'withdraw_requested',
                'updated' => DB::raw('CURRENT_TIMESTAMP()')
            ]);

        return;
    }

    // 네트워크 트랜젝션이 아닌 입출금 기록
    public function addInfoRequest($request_user_id, $request_type, $request_amount, $request_status)
    {
        DB::table('eth_io_request')
            ->insert([
                'in_progress' => 2,
                'request_type' => $request_type,
                'coin_kind' => $this->token,
                'request_user_id' => $request_user_id,
                'request_address' => '',
                'request_amount' => $request_amount,
                'request_status' => $request_status,
                'updated' => DB::raw('CURRENT_TIMESTAMP()')
            ]);

        return;
    }

    // 네트워크 트랜젝션이 아닌 입출금 기록 (confirm_tx에 메세지 추가)
    public function addInfoMessageRequest($request_user_id, $request_type, $request_amount, $request_status, $info_message)
    {
        DB::table('eth_io_request')
            ->insert([
                'in_progress' => 2,
                'request_type' => $request_type,
                'coin_kind' => $this->token,
                'request_user_id' => $request_user_id,
                'request_address' => '',
                'request_amount' => $request_amount,
                'request_status' => $request_status,
                'confirm_tx' => $info_message,
                'updated' => DB::raw('CURRENT_TIMESTAMP()')
            ]);

        return;
    }
}
