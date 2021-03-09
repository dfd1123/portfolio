/* eslint-disable no-await-in-loop */
/* eslint-disable no-restricted-syntax */
const express = require('express');
const Decimal = require('decimal.js');

const web3Init = require('./web3');
const { localDB, exchangeDB } = require('./db');
const activity = require('./activity');
const call = require('./call');
const tx = require('./tx');
const admin = require('./admin');

const app = express();

require('dotenv').config();

const MODE = process.env.NODE_ENV; // development or production
const PORT = process.env.PORT || 8080;
const MINIMUM_ETHER_AMOUNT_TO_DEPOSIT = process.env.MINIMUM_ETHER_AMOUNT_TO_DEPOSIT || 0.01; // 이더 입금 최소액수, 소수점 8자리 이하는 DB에 값 입력 시 무시됨. 값이 너무 작으면 수수료 에러
const MINIMUM_TOKEN_AMOUNT_TO_DEPOSIT = process.env.MINIMUM_TOKEN_AMOUNT_TO_DEPOSIT || 0.00000001; // 토큰 입금 최소액수, 소수점 8자리 이하는 DB에 값 입력 시 무시됨
const WITHDRAW_CONFIRM_COUNT = process.env.WITHDRAW_CONFIRM_COUNT || 1; // 출금 검증 컨펌 횟수
const DEPOSIT_CONFIRM_COUNT = process.env.DEPOSIT_CONFIRM_COUNT || 6; // 입금 검증 컨펌 회수

// 주소체크
app.get('/isAccount/:addr', (req, res) => {
  try {
    const address = req.params.addr;

    // 이더리움 주소 유효성 검사
    const web3 = web3Init();
    const isAddress = web3.utils.isAddress(address);

    // 유효성 검사 결과를 반환
    res.send(isAddress);
  } catch (err) {
    console.log(err);
    res.send('Error');
  }
});

// 주소생성
app.get('/newAddr/:userid', async (req, res) => {
  try {
    const userId = exchangeDB.escape(req.params.userid);

    // 이더리움 주소 생성
    const web3 = web3Init();
    const { address, privateKey } = web3.eth.accounts.create();

    // 새 주소 저장
    await call.addNewAddress({ userId, address, privateKey });

    console.log(`새 주소 생성 -> 아이디: ${userId}, 주소: ${address}`);

    // 생성된 주소 반환
    res.send(address);
  } catch (err) {
    console.log(err);
    res.send('Error');
  }
});

// 관리자 잔고 전부 조회
app.get('/adminBalances', async (req, res) => {
  try {
    const response = [];

    // 이더 잔고 조회
    const ethBalance = await tx.getAddressEthBalance(admin.adminAddress);
    const ethDecimal = 18;
    const ethAmount = new Decimal(ethBalance).div(10 ** ethDecimal);

    response.push({ coin: 'eth', amount: ethAmount });

    // 토큰 정보 조회
    const query1 = `
    SELECT
      c.api as coin_kind,
      c.token_contract_addr as contract_address,
      c.decimal_place as coin_decimals
    FROM btc_coins c
    WHERE c.cointype = 'token'
    AND c.active = 1
    ORDER BY c.id ASC
    `;

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const tokenInfos = await execute1;

    // 토큰 잔고 조회
    const tokenBalances = await tx.getAddressTokenBalances(
      admin.adminAddress,
      tokenInfos.map(tokenInfo => tokenInfo.contract_address),
    );

    for (const [contract, value] of Object.entries(tokenBalances)) {
      const token = tokenInfos.find(
        tokenInfo => tokenInfo.contract_address === contract,
      );
      const decimal = token.coin_decimals;
      const amount = new Decimal(value).div(10 ** decimal);

      response.push({ coin: token.coin_kind, amount });
    }

    // 조회 결과 반환
    res.send(JSON.stringify(response));
  } catch (err) {
    console.log(err);
    res.send('[]');
  }
});

