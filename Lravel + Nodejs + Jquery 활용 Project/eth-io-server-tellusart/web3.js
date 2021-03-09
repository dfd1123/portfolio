const Web3 = require('web3');

const MODE = process.env.NODE_ENV; // development or production

const provider = new Web3.providers.HttpProvider(
  MODE === 'development'
    ? 'https://mainnet.infura.io/v3/7c234f257f8b4fbe827ce68964296263'
    : 'https://mainnet.infura.io/v3/4f29f02743eb4e248df5a56cdefc2ffb',
);

module.exports = () => new Web3(provider);
