const { exchangeDB } = require('./db');

module.exports = {
  async getNextOrderRequest() {
    const query1 = `
    SELECT *
    FROM order_request_queue r
    WHERE r.in_progress IN (0, 1)
    ORDER BY r.in_progress DESC, r.id ASC
    LIMIT 1
    `;

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    if (result1.length > 0) {
      return result1[0];
    }
    return null;
  },
  async getRequestsOfUsername({ username }) {
    const query1 = `
    SELECT r.username
    FROM order_request_queue r
    WHERE r.username = '${username}'
    AND r.in_progress IN (0, 1)
    LIMIT 1
    `;

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },
  async setOrderRequest({
    type, coin, amount, username,
  }) {
    const query1 = `
    INSERT INTO order_request_queue (
      in_progress,
      type,
      coin,
      amount,
      username
    ) VALUES (
      0,
      '${type}',
      '${coin}',
      '${amount}',
      '${username}'
    )`;

    console.log(query1);

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },

  async updateOrderRequestValue({
    id,
    inProgress,
    requestQty = false,
    requestValue = false,
    tradedQty = false,
    tradedValue = false,
    remainQty = false,
    remainValue = false,
    exchanges = false,
  }) {
    const query1 = `
    UPDATE
      order_request_queue
    SET
      ${inProgress ? `in_progress = ${inProgress},` : ''}
      ${requestQty !== false ? `request_qty = '${requestQty}',` : ''}
      ${requestValue !== false ? `request_value = '${requestValue}',` : ''}
      ${tradedQty !== false ? `traded_qty = '${tradedQty}',` : ''}
      ${tradedValue !== false ? `traded_value = '${tradedValue}',` : ''}
      ${remainQty !== false ? `remain_qty = '${remainQty}',` : ''}
      ${remainValue !== false ? `remain_value = '${remainValue}',` : ''}
      ${exchanges !== false ? `exchanges = '${exchanges}',` : ''}
      updated = NOW()
    WHERE
      id = ${id}
    `;

    console.log(query1);

    const execute1 = exchangeDB
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },
};
