/* eslint-disable global-require */
/* eslint-disable no-await-in-loop */
/* eslint-disable no-restricted-syntax */
const express = require('express');
const Decimal = require('decimal.js');

require('dotenv').config();

const web3Init = require('./web3');
const activity = require('./activity');
const pool = require('./db');
const call = require('./call');
const tx = require('./tx');

const app = express();

let event;
try {
  event = require('./event');
} catch (e) {
  if (e instanceof Error && e.code === 'MODULE_NOT_FOUND') {
    event = require('./event.example');
  }
}

const MODE = process.env.NODE_ENV; // development or production
const PORT = process.env.PORT || 8080;
const CENTRAL = process.env.CENTRAL || false; // 중앙형인지
const MINIMUM_ETHER_AMOUNT_TO_DEPOSIT = String(process.env.MINIMUM_ETHER_AMOUNT_TO_DEPOSIT) || '0.01'; // 이더 입금 최소액수, 소수점 8자리 이하는 DB에 값 입력 시 무시됨. 값이 너무 작으면 수수료 에러
const MINIMUM_TOKEN_AMOUNT_TO_DEPOSIT = String(process.env.MINIMUM_TOKEN_AMOUNT_TO_DEPOSIT) || '0.00000001'; // 토큰 입금 최소액수, 소수점 8자리 이하는 DB에 값 입력 시 무시됨
const WITHDRAW_CONFIRM_COUNT = Number(process.env.WITHDRAW_CONFIRM_COUNT) || 1; // 출금 검증 컨펌 횟수
const DEPOSIT_CONFIRM_COUNT = Number(process.env.DEPOSIT_CONFIRM_COUNT) || 6; // 입금 검증 컨펌 회수
const BALANCE_CHECK_INTERVAL = 1000 * Number(process.env.BALANCE_CHECK_INTERVAL) || 1000 * 60; // 잔고 체크 간격
const REQUEST_CHECK_INTERVAL = 1000 * Number(process.env.REQUEST_CHECK_INTERVAL) || 1000 * 60; // 요청 체크 간격
const CONFIRM_CHECK_INTERVAL = 1000 * Number(process.env.CONFIRM_CHECK_INTERVAL) || 1000 * 60; // 컨펌 체크 간격

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
    const userId = pool.escape(req.params.userid);

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

async function scanDepositLoop() {
  try {
    // 이더리움 망에서 새 입금 스캔
    // 대기중이나 진행중인 작업이 없는 주소만 조회
    const addressInfos = await call.getNotInProgressOrFailAddressInfos();
    if (addressInfos.length === 0) {
      return;
    }

    // 토큰 정보 조회
    const tokenInfos = await call.getTokenInfos();
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
    }, BALANCE_CHECK_INTERVAL);
  }
}
if (CENTRAL === 'true') {
  scanDepositLoop();
}

async function requestLoop() {
  let timeout = REQUEST_CHECK_INTERVAL;
  try {
    // 진행중인 요청 가져오기
    const request = await activity.checkoutCurrentRequest();
    if (request === null) {
      // 진행중인 요청 없음
      return;
    }

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
      } else if (
        CENTRAL === 'false'
        || request.request_type === 'withdraw_from_user'
      ) {
        if (await activity.processWithdrawFromUserEthRequest(request)) {
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
      } else if (
        CENTRAL === 'false'
        || request.request_type === 'withdraw_from_user'
      ) {
        if (await activity.processWithdrawFromUserTokenRequest(request)) {
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
      if (
        request.request_type === 'withdraw'
        || request.request_type === 'withdraw_from_user'
      ) {
        // 출금 요청 컨펌 체크
        if (
          await tx.isConfirmCompleted(
            request.confirm_tx,
            WITHDRAW_CONFIRM_COUNT,
          )
        ) {
          // 출금 완료 처리
          await call.updateRequestValue({
            id: request.id,
            requestStatus: 'withdraw_confirmed',
          });

          if (CENTRAL === 'true') {
            // 출금 완료 후 추가 작업 실행
            await event.onCentralWithdrawConfirmed(request);
          }

          console.log(`출금 컨펌 완료 -> TxHash: ${request.confirm_tx}`);
        }
      } else if (request.request_type === 'deposit') {
        // 입금 요청 컨펌 체크
        if (
          await tx.isConfirmCompleted(request.confirm_tx, DEPOSIT_CONFIRM_COUNT)
        ) {
          // 입금 완료 처리
          await call.updateRequestValue({
            id: request.id,
            requestStatus: 'deposit_confirmed',
          });

          if (CENTRAL === 'true') {
            // 입금 완료 후 추가 작업 실행
            await event.onCentralDepositConfirmed(request);
          }

          console.log(`입금 컨펌 완료 -> TxHash: ${request.confirm_tx}`);
        }
      }
    }
  } catch (err) {
    console.log(err);
  } finally {
    setTimeout(() => {
      confirmLoop();
    }, CONFIRM_CHECK_INTERVAL);
  }
}
confirmLoop();

const server = app.listen(PORT, () => {
  console.log(`Server is running... on ${PORT} as ${MODE}`);
});

process.on('SIGINT', () => {
  console.log('Received SIGINT, shutting down gracefully');
  server.close();
  pool.end();
  process.exit(0);
});
