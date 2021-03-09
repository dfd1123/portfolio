@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--sign-up wrapper--sign-up--02">
	<div class="hd-title hd-title--01" id="naver_login_header" style="display:none;">
		<button type="button" class="hd-title__left-btn hd-title__left-btn--close" onclick="location.href='/login';">
			<span
			class="none">닫기버튼</span>
		</button>
		<h2 class="hd-title__center">회원가입</h2>
	</div>

	<form id="user_form" enctype="multipart/form-data">
		<div id="naver_login_content" style="visibility:hidden;">
			<input type="hidden" name="req" value = "user">
			<input type="hidden" id="push_token" name="push_token">
			<input type="hidden" name="push_agree" value = "1">
			<input type="hidden" name="user_name" id="login_name" value = "">
			<input type="hidden" name="user_email" id="login_email" value = "">
			<input type="hidden" name="user_thumb_another" id="login_thumb_another" value = "">
			<input type="hidden" name="user_pwd" id="login_pw" value = "">
			<input type="hidden" name="reg_type" value = "naver">
			<div class="wrapper--inner">

				<fieldset class="sign-up-fieldset">
					<ul class="sign-up-fieldset__group">
						<li class="sign-up-fieldset__list">
							<input type="tel" id="sign_up_contact" name="user_contact" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input" placeholder="휴대폰 번호 입력" required>
							<button type="button" class="button sign-up-fieldset--phone__button button--disable is-active" onclick ="alert('구현중입니다. 휴대폰 인증은 무시하고 진행하세요.');">
								인증번호 받기
							</button>
						</li>
						<!--li class="sign-up-fieldset__list">
						<input type="tel" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input"
						placeholder="인증번호 입력">
						</li-->
					</ul>
				</fieldset>
				<p class="sign-up-fieldset--phone__certify none-input">
					<span class="sign-up-fieldset--phone__certify__count">00:59</span>
					<span class="sign-up-fieldset--phone__certify__caution">내에 인증번호를 입력해주세요.</span>
				</p>

			</div>

			<div class="button-bt-fixed">
				<button type="submit" id="user_regist" class="button is-active">
					완료
				</button>
			</div>
		</div>
	</form>
</div>
@endsection

@section('script')
<script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8"></script>
<script>
	$(function() {

		var mobile_kind = getMobileOperatingSystem();
		if (mobile_kind == "Android") {
			if ( typeof window.JS != 'undefined') {

				window.JS.CallPushToken();

			}
		} else if (mobile_kind == "iOS") {
			// IOS function call
			if(typeof webkit !== 'undefined'){
				webkit.messageHandlers.CallPushToken.postMessage("push_token"); 
			}
		}

		var naverLogin = new naver.LoginWithNaverId({
			clientId : "NP1uNTROBqcXEWyUVAB_",
			callbackUrl : "https://xn--oy2b117blyb.com/register/naverlogin",
			isPopup : false
		});

		naverLogin.init();
			naverLogin.getLoginStatus(function(status) {
				if (status) {
					/* (5) 필수적으로 받아야하는 프로필 정보가 있다면 callback처리 시점에 체크 */
					var email = naverLogin.user.getEmail();
					var name = naverLogin.user.getNickName();
					var profileImage = naverLogin.user.getProfileImage();
					var uniqId = naverLogin.user.getId();

					if (email == undefined || email == null) {
						alert("이메일은 필수정보입니다. 정보제공을 동의해주세요.");
						naverLogin.reprompt();
						return;
					}
					if (name == undefined || name == null) {
						alert("이름은 필수정보입니다. 정보제공을 동의해주세요.");
						naverLogin.reprompt();
						return;
					}
					if (profileImage == undefined || profileImage == null) {
						alert("프로필사진은 필수정보입니다. 정보제공을 동의해주세요.");
						naverLogin.reprompt();
						return;
					}
					var param = {
						'user_email' : email,
						'uniqid' : uniqId
					};
					$.ajax({
						method : "POST",
						data : param,
						dataType : 'json',
						url : '/api/check_naver',
						success : function(data) {
							console.log(data);
							if (data == 'error') {
								dialog.alert({
									title : '로그인',
									message : '알수없는 오류가 발생했습니다. 다시 시도해 주세요.',
									button : "확인"
								});
							} else if (data == 'not-naver') {
								dialog.alert({
									title : '오류',
									message : '해당 네이버 이메일과 동일한 이메일이 회원가입 되어 있습니다.<br>일반 로그인하시거나 TRIPICK에 문의해주세요.',
									button : "확인",
									callback : function(value) {
										location.href = '/login';
									}
								});
							} else if (data == 'not-register') {
								$('#naver_login_header').css('display', 'block');
								$('#naver_login_content').css('visibility', 'visible');
								$('#login_email').val(email);
								$('#login_name').val(name);
								$('#login_thumb_another').val(profileImage);
								$('#login_pw').val(uniqId);
							} else if (data == 'success') {
								var param = {
									'email' : email,
									'password' : uniqId,
									'push_token' : $('#push_token').val()
								};
								$.ajax({
									method : "POST",
									data : param,
									dataType : 'json',
									url : '/api/login',
									success : function(data) {
										if (data.state == 1) {
											location.href = '/af_home';
										} else {
											dialog.alert({
												title : '로그인',
												message : data.msg,
												button : "확인"
											});
										}

									},
									error : function(e) {
										console.log(e);
									}
								});
							} else {
								dialog.alert({
									title : '로그인',
									message : '알수없는 오류가 발생했습니다. 다시 시도해 주세요.',
									button : "확인",
									callback: function(value){
										location.href='/login';
									}
								});
							}

						},
						error : function(e) {
							console.log(e);
							dialog.alert({
								title : '오류',
								message : '알수없는 오류가 발생했습니다. 다시 시도해 주세요.',
								button : "확인",
								callback: function(value){
									location.href='/login';
								}
							});
						}
					});
				} else {
					dialog.alert({
						title : '오류',
						message : '알수없는 오류가 발생했습니다. 다시 시도해 주세요.',
						button : "확인",
						callback: function(value){
							location.href='/login';
						}
					});
				}
			
		});

		$('#user_form').ajaxForm({
			type : "POST",
			dataType : 'json',
			url : '/api/Users',
			success : function(data) {
				console.log(data);
				if (data.state == 1 && data.query != null) {
					var param = {
						'email' : $('#login_email').val(),
						'password' : $('#login_pw').val(),
						'push_token' : $('#push_token').val()
					};
					$.ajax({
						method : "POST",
						data : param,
						dataType : 'json',
						url : '/api/login',
						success : function(data) {
							if (data.state == 1) {
								console.log(data);
								location.href = '/af_home';
							} else {
								dialog.alert({
									title : '로그인',
									message : data.msg,
									button : "확인"
								});
							}

						},
						error : function(e) {
							console.log(e);
						}
					});
				} else {
					dialog.alert({
						title : '오류',
						message : data.msg,
						button : "확인"
					});
				}
			},
			error : function(e) {
				console.log(e);
			}
		});
	});

	function getMobileOperatingSystem() {
		var userAgent = navigator.userAgent || navigator.vendor || window.opera;

		// Windows Phone must come first because its UA also contains "Android"
		if (/windows phone/i.test(userAgent)) {
			return "Windows Phone";
		}

		if (/android/i.test(userAgent)) {
			return "Android";
		}

		// iOS detection from: http://stackoverflow.com/a/9039885/177710
		if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
			return "iOS";
		}
		return "unknown";
	}

	function CallBackToken(token) {
		$('#push_token').val(token);
	}
</script>
@endsection