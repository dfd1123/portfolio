// 쿠키 생성
function setCookie(name, value, expiredays) {
	name = name.trim();
	var cookie = name + "=" + escape(value) + "; path=/;"
	if (typeof expiredays != 'undefined') {
		var todayDate = new Date();
		todayDate.setDate(todayDate.getDate() + 1);
		//todayDate.setMinutes(37);
		cookie += "expires=" + todayDate.toGMTString() + ";"
	}
	document.cookie = cookie;
}

function getCookie(cookie_name) {
	//console.log(document.cookie);
	var x,
		y;
	var val = document.cookie.split(';');

	for (var i = 0; i < val.length; i++) {
		x = val[i].substr(0, val[i].indexOf('='));
		y = val[i].substr(val[i].indexOf('=') + 1);
		x = x.replace(/^\s+|\s+$/g, '');
		// 앞과 뒤의 공백 제거하기
		if (x == cookie_name) {
			return unescape(y);
			// unescape로 디코딩 후 값 리턴
		}
	}
}

// 쿠키 삭제
function deleteCookie(name, path) {
	if (getCookie(name)) {
		document.cookie = name + "=" +
			((path) ? ";path=" + path : "") +
			";expires=Thu, 01 Jan 1970 00:00:01 GMT";
	}
}