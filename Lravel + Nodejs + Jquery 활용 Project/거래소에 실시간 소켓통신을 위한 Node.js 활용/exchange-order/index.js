/* eslint-disable indent */
const axios = require('axios');
const express = require('express');
const bodyParser = require('body-parser');
const Decimal = require('decimal.js');
const call = require('./call');
const { exchangeDB } = require('./db');
const UpbitOrderer = require('./exchange/upbit-orderer');
const BithumbOrderer = require('./exchange/bithumb-orderer');
const CoinoneOrderer = require('./exchange/coinone-orderer');

require('dotenv').config();

const MODE = process.env.NODE_ENV;
const PORT = process.env.PORT || 9601;
const SCANNER_HOST = process.env.SCANNER_HOST || 'localhost';
const SCANNER_PORT = process.env.SCANNER_PORT || 9600;

const app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const upbitOrderer = new UpbitOrderer();
const bithumbOrderer = new BithumbOrderer();
const coinoneOrderer = new CoinoneOrderer();
const state = {
  0: upbitOrderer,
  1: bithumbOrderer,
  2: coinoneOrderer,
};

const maximumTradeAmount = 1000000; // 요청 가능한 최대 금액 (현금)
const minimumTradeAmount = 15000; // 요청 가능한 최소 금액 (현금)
const interval = 1000;

const orderRequestedExchanges = new Set(); // 각 요청 처리에 사용된 거래소 eid

