$('.third_history_con .third_history_hd select').change(function(){
	
	var sel_wd_kind = $(this).val();
	$("#transaction_list tr").css('display','none');
	if(sel_wd_kind == 0){
		$("#transaction_list tr.out").css('display','');
	}else if(sel_wd_kind == 1){
		$("#transaction_list tr.in").css('display','');
	}else{
		$("#transaction_list tr").css('display','');
	}
});

$('#my_coin').click(function(){
	if($(this).prop("checked")){
		$("#coin_list_table > tbody > tr").hide();
		var k = "poss";
		var temp = $("#coin_list_table > tbody > tr > td:nth-child(3):contains('poss')");

		temp.parent().show();
	}else{
		$("#coin_list_table > tbody > tr").show();
	}
});

/*입금주소-출금신청-입출금내역 탭메뉴*/
$('.transac_con_wrap .toggle_con').each(function(index){
	//순서값 배부
	$(this).attr('data-index',index);
});
$('.transac_header ul li').each(function(index){
	
	//순서값 배부
	$(this).attr('data-index',index);
	
}).click(function(){
	
	//누른 메뉴 꾸밈효과 주고, 아닌 탭은 꾸밈효과 지운다
	$(this).addClass('active');
	$('.transac_header ul li').not(this).removeClass('active');
	
	var i = $(this).data('index');
	//클릭한 순서값에 따라 매치되는 박스 보여주고 매치되지 않으면 숨긴다
	$('.transac_con_wrap .toggle_con[data-index='+i+']').show();
	$('.transac_con_wrap .toggle_con[data-index!='+i+']').hide();
});
/*//입금주소-출금신청-입출금내역 탭메뉴*/

$('.trans_inner_2 .trans_right .ta_right_tit i').click(function(){
	
	iconRotate();
	
	setTimeout(function(){
		iconRotateStop();
	},3000);
	
});

if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
        $('.transwallet_btn').on('click', function(e){
            select_all_and_copy("deposit_address");
        });
    } else {
        var clipboard = new ClipboardJS('.transwallet_btn');
        clipboard.on('success', function(e) {
			swal({
				text: __.message.success_copy_addr,
				icon: "success",
				buttons: {
					yes: {
						text: __.message.yes,
						value: true,
					},
				},
			})
			//alertify.success(__.message.success_copy_addr);
            e.clearSelection();
        });
 
        clipboard.on('error', function(e) {
			swal({
				text: __.message.fail_copy_addr,
				icon: "warning",
				buttons: {
					yes: {
						text: __.message.yes,
						value: true,
					},
				},
			})
	});
}   


function iconRotate(){
	var rotateIcon = $('.trans_inner_2 .trans_right .ta_right_tit i');
	rotateIcon.addClass('active');
}

function iconRotateStop(){
	var rotateIcon = $('.trans_inner_2 .trans_right .ta_right_tit i');
	rotateIcon.removeClass('active');
}


