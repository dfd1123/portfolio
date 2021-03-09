@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="auth_wrap find_pw_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">Find Password</h1>

		<div class="auth_form_group find_pw_group">

			<form method="POST" action="{{ route('password.email') }}" id="password_for_reset">
				@csrf

				<div class="form-group">

					<div class="auth_input_line">
						<input id="email" placeholder="E-Mail Address"  type="email" class="auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

					</div>
					<span class="ment mt-3 mb-3">{{ __('login.join_login_sentence10') }}</span>
				</div>

				<div class="form-group mb-0">

					<div class="both_btn_group">

						<button type="submit" class="btn_style">
						{{ __('login.check') }}
						</button>

						<a href="{{ url('/') }}" class="btn_style cancel_btn">
						{{ __('login.cancel') }}
						</a>

					</div>

				</div>
			</form>

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
