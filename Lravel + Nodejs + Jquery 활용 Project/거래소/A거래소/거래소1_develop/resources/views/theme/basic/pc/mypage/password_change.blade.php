@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')

<div class="mypage_wrap">

    <div class="mypage_inner">

		<form method="post" action="{{route('mypage.password_change_update')}}">

			@csrf

			<div class="form-group mb-4">
			<label for="current_password" class="info_change_tit mb-2">{{ __('myp.now_password') }}</label>
				<div class="certifi_form_group">
					<input id="current_password" type="password" class="auth_input form-control" name="password" required>
				</div>
			</div>

			<div class="form-group mb-4">
			<label for="new_password" class="info_change_tit mb-2">{{ __('myp.change_password') }}</label>
				<div class="certifi_form_group">
					<input id="new_password" type="password" class="auth_input form-control" required>
				</div>
			</div>

			<!--비밀번호와 비밀번호 확인 일치여부 확인-->
			<span class="ment register_pw_alert">
				<span class="pw_no hide">{{ __('myp.wrong_password') }}</span>
				<span class="pw_yes hide">{{ __('myp.right_password') }}</span>
			</span>
			<!--//비밀번호와 비밀번호 확인 일치여부 확인-->

			<div class="form-group mb-4">
			<label for="confirm_password" class="info_change_tit mb-2">{{ __('myp.password_check') }}</label>
				<div class="certifi_form_group">
					<input id="confirm_password" type="password" class="auth_input form-control" name="cpassword" required>
				</div>
			</div>
			<div class="form-group mt-4">
				<button id="btn_password_change" class="btn_style gradient_btn mt-3">
					{{ __('myp.change_password') }}
				</button>
			</div>
		</form>

        </div>

    </div>

</div>

<script>
	if (typeof __ === 'undefined') { var __ = {}; }
	__.myp = {
		@foreach(__('myp') as $key => $value)
			'{{$key}}':'{{$value}}',
		@endforeach
	}
</script>

@endsection