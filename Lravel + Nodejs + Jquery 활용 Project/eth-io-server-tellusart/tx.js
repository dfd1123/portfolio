/* eslint-disable no-await-in-loop */
/* eslint-disable no-restricted-syntax */
const Tx = require('ethereumjs-tx');
const Decimal = require('decimal.js');
const web3Init = require('./web3');

const fullABI = [
  {
    constant: true,
    inputs: [],
    name: 'name',
    outputs: [{ name: '', type: 'string' }],
    type: 'function',
  },
  {
    constant: false,
    inputs: [
      { name: '_spender', type: 'address' },
      { name: '_value', type: 'uint256' },
    ],
    name: 'approve',
    outputs: [{ name: 'success', type: 'bool' }],
    type: 'function',
  },
  {
    constant: true,
    inputs: [],
    name: 'totalSupply',
    outputs: [{ name: '', type: 'uint256' }],
    type: 'function',
  },
  {
    constant: false,
    inputs: [
      { name: '_from', type: 'address' },
      { name: '_to', type: 'address' },
      { name: '_value', type: 'uint256' },
    ],
    name: 'transferFrom',
    outputs: [{ name: 'success', type: 'bool' }],
    type: 'function',
  },
  {
    constant: true,
    inputs: [],
    name: 'decimals',
    outputs: [{ name: '', type: 'uint8' }],
    type: 'function',
  },
  {
    constant: true,
    inputs: [],
    name: 'version',
    outputs: [{ name: '', type: 'string' }],
    type: 'function',
  },
  {
    constant: true,
    inputs: [{ name: '_owner', type: 'address' }],
    name: 'balanceOf',
    outputs: [{ name: 'balance', type: 'uint256' }],
    type: 'function',
  },
  {
    constant: true,
    inputs: [],
    name: 'symbol',
    outputs: [{ name: '', type: 'string' }],
    type: 'function',
  },
  {
    constant: false,
    inputs: [
      { name: '_to', type: 'address' },
      { name: '_value', type: 'uint256' },
    ],
    name: 'transfer',
    outputs: [{ name: 'success', type: 'bool' }],
    type: 'function',
  },
  {
    constant: false,
    inputs: [
      { name: '_spender', type: 'address' },
      { name: '_value', type: 'uint256' },
      { name: '_extraData', type: 'bytes' },
    ],
    name: 'approveAndCall',
    outputs: [{ name: 'success', type: 'bool' }],
    type: 'function',
  },
  {
    constant: true,
    inputs: [
      { name: '_owner', type: 'address' },
      { name: '_spender', type: 'address' },
    ],
    name: 'allowance',
    outputs: [{ name: 'remaining', type: 'uint256' }],
    type: 'function',
  },
  {
    inputs: [
      { name: '_initialAmount', type: 'uint256' },
      { name: '_tokenName', type: 'string' },
      { name: '_decimalUnits', type: 'uint8' },
      { name: '_tokenSymbol', type: 'string' },
    ],
    type: 'constructor',
  },
  {
    anonymous: false,
    inputs: [
      { indexed: true, name: '_from', type: 'address' },
      { indexed: true, name: '_to', type: 'address' },
      { indexed: false, name: '_value', type: 'uint256' },
    ],
    name: 'Transfer',
    type: 'event',
  },
  {
    anonymous: false,
    inputs: [
      { indexed: true, name: '_owner', type: 'address' },
      { indexed: true, name: '_spender', type: 'address' },
      { indexed: false, name: '_value', type: 'uint256' },
    ],
    name: 'Approval',
    type: 'event',
  },
];

async function getAutoAdjustedGasPrice(web3, maxPrice) {
  const gasPrice = await web3.eth.getGasPrice(); // wei 단위
  let checkGasPrice = new Decimal(gasPrice);

  // 항상 현재 가스가격보다 1gwei 추가
  checkGasPrice = checkGasPrice.plus(web3.utils.toWei('1', 'gwei'));

  if (checkGasPrice.comparedTo(web3.utils.toWei(maxPrice, 'gwei')) === 1) {
    // 가스가격이 maxPrice gwei보다 크면 maxPrice gwei로
    return web3.utils.toWei(maxPrice, 'gwei');
  }

  return checkGasPrice.toFixed();
}

function tryRecoverKnownTransaction(err) {
  const knownTransactionErrorMsg = 'Node error: {"code":-32000,"message":"known transaction: ';
  if (err.message.startsWith(knownTransactionErrorMsg)) {
    const recoveredTransactionHash = `0x${err.message
      .replace(knownTransactionErrorMsg, '')
      .replace('"}', '')}`;

    return recoveredTransactionHash;
  }

  return null;
}

