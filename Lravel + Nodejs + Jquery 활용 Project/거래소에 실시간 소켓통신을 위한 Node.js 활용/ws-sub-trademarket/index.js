const WebSocket = require("ws");
const _ = require("lodash");

require("dotenv").config();

const MODE = process.env.NODE_ENV; // development or production
const HOST = process.env.HOST || "0.0.0.0";
const PORT = process.env.PORT || 8091;
const PUB_HOST = process.env.PUB_HOST || "localhost";
const PUB_PORT = process.env.PUB_PORT || 8090;

const datas = new Map();

let server = null;
let markets = [];
let types = [];
let coins = [];

/**
 * 거래소 클라이언트 웹소켓 통신
 */
function listen() {
  const wss = new WebSocket.Server({ host: HOST, port: PORT });

  wss.on("connection", ws => {
    ws.isAlive = true;
    ws.requests = new Set();

    ws.on("message", message => {
      try {
        if (message === "[]") {
          ws.isAlive = true;
          return;
        }

        const request = JSON.parse(message);
        if (!_.isArray(request)) {
          send(ws, { r: "error", m: "Invalid request" });
          return;
        }

        for (let item of request) {
          for (let market of item.market ? [item.market] : markets) {
            for (let type of item.type ? [item.type] : types) {
              for (let coin of item.coins ? item.coins : coins) {
                const key = JSON.stringify([market, type, coin]);
                const data = datas.get(key);

                if (data) {
                  send(ws, { market, type, coin, data });
                }
                ws.requests.add(key);
              }
            }
          }
        }
      } catch (e) {
        console.error(e);
        send(ws, { r: "error", m: "Internal error" });
      }
    });

    ws.on("close", () => {
      ws.isAlive = null;
      ws.requests = null;
      ws = null;
    });
  });

  /**
   * 거래소 클라이언트 웹소켓 주기적으로 정리
   */
  wss.on("listening", () =>
    setTimeout(function ping() {
      try {
        for (let ws of wss.clients) {
          if (ws.isAlive === false) {
            ws.terminate();
            return;
          }

          ws.isAlive = false;
          ws.send("[]");
        }
      } catch (e) {
        if (!isKnownError(e)) {
          console.error(e);
        }
      } finally {
        setTimeout(() => ping(), 10000);
      }
    }, 10000)
  );

  return wss;
}

/**
 * Pub Server와 통신 연결
 */
function subscriber() {
  let ws = new WebSocket(`ws://${PUB_HOST}:${PUB_PORT}/`);

  ws.on("open", () => {
    send(ws, { r: "refresh" });
    ws.timeout = setTimeout(() => ws.close(), 15000);
  });

  ws.on("message", data => {
    try {
      let request = JSON.parse(data);

      if (_.isArray(request)) {
        const [market, type, coin, data = []] = request;
        const key = JSON.stringify([market, type, coin]);
        if (server) {
          broatcast(server, key, { market, type, coin, data });
        }
        datas.set(key, data);
        return;
      }

      switch (request.r) {
        case "refresh": {
          markets = request.m;
          types = request.t;
          coins = request.c;

          datas.clear();
          for (let [market, type, coin, data] of request.s) {
            const key = JSON.stringify([market, type, coin]);
            if (server) {
              broatcast(server, key, { market, type, coin, data });
            }
            datas.set(JSON.stringify([market, type, coin]), data);
          }

          if (!server) {
            server = listen();
          }
          break;
        }

        default:
          break;
      }

      request = null;
    } catch (e) {
      console.error(e);
    }
  });

  ws.on("ping", () => {
    clearTimeout(ws.timeout);
    ws.timeout = setTimeout(() => ws.close(), 15000);
  });

  ws.on("error", error => {
    clearTimeout(ws.timeout);
    if (!isKnownError(error)) {
      console.error(error);
    }
  });

  ws.on("close", () => {
    clearTimeout(ws.timeout);
    setTimeout(() => subscriber(), 1000);
    ws = null;
  });
}

function broatcast(wss, key, message) {
  let data = JSON.stringify(message);
  for (let ws of wss.clients) {
    if (ws.requests.has(key)) {
      if (ws.readyState === WebSocket.OPEN) {
        ws.send(data);
      }
    }
  }

  data = null;
}

function send(ws, message) {
  if (ws.readyState === WebSocket.OPEN) {
    ws.send(JSON.stringify(message));
  }
}

function isKnownError(err) {
  return (
    err.code === "EHOSTUNREACH" ||
    err.code === "ECONNREFUSED" ||
    err.code === "ECONNRESET"
  );
}

process.on("SIGINT", () => {
  try {
    if (server) {
      server.close();
    }
    console.log(`Sub Server on ${PORT} as ${MODE} shutting down gracefully`);
  } catch (e) {
    console.error(e);
  } finally {
    process.exit(0);
  }
});

console.log(`Sub Server on ${PORT} as ${MODE} starting`);
subscriber();