async function scanWithdrawLoop() {
  try {
    // 이미 출금중인 요청건이 없을 때 거래소 테이블에서 출금 요청건을 가져옴
    const query1 = `
    SELECT *
    FROM btc_coin_send_request a
    WHERE NOT EXISTS (
        SELECT
          r.id
        FROM btc_coin_send_request r
        WHERE (r.coin_category = 'token' OR (r.coin_category = 'coin' AND r.cointype = 'ETH' ))
        AND r.type = 'withdraw'
        AND r.send_type = 'external'
        AND r.status = 'try_withdraw_request'
      )
    AND (a.coin_category = 'token' OR (a.coin_category = 'coin' AND a.cointype = 'ETH' ))
    AND a.type = 'withdraw'
    AND a.send_type = 'external'
    AND a.status = 'withdraw_request_confirm'
    ORDER BY a.id ASC
    LIMIT 1
    `;

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    const sendRequest = result1.length > 0 ? result1[0] : null;
    if (sendRequest === null) {
      // 이미 출금건을 처리중이거나 출금 요청 없음
      return;
    }

    // 선택된 출금 요청건을 진행 중 처리
    const query2 = `
    UPDATE
      btc_coin_send_request
    SET
      status='try_withdraw_request',
      updated=UNIX_TIMESTAMP(NOW()),
      updated_dt=NOW()
    WHERE
      id=${sendRequest.id}
    `;

    const execute2 = exchangeDB
      .promise()
      .query(query2)
      .then(data => data[0]);
    await execute2;

    // 새 출금 요청 추가
    await call.setNewWithdrawRequest({
      requestId: sendRequest.id,
      coinKind: sendRequest.cointype.toLowerCase(),
      requestUserId: sendRequest.sender_userid,
      requestAddress: sendRequest.receiver_address,
      requestAmount: sendRequest.req_amount,
    });
  } catch (err) {
    console.log(err);
  } finally {
    setTimeout(() => {
      scanWithdrawLoop();
    }, 1000 * 15);
  }
}
scanWithdrawLoop();

async function scanDepositLoop() {
  try {
    // 이더리움 망에서 새 입금 스캔
    // 대기중이나 진행중인 작업이 없는 주소만 조회
    const addressInfos = await call.getNotInProgressOrFailAddressInfos();
    if (addressInfos.length === 0) {
      return;
    }

    // 토큰 정보 조회
    const query1 = `
    SELECT
      c.api as coin_kind,
      c.token_contract_addr as contract_address,
      c.decimal_place as coin_decimals
    FROM btc_coins c
    WHERE c.cointype = 'token'
    AND c.active = 1
    ORDER BY c.id ASC
    `;

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const tokenInfos = await execute1;
    if (tokenInfos.length === 0) {
      return;
    }

    // 토큰 컨트랙트 주소
    const tokens = tokenInfos.map(tokenInfo => tokenInfo.contract_address);

    for (const addressInfo of addressInfos) {
      // 해당 주소의 이더 잔고 조회
      const ethBalance = await tx.getAddressEthBalance(addressInfo.address);
      const ethDecimal = 18;
      const ethAmount = new Decimal(ethBalance).div(10 ** ethDecimal);

      if (
        !ethAmount.isZero()
        && ethAmount.comparedTo(MINIMUM_ETHER_AMOUNT_TO_DEPOSIT) !== -1
      ) {
        // 입금가능한 이더가 있으면 입금처리
        const coinKind = 'eth';
        const requestUserId = addressInfo.user_id;
        const requestAddress = addressInfo.address;
        const requestAmount = ethAmount.toFixed(8); // 소수점 8자리 까지만 DB에 저장

        await call.setNewDepositRequest({
          coinKind,
          requestUserId,
          requestAddress,
          requestAmount,
        });

        console.log(
          `입금요청 추가 -> 입금종류: ${coinKind}, 입금유저: ${requestUserId}, 입금주소: ${requestAddress}, 입금액수: ${requestAmount}`,
        );
      } else {
        // 해당 주소에 입금 가능한 이더가 없으면 알려진 토큰 잔고 모두 조회
        const tokenBalances = await tx.getAddressTokenBalances(
          addressInfo.address,
          tokens,
        );

        // 입금가능한 토큰이 있으면 가장 먼저 발견된 토큰부터 입금처리
        for (const [contract, value] of Object.entries(tokenBalances)) {
          const token = tokenInfos.find(
            tokenInfo => tokenInfo.contract_address === contract,
          );
          const decimal = token.coin_decimals;
          const amount = new Decimal(value).div(10 ** decimal);

          if (
            !amount.isZero()
            && amount.comparedTo(MINIMUM_TOKEN_AMOUNT_TO_DEPOSIT) !== -1
          ) {
            // 입금 처리 목록에 추가
            const coinKind = token.coin_kind;
            const requestUserId = addressInfo.user_id;
            const requestAddress = addressInfo.address;
            const requestAmount = amount.toFixed(8); // 소수점 8자리 까지만 DB에 저장

            await call.setNewDepositRequest({
              coinKind,
              requestUserId,
              requestAddress,
              requestAmount,
            });

            console.log(
              `입금요청 추가 -> 입금종류: ${coinKind}, 입금유저: ${requestUserId}, 입금주소: ${requestAddress}, 입금액수: ${requestAmount}`,
            );

            break; // 한 주소에 여러 입금 처리 동시처리 불가
          }
        }
      }
    }
  } catch (err) {
    console.log(err);
  } finally {
    setTimeout(() => {
      scanDepositLoop();
    }, 1000 * 30);
  }
}
scanDepositLoop();

