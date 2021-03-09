var ajax_run = "n";
var fee_percent = 0;
var trade_fee = 0;
var cointype = $('input[name="coin_apiname"]').val();
var currency = $('input[name="standard_api"]').val();
var call_unit = $('input[name="call_unit"]').val();
var decimal_usd = parseFloat($('input[name="decimal_usd"]').val());
var sid;

$( document ).ready(function() {

    fee_percent = new Decimal(parseFloat($('input[name="trade_fee"]').val()));

    sid = sess_id();

    $('.up_btn').click(function(){
        var inp_val = $(this).siblings('input').val();
        var id = $(this).siblings('input').attr('id').split('_');
        var price = value_up_btn(inp_val);

        $(this).siblings('input').val(price);

        if(id[0] == 'buy'){
            var buy_coin_price  =  new Decimal(parseFloat($('#buy_coin_price').val()));
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
            var buy_max_amount  = new Decimal(parseFloat($('#buy_max_amount').val()));
            var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

            if(isNaN(buy_cash_amt.toString())){
                buy_cash_amt = new Decimal(0.00);
            }

            $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        }else if(id[0] == 'sell'){
            var sell_coin_price  =  new Decimal(parseFloat($('#sell_coin_price').val()));
            trade_fee = sell_coin_price.mul(fee_percent);
            sell_coin_price = sell_coin_price.sub(trade_fee);
            var sell_max_amount  = new Decimal(parseFloat($('#sell_max_amount').val()));
            var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

            if(isNaN(sell_cash_amt.toString())){
                sell_cash_amt = new Decimal(0.00);
            }

            $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        }

    });

    $('.down_btn').click(function(){
        var inp_val = $(this).siblings('input').val();
        var id = $(this).siblings('input').attr('id').split('_');
        var price = value_down_btn(inp_val);

        $(this).siblings('input').val(price);

        if(id[0] == 'buy'){
            var buy_coin_price  =  new Decimal(parseFloat($('#buy_coin_price').val()));
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
            var buy_max_amount  = new Decimal(parseFloat($('#buy_max_amount').val()));
            var buy_cash_amt = buy_coin_price.mul(buy_max_amount);
            
            if(isNaN(buy_cash_amt.toString())){
                buy_cash_amt = new Decimal(0.00);
            }

            $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        }else if(id[0] == 'sell'){
            var sell_coin_price  =  new Decimal(parseFloat($('#sell_coin_price').val()));
            trade_fee = sell_coin_price.mul(fee_percent);
            sell_coin_price = sell_coin_price.sub(trade_fee);
            var sell_max_amount  = new Decimal(parseFloat($('#sell_max_amount').val()));
            var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

			 if(isNaN(sell_cash_amt.toString())){
                sell_cash_amt = new Decimal(0.00);
            }

            $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        }
    });

    $('.up_wrap.wait_wrap').click(function(e){  // 호가창 매도 리스트 클릭시 가격, 수량 데이터 연동
        if(e.target === e.currentTarget) { return; }
        var orderItem =  $(e.target).closest('tr');

        var price = parseFloat($(orderItem).data('price'));
        var amt = parseFloat($(orderItem).data('amt'));

        $('.buysell_price_inp').val(price);
        $('#buy_max_amount').val(amt);
        $('#sell_max_amount').val(0);

        var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val()));
        trade_fee = buy_coin_price.mul(fee_percent);
        buy_coin_price = buy_coin_price.add(trade_fee);
        var buy_cash_amt = buy_coin_price.mul(parseFloat($('#buy_max_amount').val()));

        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        $('#sell_cash_amt').text('');
    });

    $('.down_wrap.wait_wrap').click(function(e){  // 호가창 매수 리스트 클릭시 가격, 수량 데이터 연동
        if(e.target === e.currentTarget) { return; }
        var orderItem =  $(e.target).closest('tr');

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
        

        $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        $('#buy_cash_amt').text(0);
    });

    $('#buy_coin_price').keyup(function(){

        var buy_coin_price = new Decimal(parseFloat($(this).val().replace(",", "")));
        if(isNaN(buy_coin_price.toString())){
            buy_coin_price = new Decimal(0);
        }else{
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
        }
        var buy_max_amount = parseFloat($('#buy_max_amount').val().replace(",", ""));
        var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

        if(isNaN(buy_cash_amt.toString())){
            buy_cash_amt = new Decimal(0);
        }

        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
    });

    $('#sell_coin_price').keyup(function(){

        var sell_coin_price = new Decimal(parseFloat($(this).val().replace(",", "")));
        if(isNaN(sell_coin_price.toString())){
            sell_coin_price = new Decimal(0);
        }else{
            trade_fee = sell_coin_price.mul(fee_percent);
            sell_coin_price = sell_coin_price.sub(trade_fee);
        }
        var sell_max_amount = parseFloat($('#sell_max_amount').val().replace(",", ""));
        var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

        if(isNaN(sell_cash_amt.toString())){
            sell_cash_amt = new Decimal(0);
        }

        $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('#buy_max_amount').keyup(function(){

        var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val().replace(",", "")));
        if(isNaN(buy_coin_price.toString())){
            buy_coin_price = new Decimal(0);
        }else{
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
        }

        var buy_max_amount = parseFloat($(this).val().replace(",", ""));
        var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

        if(isNaN(buy_cash_amt.toString())){
            buy_cash_amt = new Decimal(0);
        }

        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('#sell_max_amount').keyup(function(){

        var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val().replace(",", "")));
        if(isNaN(sell_coin_price.toString())){
            sell_coin_price = new Decimal(0);
        }else{
            trade_fee = sell_coin_price.mul(fee_percent);
            sell_coin_price = sell_coin_price.sub(trade_fee);
        }

        var sell_max_amount = parseFloat($(this).val().replace(",", ""));
        var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

        if(isNaN(sell_cash_amt.toString())){
            sell_cash_amt = new Decimal(0);
        }

        $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('.trans_wrap .center_con .buysell_table ul.buy_percent li button').click(function(){
        var percent = parseFloat(parseInt($(this).text().replace("%", ""))/100);
        var my_asset = parseFloat($('input[name="my_asset_cash"]').val());
        $('#buy_max_amount').val(my_cash_percent(my_asset, percent));

        var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val().replace(",", "")));
        trade_fee = buy_coin_price.mul(fee_percent);
        buy_coin_price = buy_coin_price.add(trade_fee);
        var buy_max_amount = parseFloat($('#buy_max_amount').val().replace(",", ""));

        var buy_cash_amt = buy_coin_price.mul(buy_max_amount);
        
        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('.trans_wrap .center_con .buysell_table ul.sell_percent li button').click(function(){
        var percent = parseFloat(parseInt($(this).text().replace("%", ""))/100);
        var my_asset = parseFloat($('input[name="my_asset_coin"]').val());
        $('#sell_max_amount').val(my_coin_percent(my_asset, percent));

        var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val().replace(",", "")));
        trade_fee = sell_coin_price.mul(fee_percent);
        sell_coin_price = sell_coin_price.sub(trade_fee);
        var sell_max_amount = parseFloat($('#sell_max_amount').val().replace(",", ""));

        var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

        $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());

    });

    $('#readyorder_wrap button').click(function(e){
        //var orderItem =  $(e);

        //var id = orderItem.data('id');
        //trade_cancel(id);
    });
});