// 복사 소스 IOS, ANDROID, WINDOW, INTERNET 다 가능
function select_all_and_copy(el) {
	// http://www.seabreezecomputers.com/tips/copy2clipboard.htm
	
	// ++ added line for table
	var el = document.getElementById(el);
	// Copy textarea, pre, div, etc.
	if (document.body.createTextRange) {// IE 
		var textRange = document.body.createTextRange();
		textRange.moveToElementText(el);
		textRange.select();
		textRange.execCommand("Copy");
		swal({
			text: __.message.success_copy_addr,
			icon: "success",
			buttons: {
				yes: {
					text: __.message.yes,
					value: true,
				},
			},
		})   
		//alertify.success(__.message.success_copy_addr);
	} else if (window.getSelection && document.createRange) {// non-IE
		var editable = el.contentEditable; // Record contentEditable status of element
		var readOnly = el.readOnly; // Record readOnly status of element
		el.contentEditable = true; // iOS will only select text on non-form elements if contentEditable = true;
		el.readOnly = false; // iOS will not select in a read only form element
		var range = document.createRange();
		range.selectNodeContents(el);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range); // Does not work for Firefox if a textarea or input
		if (el.nodeName == "TEXTAREA" || el.nodeName == "INPUT") 
			el.select(); // Firefox will only select a form element with select()
		if (el.setSelectionRange && navigator.userAgent.match(/ipad|ipod|iphone/i))
			el.setSelectionRange(0, 999999); // iOS only selects "form" elements with SelectionRange
		el.contentEditable = editable; // Restore previous contentEditable status
		el.readOnly = readOnly; // Restore previous readOnly status 
		if (document.queryCommandSupported("copy"))
		{
			var successful = document.execCommand('copy');  
			if (successful) {
				swal({
					text: __.message.success_copy_addr,
					icon: "success",
					buttons: {
						yes: {
							text: __.message.yes,
							value: true,
						},
					},
				})
			}//alertify.success(__.message.success_copy_addr);
			else{
				swal({
					text: __.message.fail_copy_addr,
					icon: "warning",
					buttons: {
						yes: {
							text: __.message.yes,
							value: true,
						},
					},
				})
			}
		}
		else
		{
			if (!navigator.userAgent.match(/ipad|ipod|iphone|android|silk/i))
			swal({
				text: __.message.fail_copy_addr,
				icon: "warning",
				buttons: {
					yes: {
						text: __.message.yes,
						value: true,
					},
				},
			})
		}
	}
}
// 코인변환 함수
function exchangeCashCoin(before_cointype, after_cointype){
	if(before_cointype == 'USD'){
		var before_text_cointype = 'USDC';
		var after_text_cointype = after_cointype;
	}else{
		var before_text_cointype = before_cointype;
		var after_text_cointype = 'USDC';
	}
	swal({
		text: '정말 '+before_text_cointype+'를 '+after_text_cointype+'로 변환하시겠습니까?',
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
		dangerMode: true,
	})
	.then((value) => {
		if (value) {
			var before_text_cointype;
			var after_text_cointype;
			var decimal_before;
			var decimal_after;
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url : "/exchangeCashCoin",
				type : "POST",
				data : {_token : CSRF_TOKEN,before_cointype : before_cointype, after_cointype : after_cointype},
				dataType : "JSON"
			}).done(function(data) {
				if(data.status == 'success'){
					if(before_cointype == 'USD'){
						before_text_cointype = 'USDC';
						decimal_before = 2;
					}else{
						before_text_cointype = before_cointype;
						decimal_before = 0;
					}
					if(after_cointype == 'USD'){
						after_text_cointype = 'USDC';
						decimal_after = 2;
					}else{
						after_text_cointype = after_cointype;
						decimal_after = 0;
					}
					all_refresh();
			
					swal({
						title: __.message.change_complete,
						text: __.message.trade_wait_money + data.before_balance.toFixed(decimal_before) + " " + before_text_cointype + __.message.change_fee + data.swap_fee + "% ( " + data.fee.toFixed(decimal_after) + " " + after_text_cointype + " )" + __.message.change_money + data.after_balance.toFixed(decimal_after) + " " + after_text_cointype,
						icon: "success",
						button: __.message.ok,
					});
				}else{
					swal({
						title: __.message.change_fail,
						text: __.message.change_fail_balance,
						icon: "warning",
						button: __.message.ok,
					});
				}
			}).fail(function(){
				console.log("error");
			});
		}
	});
}
//코인선택했을때 코인정보 표출
function select_coin(symbol){
	$('.posi_wrap').css('display','table');
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/select_coin",
		type : "POST",
		data : {_token : CSRF_TOKEN, symbol : symbol},
		dataType : "JSON"
	}).done(function(data) {
		
		//사용할 코인 정보
		$("#this_symbol_hidden").val(data.symbol);
		$("#this_symbol_text_hidden").val(data.symbol_text);
		
		// 코인 보유정보
		$("#this_symbol").html(data.coinname + " " + __.message.deposit_withdraw);
		$("#this_balance_total").html(' <b>' + numberWithCommas(data.total.toFixed(data.coin_decimal)) + '</b> <b class="currency">' + data.symbol_text + '</b> ');
		$("#this_balance_eval").html(' <b>' + numberWithCommas(data.eval.toFixed(data.cash_decimal)) + '</b> <u class="currency">KRW</u> ');
		$("#this_balance_pending").html(' <b>' + numberWithCommas(data.pending.toFixed(data.coin_decimal)) + '</b> <u class="currency">' + data.symbol_text + '</u> ');
		$("#this_balance_available").html(' <b>' + numberWithCommas(data.available.toFixed(data.coin_decimal)) + '</b> <u class="currency">' + data.symbol_text + '</u> ');
		
		if(symbol == 'USD'){
			$('.coin_table').addClass('hide');
		}else if(symbol == 'KRW'){
			$('.coin_table').addClass('hide');
			$('.cash_table').removeClass('hide');
			
			$("#cash_withdraw_limit_amt").html(' <b>' + numberWithCommas(data.withdraw_limit_amt) + '</b> <b class="currency">' + data.symbol_text + '</b> ');
			$("#cash_withdraw_amt_label").text(__.message.withdraw_amt.replace("*symbol_text*", data.symbol_text));
		}else{
			$('.coin_table').removeClass('hide');
			$('.cash_table').addClass('hide');
			// 입금정보 삽입
			$("#first_deposit_info_ment").text(__.message.ments1.replace("*coinname*", data.coinname));
			$("#first_deposit_info").text(__.message.my_deposit_addr.replace("*coinname*", data.coinname));
			$("#deposit_coin_address_qrcode").html('<img id="qrcode" src="https://chart.googleapis.com/chart?chs=200x200&amp;cht=qr&amp;chl=' + data.address + '&amp;choe=UTF-8">');
			$("#deposit_coin_address").val(data.address);
			
			// 출금정보 삽입
			$("#withdraw_limit_amt").html(' <b>' + numberWithCommas(data.withdraw_limit_amt) + '</b> <b class="currency">' + data.symbol_text + '</b> ');	
			$("#withdraw_amt_label").text(__.message.withdraw_amt.replace("*symbol_text*", data.symbol_text));
			
			$('#withdraw_amt').val('');
			$('#withdraw_check_address').val('');
		}
		
		

		refresh_trans_wallet();
		refresh_withdraw_history();
		$('.posi_wrap').css('display','none');
	}).fail(function(){
		console.log("error");
	});
}

