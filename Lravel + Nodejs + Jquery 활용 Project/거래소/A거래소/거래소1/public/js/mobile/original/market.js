var ws;
$(document).ready(function () {
  var trade_coin_api = $('#top_ticker').data('coin');
  var request = [{
    type: 'orderbook',
    coins: [trade_coin_api]
  }, {
    type: 'ticker'
  }, {
    type: 'trade',
    coins: [trade_coin_api]
  }];

  if (window.location.href.indexOf("https://cointouse.com") == 0 || window.location.href.indexOf("https://prodweb.cointouse.com") == 0) {
    connect_ws('wss://wss.cointouse.com', JSON.stringify(request));
  } else {
    connect_ws('wss://ws.cointouse.com', JSON.stringify(request));
  }
});

function connect_ws(url, request) {
  ws = new WebSocket(url);
  ws.snapshot = false;
  ws.timeout = null;

  ws.onopen = function () {
    timeout_ws(ws);
    ws.send(request);
  };

  ws.onmessage = function (message) {
    if (message.data !== '[]') {
      ws.snapshot = true;
    }

    if (ws.snapshot === false) {
      ws.close();
    }

    timeout_ws(ws);

    try {
      if (data === '[]') {
        return;
      }

      var data = JSON.parse(message.data);

      if (data.result === 'error') {
        ws.close();
      }

      if (data.type === 'ticker') {
        refresh_market_ticker(data);
      } else if (data.type === 'orderbook') {
        refresh_market_orderbook(data);
        refresh_market_orderbook2(data);
      } else if (data.type === 'trade') {
        refresh_market_trade(data);
      }
    } catch (e) {}
  };

  ws.onclose = function (message) {
    clearTimeout(ws.timeout);
    setTimeout(function () {
      connect_ws(url, request);
    }, 5000);
  };
}

function timeout_ws(ws) {
  clearTimeout(ws.timeout);
  ws.timeout = setTimeout(function () {
    ws.timeout = null;
    ws.close();
  }, 15000);
}

function request_animation(item, animationName) {
  var el = item;
  el.classList.add(animationName);
  el.addEventListener('animationend', function () {
    el.classList.remove(animationName);
    el.style.borderColor = 'transparent';
  });
}

function request_cell_blink_animation(item, color) {
  var box = item.closest('.cell');
  box.css('border-color', color);
  request_animation(box[0], 'cell-blink');
}

function request_row_blink_animation(el, color) {
  el.style.backgroundColor = color;
  el.classList.add('row-blink');
  el.addEventListener('animationend', function () {
    el.classList.remove('row-blink');
    el.style.backgroundColor = 'transparent';
  });
}

function refresh_market_orderbook(data) {
  var sellWait = $('#sell_wait');
  var buyWait = $('#buy_wait');
  var prevSellOrders = sellWait.children();
  var nextSellOrders = data.sell_list.map(function (nextOrder) {
    var prevOrder = prevSellOrders.has('.orderbook_price:contains(' + nextOrder.price + ')');
    var change = '';

    if (prevOrder.length > 0) {
      if (prevOrder.find('.orderbook_amt').text() !== nextOrder.amt) {
        change = 'change';
      }
    } else {
      change = 'change';
    }

    return '<li class="' + change + '" data-price="' + nextOrder.price.replace(',', '') + '" data-amt="' + nextOrder.amt.replace(',', '') + '">' + '<span class="orderbook_price price_span">' + nextOrder.price + '</span>' + '<span class="orderbook_amt amt_span">' + nextOrder.amt + '</span>' + '<div class="per_bar" style="width: ' + Number(nextOrder.amount_percent) * 100 + '%;"></div>' + '</li>';
  }).reverse()
  /* CSS 속성 적용을 위해 역순으로 출력 */
  .join('');
  var prevBuyOrders = buyWait.children();
  var nextBuyOrders = data.buy_list.map(function (nextOrder) {
    var prevOrder = prevBuyOrders.has('.orderbook_price:contains(' + nextOrder.price + ')');
    var change = '';

    if (prevOrder.length > 0) {
      if (prevOrder.find('.orderbook_amt').text() !== nextOrder.amt) {
        change = 'change';
      }
    } else {
      change = 'change';
    }

    return '<li class="' + change + '"  data-price="' + nextOrder.price.replace(',', '') + '" data-amt="' + nextOrder.amt.replace(',', '') + '">' + '<span class="orderbook_price price_span">' + nextOrder.price + '</span>' + '<span class="orderbook_amt amt_span">' + nextOrder.amt + '</span>' + '<div class="per_bar" style="width: ' + Number(nextOrder.amount_percent) * 100 + '%;"></div>' + '</li>';
  }).join('');
  sellWait.html(nextSellOrders).find('.change').each(function (idx, val) {
    request_row_blink_animation(val, '#6789ec');
  });
  buyWait.html(nextBuyOrders).find('.change').each(function (idx, val) {
    request_row_blink_animation(val, '#e86565');
  });
}

