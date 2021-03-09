const call = require('./call');
const Actions = require('./actions');

function isKnownError(err) {
  return err.code === 'EHOSTUNREACH' || err.code === 'ECONNRESET';
}

class ChangeScanner {
  constructor() {
    this.isStarted = false;
    this.requestCallback = () => {};
    this.changeCallback = () => {};

    this.scanCoinInterval = 5000;
    this.scanCoinChangeTimeref = null;

    this.scanOrderbookInterval = 1000;
    this.scanOrderbookTimeref = null;

    this.scanTickerInterval = 1000;
    this.scanTickerTimeref = null;

    this.scanTradeInterval = 1000;
    this.scanTradeTimeref = null;
  }

  onRequest(callback) {
    this.requestCallback = callback;
  }

  onChange(callback) {
    this.changeCallback = callback;
  }

  scanCoinChange() {
    call
      .getListedCoins()
      .then(({ coins }) => {
        this.changeCallback('getListedCoins', coins);
      })
      .catch((err) => {
        if (!isKnownError(err)) {
          console.error(err);
        }
      })
      .then(() => {
        this.scanCoinChangeTimeref = setTimeout(() => {
          this.scanCoinChange();
        }, this.scanCoinInterval);
      });
  }

  scanOrderbookChange() {
    this.requestCallback(Actions.getAll('orderbook'));

    this.scanOrderbookTimeref = setTimeout(() => {
      this.scanOrderbookChange();
    }, this.scanOrderbookInterval);
  }

  scanTickerChange() {
    this.requestCallback(Actions.getAll('ticker'));

    this.scanTickerTimeref = setTimeout(() => {
      this.scanTickerChange();
    }, this.scanTickerInterval);
  }

  scanTradeChange() {
    this.requestCallback(Actions.getAll('trade'));

    this.scanTradeTimeref = setTimeout(() => {
      this.scanTradeChange();
    }, this.scanTradeInterval);
  }

  init() {
    call
      .getListedCoins()
      .then(({ coins }) => {
        this.changeCallback('getListedCoins', coins);

        this.scanCoinChangeTimeref = setTimeout(() => {
          this.scanCoinChange();
        }, this.scanCoinInterval);

        this.scanOrderbookTimeref = setTimeout(() => {
          this.scanOrderbookChange();
        }, 0);

        this.scanTickerTimeref = setTimeout(() => {
          this.scanTickerChange();
        }, 0);

        this.scanTradeTimeref = setTimeout(() => {
          this.scanTradeChange();
        }, 0);
      })
      .catch((err) => {
        if (!isKnownError(err)) {
          console.error(err);
        }

        setTimeout(() => {
          this.init();
        }, this.scanCoinInterval);
      });
  }

  start() {
    if (this.isStarted) {
      return;
    }
    this.isStarted = true;

    this.init();
  }

  stop() {
    if (!this.isStarted) {
      return;
    }
    this.isStarted = false;

    if (this.scanCoinChangeTimeref !== null) {
      clearTimeout(this.scanCoinChangeTimeref);
    }

    if (this.scanOrderbookTimeref !== null) {
      clearTimeout(this.scanOrderbookTimeref);
    }

    if (this.scanTickerTimeref !== null) {
      clearTimeout(this.scanTickerTimeref);
    }

    if (this.scanTradeTimeref !== null) {
      clearTimeout(this.scanTradeTimeref);
    }
  }
}

module.exports = ChangeScanner;
