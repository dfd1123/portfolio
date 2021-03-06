// Hide Header on on scroll down 
var didScroll; 
var lastScrollTop = 0; 
var delta = 5; 
var navbarHeight = 40;
var wrap = $('#wrap');


wrap.scroll(function(event){ 
    didScroll = true; 
}); 

setInterval(function() { 
    if (didScroll) { 
        jwhasScrolled(); 
        didScroll = false; 
    } 
}, 250); 

function hasScrolled() { 
    var st = wrap.scrollTop(); // Make sure they scroll more than delta 


    if(Math.abs(lastScrollTop - st) <= delta) return; 
    // If they scrolled down and are past the navbar, add class .nav-up. 
    // This is necessary so you never see what is "behind" the navbar. 

    if (st > lastScrollTop && st > navbarHeight){ // Scroll Down
        if(st > 40){ 
            $('nav.header').css("position","fixed");
            $('nav.header').removeClass('nav-down').addClass('nav-up'); 
        }
    } else { // Scroll Up 
        if(st <= 40){
            if(st + wrap.height() < $(document).height()) { 
                $('nav.header').css("position","absolute");
                $('nav.header').removeClass('nav-up').addClass('nav-down'); 
            } 
        }
    } 
    
    lastScrollTop = st; 
}

function jwhasScrolled(){
    var st = wrap.scrollTop();

    if(Math.abs(lastScrollTop - st) >= 40){
        if (st > lastScrollTop && st > navbarHeight){
            $('nav.header').css("transition","none");
            if(Math.abs(lastScrollTop - st) >= 40){
                $('nav.header').css("position","fixed");
				$('nav.header').css("top","-40px");
				$('.sub-header').css("position","fixed");
				$('.sub-header').css("top","0px");
            }else{
                $('nav.header').css("position","absolute");
				$('nav.header').css("top",lastScrollTop - 40);
				$('.sub-header').css("position","absolute");
				$('.sub-header').css("top",lastScrollTop - 80);
            }
        }else{
            $('nav.header').css("transition","top ease 0.1s");
            if(Math.abs(lastScrollTop - st) >= 40){
                $('nav.header').css("position","fixed");
				$('nav.header').css("top", "0");
				$('.sub-header').css("position","fixed");
				$('.sub-header').css("top","40px");
            }else{
                $('nav.header').css("position","absolute");
				$('nav.header').css("top",lastScrollTop - 40);
				$('.sub-header').css("position","absolute");
				$('.sub-header').css("top",lastScrollTop - 80);
            }
        }
    }
    lastScrollTop = st; 
}
//???????????????
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
});
$('#email_certify_btn').click(function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var email = $('input[name="email"]').val();
    
    if(email == ''){
        alert('????????? ????????? ?????????????????????');
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
                           alert('?????? ????????? ????????? ?????? ?????????.');
                           $('input[name="email"]').val('');
                       }else{
                           if(confirm('?????? ????????? ????????? ???????????????. ?????????????????????????')){
                               $('input[name="email"]').attr("readonly","readonly");
                               $('#email_certify').val(1);
                               $('#email_certify_btn').addClass('active');
                               $('#email_certify_btn').text('????????????');
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
        alert('???????????? ?????????????????????');
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
                           alert('?????? ???????????? ????????? ?????????. ?????? ???????????? ???????????? ?????????.');
                           $('input[name="nickname"]').val('');
                       }else{
                           if(confirm('?????? ????????? ????????? ?????????. ?????????????????????????')){
                               $('input[name="nickname"]').attr("readonly","readonly");
                               $('#nickname_certify').val(1);
                               $('#nickname_certify_btn').addClass('active');
                               $('#nickname_certify_btn').text('????????????');
                           }
                       }     	
               }
               });
         }
    }
    
});

function address_copy() { //????????????
	window.getSelection().removeAllRanges();
	
	var urlbox = document.querySelector("#address_copy");
	urlbox.focus();
	urlbox.select();
	
	var range = document.createRange();
	range.selectNode(urlbox);
	window.getSelection().addRange(range);
	
	try {
		var copy_success = document.execCommand('copy');
		alert("????????? ?????????????????????.");
	}catch (err) {
		alert("??????????????? ?????????????????????.");
	}
	
	window.getSelection().removeAllRanges();
}

