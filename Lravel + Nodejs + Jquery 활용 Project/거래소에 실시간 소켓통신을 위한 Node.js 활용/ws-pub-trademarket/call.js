const Decimal = require("decimal.js");
const _ = require("lodash");
const pool = require("./db");

function formatNumber(num, fixed) {
  const numberString = Decimal(num).toFixed(fixed);
  const parts = numberString.split(".");

  if (Object.is(Number(numberString), -0)) {
    parts[0] = "0";
  } else {
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  return parts.join(".");
}

function formatOrderbookData(results) {
  const orderbook = _.groupBy(results, "kind");
  const sell = orderbook.sell || [];
  const buy = orderbook.buy || [];

  return [sell, buy].map(kind =>
    kind.map(order => ({
      price: formatNumber(order.price, order.fixed),
      amt: formatNumber(order.amt, 8),
      total: formatNumber(order.total, order.fixed)
    }))
  );
}

function formatTickerData(market, results) {
  return results.map(result => ({
    api: result.api,
    percent_change_24h: formatNumber(
      result[`percent_change_24h_${market}`] || 0,
      2
    ),
    price_change_24h: formatNumber(
      result[`price_change_24h_${market}`] || 0,
      result[`decimal_${market}`]
    ),
    max_price: formatNumber(
      result[`max_price_${market}`] || 0,
      result[`decimal_${market}`]
    ),
    min_price: formatNumber(
      result[`min_price_${market}`] || 0,
      result[`decimal_${market}`]
    ),
    last_trade_price: formatNumber(
      result[`last_trade_price_${market}`],
      result[`decimal_${market}`]
    ),
    h24h_volume: formatNumber(result[`24h_volume_${market}`], 8)
  }));
}

function formatTradeData(results) {
  return results.map(result => ({
    price: formatNumber(result.price, result.fixed),
    amt: formatNumber(result.amt, 8),
    last_trade_kind: result.last_trade_kind,
    created: result.created
  }));
}

module.exports = {
  coin: async () => {
    const listedQuery = `
    SELECT
      api coin
    FROM
      btc_coins
    WHERE 1 = 1
      and active = 1
      and cointype != 'cash'`;
    const listedExecute = pool
      .promise()
      .query(listedQuery)
      .then(data => data[0]);

    const listedResult = await listedExecute;

    return listedResult.map(row => row.coin);
  },

  orderbook: async ({ market = "krw", coin = "btc", limit = 15 }) => {
    const orderbookQuery = `
    SELECT
      c.*,
      d.*
    FROM
    (
      SELECT
        a.*,
        a.amt * a.price total
      FROM
      (
          SELECT
            type kind,
            currency,
            sum(sell_COIN_amt) amt,
            sell_coin_price price
          FROM btc_ads_btc
          WHERE 1 = 1
            AND currency = '${market}'
            AND type = 'sell'
            AND cointype = '${coin}'
            AND status = 'OnProgress'
            AND sell_COIN_amt > 0
          GROUP BY sell_coin_price, currency ORDER BY sell_coin_price ASC LIMIT ${limit}
      ) a

      UNION ALL

      SELECT
          b.*,
          b.amt * b.price total
      FROM
      (
          SELECT
            type kind,
            currency,
            sum(buy_COIN_amt) amt,
            buy_coin_price price
          FROM btc_ads_btc
            WHERE currency = '${market}'
            AND type = 'buy'
            AND cointype = '${coin}'
            AND status = 'OnProgress'
            AND buy_COIN_amt > 0
          GROUP BY buy_coin_price, currency ORDER BY buy_coin_price DESC LIMIT ${limit}
      ) b
    ) c,
    (
      SELECT decimal_${market} fixed FROM btc_coins WHERE api = '${coin}' LIMIT 1
    ) d
    ORDER BY kind DESC, cast(price as DECIMAL(21,8)) DESC
    `;
    const orderbookExecute = pool
      .promise()
      .query(orderbookQuery)
      .then(data => formatOrderbookData(data[0]));

    const orderbook = await orderbookExecute;

    return orderbook;
  },

  ticker: async ({ market = "krw", coin = "btc" }) => {
    const tickerQuery = `
    SELECT
      symbol,
      api,
      percent_change_24h_${market},
      price_change_24h_${market},
      max_price_${market},
      min_price_${market},
      last_trade_price_${market},
      24h_volume_${market},
      decimal_${market}
    FROM btc_coins
    WHERE api = '${coin}'
    ORDER BY sort_num ASC`;
    const tickerExecute = pool
      .promise()
      .query(tickerQuery)
      .then(data => formatTickerData(market, data[0]));

    const ticker = await tickerExecute;

    return ticker;
  },

  trade: async ({ market = "krw", coin = "btc" }) => {
    const tradeQuery = `
    SELECT
      a.buy_coin_price price,
      a.contract_coin_amt amt,
      a.last_trade_kind,
      a.created,
      b.fixed
    FROM btc_trades_COIN_btc a,
    (
      SELECT decimal_${market} fixed FROM btc_coins WHERE api = '${coin}' LIMIT 1
    ) b
    WHERE currency = '${market}' and cointype = '${coin}'
    ORDER BY id DESC LIMIT 40`;
    const tradeExecute = pool
      .promise()
      .query(tradeQuery)
      .then(data => formatTradeData(data[0]));

    const trade = await tradeExecute;

    return trade;
  }
};
