$(function() {
    var ticker = $("#top_ticker");
    var market = ticker.data("market");
    var coin = ticker.data("coin");

    var request = [
        {
            market: market,
            type: "orderbook",
            coins: [coin]
        },
        {
            market: market,
            type: "ticker",
            coins: [coin]
        },
        {
            market: market,
            type: "trade",
            coins: [coin]
        }
    ];

    if (window.location.href.indexOf("https://spowide.co.kr") === 0) {
        connect_ws("wss://wss.spowide.co.kr", request);
    } else {
        connect_ws("wss://devwss.spowide.co.kr", request);
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

        if (data.type === "orderbook") {
            refresh_market_orderbook(data);
            refresh_market_orderbook2(data);
            return;
        }
        if (data.type === "ticker") {
            refresh_market_ticker(data);
            return;
        }
        if (data.type === "trade") {
            refresh_market_trade(data);
            add_my_readyorder_data();
            add_my_history_data();
            refresh_my_asset();
            return;
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

function refresh_market_orderbook(data) {
    var sellWait = $("#sell_wait");
    var buyWait = $("#buy_wait");

    if (data.data.length === 0) {
        return;
    }

    var totalSellAmount = data.data[0].reduce((acc, cur) => {
        return acc + Number(cur.amt.replace(/\,/g, ""));
    }, 0);
    var prevSellOrders = sellWait.children();
    var nextSellOrders = data.data[0]
        .map(function(nextOrder) {
            var prevOrder = prevSellOrders.has(
                ".orderbook_price:contains(" + nextOrder.price + ")"
            );
            var change = "";

            if (prevOrder.length > 0) {
                if (
                    prevOrder.find(".orderbook_amt").text() !==
                    formatNumber(nextOrder.amt.replace(/\,/g, ""), 4)
                ) {
                    change = "change";
                }
            } else {
                change = "change";
            }

            return (
                '<li class="' +
                change +
                '" data-price="' +
                nextOrder.price.replace(/\,/g, "") +
                '" data-amt="' +
                nextOrder.amt.replace(/\,/g, "") +
                '">' +
                '<span class="orderbook_price price_span">' +
                nextOrder.price +
                "</span>" +
                '<span class="orderbook_amt amt_span">' +
                formatNumber(nextOrder.amt.replace(/\,/g, ""), 4) +
                "</span>" +
                '<div class="per_bar" style="width: ' +
                (Number(nextOrder.amt.replace(/\,/g, "")) / totalSellAmount) *
                    100 +
                '%;"></div>' +
                "</li>"
            );
        })
        .reverse()
        .join("");

    var totalBuyAmount = data.data[1].reduce((acc, cur) => {
        return acc + Number(cur.amt.replace(/\,/g, ""));
    }, 0);
    var prevBuyOrders = buyWait.children();
    var nextBuyOrders = data.data[1]
        .map(function(nextOrder) {
            var prevOrder = prevBuyOrders.has(
                ".orderbook_price:contains(" + nextOrder.price + ")"
            );
            var change = "";

            if (prevOrder.length > 0) {
                if (
                    prevOrder.find(".orderbook_amt").text() !==
                    formatNumber(nextOrder.amt.replace(/\,/g, ""), 4)
                ) {
                    change = "change";
                }
            } else {
                change = "change";
            }

            return (
                '<li class="' +
                change +
                '"  data-price="' +
                nextOrder.price.replace(/\,/g, "") +
                '" data-amt="' +
                nextOrder.amt.replace(/\,/g, "") +
                '">' +
                '<span class="orderbook_price price_span">' +
                nextOrder.price +
                "</span>" +
                '<span class="orderbook_amt amt_span">' +
                formatNumber(nextOrder.amt.replace(/\,/g, ""), 4) +
                "</span>" +
                '<div class="per_bar" style="width: ' +
                (Number(nextOrder.amt.replace(/\,/g, "")) / totalBuyAmount) *
                    100 +
                '%;"></div>' +
                "</li>"
            );
        })
        .join("");

    sellWait
        .html(nextSellOrders)
        .find(".change")
        .each(function(idx, val) {
            request_row_blink_animation(val, "row-blink", "#6789ec");
        });
    buyWait
        .html(nextBuyOrders)
        .find(".change")
        .each(function(idx, val) {
            request_row_blink_animation(val, "row-blink", "#e86565");
        });
}

function refresh_market_orderbook2(data) {
    var sellWait = $("#sell_wait2");
    var buyWait = $("#buy_wait2");

    var totalSellAmount = data.data[0].reduce((acc, cur) => {
        return acc + Number(cur.amt.replace(/\,/g, ""));
    }, 0);
    var prevSellOrders = sellWait.children();
    var nextSellOrders = data.data[0]
        .map(function(nextOrder) {
            var prevOrder = prevSellOrders.has(
                ".orderbook_price:contains(" + nextOrder.price + ")"
            );
            var change = "";

            if (prevOrder.length > 0) {
                if (prevOrder.find(".orderbook_amt").text() !== nextOrder.amt) {
                    change = "change";
                }
            } else {
                change = "change";
            }

            return (
                '<li class="' +
                change +
                '" data-price="' +
                nextOrder.price.replace(/\,/g, "") +
                '" data-amt="' +
                nextOrder.amt.replace(/\,/g, "") +
                '">' +
                '<span class="orderbook_price">' +
                nextOrder.price +
                "</span>" +
                '<span class="orderbook_amt">' +
                nextOrder.amt +
                "</span>" +
                '<span class="orderbook_total">' +
                nextOrder.total +
                "</span>" +
                '<div class="per_bar" style="width: ' +
                (Number(nextOrder.amt.replace(/\,/g, "")) / totalSellAmount) *
                    100 +
                '%;"></div>' +
                "</li>"
            );
        })
        .join("");

    var totalBuyAmount = data.data[1].reduce((acc, cur) => {
        return acc + Number(cur.amt.replace(/\,/g, ""));
    }, 0);
    var prevBuyOrders = buyWait.children();
    var nextBuyOrders = data.data[1]
        .map(function(nextOrder) {
            var prevOrder = prevBuyOrders.has(
                ".orderbook_price:contains(" + nextOrder.price + ")"
            );
            var change = "";

            if (prevOrder.length > 0) {
                if (prevOrder.find(".orderbook_amt").text() !== nextOrder.amt) {
                    change = "change";
                }
            } else {
                change = "change";
            }

            return (
                '<li class="' +
                change +
                '"  data-price="' +
                nextOrder.price.replace(/\,/g, "") +
                '" data-amt="' +
                nextOrder.amt.replace(/\,/g, "") +
                '">' +
                '<span class="orderbook_price">' +
                nextOrder.price +
                "</span>" +
                '<span class="orderbook_amt">' +
                nextOrder.amt +
                "</span>" +
                '<span class="orderbook_total">' +
                nextOrder.total +
                "</span>" +
                '<div class="per_bar" style="width: ' +
                (Number(nextOrder.amt.replace(/\,/g, "")) / totalBuyAmount) *
                    100 +
                '%;"></div>' +
                "</li>"
            );
        })
        .join("");

    sellWait
        .html(nextSellOrders)
        .find(".change")
        .each(function(idx, val) {
            request_row_blink_animation(val, "row-blink", "#6789ec");
        });
    buyWait
        .html(nextBuyOrders)
        .find(".change")
        .each(function(idx, val) {
            request_row_blink_animation(val, "row-blink", "#e86565");
        });
}

function refresh_market_ticker(data) {
    var ticker = $("#top_ticker");
    var coin = ticker.data("coin");

    var item = data.data[0];

    if (item.api === coin) {
        //Top ticker 색상 판단 기준값
        var numPriceChange24 = Number(item.price_change_24h.replace(/\,/g, ""));

        var color =
            numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "red" : "blue";

        var lastTradePrice = ticker.find(".last_trade_price");
        lastTradePrice.text(item.last_trade_price);
        lastTradePrice
            .parent()
            .removeClass("red blue")
            .addClass(color);

        var priceChange24h = ticker.find(".price_change_24h");
        var nextPriceChange24h =
            (numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : "") +
            item.price_change_24h;
        priceChange24h.text(nextPriceChange24h);
        priceChange24h
            .parent()
            .removeClass("red blue")
            .addClass(color);

        var percentChange24h = ticker.find(".percent_change_24h");
        var nextPercentChange24h = `(${
            numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : ""
        }${item.percent_change_24h}%) ${
            numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "▲" : "▼"
        }`;
        percentChange24h.text(nextPercentChange24h);
        percentChange24h
            .parent()
            .removeClass("red blue")
            .addClass(color);

        ticker.find(".high_price_24h .number_price").text(item.max_price);
        ticker.find(".row_price_24h .number_price").text(item.min_price);
        ticker.find(".trade_volume_24h .number_price").text(item.h24h_volume);

        $("#last_traded_price").text(item.last_trade_price);
    }
}

function refresh_market_trade(data) {
    var tradeList = $("#trade_list");

    var maxLatestCreated = 0;
    var prevLatestCreated = tradeList.data("latest")
        ? Number(tradeList.data("latest"))
        : -1;

    var trades = data.data
        .map(function(trade) {
            maxLatestCreated = Math.max(maxLatestCreated, trade.created);

            return (
                '<tr class="' +
                (trade.created > prevLatestCreated ? "change" : "") +
                " " +
                (trade.last_trade_kind || "") +
                '">' +
                "<td><span>" +
                trade.amt +
                "</span></td>" +
                "<td><span>" +
                trade.price +
                "</span></td>" +
                "<td><span>" +
                dateSet(moment.unix(trade.created)) +
                "</span></td>" +
                "</tr>"
            );
        })
        .join("");

    if (data.data.length > 1) {
        var middle = $("#orderbook_middle, #orderbook_middle2");
        var first = data.data[0];
        var second = data.data[1];
        var numFirst = Number(first.price.replace(/\,/g, ""));
        var numSecond = Number(second.price.replace(/\,/g, ""));

        if (numFirst > numSecond) {
            middle.removeClass("blue red");
            middle.addClass("red");
        } else if (numFirst < numSecond) {
            middle.removeClass("blue red");
            middle.addClass("blue");
        }

        var arrow = "";
        if (first.last_trade_kind === "buy") {
            arrow = "↑";
        } else if (first.last_trade_kind === "sell") {
            arrow = "↓";
        }

        middle.text(arrow + first.price);
    }

    tradeList
        .html(trades)
        .data("latest", maxLatestCreated)
        .find(".change")
        .each(function(idx, val) {
            request_animation(val, "blink");
        });
}

function request_animation(el, animationName) {
    if (el.offsetParent === null) {
        return;
    }

    el.classList.add(animationName);
    el.addEventListener("animationend", function() {
        el.classList.remove(animationName);
    });
}

function request_row_blink_animation(el, animationName, color) {
    if (el.offsetParent === null) {
        return;
    }

    el.style.backgroundColor = color;
    el.classList.add(animationName);
    el.addEventListener("animationend", function() {
        el.classList.remove(animationName);
        el.style.backgroundColor = "";
    });
}
