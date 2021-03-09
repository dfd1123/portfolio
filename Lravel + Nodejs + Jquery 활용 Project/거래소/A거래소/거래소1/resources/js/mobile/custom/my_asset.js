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
				str = '<li class="con ' + this.trade_type + '">';
                str += '<p class="info _date mb-2">';
                str += '<span>' + this.created_dt + '</span>';
                str += '<span class="float-right type">' + this.lang_type_name + '</span>';
                str += '</p>';
                str += '<p class="info _coin">';
                str += '<span>' + this.currency + this.lang_market + '</span>&nbsp;';
                str += '<span>' + this.coinname + '(<u>' + this.symbol + '</u>)</span>';
                str += '</p>';
                str += '<p class="info _amt">';
                str += '<label>' + this.lang_trade_quantity + '</label>';
                str += '<span class="point_clr_1">' + this.contract_coin_amt + '</span>';
                str += '<span class="currency">' + this.symbol + '</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_trade_unit_price + '</label>';
                str += '<span>' + this.coin_price + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '<p class="info">';
	            str += '<label>' + this.lang_trade_price + '</label>';
                str += '<span>' + this.total_price + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_fees + '</label>';
                str += '<span>' + this.fee + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_settlement_amount_mb + '</label>';
                str += '<span>' + this.calcul_price + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '</li>';
				$('#history_lists').append(str);
			});
			if(data.historys_count == 0){
				str = '<li class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>'+__.my_asset.property_sentence2+'</li>';
				$('#history_lists').append(str);
			}
			history_offset = 20;
		}).fail(function(){
			console.log("error");
		});
	}
	
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
			str = '<li class="con ' + this.type + '">';
            str += '<p class="info _date mb-2">';
            str += '<span>' + this.created_dt + '</span>';
            str += '<span class="float-right type">' + this.lang_type_name + '</span>';
            str += '</p>';
            str += '<p class="info _coin">';
            str += '<span>' + this.currency + this.lang_market + '</span>&nbsp;';
            str += '<span>' + this.coinname + '(<u>' + this.symbol + '</u>)<span>';
            str += '</p>';
            str += '<p class="info _amt">';
        	str += '<label>' + this.lang_order_price + '</label>';
            str += '<span class="point_clr_1">' + this.coin_price + '</span>';
            str += '<span class="currency">'+this.currency+'</span>';
            str += '</p>';
            str += '<p class="info">';
            str += '<label>' + this.lang_order_quntity + '</label>';
            str += '<span>' + this.coin_amt_total + '</span>';
            str += '<span class="currency">' + this.symbol + '</span>';
            str += '</p>';
            str += '<p class="info">';
            str += '<label>' + this.lang_conclusion_quantity + '</label>';
            str += '<span>' + this.coin_amt_finished + '</span>';
            str += '<span class="currency">' + this.symbol + '</span>';
            str += '</p>';
            str += '<p class="info">';
            str += '<label>' + this.lang_not_conclusion_quantity + '</label>';
            str += '<span>' + this.coin_amt + '</span>';
            str += '<span class="currency">' + this.symbol + '</span>';
            str += '</p>';
            str += '<p class="info">';
            str += '<label>' + this.lang_now + '</label>';
            str += '<span>';
            str += '<button type="button" id="btc_cancel_request_'+this.id+'" data-id="'+this.id+'" onclick="myasset_trade_cancel('+this.id+')">' + this.lang_cancel_trade + '</button>';
            str += '</span>';
            str += '</p>';
            str += '</li>';
			
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
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
                url : "/requests/trade_cancel",
                type : "POST",
                data: {_token: CSRF_TOKEN, id: id},
                dataType: 'JSON',
                success : function(data) {
                    alertify.success(data.message);
                       refresh_not_concluded();
                }
            });
        }
    });
}

//필터 (코인목록 검색)
function filter_history() {
  var searchstr1 = $('#txtFilter_history').val().toUpperCase();
  var searchstr2 = $('#txtFilter_history').val().toLowerCase();
  if ($('#txtFilter_history').val() == ""){
  	$("#history_lists li").css('display', '');
  } else {
    $("#history_lists li").css('display', 'none');
    $("#history_lists li[name*='" + searchstr1 + "']").css('display', '');
    $("#history_lists li[name*='" + searchstr2 + "']").css('display', '');
  }
  return false;
}

//거래내역 코인검색
$('#txtFilter_history').keyup(function(){
	filter_history();
	return false;
});

$('#txtFilter_history').keypress(function(){
	if(event.keyCode==13){
		filter_history();
		return false;
	}
});

// 전체,입금,출금 중 선택한것만 보이도록
$('.con_2 .sch_bar .coin_sch_checkbox select').change(function(){
	
	var sel_wd_kind = $(this).val();
	$("#history_lists li").css('display','none');
	if(sel_wd_kind == 0){
		$("#history_lists li.sell").css('display','');
	}else if(sel_wd_kind == 1){
		$("#history_lists li.buy").css('display','');
	}else{
		$("#history_lists li").css('display','');
	}
});

// 보유코인만 보이도록
$('#my_coin').click(function(){
	if($(this).prop("checked")){
		$(".my_coin_list_con").hide();
		$(".exist_balance").show();
	}else{
		$(".my_coin_list_con").show();
	}
});

