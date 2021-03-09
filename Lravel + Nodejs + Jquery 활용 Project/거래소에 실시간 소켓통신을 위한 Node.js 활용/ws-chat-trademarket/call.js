const { dbSub } = require("./db");

module.exports = {
  async checkRoomIsExists({ userId, roomId, roomToken }) {
    const user_id = dbSub.escape(userId);
    const room_id = dbSub.escape(roomId);
    const room_token = dbSub.escape(roomToken);

    const query1 = `
    SELECT
      id
    FROM btc_p2p
    WHERE (b_id = ${user_id} OR s_id = ${user_id})
    AND chatroom_id = ${room_id}
    AND EXISTS
      (
        SELECT id
        FROM btc_p2p_chatroom
        WHERE id = ${room_id}
        AND token = ${room_token}
      )
    LIMIT 1
    `;

    const execute1 = dbSub
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    if (result1.length > 0) {
      return true;
    }
    return false;
  },
  async loadRoomChatData({ roomId }) {
    const room_id = dbSub.escape(roomId);

    const query1 = `
    SELECT data
    FROM btc_p2p_chatroom
    WHERE id = ${room_id}
    LIMIT 1
    `;

    const execute1 = dbSub
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    if (result1.length > 0) {
      return result1[0];
    }
    return null;
  },

  async appendRoomChatData({ roomId, data }) {
    const room_id = dbSub.escape(roomId);
    const id = data.id;
    const name = dbSub.escape(data.name);
    const message = dbSub.escape(data.message);
    const timestamp = data.timestamp;

    const query1 = `
    UPDATE
      btc_p2p_chatroom
    SET
      data = JSON_ARRAY_APPEND(data, '$', JSON_OBJECT('id', ${id}, 'name', ${name}, 'message', ${message}, 'timestamp', ${timestamp}))
    WHERE
      id = ${room_id}
    `;

    const execute1 = dbSub
      .promise()
      .query(query1)
      .then(data => data[0]);
    const result1 = await execute1;

    return result1;
  }
};
