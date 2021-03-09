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
            type: "ticker"
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
    var sellWait = $("#sell_wait tbody");
    var buyWait = $("#buy_wait tbody");

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
                '<tr class="' +
                change +
                '" data-price="' +
                nextOrder.price.replace(/\,/g, "") +
                '" data-amt="' +
                nextOrder.amt.replace(/\,/g, "") +
                '">' +
                '<td class="blue"><div class="per_bar" style="width: ' +
                (Number(nextOrder.amt.replace(/\,/g, "")) / totalSellAmount) *
                    100 +
                '%;"></div><span class="orderbook_price">' +
                nextOrder.price +
                "</span></td>" +
                '<td><span class="orderbook_amt">' +
                nextOrder.amt +
                "</span></td>" +
                //'<td><span class="orderbook_total">' + nextOrder.total + '</span></td>' +
                "</tr>"
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
                //if(prevOrder.find('.orderbook_amt').text() !== nextOrder.amt || prevOrder.find('.orderbook_total').text() !== nextOrder.total) {
                if (prevOrder.find(".orderbook_amt").text() !== nextOrder.amt) {
                    change = "change";
                }
            } else {
                change = "change";
            }

            return (
                '<tr class="' +
                change +
                '"  data-price="' +
                nextOrder.price.replace(/\,/g, "") +
                '" data-amt="' +
                nextOrder.amt.replace(/\,/g, "") +
                '">' +
                '<td class="red"><span class="orderbook_price">' +
                nextOrder.price +
                "</span></td>" +
                '<td><div class="per_bar" style="width: ' +
                (Number(nextOrder.amt.replace(/\,/g, "")) / totalBuyAmount) *
                    100 +
                '%;"></div><span class="orderbook_amt">' +
                nextOrder.amt +
                "</span></td>" +
                //'<td><span class="orderbook_total">' + nextOrder.total + '</span></td>' +
                "</tr>"
            );
        })
        .join("");

    sellWait
        .html(nextSellOrders)
        .find(".change")
        .each(function(idx, val) {
            request_row_blink_animation(val, "#6789ec");
        });
    buyWait
        .html(nextBuyOrders)
        .find(".change")
        .each(function(idx, val) {
            request_row_blink_animation(val, "#e86565");
        });
}

