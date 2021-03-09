$(function() {
    if (window.location.href.indexOf("https://spowide.co.kr") === 0) {
        connect_ws("wss://wss.spowide.co.kr", [{ type: "ticker" }]);
    } else {
        connect_ws("wss://devwss.spowide.co.kr", [{ type: "ticker" }]);
    }
});

function connect_ws(url, request) {
    var ws = new WebSocket(url);
    ws.timeout = null;

    ws.onopen = function() {
        timeout_ws(ws);
        ws.send(JSON.stringify(request));
    };

    ws.onmessage = function(message) {
        timeout_ws(ws);

        if (message.data === "[]") {
            ws.send(message.data);
            return;
        }

        var data = JSON.parse(message.data);
        if (data.result === "error") {
            ws.close();
            return;
        }

        if (data.type === "ticker") {
            refresh_homepage_ticker(data);
        }
    };

    ws.onclose = function(message) {
        clearTimeout(ws.timeout);
        setTimeout(function() {
            connect_ws(url, request);
        }, 5000);
    };
}

function timeout_ws(ws) {
    clearTimeout(ws.timeout);
    ws.timeout = setTimeout(function() {
        ws.timeout = null;
        ws.close();
    }, 15000);
}

function refresh_homepage_ticker(data) {
    if (data.data.length === 0) {
        return;
    }
    var item = data.data[0];
    var row = $("[id^='coin_table'] #row_" + item.api);
    if (row.length === 0) {
        return;
    }

    update_value_change_percent(
        row.find(".percent_change_24h"),
        item.percent_change_24h
    );
    update_value(row.find(".last_trade_price_usd"), item.last_trade_price);
    update_value(
        row.find(".h24h_volume"),
        formatNumber(item.h24h_volume.replace(/\,/g, ""), 3)
    );
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
    var numValue = Number(value.replace(/\,/g, ""));
    var next =
        numValue === 0
            ? value + "%"
            : numValue > 0
            ? "+" + value + "% ▲"
            : value + "% ▼";
    if (prev === next) {
        return;
    }

    item.removeClass("red blue");
    item.text(next).addClass(
        numValue === 0 ? "" : numValue > 0 ? "red" : "blue"
    );

    if (item[0].offsetParent === null) {
        return;
    }

    request_animation(item[0], "blink");

    var box = item.closest(".cell");
    box.css(
        "border-color",
        numValue === 0 ? "" : numValue > 0 ? "#ff0000" : "#2e4dff"
    );
    request_animation(box[0], "cell-blink");
}

function request_animation(el, animationName) {
    if (el.offsetParent === null) {
        return;
    }

    el.classList.add(animationName);
    el.addEventListener("animationend", function() {
        el.classList.remove(animationName);
        el.style.borderColor = "";
    });
}

function formatNumber(num, fixed) {
    const parts = new Decimal(num).toFixed(fixed).split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
