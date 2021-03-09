@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
		<h1 class="logo">
            <a href="/?country=kr">
				<img src="{{ asset('/storage/image/homepage/spowide_header_logo.png')}}" style="    width: 106px;" alt="logo"/>
            </a>
        </h1>
    </div>
</div>

<div class="auth_wrap find_pw_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">{{ __('login.find_password') }}</h1>

		<div class="auth_form_group find_pw_group">

			<form method="POST" action="{{ route('password.email') }}" id="password_for_reset">
				@csrf

				<div class="form-group">

					<span class="ment mt-3 mb-3">{!! __('login.join_login_sentence10') !!}</span>
					<div class="auth_input_line">
						<input id="email" placeholder="{{ __('login.email_id') }}"  type="email" class="auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

						@if ($errors->has('email'))
						<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('email') }}</strong> </span>
						@endif
					</div>
				</div>

				<div class="form-group mb-0">

					<div class="both_btn_group">

						<button type="button" class="btn_style cancel_btn" onclick="location.href='/'">
						{{ __('login.cancel') }}
						</button>
						<button type="submit" class="btn_line_style">
						{{ __('login.email_check') }}
						</button>


					</div>

				</div>
			</form>
			<div>
                <h5 style="margin: 36px 0;font-size: 17px;font-weight: bold;color: red;">비밀번호 찾기 했는데도 메일이 안온다?</h5>
                <div style="font-size: 13px;text-align: left;">
                    <ul style="margin: 20px 10px;line-height: 1.5;">
                        <li>- 비밀번호 찾기를 했는데 메일이 오지 않는 분들</li>
                        <li>- 이메일주소가 오류 또는 허위로 가입하신분들</li>
                        <li>- 비밀번호를 모르시는분들</li>
                    </ul>

                    <ol style="margin: 20px 10px;line-height: 1.5;">
                        <li>1. 스포와이드 공식 텔레그램: <a href="https://t.me/Spowide" target="_blank">https://t.me/Spowide</a> </li>
                        <li>2. 스포와이드 공식 카카오톡 ID: spowide </li>
                        <li>3. 스포와이드 공식 메일: cs@spowide.com </li>
                    </ol>
					<div style="text-align: center;">
						<p style="margin: 40px 0;">셋중에 한가지 선택하셔서</p>

						<p style="margin: 30px 0;line-height: 2;">
						본인 성함<br>
						휴대폰 번호<br>
						기존 이메일 주소<br>
						적어서 보내주세요<br>
						</p>

						<p>임시 비밀번호를 발송해드리겠습니다.</p>
					</div>
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

<script>

	$(document).ready(function(){
		@if (session('status'))
			swal({
				title: '{{ __('message.mail_send') }}',
				text: '{{ __('message.confirm_your_email') }}',
				icon: "success",
				button: '{{ __('message.ok') }}',
			});
		@endif

		@if ($errors->has('email'))
			swal({
				text: '존재하지 않는 이메일입니다.',
				icon: "warning",
				button: '{{ __('message.ok') }}',
			});
		@endif
	})

	$('#password_for_reset').submit(function(){
		$('.overlay').show();
		$('.sending_progress_wrap').show();

		return true;
	})
</script>

@endsection
