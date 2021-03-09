const uuidv4 = require('uuid/v4');
const XCoinAPI = require('../lib/XCoinAPI');
const stateGenerator = require('../generator/bithumb-state-generator');

require('dotenv').config();

function calibratedInterval(timestamp, interval) {
  const passedTime = Date.now() - timestamp;
  return passedTime < interval ? interval - passedTime : 0;
}
module.exports = class BithumbOrderer {
  constructor() {
    this.currentTransaction = null;
    this.xcoinAPI = new XCoinAPI(
      process.env.BITHUMB_API_KEY_A,
      process.env.BITHUMB_API_KEY_B,
    );
    this.call = (api, params, callback) => {
      this.xcoinAPI.xcoinApiCall(api, params, callback);
    };
    this.callInterval = 250;
    this.requestInterval = 1000;
    this.startTimestamp = 0;
    this.loopTimestamp = 0;
  }

  handleTransaction() {
    const { txid, action, state } = this.currentTransaction;
    const { api, params, started } = state.next().value;

    console.log(
      `Request -> ${JSON.stringify({ txid, request: { api, params } })}`,
    );

    if (started) {
      this.startTimestamp = Date.now();
    }
    this.loopTimestamp = Date.now();

    this.call(api, params, (error, response, result) => {
      try {
        if (error) {
          throw error;
        }

        const parsedResult = JSON.parse(result);
        console.log(
          `Response -> ${JSON.stringify({ txid, response: parsedResult })}`,
        );

        const next = state.next(parsedResult);
        console.log(`Result -> ${JSON.stringify({ txid, info: next.value })}`);

        if (next.done) {
          if (next.value.result.status === 'error') {
            action.reject(next.value.result);
          } else {
            action.resolve(next.value.result);
          }
          this.loopExecuted();
        } else {
          this.loopContinue();
          console.log('');
        }
      } catch (e) {
        action.reject(e);
        this.loopExecuted();
      }
    });
  }

  loopExecuted() {
    setTimeout(() => {
      this.currentTransaction = null;
    }, calibratedInterval(this.startTimestamp, this.requestInterval));
  }

  loopContinue() {
    setTimeout(() => {
      this.handleTransaction();
    }, calibratedInterval(this.loopTimestamp, this.callInterval));
  }

  requestTransaction(parameter) {
    return new Promise((resolve, reject) => {
      if (this.isReady()) {
        this.currentTransaction = {
          txid: uuidv4().substring(0, 8),
          action: { resolve, reject },
          state: stateGenerator(parameter),
        };

        setTimeout(() => {
          this.handleTransaction();
        }, calibratedInterval(this.startTimestamp, this.requestInterval));
      } else {
        reject();
      }
    });
  }

  isReady() {
    return this.currentTransaction === null;
  }
};
