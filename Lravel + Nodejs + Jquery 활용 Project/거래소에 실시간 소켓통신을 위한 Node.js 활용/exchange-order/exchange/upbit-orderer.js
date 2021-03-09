const request = require('request');
const uuidv4 = require('uuid/v4');
const jwt = require('jsonwebtoken');
const crypto = require('crypto');
const querystring = require('querystring');

require('dotenv').config();

const stateGenerator = require('../generator/upbit-state-generator');

function calibratedInterval(timestamp, interval) {
  const passedTime = Date.now() - timestamp;
  return passedTime < interval ? interval - passedTime : 0;
}

module.exports = class UpbitOrderer {
  constructor() {
    this.currentTransaction = null;

    // 사용기한 만료 시 재발급 필요 (1년 기간)
    this.access_key = process.env.UPBIT_ACCESS_KEY;
    this.secret_key = process.env.UPBIT_SECRET_KEY;

    this.generateToken = (uuid, query) => {
      const hash = crypto.createHash('sha512');
      const queryHash = hash.update(query, 'utf8').digest('hex');
      const payload = {
        access_key: this.access_key,
        nonce: uuid,
        query_hash: queryHash,
        query_hash_alg: 'SHA512',
      };
      const jwtToken = jwt.sign(payload, this.secret_key);

      return jwtToken;
    };

    this.call = (method, api, params, callback) => {
      const uuid = uuidv4();
      const query = querystring.stringify(params);
      const token = this.generateToken(uuid, query);
      const options = {
        method,
        url: `https://api.upbit.com/v1${api}${
          method === 'GET' || method === 'DELETE' ? `?${query}` : ''
        }`,
        headers: { Authorization: `Bearer ${token}` },
        json: true,
      };

      if (method === 'POST') {
        options.json = params;
      }

      request(options, callback);
    };

    this.callInterval = 250;
    this.requestInterval = 1000;
    this.startTimestamp = 0;
    this.loopTimestamp = 0;
  }

  handleTransaction() {
    const { txid, action, state } = this.currentTransaction;
    const {
      method, api, params, started,
    } = state.next().value;

    console.log(
      `Request -> ${JSON.stringify({
        txid,
        request: { method, api, params },
      })}`,
    );

    if (started) {
      this.startTimestamp = Date.now();
    }
    this.loopTimestamp = Date.now();

    this.call(method, api, params, (error, response, result) => {
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
