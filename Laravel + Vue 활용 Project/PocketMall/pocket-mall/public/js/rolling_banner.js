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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/plugin/rolling_banner.js":
/*!***********************************************!*\
  !*** ./resources/js/plugin/rolling_banner.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

//client rolling banner
window.onload = function () {
  var bannerLeft = 0;
  var first = 1;
  var last;
  var imgCnt = 0;
  var $img = $(".banner_wraper img");
  var $first;
  var $last;
  var moveWidth;
  var bannerMargin;

  if (navigator.userAgent.match(/iPhone|iPad|iPod|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i) != null || navigator.userAgent.match(/LG|SAMSUNG|Samsung/) != null) {
    moveWidth = -90;
    bannerMargin = 8;
  } else {
    moveWidth = 100;
    bannerMargin = 18;
  }

  $img.each(function () {
    // 5px 간격으로 배너 처음 위치 시킴
    $(this).css("left", bannerLeft);
    bannerLeft += $(this).width() + bannerMargin;
    $(this).attr("id", "banner" + ++imgCnt); // img에 id 속성 추가
  });

  if (imgCnt > 9) {
    //배너 9개 이상이면 이동시킴
    last = imgCnt;
    setInterval(function () {
      $img.each(function () {
        $(this).css("left", $(this).position().left - 1); // 1px씩 왼쪽으로 이동
      });
      $first = $("#banner" + first);
      $last = $("#banner" + last);

      if ($first.position().left < moveWidth) {
        // 제일 앞에 배너 제일 뒤로 옮김
        $first.css("left", $last.position().left + $last.width() + bannerMargin);
        first++;
        last++;

        if (last > imgCnt) {
          last = 1;
        }

        if (first > imgCnt) {
          first = 1;
        }
      }
    }, 50); //여기 값을 조정하면 속도를 조정할 수 있다.(위에 1px 이동하는 부분도 조정하면 
    //깔끔하게 변경가능하다           
  }
};

/***/ }),

/***/ 2:
/*!*****************************************************!*\
  !*** multi ./resources/js/plugin/rolling_banner.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\my\project\repository\pocket-mall\resources\js\plugin\rolling_banner.js */"./resources/js/plugin/rolling_banner.js");


/***/ })

/******/ });