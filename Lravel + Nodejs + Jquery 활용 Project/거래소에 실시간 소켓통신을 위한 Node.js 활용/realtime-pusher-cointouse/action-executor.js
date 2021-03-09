// const actions = require('./actions');
const call = require('./call');

function isKnownError(err) {
  return err.code === 'EHOSTUNREACH' || err.code === 'ECONNRESET';
}

class ActionExecutor {
  constructor(initialActions = []) {
    this.queuedActions = new Set(initialActions);
    this.activeActions = new Set();
    this.actionResultCallback = () => {};
    this.timeref = null;
  }

  requestAction(action) {
    this.queuedActions.add(action);
    if (this.timeref === null) {
      this.timeref = setTimeout(() => {
        this.requestLoop();
      }, 0);
    }
  }

  requestLoop() {
    this.queuedActions.forEach((action) => {
      if (!this.activeActions.has(action)) {
        this.queuedActions.delete(action);
        this.activeActions.add(action);

        const currentAction = call[action.type];
        if (!currentAction) {
          this.activeActions.delete(action);
          return;
        }

        currentAction(action)
          .then((response) => {
            this.actionResultCallback({ action, response });
          })
          .catch((err) => {
            if (!isKnownError(err)) {
              console.error(err);
            }
          })
          .then(() => {
            this.activeActions.delete(action);
          });
      }
    });

    if (this.queuedActions.size > 0) {
      this.timeref = setTimeout(() => {
        this.requestLoop();
      }, 0);
    } else {
      this.timeref = null;
    }
  }

  onActionResult(callback) {
    this.actionResultCallback = callback;
  }

  start() {
    if (this.timeref === null) {
      this.timeref = setTimeout(() => {
        this.requestLoop();
      }, 0);
    }
  }

  stop() {
    if (this.timeref !== null) {
      clearTimeout(this.timeref);
    }
  }
}

module.exports = ActionExecutor;
