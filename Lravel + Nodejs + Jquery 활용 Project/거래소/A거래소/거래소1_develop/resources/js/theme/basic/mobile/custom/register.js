var market_type = $('meta[name="market_kind"]').attr('content');

$('input[name="market_type"]').val(market_type);

$('#register_agree').submit(function(){
	var register_agree1 = $('input[name="register_agree1"]').is(":checked");
	var register_agree2 = $('input[name="register_agree2"]').is(":checked");
	var register_agree3 = $('input[name="register_agree3"]').is(":checked");

	if(register_agree1 && register_agree2){
		$('input[name="register_agree1"]').val(1);
		$('input[name="register_agree2"]').val(1);
		if(register_agree3){
			$('input[name="register_agree3"]').val(1);
		}
		return true;
	}else{
		swal({
			title: __.message.register,
			text: __.message.register_agree_ments,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
})





$('#email_certify').click(function(){
	var email = $('input[name="email"]').val();
	if(email == ''){

		swal({
			text: __.message.not_email_addr_input,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
	
	if($(this).hasClass('active') && $('input[name="email_cv"]').val() == 1){
		swal({
			text: __.message.already_confirm_email,
			icon: "warning",
			button: __.message.ok,
		});
	}else{
		$.ajax({
            url : "/email/duplicate",
            type : "POST",
            data: {_token: CSRF_TOKEN, email: email},
            dataType: 'JSON',
            success : function(data) {
                if(data.yn){
					swal({
						text: __.message.already_exist_email,
						icon: "warning",
						button: __.message.ok,
					});
					$('input[name="email"]').val('');
				}else{
					swal({
						text: __.message.possible_email_addr,
						icon: "success",
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
							$('input[name="email_cv"]').val(1);
							$('#email_certify').addClass('active');
							$('#email_certify').html('<i class="fal fa-check"></i>'+__.message.possible_use);
							$('input[name="email"]').attr('readonly','readonly');
						}else{
							$('input[name="email"]').val('');
						}
					});
				}
            }
        });
	}
});

$('#password').keyup(function(){
	var password_confirm = $('#password-confirm').val();
	var password = $(this).val();

	if(password =='' || password_confirm == ''){
		$('.register_pw_alert .pw_yes').addClass('hide');
		$('.register_pw_alert .pw_no').addClass('hide');
		return false;
	}

	if(password == password_confirm){
		$('.register_pw_alert .pw_yes').removeClass('hide');
		$('.register_pw_alert .pw_no').addClass('hide');
	}else{
		$('.register_pw_alert .pw_no').removeClass('hide');
		$('.register_pw_alert .pw_yes').addClass('hide');
	}
});

$('#password-confirm').keyup(function(){
	var password_confirm = $(this).val();
	var password = $('#password').val();

	if(password =='' || password_confirm == ''){
		$('.register_pw_alert .pw_yes').addClass('hide');
		$('.register_pw_alert .pw_no').addClass('hide');
		return false;
	}

	if(password == password_confirm){
		$('.register_pw_alert .pw_yes').removeClass('hide');
		$('.register_pw_alert .pw_no').addClass('hide');
	}else{
		$('.register_pw_alert .pw_no').removeClass('hide');
		$('.register_pw_alert .pw_yes').addClass('hide');
	}
});

$('.register_auth_wrap #password').change(function(){
	if(!chkPwd( $.trim($('#password').val()))){
		$('#password').val('');
		$('mpassword').focus();
	 
		return false;
	}
});

$('#nickname_certify').click(function(){
	var nickname = $('input[name="nickname"]').val();
	if(nickname == ''){
		swal({
			text: "닉네임을 입력하세요.",
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
	
	if($(this).hasClass('active') && $('input[name="nickname_cv"]').val() == 1){
		swal({
			text: "이미 닉네임 중복검사를 실행 하셨습니다.",
			icon: "warning",
			button: __.message.ok,
		});
	}else{
		$.ajax({
            url : "/duplicate/nickname/"+nickname+"",
            type : "GET",
            data: {_token: CSRF_TOKEN},
            dataType: 'JSON',
            success : function(data) {
                if(data.duplicate){
					swal({
						text: "이미 존재하는 닉네임 입니다.",
						icon: "warning",
						button: __.message.ok,
					});
					$('input[name="nickname"]').val('');
				}else{
					swal({
						text: "사용 가능한 닉네임 입니다.\n사용 하시겠습니까?",
						icon: "success",
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
							$('input[name="nickname_cv"]').val(1);
							$('#nickname_certify').addClass('active');
							$('#nickname_certify').html('<i class="fal fa-check"></i>'+__.message.possible_use);
							$('input[name="nickname"]').attr('readonly','readonly');
						}else{
							$('input[name="nickname"]').val('');
						}
					});
				}
            }
        });
	}
});


$('#mobile_number_certify').click(function(){
	var mobile_number = $('input[name="mobile_number"]').val();
	if(mobile_number == ''){
		swal({
			text: "휴대폰 번호를 입력하세요.",
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
	
	if($(this).hasClass('active') && $('input[name="mobile_number_cv"]').val() == 1){
		swal({
			text: "이미 휴대폰 번호 중복검사를 실행 하셨습니다.",
			icon: "warning",
			button: __.message.ok,
		});
	}else{
		$.ajax({
            url : "/duplicate/mobile_number/"+mobile_number+"",
            type : "GET",
            data: {_token: CSRF_TOKEN},
            dataType: 'JSON',
            success : function(data) {
                if(data.duplicate){
					swal({
						text: "이미 존재하는 휴대폰 번호 입니다.",
						icon: "warning",
						button: __.message.ok,
					});
					$('input[name="mobile_number"]').val('');
				}else{
					swal({
						text: "사용 가능한 휴대폰 번호 입니다.\n사용 하시겠습니까?",
						icon: "success",
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
							$('input[name="mobile_number_cv"]').val(1);
							$('#mobile_number_certify').addClass('active');
							$('#mobile_number_certify').html('<i class="fal fa-check"></i>'+__.message.possible_use);
							$('input[name="mobile_number"]').attr('readonly','readonly');
						}else{
							$('input[name="mobile_number"]').val('');
						}
					});
				}
            }
        });
	}
});

$('#register_form').submit(function(){
	var email_cv = $('input[name="email_cv"]').val();
	var nickname_cv = $('input[name="nickname_cv"]').val();
	var mobile_number_cv = $('input[name="mobile_number_cv"]').val();
	//var country = $('select[name="country"]').val();

	if(email_cv != 1){
		swal({
			text: __.message.not_certify_email,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}

	if(nickname_cv != 1){
		swal({
			text: "닉네임 중복검사를 하지 않으셨습니다.",
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}

	if(mobile_number_cv != 1){
		swal({
			text: "휴대폰 번호 중복검사를 하지 않으셨습니다.",
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
	/*
	if(country == ''){
		swal({
			text: __.message.please_select_country,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
	*/
});



function chkPwd(str){

	var reg_pwd = /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;
   
	if(!reg_pwd.test(str)){
		
		swal({
			text: __.message.en,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
   
	}
   
	return true;
   
}



