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
        var dealtype = $(this).data('dealtype');
        var inp_val = $('#'+dealtype+'_coin_price').val();
        var id = $('#'+dealtype+'_coin_price').attr('id').split('_');
        var price = value_up_btn(inp_val);

        $('#'+dealtype+'_coin_price').val(price);

        if(id[0] == 'buy'){
            var buy_coin_price  =  new Decimal(parseFloat($('#buy_coin_price').val()));
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
            var buy_max_amount  = new Decimal(parseFloat($('#buy_max_amount').val()));
            var buy_cash_amt = buy_coin_price.mul(buy_max_amount);
            $('#buy_fee').text(trade_fee);

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
            $('#sell_fee').text(trade_fee);

            if(isNaN(sell_cash_amt.toString())){
                sell_cash_amt = new Decimal(0.00);
            }


            $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        }

    });

    $('.down_btn').click(function(){
        var dealtype = $(this).data('dealtype');
        var inp_val = $('#'+dealtype+'_coin_price').val();
        var id = $('#'+dealtype+'_coin_price').attr('id').split('_');
        var price = value_down_btn(inp_val);

        $('#'+dealtype+'_coin_price').val(price);

        if(id[0] == 'buy'){
            var buy_coin_price  =  new Decimal(parseFloat($('#buy_coin_price').val()));
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
            var buy_max_amount  = new Decimal(parseFloat($('#buy_max_amount').val()));
            var buy_cash_amt = buy_coin_price.mul(buy_max_amount);
            $('#buy_fee').text(trade_fee);

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
            $('#sell_fee').text(trade_fee);

            if(isNaN(sell_cash_amt.toString())){
                sell_cash_amt = new Decimal(0.00);
            }

            $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        }
    });

    $('#sell_wait').click(function(e){  // ????????? ?????? ????????? ????????? ??????, ?????? ????????? ??????
        if(e.target === e.currentTarget) { return; }

        var orderItem =  $(e.target).closest('li');

        var price = new Decimal($(orderItem).data('price')).toFixed();
        var amt = new Decimal($(orderItem).data('amt')).toFixed();

        $('.buysell_price_inp').val(price);
        $('#buy_max_amount').val(amt);
        $('#sell_max_amount').val(0);

        var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val()));
        trade_fee = buy_coin_price.mul(fee_percent);
        buy_coin_price = buy_coin_price.add(trade_fee);
        var buy_cash_amt = buy_coin_price.mul(parseFloat($('#buy_max_amount').val()));
        $('#buy_fee').text(trade_fee);

        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        $('#sell_cash_amt').text('');
    });

    $('#buy_wait').click(function(e){  // ????????? ?????? ????????? ????????? ??????, ?????? ????????? ??????
        if(e.target === e.currentTarget) { return; }

        var orderItem =  $(e.target).closest('li');

        var price = new Decimal($(orderItem).data('price')).toFixed();
        var amt = new Decimal($(orderItem).data('amt')).toFixed();

        var max_decimal = $('#sell_coin_price').data('decimal') + 8;

        $('.buysell_price_inp').val(price);
        $('#sell_max_amount').val(amt);
        $('#buy_max_amount').val('');

        var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val()));
        trade_fee = sell_coin_price.mul(fee_percent);
        sell_coin_price = sell_coin_price.sub(trade_fee);
        var sell_cash_amt = sell_coin_price.mul(parseFloat($('#sell_max_amount').val()));
        $('#sell_fee').text(trade_fee);


        $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
        $('#buy_cash_amt').text(0);
    });

    $('#buy_coin_price').keyup(function(){

        var buy_coin_price = new Decimal(parseFloat($(this).val().replace(/\,/g, "")));
        if(isNaN(buy_coin_price.toString())){
            buy_coin_price = new Decimal(0);
        }else{
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
            $('#buy_fee').text(trade_fee);
        }
        var buy_max_amount = parseFloat($('#buy_max_amount').val().replace(/\,/g, ""));
        var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

        if(isNaN(buy_cash_amt.toString())){
            buy_cash_amt = new Decimal(0);
        }

        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString().toString());
    });

    $('#sell_coin_price').keyup(function(){

        var sell_coin_price = new Decimal(parseFloat($(this).val().replace(/\,/g, "")));
        if(isNaN(sell_coin_price.toString())){
            sell_coin_price = new Decimal(0);
        }else{
            trade_fee = sell_coin_price.mul(fee_percent);
            sell_coin_price = sell_coin_price.sub(trade_fee);
            $('#sell_fee').text(trade_fee);
        }
        var sell_max_amount = parseFloat($('#sell_max_amount').val().replace(/\,/g, ""));
        var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

        if(isNaN(sell_cash_amt.toString())){
            sell_cash_amt = new Decimal(0);
        }

        $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('#buy_max_amount').keyup(function(){

        var buy_coin_price = new Decimal(parseFloat($('#buy_coin_price').val().replace(/\,/g, "")));
        if(isNaN(buy_coin_price.toString())){
            buy_coin_price = new Decimal(0);
        }else{
            trade_fee = buy_coin_price.mul(fee_percent);
            buy_coin_price = buy_coin_price.add(trade_fee);
            $('#buy_fee').text(trade_fee);
        }

        var buy_max_amount = parseFloat($(this).val().replace(/\,/g, ""));
        var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

        if(isNaN(buy_cash_amt.toString())){
            buy_cash_amt = new Decimal(0);
        }

        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('#sell_max_amount').keyup(function(){

        var sell_coin_price = new Decimal(parseFloat($('#sell_coin_price').val().replace(/\,/g, "")));
        if(isNaN(sell_coin_price.toString())){
            sell_coin_price = new Decimal(0);
        }else{
            trade_fee = sell_coin_price.mul(fee_percent);
            sell_coin_price = sell_coin_price.sub(trade_fee);
            $('#sell_fee').text(trade_fee);
        }

        var sell_max_amount = parseFloat($(this).val().replace(/\,/g, ""));
        var sell_cash_amt = sell_coin_price.mul(sell_max_amount);

        if(isNaN(sell_cash_amt.toString())){
            sell_cash_amt = new Decimal(0);
        }

        $('#sell_cash_amt').text(new Decimal(sell_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('.buysell_table ul.buy_percent li button').click(function(){
        var percent = new Decimal($(this).text().replace("%", "").trim()).div(100);
        var my_asset = new Decimal($('input[name="my_asset_cash"]').val().trim());
        $('#buy_max_amount').val(my_cash_percent(my_asset, percent));

        var buy_coin_price = new Decimal($('#buy_coin_price').val().replace(/\,/g, "").trim());
        trade_fee = buy_coin_price.mul(fee_percent);
        buy_coin_price = buy_coin_price.add(trade_fee);
        var buy_max_amount = $('#buy_max_amount').val().replace(/\,/g, "").trim();
        $('#buy_fee').text(trade_fee);

        var buy_cash_amt = buy_coin_price.mul(buy_max_amount);

        $('#buy_cash_amt').text(new Decimal(buy_cash_amt).toDecimalPlaces(decimal_usd, Decimal.ROUND_DOWN).toString());
    });

    $('.buysell_table ul.sell_percent li button').click(function(){
        var percent = new Decimal($(this).text().replace("%", "").trim()).div(100);
        var my_asset = new Decimal($('input[name="my_asset_coin"]').val().trim());
        $('#sell_max_amount').val(my_coin_percent(my_asset, percent));

        var sell_coin_price = new Decimal($('#sell_coin_price').val().replace(/\,/g, "").trim());
        trade_fee = sell_coin_price.mul(fee_percent);
        sell_coin_price = sell_coin_price.sub(trade_fee);
        var sell_max_amount = $('#sell_max_amount').val().replace(/\,/g, "").trim();
        $('#sell_fee').text(trade_fee);

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
    $('#buy_fee').text(trade_fee);

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
    $('#sell_fee').text(trade_fee);

    coin_amt = new Decimal(coin_amt).toDecimalPlaces(8, Decimal.ROUND_DOWN).toString();

    return coin_amt;
}



function buysell_coin_data(order_type, coin_currency,symbol){

	if(ajax_run == 'n'){
		ajax_run = 'y';
		if(order_type == "buy"){
			var btc_price = onlyNumber_price($("#buy_coin_price").val());
            var max_amount = onlyNumber_amount($("#buy_max_amount").val());
            var currency_balance = $('input[name="my_asset_cash"]').val().replace(/\,/g, "");

            if(btc_price === "" || parseFloat(btc_price) == 0 || max_amount === "" || parseFloat(max_amount) == 0) {
                swal({
                    text: '???????????? ????????? ?????????????????????',
                    icon: "error",
                    button: __.message.ok,
                });
                ajax_run = 'n';
                return;
            }

            var currencyBalance = Decimal(currency_balance);
            var btcPrice = Decimal(btc_price);
            var maxAmount = Decimal(max_amount);

			if(currencyBalance.lessThan(btcPrice.mul(maxAmount))){
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

                            // event ????????????                            
                            $('input[name="event_status"]').val(data.event_status);

                            console.log(data.event_info.status);

                            if(data.event_info.status) {
                                $('#n_event_modal_m').css('display', 'block');
                                $('#n_event_modal_notify_m').html('');
                                $('#n_event_modal_notify_m').html(data.event_info.number+'?????? ???????????? ?????? ???????????????.');
                                $('#n_event_modal_prize_m').html('');
                                $('#n_event_modal_prize_m').html('?????? : ' + data.event_info.productName);
                                $('.event_ul').append('<li>??? '+data.event_info.number+'?????? ?????? ????????? ????????? : '+data.event_info.nickName+'</li>');
                            }

                            alertify.buy(__.message.success_purchase);

                            if(data.trade_result.response_buy.uid == sid){
                                alertify.success(data.trade_result.response_buy.amount + data.trade_result.response_buy.cointype + __.message.success_buy);
                            }

                            if(data.trade_result.response_sell.uid == sid){
                                alertify.success(data.trade_result.response_sell.amount + data.trade_result.response_sell.cointype + __.message.success_to_sell);
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
						} else if (data.data  == 6) {
							alertify.error('SPORTS COIN??? ?????? 20??? ????????? ???????????? ??? ????????????.');
                        } else if (data.data  == 10) {
							alertify.error('?????? ?????? ??????! ???????????? ?????? ??????!');
						} else {
							alertify.error(__.message.less_than_buy);
						}
						ajax_run = 'n';
					}
				});
			}

		}else if(order_type == "sell"){
			var btc_price = onlyNumber_price($("#sell_coin_price").val());
            var max_amount = onlyNumber_amount($("#sell_max_amount").val());
            var balance_value = $('input[name="my_asset_coin"]').val().replace(/\,/g, ""); // ?????? ??????

            if(btc_price === "" || parseFloat(btc_price) == 0 || max_amount === "" || parseFloat(max_amount) == 0) {
                swal({
                    text: '???????????? ????????? ?????????????????????',
                    icon: "error",
                    button: __.message.ok,
                });
                ajax_run = 'n';
                return;
            }

            var balance = Decimal(balance_value);
            var maxAmount = Decimal(max_amount);

			if(balance.lessThan(maxAmount)){
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

                            if(data.trade_result.response_buy.uid == sid){
                                alertify.success(data.trade_result.response_buy.amount + data.trade_result.response_buy.cointype + __.message.success_buy);
                            }

                            if(data.trade_result.response_sell.uid == sid){
                                alertify.success(data.trade_result.response_sell.amount + data.trade_result.response_sell.cointype + __.message.success_to_sell);
                            }

							refresh_my_data();
							add_my_readyorder_data();
							add_my_history_data();
						} else if (data.data  == 2) {
							alertify.error(__.message.incorrect_quantity);
						} else if (data.data  == 4) {
							alertify.error('???????????? ????????? ?????? ?????? ?????? ?????? 20% ????????? ?????? ????????? ??? ????????????.');
						} else if (data.data  == 5) {
							alertify.error('???????????? ????????? ?????? ?????? ?????? ?????? 20% ???????????? ?????? ????????? ??? ????????????.');
						} else if (data.data  == 6) {
							alertify.error('SPORTS COIN??? ?????? 20??? ????????? ???????????? ??? ????????????.');
                        } else if (data.data  == 10) {
							alertify.error('?????? ?????? ??????! ???????????? ?????? ??????!');
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
                $('input[name="my_asset_coin"]').val(data.coin_balance); // ?????? ??????
                $("#use_balance_buy").html(symbol_text +__.message.remain_amt+" : <u>" + data.currency_balance_front + " " + symbol_text + "</u>");
                $("#use_balance_sell").html(cointype.toUpperCase() + __.message.remain_amt + " : <u>" + data.coin_balance_front + " " + cointype.toUpperCase() + "</u>");
            }
        });
    }
}


function add_my_readyorder_data(){
    if(login_yn){
        //// JSON Call ??????
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
                    return '<li class="con '+ item.type +'"><p class="info _date">'+
                    '<span>'+ dateSet(item.local_time) + '</span>' +
                    '<span class="float-right type">'+ item.type_name + '</span></p>' +
                    '<p class="info _coin">'+ item.coinname + '</p>' +
                    '<p class="info _amt"><label>'+ __.message.amt + '</label>' +
                    '<span class="point_clr_1">'+ item.ads_amount + '</span></p>' +
                    '<p class="info"><label>'+ __.message.price +'</label>' +
                    '<span>'+ item.ads_price +'</span>' +
                    '<span class="currency"> '+currency+'</span></p>' +
                    '<p class="info"><label>'+__.message.total_price+'</label>' +
                    '<span>'+item.ads_total_amount+'</span>' +
                    '<span class="currency"> '+currency+'</span></p>' +
                    '<p class="info"><label>'+__.message.percent+'</label>' +
                    '<span>'+item.ads_percent+'</span>' +
                    '<span>%</span></p>' +
                    '<p class="info"><label>'+__.message.status+'</label>' +

                    (item.status == 'OnProgress'
                        ? '<button type="button" id="btc_cancel_request_'+item.id+'" class="btc_cancel_request mr-1" data-id="'+item.id+'" onclick="trade_cancel('+item.id+')">'+__.message.trade_cancel+'</button>'
                        : ''
                    ) +
                    '<span class="status_type">'+item.lang_status+'</span></p></li>';

                }).join('');

                if(orders === ""){
                    orders = '<li class="non_data"><img src="/images/icon_notice.svg" alt="" class="btn_notice">'+__.message.today_trade_not_readyorder+'</li>';
                }

                $("#ready_order_queue").empty().append(orders);

            }
        });
    }
}

