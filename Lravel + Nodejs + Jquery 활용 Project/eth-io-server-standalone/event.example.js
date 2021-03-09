/* eslint-disable indent */
/* eslint-disable no-empty-function */
/* eslint-disable no-unused-vars */
const pool = require('./db');
const call = require('./call');
const tx = require('./tx');

require('dotenv').config();

module.exports = {
  async onCentralWithdrawConfirmed(request) {
    // 출금 컨펌 완료 시 작업
  },
  async onCentralDepositConfirmed(request) {
    // 입금 컨펌 완료 시 작업
  },
};
