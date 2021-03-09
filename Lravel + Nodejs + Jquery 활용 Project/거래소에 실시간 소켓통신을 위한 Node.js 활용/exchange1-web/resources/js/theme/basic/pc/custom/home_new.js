var ws;

$(document).ready(function() {
    //connect_ws('ws://localhost', '[{"type":"ticker"}]');
    //connect_ws("wss://exchange1-ws.pocketcompany.co.kr", '[{"type":"ticker"}]');
    if(window.location.href.indexOf("https://sharebits.world") == 0) {
        connect_ws("wss://wss.sharebits.world", '[{"type":"ticker"}]');
    } else {
        connect_ws("wss://exchange1-ws.pocketcompany.co.kr", '[{"type":"ticker"}]');
    }
});

function connect_ws(url, request) {
    ws = new WebSocket(url);
    ws.snapshot = false;
    ws.timeout = null;

    ws.onopen = function() {
        timeout_ws(ws);
        ws.send(request);
    };

    ws.onmessage = function(message) {
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
    data.coin_data.forEach(function(row) {
        var curRow = $("#coin_table_" + data.market + " #row_" + row.api);
        update_value_change_percent(
            curRow.find(".percent_change_24h"),
            row.percent_change_24h
        );
        update_value(curRow.find(".h24h_volume"), row.h24h_volume);
        update_value(curRow.find(".max_price"), row.max_price);
        update_value(curRow.find(".min_price"), row.min_price);
        update_value(
            curRow.find(".last_trade_price_usd"),
            row.last_trade_price_usd
        );
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
    var next =
        value == 0 ? value + "%" : value > 0 ? value + "% ▲" : value + "% ▼";
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
    el.addEventListener("animationend", function() {
        el.classList.remove(animationName);
        el.style.borderColor = "transparent";
    });
}