async function requestLoop() {
  let timeout = 1000 * 10;
  try {
    // 진행중인 요청 가져오기
    const currentRequest = await activity.checkoutCurrentRequest();
    if (currentRequest === null) {
      // 진행중인 요청 없음
      return;
    }

    // 요청 화폐 정보
    const query1 = `
    SELECT
      c.cointype as coin_type,
      c.token_contract_addr as contract_address,
      c.decimal_place as coin_decimals
    FROM btc_coins c
    WHERE c.api = '${currentRequest.coin_kind}'
    ORDER BY c.id ASC
    `;

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const currencyInfo = await execute1;

    if (currencyInfo.length === 0) {
      // 화폐 정보 없음
      await call.updateRequestValue({
        id: currentRequest.id,
        inProgress: '3',
        requestStatus: 'request_unknown_currency',
      });
      return;
    }

    // 요청에 화폐 정보 병합
    const request = { ...currentRequest, ...currencyInfo[0] };

    // 요청 종류에 따라 처리
    if (request.coin_type === 'coin' && request.coin_kind === 'eth') {
      if (request.request_type === 'withdraw') {
        if (await activity.processWithdrawEthRequest(request)) {
          timeout = 0;
        }
      } else if (request.request_type === 'deposit') {
        if (await activity.processDepositEthRequest(request)) {
          timeout = 0;
        }
      }
    } else if (request.coin_type === 'token') {
      if (request.request_type === 'withdraw') {
        if (await activity.processWithdrawTokenRequest(request)) {
          timeout = 0;
        }
      } else if (request.request_type === 'deposit') {
        if (await activity.processDepositTokenRequest(request)) {
          timeout = 0;
        }
      }
    }
  } catch (err) {
    if (
      err.message.startsWith(
        'Node error: {"code":-32603,"message":"request failed or timed out"}',
      )
    ) {
      timeout = 0; // 트랜잭션 타임아웃
    }
    console.log(err);
  } finally {
    setTimeout(() => {
      requestLoop();
    }, timeout);
  }
}
requestLoop();