async function requestAcceptor() {
  try {
    const request = await call.getNextOrderRequest();
    if (request !== null) {
      const orderPlan = await axios.post(
        `http://${SCANNER_HOST}:${SCANNER_PORT}/orderbook`,
        request.in_progress === 0
          ? {
              // 요청 처음 시작
              type: request.type,
              coin: request.coin,
              amount: request.amount,
            } // 요청 반복
          : {
              type: request.type,
              coin: request.coin,
              amount:
                request.type === 'buy'
                  ? request.remain_value || '0' // 구매 시 현금 기준 요청
                  : request.remain_qty || '0', // 판매 시 코인 기준 요청
            },
      );

      console.log(JSON.stringify(orderPlan.data));
      const { coin, type, orders } = orderPlan.data;

      const totalOrder = orders.reduce(
        (acc, order) => ({
          qty: acc.qty.plus(order.qty),
          value: acc.value.plus(order.value),
        }),
        { qty: new Decimal(0), value: new Decimal(0) },
      );

      if (
        totalOrder.value.isZero()
        || totalOrder.value.comparedTo(maximumTradeAmount) === 1
      ) {
        // 잘못된 조회거나 금액이 클 때
        await call.updateOrderRequestValue({
          id: request.id,
          inProgress: '3',
          requestQty: totalOrder.qty,
          requestValue: totalOrder.value,
        });
        console.log(`Request stop: ${JSON.stringify(request.id)}`);
        throw new Error('Invalid Amount');
      }

      const exchangeReadyState = orders.map(exchange => ({
        eid: exchange.eid,
        ready:
          state[exchange.eid] !== undefined && state[exchange.eid].isReady(),
      }));

      const isReadyAll = exchangeReadyState.every(exchange => exchange.ready);
      if (isReadyAll) {
        try {
          // 요청 시작 기록
          if (request.in_progress === 0) {
            await call.updateOrderRequestValue({
              id: request.id,
              inProgress: '1',
              requestQty: totalOrder.qty,
              requestValue: totalOrder.value,
            });

            // request 인스턴스 업데이트
            request.in_progress = '1';
            request.request_qty = totalOrder.qty;
            request.request_value = totalOrder.value;

            // 사용자 금액 펜딩
            const currency = request.type === 'buy' ? 'krw' : request.coin.toLowerCase();
            const amount = request.type === 'buy'
                ? request.request_value
                : request.request_qty;

            const query1 = `
            UPDATE
              btc_users_addresses
            SET
              pending_received_balance_${currency} = pending_received_balance_${currency} - CAST(${amount} AS decimal(21,8))
            WHERE
              label = '${request.username}'
            LIMIT 1
            `;
            console.log(query1);

            const execute1 = exchangeDB
              .promise()
              .query(query1)
              .then(data => data[0]);
            await execute1;
          }

          // 10분 동안 돌면 타임아웃 처리
          const current = Math.ceil(Date.now() / 1000);
          const started = Math.ceil(new Date(request.updated).getTime() / 1000);

          // 로컬과 서버 9시간 차이남
          if (current - started - (MODE !== 'production' ? 32400 : 0) > 600) {
            console.log(
              `Request timeout: ${request.id} - ${JSON.stringify(
                orderPlan.data,
              )}`,
            );
            throw new Error('timeout');
          }

          // 요청 시작
          console.log(
            `Request order plan: ${request.id} - ${JSON.stringify(
              orderPlan.data,
            )}`,
          );
          const result = await Promise.all(
            orders.map((order) => {
              orderRequestedExchanges.add(order.eid);
              return state[order.eid].requestTransaction({
                coin,
                type,
                price: order.price,
                qty: order.qty,
                value: order.value,
              });
            }),
          );

          // 거래된 액수
          const tradedAmount = {
            qty: new Decimal(request.traded_qty || 0),
            value: new Decimal(request.traded_value || 0),
          };
          // 거래중인 액수
          const remainAmount = {
            qty: new Decimal(request.remain_qty || 0),
            value: new Decimal(request.remain_value || 0),
          };

          // 요청 성공 시
          result.forEach((response, index) => {
            if (response.status === 'done') {
              // 완전히 거래됨
              const { qty, value } = response.detail;
              tradedAmount.qty = tradedAmount.qty.plus(qty);
              tradedAmount.value = tradedAmount.value.plus(value);
              remainAmount.qty = new Decimal(0);
              remainAmount.value = new Decimal(0);
            } else if (response.status === 'part') {
              // 완전히 거래되지 않음
              const { traded, remain } = response.detail;
              tradedAmount.qty = tradedAmount.qty.plus(traded.qty);
              tradedAmount.value = tradedAmount.value.plus(traded.value);
              remainAmount.qty = remain.qty;
              remainAmount.value = remain.value;
            }
            console.log(
              `Success Response[${index}]: ${JSON.stringify(response)}`,
            );
          });

          // 거래 상황 기록
          await call.updateOrderRequestValue({
            id: request.id,
            tradedQty: tradedAmount.qty,
            tradedValue: tradedAmount.value,
            remainQty: remainAmount.qty,
            remainValue: remainAmount.value,
          });

          // request 인스턴스 업데이트
          request.traded_qty = tradedAmount.qty;
          request.traded_value = tradedAmount.value;
          request.remain_qty = remainAmount.qty;
          request.remain_value = remainAmount.value;

          console.log(`Request Running: ${JSON.stringify(request)}`);

          if (
            !remainAmount.value.isZero()
            && remainAmount.value.comparedTo(minimumTradeAmount) === 1
          ) {
            // 거래되지 않은 금액이 남아있고 일정 액수 가치 이상일 때 남은 금액으로 요청 재시작
            console.log(`Request continue: ${JSON.stringify(request.id)}`);
            return;
          }

          // 요청 완료 기록
          await call.updateOrderRequestValue({
            id: request.id,
            inProgress: '2',
            exchanges: JSON.stringify(Array.from(orderRequestedExchanges)),
          });

          // 거래소 기록 제거
          orderRequestedExchanges.clear();

          // request 인스턴스 업데이트
          request.in_progress = '2';

          // 사용자 금액 정산
          let query2 = '';
          if (request.type === 'buy') {
            query2 = `
              UPDATE
                btc_users_addresses
              SET
                pending_received_balance_krw = pending_received_balance_krw + CAST(${
                  request.request_value
                } AS decimal(21,8)),
                available_balance_krw = available_balance_krw - CAST(${
                  request.traded_value
                } AS decimal(21,8)),
                available_balance_${request.coin.toLowerCase()} = available_balance_${request.coin.toLowerCase()} + CAST(${
              request.traded_qty
            } AS decimal(21,8))
              WHERE
                label = '${request.username}'
              LIMIT 1
              `;
          } else {
            query2 = `
            UPDATE
              btc_users_addresses
            SET
              pending_received_balance_${request.coin.toLowerCase()} = pending_received_balance_${request.coin.toLowerCase()} + CAST(${
              request.request_qty
            } AS decimal(21,8)),
              available_balance_${request.coin.toLowerCase()} = available_balance_${request.coin.toLowerCase()} - CAST(${
              request.traded_qty
            } AS decimal(21,8)),
              available_balance_krw = available_balance_krw + CAST(${
                request.traded_value
              } AS decimal(21,8))
            WHERE
              label = '${request.username}'
            LIMIT 1
            `;
          }
          console.log(query2);

          const execute2 = exchangeDB
            .promise()
            .query(query2)
            .then(data => data[0]);
          await execute2;

          // 거래내역에 기록
          let query3 = '';
          if (request.type === 'buy') {
            const txId = `${request.coin}_trade_from_${
              request.username
            }_to_order_server_${request.traded_qty}_${request.coin}_${
              request.id
            }_${request.username}`;

            query3 = `
            INSERT INTO btc_transaction(
              cointxid,
              cointype,
              account,
              address,
              category,amount,
              confirmations,
              txid,
              tr_time,
              timereceived,
              processed,
              created_dt
            ) VALUES (
              '${txId}',
              '${request.coin}',
              '${request.username}',
              'order_server',
              'trade',
              '${request.traded_qty}',
              '999',
              '${txId}',
              UNIX_TIMESTAMP(),
              UNIX_TIMESTAMP(),
              'y',
              now()
            )`;
          } else {
            const txId = `${request.coin}_trade_from_order_server_to_${
              request.username
            }_${request.traded_qty}_${request.coin}_${request.id}_${
              request.username
            }`;
            query3 = `
            INSERT INTO btc_transaction(
              cointxid,
              cointype,
              account,
              address,
              category,
              amount,
              confirmations,
              txid,
              tr_time,
              timereceived,
              processed,
              created_dt
            ) VALUES (
              '${txId}',
              '${request.coin}',
              'order_server',
              '${request.username}',
              'trade',
              '${request.traded_qty}',
              '999',
              '${txId}',
              UNIX_TIMESTAMP(),
              UNIX_TIMESTAMP(),
              'y',
              now()
            )`;
          }
          console.log(query3);

          const execute3 = exchangeDB
            .promise()
            .query(query3)
            .then(data => data[0]);
          await execute3;

          console.log(`Request success: ${JSON.stringify(request.id)}`);

          // 사용자에게 구매 완료 푸시 알림
          try {
            await axios.post('http://localhost/push_order_result', {
              result: 'success',
              username: request.username,
              type: request.type,
              coin: request.coin.toLowerCase(),
              qty: request.traded_qty,
              value: request.traded_value,
            });
          } catch (e) {
            // 모든 절차가 완료됐으나 푸시 알림만 실패한 경우
            if ('response' in e) {
              console.error(e.response.data);
            } else {
              console.error(e);
            }
            console.log(`Request push fail: ${JSON.stringify(request.id)}`);
          }
        } catch (e) {
          if ('response' in e) {
            console.error(e.response.data);
          } else {
            console.error(e);
          }

          // 요청 실패 기록
          await call.updateOrderRequestValue({
            id: request.id,
            inProgress: '3',
            exchanges: JSON.stringify(Array.from(orderRequestedExchanges)),
          });

          // 거래소 기록 제거
          orderRequestedExchanges.clear();

          // request 인스턴스 업데이트
          request.in_progress = '3';

          // 사용자 금액 정산
          let query3 = '';
          if (request.type === 'buy') {
            query3 = `
              UPDATE
                btc_users_addresses
              SET
                pending_received_balance_krw = pending_received_balance_krw + CAST(${
                  request.request_value
                } AS decimal(21,8))
              WHERE
                label = '${request.username}'
              LIMIT 1
              `;
          } else {
            query3 = `
            UPDATE
              btc_users_addresses
            SET
              pending_received_balance_${request.coin.toLowerCase()} = pending_received_balance_${request.coin.toLowerCase()} + CAST(${
              request.request_qty
            } AS decimal(21,8))
            WHERE
              label = '${request.username}'
            LIMIT 1
            `;
          }
          console.log(query3);

          const execute3 = exchangeDB
            .promise()
            .query(query3)
            .then(data => data[0]);
          await execute3;

          console.log(`Request fail: ${JSON.stringify(request.id)}`);

          // 사용자에게 구매 실패 푸시 알림
          try {
            await axios.post('http://localhost/push_order_result', {
              result: 'fail',
              username: request.username,
              type: '',
              coin: '',
              qty: '',
              value: '',
            });
          } catch (e2) {
            // 모든 절차가 완료됐으나 푸시 알림만 실패한 경우
            if ('response' in e2) {
              console.error(e2.response.data);
            } else {
              console.error(e2);
            }
            console.log(`Request push fail: ${JSON.stringify(request.id)}`);
          }
        }
      } else {
        console.log(
          `exchangeReadyState: ${JSON.stringify(exchangeReadyState)}`,
        );
      }
    }
  } catch (e) {
    if ('response' in e) {
      console.error(e.response.data);
    } else {
      console.error(e);
    }
  } finally {
    setTimeout(() => {
      requestAcceptor();
    }, interval);
  }
}
requestAcceptor();

