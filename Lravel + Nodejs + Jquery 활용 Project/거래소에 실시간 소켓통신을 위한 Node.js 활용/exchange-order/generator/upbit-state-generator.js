const Decimal = require('decimal.js');

function toFixedRoundDown(num, fixed = 8) {
  return Decimal(num)
    .toDecimalPlaces(fixed, Decimal.ROUND_DOWN)
    .toFixed();
}

function sumOrderDetailData(data) {
  const calc = data.trades.reduce(
    (acc, trade) => ({
      qty: acc.qty.plus(trade.volume),
      value: acc.value.plus(trade.funds),
    }),
    { qty: new Decimal(0), value: new Decimal(0) },
  );
  calc.value = data.side === 'bid'
    ? toFixedRoundDown(Decimal.ceil(calc.value.plus(data.paid_fee)), 0) // 살 때는 현금을 수수료만큼 더 냄
    : toFixedRoundDown(calc.value.minus(data.paid_fee), 0); // 팔 때는 현금을 수수료만큼 덜 받음
  return calc;
}

function call(method, api, params, started = false) {
  return {
    method,
    api,
    params,
    started,
  };
}

function info(stage, result) {
  if (result === undefined) {
    return { stage };
  }

  return {
    stage,
    result,
  };
}

module.exports = function* UpbitStateGenerator(parameter) {
  const { coin } = parameter;
  const qty = new Decimal(parameter.qty).mul(
    parameter.type === 'buy' ? '0.9985' : '1',
  ); // 업비트 수수료 보정;
  const price = new Decimal(parameter.price);
  const value = new Decimal(parameter.value);
  const type = parameter.type === 'buy' ? 'bid' : 'ask';

  // 주문하기
  const place1 = yield call(
    'POST',
    '/orders',
    {
      market: `KRW-${coin}`,
      side: type,
      price: toFixedRoundDown(price).toString(),
      volume: toFixedRoundDown(qty).toString(),
      ord_type: 'limit',
    },
    true,
  );

  if (!place1.error) {
    const params = { uuid: place1.uuid };
    yield info(1);

    // 주문이 바로 체결되었는지 확인하기 위해 해당 주문 조회
    const order1 = yield call('GET', '/order', params);

    if (!order1.error) {
      if (order1.state === 'done') {
        // 모두 체결됨
        return info(2, {
          status: 'done',
          detail: sumOrderDetailData(order1),
        });
      }
      if (order1.state === 'wait') {
        yield info(3);

        // 체결 여부와 상관없이 취소 시도
        const cancel1 = yield call('DELETE', '/order', params);

        if (!cancel1.error) {
          yield info(4);

          // 취소한 주문의 일부나 전부가 체결되었는지 확인하기 위해 주문 조회
          const order2 = yield call('GET', '/order', params);

          if (!order2.error) {
            if (order2.trades.length !== 0) {
              // 바로 체결되지 않은 주문을 취소했지만 이미 부분적으로 체결됨. 남은값을 정리해서 리턴
              const calcPart = sumOrderDetailData(order2);
              const calcResult = {
                traded: {
                  qty: toFixedRoundDown(calcPart.qty),
                  value: calcPart.value,
                },
                remain: {
                  qty: toFixedRoundDown(qty.minus(calcPart.qty)),
                  value: value.minus(calcPart.value),
                },
              };
              return info(5, { status: 'part', detail: calcResult });
            }

            // 체결되지 않은 주문을 바로 취소했고 부분적으로 체결되지 않음
            const calcResult = {
              traded: { qty: '0', value: '0' },
              remain: { qty, value },
            };
            return info(6, { status: 'part', detail: calcResult });
          }

          return info(7, { status: 'error' });
        }

        if (cancel1.error.name === 'order_not_found') {
          yield info(8);

          // 잠시 호가창에 있었지만 취소하기 전에 모두 체결됨. 체결된 내역을 확인하기 위해 주문 상세조회
          const order3 = yield call('GET', '/order', params);

          if (!order3.error) {
            if (order3.state === 'done') {
              // 모두 체결됨
              return info(9, {
                status: 'done',
                detail: sumOrderDetailData(order3),
              });
            }

            return info(10, { status: 'error' });
          }
        }
      }

      return info(11, { status: 'error' });
    }

    return info(12, { status: 'error' });
  }

  if (place1.error.name.startsWith('insufficient_')) {
    // 주문실패, 잔고없음
    return info(13, { status: 'error' });
  }

  return info(14, { status: 'error' });
};
