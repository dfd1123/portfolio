const Decimal = require('decimal.js');

function toFixedRoundDown(num, fixed = 8) {
  return Decimal(num)
    .toDecimalPlaces(fixed, Decimal.ROUND_DOWN)
    .toFixed();
}
module.exports = class State {
  constructor(scanners) {
    this.state = {};

    scanners.forEach((scanner) => {
      const exchange = {
        name: scanner.name,
        scanner,
        timestamp: 0,
        orderbooks: {},
      };
      this.state[scanner.eid] = exchange;
    });
  }

  getScannerList() {
    return Object.keys(this.state).map((eid) => {
      const exchange = this.state[eid];

      return {
        eid: Number(eid),
        name: exchange.name,
        scanning: exchange.scanner.isScanning(),
      };
    });
  }

  getExchange(eid) {
    return this.state[eid];
  }

  getSimpleBuyPlan(coin, amount) {
    let sorted = Object.values(this.state)
      .filter(exchange => coin in exchange.orderbooks)
      .reduce(
        (total, exchange) => [...total, ...exchange.orderbooks[coin].asks],
        [],
      )
      .sort((a, b) => {
        if (a.price < b.price) {
          return -1;
        }
        if (a.price > b.price) {
          return 1;
        }
        if (a.qty > b.qty) {
          return -1;
        }
        if (a.qty < b.qty) {
          return 1;
        }
        return 0;
      });

    if (sorted.length > 0) {
      const selectedEid = sorted[0].eid;
      sorted = sorted.filter(order => order.eid === selectedEid);
    } else {
      return [];
    }

    const orders = [];
    let total = new Decimal(amount);
    sorted.some((order) => {
      const qty = new Decimal(order.qty);
      const price = new Decimal(order.price);
      const value = Decimal.mul(qty, price);

      if (total.comparedTo(value) === 1) {
        // total이 더 큼
        total = total.minus(value);
        orders.push(Object.assign(order, { price, value }));
        return false;
      }

      orders.push(
        Object.assign(order, {
          qty: total.dividedBy(price),
          price,
          value: total,
        }),
      );
      total = new Decimal(0);
      return true;
    });

    if (total.comparedTo(0) !== 0) {
      return [];
    }

    const results = {};
    orders.forEach(({
      eid, qty, price, value,
    }) => {
      const sum = results[eid] || {
        eid,
        qty: new Decimal(0),
        price: new Decimal(0),
        value: new Decimal(0),
      };

      sum.qty = sum.qty.plus(qty);
      sum.price = Decimal.max(sum.price, price);
      sum.value = sum.value.plus(value);
      results[eid] = sum;
    });

    return Object.values(results).map(order => Object.assign(order, {
      qty: toFixedRoundDown(order.qty).toString(),
      value: order.value.floor().toString(),
    }));
  }

  getSimpleSellPlan(coin, amount) {
    let sorted = Object.values(this.state)
      .filter(exchange => coin in exchange.orderbooks)
      .reduce(
        (total, exchange) => [...total, ...exchange.orderbooks[coin].bids],
        [],
      )
      .sort((a, b) => {
        if (a.price < b.price) {
          return 1;
        }
        if (a.price > b.price) {
          return -1;
        }
        if (a.qty > b.qty) {
          return -1;
        }
        if (a.qty < b.qty) {
          return 1;
        }
        return 0;
      });

    if (sorted.length > 0) {
      const selectedEid = sorted[0].eid;
      sorted = sorted.filter(order => order.eid === selectedEid);
    } else {
      return [];
    }

    let total = new Decimal(amount);
    const orders = [];
    sorted.some((order) => {
      const qty = new Decimal(order.qty);
      const price = new Decimal(order.price);

      if (total.comparedTo(qty) === 1) {
        total = total.minus(qty);
        orders.push(Object.assign(order, { value: Decimal.mul(qty, price) }));
        return false;
      }

      orders.push(
        Object.assign(order, { qty: total, value: Decimal.mul(total, price) }),
      );
      total = new Decimal(0);
      return true;
    });

    if (total.comparedTo(0) !== 0) {
      return [];
    }

    const results = {};
    orders.forEach(({
      eid, qty, price, value,
    }) => {
      const sum = results[eid] || {
        eid,
        qty: new Decimal(0),
        price: new Decimal(Infinity),
        value: new Decimal(0),
      };

      sum.qty = sum.qty.plus(qty);
      sum.price = Decimal.min(sum.price, price);
      sum.value = sum.value.plus(value);
      results[eid] = sum;
    });

    return Object.values(results).map(order => Object.assign(order, {
      qty: toFixedRoundDown(order.qty).toString(),
      value: order.value.floor().toString(),
    }));
  }

  updateOrderbook({
    eid, coin, timestamp, asks, bids,
  }) {
    const exchange = this.state[eid];
    exchange.timestamp = timestamp;

    if (!(coin in exchange.orderbooks)) {
      exchange.orderbooks[coin] = {
        asks: [],
        bids: [],
      };
    }

    exchange.orderbooks[coin].asks = asks;
    exchange.orderbooks[coin].bids = bids;
  }

  clearOrderbook(eid) {
    const exchange = this.state[eid];
    exchange.timestamp = 0;
    exchange.orderbooks = {};
  }
};
