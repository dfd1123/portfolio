const request = require('request');
const uuidv4 = require('uuid/v4');
const crypto = require('crypto');
const stateGenerator = require('../generator/coinone-state-generator');

require('dotenv').config();

function calibratedInterval(timestamp, interval) {
  const passedTime = Date.now() - timestamp;
  return passedTime < interval ? interval - passedTime : 0;
}

module.exports = class CoinoneOrderer {
  constructor() {
    this.currentTransaction = null;
    this.access_token = process.env.COINONE_ACCESS_TOKEN;
    this.secret_key = process.env.COINONE_SECRET_TOKEN;

    this.call = (api, params, callback) => {
      const body = {
        access_token: this.access_token,
        nonce: Date.now(),
        ...params,
      };
      const payload = Buffer.from(JSON.stringify(body)).toString('base64');
      const hmac = crypto.createHmac('sha512', this.secret_key.toUpperCase());
      const signature = hmac.update(payload, 'utf8').digest('hex');
      const options = {
        method: 'POST',
        url: `https://api.coinone.co.kr${api}`,
        headers: {
          'X-COINONE-PAYLOAD': payload,
          'X-COINONE-SIGNATURE': signature,
        },
        json: body,
      };

      request(options, callback);
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

        console.log(
          `Response -> ${JSON.stringify({ txid, response: result })}`,
        );

        const next = state.next(result);
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
