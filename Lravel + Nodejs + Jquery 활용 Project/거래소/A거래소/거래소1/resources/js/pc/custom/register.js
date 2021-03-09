


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



$('#username_certify').click(function(){
	var username = $('input[name="username"]').val();
	if(username == ''){
		swal({
			text: __.message.please_input_username,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}
	
	if($(this).hasClass('active') && $('input[name="username_cv"]').val() == 1){
		swal({
			text: __.message.already_confirm_username,
			icon: "warning",
			button: __.message.ok,
		});
	}else{
		$.ajax({
            url : "/username/duplicate",
            type : "POST",
            data: {_token: CSRF_TOKEN, username: username},
            dataType: 'JSON',
            success : function(data) {
                if(data.yn){
					swal({
						text: __.message.already_exist_username,
						icon: "warning",
						button: __.message.ok,
					});
					$('input[name="username"]').val('');
				}else{
					swal({
						text: __.message.possible_username,
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
							$('input[name="username_cv"]').val(1);
							$('#username_certify').addClass('active');
							$('#username_certify').html('<i class="fal fa-check"></i>'+__.message.possible_use);
							$('input[name="username"]').attr('readonly','readonly');
						}else{
							$('input[name="username"]').val('');
						}
					});
				}
            }
        });
	}
});

$('#register_form').submit(function(){
	var email_cv = $('input[name="email_cv"]').val();
	var country = $('select[name="country"]').val();

	if(email_cv != 1){
		swal({
			text: __.message.not_certify_email,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}

	if(country == ''){
		swal({
			text: __.message.please_select_country,
			icon: "warning",
			button: __.message.ok,
		});

		return false;
	}

});



function chkPwd(str){

	var reg_pwd = /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;
   
	if(!reg_pwd.test(str)){
		
		swal({
			text: '영문, 숫자를 혼합하여\n8자리 ~ 20자리 이내로 입력해주세요.',
			icon: "warning",
			button: __.message.ok,
		});

		return false;
   
	}
   
	return true;
   
}

