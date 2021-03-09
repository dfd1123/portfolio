window.getMobileOperatingSystem = function () {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera

  // Windows Phone must come first because its UA also contains "Android"
  if (/windows phone/i.test(userAgent)) {
    return 'Windows Phone'
  }

  if (/android/i.test(userAgent)) {
    return 'Android'
  }

  // iOS detection from: http://stackoverflow.com/a/9039885/177710
  if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
    return 'iOS'
  }
  return 'unknown'
}

window.callToken = function () {
  var loginOs = window.getMobileOperatingSystem()
  if (loginOs === 'Android') {
    if (typeof window.myJs !== 'undefined') {
      window.myJs.requestToken()
    }
  } else if (loginOs === 'iOS') {
    if (typeof webkit !== 'undefined') {
      window.webkit.messageHandlers.requestToken.postMessage('')
    }
  } else {
    // alert('기기확인불가')
  }
}

window.setToken = function (token) {
  window.$EventBus.$emit('native-login', token)
}