function refresh_market_orderbook2(data) {
  var sellWait = $('#sell_wait2');
  var buyWait = $('#buy_wait2');
  var prevSellOrders = sellWait.children();
  var nextSellOrders = data.sell_list.map(function (nextOrder) {
    var prevOrder = prevSellOrders.has('.orderbook_price:contains(' + nextOrder.price + ')');
    var change = '';

    if (prevOrder.length > 0) {
      if (prevOrder.find('.orderbook_amt').text() !== nextOrder.amt) {
        change = 'change';
      }
    } else {
      change = 'change';
    }

    return '<li class="' + change + '" data-price="' + nextOrder.price.replace(',', '') + '" data-amt="' + nextOrder.amt.replace(',', '') + '">' + '<span class="orderbook_price">' + nextOrder.price + '</span>' + '<span class="orderbook_amt">' + nextOrder.amt + '</span>' + '<span class="orderbook_total">' + nextOrder.total + '</span>' + '<div class="per_bar" style="width: ' + Number(nextOrder.amount_percent) * 100 + '%;"></div>' + '</li>';
  }).join('');
  var prevBuyOrders = buyWait.children();
  var nextBuyOrders = data.buy_list.map(function (nextOrder) {
    var prevOrder = prevBuyOrders.has('.orderbook_price:contains(' + nextOrder.price + ')');
    var change = '';

    if (prevOrder.length > 0) {
      if (prevOrder.find('.orderbook_amt').text() !== nextOrder.amt) {
        change = 'change';
      }
    } else {
      change = 'change';
    }

    return '<li class="' + change + '"  data-price="' + nextOrder.price.replace(',', '') + '" data-amt="' + nextOrder.amt.replace(',', '') + '">' + '<span class="orderbook_price">' + nextOrder.price + '</span>' + '<span class="orderbook_amt">' + nextOrder.amt + '</span>' + '<span class="orderbook_total">' + nextOrder.total + '</span>' + '<div class="per_bar" style="width: ' + Number(nextOrder.amount_percent) * 100 + '%;"></div>' + '</li>';
  }).join('');
  sellWait.html(nextSellOrders).find('.change').each(function (idx, val) {
    request_row_blink_animation(val, '#6789ec');
  });
  buyWait.html(nextBuyOrders).find('.change').each(function (idx, val) {
    request_row_blink_animation(val, '#e86565');
  });
}

