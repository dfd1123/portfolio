
var ajax_run = "n";

$('#my_coin').click(function(){
	if($(this).prop("checked")){
		$("#my_coin_table > tbody > tr").hide();
		var k = "poss";
		var temp = $("#my_coin_table > tbody > tr > td:nth-child(2):contains('poss')");

		temp.parent().show();
	}else{
		$("#my_coin_table > tbody > tr").show();
	}
});


//1주일, 2주일, 1개월 클릭시 달력 input box 에 삽입
function input_calendar_data(value){
	var date_start = new Date();
	var date_end = new Date();
	date_start.setDate(date_start.getDate() - value); // value 값 전으로 날짜선택
	
	//시작날짜 yyyy-mm-dd 형식 만들기
	var start_year = date_start.getFullYear();
	var start_month = date_start.getMonth() + 1;
	var start_day = date_start.getDate();

	
	var date_s = start_year + '-' + start_month + '-' + start_day;
	
	//마지막날짜 yyyy-mm-dd 형식 만들기
	var end_year = date_end.getFullYear();
	var end_month = date_end.getMonth() + 1;
	var end_day = date_end.getDate();

	var date_e = end_year + '-' + end_month + '-' + end_day;

	$("#start_sch").val(date_s);
	$("#end_sch").val(date_e);
}

//날짜를 통한 검색 조회
function search_date_history(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var start_date = $("#start_sch").val();
	var end_date = $("#end_sch").val();
	if(start_date == '' || end_date == ''){
		swal({
			text: __.message.start_date_end_date,
			icon: "warning",
			button: __.message.ok,
		});
	}else{
		$.ajax({
			url : "/search_date_history",
			type : "POST",
			data : {_token : CSRF_TOKEN,start_date : start_date, end_date : end_date},
			dataType : "JSON"
		}).done(function(data) {
			$('#history_lists').empty();
			$.each(data.historys, function(){
				str = '<tr class="trs_type-' + this.trade_type + '" name="'+this.coinname.replace('<small>Fanclub Coin</small>', '')+'/'+this.symbol+'">';
				
				str += '<td>';
				str += '<p>';
				str += '<span>' + this.created_dt + '</span>';
				str += '</p></td>';
				
				str += '<td>';
				str += '<p>';
				str += '<span>' + this.currency + this.lang_market + '</span>';
				str += '</p>';
				str += '</td>';
				
				str += '<td class="coin_td"><img class="coin_symbol" src="/images/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname.replace('<small>Fanclub Coin</small>', '') + '</span><span class="coin_name_eng">' + this.api + '/'+this.currency+'</span></td>';
				
				str += '<td>';
				str += '<p>';
				str += '<span>' + this.trade_type_name + '</span>';
				str += '</p></td>';
				
				str += '<td>';
				str += '<div>';
				str += '<p>';
				str += '<span>' + this.coin_price + '</span>';
				str += '<span class="currency">&nbsp;'+this.currency+'</span>';
				str += '</p>';
				str += '</div>';
				str += '<div>';
				str += '<p>';
				str += '<span>' + this.contract_coin_amt + '</span>';
				str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
				str += '</p>';
				str += '</div>';
				str += '</td>';
				
				str += '<td>';
				str += '<div>';
				str += '<p>';
				str += '<span>' + this.total_price + '</span>';
				str += '<span class="currency">&nbsp;'+this.currency+'</span>';
				str += '</p>';
				str += '</div>';
				str += '<div>';
				str += '<p>';
				str += '<span>' + this.fee + '</span>';
				str += '<span class="currency">&nbsp;'+this.currency+'</span>';
				str += '</p>';
				str += '</div>';
				str += '</td>';
				
				str += '<td>';
				str += '<p>';
				str += '<span>' + this.calcul_price + '</span>';
				str += '<span class="currency">&nbsp;'+this.currency+'</span>';
				str += '</p></td>';
				
				str += '</tr>';
				$('#history_lists').append(str);
			});
			if(data.historys_count == 0){
				str = '<tr class="none_transac"><td colspan="8"><i class="fas fa-exclamation-circle none_fas mr-1"></i>'+__.my_asset.property_sentence2+'</td></tr>';
				$('#history_lists').append(str);
			}
			history_offset = 20;
			if(history_offset >= data.historys_count){
				$("#show_more_btn_div").empty();
			}else{
				$("#show_more_btn_div").html('<button onclick = "show_more_history();"><i class="fal fa-plus"></i>'+__.my_asset.more+'</button>');
			}
		}).fail(function(){
			console.log("error");
		});
	}
	
}

