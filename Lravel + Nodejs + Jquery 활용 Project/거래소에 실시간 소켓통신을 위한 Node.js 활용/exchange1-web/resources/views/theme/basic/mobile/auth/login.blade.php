@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
        <h1 class="logo">
            <a href="{{ url('/?country='.config('app.country')) }}">
                <!--img src="{{ asset('/storage/image/homepage/sharebits-logo-dark.svg')}}" alt="logo"/-->
            </a>
        </h1>
    </div>
</div>

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
						Login
					</button>

				</div>

				@if (Route::has('password.request'))
				<div class="text-right btn_group">
					<a class="btn btn-link point_clr_1" href="{{ route('register_agree') }}"> {{ __('login.join') }} </a>
					<a class="btn btn-link" href="{{ route('password.request') }}">{{ __('login.find') }} </a>
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
