class Actions {
  constructor() {
    this.marketTypes = ['usd', 'btc', 'eth'];
    this.actionTypes = ['orderbook', 'ticker', 'trade'];
    this.unusing = new Set();
    this.using = {};

    this.marketTypes.forEach((market) => {
      this.using[market] = this.actionTypes.reduce((obj, type) => {
        obj[type] = {};
        return obj;
      }, {});
    });
  }

  getAll(type) {
    return Object.values(this.using)
      .map(market => Object.values(market[type]))
      .reduce((a, b) => a.concat(b));
  }

  get(market, type, coin) {
    if (
      market in this.using
      && type in this.using[market]
      && coin in this.using[market][type]
    ) {
      return this.using[market][type][coin];
    }

    return null;
  }

  useAction(market, type, coin) {
    let found = null;
    this.unusing.forEach((action) => {
      if (
        action.market === market
        && action.type === type
        && action.coin === coin
      ) {
        found = action;
      }
    });

    if (found) {
      this.using[market][type][coin] = found;
    } else {
      this.using[market][type][coin] = { market, type, coin };
    }
  }

  unuseActions(market, type, coin) {
    if (
      market in this.using
      && type in this.using[market]
      && coin in this.using[market][type]
    ) {
      this.unusing.add(this.using[market][type][coin]);
      delete this.using[market][type][coin];
    }
  }

  updateActions(coins) {
    const usingCoins = [...new Set(Object.keys(this.using.usd.orderbook))];

    const added = coins.filter(x => !usingCoins.includes(x));
    added.forEach((coin) => {
      this.marketTypes.forEach((market) => {
        this.actionTypes.forEach((type) => {
          if (market !== coin) {
            this.useAction(market, type, coin);
          }
        });
      });
    });

    const removed = usingCoins.filter(x => !coins.includes(x));
    removed.forEach((coin) => {
      this.marketTypes.forEach((market) => {
        this.actionTypes.forEach((type) => {
          this.unuseActions(market, type, coin);
        });
      });
    });
  }
}

module.exports = new Actions();