function add_my_history_data(){
    if(login_yn){
        //// JSON Call ??????
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
                        str = '<li class="con '+this.type+'">';
                        str += '<p class="info _date"><span>'+ dateSet(this.trade_date) +'</span>';
                        str += '<span class="float-right type">'+ this.type_name +'</span></p>';
                        str += '<p class="info _coin">'+ this.coinname +'</p>';
                        str += '<p class="info _amt"><label>'+ __.message.amt +'</label>';
                        str += '<span>'+ this.trade_amount +'</span></p>';
                        str += '<p class="info"><label>'+ __.message.price +'</label>';
                        str += '<span>'+ this.trade_price +'</span>';
                        str += '<span class="currency"> '+currency+'</span></p>';
                        str += '<p class="info"><label>'+ __.message.total_price +'</label>';
                        str += '<span>'+ this.trade_total_amount +'</span>';
                        str += '<span class="currency"> '+currency+'</span></p>';
                        str += '</li>';

                        $("#ready_history_queue").append(str);
                    });
                }else{
                    str = '<li class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>'+__.message.today_trade_not_history+'</li>';
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
            if(ajax_run == 'n'){
                ajax_run = 'y';
                $.ajax({
                    url : "/trade_cancel_new",
                    type : "POST",
                    data: {_token: CSRF_TOKEN, id: id},
                    dataType: 'JSON',
                    success : function(data) {
                        alertify.success(data.message);
                        add_my_readyorder_data();
                        refresh_my_data();
                        ajax_run = 'n';
                    }
                });
            }
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

var isRefresh = false;
function refresh_my_asset(){
    if(login_yn){
        if(isRefresh) {
            return;
        }
        isRefresh = true;

        $.ajax({
            url : "/refresh_user_asset",
            type : "POST",
            data: {_token: CSRF_TOKEN},
            dataType: 'JSON',
            success : function(data) {
                $('#total_buying').text(formatNumber(data.total_buying,0));
                $('#total_eval_revenue').text(formatNumber(data.total_eval_revenue,0));
                $('#total_balance_krw').text(formatNumber(data.coin_balance_krw,0));
                $('#total_holding').text(formatNumber(data.total_holding,0));
                $('#total_asset').text(formatNumber(data.total_asset,0));
                $('#total_eval_percent').text(formatNumber(data.total_eval_percent,2));

                $.each(data.result,function(index,item){
                    if(item.balance > 0){
                        $('[data-symbol="'+index.toUpperCase()+'"]').css("display","block");
                    }else{
                        $('[data-symbol="'+index.toUpperCase()+'"]').css("display","none");
                    }
                    $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_balance]').text(formatNumber(item.balance,8));
                    $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_avg]').text(formatNumber(item.avg,0));
                    $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_buying]').text(formatNumber(item.buying,0));
                    $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_eval]').text(formatNumber(item.eval,0));
                    if(item.eval_percent < 0){
                        $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_percent]').text(formatNumber(item.eval_percent,2));
                        $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_percent]').removeClass('red');
                        $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_percent]').addClass('blue');
                    }else{
                        $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_percent]').text("+"+formatNumber(item.eval_percent,2));
                        $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_percent]').removeClass('blue');
                        $('[data-symbol="'+index.toUpperCase()+'"]').find('[name=asset_percent]').addClass('red');
                    }
                });
            },
            complete: function() {
                isRefresh = false;
            }
        });
    }
}