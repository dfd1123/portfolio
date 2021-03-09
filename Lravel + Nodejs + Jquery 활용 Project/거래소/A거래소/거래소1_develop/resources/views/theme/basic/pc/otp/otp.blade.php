@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="auth_wrap">

	<div class="auth_panel">

	<h1 class="card_title pb-3 mb-5">{{ __('otp.otp') }}</h1>

		<div class="auth_form_group">

			<form method="POST" action="{{ route('login') }}">
				@csrf
				
				@if(isset($user['email']) && isset($user['password']))
				<input type="hidden" name="push_token" value="{{ $user['push_token'] }}">
				<input type="hidden" name="email" value="{{ $user['email'] }}" />
				<input type="hidden" name="password" value="{{ $user['password'] }}" />
				@else
				<script>
					swal({
						text: "올바르지 않은 경로로 접근하셨습니다.",
						icon: "error",
						button: '{{ __('message.ok') }}',
					}).then(function(){
						location.href = '/login';
					});
				</script>
				@endif
				<p class="ment">
				{{ __('otp.otpn') }}
				</p>

				<div class="form-group mt-4">
					<div class="reg_inp">
						<div>
							<input type="text" name="secret" class="auth_input form-control" placeholder="otp 6자리를 입력해주세요." required autofocus />
						</div>
					</div>
					<button type="submit" class="btn_style">
						login
					</button>

				</div>

			</form>

		</div>

	</div>

</div>

@endsection