/* 내자산페이지 보유코인-거래내역-미체결 누를 때 효과적용 start */
$('#my_ast_tab ul li').click(function(){
	$(this).addClass('active');
	$('#my_ast_tab ul li').not(this).removeClass('active');
});
/* 내자산페이지 보유코인-거래내역-미체결 누를 때 효과적용 end */

/*  거래내역 더보기 무한스크롤링 */
$("#history_scroll").scroll(function() { // 스크롤 움직일때마다 이벤트 발생
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var start_date = $("#start_sch").val();
	var end_date = $("#end_sch").val();
	
	if(start_date == '' || end_date == ''){ 
		input_calendar_data(30); //날짜 없을시 기본날짜 1달치
		start_date = $("#start_sch").val();
		end_date = $("#end_sch").val();
	}
	
	var maxHeight = document.getElementById('history_scroll').scrollHeight; // 스크롤바 뺀 총높이
	var clientHeight = document.getElementById('history_scroll').clientHeight; // 스크롤바 뺀 현재높이
	var scrollTop = document.getElementById('history_scroll').scrollTop; // 스크롤 현재위치
	
	var currentScroll = parseInt(clientHeight.toFixed(0)) + parseInt(scrollTop.toFixed(0)); // 브라우저 스크롤 위치값 + 윈도우의크기

	if (maxHeight == currentScroll) {
		$.ajax({
			url : "/show_more_history",
			type : "POST",
			data : {_token : CSRF_TOKEN,offset : history_offset,start_date : start_date, end_date : end_date},
			dataType : "JSON"
		}).done(function(data) {
			history_offset += 10;
			$.each(data.historys, function(){
			
				str = '<li class="con ' + this.trade_type + '">';
                str += '<p class="info _date mb-2">';
                str += '<span>' + this.created_dt + '</span>';
                str += '<span class="float-right type">' + this.lang_type_name + '</span>';
                str += '</p>';
                str += '<p class="info _coin">';
                str += '<span>' + this.currency + this.lang_market + '</span>&nbsp;';
                str += '<span>' + this.coinname + '(<u>' + this.symbol + '</u>)</span>';
                str += '</p>';
                str += '<p class="info _amt">';
                str += '<label>' + this.lang_trade_quantity + '</label>';
                str += '<span class="point_clr_1">' + this.contract_coin_amt + '</span>';
                str += '<span class="currency">' + this.symbol + '</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_trade_unit_price + '</label>';
                str += '<span>' + this.coin_price + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '<p class="info">';
	            str += '<label>' + this.lang_trade_price + '</label>';
                str += '<span>' + this.total_price + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_fees + '</label>';
                str += '<span>' + this.fee + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_settlement_amount_mb + '</label>';
                str += '<span>' + this.calcul_price + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '</li>';
				$('#history_lists').append(str);
			});

		}).fail(function(){
			console.log("error");
		});
	}
});

/*  미체결내역 더보기 무한스크롤링 */
$("#wait_scroll").scroll(function() { // 스크롤 움직일때마다 이벤트 발생
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	var maxHeight = document.getElementById('wait_scroll').scrollHeight; // 스크롤바 뺀 총높이
	var clientHeight = document.getElementById('wait_scroll').clientHeight; // 스크롤바 뺀 현재높이
	var scrollTop = document.getElementById('wait_scroll').scrollTop; // 스크롤 현재위치
	
	var currentScroll = parseInt(clientHeight.toFixed(0)) + parseInt(scrollTop.toFixed(0)); // 브라우저 스크롤 위치값 + 윈도우의크기

	if (maxHeight == currentScroll) {
		$.ajax({
			url : "/show_more_not_concluded",
			type : "POST",
			data : {_token : CSRF_TOKEN,offset : concluded_offset},
			dataType : "JSON"
		}).done(function(data) {
			concluded_offset += 10;
			$.each(data.wait_trades, function(){
				str = '<li class="con ' + this.type + '">';
                str += '<p class="info _date mb-2">';
                str += '<span>' + this.created_dt + '</span>';
                str += '<span class="float-right type">' + this.lang_type_name + '</span>';
                str += '</p>';
                str += '<p class="info _coin">';
                str += '<span>' + this.currency + this.lang_market + '</span>&nbsp;';
                str += '<span>' + this.coinname + '(<u>' + this.symbol + '</u>)</span>';
                str += '</p>';
                str += '<p class="info _amt">';
            	str += '<label>' + this.lang_order_price + '</label>';
                str += '<span class="point_clr_1">' + this.coin_price + '</span>';
                str += '<span class="currency">'+this.currency+'</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_order_quntity + '</label>';
                str += '<span>' + this.coin_amt_total + '</span>';
                str += '<span class="currency">' + this.symbol + '</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_conclusion_quantity + '</label>';
                str += '<span>' + this.coin_amt_finished + '</span>';
                str += '<span class="currency">' + this.symbol + '</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_not_conclusion_quantity + '</label>';
                str += '<span>' + this.coin_amt + '</span>';
                str += '<span class="currency">' + this.symbol + '</span>';
                str += '</p>';
                str += '<p class="info">';
                str += '<label>' + this.lang_now + '</label>';
                str += '<span>';
                str += '<button type="button">' + this.lang_cancel_trade + '</button>';
                str += '</span>';
                str += '</p>';
	            str += '</li>';
			
				
				$('#not_concluded_lists').append(str);
			});

		}).fail(function(){
			console.log("error");
		});
	}
});

var history_offset = 20; // 시작점
var concluded_offset = 20; // 시작점

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

