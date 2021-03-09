const app = require("express")();
const cors = require("cors");
const server = require("http").createServer(app);
const io = require("socket.io")(server);

const call = require("./call");

app.use(cors());

require("dotenv").config();

const MODE = process.env.NODE_ENV; // development or production
const PORT = process.env.PORT || 9602;

const connected = {};

io.sockets.on("connection", client => {
  let room = null;
  let user = null;
  let roomId = null;
  let toMsg = () => {};

  // Load Room
  client.on("load:room", async data => {
    room = `room-${data.roomId}-${data.roomToken}`;
    user = `user-${data.userId}-${data.roomToken}`;
    roomId = data.roomId;

    // 해당 userId가 거래중인 거래에 해당 roomId가 있는지 체크 후 없으면 접속 끊기
    const isRoomExists = await call.checkRoomIsExists({
      userId: data.userId,
      roomId: data.roomId,
      roomToken: data.roomToken
    });

    if (!isRoomExists) {
      room = null;
      user = null;

      setTimeout(() => {
        try {
          client.disconnect();
        } catch (e) {
          console.error(e);
        }
      }, 10000);
      return;
    }

    toMsg = msg => {
      return {
        id: data.userId,
        name: data.userName,
        message: msg,
        timestamp: Date.now()
      };
    };

    // 해당 방의 채팅 기록 가져오기
    const allMsgs = await call.loadRoomChatData({ roomId: data.roomId });
    client.emit("load:room", allMsgs);
  });

  // Join Room
  client.on("join:room", async () => {
    if (!room || !user) {
      // 먼저 load를 해야 함
      setTimeout(() => {
        try {
          client.disconnect();
        } catch (e) {
          console.error(e);
        }
      }, 10000);
    }

    client.join(room);
    if (!connected[user]) {
      const msg = toMsg("__connect");
      io.sockets.in(room).emit("send:message", msg);
      await call.appendRoomChatData({ roomId: roomId, data: msg });
    }
    // eslint-disable-next-line require-atomic-updates
    connected[user] = connected[user] ? connected[user] + 1 : 1;
  });

  // Broadcast to room
  client.on("send:message", async data => {
    const msg = toMsg(data.message);
    io.sockets.in(room).emit("send:message", msg);
    await call.appendRoomChatData({ roomId: roomId, data: msg });
  });

  // Leave room
  client.on("disconnect", async () => {
    if (connected[user]) {
      if (connected[user] <= 1) {
        const msg = toMsg("__disconnect");
        io.sockets.in(room).emit("send:message", msg);
        await call.appendRoomChatData({ roomId: roomId, data: msg });
        delete connected[user];
      } else {
        connected[user] -= 1;
      }
    }
  });
});

server.listen(PORT, () => {
  console.log(`Server is running... on ${PORT} as ${MODE}`);
});

process.on("SIGINT", () => {
  console.log("Received SIGINT, shutting down gracefully");
  io.close();
  server.close();
  process.exit(0);
});