function refresh_market_ticker(data) {
  var topTicker = $('#top_ticker');
  var topCoin = topTicker.data('coin');
  var coinListTicker = $('#coin_list_table tbody');
  data.coin_data.forEach(function (item) {
    if (item.api === topCoin) {
      //Top ticker 색상 판단 기준값
      var numPriceChange24 = Number(item.price_change_24h.replace(/\,/g, ''));
      var lastTradePrice = topTicker.find('.last_trade_price');
      lastTradePrice.text(item.last_trade_price_usd);
      lastTradePrice.parent().removeClass('red blue').addClass(numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? 'red' : 'blue');
      var priceChange24h = topTicker.find('.price_change_24h');
      var nextPriceChange24h = (numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? '+' : '') + item.price_change_24h;
      priceChange24h.text(nextPriceChange24h);
      priceChange24h.parent().removeClass('red blue').addClass(numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? 'red' : 'blue');
      var percentChange24h = topTicker.find('.percent_change_24h');
      var nextPercentChange24h = '(' + (numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? '▲' : '▼') + item.percent_change_24h + '%' + ')';
      percentChange24h.text(nextPercentChange24h);
      percentChange24h.parent().removeClass('red blue').addClass(numPriceChange24 == 0 ? '' : numPriceChange24 > 0 ? 'red' : 'blue');
      topTicker.find('.high_price_24h .number_price').text(item.max_price);
      topTicker.find('.row_price_24h .number_price').text(item.min_price);
      topTicker.find('.trade_volume_24h .number_price').text(item.h24h_volume);
      $('#last_traded_price').text(item.last_trade_price_usd);
    }

    var tickerRow = coinListTicker.find('[data-coin=' + item.api + ']');

    if (tickerRow.length > 0) {
      var lastTradePrice = tickerRow.find('.last_trade_price_usd');
      var percentChange = tickerRow.find('.percent_change_24h');
      var numPercentChange = Number(item.percent_change_24h.replace(/\,/g, ''));
      lastTradePrice.removeClass('red blue').addClass(numPercentChange == 0 ? '' : numPercentChange > 0 ? 'red' : 'blue');
      percentChange.removeClass('red blue').addClass(numPercentChange == 0 ? '' : numPercentChange > 0 ? 'red' : 'blue');
      var prevTradePrice = lastTradePrice.text();
      var nextTradePrice = item.last_trade_price_usd;

      if (prevTradePrice !== nextTradePrice) {
        var numPrevTradePrice = Number(prevTradePrice.replace(/\,/g, ''));
        var numNextTradePrice = Number(nextTradePrice.replace(/\,/g, ''));
        lastTradePrice.text(nextTradePrice);
        request_cell_blink_animation(lastTradePrice, numNextTradePrice > numPrevTradePrice ? '#e86565' : '#6789ec');
      }

      var prevPercentChange = percentChange.text();
      var nextPercentChange = item.percent_change_24h + '%';

      if (prevPercentChange !== nextPercentChange) {
        var numPrevPercentChange = Number(prevPercentChange.replace(/\,/g, '').replace(/%/g, ''));
        var numNextPercentChange = Number(nextPercentChange.replace(/\,/g, '').replace(/%/g, ''));
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
  var trades = data.trade_list.map(function (trade) {
    maxLatestCreated = Math.max(maxLatestCreated, trade.created);
    var change = '';

    if (prevLatestCreated) {
      change = trade.created > prevLatestCreated ? 'change' : '';
    }

    if (trade.last_trade_kind == undefined) {
      trade.last_trade_kind = '';
    }

    if (cnt == 0) {
      last_price = trade.price;
      last_status = trade.last_trade_kind;
    }

    if (cnt == 1) {
      one_before_price = trade.price;
    }

    cnt++;
    price = new Decimal(trade.price.replace(',', ''));
    amt = parseFloat(trade.amt);
    total_price = price.mul(amt);
    total_price = new Decimal(total_price).toFixed(2, Decimal.ROUND_DOWN).toString();
    return '<tr class="' + change + " " + trade.last_trade_kind + '">' + '<td><span>' + trade.amt + '</span></td>' + '<td><span>' + formatNumber(price, 2) + '</span></td>' + '<td><span>' + dateSet(trade.created_dt) + '</span></td>' + '</tr>';
  }).join('');

  if (one_before_price > last_price) {
    color = 'blue';
  } else {
    color = 'red';
  }

  if (last_status == 'buy') {
    last_price = '↑' + last_price.toString();
  } else if (last_status == 'sell') {
    last_price = '↓' + last_price.toString();
  }

  $('#orderbook_middle').removeClass('blue');
  $('#orderbook_middle').removeClass('red');
  $('#orderbook_middle').addClass(color);
  $('#orderbook_middle').text(last_price);
  $('#orderbook_middle2').removeClass('blue');
  $('#orderbook_middle2').removeClass('red');
  $('#orderbook_middle2').addClass(color);
  $('#orderbook_middle2').text(last_price);
  tradeList.html(trades).data('latest', maxLatestCreated).find('.change').each(function (idx, val) {
    request_animation(val, 'blink');
  });
  add_my_readyorder_data();
  add_my_history_data();
}

var ajax_run = "n";
var fee_percent = 0;
var trade_fee = 0;
var cointype = $('input[name="coin_apiname"]').val();
var currency = "usd";
var call_unit = $('input[name="call_unit"]').val();
var decimal_usd = $('input[name="decimal_usd"]').val();
var sid;
$(document).ready(function () {
  fee_percent = new Decimal(parseFloat($('input[name="trade_fee"]').val()));
  sid = sess_id();
  $('.up_btn').click(function () {
    var dealtype = $(this).data('dealtype');
    var inp_val = $('#' + dealtype + '_coin_price').val();
    var id = $('#' + dealtype + '_coin_price').attr('id').split('_');
    var price = value_up_btn(inp_val);
    $('#' + dealtype + '_coin_price').val(price);

    if (id[0] == 'buy') {
      var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val()));
      trade_fee = buy_coin_price.mul(fee_percent);
      buy_coin_price = buy_coin_price.add(trade_fee);
      var buy_max_amount = new Decimal(parseFloat($('#buy_max_amount').val()));
      var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

      if (isNaN(buy_cash_amt.toString())) {
        buy_cash_amt = new Decimal(0.00);
      }

      $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString().toString());
    } else if (id[0] == 'sell') {
      var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val()));
      trade_fee = sell_coin_price.mul(fee_percent);
      sell_coin_price = sell_coin_price.sub(trade_fee);
      var sell_max_amount = new Decimal(parseFloat($('#sell_max_amount').val()));
      var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

      if (isNaN(sell_cash_amt.toString())) {
        sell_cash_amt = new Decimal(0.00);
      }

      $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString().toString());
    }
  });
  $('.down_btn').click(function () {
    var dealtype = $(this).data('dealtype');
    var inp_val = $('#' + dealtype + '_coin_price').val();
    var id = $('#' + dealtype + '_coin_price').attr('id').split('_');
    var price = value_down_btn(inp_val);
    $('#' + dealtype + '_coin_price').val(price);

    if (id[0] == 'buy') {
      var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val()));
      trade_fee = buy_coin_price.mul(fee_percent);
      buy_coin_price = buy_coin_price.add(trade_fee);
      var buy_max_amount = new Decimal(parseFloat($('#buy_max_amount').val()));
      var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

      if (isNaN(buy_cash_amt.toString())) {
        buy_cash_amt = new Decimal(0.00);
      }

      $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString().toString());
    } else if (id[0] == 'sell') {
      var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val()));
      trade_fee = sell_coin_price.mul(fee_percent);
      sell_coin_price = sell_coin_price.sub(trade_fee);
      var sell_max_amount = new Decimal(parseFloat($('#sell_max_amount').val()));
      var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

      if (isNaN(sell_cash_amt.toString())) {
        sell_cash_amt = new Decimal(0.00);
      }

      $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString().toString());
    }
  });
  $('#sell_wait').click(function (e) {
    // 호가창 매도 리스트 클릭시 가격, 수량 데이터 연동
    if (e.target === e.currentTarget) {
      return;
    }

    var orderItem = $(e.target).closest('li');
    var price = parseFloat($(orderItem).data('price'));
    var amt = parseFloat($(orderItem).data('amt'));
    $('.buysell_price_inp').val(price);
    $('#buy_max_amount').val(amt);
    $('#sell_max_amount').val(0);
    var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val()));
    trade_fee = buy_coin_price.mul(fee_percent);
    buy_coin_price = buy_coin_price.add(trade_fee);
    var buy_cash_amt = buy_coin_price.mul(parseFloat($('#buy_max_amount').val()));
    $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString().toString());
    $('#sell_cash_amt').text('');
  });
  $('#buy_wait').click(function (e) {
    // 호가창 매수 리스트 클릭시 가격, 수량 데이터 연동
    if (e.target === e.currentTarget) {
      return;
    }

    var orderItem = $(e.target).closest('li');
    var price = parseFloat($(orderItem).data('price'));
    var amt = parseFloat($(orderItem).data('amt'));
    var max_decimal = $('#sell_coin_price').data('decimal') + 8;
    $('.buysell_price_inp').val(price);
    $('#sell_max_amount').val(amt);
    $('#buy_max_amount').val('');
    var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val()));
    trade_fee = sell_coin_price.mul(fee_percent);
    sell_coin_price = sell_coin_price.sub(trade_fee);
    var sell_cash_amt = sell_coin_price.mul(parseFloat($('#sell_max_amount').val()));
    $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString().toString());
    $('#buy_cash_amt').text(0);
  });
  $('#buy_coin_price').keyup(function () {
    var buy_coin_price = new Decimal(parseFloat($(this).val().replace(",", "")));

    if (isNaN(buy_coin_price.toString())) {
      buy_coin_price = new Decimal(0);
    } else {
      trade_fee = buy_coin_price.mul(fee_percent);
      buy_coin_price = buy_coin_price.add(trade_fee);
    }

    var buy_max_amount = parseFloat($('#buy_max_amount').val().replace(",", ""));
    var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

    if (isNaN(buy_cash_amt.toString())) {
      buy_cash_amt = new Decimal(0);
    }

    $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString().toString());
  });
  $('#sell_coin_price').keyup(function () {
    var sell_coin_price = new Decimal(parseFloat($(this).val().replace(",", "")));

    if (isNaN(sell_coin_price.toString())) {
      sell_coin_price = new Decimal(0);
    } else {
      trade_fee = sell_coin_price.mul(fee_percent);
      sell_coin_price = sell_coin_price.sub(trade_fee);
    }

    var sell_max_amount = parseFloat($('#sell_max_amount').val().replace(",", ""));
    var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

    if (isNaN(sell_cash_amt.toString())) {
      sell_cash_amt = new Decimal(0);
    }

    $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString());
  });
  $('#buy_max_amount').keyup(function () {
    var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val().replace(",", "")));

    if (isNaN(buy_coin_price.toString())) {
      buy_coin_price = new Decimal(0);
    } else {
      trade_fee = buy_coin_price.mul(fee_percent);
      buy_coin_price = buy_coin_price.add(trade_fee);
    }

    var buy_max_amount = parseFloat($(this).val().replace(",", ""));
    var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

    if (isNaN(buy_cash_amt.toString())) {
      buy_cash_amt = new Decimal(0);
    }

    $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString());
  });
  $('#sell_max_amount').keyup(function () {
    var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val().replace(",", "")));

    if (isNaN(sell_coin_price.toString())) {
      sell_coin_price = new Decimal(0);
    } else {
      trade_fee = sell_coin_price.mul(fee_percent);
      sell_coin_price = sell_coin_price.sub(trade_fee);
    }

    var sell_max_amount = parseFloat($(this).val().replace(",", ""));
    var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

    if (isNaN(sell_cash_amt.toString())) {
      sell_cash_amt = new Decimal(0);
    }

    $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString());
  });
  $('.buysell_table ul.buy_percent li button').click(function () {
    var percent = parseFloat(parseInt($(this).text().replace("%", "")) / 100);
    var my_asset = parseFloat($('input[name="my_asset_cash"]').val());
    $('#buy_max_amount').val(my_cash_percent(my_asset, percent));
    var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val().replace(",", "")));
    trade_fee = buy_coin_price.mul(fee_percent);
    buy_coin_price = buy_coin_price.add(trade_fee);
    var buy_max_amount = parseFloat($('#buy_max_amount').val().replace(",", ""));
    var buy_cash_amt = buy_coin_price.mul(buy_max_amount);
    $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString());
  });
  $('.buysell_table ul.sell_percent li button').click(function () {
    var percent = parseFloat(parseInt($(this).text().replace("%", "")) / 100);
    var my_asset = parseFloat($('input[name="my_asset_coin"]').val());
    $('#sell_max_amount').val(my_coin_percent(my_asset, percent));
    var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val().replace(",", "")));
    trade_fee = sell_coin_price.mul(fee_percent);
    sell_coin_price = sell_coin_price.sub(trade_fee);
    var sell_max_amount = parseFloat($('#sell_max_amount').val().replace(",", ""));
    var sell_cash_amt = sell_coin_price.mul(sell_max_amount);
    $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(2, Decimal.ROUND_DOWN).toString());
  });
  $('#readyorder_wrap button').click(function (e) {//var orderItem =  $(e);
    //var id = orderItem.data('id');
    //trade_cancel(id);
  });
});

