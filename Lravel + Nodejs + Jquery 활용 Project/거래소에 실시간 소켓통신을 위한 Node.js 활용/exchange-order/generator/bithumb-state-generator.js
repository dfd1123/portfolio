const Decimal = require('decimal.js');

const NO_ACTIVE_ORDER = '거래 진행중인 내역이 존재하지 않습니다.';
const NO_CLOSED_ORDER = '거래 체결내역이 존재하지 않습니다.';
const NO_CANCEL_ORDER = '매수건의 상태가 진행중이 아닙니다. 취소할 수 없습니다.';
const NOT_ENOUGH_KRW = '매수금액이 사용가능 KRW 를 초과하였습니다.';

function toFixedRoundDown(num, fixed = 8) {
  return Decimal(num)
    .toDecimalPlaces(fixed, Decimal.ROUND_DOWN)
    .toFixed();
}

function sumOrderDetailData(data) {
  Decimal.set({ rounding: 5 }); // 0.5보다 크면 반올림
  const { type, contract: contracts } = data;
  const calc = contracts.reduce(
    (acc, contract) => ({
      qty: acc.qty
        .plus(contract.units)
        .minus(type === 'bid' ? contract.fee : 0),
      value: acc.value
        .plus(contract.total)
        .minus(type === 'ask' ? Decimal.round(contract.fee) : 0),
    }),
    { qty: new Decimal(0), value: new Decimal(0) },
  );
  Decimal.set({ rounding: 4 }); // default
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

module.exports = function* BithumbStateGenerator(parameter) {
  const { coin } = parameter;
  const qty = new Decimal(parameter.qty);
  const price = new Decimal(parameter.price);
  const value = new Decimal(parameter.value);
  const type = parameter.type === 'buy' ? 'bid' : 'ask';

  // 주문하기
  const place1 = yield call(
    '/trade/place',
    {
      order_currency: coin,
      payment_currency: 'KRW',
      price: toFixedRoundDown(price).toString(),
      units: toFixedRoundDown(qty, 4).toString(), // 빗썸에서 소수점 4째 자리까지만 받음
      type,
    },
    true,
  );

  if (place1.status === '0000') {
    const params = { type, order_id: place1.order_id, currency: coin };
    yield info(1);

    // 주문이 바로 체결되었는지 확인하기 위해 해당 주문 조회
    const orders1 = yield call('/info/orders', params);

    if (orders1.status === '0000') {
      yield info(2);

      // 바로 체결되지 않은 주문이 존재하면 취소
      const cancel1 = yield call('/trade/cancel', params);

      if (cancel1.status === '0000') {
        yield info(3);

        // 취소한 주문의 일부나 전부가 체결되었는지 확인하기 위해 주문 상세조회
        const orderDetail1 = yield call('/info/order_detail', params);

        if (orderDetail1.status === '0000') {
          // 바로 체결되지 않은 주문을 취소했지만 이미 부분적으로 체결됨. 남은값을 정리해서 리턴
          const calcPart = sumOrderDetailData(orderDetail1.data);
          const calcResult = {
            traded: {
              qty: toFixedRoundDown(calcPart.qty, 4),
              value: calcPart.value,
            },
            remain: {
              qty: toFixedRoundDown(qty.minus(calcPart.qty), 4),
              value: value.minus(calcPart.value),
            },
          };
          return info(4, { status: 'part', detail: calcResult });
        }

        if (
          orderDetail1.status === '5600'
          && orderDetail1.message === NO_CLOSED_ORDER
        ) {
          // 체결되지 않은 주문을 바로 취소했고 부분적으로 체결되지 않음
          const calcResult = {
            traded: { qty: '0', value: '0' },
            remain: { qty, value },
          };
          return info(5, { status: 'part', detail: calcResult });
        }

        return info(6, { status: 'error' });
      }

      if (cancel1.status === '5600' && cancel1.message === NO_CANCEL_ORDER) {
        yield info(7);

        // 잠시 호가창에 있었지만 취소하기 전에 모두 체결됨. 체결된 내역을 확인하기 위해 주문 상세조회
        const orderDetail2 = yield call('/info/order_detail', params);

        if (orderDetail2.status === '0000') {
          // 체결된 내역을 정리해서 리턴
          return info(8, {
            status: 'done',
            detail: sumOrderDetailData(orderDetail2.data),
          });
        }

        return info(9, { status: 'error' });
      }

      return info(10, { status: 'error' });
    }

    if (orders1.status === '5600' && orders1.message === NO_ACTIVE_ORDER) {
      yield info(11);

      // 주문이 바로 전부 체결됨. 체결된 내역을 확인하기 위해 주문 상세조회
      const orderDetail3 = yield call('/info/order_detail', params);

      if (orderDetail3.status === '0000') {
        // 체결된 내역을 합해서 리턴
        return info(12, {
          status: 'done',
          detail: sumOrderDetailData(orderDetail3.data),
        });
      }

      return info(13, { status: 'error' });
    }
  } else if (place1.status === '5600' && place1.message === NOT_ENOUGH_KRW) {
    // 주문실패, 잔고없음
    return info(14, { status: 'error' });
  }

  return info(15, { status: 'error' });
};