async function confirmLoop() {
  try {
    // 완료된 요청 컨펌 체크. 컨펌 되면 추가 작업 실행
    const requests = await call.getCompletedRequests();

    for (const request of requests) {
      if (request.request_type === 'withdraw') {
        // 출금 요청 컨펌 체크
        if (
          await tx.isConfirmCompleted(
            request.confirm_tx,
            WITHDRAW_CONFIRM_COUNT,
          )
        ) {
          await call.updateRequestValue({
            id: request.id,
            requestStatus: 'withdraw_confirmed',
          });

          // 출금 완료 후 추가 작업 실행
          console.log(`출금 컨펌 완료 -> TxHash: ${request.confirm_tx}`);

          // 1. 출금 이력 추가
          const query1 = `INSERT btc_users_transactions set
          uid = (SELECT id FROM btc_users WHERE username = '${
  request.request_user_id
}'),
          type = 'send_${request.coin_kind}', recipient = '${
  request.request_address
}', sender = '${request.request_user_id}',amount = ${
  request.request_amount
}, time = unix_timestamp(), txid = '${request.confirm_tx}'`;

          const execute1 = exchangeDB
            .promise()
            .query(query1)
            .then(data => data[0]);
          await execute1;

          // 2. 관리자 수수료 가감
          const query2 = `update btc_users_addresses set available_balance_${
            request.coin_kind
          } = available_balance_${
            request.coin_kind
          } + (SELECT send_fee FROM btc_coins WHERE api = '${
            request.coin_kind
          }') where label = 'test00'`;

          const execute2 = exchangeDB
            .promise()
            .query(query2)
            .then(data => data[0]);
          await execute2;

          // 3. 사용자 수수료 차감
          const query3 = `update btc_users_addresses set available_balance_${
            request.coin_kind
          } = available_balance_${
            request.coin_kind
          } - (SELECT send_fee FROM btc_coins WHERE api = '${
            request.coin_kind
          }') where label = '${request.request_user_id}' `;

          const execute3 = exchangeDB
            .promise()
            .query(query3)
            .then(data => data[0]);
          await execute3;

          // 4. 사용자 출금대기 금액 제거
          const query4 = `update btc_users_addresses set pending_received_balance_${
            request.coin_kind
          } = pending_received_balance_${request.coin_kind} + ${
            request.request_amount
          } + (SELECT send_fee FROM btc_coins WHERE api = '${
            request.coin_kind
          }') WHERE label='${request.request_user_id}'`;

          const execute4 = exchangeDB
            .promise()
            .query(query4)
            .then(data => data[0]);
          await execute4;

          // 5. 사용자 잔액 차감
          const query5 = `update btc_users_addresses set available_balance_${
            request.coin_kind
          } = available_balance_${request.coin_kind} - ${
            request.request_amount
          } where label = '${request.request_user_id}' `;

          const execute5 = exchangeDB
            .promise()
            .query(query5)
            .then(data => data[0]);
          await execute5;

          // 6. 출금완료 처리
          const query6 = `
          UPDATE
            btc_coin_send_request
          SET
            status='withdraw_complete',
            tx_id='${request.confirm_tx}',
            updated=UNIX_TIMESTAMP(NOW()),
            updated_dt=NOW()
          WHERE
            id=${request.request_id}
          `;

          const execute6 = exchangeDB
            .promise()
            .query(query6)
            .then(data => data[0]);
          await execute6;
        }
      } else if (request.request_type === 'deposit') {
        // 입금 요청 컨펌 체크
        if (
          await tx.isConfirmCompleted(request.confirm_tx, DEPOSIT_CONFIRM_COUNT)
        ) {
          await call.updateRequestValue({
            id: request.id,
            requestStatus: 'deposit_confirmed',
          });

          // 입금 완료 후 추가 작업 실행
          console.log(`입금 컨펌 완료 -> TxHash: ${request.confirm_tx}`);
        }
      }
    }
  } catch (err) {
    console.log(err);
  } finally {
    setTimeout(() => {
      confirmLoop();
    }, 1000 * 60);
  }
}
confirmLoop();

// 테스트용
app.get('/test', (req, res) => {
  try {
    if (MODE === 'development') {
      res.send('ok');
    }
  } catch (err) {
    console.log(err);
    res.send('Error');
  }
});

const server = app.listen(PORT, () => {
  console.log(`Server is running... on ${PORT} as ${MODE}`);
});

process.on('SIGINT', () => {
  console.log('Received SIGINT, shutting down gracefully');
  server.close();
  localDB.end();
  exchangeDB.end();
  process.exit(0);
});