function value_up_btn(value) {
  return (parseFloat(value) + 0.01).toFixed(2);
}

function value_down_btn(value) {
  return (parseFloat(value) - 0.01).toFixed(2);
}

function AddComma(n) {
  var parts = n.toString().split(".");
  return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function comma(str) {
  var parts = str.toString().split(".");
  return parts[0].replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,') + (parts[1] ? "." + parts[1] : "");
}

function uncomma(str) {
  str = String(str);
  return str.replace(/[^\d]+/g, '');
}

function my_cash_percent(cash, percent) {
  var coin_price = new Decimal($('#buy_coin_price').val());
  trade_fee = coin_price.mul(fee_percent);
  coin_price = coin_price.add(trade_fee);
  var cash2 = new Decimal(cash);
  var coin_a = cash2.mul(percent);
  var coin_amt = coin_a.div(coin_price);
  coin_amt = new Decimal(coin_amt).toDecimalPlaces(8, Decimal.ROUND_DOWN).toString();
  return coin_amt;
}

function test() {
  swal({
    text: 'Tlqkf',
    icon: "warning",
    button: __.message.ok
  });
}

function my_coin_percent(coin, percent) {
  var coin_price = new Decimal($('#sell_coin_price').val());
  trade_fee = coin_price.mul(fee_percent);
  coin_price = coin_price.sub(trade_fee);
  var coin2 = new Decimal(coin);
  var coin_amt = coin2.mul(percent);
  coin_amt = new Decimal(coin_amt).toDecimalPlaces(8, Decimal.ROUND_DOWN).toString();
  return coin_amt;
}

function buysell_coin_data(order_type, coin_currency, symbol) {
  if (ajax_run == 'n') {
    ajax_run = 'y';

    if (order_type == "buy") {
      var btc_price = onlyNumber_price($("#buy_coin_price").val());
      var max_amount = onlyNumber_amount($("#buy_max_amount").val());

      if (btc_price === "" || parseFloat(btc_price) == 0 || max_amount === "" || parseFloat(max_amount) == 0) {
        swal({
          text: '가격이나 수량이 잘못되었습니다',
          icon: "error",
          button: __.message.ok
        });
        ajax_run = 'n';
        return;
      }

      var cash_amt = $("#buy_cash_amt").val();
      var currency_balance = $('input[name="my_asset_cash"]').val().replace(",", "");

      if (parseFloat(currency_balance) < parseFloat(btc_price) * parseFloat(max_amount)) {
        alertify.error(__.message.less_than_buy);
        ajax_run = 'n';
      } else {
        $.ajax({
          url: "/requests/buysell_coin_data",
          type: "POST",
          data: {
            _token: CSRF_TOKEN,
            symbol: symbol,
            btc_price: btc_price,
            max_amount: max_amount,
            buy_cash_amt: cash_amt,
            order_type: order_type,
            coin_currency: coin_currency,
            currency_balance: currency_balance
          },
          dataType: 'JSON',
          success: function success(data) {
            if (data.data == 1) {
              alertify.buy(__.message.success_purchase);

              if (data.trade_result.response_buy.uid == sid) {
                alertify.success(data.trade_result.response_buy.amount + data.trade_result.response_buy.cointype + __.message.success_buy);
              }

              if (data.trade_result.response_sell.uid == sid) {
                alertify.success(data.trade_result.response_sell.amount + data.trade_result.response_sell.cointype + __.message.success_to_sell);
              }

              refresh_my_data();
              add_my_readyorder_data();
              add_my_history_data();
            } else if (data.data == 2) {
              alertify.error(__.message.incorrect_quantity);
            } else if (data.data == 4) {
              alertify.error(__.message.less_than_buy);
            } else if (data.data == 5) {
              alertify.error(__.message.not_register_more_than_20_sell);
            } else if (data.data == 10) {
              alertify.error('계정 정지 상태! 비정상적 경로 접근!');
            } else {
              alertify.error(__.message.less_than_buy);
            }

            ajax_run = 'n';
          }
        });
      }
    } else if (order_type == "sell") {
      var btc_price = onlyNumber_price($("#sell_coin_price").val());
      var max_amount = onlyNumber_amount($("#sell_max_amount").val());

      if (btc_price === "" || parseFloat(btc_price) == 0 || max_amount === "" || parseFloat(max_amount) == 0) {
        swal({
          text: '가격이나 수량이 잘못되었습니다',
          icon: "error",
          button: __.message.ok
        });
        ajax_run = 'n';
        return;
      }

      var cash_amt = $("#sell_cash_amt").val();
      var currency_balance = $('input[name="my_asset_cash"]').val().replace(",", "");
      var balance = parseFloat($('input[name="my_asset_coin"]').val().replace(",", "")); // 현재 잔액

      if (balance < parseFloat(max_amount)) {
        alertify.error(__.message.less_than_sell);
        ajax_run = 'n';
      } else {
        $.ajax({
          url: "/requests/buysell_coin_data",
          type: "POST",
          data: {
            _token: CSRF_TOKEN,
            symbol: symbol,
            btc_price: btc_price,
            max_amount: max_amount,
            sell_cash_amt: cash_amt,
            order_type: order_type,
            coin_currency: coin_currency,
            currency_balance: currency_balance
          },
          dataType: 'JSON',
          success: function success(data) {
            if (data.data == 1) {
              alertify.sell(__.message.success_sell);

              if (data.trade_result.response_buy.uid == sid) {
                alertify.success(data.trade_result.response_buy.amount + data.trade_result.response_buy.cointype + __.message.success_buy);
              }

              if (data.trade_result.response_sell.uid == sid) {
                alertify.success(data.trade_result.response_sell.amount + data.trade_result.response_sell.cointype + __.message.success_to_sell);
              }

              refresh_my_data();
              add_my_readyorder_data();
              add_my_history_data();
            } else if (data.data == 2) {
              alertify.error(__.message.incorrect_quantity);
            } else if (data.data == 4) {
              alertify.error('시세조작 방지를 위해 현재 시세 대비 20% 이하로 매수 등록할 수 없습니다.');
            } else if (data.data == 5) {
              alertify.error('정상적인 거래를 위해 현재 시세 대비 20% 이상으로 매도 등록할 수 없습니다.');
            } else if (data.data == 10) {
              alertify.error('계정 정지 상태! 비정상적 경로 접근!');
            } else {
              alertify.error(__.message.less_than_sell);
            }

            ajax_run = 'n';
          }
        });
      }
    }
  }
}

function sess_id() {
  $.ajax({
    url: "/requests/sess_id",
    type: "POST",
    data: {
      _token: CSRF_TOKEN
    },
    dataType: 'JSON',
    success: function success(data) {
      sid = data.uid;
    }
  });
}

function refresh_my_data() {
  if (login_yn) {
    $.ajax({
      url: "/requests/refresh_user_data",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        cointype: cointype,
        currency: currency
      },
      dataType: 'JSON',
      success: function success(data) {
        $('input[name="my_asset_cash"]').val(data.currency_balance);
        $('input[name="my_asset_coin"]').val(data.coin_balance); // 현재 잔액

        $("#use_balance_buy").html("USDC" + __.message.remain_amt + " : " + data.currency_balance_front + " USDC");
        $("#use_balance_sell").html(cointype.toUpperCase() + __.message.remain_amt + " : " + data.coin_balance_front + " " + cointype.toUpperCase());
      }
    });
  }
}

