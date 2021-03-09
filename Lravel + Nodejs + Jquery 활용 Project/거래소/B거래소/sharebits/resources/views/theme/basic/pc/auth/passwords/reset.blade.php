@extends(session('theme').'.pc.layouts.app')

@section('content')


<div class="auth_wrap find_pw_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">Find Password</h1>

		<div class="auth_form_group find_pw_group">
			
			<!--<form method="POST" action="{{ route('password.email') }}">-->
			<form method="POST" action="{{ route('password.update') }}">
				@csrf

				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group">
					<div class="certifi_form_group">
						<label class="certify_label">{!! __('login.user_id') !!}</label>
						<input type="email" class="pad_left_120 auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" style="padding-left: 93px;" required autofocus>
					</div>
				</div>
				
				<div class="form-group">
					<div class="certifi_form_group">
						<input id="password" placeholder="{{ __('login.join_login_sentence7') }} " type="password" class="auth_input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

						@if ($errors->has('password'))
						<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('password') }}</strong> </span>
						@endif
					</div>
				</div>

				<div class="form-group">
					<div class="certifi_form_group">
						<input id="password-confirm" placeholder="{{ __('login.check_password') }}" type="password" class="auth_input form-control" name="password_confirmation" required>
					</div>
				</div>

				<div class="form-group mb-0 mt-5">
					<button type="submit" class="btn_style">
					{{ __('login.change') }}
					</button>
				</div>
				
			</form>

		</div>
		
	</div>

</div>


@endsection