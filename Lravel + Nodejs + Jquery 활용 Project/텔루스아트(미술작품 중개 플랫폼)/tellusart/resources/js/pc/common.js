//양쪽퀵
$(function(){
		/*setInterval(function() {
		refresh_balance();
		}, 3000);*/

		$(window).scroll(function(){
			var topPos=$(window).scrollTop()+125;
			$("#quickW").stop().animate({top:topPos + "px"},300);
		});

		if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
			$('#addressCopyButton').on('click', function(e){
				select_all_and_copy("address_copy");
			});
		} else {
			var clipboard = new ClipboardJS('#addressCopyButton');
			clipboard.on('success', function(e) {
				alert('주소가 복사되었습니다.');
				e.clearSelection();
			});

			clipboard.on('error', function(e) {
				alert('선택된 텍스트를 복사하세요.');
			});
		}
});
$(document).ready(function(){

		$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
		$('.scrollToTop').fadeIn();
		} else {
		$('.scrollToTop').fadeOut();
		}
		});
		//Click event to scroll to top
		$(".scrollToTop").click(function(){
		$("html, body").animate({scrollTop : 0},600);
		return false;
		});
});

//메인터치슬라이드
$(document).ready(function() {
	
	$("#touchSlider6").touchSlider({
		flexible : true,
		paging : $("#touchSlider6").next().find(".btn_page"),
		initComplete : function (e) {
			$("#touchSlider6").next().find(".btn_page").each(function (i, el) {
				$(this).text("page " + (i+1));
			});
		},
		counter : function (e) {
			$("#touchSlider6").next().find(".btn_page").removeClass("on").eq(e.current-1).addClass("on");
		}
	});
	
});
//서브3뎁스


//카운트효과