//거래내역 더보기 클릭
function show_more_history(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var start_date = $("#start_sch").val();
	var end_date = $("#end_sch").val();
	var str;
	if(start_date == '' || end_date == ''){ 
		input_calendar_data(30); //날짜 없을시 기본날짜 1달치
		start_date = $("#start_sch").val();
		end_date = $("#end_sch").val();
	}
	$.ajax({
		url : "/show_more_history",
		type : "POST",
		data : {_token : CSRF_TOKEN,offset : history_offset,start_date : start_date, end_date : end_date},
		dataType : "JSON",
		async: false
	}).done(function(data) {
		$('#txtFilter').val('');
		filter();
		$('#selectFilter').val('');
		selFilter();
		
		history_offset += 10;
		$.each(data.historys, function(){
			str = '<tr class="trs_type-' + this.trade_type + '" name="'+this.coinname.replace('<small>Fanclub Coin</small>', '')+'/'+this.symbol+'">';
				
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.created_dt + '</span>';
			str += '</p></td>';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.currency + this.lang_market + '</span>';
			str += '</p>';
			str += '</td>';
			
			str += '<td class="coin_td"><img class="coin_symbol" src="/images/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname.replace('<small>Fanclub Coin</small>', '') + '</span><span class="coin_name_eng">' + this.api + '/'+this.currency+'</span></td>';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.trade_type_name + '</span>';
			str += '</p></td>';
			
			str += '<td>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_price + '</span>';
			str += '<span class="currency">&nbsp;' + this.currency + '</span>';
			str += '</p>';
			str += '</div>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.contract_coin_amt + '</span>';
			str += '<span class="currency">&nbsp;'+this.symbol+'</span>';
			str += '</p>';
			str += '</div>';
			str += '</td>';
			
			str += '<td>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.total_price + '</span>';
			str += '<span class="currency">&nbsp;'+this.currency+'</span>';
			str += '</p>';
			str += '</div>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.fee + '</span>';
			str += '<span class="currency">&nbsp;'+this.currency+'</span>';
			str += '</p>';
			str += '</div>';
			str += '</td>';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.calcul_price + '</span>';
			str += '<span class="currency">&nbsp;'+this.currency+'</span>';
			str += '</p></td>';
			
			str += '</tr>';
			$('#history_lists').append(str);
		});
		
		if(history_offset >= data.historys_count){
			$("#show_more_btn_div").empty();
		}
	}).fail(function(){
		console.log("error");
	});
	
}

//미체결 더보기 클릭
function show_more_not_concluded(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	var str;

	$.ajax({
		url : "/show_more_not_concluded",
		type : "POST",
		data : {_token : CSRF_TOKEN,offset : concluded_offset},
		dataType : "JSON",
		async: false
	}).done(function(data) {
		concluded_offset += 10;
		$.each(data.wait_trades, function(){
			str = '<tr class="trs_type-' + this.type + '">';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.created_dt + '</span>';
			str += '</p></td>';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.currency + this.lang_market + '</span>';
			str += '</p>';
			str += '</td>';
			
			str += '<td class="coin_td"><img class="coin_symbol" src="/images/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname.replace('<small>Fanclub Coin</small>', '') + '</span><span class="coin_name_eng">' + this.api + '/'+this.currency+'</span></td>';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.type_name + '</span>';
			str += '</p></td>';
			
			str += '<td>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_price + '</span>';
			str += '<span class="currency">&nbsp;'+this.currency+'</span>';
			str += '</p>';
			str += '</div>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_amt_total + '</span>';
			str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
			str += '</p>';
			str += '</div>';
			
			str += '<td>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_amt_finished + '</span>';
			str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
			str += '</p>';
			str += '</div>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_amt + '</span>';
			str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
			str += '</p>';
			str += '</div>';
			str += '</td>';
			
			str += '<td>';
			str += '<button class="status_btn" id="btc_cancel_request_'+this.id+'" data-id="'+this.id+'" onclick="myasset_trade_cancel('+this.id+')">';
			str += __.my_asset.cancel_trade;
			str += '</button></td>';
			str += '</tr>';
			
			$('#not_concluded_lists').append(str);
		});
		
		if(concluded_offset >= data.wait_trades_count){
			$("#show_more_not_concluded_btn").empty();
		}
	}).fail(function(){
		console.log("error");
	});
	
}

