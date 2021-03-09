@extends(session('theme').'.pc.layouts.app')

@section('content')
<div class="auth_wrap register_auth_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">JOIN US</h1>

		<div class="in_register_wrapper">

			<p class="ment">
			{!! __('login.join_login_sentence8') !!}
			</p>

			<p class="ment small_ment mt-4 mb-5">
			{!! __('login.join_login_sentence9') !!}
			</p>

			<div class="form-group">

				<button class="btn_style" onclick="window.location.href='login';">
				{{ __('login.login') }}
				</button>

			</div>

		</div>

	</div>

</div>
@endsection
