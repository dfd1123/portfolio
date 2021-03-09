const WebSocket = require('ws');
const pool = require('./db');
const Actions = require('./actions');
const ChangeScanner = require('./change-scanner');
const ActionExecutor = require('./action-executor');

require('dotenv').config();

const MODE = process.env.NODE_ENV; // development or production
const PORT = process.env.PORT || 8080;

const snapshots = new Map();

const wss = new WebSocket.Server({ port: PORT });

const actionExecutor = new ActionExecutor();
actionExecutor.onActionResult((result) => {
  const responseData = JSON.stringify(result.response);
  const snapshotData = JSON.stringify(snapshots.get(result.action));
  if (responseData === snapshotData) {
    return;
  }

  snapshots.set(result.action, result.response);

  wss.clients.forEach((client) => {
    const data = JSON.stringify(
      Object.assign(
        {
          result: 0,
          market: result.action.market,
          type: result.action.type,
          updated: Date.now(),
        },
        result.response,
      ),
    );

    if (client.readyState === WebSocket.OPEN) {
      if (client.actions.has(result.action)) {
        client.send(data);
      }
    }
  });
});
actionExecutor.start();

const changeScanner = new ChangeScanner();
changeScanner.onRequest((changedActions) => {
  changedActions.forEach((action) => {
    actionExecutor.requestAction(action);
  });
});
changeScanner.onChange((name, value) => {
  if (name === 'getListedCoins') {
    Actions.updateActions(value);
  }
});
changeScanner.start();

wss.on('connection', (ws) => {
  ws.isAlive = true;
  ws.actions = new Set();

  ws.on('pong', () => {
    ws.isAlive = true;
  });

  ws.on('message', (data) => {
    if (data === 'null') {
      return;
    }

    try {
      const request = JSON.parse(data);
      if (typeof request !== 'object') {
        ws.send(
          JSON.stringify({
            result: 'error',
            message: 'Invalid request',
          }),
        );
        return;
      }

      const requestTypes = [...new Set(request.map(item => item.type))];
      ws.actions.forEach((action) => {
        if (requestTypes.includes(action.type)) {
          ws.actions.delete(action);
        }
      });

      request.forEach((item) => {
        switch (item.type) {
          case 'orderbook':
          case 'trade':
            item.coins.forEach((coin) => {
              const action = Actions.get(item.market, item.type, coin);
              if (action) {
                ws.actions.add(action);
                if (snapshots.has(action)) {
                  ws.send(
                    JSON.stringify(
                      Object.assign(
                        {
                          result: 0,
                          type: action.type,
                          updated: Date.now(),
                        },
                        snapshots.get(action),
                      ),
                    ),
                  );
                }
              } else {
                ws.send(
                  JSON.stringify({
                    result: 'error',
                    message: 'Invalid request',
                  }),
                );
              }
            });
            break;

          case 'ticker': {
            let actions = [];

            if ('coins' in item && 'market' in item) {
              actions = item.coins
                .map(coin => Actions.get(item.market, item.type, coin))
                .filter(action => action);
            } else if ('coins' in item) {
              actions = Actions.getAll(item.type).filter(action => item.coins.includes(action.coin));
            } else if ('market' in item) {
              actions = Actions.getAll(item.type).filter(
                action => action.market === item.market,
              );
            } else {
              actions = Actions.getAll(item.type);
            }

            actions.forEach((action) => {
              ws.actions.add(action);
              if (snapshots.has(action)) {
                ws.send(
                  JSON.stringify(
                    Object.assign(
                      {
                        result: 0,
                        type: action.type,
                        market: action.market,
                        updated: Date.now(),
                      },
                      snapshots.get(action),
                    ),
                  ),
                );
              } else {
                ws.send(
                  JSON.stringify({
                    result: 'error',
                    message: 'Invalid request',
                  }),
                );
              }
            });
            break;
          }

          default:
            ws.send(
              JSON.stringify({ result: 'error', message: 'Invalid request' }),
            );
            break;
        }
      });
    } catch (err) {
      console.log(err);
      ws.send(JSON.stringify({ result: 'error', message: 'Internal error' }));
    }
  });
});

(function ping() {
  wss.clients.forEach((client) => {
    if (client.isAlive === false) {
      client.terminate();
      return;
    }
    client.isAlive = false;
    client.send('[]');
    client.ping();
  });
  setTimeout(ping, 10000);
}());

process.on('SIGINT', () => {
  console.log('Received SIGINT, shutting down gracefully');
  wss.close();
  pool.end();
  changeScanner.stop();
  actionExecutor.stop();
  process.exit(0);
});

console.log(`Server is running... on ${PORT} as ${MODE}`);