function refresh_not_concluded(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	var str;

	$.ajax({
		url : "/refresh_not_concluded",
		type : "POST",
		data : {_token : CSRF_TOKEN,offset : concluded_offset},
		dataType : "JSON"
	}).done(function(data) {
		concluded_offset = 20;
		$('#not_concluded_lists').empty();
		$.each(data.wait_trades, function(){
			str = '<tr class="trs_type-' + this.type + '">';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.created_dt + '</span>';
			str += '</p></td>';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.currency + this.lang_market + '</span>';
			str += '</p>';
			str += '</td>';
			
			str += '<td class="coin_td"><img class="coin_symbol" src="/images/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname.replace('<small>Fanclub Coin</small>', '') + '</span><span class="coin_name_eng">' + this.api + '/'+this.currency+'</span></td>';
			
			str += '<td>';
			str += '<p>';
			str += '<span>' + this.type_name + '</span>';
			str += '</p></td>';
			
			str += '<td>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_price + '</span>';
			str += '<span class="currency">&nbsp;'+this.currency+'</span>';
			str += '</p>';
			str += '</div>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_amt_total + '</span>';
			str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
			str += '</p>';
			str += '</div>';
			
			str += '<td>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_amt_finished + '</span>';
			str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
			str += '</p>';
			str += '</div>';
			str += '<div>';
			str += '<p>';
			str += '<span>' + this.coin_amt + '</span>';
			str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
			str += '</p>';
			str += '</div>';
			str += '</td>';
			
			str += '<td>';
			str += '<button class="status_btn" id="btc_cancel_request_'+this.id+'" data-id="'+this.id+'" onclick="myasset_trade_cancel('+this.id+')">';
			str += __.my_asset.cancel_trade;
			str += '</button></td>';
			str += '</tr>';
			
			$('#not_concluded_lists').append(str);
		});
		
		if(concluded_offset >= data.wait_trades_count){
			$("#show_more_not_concluded_btn").empty();
		}
	}).fail(function(){
		console.log("error");
	});
	
}

function myasset_trade_cancel(id){

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
						refresh_not_concluded();
						$('#btc_cancel_request_' + id + '').parent().parent().remove();
						if($( "#not_concluded_lists" ).children().length == 0){
							str += '<tr class="none_transac"><td colspan="8"><img src="/images/icon_notice.svg" alt="" class="btn_notice">'+__.message.not_wait_trade_list+'</td></tr>'
						}
						ajax_run = 'n';
					}
				});
			}
		}
    });
}

	var history_offset = 20; // 시작점
	var concluded_offset = 20; // 시작점
//START 내 자산 > 거래내역 데이터 검색 pikaday plugin
    var startDate,
        endDate,
        updateStartDate = function() {
            startPicker.setStartRange(startDate);
            endPicker.setStartRange(startDate);
            endPicker.setMinDate(startDate);
        },
        updateEndDate = function() {
            startPicker.setEndRange(endDate);
            startPicker.setMaxDate(endDate);
            endPicker.setEndRange(endDate);
        },
        startPicker = new Pikaday({
            field: document.getElementById('start_sch'),
            minDate: new Date(2017, 1, 1),
            maxDate: new Date(),
            onSelect: function() {
                startDate = this.getDate();
                updateStartDate();
            },
			format: 'YYYY-MM-D',
			toString(date, format) {
				const day = date.getDate();
				const month = date.getMonth() + 1;
				const year = date.getFullYear();
				return `${year}-${month}-${day}`;
			}
			
        }),
        endPicker = new Pikaday({
            field: document.getElementById('end_sch'),
            minDate: new Date(2017, 1, 1),
            maxDate: new Date(),
            onSelect: function() {
                endDate = this.getDate();
                updateEndDate();
            },
			format: 'YYYY-MM-D',
			toString(date, format) {
				const day = date.getDate();
				const month = date.getMonth() + 1;
				const year = date.getFullYear();
				return `${year}-${month}-${day}`;
			}
			
        }),
        _startDate = startPicker.getDate(),
        _endDate = endPicker.getDate();

        if (_startDate) {
            startDate = _startDate;
            updateStartDate();
        }

        if (_endDate) {
            endDate = _endDate;
            updateEndDate();
        }
//END 내 자산 > 거래내역 데이터 검색 pikaday plugin