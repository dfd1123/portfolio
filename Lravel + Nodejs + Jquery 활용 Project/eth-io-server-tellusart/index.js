/* eslint-disable no-await-in-loop */
/* eslint-disable no-restricted-syntax */
const heapdump = require('heapdump');
const express = require('express');
const Decimal = require('decimal.js');

const web3Init = require('./web3');
const pool = require('./db');
const activity = require('./activity');
const call = require('./call');
const tx = require('./tx');
const admin = require('./admin');

const app = express();

const MODE = process.env.NODE_ENV; // development or production
const PORT = process.env.PORT || 8080;
const HOSTNAME = MODE === 'development' ? '0.0.0.0' : 'localhost';

// 주소체크
app.get('/isAddress/:addr', (req, res) => {
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
app.get('/newAddress/:userid', async (req, res) => {
  try {
    const userId = pool.escape(req.params.userid);

    // 이더리움 주소 생성
    const web3 = web3Init();
    const account = web3.eth.accounts.create(userId + web3.utils.randomHex(32));

    // 생성된 주소 정보를 DB에 추가
    const query1 = `INSERT INTO eth_io_account SET user_id=${userId}, address='${
      account.address
    }', private='${account.privateKey}'`;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    await execute1;

    // 생성된 주소 반환
    res.send(account.address);
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
    const tokenInfos = await call.getTokenInfos();

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

async function requestLoop() {
  let timeout = 1000 * 10;
  try {
    // 진행중인 요청 가져오기
    const currentRequest = await activity.checkoutCurrentRequest();
    if (currentRequest === null) {
      // 진행중인 요청 없음
      return;
    }

    // 요청 종류에 따라 처리
    if (currentRequest.request_type === 'withdraw') {
      if ((await activity.processWithdrawRequest(currentRequest)) === true) {
        timeout = 0;
      }
    } else if (currentRequest.request_type === 'deposit') {
      if ((await activity.processDepositRequest(currentRequest)) === true) {
        timeout = 0;
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

async function scanLoop() {
  try {
    /* 한 주소에서 한번에 한 종류씩만 입금 요청 처리. 다른 작업이 진행중인 주소에서 입금 요청 불가 */
    await activity.scanNewDeposit();
  } catch (err) {
    console.log(err);
  } finally {
    setTimeout(() => {
      scanLoop();
    }, 1000 * 60);
  }
}
scanLoop();

async function confirmLoop() {
  try {
    /* 입금 요청 완료 후 컨펌 체크. 컨펌 되면 추가 작업 실행 */
    const requests = await call.getCompletedDepositRequests();
    if (requests.length === 0) {
      return;
    }
    const web3 = web3Init();
    const verifiyComfirmNumber = 6;
    const currentBlockNumber = await web3.eth.getBlockNumber();

    for (const request of requests) {
      const confirmTx = await web3.eth.getTransaction(request.confirm_tx);

      if (currentBlockNumber - confirmTx.blockNumber < verifiyComfirmNumber) {
        // 지정된 횟수의 컨펌이 완료되지 않음
        return;
      }

      // 요청 컨펌 완료 처리
      await call.updateRequestValue({
        id: request.id,
        requestStatus: 'deposit_confirmed',
      });

      console.log(`컨펌 완료 -> TxHash: ${request.confirm_tx}`);

      if (request.coin_kind === 'tlg') {
        // 입금 금액 계좌 반영
        const query1 = `
        UPDATE tlca_user_addresses
        SET available_balance_tlc = available_balance_tlc + ${
  request.request_amount
}
        WHERE user_email = '${request.request_user_id}'
        `;

        const execute1 = pool
          .promise()
          .query(query1)
          .then(data => data[0]);
        await execute1;

        console.log(
          `잔액 반영 완료 -> User: ${request.request_user_id}, Coin: ${
            request.coin_kind
          }, Amount: ${request.request_amount}`,
        );
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
    res.send('ok');
  } catch (err) {
    console.log(err);
    res.send('Error');
  }
});

// heapdump
app.use('/heapdump', (req, res, next) => {
  const filename = `./${Date.now()}.heapsnapshot`;
  heapdump.writeSnapshot(filename);
  res.send(`Heapdump has been generated in ${filename}`);
  next();
});

const server = app.listen(PORT, HOSTNAME, () => {
  console.log(`Server is running... on ${PORT} as ${MODE}`);
});

process.on('SIGINT', () => {
  console.log('Received SIGINT, shutting down gracefully');
  server.close();
  pool.end();
  process.exit(0);
});