$(document).ready(function(){
	var theDaysBox  = $("#numdays");
	var theHoursBox = $("#numhours");
	var theMinsBox  = $("#nummins");
	var theSecsBox  = $("#numsecs");
	
	var refreshId = setInterval(function() {
	  var currentSeconds = theSecsBox.text();
	  var currentMins    = theMinsBox.text();
	  var currentHours   = theHoursBox.text();
	  var currentDays    = theDaysBox.text();
	  
	  if(currentSeconds == 0 && currentMins == 0 && currentHours == 0 && currentDays == 0) {
	  
	  } else if(currentSeconds == 0 && currentMins == 0 && currentHours == 0) {
	  	
	  	theDaysBox.html(currentDays-1);
	  	theHoursBox.html("23");
	  	theMinsBox.html("59");
	  	theSecsBox.html("59");
	  } else if(currentSeconds == 0 && currentMins == 0) {
	  
	  	theHoursBox.html(currentHours-1);
	  	theMinsBox.html("59");
	  	theSecsBox.html("59");
	  } else if(currentSeconds == 0) {

	  	theMinsBox.html(currentMins-1);
	  	theSecsBox.html("59");
	  } else {
      	theSecsBox.html(currentSeconds-1);
      }
   }, 1000);
   
   
   $('#email_certify_btn').click(function(){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var email = $('input[name="email"]').val();
		
		if(email == ''){
			alert('이메일 주소를 입력하여주세요');
		}else{
			if(!$(this).hasClass('active')){
				$.ajax({
			       url: '/certify/email',
			       type: 'POST',
			       /* send the csrf-token and the input to the controller */
			       data: {_token: CSRF_TOKEN, email:email},
			       dataType: 'JSON',
			       /* remind that 'data' is the response of the AjaxController */
			       success: function (data) { 
			       		if(data.exist){
			       			alert('이미 가입된 이메일 주소 입니다.');
			       			$('input[name="email"]').val('');
			       		}else{
			       			if(confirm('사용 가능한 이메일 주소입니다. 사용하시겠습니까?')){
			       				$('input[name="email"]').attr("readonly","readonly");
			       				$('#email_certify').val(1);
			       				$('#email_certify_btn').addClass('active');
			       				$('#email_certify_btn').text('사용가능');
			       			}
			       		}     	
			       }
			   	});
			  }
			}
   });
   
   $('#nickname_certify_btn').click(function(){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var nickname = $('#nickname').val();
		
		if(nickname == ''){
			alert('닉네임을 입력하여주세요');
		}else{
			if(!$(this).hasClass('active')){
				$.ajax({
			       url: '/certify/nickname',
			       type: 'POST',
			       /* send the csrf-token and the input to the controller */
			       data: {_token: CSRF_TOKEN, nickname:nickname},
			       dataType: 'JSON',
			       /* remind that 'data' is the response of the AjaxController */
			       success: function (data) { 
			       		if(data.exist){
			       			alert('이미 존재하는 닉네임 입니다. 다른 닉네임을 입력하여 주세요.');
			       			$('input[name="nickname"]').val('');
			       		}else{
			       			if(confirm('사용 가능한 닉네임 입니다. 사용하시겠습니까?')){
			       				$('input[name="nickname"]').attr("readonly","readonly");
			       				$('#nickname_certify').val(1);
			       				$('#nickname_certify_btn').addClass('active');
			       				$('#nickname_certify_btn').text('사용가능');
			       			}
			       		}     	
			       }
			   	});
			 }
		}
		
   });
   
   /* Original Jongwan modal JS Start */

	$('#review_jw_more_btn').click(function(){
		$('html').css("overflow", "hidden");
		$('#review_more_modal').removeClass('hidden');
		setTimeout(function() {
			$('#review_more_modal').addClass('active');
		}, 300);
		
	});
	
	$('#review_more_modal .jw_overlay, #review_more_modal .jw_modal_hd>div').click(function(){
		$('#review_more_modal').removeClass('active');
		$('html').css("overflow", "auto");
		setTimeout(function() {
			$('#review_more_modal').addClass('hidden');
		}, 300);
	});
	
	
	$('#expert_review_jw_more_btn').click(function(){
		$('html').css("overflow", "hidden");
		$('#expert_review_more_modal').removeClass('hidden');
		setTimeout(function() {
			$('#expert_review_more_modal').addClass('active');
		}, 300);
		
	});
	
	$('#expert_review_more_modal .jw_overlay, #expert_review_more_modal .jw_modal_hd>div').click(function(){
		$('#expert_review_more_modal').removeClass('active');
		$('html').css("overflow", "auto");
		setTimeout(function() {
			$('#expert_review_more_modal').addClass('hidden');
		}, 300);
	});
	
	/* Original Jongwan modal JS End */
	
	$('#transactions_list_div').scroll(function() { //transactions infinity scroll event
		if (($('#transactions_list_div').scrollTop() + $('#transactions_list_div').prop('clientHeight')).toFixed(0)  == $('#transactions_list_div').prop('scrollHeight') ) {
			var list = $('#transactions_list_div');
			if(list.data('isLoading')) {
				return;
			}
			list.data('isLoading', true);

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');		
			var page = $("#transactions_list tr").length;
			$.ajax({
				url : "/address/infinity",
				type : "POST",
				data : {_token : CSRF_TOKEN, page : page},
				dataType : "JSON"
			}).done(function(data) {
				$.each(data,function(){					
					var str = '<tr>';

					if(this.request_type == 'deposit'){
						str += '<td class="blue bold">입금</td>';
						str += '<td class="en"><strong>' + this.request_amount + '<em>TLG</em></strong>' + (this.request_address ? this.request_address : '') + '<br>' + (this.confirm_tx ? this.confirm_tx : '') + '</td>';
						if(this.request_status === 'sell') {
							str += '<td><span class="state ok">판매</span><span class="en">' + this.updated + '</span></td>';
						} else if(this.request_status === 'refund') {
							str += '<td><span class="state ok">환불</span><span class="en">' + this.updated + '</span></td>';
						} else if(this.request_status === 'batting') {
							str += '<td><span class="state ok">베팅</span><span class="en">' + this.updated + '</span></td>';
						} else if(this.request_status === 'reward') {
							str += '<td><span class="state ok">보상</span><span class="en">' + this.updated + '</span></td>';
						} else {
							if(this.request_status === 'deposit_confirmed') {
								str += '<td><span class="state ok">입금완료</span><span class="en">' + this.updated + '</span></td>';
							} else {
								str += '<td><span class="state ok">입금대기</span><span class="en">' + this.updated + '</span></td>';
							}
						}
					}else{
						str += '<td class="red bold">출금</td>';
						str += '<td class="en"><strong>' + this.request_amount + '<em>TLG</em></strong>' + (this.request_address ? this.request_address : '') + '<br>' + (this.confirm_tx ? this.confirm_tx : '') + '</td>';
						if(this.request_status === 'buy') {
							str += '<td><span class="state okout">구매</span><span class="en">' + this.updated + '</span></td>';
						} else if(this.request_status === 'batting') {
							str += '<td><span class="state okout">베팅</span><span class="en">' + this.updated + '</span></td>';
						} else if(this.request_status === 'fee') {
							str += '<td><span class="state okout">수수료</span><span class="en">' + this.updated + '</span></td>';
						} else {
							if(this.request_status === 'withdraw_confirmed') {
								str += '<td><span class="state okout">출금완료</span><span class="en">' + this.updated + '</span></td>';
							} else {
								str += '<td><span class="state okout">출금대기</span><span class="en">' + this.updated + '</span></td>';
							}
						}
					}

					str += '</tr>';
					
					$("#transactions_list").append(str);
				});
			}).error(function(){
				console.log("error");
			}).always(function(){
				list.data('isLoading', false);
			});
		}
	});
	
	$('#charge_list_div').scroll(function() { //charges infinity scroll event
		if (($('#charge_list_div').scrollTop() + $('#charge_list_div').prop('clientHeight')).toFixed(0)  == $('#charge_list_div').prop('scrollHeight') ) {
			var list = $('#charge_list_div');
			if(list.data('isLoading')) {
				return;
			}
			list.data('isLoading', true);

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			var page = $("#charge_list tr").length;
			$.ajax({
				url : "/charge/infinity",
				type : "POST",
				data : {_token : CSRF_TOKEN, page : page},
				dataType : "JSON"
			}).done(function(data) {
				$.each(data,function(){
					var str = '<tr>';
					str += '<td>' + numberWithCommas(this['amount_krw']) + ' 원</td>';
					str += '<td>' + numberWithCommas(this['amount_tlc']) + '<em> TLG</em></td>';
					str += '<td>' + this['created_at'] + '</td>';
					str += '</tr>';
					$("#charge_list").append(str);
				});
			}).error(function(){
				console.log("error");
			}).always(function(){
				list.data('isLoading', false);
			});
		}
	});
	
	$('.num_format').keyup(function(){
        $(this).val(comma(uncomma($(this).val())));
	})
	
	$('#left_fix .openBtn').click(function(){
		if($('#left_fix').hasClass('active')){
			$('.overlay').removeClass('active');
			$('#left_fix').removeClass('active');
			$(this).children('.fal').removeClass('fa-angle-left');
			$(this).children('.fal').addClass('fa-angle-right');
		}else{
			$('#left_fix').addClass('active');
			$('.overlay').addClass('active');
			$(this).children('.fal').removeClass('fa-angle-right');
			$(this).children('.fal').addClass('fa-angle-left');
		}
	});
	
	$('.overlay').click(function(){
		$('.overlay').removeClass('active');
		$('#left_fix').removeClass('active');
		$(this).children('.fal').removeClass('fa-angle-left');
		$(this).children('.fal').addClass('fa-angle-right');
	});
	
});


