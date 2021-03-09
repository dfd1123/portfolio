@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="auth_wrap">

	<div class="auth_panel">

		<h1 class="card_title pb-3 mb-5">LOGIN</h1>

		<div class="auth_form_group">

			<form method="POST" action="{{ route('login') }}">
				
				@csrf

				<div class="form-group">

					<div class="auth_input_line">

						<input id="email" type="email" class="auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-Mail Address" name="email" value="{{ old('email') }}" required autofocus>

					</div>

				</div>

				<div class="form-group">

					<div class="auth_input_line">

						<input id="password" type="password" class="auth_input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" name="password" required>

					</div>

				</div>

				<div class="form-group">

					<button type="submit" class="btn_style">
						login
					</button>

				</div>

				@if (Route::has('password.request'))
				<div class="text-right btn_group">
					<a class="btn btn-link" href="{{ route('register_agree') }}"> JOIN </a>
					<a class="btn btn-link" href="{{ route('password.request') }}"> Find Password </a>
				</div>
				@endif

			</form>

		</div>

	</div>

</div>


	<script>
		$(document).ready(function(){
			@if ($errors->has('email'))

				swal({
					title: '{{ __('message.login_fail') }}',
					text: '{{ __('message.login_fail_reson') }}',
					icon: "warning",
					button: '{{ __('message.ok') }}',
				});

			@endif
		});
	</script>

@endsection
