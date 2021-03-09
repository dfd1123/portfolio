@extends('layouts.app')

@section('content')
<div class="container ex_padding">
	
	<div class="background_wrap register_area">
		
		<div class="bg_img"></div>
		
		<div class="overlay_effect"></div>
		
	</div>
	
	<div class="row main_wrap">
		
		<div class="login_container">
			
			<div class="info_box">
				
				<img src="{{ asset('/image/logo/allcoin_login_logo.png') }}" alt="logo"/>
					
				<p>
					암호화폐 사용자의 코인결제 서비스,<br>올코인페이 서비스가 지원합니다.
				</p>
					
				<span><a href="{{ route('login') }}">로그인 하기</a></span>
				
			</div>
			
			<div class="panel panel-default main_panel">
				
				<div class="panel-body">
					
					<img src="{{ asset('/image/logo/allcoin_login_logo.png') }}" alt="logo" class="m_login_logo"/>
					
					<span class="text text-muted">회원가입</span>
					
					<br><br>
					<form name="form_chk" method="post">
						<input type="hidden" name="m" value="checkplusSerivce">	
						<input type="hidden" name="EncodeData" id="EncodeData" >
					</form>
					
					<form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="email_cv" value="0">
						<div class="form-group register_form_group" style="position:relative;">
							<label class="text text-muted">이메일 주소</label>
							<input id="email" type="email" class="form-control input-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
							<button type = "button" id="email_certify" class="btn btn-warning" style="position:absolute;right:55px;top:30px;">이메일 전송</button>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
							<div id="verify_div" class="hide" style="position:relative;margin-top:7px;">
								<input id="verify_code" class="form-control input-lg">
								<span id="countTimeMinute">01</span>:<span id="countTimeSecond">00</span> 후에 자동취소됩니다.
								<button type = "button" id="verify_code_submit" class="btn btn-warning" style="position:absolute;top:2px;right:-5px;">인증하기</button>
							</div>
						</div>
						<div class="form-group register_form_group">
							<label class="text text-muted">이름</label>
							<input id="name" type="text" class="form-control input-lg @error('name') is-invalid @enderror" name="fullname" value="{{ old('name') }}" onclick="fnPopup();" required readonly>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</div>
						<div class="form-group register_form_group">
							<label class="text text-muted">휴대폰 번호</label>
							<input id="mobile_number" type="text" class="form-control input-lg @error('mobile_number') is-invalid @enderror" name="mobile_number" autocomplete="mobile_number" required readonly>

                            @error('mobile_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</div>
						<div class="form-group register_form_group">
							<label class="text text-muted">비밀번호</label>
							<input id="password" type="password" class="form-control input-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</div>
						<div class="form-group register_form_group">
							<label class="text text-muted">비밀번호 확인</label>
							<input id="password-confirm" type="password" class="form-control input-lg" name="password_confirmation" required autocomplete="new-password">
						</div>
						<div class="login_btn_wrap">
							<div class="center_btn">
								<button type="submit" class="btn btn-warning" name="btc_register" id="allcoin_register_btn">회원가입</button>
							</div>
						</div>
						<div class="add_btn">
							<a href="{{ route('login') }}">이미 가입하셨나요?</a>
						</div>
					</form>			
				</div>
			</div>	
		</div>
	</div>
</div>
<div class="overlay" style="display:none;"></div>
<div class="sending_progress_wrap" style="display:none;">
	<div class="box">
		<div class="border one"></div>
		<div class="border two"></div>
		<div class="border three"></div>
		<div class="border four"></div>

		<div class="line one"></div>
		<div class="line two"></div>
		<div class="line three"></div>
	</div>
</div>

@endsection

@section('script')
	<script>
		var minute = 1;
		var second = 0;

		var check_timer = null;

		function fnPopup(){
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url : "/mobile_verify_nicecheck",
				type : "POST",
				data: {_token: CSRF_TOKEN},
				dataType: 'JSON',
				success : function(data) {
					$('#EncodeData').val(data.enc_data);
					window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
					document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
					document.form_chk.target = "popupChk";
					document.form_chk.submit();
				}
			});
		}

		function nicecheck_alert(status,messages,mobile_number,name){
			if(status == 0){
				alert(messages);
			}else{
				alert('휴대폰 인증 완료!');
				$('#name').val(name);
				$('#mobile_number').val(mobile_number);
			}
		}

		function time_decrese(){
			if((minute == 0 && second == 0) || minute < 0 ){
				minute = 3;
				second = 0;
				clearInterval(check_timer); // 타임아웃 타이머 stop
				check_timer = null;
				alert('인증 시간이 초과되었습니다. 다시 이메일 재전송 버튼을 눌러주세요.');
				$('#verify_div').addClass('hide');
				$("#countTimeMinute").html(minute);
				$("#countTimeSecond").html(second);
				
			}

			$("#countTimeMinute").html(minute);
			$("#countTimeSecond").html(second);
			
			if(second == 0){
				minute--;
				second = 59;
			}else{
				second = second - 1;
			}
		}

		$(function(){
			$('#email_certify').click(function(){
				$('.overlay').show();
				$('.sending_progress_wrap').show();

				return true;
			});
			$('#email_certify').click(function(){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var email = $('input[name="email"]').val();

				if(email == ''){
					alert('Email을 입력해 주세요.');
					return false;
				}
				if($(this).hasClass('active') && $('input[name="email_cv"]').val() == 1){
					alert('이미 이메일 인증을 마치셨습니다.');
				}else{
					$.ajax({
			            url : "/email/verify/request",
			            type : "POST",
			            data: {_token: CSRF_TOKEN, email: email},
			            dataType: 'JSON',
			            success : function(data) {
							console.log(data.error);
							if(data.error == 'already_exists'){
								alert('이미 가입한 이메일이 존재합니다.');
								$('input[name="email"]').val('');
								$('.overlay').hide();
								$('.sending_progress_wrap').hide();
							}else if(data.error == 'invalid_format'){
								alert('이메일 형식이 아닙니다.');
								$('input[name="email"]').val('');
								$('.overlay').hide();
								$('.sending_progress_wrap').hide();
							}else{
								$('.overlay').hide();
								$('.sending_progress_wrap').hide();
								alert('회원가입이 가능한 이메일입니다. '+ email +' 이메일을 열어 인증을 완료해주세요.');
								$('#email_certify').text('이메일재전송');
								minute = 3;
								second = 0;
								$("#countTimeMinute").html(minute);
								$("#countTimeSecond").html(second);
								clearInterval(check_timer);
								check_timer = null;
								$('#verify_div').removeClass('hide');
								if(check_timer === null){
									check_timer = setInterval(function(){ //인증 체크
										time_decrese();
									}, 1000);
								}
							}
			                /*if(data.yn){
								
							}else{
								alert('회원가입이 가능한 이메일입니다. 적으신 이메일을 열어 인증을 완료해주세요.');
								
								//$('input[name="email_cv"]').val(1);
								$('#email_certify').addClass('active');
								$('#email_certify').text('이메일재전송');
							}*/
			            }
			        });
				}

			});

			$('#verify_code_submit').click(function(){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var verify_code = $('#verify_code').val();

				if(verify_code == ''){
					alert('Email을 입력해 주세요.');
				}else{
					$.ajax({
			            url : "/email/verify/request",
			            type : "POST",
			            data: {_token: CSRF_TOKEN, verify_code: verify_code},
			            dataType: 'JSON',
			            success : function(data) {
							if(data){
								alert('이메일 인증에 성공하셨습니다. 다시 재설정하시려면 새로고침 해주세요.');
								$('#email').attr('readonly',true);
								$('#verify_div').addClass('hide');
								$('input[name="email_cv"]').val(1);
								$('#email_certify').remove();
								clearInterval(check_timer);
								check_timer = null;
							}else{
								alert('이메일 인증에 실패하셨습니다. 인증코드를 다시 확인해주시거나 이메일 재전송을 통해 다시 인증해주세요.');
							}
						}
					});
				}
			});
			
			
			/*$('#email').change(function(){
				if($('input[name="email_cv"]').val() == 1 ){
					alert('이메일 입력란의 변경으로 인해 다시 중복검사를 하셔야 합니다.');
					$('input[name="email_cv"]').val(0);
					$('#email_certify').removeClass('active');
					$('#email_certify').text('이메일 중복검사');
				}
			});
			$('#email_certify').click(function(){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var email = $('input[name="email"]').val();
				if(email == ''){
					alert('Email을 입력해 주세요.');
					return false;
				}
				
				if($(this).hasClass('active') && $('input[name="email_cv"]').val() == 1){
					alert('이미 이메일 인증을 마치셨습니다.');
				}else{
					$.ajax({
			            url : "/email/duplicate",
			            type : "POST",
			            data: {_token: CSRF_TOKEN, email: email},
			            dataType: 'JSON',
			            success : function(data) {
			                if(data.yn){
								alert('이미 가입한 이메일이 존재합니다.');
								$('input[name="email"]').val('');
							}else{
								alert('회원가입이 가능한 이메일입니다');
								
								$('input[name="email_cv"]').val(1);
								$('#email_certify').addClass('active');
								$('#email_certify').text('중복검사완료');
							}
			            }
			        });
				}
			});*/
		});
		
		
	</script>
@endsection