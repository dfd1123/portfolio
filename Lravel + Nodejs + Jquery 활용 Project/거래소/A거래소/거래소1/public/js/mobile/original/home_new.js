/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/mobile/custom/home_new.js":
/*!************************************************!*\
  !*** ./resources/js/mobile/custom/home_new.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var ws;
$(document).ready(function () {
  if (window.location.href.indexOf("https://cointouse.com") == 0 || window.location.href.indexOf("https://prodweb.cointouse.com") == 0) {
    connect_ws("wss://wss.cointouse.com/", '[{"type":"ticker"}]');
  } else {
    connect_ws("wss://ws.cointouse.com/", '[{"type":"ticker"}]');
  }
});

function connect_ws(url, request) {
  ws = new WebSocket(url);
  ws.snapshot = false;
  ws.timeout = null;

  ws.onopen = function () {
    timeout_ws(ws);
    ws.send(request);
  };

  ws.onmessage = function (message) {
    if (message.data !== "[]") {
      ws.snapshot = true;
    }

    if (ws.snapshot === false) {
      ws.close();
    }

    timeout_ws(ws);

    try {
      if (data === "[]") {
        return;
      }

      var data = JSON.parse(message.data);

      if (data.result === "error") {
        ws.close();
      }

      if (data.type === "ticker") {
        refresh_homepage_ticker(data);
      }
    } catch (e) {}
  };

  ws.onclose = function (message) {
    clearTimeout(ws.timeout);
    setTimeout(function () {
      connect_ws(url, request);
    }, 5000);
  };
}

function timeout_ws(ws) {
  clearTimeout(ws.timeout);
  ws.timeout = setTimeout(function () {
    ws.timeout = null;
    ws.close();
  }, 15000);
}

function refresh_homepage_ticker(data) {
  data.coin_data.forEach(function (row) {
    var curRow = $("#coin_table_" + data.market + " #row_" + row.api);
    update_value_change_percent(curRow.find(".percent_change_24h"), row.percent_change_24h);
    update_value(curRow.find(".last_trade_price_usd"), row.last_trade_price_usd);
    update_value(curRow.find(".h24h_volume"), row.h24h_volume);
  });
}

function update_value(item, value) {
  var prev = item.text().trim();

  if (prev === value) {
    return;
  }

  item.text(value);
  request_animation(item[0], "blink");
}

function update_value_change_percent(item, value) {
  var prev = item.text().trim();
  var next = value == 0 ? value + "%" : value > 0 ? value + "% ▲" : value + "% ▼";

  if (prev === next) {
    return;
  }

  var color = value == 0 ? "inherit" : value > 0 ? "#ff0000" : "#2e4dff";
  item.text(next).css("color", color);
  request_animation(item[0], "blink");
  var box = item.closest(".cell");
  box.css("border-color", color);
  request_animation(box[0], "cell-blink");
}

function request_animation(item, animationName) {
  var el = item;
  el.classList.add(animationName);
  el.addEventListener("animationend", function () {
    el.classList.remove(animationName);
    el.style.borderColor = "transparent";
  });
}

/***/ }),

/***/ 1:
/*!******************************************************!*\
  !*** multi ./resources/js/mobile/custom/home_new.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! E:\spowide\resources\js\mobile\custom\home_new.js */"./resources/js/mobile/custom/home_new.js");


/***/ })

/******/ });