//새로코침 버튼 기능
function all_refresh(){
	$('.posi_wrap').css('display','table');
	var symbol = $("#this_symbol_hidden").val();
	refresh_trans_wallet();
	select_coin(symbol);
	refresh_withdraw_history();
}
// 출금 시 코인 주소 체크
function withdraw_check_address(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var symbol = $("#this_symbol_text_hidden").val();
	var address = $("#withdraw_check_address").val();
	if(address == '' || address == null){
		swal({
			text: __.message.please_input_addr,
			icon: "warning",
			button: __.message.ok,
		});
	}else{
		$.ajax({
			url : "/check_address",
			type : "POST",
			data : {_token : CSRF_TOKEN, symbol : symbol, address : address},
			dataType : "JSON"
		}).done(function(data) {
			if(data.result == 'valid'){
				swal({
					text: __.message.correct_addr,
					icon: "success",
					button: __.message.ok,
				});
			}else if(data.result == 'invalid'){
				swal({
					text: __.message.uncorrect_addr,
					icon: "error",
					button: __.message.ok,
				});
			}else{
				swal({
					text: __.message.error_network,
					icon: "warning",
					button: __.message.ok,
				});
			}
		}).fail(function(){
			console.log("error");
		});
	}
}

//출금가능한 코인 최대양
function withdraw_max_amt(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var symbol = $("#this_symbol_hidden").val();
	$.ajax({
		url : "/withdraw_max_amt",
		type : "POST",
		data : {_token : CSRF_TOKEN, symbol : symbol},
		dataType : "JSON"
	}).done(function(data) {
		$("#withdraw_amt").val(data.max_amount);
		$("#withdraw_send_fee").text(data.send_fee);
		$("#withdraw_total_amt").text(data.total_amount);
		
		$("#cash_withdraw_amt").val(data.max_amount);
		$("#cash_withdraw_send_fee").text(data.send_fee);
		$("#cash_withdraw_total_amt").text(data.total_amount);
	}).fail(function(){
		console.log("error");
	});
}

