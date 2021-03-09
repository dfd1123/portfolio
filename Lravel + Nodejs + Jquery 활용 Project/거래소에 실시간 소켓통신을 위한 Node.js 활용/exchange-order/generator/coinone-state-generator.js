const Decimal = require('decimal.js');

function toFixedRoundDown(num, fixed = 8) {
  return Decimal(num)
    .toDecimalPlaces(fixed, Decimal.ROUND_DOWN)
    .toFixed();
}

function sumOrderDetailData(order1) {
  const calc = order1.info.type === 'bid'
    ? {
      qty: toFixedRoundDown(
        Decimal(order1.info.qty).minus(order1.info.fee),
        8,
      ),
      value: toFixedRoundDown(
        Decimal.mul(order1.info.price, order1.info.qty).ceil(),
        0,
      ),
    }
    : {
      qty: toFixedRoundDown(order1.info.qty, 8),
      value: toFixedRoundDown(
        Decimal.mul(order1.info.price, order1.info.qty)
          .floor()
          .minus(order1.info.fee)
          .floor(),
        0,
      ),
    };

  return calc;
}

function call(api, params, started = false) {
  return {
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

module.exports = function* CoinoneStateGenerator(parameter) {
  const { coin } = parameter;
  const qty = new Decimal(parameter.qty);
  const price = new Decimal(parameter.price);
  const value = new Decimal(parameter.value);
  const { type } = parameter;

  // 주문하기
  const place1 = yield call(
    type === 'buy' ? '/v2/order/limit_buy/' : '/v2/order/limit_sell/',
    {
      price: toFixedRoundDown(price).toString(),
      qty: toFixedRoundDown(qty, 4).toString(), // 코인원에서 소수점 4자리까지만 받음
      currency: coin,
    },
    true,
  );

  if (place1.result === 'success') {
    const params = {
      order_id: place1.orderId,
      currency: coin,
    };
    yield info(1);

    // 주문이 바로 체결되었는지 확인하기 위해 해당 주문 조회
    const order1 = yield call('/v2/order/order_info/', params);

    if (order1.result === 'success') {
      if (order1.status === 'filled') {
        // 모두 체결됨
        return info(2, {
          status: 'done',
          detail: sumOrderDetailData(order1),
        });
      }
      if (order1.status === 'live' || order1.status === 'partially_filled') {
        yield info(3);

        // 체결 여부와 상관없이 취소 시도
        const cancel1 = yield call('/v2/order/cancel/', {
          ...params,
          price: toFixedRoundDown(price).toString(),
          qty: toFixedRoundDown(qty, 4).toString(),
          is_ask: type === 'sell' ? 1 : 0,
        });

        if (cancel1.result === 'success') {
          yield info(4);

          // 취소한 주문의 일부나 전부가 체결되었는지 확인하기 위해 주문 조회
          const order2 = yield call('/v2/order/order_info/', params);

          if (order2.result === 'success') {
            /*
             *
             * [경고] !!! 충분히 검증되지 않음 !!!
             *
             */
            if (order2.status === 'partially_filled') {
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

            return info(6, { status: 'error' });
          }

          if (order2.result === 'error' && order2.errorCode === '104') {
            yield info(7);

            // 잠시 호가창에 있었지만 취소하기 전에 모두 체결됨. 체결된 내역을 확인하기 위해 주문 상세조회
            const order3 = yield call('/v2/order/order_info/', params);

            if (order3.result === 'success') {
              if (order3.status === 'filled') {
                // 모두 체결됨
                return info(8, {
                  status: 'done',
                  detail: sumOrderDetailData(order3),
                });
              }

              return info(9, { status: 'error' });
            }
          }

          return info(10, { status: 'error' });
        }

        if (cancel1.result === 'error' && cancel1.errorCode === '104') {
          yield info(11);

          // 잠시 호가창에 있었지만 취소하기 전에 모두 체결됨. 체결된 내역을 확인하기 위해 주문 상세조회
          const order4 = yield call('/v2/order/order_info/', params);

          if (order4.result === 'success') {
            if (order4.status === 'filled') {
              // 모두 체결됨
              return info(12, {
                status: 'done',
                detail: sumOrderDetailData(order4),
              });
            }

            return info(13, { status: 'error' });
          }
        }
      }

      return info(14, { status: 'error' });
    }

    return info(15, { status: 'error' });
  }

  if (place1.result === 'error' && place1.errorCode === '103') {
    // 주문실패, 잔고없음
    return info(16, { status: 'error' });
  }

  return info(17, { status: 'error' });
};
