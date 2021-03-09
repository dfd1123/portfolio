const axios = require('axios');

module.exports = class BithumbScanner {
  constructor({ eid, name, coins }) {
    this.eid = eid;
    this.name = name;
    this.coins = coins;
    this.interval = 1000;
    this.timeoutRef = null;
    this.callback = () => {};
  }

  onResult(callback) {
    this.callback = callback;
    return this;
  }

  polling() {
    axios
      .all(
        this.coins.map(coin => axios.get(`https://api.bithumb.com/public/orderbook/${coin}`, {
          timeout: 5 * 1000,
        })),
      )
      .then(resps => resps.map(resp => resp.data))
      .then(datas => datas.map((result) => {
        if (result.status !== '0000') {
          throw new Error(JSON.stringify(result));
        }

        return result.data;
      }))
      .then((infos) => {
        infos.forEach((info) => {
          const result = {
            eid: this.eid,
            coin: info.order_currency,
            timestamp: Number(info.timestamp),
            asks: info.asks.slice(0, 20).map(ask => ({
              eid: this.eid,
              qty: parseFloat(ask.quantity),
              price: parseFloat(ask.price),
            })),
            bids: info.bids.slice(0, 20).map(bid => ({
              eid: this.eid,
              qty: parseFloat(bid.quantity),
              price: parseFloat(bid.price),
            })),
          };

          this.callback(result);
        });

        this.interval = 1000;
      })
      .catch((error) => {
        if (
          !error.stack.startsWith('Error: getaddrinfo ENOTFOUND')
          && !error.stack.startsWith('Error: timeout')
        ) {
          console.log(error.stack);
        }

        this.callback('error');
        this.interval = 5 * 1000;
      })
      .then(() => {
        this.timeoutRef = setTimeout(() => {
          this.polling();
        }, this.interval);
      });
  }

  start() {
    if (this.timeoutRef !== null) {
      return;
    }
    console.log('bithumb-scanner start');
    this.timeoutRef = setTimeout(() => {
      this.polling();
    }, 0);
  }

  stop() {
    if (this.timeoutRef === null) {
      return;
    }
    console.log('bithumb-scanner stop');
    clearTimeout(this.timeoutRef);
    this.timeoutRef = null;
  }

  isScanning() {
    return this.timeoutRef !== null;
  }
};
