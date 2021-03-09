//phone os 검사
function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }
    return "unknown";
}

// token 부르는함수
function callPushToken2() {
    var mobile_kind = getMobileOperatingSystem();
    if (mobile_kind == "Android") {
        window.myJs.callPushToken();
    } else if (mobile_kind == "iOS") {
        webkit.messageHandlers.requestToken.postMessage("");
    }
}

function callAndroid(){
	var mobile_kind = getMobileOperatingSystem();
	if(mobile_kind == 'Android'){
		window.myJs.callAndroid();
	}else if(mobile_kind == 'iOS'){
		webkit.messageHandlers.IosQrcodeAddress.postMessage("");
	}
	
}
function callAllpay(){
	var mobile_kind = getMobileOperatingSystem();
	if(mobile_kind == 'Android'){
		window.myJs.callAllpay();
	}else if(mobile_kind == 'iOS'){
		webkit.messageHandlers.IosQrcodeAllpay.postMessage("");
	}
}

// Android 에서 토큰 받을 함수(window.myJs.callPushToken())
function callPushToken_script(token) {
    Vue.prototype.$EventBus.$emit("push-token-result", { token: token });
}

// QR코드 스캔한 값을 받을 함수(window.myJs.callAndroid())
function callJavascript2(address) {
    Vue.prototype.$EventBus.$emit("qr-scan-result", { address: address });
}

// pay용 텍스트 정보 받을 함수(window.myJs.callAllpay())
function ExecutePay(data) {
    Vue.prototype.$EventBus.$emit("pay-scan-result", { data: data });
}