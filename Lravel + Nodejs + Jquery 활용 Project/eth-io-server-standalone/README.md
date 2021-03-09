# eth-io-server-standalone

> WE ARE (NOT) ALL ALONE

이더리움 io 서버입니다.

>## 🛑[경고]
>**이 문서는 불완전 하므로 실제 사용시에는 커미터에게 충분한 설명을 요청하여 주의사항을 완전히 숙지 후 사용하여야 합니다.**

## 특징
+ 모든 요청을 순차적으로 하나씩 처리합니다.
+ 중앙형일 경우 이더, 토큰 부족 시 관리자 주소에 충전 될 때까지 계속 대기합니다.
+ 분산형일 경우 이더, 토큰 부족 시 중단되며 복구 절차 수행 전까지 해당 주소가 사용불가 할 수 있습니다.
+ 이더리움 가스비 2 ~ 50 사이에서 네트워크 상태에 따라 자동 조절. (중앙형일 경우 토큰 입금절차에 0.005ETH를 입출금)
+ infura 서비스에 연결하여 사용하는데 최적화 되어 있습니다.
+ 이더와 토큰을 소수점 8자리까지만 처리합니다.

## 사용절차 (우분투 리눅스 기준)
1. **/table**의 sql을 참고하여 mysql DB에 테이블 생성
2. DB의 코인 정보 설정
3. ```cp .env.example .env``` 명령어로 파일을 복사하여 **.env** 파일 생성 후 설정입력
4. 중앙형일 때 ```cp event.example.js event.js``` 명령어로 파일을 복사하여 **event.js** 파일 생성 후 입금, 출금 컨펌 후 콜백 작성
5. 의존성 설치
``` bash
# 해당 순서로 실행 (I wish you good luck)
sudo apt install build-essential
sudo npm install -g node-gyp
sudo npm install
```
6. process.env.NODE_ENV 값을 ```development``` 또는 ```production```으로 설정 후 *pm2*같은 프로세스 매니저로 배포 후 실행

## .env 파일 설정
+ 설정 변경 후 프로그램을 재시작해야 적용됨

| 설정명 | 설명 | 예시 | 기타 |
| ---------- | ---------- | ---------- | ---------- |
| PORT | 포트번호 | 8080 | |
| CENTRAL | 중앙형/분산형 선택 | true | 중앙형이면 true, 분산형이면 false |
| INFURA_MAINNET | infura 메인넷 주소 | mainnet.infura.io/v3/4a8c... | mainnet |
| INFURA_TESTNET | infura 테스트넷 주소 | ropsten.infura.io/v3/4a8... | ropsten |
| DB_HOST_LOCAL | DB 서버 주소 | localhost ||
| DB_USER_LOCAL | DB 사용자 id | root ||
| DB_NAME_LOCAL | DB 스키마 이름 | eth_io_server ||
| DB_PASS_LOCAL | DB 비밀번호 | ******** ||
| ADMIN_ADDRESS | 관리자 주소 | 0x0384c89... | 이더리움 주소|
| ADMIN_PRIVATE | 관리자 개인키 | 0x38f4c0a... | 🛑[경고] **이 값은 절대로 타인이나 외부에 노출되면 안됩니다.** |
| MINIMUM_ETHER_AMOUNT_TO_DEPOSIT | 중앙형에서 입금처리 가능한 최소 이더 액수 | 0.01 | 값이 사용되는 가스비보다 적으면 오류발생 |
| MINIMUM_TOKEN_AMOUNT_TO_DEPOSIT | 중앙형에서 입금처리 가능한 최소 토큰 액수 | 0.00000001 | 입금처리 시 관리자 주소에 이더 필요 |
| WITHDRAW_CONFIRM_COUNT | 출금 처리 시 컨펌 횟수 | 1 ||
| DEPOSIT_CONFIRM_COUNT | 입금 처리 시 컨펌 횟수 | 6 ||
| BALANCE_CHECK_INTERVAL | 중앙형에서 입금처리 시 잔액 체크 간격 | 900 | 초단위 |
| REQUEST_CHECK_INTERVAL | 입출금 요청처리 시 DB에서 요청 체크 간격 | 15 | 초단위 |
| CONFIRM_CHECK_INTERVAL | 입출금 요청처리 처리 후 컨펌 횟수 체크 간격 | 60 | 초단위 |
| BALANCE_CHECK_CALL_TIMEOUT | 잔액 체크 시 요청 타임아웃 | 15 | 초단위. 🛑[경고] **버그 때문에 반드시 적절한 값을 입력해야 함** |
| BALANCE_CHECK_CALL_TIMEWAIT | 잔액 체크 시 요청이 완료되는 시간 지연 | 1 | 초단위. 1초면 요청 시작부터 끝까지 최소 1초 경과 |

## 자주 볼 수 있는 에러들...
```
Error: Node error: {"code":-32000,"message":"missing trie node b12bae657657b1981...
```
정상입니다.
```
Error: Web3 certain request timeout
```
정상입니다.
```
Error: Node error: {"code":-32603,"message":"request failed or timed out"}
```
대부분의 경우에 정상입니다.
```
Error: Node error: {"code":-32000,"message":"insufficient funds for gas * price + value"}
```
이더 잔액 부족. 이더를 충전할 때까지 계속 대기합니다.
```
Error: insufficient funds for token transfer
```
토큰 잔액 부족. 토큰을 충전할 때까지 계속 대기합니다.
```
Error: insufficient funds for transaction with fee
```
이더 잔액 부족. 수수료를 포함하면 이더 출금이 불가능할 정도로 액수가 적습니다.

> 처음 보는 에러가 발생했을 경우 커미터에게 빠르게 연락바랍니다.

## 출금 요청
> Laravel ORM 기준
``` php
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
            'updated' => DB::raw('NOW()')
        ]);

    return;
}
```