function value_up_btn(value){
	var decimal_pow = 1/Math.pow(10,decimal_usd);
    return (parseFloat(value) + decimal_pow).toFixed(decimal_usd);
}

function value_down_btn(value){
	var decimal_pow = 1/Math.pow(10,decimal_usd);
    return (parseFloat(value) - decimal_pow).toFixed(decimal_usd);
}

function AddComma(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function comma(str) { 
    var parts=str.toString().split(".");
    return parts[0].replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,') + (parts[1] ? "." + parts[1] : ""); 
} 

function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}

function my_cash_percent(cash, percent){
    var coin_price = new Decimal($('#buy_coin_price').val());
    trade_fee = coin_price.mul(fee_percent);
    coin_price = coin_price.add(trade_fee);
    var cash2 = new Decimal(cash);
    var coin_a = cash2.mul(percent);
    var coin_amt = coin_a.div(coin_price);

    coin_amt = new Decimal(coin_amt).toDecimalPlaces(8, Decimal.ROUND_DOWN).toString();

    return coin_amt;
}

function test(){
    swal({
        text: 'Tlqkf',
        icon: "warning",
        button: __.message.ok,
    });
}

function my_coin_percent(coin, percent){
    var coin_price = new Decimal($('#sell_coin_price').val());
    trade_fee = coin_price.mul(fee_percent);
    coin_price = coin_price.sub(trade_fee);
    var coin2 = new Decimal(coin);
    var coin_amt = coin2.mul(percent);

    coin_amt = new Decimal(coin_amt).toDecimalPlaces(8, Decimal.ROUND_DOWN).toString();

    return coin_amt;
}