function withdraw_onkey_amt(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var symbol = $("#this_symbol_hidden").val();
	if(symbol == 'KRW'){
		var amt = $("#cash_withdraw_amt").val();
	}else{
		var amt = $("#withdraw_amt").val();
	}
	if(amt == '' || amt == null){
		amt = 0;
	}
	
	$.ajax({
		url : "/withdraw_onkey_amt",
		type : "POST",
		data : {_token : CSRF_TOKEN, symbol : symbol, amt : amt},
		dataType : "JSON"
	}).done(function(data) {
		console.log(data);
		$("#withdraw_amt").val(data.amount);
		$("#withdraw_send_fee").text(data.send_fee);
		$("#withdraw_total_amt").text(data.total_amount);
		
		
		$("#cash_withdraw_amt").val(data.amount);
		$("#cash_withdraw_send_fee").text(data.send_fee);
		$("#cash_withdraw_total_amt").text(data.total_amount);
		
		if(data.check_amt){
			swal({
				text: __.message.over_Asset,
				icon: "warning",
				button: __.message.ok,
			});
		}
	}).fail(function(){
		console.log("error");
	});
	
}

//코인 출금
function send_transaction(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var symbol = $("#this_symbol_hidden").val();
	var amt = $("#withdraw_amt").val();
	var address = $("#withdraw_check_address").val();
	
	if(symbol == 'USD'){
		swal({
			text: __.message.please_change_another_coin,
			icon: "warning",
			button: __.message.ok,
		});
	}else if(address == ''){
		swal({
			text: __.message.please_input_addr,
			icon: "warning",
			button: __.message.ok,
		});
	}else if(amt == '' || amt == 0){
		swal({
			text: __.message.please_input_out_amt,
			icon: "warning",
			button: __.message.ok,
		});
	}else{
		$.ajax({
			url : "/send_transaction",
			type : "POST",
			data : {_token : CSRF_TOKEN, symbol : symbol, amt : amt, address : address},
			dataType : "JSON"
		}).done(function(data) {
			if(data.state == 'check_address'){
				swal({
					text: __.message.please_check_addr,
					icon: "warning",
					button: __.message.ok,
				});
			}else if(data.state == 'over_balance'){
				swal({
					text: __.message.over_asset2,
					icon: "warning",
					button: __.message.ok,
				});
			}else if(data.state == 'under_fee'){
				swal({
					text: __.message.over_asset3,
					icon: "warning",
					button: __.message.ok,
				});
			}else if(data.state == 'success'){
				swal({
					title: __.message.withdraw_success,
					text: __.message.withdraw_wait_need_confirm,
					icon: "success",
					button: __.message.ok,
				});
				$('.transac_header ul li[data-index=2]').addClass('active');
				$('.transac_header ul li[data-index!=2]').removeClass('active');
				$('.transac_con_wrap .toggle_con[data-index=2]').show();
				$('.transac_con_wrap .toggle_con[data-index!=2]').hide();
				all_refresh();
			}else if(data.state == 'success_now'){
				swal({
					text: __.message.complete_withdraw,
					icon: "success",
					button: __.message.ok,
				});
				$('.transac_header ul li[data-index=2]').addClass('active');
				$('.transac_header ul li[data-index!=2]').removeClass('active');
				$('.transac_con_wrap .toggle_con[data-index=2]').show();
				$('.transac_con_wrap .toggle_con[data-index!=2]').hide();
				all_refresh();
			}
			
		}).fail(function(){
			console.log("error");
		});
	}
}

// 자기지갑 보유내역 새로고침
function refresh_trans_wallet(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var total_holding;
	var holding_percent;
	var holding_balance;
	var holding_convert;
	var refresh_decimal;
	$.ajax({
		url : "/refresh_trans_wallet",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		total_holding = data.total_holding;
		$("#holding_total_balance").text(numberWithCommas(total_holding.toFixed(0)));
		$.each(data.coin, function(){
			
			if(total_holding == 0){
				holding_percent = 0;
			}else{
				holding_percent = this.balance*this.price/total_holding*100;
			}
			if(this.api == 'krw'){
				refresh_decimal = 0;
				holding_balance = this.balance;
				holding_convert = this.balance * this.price_usd;
				$("#holding_convert_"+this.symbol).text(numberWithCommas(holding_convert.toFixed(this.decimal_usd)));
				$("#holding_balance_"+this.symbol).text(numberWithCommas(holding_balance.toFixed(refresh_decimal)));
				$("#holding_percent_"+this.symbol).text(holding_percent.toFixed(2)+"%");
			}else{
				refresh_decimal = 8;
				holding_balance = this.balance;
				holding_convert = this.balance * this.price;
				$("#holding_convert_"+this.symbol).text(numberWithCommas(holding_convert.toFixed(this.decimal_krw)));
				$("#holding_balance_"+this.symbol).text(numberWithCommas(holding_balance.toFixed(refresh_decimal)));
				$("#holding_percent_"+this.symbol).text(holding_percent.toFixed(2)+"%");
			}
		});
		
	}).fail(function(){
		console.log("error");
	});

}

