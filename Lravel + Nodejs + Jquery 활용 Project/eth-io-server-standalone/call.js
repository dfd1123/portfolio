const pool = require('./db');

async function getRequest({ inProgress }) {
  const query1 = `
  SELECT
    r.id,
    r.request_type,
    r.coin_kind,
    r.request_user_id,
    r.request_address,
    r.request_amount,
    r.request_status,
    r.pending_tx,
    c.coin_type,
    c.coin_decimals,
    c.contract_address
  FROM eth_io_request r
    JOIN eth_io_coin c ON r.coin_kind = c.coin_kind
  WHERE in_progress = ${inProgress}
  ORDER BY r.id ASC
  LIMIT 1
  `;

  const execute1 = pool
    .promise()
    .query(query1)
    .then(data => data[0]);
  const result1 = await execute1;

  if (result1.length > 0) {
    return result1[0];
  }

  return null;
}

module.exports = {
  async getInProgressRequest() {
    const result1 = await getRequest({ inProgress: 1 });

    return result1;
  },

  async getNextRequest() {
    const result1 = await getRequest({ inProgress: 0 });

    return result1;
  },

  async setNewDepositRequest({
    coinKind,
    requestUserId,
    requestAddress,
    requestAmount,
  }) {
    const query1 = `
    INSERT INTO eth_io_request (
        in_progress,
        request_type,
        coin_kind,
        request_user_id,
        request_address,
        request_amount,
        request_status,
        created,
        updated
      )
    VALUES (
        0,
        'deposit',
        '${coinKind}',
        '${requestUserId}',
        '${requestAddress}',
        '${requestAmount}',
        'deposit_requested',
        NOW(),
        NOW()
      )
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },

  async setNewWithdrawRequest({
    requestId,
    coinKind,
    requestUserId,
    requestAddress,
    requestAmount,
  }) {
    const query1 = `
    INSERT INTO eth_io_request (
        request_id,
        in_progress,
        request_type,
        coin_kind,
        request_user_id,
        request_address,
        request_amount,
        request_status,
        created,
        updated
      )
    VALUES (
        ${requestId},
        0,
        'withdraw',
        '${coinKind}',
        '${requestUserId}',
        '${requestAddress}',
        '${requestAmount}',
        'withdraw_requested',
        NOW(),
        NOW()
      )
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },

  async updateRequestValue({
    id,
    inProgress = null,
    requestStatus = null,
    pendingTx = false,
    confirmTx = false,
  }) {
    const setPendingTx = pendingTx !== false
      ? `pending_tx=${pendingTx === null ? 'NULL' : `'${pendingTx}'`},`
      : '';

    const setConfirmTx = confirmTx !== false
      ? `confirm_tx=${confirmTx === null ? 'NULL' : `'${confirmTx}'`},`
      : '';

    const query1 = `
    UPDATE
      eth_io_request
    SET
      ${inProgress ? `in_progress=${inProgress},` : ''}
      ${requestStatus ? `request_status='${requestStatus}',` : ''}
      ${setPendingTx}
      ${setConfirmTx}
      updated=NOW()
    WHERE
      id=${id}
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },

  async getAccountFromAddress({ address }) {
    const query1 = `
    SELECT
      a.id,
      a.user_id,
      a.address,
      a.private
    FROM eth_io_account a
    WHERE address = '${address}'
    LIMIT 1
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    if (result1.length > 0) {
      return result1[0];
    }
    return null;
  },

  async getAccountFromUserId({ userId }) {
    const query1 = `
    SELECT
      a.id,
      a.user_id,
      a.address,
      a.private
    FROM eth_io_account a
    WHERE user_id = '${userId}'
    LIMIT 1
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    if (result1.length > 0) {
      return result1[0];
    }
    return null;
  },

  async getNotInProgressOrFailAddressInfos() {
    const query1 = `
    SELECT
      a.id,
      a.user_id,
      a.address
    FROM eth_io_account a
    WHERE NOT EXISTS (
        SELECT
          r.request_address
        FROM eth_io_request r
        WHERE a.address = r.request_address
        AND r.in_progress IN (0, 1, 3)
      )
    ORDER BY RAND()
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },

  async getTokenInfos() {
    const query1 = `
    SELECT
      c.coin_kind,
      c.contract_address,
      c.coin_decimals
    FROM eth_io_coin c
    WHERE c.coin_type = 'token'
    ORDER BY c.id ASC
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },

  async getCompletedRequests() {
    const query1 = `
    SELECT
      r.id,
      r.request_id,
      r.coin_kind,
      r.request_type,
      r.request_user_id,
      r.request_address,
      r.request_amount,
      r.confirm_tx
    FROM eth_io_request r
    WHERE r.in_progress = 2
      AND r.request_status IN ('deposit_completed', 'withdraw_completed')
    ORDER BY r.id ASC
    `;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },

  async addNewAddress({ userId, address, privateKey }) {
    // 생성된 주소 정보를 DB에 추가
    const query1 = `INSERT INTO eth_io_account SET user_id=${userId}, address='${address}', private='${privateKey}'`;

    const execute1 = pool
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  },
};
