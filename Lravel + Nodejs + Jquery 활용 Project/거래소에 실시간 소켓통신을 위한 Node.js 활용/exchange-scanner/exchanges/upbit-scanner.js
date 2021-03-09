const WS = require('ws');
const ReconnectingWebSocket = require('reconnecting-websocket');
const uuidv4 = require('uuid/v4');

module.exports = class UpbitScanner {
  constructor({ eid, name, coins }) {
    this.eid = eid;
    this.name = name;
    this.coins = coins;
    this.isStarted = false;
    this.callback = () => {};
  }

  onResult(callback) {
    this.callback = callback;
    return this;
  }

  connection() {
    this.rws = new ReconnectingWebSocket(
      'wss://api.upbit.com/websocket/v1',
      [],
      {
        WebSocket: WS,
        maxReconnectionDelay: 5 * 1000,
        connectionTimeout: 5 * 1000,
      },
    );

    this.rws.addEventListener('open', () => {
      this.heartbeat();

      this.rws.send(
        `[{"ticket":"${uuidv4()}"},{"type":"orderbook","codes":${JSON.stringify(
          this.coins.map(coin => `KRW-${coin}`),
        )}}]`,
      );
    });

    this.rws.addEventListener('message', (message) => {
      this.heartbeat();

      try {
        const info = JSON.parse(message.data);
        const result = {
          eid: this.eid,
          coin: info.code.replace('KRW-', ''),
          timestamp: info.timestamp,
          asks: info.orderbook_units.slice(0, 20).map(unit => ({
            eid: this.eid,
            qty: unit.ask_size,
            price: unit.ask_price,
          })),
          bids: info.orderbook_units.slice(0, 20).map(unit => ({
            eid: this.eid,
            qty: unit.bid_size,
            price: unit.bid_price,
          })),
        };

        this.callback(result);
      } catch (error) {
        console.log(error.stack);

        this.callback('error');
      }
    });

    this.rws.addEventListener('error', ({ error }) => {
      if (!error.stack.startsWith('Error: getaddrinfo ENOTFOUND')) {
        console.log(error.stack);
      }
    });

    this.rws.addEventListener('close', () => {
      this.callback('error');
      clearTimeout(this.rws.pingTimeout);
    });
  }

  heartbeat() {
    clearTimeout(this.rws.pingTimeout);
    this.rws.pingTimeout = setTimeout(() => {
      console.log('Error: heartbeat timeout');
      this.rws.reconnect();
    }, 5 * 1000);
  }

  start() {
    if (this.isStarted) {
      return;
    }
    console.log('upbit-scanner start');
    this.isStarted = true;
    this.connection();
  }

  stop() {
    if (!this.isStarted) {
      return;
    }
    console.log('upbit-scanner stop');
    this.isStarted = false;
    this.rws.close();
  }

  isScanning() {
    return this.isStarted;
  }
};