function refresh_withdraw_history(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var symbol = $("#this_symbol_hidden").val();
	var str;
	var symbol_text;
	var status_text;
	var button_text;
	var date_at;
	$.ajax({
		url : "/refresh_withdraw_history",
		type : "POST",
		data : {_token : CSRF_TOKEN, symbol : symbol},
		dataType : "JSON"
	}).done(function(data) {
		$("#transaction_list").empty();
		$("#cash_list").empty();
		$.each(data.transaction_list, function(){
			if(this.cointype == 'USD' || this.cointype == 'usd'){
				symbol_text = 'USDC';
			}else if(this.cointype == 'KRW' || this.cointype == 'krw'){
				date_at = this.updated.split(' '); //날짜 분리
				symbol_text = this.cointype;
				button_text = '';
				if(this.status == 'confirm'){
					status_text = __.message.trans_complete;
				}else if(this.status == 'deposite_request'){
					status_text = __.message.deposite_request;
					button_text = "<button onclick = 'cash_cancel(" + this.id + ",0);'>" + __.message.trans_cancel + "</button>";
				}else if(this.status == 'deposite_reject'){
					status_text = __.message.deposite_reject;
				}else if(this.status == 'deposite_cancel'){
					status_text = __.message.deposite_cancel;
				}else if(this.status == 'withdraw_request'){
					status_text = __.message.withdraw_request;
					button_text = "<button onclick = 'cash_cancel(" + this.id + ",1);'>" + __.message.trans_cancel + "</button>";
				}else if(this.status == 'withdraw_reject'){
					status_text = __.message.withdraw_reject;
				}else if(this.status == 'withdraw_cancel'){
					status_text = __.message.withdraw_cancel;
				} 
				if(this.type == 'deposite'){
					str = '<tr class="in" data-kind="0">';
					str += '<td rowspan="2">'+__.message.deposit+'</td>';
				}else if(this.type == 'withdraw'){
					str = '<tr class="out" data-kind="1">';
					str += '<td rowspan="2">'+__.message.withdraw+'</td>';
				}
				str += '<td rowspan="2">';
				str += '<p>';
				str += '' + numberWithCommas(this.amount) + ' <span class="currency">' + symbol_text + '</span>';
				str += '</p></td>';
				
				str += '<td>' + status_text + button_text  + '</td>';
				str += '</tr>';
				if(this.type == 'deposite'){
					str += '<tr>';
				}else if(this.type == 'withdraw'){
					str += '<tr>';
				}
				str += '<td class="data"> ' + date_at[0] + '';
				str += '<br>';
				str += '' + date_at[1] + ' </td>';
				str += '</tr>';
				$("#cash_list").append(str);
			}else{
				date_at = this.updated.split(' '); //날짜 분리
				symbol_text = this.cointype;
				if(this.type == 'withdraw' || this.type == 'out'){
					if(this.status == 'withdraw_request'){
						status_text = __.message.request_withdraw;
					}else if(this.status == 'withdraw_request_confirm'){
						status_text = __.message.wait_withdraw;
					}else if(this.status == 'withdraw_reject'){
						status_text = __.message.reject_withdraw;
					}else{
						status_text = __.message.complete_withdraw;
					}
					
					str = '<tr class="out" data-kind="0">';
					str += '<td rowspan="2">'+__.message.withdraw+'</td>';
					str += '<td>';
					str += '<p>';
					str += '' + this.total_amt + ' <span class="currency">' + symbol_text + '</span>';
					str += '</p></td>';
					str += '<td>' + status_text + '</td>';
					str += '</tr>';
					str += '<tr class="out">';
					str += '<td>';
					str += '<p>';
					str += '' + this.receiver_address + '';
					str += '<br>';
					if(this.type == 'withdraw' && this.status != 'withdraw_request'){
						str += '(<a href="#" target="_blank">' + this.tx_id + '</a>)';
					}
					
					str += '</p></td>';
					str += '<td class="data"> ' + date_at[0] + '';
					str += '<br>';
					str += '' + date_at[1] + ' </td>';
					str += '</tr>';
				}else if(this.type == 'receive' || this.type == 'in'){
					str = '<tr class="in" data-kind="1">';
					str += '<td rowspan="2">'+__.message.deposit+'</td>';
					str += '<td>';
					str += '<p>';
					str += '' + this.total_amt + ' <span class="currency">' + symbol_text + '</span>';
					str += '</p></td>';
					str += '<td>'+__.message.complete_deposit+'</td>';
					str += '</tr>';
					str += '<tr class="in">';
					str += '<td>';
					str += '<p>';
					str += '' + this.receiver_address + '';
					str += '<br>';
					if(this.type == 'withdraw'){
						str += '(<a href="#" target="_blank">' + this.tx_id + '</a>)';
					}
					str += '</p></td>';
					str += '<td class="data"> ' + date_at[0] + '';
					str += '<br>';
					str += '' + date_at[1] + ' </td>';
					str += '</tr>';
				}
				$("#transaction_list").append(str);
			}
			
			
			
			
			$('.posi_wrap').css('display','none');
		});
	}).fail(function(){
		console.log("error");
	});
}

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function update_erc_eth_balance(){
	$.ajax({
		url : "/refresh_erc_eth_balance",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		//console.log(data);	
	}).fail(function(){
		console.log("error");
	});

}