function add_my_readyorder_data() {
  if (login_yn) {
    //// JSON Call 시작
    var str = '';
    var m_str = '';
    var m_str2 = '';
    $.ajax({
      url: "/livedata/refresh_user_readyorder",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        cointype: cointype
      },
      dataType: 'JSON',
      success: function success(data) {
        $("#ready_order_queue").empty();

        if (data.length > 0) {
          $.each(data, function () {
            str += '<li class="con ' + this.type + '"><p class="info _date">';
            str += '<span>' + dateSet(this.local_time) + '</span>';
            str += '<span class="float-right type">' + this.type_name + '</span></p>';
            str += '<p class="info _coin">' + this.coinname + '</p>';
            str += '<p class="info _amt"><label>' + __.message.amt + '</label>';
            str += '<span class="point_clr_1">' + this.ads_amount + '</span></p>';
            str += '<p class="info"><label>' + __.message.price + '</label>';
            str += '<span>' + this.ads_price + '</span>';
            str += '<span class="currency"> USDC</span></p>';
            str += '<p class="info"><label>' + __.message.total_price + '</label>';
            str += '<span>' + this.ads_total_amount + '</span>';
            str += '<span class="currency"> USDC</span></p>';
            str += '<p class="info"><label>' + __.message.percent + '</label>';
            str += '<span>' + this.ads_percent + '</span>';
            str += '<span>%</span></p>';
            str += '<p class="info"><label>' + __.message.status + '</label>';

            if (this.status == 'OnProgress') {
              str += '<button type="button" id="btc_cancel_request_' + this.id + '" class="btc_cancel_request mr-1" data-id="' + this.id + '" onclick="trade_cancel(' + this.id + ')">' + __.message.trade_cancel + '</button>';
            }

            str += '<span class="status_type">' + this.lang_status + '</span></p></li>';
          });
        } else {
          str = '<li class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>' + __.message.today_trade_not_readyorder + '</li>';
        }

        $("#ready_order_queue").append(str);
      }
    });
  }
}

