const WebSocket = require("ws");
const _ = require("lodash");
const pool = require("./db");
const call = require("./call");

require("dotenv").config();

const MODE = process.env.NODE_ENV; // development or production
const HOST = process.env.HOST || "localhost";
const PORT = process.env.PORT || 8090;
const UPDATE_INTERVAL = Number(process.env.UPDATE_INTERVAL) || 500;

const wss = new WebSocket.Server({ host: HOST, port: PORT });

const datas = new Map();
const scans = new Map();

let markets = ["krw"];
let types = ["orderbook", "ticker", "trade"];
let coins = [];

/**
 * 거래소 코인 목록 스캔
 */
async function scanner() {
  try {
    let newCoins = await call.coin();
    if (!_.isEqual(coins, newCoins)) {
      coins = newCoins;

      let tempMap = new Map(datas);
      datas.clear();
      for (let market of markets) {
        for (let type of types) {
          for (let coin of coins) {
            let value = [market, type, coin];
            let key = JSON.stringify(value);
            let data = tempMap.get(key);

            datas.set(key, data || []);
            if (!data && !scans.has(key)) {
              scans.set(key, value);
              (x => setTimeout(() => updater(x), UPDATE_INTERVAL))(key);

              value = null;
              key = null;
              data = null;
            }
          }
        }
      }

      // Sub Server 업데이트
      broatcast(refreshMessage());

      newCoins = null;
      tempMap = null;
    }
  } catch (e) {
    if (!isKnownError(e)) {
      console.error(e);
    }
  } finally {
    setTimeout(() => scanner(), 1000);
  }
}

/**
 * 거래소 코인 정보 스캔
 */
async function updater(key) {
  if (!datas.has(key)) {
    scans.delete(key);
    return;
  }

  try {
    let [market, type, coin] = scans.get(key);
    let data = await call[type]({ market, coin });
    if (!_.isEqual(data, datas.get(key))) {
      broatcast([market, type, coin, data]);
      datas.set(key, data);
    }

    market = null;
    type = null;
    coin = null;
    data = null;
  } catch (e) {
    if (!isKnownError(e)) {
      console.error(e);
    }
  } finally {
    (x => setTimeout(() => updater(x), UPDATE_INTERVAL))(key);
  }
}

/**
 * Sub Server와 통신 연결
 */
wss.on("connection", ws => {
  ws.isAlive = true;

  ws.on("message", data => {
    try {
      let request = JSON.parse(data);
      if (!_.isObject(request)) {
        send(ws, { r: "error", m: "Invalid request" });
        request = null;
        return;
      }

      switch (request.r) {
        case "refresh": {
          send(ws, refreshMessage());
          break;
        }

        default:
          send(ws, { r: "error", m: "Invalid request" });
          break;
      }

      request = null;
    } catch (e) {
      console.error(e);
      send(ws, { r: "error", m: "Internal error" });
    }
  });

  ws.on("pong", () => {
    ws.isAlive = true;
  });
});

wss.on("listening", () => {
  setTimeout(() => ping(), 10000);
});

function ping() {
  try {
    for (let ws of wss.clients) {
      if (ws.isAlive === false) {
        ws.terminate();
        return;
      }

      ws.isAlive = false;
      ws.ping();
    }
  } catch (e) {
    if (!isKnownError(e)) {
      console.error(e);
    }
  } finally {
    setTimeout(() => ping(), 10000);
  }
}

function refreshMessage() {
  const temp = [];
  for (let [key, value] of datas) {
    temp.push([...scans.get(key), value]);
  }

  return { r: "refresh", m: markets, t: types, c: coins, s: temp };
}

function broatcast(message) {
  let data = JSON.stringify(message);
  for (let ws of wss.clients) {
    if (ws.readyState === WebSocket.OPEN) {
      ws.send(data);
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
    wss.close();
    pool.end();
    console.log(`Pub Server on ${PORT} as ${MODE} shutting down gracefully`);
  } catch (e) {
    if (!isKnownError(e)) {
      console.error(e);
    }
  } finally {
    process.exit(0);
  }
});

console.log(`Pub Server on ${PORT} as ${MODE} starting`);
scanner();