module.exports = {
  async sendEthTransaction(
    from,
    to,
    value,
    fromPrivateKey,
    includeFee = false,
  ) {
    try {
      const web3 = web3Init();
      const nonce = await web3.eth.getTransactionCount(from);
      const gasPrice = await getAutoAdjustedGasPrice(web3, '50'); // wei 단위
      const gasLimit = '21000';
      const decimal = 18; // ether 자리수

      let amount = 0;

      if (includeFee) {
        const calculatedAmount = new Decimal(value).minus(
          Decimal.mul(gasPrice, gasLimit).div(10 ** decimal),
        );

        if (calculatedAmount.isNegative()) {
          return null;
        }

        amount = calculatedAmount.toFixed(decimal);
      } else {
        amount = value;
      }

      const rawTx = {
        to,
        value: web3.utils.toHex(web3.utils.toWei(amount, 'ether')),
        nonce: web3.utils.toHex(nonce),
        gasPrice: web3.utils.toHex(gasPrice),
        gasLimit: web3.utils.toHex(gasLimit),
      };

      const tx = new Tx(rawTx);
      tx.sign(Buffer.from(fromPrivateKey.substring(2), 'hex'));

      const serializedTx = tx.serialize();
      const signedTx = `0x${serializedTx.toString('hex')}`;

      /*
      console.log('start transaction');

      console.log('from', from);
      console.log('to', to);
      console.log('value', value);
      console.log('amount', amount);
      console.log('nonce', nonce);
      console.log('gasPrice', gasPrice);
      console.log('gasLimit', gasLimit);
      console.log('signedTx', signedTx);
      */

      const transactionHash = await new Promise((resolve, reject) => {
        web3.eth.sendSignedTransaction(signedTx, (err, result) => {
          if (err) {
            const recoveredTransactionHash = tryRecoverKnownTransaction(err);
            if (recoveredTransactionHash === null) {
              reject(err);
            } else {
              console.log(
                `Recovered TransactionHash: ${recoveredTransactionHash}`,
              );
              resolve(recoveredTransactionHash);
            }
          } else {
            resolve(result);
          }
        });
      });

      // console.log('end transaction');

      return transactionHash;
    } catch (err) {
      throw err;
    }
  },

  async sendErcTransaction(
    from,
    to,
    value,
    fromPrivateKey,
    contractAddress,
    decimals,
  ) {
    try {
      const web3 = web3Init();
      const nonce = await web3.eth.getTransactionCount(from);
      const gasPrice = await getAutoAdjustedGasPrice(web3, '50'); // wei 단위
      const gasLimit = '100000';
      const transferAmount = new Decimal(value).mul(10 ** decimals).toFixed();

      let contract = new web3.eth.Contract(fullABI, contractAddress);
      const balance = await contract.methods.balanceOf(from).call();
      const currentBalance = new Decimal(balance.toString());

      if (
        currentBalance.isZero()
        || currentBalance.comparedTo(transferAmount) === -1
      ) {
        throw new Error('토큰 잔액 부족');
      }

      const rawTx = {
        from,
        to: contractAddress,
        nonce: web3.utils.toHex(nonce),
        gasPrice: web3.utils.toHex(gasPrice),
        gasLimit: web3.utils.toHex(gasLimit),
        value: '0x0',
        data: contract.methods
          .transfer(to, web3.utils.toHex(transferAmount))
          .encodeABI(),
      };

      contract = null;

      const tx = new Tx(rawTx);
      tx.sign(Buffer.from(fromPrivateKey.substring(2), 'hex'));

      const serializedTx = tx.serialize();
      const signedTx = `0x${serializedTx.toString('hex')}`;

      /*
      console.log('start transaction');

      console.log('from', from);
      console.log('to', to);
      console.log('value', value);
      console.log('nonce', nonce);
      console.log('gasPrice', gasPrice);
      console.log('gasLimit', gasLimit);
      console.log('signedTx', signedTx);
      */

      const transactionHash = await new Promise((resolve, reject) => {
        web3.eth.sendSignedTransaction(signedTx, (err, result) => {
          if (err) {
            const recoveredTransactionHash = tryRecoverKnownTransaction(err);
            if (recoveredTransactionHash === null) {
              reject(err);
            } else {
              console.log(
                `Recovered TransactionHash: ${recoveredTransactionHash}`,
              );
              resolve(recoveredTransactionHash);
            }
          } else {
            resolve(result);
          }
        });
      });

      // console.log('end transaction');

      return transactionHash;
    } catch (err) {
      throw err;
    }
  },

  async getTransactionReceipt(transactionHash) {
    try {
      const web3 = web3Init();
      const result1 = await web3.eth.getTransactionReceipt(transactionHash);
      return result1;
    } catch (err) {
      throw err;
    }
  },

  async getTransaction(transactionHash) {
    try {
      const web3 = web3Init();
      const result1 = await web3.eth.getTransaction(transactionHash);
      return result1;
    } catch (err) {
      throw err;
    }
  },

  async getAddressEthBalance(address) {
    try {
      let web3 = web3Init();
      const balance = await Promise.race([
        web3.eth.getBalance(address),
        this.timeout(30 * 1000),
      ]);
      web3 = null;

      return balance;
    } catch (err) {
      throw err;
    }
  },

  async getAddressTokenBalances(address, tokens) {
    try {
      let web3 = web3Init();

      const balances = {};
      for (const contractAddress of tokens) {
        let contract = new web3.eth.Contract(fullABI, contractAddress);
        let balance = await Promise.race([
          contract.methods.balanceOf(address).call(),
          this.timeout(30 * 1000),
        ]);

        if (balance !== null) {
          balances[contractAddress] = balance.toString(); // 가끔씩 알수없는 이유로 null을 리턴함
        }
        balance = null;
        contract = null;
      }
      web3 = null;

      return balances;
    } catch (err) {
      throw err;
    }
  },

  timeout(ms) {
    return new Promise((resolve, reject) => {
      const id = setTimeout(() => {
        clearTimeout(id);
        reject(new Error('contract.methods.balanceOf().call() timeout'));
      }, ms);
    });
  },
};