app.post('/order', async (req, res) => {
  const { type, username } = req.body;
  let { coin, amount } = req.body;

  if (!type || !coin || !amount || Number.isNaN(Number(amount)) || !username) {
    return res.send({ status: 'error', error: 'Invalid parameters' });
  }

  coin = coin.toUpperCase();
  amount = Number(amount);

  if (!['buy', 'sell'].includes(type)) {
    return res.send({ status: 'error', error: 'Invalid order type' });
  }

  if (!['BTC', 'ETH', 'LTC', 'DASH', 'BTG'].includes(coin)) {
    return res.send({ status: 'error', error: 'Unknown coin' });
  }

  if (type === 'buy' && amount < 1) {
    return res.send({
      status: 'error',
      error: 'Amount must be greater than 1',
    });
  }

  if (type === 'sell' && amount <= 0) {
    return res.send({ status: 'error', error: 'Amount cannot be 0 or less' });
  }

  try {
    const result1 = await call.getRequestsOfUsername({ username });
    if (result1.length > 0) {
      return res.send({
        status: 'rejected',
        error: 'Request already queued',
      });
    }

    await call.setOrderRequest({
      type,
      coin,
      amount,
      username,
    });
  } catch (e) {
    return res.send({ status: 'error', error: 'Internal server error' });
  }

  return res.send({
    status: 'accepted',
  });
});

app.listen(PORT, () => {
  const msg = `Server is running... on ${PORT}`;
  console.log(msg);
});

/*
 * TEST
 */

/*
if (false) {
  axios
    .post('http://localhost:9601/order', {
      type: 'buy',
      coin: 'eth',
      amount: '500000',
      username: 'test1234',
    })
    .then((response) => {
      console.log(response.data);
    })
    .catch((error) => {
      console.error(error.response.data);
    });
} else {
  axios
    .post('http://localhost:9601/order', {
      type: 'sell',
      coin: 'eth',
      amount: '1.56093750',
      username: 'test1234',
    })
    .then((response) => {
      console.log(response.data);
    })
    .catch((error) => {
      console.error(error.response.data);
    });
}
*/
