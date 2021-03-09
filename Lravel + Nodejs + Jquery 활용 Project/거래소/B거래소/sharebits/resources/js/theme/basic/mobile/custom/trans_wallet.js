// 전체,입금,출금 중 선택한것만 보이도록
$('.con_3 .sch_bar .coin_sch_checkbox select').change(function(){
	
	var sel_wd_kind = $(this).val();
	$("#transaction_list li").css('display','none');
	if(sel_wd_kind == 0){
		$("#transaction_list li.out").css('display','');
	}else if(sel_wd_kind == 1){
		$("#transaction_list li.in").css('display','');
	}else{
		$("#transaction_list li").css('display','');
	}
});
// 보유코인만 보이도록
$('#my_coin').click(function(){
	if($(this).prop("checked")){
		$("#coin_list_table > tbody > tr").hide();
		var temp = $(".exist_balance").show();
	}else{
		$("#coin_list_table > tbody > tr").show();
	}
});

/* 입출금 코인목록에서 누르면 입출금박스로 이동 start */
$('#m_transWltwrap .coin_chart_tbl tr>td').not('.td_btn').click(function(){
    $('#m_transWltwrap').css({
        left: -100+'%'
    });
    $('#goTolist').fadeIn(200);
});

$('#goTolist').click(function(){
    $('#m_transWltwrap').css({
        left: 0
    });
    $(this).fadeOut(100);
});
/* 코인목록에서 누르면 입출금박스로 이동 end */

/* 입금하기-출금하기-입출금내역 누르면 active 주기 start */
$('#trans_wlt_tab ul li').click(function(){
	$(this).addClass('active');
	$('#trans_wlt_tab ul li').not(this).removeClass('active');
});
/* 입금하기-출금하기-입출금내역 누르면 active 주기 end */

if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
        $('.transwallet_btn').on('click', function(e){
            select_all_and_copy("deposit_coin_address");
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
			//alertify.error(__.message.fail_copy_addr);
	});
}   

//거래내역 코인검색
$('#txtFilter_trans').keyup(function(){
	filter_trans();
	return false;
});

$('#txtFilter_trans').keypress(function(){
	if(event.keyCode==13){
		filter_trans();
		return false;
	}
});

function filter_trans() {
  var searchstr1 = $('#txtFilter_trans').val().toUpperCase();
  var searchstr2 = $('#txtFilter_trans').val().toLowerCase();
  if ($('#txtFilter_trans').val() == ""){
  	$("#coin_list_table tbody tr").css('display', '');
  } else {
    $("#coin_list_table tbody tr").css('display', 'none');
    $("#coin_list_table tbody tr[name*='" + searchstr1 + "']").css('display', '');
    $("#coin_list_table tbody tr[name*='" + searchstr2 + "']").css('display', '');
  }
  return false;
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
		if (el.nodeName == "TEXTAREA" || el.nodeName == "INPUT"){
			el.select(); // Firefox will only select a form element with select()
		}
		if (el.setSelectionRange && navigator.userAgent.match(/ipad|ipod|iphone/i)){
			el.setSelectionRange(0, 999999); // iOS only selects "form" elements with SelectionRange
		}
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
				icon: "success",
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
				icon: "success",
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
	swal({
		text: __.message.rusure,
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
	}).then((value) => {
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
						before_text_cointype = 'UCSS';
						decimal_before = 2;
					}else{
						before_text_cointype = before_cointype;
						decimal_before = 8;
					}
					if(after_cointype == 'USD'){
						after_text_cointype = 'UCSS';
						decimal_after = 2;
					}else{
						after_text_cointype = after_cointype;
						decimal_after = 8;
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
		$("#this_symbol").html(data.coinname + " " + __.trans.inout);
		$("#this_balance_total").html(numberWithCommas(data.total.toFixed(data.coin_decimal)));
		$("#this_balance_total_currency").html(data.symbol_text);
		$("#this_balance_eval").html(numberWithCommas(data.eval.toFixed(data.cash_decimal))+' <span class="currency">UCSS</span> ');
		$("#this_balance_pending").html(numberWithCommas(data.pending.toFixed(data.coin_decimal)) + ' <u>' + data.symbol_text + '</u>');
		$("#this_balance_available").html(numberWithCommas(data.available.toFixed(data.coin_decimal)) + ' <u>' + data.symbol_text + '</u>');
		
		// 입금정보 삽입
		
		$("#first_deposit_info").text(__.trans.is_my + ' ' + data.coinname + ' ' + __.trans.deposit_address);
		$("#deposit_coin_address_qrcode").html('<img id="qrcode" src="https://chart.googleapis.com/chart?chs=200x200&amp;cht=qr&amp;chl=' + data.address + '&amp;choe=UTF-8" alt="qr">');
		$("#deposit_coin_address").val(data.address);
		
		// 출금정보 삽입
		$("#withdraw_limit_amt").html(' <b>' + numberWithCommas(data.withdraw_limit_amt) + '</b> <b class="currency">' + data.symbol_text + '</b> ');
		$("#withdraw_send_fee_currency").text(data.symbol_text);
		$("#withdraw_total_amt_currency").text(data.symbol_text);
		
		$('#withdraw_amt').val('');
		$('#withdraw_check_address').val('');

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
	}).fail(function(){
		console.log("error");
	});
}

