@extends('pc.layouts.app')

@section('content')

<div class="sub_spot member" style="height: 278px;">
	<h2>비밀번호 찾기</h2>
</div>
<div id="container" style="margin-top: 278px;">
	<div class="loginbox" style="max-width: 500px;">
		<div class="psearch">
			<form role="form" method="POST" action="{{ route('password.email') }}" id="password_for_reset">
				{!! csrf_field() !!}

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<h3 style="font-size: 16px; padding-bottom: 20px;">가입하신 이메일 주소를 입력해주세요.</h3>
					<div class="col-md-6">
						<input type="email" id="email" name="email" tabindex="2" title="이메일주소" class="required kr" placeholder="E-mail Address">

						@if ($errors->has('email'))
						<span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span>
						@endif
					</div>
				</div>

				<div class="both_btn_group">
					<button type="submit" class="joinbt">
						확인
					</button>
					<a href="{{ route('login') }}" class="cancel_btn">취소</a>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="overlays" style="display:none;"></div>
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
<style>
	.psearch {
		display: block;
		margin: 0 auto;
		width: 100%;
		background: #efefef;
		padding: 20px;
		box-sizing: border-box;
	}
	.psearch p {
		display: block;
		text-align: center;
		padding: 10px 0;
	}

	button.joinbt {
		display: block;
		line-height: 51px;
		background: #413e70;
		border: 1px solid #413e70;
		color: #fff;
		font-size: 15px;
		width: 100%;
		border-radius: 5px;
		margin-top: 5px;
		text-align: center;
	}
	.both_btn_group button, .both_btn_group a {
		display: inline-block !important;
		vertical-align: middle;
		width: 49% !important;
	}
	.both_btn_group a.cancel_btn {
		background: #999;
		border: 1px solid #999;
		line-height: 51px;
		color: #fff;
		font-size: 15px;
		border-radius: 5px;
		margin-top: 5px;
		text-align: center;
	}
	@media screen and (max-width: 640px) {
		.psearch {
			width: 98%;
		}
	}
</style>
<script>
	@if(session('status'))
		alert('입력하신 메일 주소로 비밀번호 재설정 URL을 보내드렸습니다.\n메일함을 확인하세요.');
	@endif

	$('#password_for_reset').submit(function(){
		$('.overlays').show();
		$('.sending_progress_wrap').show();

		return true;
	})
</script>
@endsection