function dateDiff(_date1, _date2) {
    var diffDate_1 = _date1 instanceof Date ? _date1 : new Date(_date1);
    var diffDate_2 = _date2 instanceof Date ? _date2 : new Date(_date2);
 
    diffDate_1 = new Date(diffDate_1.getFullYear(), diffDate_1.getMonth()+1, diffDate_1.getDate());
    diffDate_2 = new Date(diffDate_2.getFullYear(), diffDate_2.getMonth()+1, diffDate_2.getDate());
 
    var diff = Math.abs(diffDate_2.getTime() - diffDate_1.getTime());
    diff = Math.ceil(diff / (1000 * 3600 * 24));
 
    return diff;
}


function chkchar(obj)
{
	 var chrTmp;
	 var strTmp  = obj.value;
	 var strLen  = strTmp.length;
	 var chkAlpha = false;
	 var resString = '';
	    if (strLen > 0) {
	        for (var i=0; i<strTmp.length; i++)
	        {
	            chrTmp = strTmp.charCodeAt(i);
	            if (!((chrTmp > 47 && chrTmp < 58) || (chrTmp > 64 && chrTmp < 91) || (chrTmp > 96 && chrTmp < 123) || (chrTmp > 44031 && chrTmp < 55203) || (chrTmp > 12592 && chrTmp < 12644)))
	            {
	                chkAlpha = true;
	            }
	            else
	            {
	                resString = resString + String.fromCharCode(chrTmp);
	            }
	        }
	    }
	 if (chkAlpha == true)
	 {
		  alert("한글,영문,숫자로만 작성해주세요.");
		  obj.value = resString;
		  obj.focus();
		  return false;
	 }
}