function cash_deposite(){
	var amount = $('#cash_deposite').val();
	$.ajax({
		url : "/cash_deposite",
		type : "POST",
		data : {_token : CSRF_TOKEN, amount:amount},
		dataType : "JSON"
	}).done(function(data) {
		if(data.status == 'error1'){
			swal({
				text: __.message.cash_deposite_error1,
				icon: "warning",
				button: __.message.ok,
			});
		}else if(data.status == 'error2'){
			swal({
				text: __.message.cash_deposite_error2,
				icon: "warning",
				button: __.message.ok,
			});
		}else{
			swal({
				text: __.message.cash_deposite_success,
				icon: "success",
				button: __.message.ok,
			});
			all_refresh();
		}
	}).fail(function(){
		console.log("error");
	});
}

function cash_withdraw(){
	var amount = $('#cash_withdraw_amt').val();
	var symbol = $("#this_symbol_hidden").val();
	$.ajax({
		url : "/cash_withdraw",
		type : "POST",
		data : {_token : CSRF_TOKEN, amount:amount, symbol:symbol},
		dataType : "JSON"
	}).done(function(data) {
		if(data.status == 'error1'){
			swal({
				text: __.message.cash_withdraw_error1,
				icon: "warning",
				button: __.message.ok,
			});
		}else if(data.status == 'error2'){
			swal({
				text: __.message.cash_withdraw_error2,
				icon: "warning",
				button: __.message.ok,
			});
		}else if(data.status == 'error2'){
			swal({
				text: __.message.cash_withdraw_error3,
				icon: "warning",
				button: __.message.ok,
			});
		}else{
			swal({
				text: __.message.cash_withdraw_success,
				icon: "success",
				button: __.message.ok,
			});
			all_refresh();
		}
	}).fail(function(){
		console.log("error");
	});
}

function cash_cancel(id,type){
	$.ajax({
		url : "/cash_cancel",
		type : "POST",
		data : {_token : CSRF_TOKEN, id:id, type:type},
		dataType : "JSON"
	}).done(function(data) {
		console.log(data);
		if(data.status == 'error'){
			swal({
				text: __.message.cash_cancel_error,
				icon: "warning",
				button: __.message.ok,
			});
		}else{
			swal({
				text: __.message.cash_cancel_success,
				icon: "success",
				button: __.message.ok,
			});
			all_refresh();
		}
	}).fail(function(){
		console.log("error");
	});
}