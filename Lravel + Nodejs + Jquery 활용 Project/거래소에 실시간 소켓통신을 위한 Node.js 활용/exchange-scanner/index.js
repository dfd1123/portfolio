const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const State = require('./state');
const UpbitScanner = require('./exchanges/upbit-scanner');
const BithumbScanner = require('./exchanges/bithumb-scanner');
const CoinoneScanner = require('./exchanges/coinone-scanner');

require('dotenv').config();

const PORT = process.env.PORT || 9600;

const upbitScanner = new UpbitScanner({
  eid: 0,
  name: 'upbit',
  coins: ['BTC', 'ETH', 'LTC', 'DASH', 'BTG'],
});
const bithumbScanner = new BithumbScanner({
  eid: 1,
  name: 'bithumb',
  coins: ['BTC', 'ETH'],
});
const coinoneScanner = new CoinoneScanner({
  eid: 2,
  name: 'coinone',
  coins: ['BTC', 'ETH', 'LTC'],
});
const state = new State([upbitScanner, bithumbScanner, coinoneScanner]);

upbitScanner
  .onResult((result) => {
    if (result === 'error') {
      state.clearOrderbook(upbitScanner.eid);
    } else {
      state.updateOrderbook(result);
    }
  })
  .start();

bithumbScanner
  .onResult((result) => {
    if (result === 'error') {
      state.clearOrderbook(bithumbScanner.eid);
    } else {
      state.updateOrderbook(result);
    }
  })
  .start();

coinoneScanner
  .onResult((result) => {
    if (result === 'error') {
      state.clearOrderbook(coinoneScanner.eid);
    } else {
      state.updateOrderbook(result);
    }
  })
  .start();

const app = express();
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

app.post('/orderbook', (req, res) => {
  const { type } = req.body;
  let { coin, amount } = req.body;

  if (!type || !coin || !amount || Number.isNaN(Number(amount))) {
    return res.status(404).send({ error: 'Invalid parameters' });
  }

  coin = coin.toUpperCase();
  amount = Number(amount);

  if (!['buy', 'sell'].includes(type)) {
    return res.status(404).send({ error: 'Invalid order type' });
  }

  if (!['BTC', 'ETH', 'LTC', 'DASH', 'BTG'].includes(coin)) {
    return res.status(404).send({ error: 'Unknown coin' });
  }

  if (type === 'buy' && amount < 1) {
    return res.status(404).send({ error: 'Amount must be greater than 1' });
  }

  if (type === 'sell' && amount <= 0) {
    return res.status(404).send({ error: 'Amount cannot be 0 or less' });
  }

  const orderPlan = type === 'buy'
    ? state.getSimpleBuyPlan(coin, amount)
    : state.getSimpleSellPlan(coin, amount);

  if (orderPlan.length === 0) {
    return res.status(404).send({ error: 'Too much orders' });
  }

  if (orderPlan.filter(x => x.value === '0').length > 0) {
    return res.status(404).send({ error: 'Too small orders' });
  }

  return res.json({
    coin,
    type,
    timestamp: Date.now(),
    orders: orderPlan,
  });
});

app.post('/scanner', (req, res) => {
  let { eid } = req.body;
  const { scan } = req.body;

  if (!eid || !scan) {
    return res.status(404).send({ error: 'Invalid parameters' });
  }

  eid = Number(eid);

  if (
    !state
      .getScannerList()
      .map(x => x.eid)
      .includes(eid)
  ) {
    return res.status(404).send({ error: 'Unknown eid' });
  }

  if (!['on', 'off'].includes(scan)) {
    return res.status(404).send({ error: 'Invalid scan value' });
  }

  const { scanner, name } = state.getExchange(eid);
  if (!scanner) {
    return res.status(404).send({ error: `Scanner not found: ${eid}` });
  }

  const scanning = scan === 'on';
  if (scanning) {
    scanner.start();
  } else {
    scanner.stop();
    state.clearOrderbook(eid);
  }

  return res.json({
    eid,
    name,
    timestamp: Date.now(),
    scanning,
  });
});

app.post('/scanner/list', (req, res) => res.json({
  timestamp: Date.now(),
  scanners: state.getScannerList(),
}));

app.listen(PORT, () => {
  const msg = `Server is running... on ${PORT}`;
  console.log(msg);
});