function buysell_coin_data(order_type, coin_currency,symbol){

	if(ajax_run == 'n'){
		ajax_run = 'y';
		if(order_type == "buy"){
			var btc_price = onlyNumber_price($("#buy_coin_price").val());
			var max_amount = onlyNumber_amount($("#buy_max_amount").val());
            var currency_balance = $('input[name="my_asset_cash"]').val().replace(",","");

            if(btc_price === "" || parseFloat(btc_price) == 0 || max_amount === "" || parseFloat(max_amount) == 0) {
                swal({
                    text: __.market.nonono,
                    icon: "error",
                    button: __.message.ok,
                });
                ajax_run = 'n';
                return;
            }
			
			if(parseFloat(currency_balance) < parseFloat(btc_price) * parseFloat(max_amount)){
				alertify.error(__.message.less_than_buy);
				ajax_run = 'n';
			}else{
					
				$.ajax({
					url : "/buysell_coin_data_new",
					type : "POST",
					data: {_token: CSRF_TOKEN, symbol: symbol, btc_price: btc_price, max_amount: max_amount, order_type: order_type, coin_currency : coin_currency},
            		dataType: 'JSON',
					success : function(data) {
						if (data.data == 1) {
                            alertify.buy(__.message.success_purchase);
                            console.log(sid);
                            if(data.trade_result.response_buy.uid == sid){
                                alertify.success(data.trade_result.response_buy.amount + data.trade_result.response_buy.cointype + __.message.success_buy);
                            }

                            if(data.trade_result.response_sell.uid == sid){
                                alertify.success(data.trade_result.response_sell.amount + data.trade_result.response_sell.cointype + __.message.success_sell);
                            }
							refresh_my_data();
							add_my_readyorder_data();
							add_my_history_data();
						} else if (data.data == 2) {
							alertify.error(__.message.incorrect_quantity);
						} else if (data.data  == 4) {
							alertify.error(__.message.less_than_buy);
						} else if (data.data  == 5) {
							alertify.error(__.message.not_register_more_than_20_sell);
						} else if (data.data  == 10) {
							alertify.error('계정 정지 상태! 비정상적 경로 접근!');
						}  else {
							alertify.error(__.message.less_than_buy);
						}
						ajax_run = 'n';
					}
				});
			}
	
		}else if(order_type == "sell"){
			var btc_price = onlyNumber_price($("#sell_coin_price").val());
			var max_amount = onlyNumber_amount($("#sell_max_amount").val());
			var balance = parseFloat($('input[name="my_asset_coin"]').val().replace(",","")); // 현재 잔액
            
            if(btc_price === "" || parseFloat(btc_price) == 0 || max_amount === "" || parseFloat(max_amount) == 0) {
                swal({
                    text: __.market.nonono,
                    icon: "error",
                    button: __.message.ok,
                });
                ajax_run = 'n';
                return;
            }

			if(balance < parseFloat(max_amount)){
				alertify.error(__.message.less_than_sell);
				ajax_run = 'n';
			}else{
				
				$.ajax({
					url : "/buysell_coin_data_new",
					type : "POST",
					data: {_token: CSRF_TOKEN, symbol: symbol, btc_price: btc_price, max_amount: max_amount, order_type: order_type, coin_currency : coin_currency},
            		dataType: 'JSON',
					success : function(data) {
						if (data.data  == 1) {
                            alertify.sell(__.message.success_sell);
                            console.log(sid);
                            if(data.trade_result.response_buy.uid == sid){
                                alertify.success(data.trade_result.response_buy.amount + data.trade_result.response_buy.cointype +__.message.success_buy);
                            }

                            if(data.trade_result.response_sell.uid == sid){
                                alertify.success(data.trade_result.response_sell.amount + data.trade_result.response_sell.cointype + __.message.success_sell);
                            }

							refresh_my_data();
							add_my_readyorder_data();
							add_my_history_data();
						} else if (data.data  == 2) {
							alertify.error(__.message.incorrect_quantity);
						} else if (data.data  == 4) {
							alertify.error(__.message.not_register_more_than_20_buy);
						} else if (data.data  == 5) {
							alertify.error(__.message.not_register_more_than_20_sell);
						} else if (data.data  == 10) {
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

function sess_id(){
    $.ajax({
        url : "/sess_id_new",
        type : "POST",
        data: {_token: CSRF_TOKEN},
        dataType: 'JSON',
        success : function(data) {
            sid = data.uid;
        }
    });
}


function refresh_my_data(){
	var symbol_text;
    if(login_yn){
        $.ajax({
            url : "/refresh_user_data_new",
            type : "POST",
            data: {_token: CSRF_TOKEN, cointype: cointype, currency: currency},
            dataType: 'JSON',
            success : function(data) {
            	if(currency == 'usd' || currency == 'USD'){
            		symbol_text = 'USDC';
            	}else{
            		symbol_text = currency.toUpperCase();
            	}
                $('input[name="my_asset_cash"]').val(data.currency_balance);
                $('input[name="my_asset_coin"]').val(data.coin_balance); // 현재 잔액
                $("#use_balance_buy").html(symbol_text + __.message.remain_amt + " : " + data.currency_balance_front + " " + symbol_text);
                $("#use_balance_sell").html(cointype.toUpperCase() + __.message.remain_amt + " : " + data.coin_balance_front + " " + cointype.toUpperCase());
            }
        });
    }
}


function add_my_readyorder_data(){
    if(login_yn){
        //// JSON Call 시작
        var str = '';
        var m_str = '';
        var m_str2 = '';
        $.ajax({
            url : "/refresh_user_readyorder_new",
            type : "POST",
            data: {_token: CSRF_TOKEN, cointype: cointype, currency: currency},
            dataType: 'JSON',
            success : function(data) {
                var orders = data.map(function(item) {
                    return '<tr>' +
                        '<td><span>' + dateSet(item.local_time) + '</span></td>' +
                        '<td><span>' + item.coinname + '</span></td>' +
                        '<td><span>' + item.type_name + '</span></td>' +
                        '<td><span>' + item.ads_price + '</span></td>' +
                        '<td><span>' + item.ads_amount + '</span></td>' +
                        '<td><span>' + item.ads_percent + '%</span></td>' +
                        '<td><span>' + item.ads_total_amount + '</span></td>' +
                        '<td>' +
                        '<p class="status_type">' + item.lang_status + '</p>' +
                        (item.status === 'OnProgress' 
                            ? '<button type="button" id="btc_cancel_request_' + item.id + '" class="btc_cancel_request" data-id="' + item.id + '" onclick="trade_cancel(' + item.id + ')">' + __.message.trade_cancel + '</button>'
                            : ''
                        ) +
                        '</td>' +
                        '</tr>';
                }).join('');

                if(orders === ""){
                    orders = '<tr><td class="non_data" colspan="8" rowspan="5">' + __.message.today_trade_not_readyorder + '</td></tr>';
                }

                $("#ready_order_queue").empty().append(orders);
                
            }
        });
    }
}

function add_my_history_data(){
    if(login_yn){
        //// JSON Call 시작
        var str = '';
        $.ajax({
            url : "/refresh_user_history_new",
            type : "POST",
            data: {_token: CSRF_TOKEN, cointype: cointype, currency: currency},
            dataType: 'JSON',
            success : function(data) {
                $("#ready_history_queue").empty();
                if(data.length > 0){
                    $.each(data, function() {
                        str = "<tr>";
                        str += "<td><span>"+ dateSet(this.trade_date) +"</span></td>";
                        str += "<td><span>"+ this.coinname +"</span></td>";
                        str += "<td><span>"+ this.type_name +"</span></td>";
                        str += "<td><span>"+ this.trade_price +" " + currency.toUpperCase() + "</span></td>";
                        str += "<td><span>"+ this.trade_amount +"</span></td>";
                        str += "<td><span>"+ this.trade_total_amount +" " + currency.toUpperCase() + "</span></td>";
                        str += "</tr>";
                        
                        $("#ready_history_queue").append(str);
                    });
                }else{
                    str = '<tr><td class="non_data" colspan="8" rowspan="5">'+__.message.today_trade_not_history+'</td></tr>';
                    $("#ready_history_queue").append(str);
                }
            }
        });
    }
}

function trade_cancel(id){

    swal({
        text: __.message.real_trade_cancel_confirm,
        icon: "warning",
        buttons: {
            yes: {
                text: __.message.yes,
                value: true,
            },
            no: {
                text: __.message.no,
                value: false,
            },
        },
    })
    .then((value) => {
        if(value){
            $.ajax({
                url : "/trade_cancel_new",
                type : "POST",
                data: {_token: CSRF_TOKEN, id: id},
                dataType: 'JSON',
                success : function(data) {
                    alertify.success(data.message);
                    add_my_readyorder_data();
                    refresh_my_data();
                }
            });
        }
    });
}

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function onlyNumber_amount(price) {
	if(price.indexOf('.') != -1){
		var parts=price.toString().split(".");
		if(parts[1] == ''){
			return parts[0].replace(/[^0-9]/g,"") + ".";
		}else{
			if(parts[1].length > 8){
				parts[1] = parts[1].substr(0,8);
			}
			return parts[0].replace(/[^0-9]/g,"") + (parts[1].replace(/[^0-9]/g,"") ? "." + parts[1].replace(/[^0-9]/g,"") : "");
		}
	}else{
		return price.replace(/[^0-9]/g,"");
	}
}

function onlyNumber_price(price) {
	if(price.indexOf('.') != -1){
		var parts=price.toString().split(".");
		if(parts[1] == ''){
			return parts[0].replace(/[^0-9]/g,"") + ".";
		}else{
			if(parts[1].length > decimal_usd){
				parts[1] = parts[1].substr(0,decimal_usd);
			}
			return parts[0].replace(/[^0-9]/g,"") + (parts[1].replace(/[^0-9]/g,"") ? "." + parts[1].replace(/[^0-9]/g,"") : "");
		}
	}else{
		return price.replace(/[^0-9]/g,"");
	}
}

function formatNumber(num, fixed) {
    const parts = new Decimal(num)
      .toFixed(fixed)
      .split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return parts.join('.');
}

function formatNumberJW(num) {
    const parts = new Decimal(num).toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    //alert(parts.join('.'));
    return parts.join('.');
}