function chkPwd(str){

	 var pw = str;
	
	 var num = pw.search(/[0-9]/g);
	
	 var eng = pw.search(/[a-z]/ig);
	
	 var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);
	
	 
	
	 if(pw.length < 8 || pw.length > 20){
	
		  alert("8자리 ~ 20자리 이내로 입력해주세요.");
		
		  return false;
		
	 }
	
	 if(pw.search(/₩s/) != -1){
	
		  alert("비밀번호는 공백없이 입력해주세요.");
		
		  return false;
	
	 } if(num < 0 || eng < 0 || spe < 0 ){
	
		  alert("영문,숫자, 특수문자를 혼합하여 입력해주세요.");
		
		  return false;
	
	 }
	
	 
	
	 return true;

}

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function select_all_and_copy(el) {
	// http://www.seabreezecomputers.com/tips/copy2clipboard.htm
	
	var el = document.getElementById(el);
	// Copy textarea, pre, div, etc.
	if (document.body.createTextRange) {// IE 
	   var textRange = document.body.createTextRange();
	   textRange.moveToElementText(el);
	   textRange.select();
	   textRange.execCommand("Copy");   
	   alert('Copied to clipboard');
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
		  if (successful) alert('Copied to clipboard');
		  else alert('Press Ctrl+C to copy');
	   }
	   else
	   {
		  if (!navigator.userAgent.match(/ipad|ipod|iphone|android|silk/i))
			 alert('Press Ctrl+C to copy');
	   }
	}
 }

 /*
function address_copy() { //주소복사
	window.getSelection().removeAllRanges();
	
	var urlbox = document.querySelector("#address_copy");
	urlbox.focus();
	urlbox.select();
	
	var range = document.createRange();
	range.selectNode(urlbox);
	window.getSelection().addRange(range);
	
	try {
		var copy_success = document.execCommand('copy');
		alert("주소가 복사되었습니다.");
	}catch (err) {
		alert("주소복사에 실패하셨습니다.");
	}
	
	window.getSelection().removeAllRanges();
}
*/

function address_valid(){ //주소 유효성 검사
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var address_send = $('#address_send').val();
	if($.trim(address_send) == ''){
		alert('주소값을 적어주세요.');
	}else{
		$.ajax({
			url : "/address/valid",
			type : "POST",
			data : {address : address_send, _token : CSRF_TOKEN},
			dataType : "JSON"
		}).done(function(data) {
			if(data){
				$('#address_send').attr("disabled",true);
				$('#address_valid_btn').attr("onclick","address_valid_btn_offset();");
				$('#address_valid_btn').text('수정');
				alert("유효한 주소입니다.");
			}else{
				alert("유효하지 않은 주소입니다.");
			}
		}).error(function(){
			console.log("error");
		});
	} 

}

function address_valid_btn_offset(){ //주소 수정
	$('#address_send').attr("disabled",false);
	$('#address_valid_btn').attr("onclick","address_valid();");
	$('#address_valid_btn').text('주소체크');
}