function add_my_history_data() {
  if (login_yn) {
    //// JSON Call 시작
    var str = '';
    $.ajax({
      url: "/livedata/refresh_user_history",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        cointype: cointype
      },
      dataType: 'JSON',
      success: function success(data) {
        $("#ready_history_queue").empty();

        if (data.length > 0) {
          $.each(data, function () {
            str += '<li class="con ' + this.type + '">';
            str += '<p class="info _date"><span>' + dateSet(this.trade_date) + '</span>';
            str += '<span class="float-right type">' + this.type_name + '</span></p>';
            str += '<p class="info _coin">' + this.coinname + '</p>';
            str += '<p class="info _amt"><label>' + __.message.amt + '</label>';
            str += '<span>' + this.trade_amount + '</span></p>';
            str += '<p class="info"><label>' + __.message.price + '</label>';
            str += '<span>' + this.trade_price + '</span>';
            str += '<span class="currency"> USDC</span></p>';
            str += '<p class="info"><label>' + __.message.total_price + '</label>';
            str += '<span>' + this.trade_total_amount + '</span>';
            str += '<span class="currency"> USDC</span></p>';
            str += '</li>';
            $("#ready_history_queue").append(str);
          });
        } else {
          str = '<li class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>' + __.message.today_trade_not_history + '</li>';
          $("#ready_history_queue").append(str);
        }
      }
    });
  }
}

