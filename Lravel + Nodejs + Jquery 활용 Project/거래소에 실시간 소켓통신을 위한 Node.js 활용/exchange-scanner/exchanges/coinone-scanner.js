const axios = require('axios');

module.exports = class CoinoneScanner {
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
        this.coins.map(coin => axios.get(`https://api.coinone.co.kr/orderbook/?currency=${coin}`, {
          timeout: 5 * 1000,
        })),
      )
      .then(resps => resps.map(resp => resp.data))
      .then(datas => datas.map((result) => {
        if (result.result === 'error') {
          if (result.errorCode === '4') {
            // API 리밋 초과 (300 requests per minute) 10분 블락
            this.interval = 15 * 60 * 1000;
          }

          throw new Error(result);
        }

        return result;
      }))
      .then((infos) => {
        infos.forEach((info) => {
          const result = {
            eid: this.eid,
            coin: info.currency.toUpperCase(),
            timestamp: Number(info.timestamp),
            asks: info.ask.slice(0, 20).map(ask => ({
              eid: this.eid,
              qty: parseFloat(ask.qty),
              price: parseFloat(ask.price),
            })),
            bids: info.bid.slice(0, 20).map(bid => ({
              eid: this.eid,
              qty: parseFloat(bid.qty),
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
    console.log('coinone-scanner start');
    this.timeoutRef = setTimeout(() => {
      this.polling();
    }, 0);
  }

  stop() {
    if (this.timeoutRef === null) {
      return;
    }
    console.log('coinone-scanner stop');
    clearTimeout(this.timeoutRef);
    this.timeoutRef = null;
  }

  isScanning() {
    return this.timeoutRef !== null;
  }
};
