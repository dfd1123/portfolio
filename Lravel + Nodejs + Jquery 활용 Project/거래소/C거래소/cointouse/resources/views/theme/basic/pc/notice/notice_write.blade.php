@extends(session('theme').'.pc.layouts.app')

@section('content')

@guest
<form method="post" action="">

	@csrf
	<div class="main_login_group main_login_before_group">
		<div class="main_login_form login_form_first">
			<input class="main_login-input" type="email" name="email"  value="{{ old('email') }}" required autofocus placeholder="이메일 주소"/>
			<br>
			<input class="main_login-input" type="password" name="password" name="password" placeholder="비밀번호" required />
			<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="display: none;">
		</div>
		<div class="main_login_form ">
			<button class="main_login_form-btn login_btn" type="submit">
			{{ __('support.login') }}
			</button>
			<a class="main_login_form-btn" href="{{ route('register_agree') }}">{{ __('support.join') }}</a>
			<a class="main_login_form-btn" href="{{ route('password.request') }}">{{ __('support.find') }}</a>
		</div>
	</div>
</form>
@else
<div class="main_login_group main_login_after_group">
	<a class="login_after-btn main_login_form-btn" href="{{ route('my_asset.index') }}"><i class="fas fa-wallet"></i> {{ __('support.my') }}</a>
	<a class="login_after-btn main_login_form-btn" href="{{ route('security_lv.index') }}"><i class="far fa-id-badge"></i> {{ __('support.sec') }}</a>
	<a class="login_after-btn main_login_form-btn" href="{{ route('password.request') }}"><i class="fas fa-user-lock"></i> {{ __('support.change') }}</a>
	<a class="login_after-btn main_login_form-btn" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-unlock-alt"></i> 로그아웃</a>
</div>
@endguest

@endsection