function address_amount_maximum() { //보낼양 최대치
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/address/maximum",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		var maximum_amount = parseFloat(data.toFixed(8));
		$('#address_send_amount').val(maximum_amount);
		address_calcul_total();
		
	}).error(function(){
		console.log("error");
	}); 
}
function address_calcul_total(){ //수수료 계산 후 총 합계 계산
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/fee/withdraw",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
	var amount = parseFloat($('#address_send_amount').val());
	var fee = parseFloat(data);
	if(isNaN(amount)){
		amount = 0;
		fee = 0;
	}
	var total = parseFloat((amount + fee).toFixed(8));
	
	$('#address_send_fee').text(fee);
	$('#address_send_total').text(numberWithCommas(total));
	}).error(function(){
		console.log("error");
	});
}
function address_send(){ // 코인 보내기
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var address = $('#address_send').val();
	var amount = parseFloat($('#address_send_amount').val());
	var total = parseFloat($('#address_send_total').text());
	
	if($.trim(address) == ''){
		alert('주소값을 적어주세요.');
	}else{
		$.ajax({
			url : "/address/send",
			type : "POST",
			data : {_token : CSRF_TOKEN, address : address, amount : amount, total : total},
			dataType : "JSON"
		}).done(function(data) {
			refresh_transactions();
			refresh_balance();
			alert(data);
			
			$('#tab-21').attr('checked', false);
			$('#tab-22').attr('checked', true);
			$('#address_send_fee').text(0);
			$('#address_send_total').text(0);
			address_valid_btn_offset();
			$('#address_send').val("");
			$('#address_send_amount').val("");
			
			
		}).error(function(){
			console.log("error");
		});
	} 
}
function refresh_transactions(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/address/refresh",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		$("#transactions_list").empty();
		$.each(data,function(){			
			var str = '<tr>';
			
			if(this.request_type == 'deposit'){
				str += '<td class="blue bold">입금</td>';
				str += '<td class="en"><strong>' + this.request_amount + '<em>TLG</em></strong>' + (this.request_address ? this.request_address : '') + '<br>' + (this.confirm_tx ? this.confirm_tx : '') + '</td>';
				if(this.request_status === 'sell') {
					str += '<td><span class="state ok">판매</span><span class="en">' + this.updated + '</span></td>';
				} else if(this.request_status === 'refund') {
					str += '<td><span class="state ok">환불</span><span class="en">' + this.updated + '</span></td>';
				} else if(this.request_status === 'batting') {
					str += '<td><span class="state ok">베팅</span><span class="en">' + this.updated + '</span></td>';
				} else if(this.request_status === 'fee') {
					str += '<td><span class="state ok">수수료</span><span class="en">' + this.updated + '</span></td>';
				} else if(this.request_status === 'reward') {
					str += '<td><span class="state ok">보상</span><span class="en">' + this.updated + '</span></td>';
				} else {
					if(this.request_status === 'deposit_confirmed') {
						str += '<td><span class="state ok">입금완료</span><span class="en">' + this.updated + '</span></td>';
					} else {
						str += '<td><span class="state ok">입금대기</span><span class="en">' + this.updated + '</span></td>';
					}
				}
			}else{
				str += '<td class="red bold">출금</td>';
				str += '<td class="en"><strong>' + this.request_amount + '<em>TLG</em></strong>' + (this.request_address ? this.request_address : '') + '<br>' + (this.confirm_tx ? this.confirm_tx : '') + '</td>';
				if(this.request_status === 'buy') {
					str += '<td><span class="state okout">구매</span><span class="en">' + this.updated + '</span></td>';
				} else if(this.request_status === 'batting') {
					str += '<td><span class="state okout">베팅</span><span class="en">' + this.updated + '</span></td>';
				} else if(this.request_status === 'fee') {
					str += '<td><span class="state okout">수수료</span><span class="en">' + this.updated + '</span></td>';
				} else {
					if(this.request_status === 'withdraw_confirmed') {
						str += '<td><span class="state okout">출금완료</span><span class="en">' + this.updated + '</span></td>';
					} else {
						str += '<td><span class="state okout">출금대기</span><span class="en">' + this.updated + '</span></td>';
					}
				}
			}

			str += '</tr>';
			
			$("#transactions_list").append(str);
		});
		
		
		
	}).error(function(){
		console.log("error");
	});
}