function refresh_market_ticker(data) {
    var hmUsd = $('input[type=hidden][name="hm_usd"]');
    var hmDec = $('input[type=hidden][name="hm_dec"]');
    var hmCur = $('input[type=hidden][name="hm_cur"]');

    var ticker = $("#top_ticker");
    var market = ticker.data("market");
    var coin = ticker.data("coin");

    if (data.data.length === 0) {
        return;
    }

    var item = data.data[0];

    // 각 거래소 별 기준코인의 USDC값 업데이트 (USDC 마켓은 항상 1)
    if (data.market === "usd" && (item.api === "btc" || item.api === "eth")) {
        hmUsd.val(item.last_trade_price.replace(/\,/g, ""));
    }

    // 상단 Top ticker 업데이트
    if (data.market === market && item.api === coin) {
        //Top ticker 색상 판단 기준값
        var numPriceChange24 = Number(item.price_change_24h.replace(/\,/g, ""));

        var color =
            numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "red" : "blue";

        var lastTradePrice = ticker.find(".last_trade_price");
        lastTradePrice.text(item.last_trade_price);
        lastTradePrice.removeClass("red blue").addClass(color);

        //현재 마켓에서의 코인 값 * 마켓이 사용중인 기준 코인의 개당 USD값 * USD와 사용자 화폐의 환율값
        var lastTradePriceCurrency = ticker.find(".last_trade_price_currency");
        var numLastTradePriceCurrency = Number(
            item.last_trade_price.replace(/\,/g, "")
        );
        var nextLastTradePriceCurrency = formatNumber(
            Decimal.mul(
                Decimal.mul(numLastTradePriceCurrency, hmUsd.val()),
                hmCur.val()
            ),
            Number(hmDec.val())
        );
        lastTradePriceCurrency.text(nextLastTradePriceCurrency);
        lastTradePriceCurrency.removeClass("red blue").addClass(color);

        var priceChange24h = ticker.find(".price_change_24h");
        var nextPriceChange24h =
            (numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : "") +
            item.price_change_24h;
        priceChange24h.text(nextPriceChange24h);
        priceChange24h.removeClass("red blue").addClass(color);

        var percentChange24h = ticker.find(".percent_change_24h");
        var nextPercentChange24h = `(${
            numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : ""
        }${item.percent_change_24h}%) ${
            numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "▲" : "▼"
        }`;
        percentChange24h.text(nextPercentChange24h);
        percentChange24h.removeClass("red blue").addClass(color);

        ticker.find(".high_price_24h .number_price").text(item.max_price);
        ticker.find(".row_price_24h .number_price").text(item.min_price);
        ticker.find(".trade_volume_24h .number_price").text(item.h24h_volume);
    }

    var tickerLists = $("[id^='coin_list_table'] tbody");
    var tickerRow = tickerLists.find("[data-coin=" + item.api + "]");
    if (tickerRow.length > 0) {
        //Ticker 색상 판단 기준값
        var numPriceChange24 = Number(item.price_change_24h.replace(/\,/g, ""));

        var lastTradePrice = tickerRow.find(".last_trade_price_usd");
        var percentChange = tickerRow.find(".percent_change_24h");

        var numPercentChange = Number(
            item.percent_change_24h.replace(/\,/g, "")
        );
        var color =
            numPriceChange24 === 0 ? "" : numPercentChange > 0 ? "red" : "blue";

        lastTradePrice.removeClass("red blue").addClass(color);
        percentChange.removeClass("red blue").addClass(color);

        var prevTradePrice = lastTradePrice.text();
        var nextTradePrice = item.last_trade_price;
        if (prevTradePrice !== nextTradePrice) {
            var numPrevTradePrice = Number(prevTradePrice.replace(/\,/g, ""));
            var numNextTradePrice = Number(nextTradePrice.replace(/\,/g, ""));

            lastTradePrice.text(nextTradePrice);
            request_cell_blink_animation(
                lastTradePrice,
                numNextTradePrice > numPrevTradePrice ? "#e86565" : "#6789ec"
            );
        }

        var prevPercentChange = percentChange.text();
        var nextPercentChange =
            (numPriceChange24 === 0 ? "" : numPriceChange24 > 0 ? "+" : "") +
            item.percent_change_24h +
            "%";
        if (prevPercentChange !== nextPercentChange) {
            var numPrevPercentChange = Number(
                prevPercentChange.replace(/\,/g, "").replace(/%/g, "")
            );
            var numNextPercentChange = Number(
                nextPercentChange.replace(/\,/g, "").replace(/%/g, "")
            );

            percentChange.text(nextPercentChange);
            request_cell_blink_animation(
                percentChange,
                numNextPercentChange > numPrevPercentChange
                    ? "#e86565"
                    : "#6789ec"
            );
        }
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
            maxLatestCreated = Math.max(
                maxLatestCreated,
                Number(trade.created)
            );

            return (
                '<tr class="' +
                (Number(trade.created) > prevLatestCreated ? "change" : "") +
                " " +
                (trade.last_trade_kind || "") +
                '">' +
                "<td>" +
                trade.amt +
                "</td>" +
                "<td>" +
                trade.price +
                "</td>" +
                "<td>" +
                dateSet(moment.unix(trade.created)) +
                "</td>" +
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
        el.style.borderColor = "transparent";
    });
}

function request_cell_blink_animation(item, color) {
    var box = item.closest(".cell");
    var el = box[0];
    if (el.offsetParent === null) {
        return;
    }

    box.css("border-color", color);
    request_animation(el, "cell-blink");
}

function request_row_blink_animation(el, color) {
    if (el.offsetParent === null) {
        return;
    }

    el.style.backgroundColor = color;
    el.classList.add("row-blink");
    el.addEventListener("animationend", function() {
        el.classList.remove("row-blink");
        el.style.backgroundColor = "transparent";
    });
}
