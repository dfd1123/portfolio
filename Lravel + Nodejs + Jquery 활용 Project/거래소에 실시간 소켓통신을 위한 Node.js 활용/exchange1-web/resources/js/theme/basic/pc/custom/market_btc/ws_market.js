var ws;

$( document ).ready(function() {
    var trade_coin_api = $('#top_ticker').data('coin');
    var trade_market = $('input[type=hidden][name=standard_api]').val();

    var request = [
        {
            market: trade_market,
            type: 'orderbook',
            coins: [trade_coin_api]
        },
        {
            type: 'ticker'
        },
        {
            market: trade_market,
            type: 'trade',
            coins: [trade_coin_api]
        }
    ]
    //connect_ws('ws://localhost:8080', JSON.stringify(request));
    //connect_ws('wss://exchange1-ws.pocketcompany.co.kr', JSON.stringify(request));
    if(window.location.href.indexOf("https://sharebits.world") == 0) {
        connect_ws('wss://wss.sharebits.world', JSON.stringify(request));
    } else {
        connect_ws('wss://exchange1-ws.pocketcompany.co.kr', JSON.stringify(request));
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
        if(message.data !== '[]') {
            ws.snapshot = true;
        }
        
        if(ws.snapshot === false) {
            ws.close();
        }
        
        timeout_ws(ws);
        try {
            if(data === '[]') {
                return;
            }
            
            var data = JSON.parse(message.data);
            if(data.result === 'error') {
                ws.close();
            }
            
            if(data.type === 'ticker') {
                refresh_market_ticker(data);
            } else if(data.type === 'orderbook') {
                refresh_market_orderbook(data);
            } else if(data.type === 'trade') {
                refresh_market_trade(data);
            }
            
        } catch (e) {
            console.log(e);
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

function request_animation(item, animationName) {
    var el = item;
    el.classList.add(animationName);
    el.addEventListener('animationend', function() {
        el.classList.remove(animationName);
        el.style.borderColor = 'transparent';	
    });
}

function request_cell_blink_animation(item, color){
    var box = item.closest('.cell');
    box.css('border-color', color);
    request_animation(box[0], 'cell-blink');
}

function request_row_blink_animation(el, color){
    el.style.backgroundColor = color;
    el.classList.add('row-blink');
    el.addEventListener('animationend', function() {
        el.classList.remove('row-blink');
        el.style.backgroundColor = 'transparent';	
    });
}

function refresh_market_orderbook(data) {
    var sellWait = $('#sell_wait tbody');
    var buyWait = $('#buy_wait tbody');
    
    var prevSellOrders = sellWait.children();
    var nextSellOrders = data
        .sell_list.map(function(nextOrder) {
            var prevOrder = prevSellOrders.has('.orderbook_price:contains(' + nextOrder.price + ')');
            var change = '';
            
            if(prevOrder.length > 0) {
                if(prevOrder.find('.orderbook_amt').text() !== nextOrder.amt || prevOrder.find('.orderbook_total').text() !== nextOrder.total) {
                    change = 'change';
                }
            } else {
                change = 'change';
            }
            
            return '<tr class="' + change + '" data-price="' + nextOrder.price.replace(',','') + '" data-amt="' + nextOrder.amt + '">' + 
            '<td class="blue"><span class="orderbook_price">' + nextOrder.price + '</span></td>' +
            '<td><span class="orderbook_amt">' + nextOrder.amt + '</span></td>' +
            '<td><span class="orderbook_total">' + nextOrder.total + '</span><div class="per_bar" style="width: ' +  Number(nextOrder.amount_percent) * 600 + '%;"></div></td>' +
            '</tr>';
            
        })
        .join('');
        
    var prevBuyOrders = buyWait.children();
    var nextBuyOrders = data
        .buy_list.map(function(nextOrder) {
            var prevOrder = prevBuyOrders.has('.orderbook_price:contains(' + nextOrder.price + ')');
            var change = '';
            
            if(prevOrder.length > 0) {
                if(prevOrder.find('.orderbook_amt').text() !== nextOrder.amt || prevOrder.find('.orderbook_total').text() !== nextOrder.total) {
                    change = 'change';
                }
            } else {
                change = 'change';
            }
            
            return '<tr class="' + change + '"  data-price="' + nextOrder.price.replace(',','') + '" data-amt="' + nextOrder.amt + '">' + 
            '<td class="red"><span class="orderbook_price">' + nextOrder.price + '</span></td>' +
            '<td><span class="orderbook_amt">' + nextOrder.amt + '</span></td>'+
            '<td><span class="orderbook_total">' + nextOrder.total + '</span><div class="per_bar" style="width: ' + Number(nextOrder.amount_percent) * 600 + '%;"></div></td>' +
            '</tr>';
        })
        .join('');
    
    sellWait.html(nextSellOrders).find('.change').each(function(idx, val){
        request_row_blink_animation(val, '#6789ec');
    });
    buyWait.html(nextBuyOrders).find('.change').each(function(idx, val){
        request_row_blink_animation(val, '#e86565');
    });
}

function refresh_market_ticker(data) {
    var topTicker = $('#top_ticker');
    var topMarket = topTicker.data('market');
    var topCoin = topTicker.data('coin');
    var coinListTicker = $('#coin_list_table_' + data.market + ' tbody');

    var hmUsd = $('input[type=hidden][name="hm_usd"]');
    var hmDec = $('input[type=hidden][name="hm_dec"]');
    var hmCur = $('input[type=hidden][name="hm_cur"]');
    
    data.coin_data.forEach(function(item) {
        // 각 거래소 별 기준코인의 UCSS값 업데이트 (UCSS 마켓은 항상 1)
        if(topMarket === 'btc' && data.market === 'usd' && item.api === 'btc') {
            hmUsd.val(item.last_trade_price_usd.replace(/\,/g,''));
        } else if(topMarket === 'eth' && data.market === 'usd' && item.api === 'eth') {
            hmUsd.val(item.last_trade_price_usd.replace(/\,/g,''));
        }

        if(data.market === topMarket && item.api === topCoin) {
            //Top ticker 색상 판단 기준값
            var numPriceChange24 = Number(item.price_change_24h.replace(/\,/g,''));
            
            var lastTradePrice = topTicker.find('.last_trade_price');
            lastTradePrice.text(item.last_trade_price_usd);
            lastTradePrice.parent().removeClass('red blue').addClass(numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? 'red' : 'blue');

            //현재 마켓에서의 코인 값 * 마켓이 사용중인 기준 코인의 개당 USD값 * USD와 사용자 화폐의 환율값
            var lastTradePriceCurrency = topTicker.find('.last_trade_price_currency');
            var numLastTradePriceCurrency = Number(item.last_trade_price_usd.replace(/\,/g,''));
            var nextLastTradePriceCurrency = formatNumber(Decimal.mul(Decimal.mul(numLastTradePriceCurrency, hmUsd.val()), hmCur.val()), Number(hmDec.val()));
            lastTradePriceCurrency.text(nextLastTradePriceCurrency);
            lastTradePriceCurrency.parent().removeClass('red blue').addClass(numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? 'red' : 'blue');

            var priceChange24h = topTicker.find('.price_change_24h');
            var nextPriceChange24h = '(' + (numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? '+' : '-') + item.price_change_24h + ')';
            priceChange24h.text(nextPriceChange24h);
            priceChange24h.parent().removeClass('red blue').addClass(numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? 'red' : 'blue');
            
            var percentChange24h = topTicker.find('.percent_change_24h');
            var nextPercentChange24h = '(' + (numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? '▲' : '▼') + item.percent_change_24h + '%' +')';
            percentChange24h.text(nextPercentChange24h);
            percentChange24h.parent().removeClass('red blue').addClass(numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? 'red' : 'blue');

            topTicker.find('.high_price_24h .number_price').text(item.max_price);
            topTicker.find('.row_price_24h .number_price').text(item.min_price);
            topTicker.find('.trade_volume_24h .number_price').text(item.h24h_volume);
        }
        
        var tickerRow = coinListTicker.find('[data-coin=' + item.api + ']');
        if(tickerRow.length > 0) {
            var lastTradePrice = tickerRow.find('.last_trade_price_usd');
            var percentChange = tickerRow.find('.percent_change_24h');
            
            var numPercentChange = Number(item.percent_change_24h.replace(/\,/g,''));
            
            lastTradePrice.removeClass('red blue').addClass(numPercentChange == 0 ? '' : numPercentChange > 0 ? 'red' : 'blue');
            percentChange.removeClass('red blue').addClass(numPercentChange == 0 ? '' : numPercentChange > 0 ? 'red' : 'blue');
            
            
            var prevTradePrice = lastTradePrice.text();
            var nextTradePrice = item.last_trade_price_usd;
            if(prevTradePrice !== nextTradePrice) {
                var numPrevTradePrice = Number(prevTradePrice.replace(/\,/g,''));
                var numNextTradePrice = Number(nextTradePrice.replace(/\,/g,''));	
                
                lastTradePrice.text(nextTradePrice);
                request_cell_blink_animation(lastTradePrice, numNextTradePrice > numPrevTradePrice ? '#e86565' : '#6789ec');
            }
            
            var prevPercentChange = percentChange.text();
            var nextPercentChange = item.percent_change_24h + '%';
            if(prevPercentChange !== nextPercentChange) {
                var numPrevPercentChange = Number(prevPercentChange.replace(/\,/g,'').replace(/%/g,''));
                var numNextPercentChange = Number(nextPercentChange.replace(/\,/g,'').replace(/%/g,''));
                
                percentChange.text(nextPercentChange);
                request_cell_blink_animation(percentChange, numNextPercentChange > numPrevPercentChange ? '#e86565' : '#6789ec');
            }
        }
    });
}

function refresh_market_trade(data) {
    var tradeList = $('#trade_list');
    var maxLatestCreated = 0;
    var prevLatestCreated = Number(tradeList.data('latest') ? tradeList.data('latest') : null);
    var tradeDate = '';
    var last_status = '';
    var last_price = 0;
    var color = '';
    var cnt = 0;
    var price = 0;
    var total_price = 0;
    var decimal_limit = Number($('input[type=hidden][name="decimal_usd"]').val());
    var trades = data
        .trade_list.map(function(trade) {
            maxLatestCreated = Math.max(maxLatestCreated, trade.created);
            
            var change = '';
            if(prevLatestCreated) {
                change = trade.created > prevLatestCreated ? 'change' : '';
            }

            if(trade.last_trade_kind == undefined){
                trade.last_trade_kind = '';
            }

            if(cnt == 0){
                last_price = trade.price;
                last_status = trade.last_trade_kind;
            }

            if(cnt == 1){
                one_before_price = trade.price;
            }

            cnt++;
            
            return '<tr class="' + change +" "+ trade.last_trade_kind + '">' +
                        '<td>' + trade.amt + '</td>' +
                        '<td>' + formatNumber(trade.price.replace(/\,/g,'').replace(/%/g,''), decimal_limit) + '</td>' +
                        '<td>' + dateSet(trade.created_dt) + '</td>' +
                    '</tr>';
        })
        .join('');

    if(one_before_price > last_price){
        color = 'blue';
    }else{
        color = 'red';
    }

    if(last_status == 'buy'){
        last_price = '↑' + last_price.toString();
    }else if(last_status == 'sell'){
        last_price = '↓' + last_price.toString();
    }

    $('#orderbook_middle').removeClass('blue');
    $('#orderbook_middle').removeClass('red');
    $('#orderbook_middle').addClass(color);
    $('#orderbook_middle').text(last_price);
    
    tradeList
        .html(trades)
        .data('latest', maxLatestCreated)
        .find('.change')
        .each(function(idx, val){
            request_animation(val, 'blink');
        });

    add_my_readyorder_data();
    add_my_history_data();
} 