function trade_cancel(id) {
  swal({
    text: __.message.real_trade_cancel_confirm,
    icon: "warning",
    buttons: {
      yes: {
        text: __.message.yes,
        value: true
      },
      no: {
        text: __.message.no,
        value: false
      }
    }
  }).then(function (value) {
    if (value) {
      $.ajax({
        url: "/requests/trade_cancel",
        type: "POST",
        data: {
          _token: CSRF_TOKEN,
          id: id
        },
        dataType: 'JSON',
        success: function success(data) {
          alertify.success(data.message);
          add_my_readyorder_data();
          refresh_my_data();
        }
      });
    }
  });
}

function numberWithCommas(n) {
  var parts = n.toString().split(".");
  return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function onlyNumber_amount(price) {
  if (price.indexOf('.') != -1) {
    var parts = price.toString().split(".");

    if (parts[1] == '') {
      return parts[0].replace(/[^0-9]/g, "") + ".";
    } else {
      if (parts[1].length > 8) {
        parts[1] = parts[1].substr(0, 8);
      }

      return parts[0].replace(/[^0-9]/g, "") + (parts[1].replace(/[^0-9]/g, "") ? "." + parts[1].replace(/[^0-9]/g, "") : "");
    }
  } else {
    return price.replace(/[^0-9]/g, "");
  }
}

function onlyNumber_price(price) {
  if (price.indexOf('.') != -1) {
    var parts = price.toString().split(".");

    if (parts[1] == '') {
      return parts[0].replace(/[^0-9]/g, "") + ".";
    } else {
      if (parts[1].length > decimal_usd) {
        parts[1] = parts[1].substr(0, decimal_usd);
      }

      return parts[0].replace(/[^0-9]/g, "") + (parts[1].replace(/[^0-9]/g, "") ? "." + parts[1].replace(/[^0-9]/g, "") : "");
    }
  } else {
    return price.replace(/[^0-9]/g, "");
  }
}

function formatNumber(num, fixed) {
  var parts = new Decimal(num).toFixed(fixed).split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  return parts.join('.');
}

function formatNumberJW(num) {
  var parts = new Decimal(num).toString().split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); //alert(parts.join('.'));

  return parts.join('.');
}

