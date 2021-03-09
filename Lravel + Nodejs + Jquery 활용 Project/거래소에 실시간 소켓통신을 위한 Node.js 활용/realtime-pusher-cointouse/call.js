const Decimal = require('decimal.js');
const pool = require('./db');

function formatNumber(num, fixed) {
  const parts = Decimal(num)
    .toFixed(fixed)
    .split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

  if (Number(parts.join('.')) === 0) {
    parts[0] = '0'; // -0 방지
  }

  return parts.join('.');
}

function formatOrderbookData(results, usdFixed) {
  return results.map(result => ({
    price: formatNumber(result.price, usdFixed),
    amt: formatNumber(result.amt, 8),
    total: formatNumber(Decimal.mul(result.price, result.amt), usdFixed),
    amount_percent: result.amount_percent.toFixed(4),
  }));
}

function formatTickerData(market, results) {
  return results.map(result => ({
    symbol: result.symbol,
    api: result.api,
    price_usd: formatNumber(
      result[`price_${market}`],
      result[`decimal_${market}`],
    ),
    percent_change_24h: formatNumber(
      result[`percent_change_24h_${market}`] || 0,
      2,
    ),
    price_change_24h: formatNumber(
      result[`price_change_24h_${market}`] || 0,
      result[`decimal_${market}`],
    ),
    max_price: formatNumber(
      result[`max_price_${market}`] || 0,
      result[`decimal_${market}`],
    ),
    min_price: formatNumber(
      result[`min_price_${market}`] || 0,
      result[`decimal_${market}`],
    ),
    last_trade_price_usd: formatNumber(
      result[`last_trade_price_${market}`],
      result[`decimal_${market}`],
    ),
    h24h_volume: formatNumber(result[`24h_volume_${market}`], 8),
  }));
}

function formatTradeData(results, usdFixed) {
  return results.map(result => ({
    price: formatNumber(result.buy_coin_price, usdFixed),
    amt: formatNumber(result.contract_coin_amt, 8),
    last_trade_kind: result.last_trade_kind,
    created: result.created,
    created_dt: result.created_dt,
  }));
}

async function getDecimalOfCoin({ market = 'usd', coin = 'btc' }) {
  const decimalStatement = `btc_coins WHERE api = '${coin}'`;
  const decimalQuery = `SELECT decimal_${market} from ${decimalStatement}`;
  const decimalExcute = pool
    .promise()
    .query(decimalQuery)
    .then(data => data[0]);

  const decimalResult = await decimalExcute;
  return decimalResult[0][`decimal_${market}`];
}

module.exports = {
  getListedCoins: async () => {
    const listedStatement = "btc_coins WHERE active = 1 and cointype != 'cash'";
    const listedQuery = `SELECT api coin FROM ${listedStatement}`;
    const listedExecute = pool
      .promise()
      .query(listedQuery)
      .then(data => data[0]);

    const listedResult = await listedExecute;

    return {
      coins: listedResult.map(row => row.coin),
    };
  },

  orderbook: async ({ market = 'usd', coin = 'btc' }) => {
    const limit = 15;
    const usdFixed = await getDecimalOfCoin({ market, coin });

    const sellStatement = `btc_ads_btc a WHERE currency = '${market}' and type = 'sell' and cointype = '${coin}' and status = 'OnProgress' and sell_COIN_amt > 0`;
    const sellQuery = `SELECT * FROM ( SELECT sum(sell_COIN_amt) amt, sell_coin_price price, currency FROM ${sellStatement} group by sell_coin_price, currency ORDER BY sell_coin_price ASC LIMIT ${limit} ) a ORDER BY cast(price as DECIMAL(21,8)) DESC`;
    const sellExecute = pool
      .promise()
      .query(sellQuery)
      .then(data => data[0]);

    const buyStatement = `btc_ads_btc WHERE currency = '${market}' and type = 'buy' and cointype = '${coin}' and status = 'OnProgress' and buy_COIN_amt > 0`;
    const buyQuery = `SELECT * from ( SELECT sum(buy_COIN_amt) amt, buy_coin_price price, currency  FROM ${buyStatement} group by buy_coin_price, currency ORDER BY buy_coin_price DESC ) a ORDER BY cast(price as DECIMAL(21,8)) DESC LIMIT ${limit}`;
    const buyExecute = pool
      .promise()
      .query(buyQuery)
      .then(data => data[0]);

    const [sells, buys] = await Promise.all([sellExecute, buyExecute]);

    const totalAmountSell = sells.reduce((a, b) => Decimal.add(a, b.amt), 0);
    const totalAmountBuy = buys.reduce((a, b) => Decimal.add(a, b.amt), 0);

    const sellsWithTotalAmount = sells.map((order) => {
      order.amount_percent = Decimal.div(order.amt, totalAmountSell);
      return order;
    });
    const buysWithTotalAmount = buys.map((order) => {
      order.amount_percent = Decimal.div(order.amt, totalAmountBuy);
      return order;
    });

    return {
      cointype: coin,
      sell_list_cnt: sells.length,
      buy_list_cnt: buys.length,
      sell_list: formatOrderbookData(sellsWithTotalAmount, usdFixed),
      buy_list: formatOrderbookData(buysWithTotalAmount, usdFixed),
    };
  },

  ticker: async ({ market = 'usd', coin = 'btc' }) => {
    const tickerStatement = `btc_coins where api = '${coin}'`;
    const tickerQuery = `SELECT symbol,api,price_${market},percent_change_24h_${market},price_change_24h_${market},max_price_${market},min_price_${market},last_trade_price_${market},24h_volume_${market},decimal_${market} FROM ${tickerStatement} ORDER BY sort_num asc`;
    const tickerExecute = pool
      .promise()
      .query(tickerQuery)
      .then(data => formatTickerData(market, data[0]));

    const tickerResult = await tickerExecute;

    return {
      coin_data_cnt: tickerResult.length,
      coin_data: tickerResult,
    };
  },

  trade: async ({ market = 'usd', coin = 'btc' }) => {
    const usdFixed = await getDecimalOfCoin({ market, coin });

    const tradeStatement = `btc_trades_COIN_btc where currency = '${market}' and cointype = '${coin}'`;
    const tradeQuery = `SELECT buy_coin_price, contract_coin_amt, last_trade_kind, date_format(created_dt,  '%Y-%m-%d %H:%i:%s') created_dt, created FROM ${tradeStatement} ORDER BY id DESC LIMIT 40`;
    const tradeExecute = pool
      .promise()
      .query(tradeQuery)
      .then(data => formatTradeData(data[0], usdFixed));

    const tradeResult = await tradeExecute;

    return {
      trade_list_cnt: tradeResult.length,
      trade_list: tradeResult,
    };
  },
};
