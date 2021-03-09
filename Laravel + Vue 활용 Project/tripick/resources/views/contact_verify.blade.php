@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--sign-up wrapper--sign-up--02">

	<div class="hd-title hd-title--01">
		<h2 class="hd-title__center">휴대폰 인증</h2>
	</div>

	<div class="wrapper--inner">

		<fieldset class="sign-up-fieldset sign-up-fieldset--phone">
			<ul class="sign-up-fieldset__group">
				<li class="sign-up-fieldset__list">
					<input type="tel" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input"
					placeholder="휴대폰 번호 입력" id="number">
					<button type="button" class="button sign-up-fieldset--phone__button button--disable is-active" id="send_msg_btn">
						인증번호 받기
					</button>
				</li>
				<li class="sign-up-fieldset__list">
					<input type="tel" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input"
					placeholder="인증번호 입력" id="verify_code">
				</li>
			</ul>
		</fieldset>
		<p class="sign-up-fieldset--phone__certify">
			<span class="sign-up-fieldset--phone__certify__count">00:59</span>
			<span class="sign-up-fieldset--phone__certify__caution">내에 인증번호를 입력해주세요.</span>
		</p>

	</div>

	<div class="button-bt-fixed">
		<button type="button" class="button button--disable" id="complete_btn">
			완료
		</button>
		<button class="nextbtn" onclick="location.href='/af_home';">
			다음에 하기 >
		</button>
	</div>
</div>
@endsection
@section('script')
<script>
	$('#verify_code').keyup(function(){
		if($(this).val().length > 4){
			$('#complete_btn').addClass('is-active');
		}else{
			$('#complete_btn').removeClass('is-active');
		}
	});
	$('#send_msg_btn').click(function(){
		var number = $('#number').val();
		var params = {
			'user_contact' : number
		}
		$.ajax({
			url:'/api/Users/msg_verify',
			data: params,
			type: 'PUT',
			dataType : 'json'
		}).done(function(res){
			if(res.state == 1 && res.query == 1){
				//$('#number').attr('disabled','disabled');
				dialog.alert({
	                title:'안내',  
	                message: "메세지를 전송했습니다. 인증번호를 확인해주세요",
	                button: "확인"
	            });
			}else{
				dialog.alert({
	                title:'안내',  
	                message: "메세지 전송 실패",
	                button: "확인"
	            });
			}
		})
	});
	$('#complete_btn').click(function(){
		if($(this).hasClass('is-active')){
			var params = {
				'verify_code' : $('#verify_code').val()
			}
			$.ajax({
				url:'/api/Users/msg_verify_check',
				data: params,
				type: 'PUT',
				dataType : 'json'
			}).done(function(res){
				if(res.query == 1 && res.state == 1){
					dialog.alert({
		                title:'안내',  
		                message: "인증이 완료되었습니다.",
		                button: "확인",
		                callback: function(value){
	                        if(value){
	                            location.href='/af_home';
	                        }
	                    }
		            });
				}else{
					dialog.alert({
		                title:'안내',  
		                message: "인증번호를 다시확인해 주세요",
		                button: "확인"
		            });
				}
			}).fail(function(xhr, status, errorThrown) {
				dialog.alert({
	                title:'안내',  
	                message: "서버 통신 실패",
	                button: "확인"
	            });
			})
		}else{
			dialog.alert({
                title:'안내',  
                message: "인증번호를 제대로 입력해주세요",
                button: "확인"
            });
		}
		
	});
</script>
<style lang="scss">
	.button-bt-fixed {
		text-align: center;
	}
	.nextbtn {
		padding: 0.5em 1em;
		margin-top: 1em;
		font-size: 1.2em;
		background: transparent;
		border: none;
	}
</style>
@endsection