function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
      results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

TradingView.onready(function () {
  var widget = window.tvWidget = new TradingView.widget({
    //debug: true, // uncomment this line to see Library errors and warnings in the console
    //fullscreen: true,
    symbol: $('input[name="coin_apiname"]').val().toUpperCase(),
    //symbol name
    timezone: 'Asia/Seoul',
    autosize: true,
    interval: '60',
    container_id: "chartdiv",
    datafeed: new Datafeeds.UDFCompatibleDatafeed($('input[name="setting_url"]').val()),
    library_path: "/vendor/tradingview_new/charting_library/",
    theme: "Light",
    style: "1",
    locale: __.message.trading_view,
    toolbar_bg: "rgb(240,240,240)",
    enable_publishing: false,
    hide_legend: false,
    hide_top_toolbar: false,
    save_image: true,
    drawings_access: {
      type: 'black',
      tools: [{
        name: "Regression Trend"
      }]
    },
    disabled_features: ["use_localstorage_for_settings", "timeframes_toolbar"],
    //disabled_features: ["use_localstorage_for_settings","header_widget","symbol_description","left_toolbar","timeframes_toolbar","legend_context_menu","edit_buttons_in_legend","hide_last_na_study_output"],
    charts_storage_api_version: "1.1",
    client_id: 'trademarket.local',
    user_id: 'public_user_id',
    chartTypes: ["Area", "Line"]
  });
});
