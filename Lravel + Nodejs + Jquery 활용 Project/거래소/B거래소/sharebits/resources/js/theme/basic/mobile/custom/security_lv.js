var min;
var sec;
var timer;
var timer2;

$('#existing_user_sms_certify').click(function(){
	var country = $('select[name="country"]').val();
	var mobile_number = $('input[name="mobile_number"]').val();
	var duplicate_confirm = $('input[name="duplicate_confirm"]').val();
	
	if(country == '' || (country == null)){
		swal({
			text: __.message.please_select_country,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}

	if(mobile_number == ''){
		swal({
			text: __.message.please_input_mobile,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}

	$.ajax({
		url : "/mobile/existing_user_verify",
		type : "POST",
		data: {_token: CSRF_TOKEN, mobile_number: mobile_number, country: country, duplicate_confirm: duplicate_confirm},
		dataType: 'JSON',
		success : function(data) {
			if(data.yn){
				alertify.success(__.message.send_sms_certify_code);
				clearInterval(timer);
				$('#sms_certify').text(__.message.resend);
				min = 3;
				sec = (min*60)%60;
				$('#mobile_number_code').attr('readonly',false);
				timer = setInterval("security_lv_start_timer()",1000);
			}else{
				swal({
					text: __.message.already_exist_mobile,
					icon: "warning",
					button: __.message.ok,
				});
			}
		}
	});
});

$('#existing_user_sms_certify_confirm').click(function(){
	var certify_code = $('input[name="mobile_certify_code"]').val();

	if(certify_code == ''){
		swal({
			text: __.message.please_input_mobile_certify,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}

	$.ajax({
		url : "/mobile/existing_user_verify/confirm",
		type : "POST",
		data: {_token: CSRF_TOKEN, certify_code: certify_code},
		dataType: 'JSON',
		success : function(data) {
			if(data.yn){
				clearInterval(timer);
				$('#existing_user_sms_certify_confirm').html('<i class="fal fa-check"></i>'+__.message.complete_certify);
				$('#existing_user_sms_certify_confirm').addClass('active');
				$('#mobile_number_code').attr('readonly','readonly');
				swal({
					text: __.message.complete_sms_certify,
					icon: "success",
					button: __.message.ok,
				}).then(function() {
					window.location.href = '/';
				});
			}else{
				swal({
					text: __.message.wrong_sms_certify,
					icon: "warning",
					button: __.message.ok,
				});
			}
		}
	});
});

function security_lv_start_timer(){

	var temp_chr1;
	var temp_chr2;

	if(sec == 0){
		sec = 59;
		min -= 1;
	}else{
		sec -= 1;
	}
	
	temp_chr1 = min.toString();
	temp_chr2 = sec.toString();
	
	if(temp_chr1.length == 1){
		temp_chr1 = '0' + temp_chr1;
	}
	
	if(temp_chr2.length == 1){
		temp_chr2 = '0' + temp_chr2;
	}
	
	$('#ViewTimer').text(temp_chr1+':'+temp_chr2);
	
	if(min==0 && sec==0){
		clearInterval(timer);
		$('#sms_certify_confirm').removeClass('active');
		$('input[name="mobile_certify_code"]').attr('readonly','readonly');
	}
}
