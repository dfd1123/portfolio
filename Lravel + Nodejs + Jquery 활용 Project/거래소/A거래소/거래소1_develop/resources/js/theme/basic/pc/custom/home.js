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
            refresh_ticker_slide(data);
            refresh_chart_coin_info(data);
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

function refresh_ticker_slide(data) {
    var item = data.data[0];
    var slide = $(`#coin_slider .slide[data-coin=${item.api}]`);
    if (slide.length === 0) {
        return;
    }

    var txt = slide.find(".markets_txt");
    var priceElems = txt.children().detach();
    txt.text(item.last_trade_price).append(priceElems.first());

    var ratio = slide.find(".markets_ratio");
    var numPriceChange24 = Number(item.price_change_24h.replace(/\,/g, ""));

    var indicate =
        numPriceChange24 === 0
            ? ""
            : numPriceChange24 > 0
            ? "ratio_up"
            : "ratio_down";
    ratio.removeClass("ratio_up ratio_down").addClass(indicate);

    var ratioNumber =
        (numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : "") +
        item.price_change_24h;
    var ratioPercent =
        (numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : "") +
        item.percent_change_24h;
    var ratioUpDown =
        numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "▲" : "▼";
    ratio.text(`${ratioNumber} (${ratioPercent}%) ${ratioUpDown}`);
}

function refresh_chart_coin_info(data) {
    var item = data.data[0];

    var price = $(
        `#chart_public_price[data-coin=${item.api}], #chart_sports_price[data-coin=${item.api}]`
    );
    if (price.length === 0) {
        return;
    }
    price.text(item.last_trade_price);

    var change = $(
        `#chart_public_change[data-coin=${item.api}], #chart_sports_change[data-coin=${item.api}]`
    ).find("b");

    var numPriceChange24 = Number(item.price_change_24h.replace(/\,/g, ""));
    var indicate =
        numPriceChange24 === 0
            ? ""
            : numPriceChange24 > 0
            ? "percent_up_color"
            : "percent_down_color";
    change.removeClass("percent_up_color percent_down_color").addClass(indicate);

    var ratioNumber =
        (numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : "") +
        item.price_change_24h;
    var ratioPercent =
        (numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : "") +
        item.percent_change_24h;
    var ratioUpDown =
        numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "▲" : "▼";
    change.text(`${ratioNumber} (${ratioPercent}%) ${ratioUpDown}`);
}

/*
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
*/
