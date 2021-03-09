// Webview @JavascriptInterface
// window.$EventBus = new Vue();

function getMobileOperatingSystem () {
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

window.CallPushToken = function () {
  try {
    const OS = getMobileOperatingSystem()
    if (OS === 'Android' && typeof window.myJS !== 'undefined') {
      window.myJS.CallPushToken()
    } else if (OS === 'iOS' && typeof webkit !== 'undefined') {
      window.webkit.messageHandlers.CallPushToken.postMessage('push_token')
    } else {
      // Other
    }
  } catch (e) {
    console.log(e)
  }
}

window.CallBackToken = function (token) {
  try {
    window.$EventBus.$emit('push-token-result', { token: token })
  } catch (e) {
    console.log(e)
  }
}

window.SetAlarmNotify = function (userid, id, title, body, hour, min) {
  const OS = getMobileOperatingSystem()
  if (OS === 'Android' && typeof window.myJS !== 'undefined') {
    window.myJS.SetAlarmNotify(id, title, body, hour, min)
  } else if (OS === 'iOS' && typeof window.webkit !== 'undefined') {
    const message = {
      userid: String(userid),
      id,
      isOn: true,
      title,
      body,
      hour: Number(hour),
      min: Number(min)
    }
    window.webkit.messageHandlers.addAlarm.postMessage(message)
  } else {
    // Other
  }
}

window.ResetAlarmNotify = function (userid, id) {
  const OS = getMobileOperatingSystem()
  if (OS === 'Android' && typeof window.myJS !== 'undefined') {
    window.myJS.ResetAlarmNotify(id)
  } else if (OS === 'iOS' && typeof window.webkit !== 'undefined') {
    const message = {
      userid: String(userid),
      id
    }
    window.webkit.messageHandlers.removeAlarm.postMessage(message)
  } else {
    // Other
  }
}