function withdraw_onkey_amt(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var symbol = $("#this_symbol_hidden").val();
	var amt = $("#withdraw_amt").val();
	if(amt == '' || amt == null){
		amt = 0;
	}
	
	$.ajax({
		url : "/withdraw_onkey_amt",
		type : "POST",
		data : {_token : CSRF_TOKEN, symbol : symbol, amt : amt},
		dataType : "JSON"
	}).done(function(data) {
		$("#withdraw_amt").val(data.amount);
		$("#withdraw_send_fee").text(data.send_fee);
		$("#withdraw_total_amt").text(data.total_amount);
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
				$('#trans_wlt_tab ul li:nth-child(3)').addClass('active');
				$('#trans_wlt_tab ul li:nth-child(3)').siblings().removeClass('active');
				$('#con_out').attr("checked","false");
				$('#con_history').attr("checked","true");
				
				all_refresh();
			}else if(data.state == 'success_now'){
				swal({
					text: __.message.complete_withdraw,
					icon: "success",
					button: __.message.ok,
				});
				$('#trans_wlt_tab ul li:nth-child(3)').addClass('active');
				$('#trans_wlt_tab ul li:nth-child(3)').siblings().removeClass('active');
				$('#con_out').attr("checked","false");
				$('#con_history').attr("checked","true");
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
	var btc_price_usd;
	var total_holding;
	var holding_percent;
	var holding_balance;
	var holding_convert;
	$.ajax({
		url : "/refresh_trans_wallet",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		btc_price_usd = data.btc_price_usd;
		total_holding = data.total_holding;
		$("#holding_total_balance").text(numberWithCommas(total_holding.toFixed(8)));
		$.each(data.coin, function(){
			if(total_holding == 0){
				holding_percent = 0;
			}else{
				holding_percent = this.balance*this.price/total_holding*100;
			}
			holding_balance = this.balance;
			if(this.symbol == 'USD'){
				if(btc_price_usd == 0){
					holding_convert = 0;
				}else{
					holding_convert = this.balance / btc_price_usd;	
				}
				$("#holding_convert_"+this.symbol).text(numberWithCommas(holding_convert.toFixed(8)));
				$("#holding_balance_"+this.symbol).text(numberWithCommas(holding_balance.toFixed(8)));
			}else{
				holding_convert = this.balance * this.price;
				$("#holding_convert_"+this.symbol).text(numberWithCommas(holding_convert.toFixed(this.decimal_usd)));
				$("#holding_balance_"+this.symbol).text(numberWithCommas(holding_balance.toFixed(8)));
			}
			
			$("#holding_percent_"+this.symbol).text(holding_percent.toFixed(2)+"%");
			
			
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
	var date_at;
	$.ajax({
		url : "/refresh_withdraw_history",
		type : "POST",
		data : {_token : CSRF_TOKEN, symbol : symbol},
		dataType : "JSON"
	}).done(function(data) {
		$("#transaction_list").empty();
		$.each(data.transaction_list, function(){
			if(this.cointype == 'USD'){
				symbol_text = 'UCSS';
			}else{
				symbol_text = this.cointype;
			}
			date_at = this.updated.split(' '); //날짜 분리
			if(this.type == 'withdraw' || this.type == 'out'){
				if(this.status == 'withdraw_request'){
					status_text = __.trans.out_request;
				}else if(this.status == 'withdraw_request_confirm'){
					status_text =  __.trans.out_ready;
				}else if(this.status == 'withdraw_reject'){
					status_text = __.trans.denial_out;
				}else{
					status_text = __.trans.out_okay;
				}
				str = '<li class="con out">';
	            str += '<p class="info _date mb-2">';
                str += '<span>' + date_at[0] + '</span>';
                str += '<span class="ml-1">' + date_at[1] + '</span>';
                str += '<span class="float-right type">'+__.trans.out+'</span>';
	            str += '</p>';
	            str += '<p class="info _amt">';
                str += '<label>'+__.trans.quantity+'</label>';
                str += '<span>' + this.total_amt + '</span>';
                str += '<span class="currency">' + symbol_text + '</span>';
	            str += '</p>';
	            str += '<p class="info">';
	            str += '<label>'+__.trans.now+'</label>';
                str += '<span>' + status_text + '</span>';
	            str += '</p>';
                str += '</li>';
			}else if(this.type == 'receive' || this.type == 'in'){				
				str = '<li class="con in">';
	            str += '<p class="info _date mb-2">';
                str += '<span>' + date_at[0] + '</span>';
                str += '<span class="ml-1">' + date_at[1] + '</span>';
                str += '<span class="float-right type">'+__.trans.in+'</span>';
	            str += '</p>';
	            str += '<p class="info _amt">';
                str += '<label>'+__.trans.quantity+'</label>';
                str += '<span>' + this.total_amt + '</span>';
                str += '<span class="currency">' + symbol_text + '</span>';
	            str += '</p>';
	            str += '<p class="info">';
	            str += '<label>'+__.trans.now+'</label>';
				str += '<span>'+__.trans.in_okay+'</span>';
	            str += '</p>';
                str += '</li>';
			}
			$("#transaction_list").append(str);
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
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/refresh_erc_eth_balance",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		console.log(data);	
	}).fail(function(){
		console.log("error");
	});

}
