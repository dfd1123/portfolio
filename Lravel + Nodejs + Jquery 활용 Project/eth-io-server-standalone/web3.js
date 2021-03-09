const Web3 = require('web3');

require('dotenv').config();

const MODE = process.env.NODE_ENV; // development or production

const provider = new Web3.providers.HttpProvider(
  MODE === 'development'
    ? `https://${process.env.INFURA_TESTNET}`
    : `https://${process.env.INFURA_MAINNET}`,
);

module.exports = () => new Web3(provider);