function address_amount_maximum() { //????????? ?????????
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

function address_calcul_total(){ //????????? ?????? ??? ??? ?????? ??????
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

function address_send(){ // ?????? ?????????
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var address = $('#address_send').val();
	var amount = parseFloat($('#address_send_amount').val());
	var total = parseFloat($('#address_send_total').text());
	
	if($.trim(address) == ''){
		alert('???????????? ???????????????.');
	}else{
		$.ajax({
			url : "/address/send",
			type : "POST",
			data : {_token : CSRF_TOKEN, address : address, amount : amount, total : total},
			dataType : "JSON"
		}).done(function(data) {
			refresh_transactions();
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

function address_valid(){ //?????? ????????? ??????
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var address_send = $('#address_send').val();
	if($.trim(address_send) == ''){
		alert('???????????? ???????????????.');
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
				$('#address_valid_btn').text('??????');
				alert("????????? ???????????????.");
			}else{
				alert("???????????? ?????? ???????????????.");
			}
		}).error(function(){
			console.log("error");
		});
	} 

}

function address_valid_btn_offset(){ //?????? ??????
	$('#address_send').attr("disabled",false);
	$('#address_valid_btn').attr("onclick","address_valid();");
	$('#address_valid_btn').text('????????????');
}

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function refresh_transactions(){
	var list = $('#transactions_list');

	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url : "/address/refresh",
		type : "POST",
		data : {_token : CSRF_TOKEN},
		dataType : "JSON"
	}).done(function(data) {
		list.empty();
		appendCoinDepositItems(list, data);
	}).error(function(){
		console.log("error");
	});
}

$('#wrap:has(#scroll-content):has(#transactions_list)').on('touchmove', function() { //transactions infinity scroll event
	if($('#wrap').scrollTop() + $('#wrap').height() > $('#scroll-content').height()) {
		var list = $('#transactions_list');

		if(list.data('isLoading')) {
			return;
		}
		list.data('isLoading', true);

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var page = list.children().length;
		$.ajax({
			url : "/address/infinity",
			type : "POST",
			data : {_token : CSRF_TOKEN, page : page},
			dataType : "JSON"
		}).done(function(data) {
			appendCoinDepositItems(list, data);
		}).error(function(){
			console.log("error");
		}).always(function(){
			$('#transactions_list').data('isLoading', false);
		});
	}
});

function appendCoinDepositItems(list, data) {
	data.forEach(function(item){
		var template = $($('#coin-deposit-item').html());
		template.find('.date').text(item.updated.split(' ')[0]);

		if(item.request_type === 'deposit') {
			template.find('.type').text('??????');
			template.find('.status').addClass('inend');

			if(item.request_status === 'sell') {
				template.find('.status').text('??????');
			} else if(item.request_status === 'refund') {
				template.find('.status').text('??????');
			} else if(item.request_status === 'batting') {
				template.find('.status').text('??????');
			} else if(item.request_status === 'reward') {
				template.find('.status').text('??????');
			} else {
				if(item.request_status === 'deposit_confirmed') {
					template.find('.status').text('????????????');
				} else {
					template.find('.status').text('????????????');
				}
			}
		} else if(item.request_type === 'withdraw') {
			template.find('.type').text('??????');
			template.find('.status').addClass('cend');

			if(item.request_status === 'buy') {
				template.find('.status').text('??????');
			} else if(item.request_status === 'batting') {
				template.find('.status').text('??????');
			} else if(item.request_status === 'fee') {
				template.find('.status').text('?????????');
			} else {
				if(item.request_status === 'withdraw_confirmed') {
					template.find('.status').text('????????????');
				} else {
					template.find('.status').text('????????????');
				}
			}
		}

		template.find('.amount').text(item.request_amount);
		template.find('.address').text(item.request_address);
		template.find('.txid').text(item.confirm_tx || '');
		template.appendTo(list);
	});
}