function refresh_balance(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/balance/refresh",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		
		$('#my_balance_info').text(data);
		
	}).error(function(){
		console.log("error");
	});
}
function refresh_charges(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/charge/refresh",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		$("#charge_list").empty();
		$.each(data,function(){
			var str = '<tr>';
			str += '<td>' + numberWithCommas(this['amount_krw']) + ' 원</td>';
			str += '<td>' + numberWithCommas(this['amount_tlc']) + '<em>TLG</em></td>';
			str += '<td>' + this['created_at'] + '</td>';
			str += '</tr>';
			$("#charge_list").append(str);
		});
		
	}).error(function(){
		console.log("error");
	});
}

function charge_calcul(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var cash = $('#charge_cash').val();
	if(cash == 0 || cash == ''){
		$.ajax({
			url : "/charge/order",
			type : "POST",
			data : {_token : CSRF_TOKEN, cash : cash},
			dataType : "JSON"
		}).done(function(data) {
			if(data == 0){
				alert("현재 결제하신 금액에 맞는 매도물량의 코인이 없습니다. 거래소에서 거래 후 옮겨주세요.");
				$('#charge_coin').val(numberWithCommas(parseFloat(0).toFixed(8)));
				$('#charge_cash').val(0);
			}else{
				$('#charge_coin').val(numberWithCommas(parseFloat(data).toFixed(8)));
			}
		}).error(function(){
			console.log("error");
		});
	}
}

function charge_buy(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var cash = $('#charge_cash').val();
	if(cash == 0 || cash == ''){
		$.ajax({
			url : "/charge/buy",
			type : "POST",
			data : {_token : CSRF_TOKEN, cash : cash},
			dataType : "JSON"
		}).done(function(data) {
			if(data == 0){
				
			}else if(data == 0){
				
			}else{
				
			}
		}).error(function(){
			console.log("error");
		});
	}
}
function unixTime(unixtime) {

    var u = new Date(unixtime*1000);

	return u.getUTCFullYear() +
		'-' + ('0' + (u.getUTCMonth()+1)).slice(-2) +
		'-' + ('0' + u.getUTCDate()).slice(-2) + 
		' ' + ('0' + u.getUTCHours()).slice(-2) +
		':' + ('0' + u.getUTCMinutes()).slice(-2) +
		':' + ('0' + u.getUTCSeconds()).slice(-2)
};

function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 

function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}

function copyTextToClipboard(text) {
	var dummy = document.createElement('input');

	document.body.appendChild(dummy);
	dummy.value = text;
	dummy.select();
	document.execCommand('copy');
	document.body.removeChild(dummy);
}


$(".tab-contents").not(':first').hide();
	  
/*$('.big_tab li').on('click',function(){
    $(this).addClass("activeClass").siblings().removeClass("activeClass");
    var link = $(this).find("a").attr("href");
    var link_num = link.substr(link.length-1);
    $("select#tabmenu option").eq(link_num-1).prop("selected", "selected");
    $(".tab-contents").hide();
    $(link).show();
});*/

$("select#tabmenu").on("change",function(){ 
    var select_link = $("select#tabmenu").val();
    var select_num = $(this).prop('selectedIndex');
    $('.big_tab li').eq(select_num).addClass("activeClass").siblings().removeClass('activeClass');
    $(".tab-contents").hide();
    $(select_link).show();
    console.log(select_link);
});

var $grid = $('.glist').masonry({
    itemSelector: '.item',
    columnWidth: 5
});

$grid.imagesLoaded()
    .always( function( instance ) {
        $('.glist .item a p').removeClass('is-loading');
    }).progress( function() {
        $grid.masonry('layout');
        
});

$('.product_rating_star.starRev span').click(function(){
    $(this).parent().children('span').removeClass('on');
    $(this).addClass('on').prevAll('span').addClass('on');
    
    var rating = $(this).data('rating');
    
    $('.staron em.en').text(rating);
    $('input[name="rating"]').val(rating);
    $('#comment_modal .comodify ul li em').text(rating);
    return false;
});

$('.expert_rating_star.starRev span').click(function(){
    $(this).parent().children('span').removeClass('on');
    $(this).addClass('on').prevAll('span').addClass('on');
    
    var rating = $(this).data('rating');
    
	$(this).parent().siblings('em').text(rating);
	$('.modal_rating i').text(rating);
    return false;
});