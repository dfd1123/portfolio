export function setCookie(name, value, days) {
    let expires = '';
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = '; expires=' + date.toGMTString();
    } else {
        expires = '';
    }

    document.cookie = name + '=' + value + expires + '; path=/';
}

export function getCookie(name) {
    var i,
        x,
        y,
        ARRcookies = document.cookie.split(';');

    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf('='));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf('=') + 1);

        x = x.replace(/^\s+|\s+$/g, '');

        if (x === name) {
            return unescape(y);
        }
    }
}
