@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="auth_wrap register_auth_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">JOIN US</h1>

		<div class="auth_form_group">

			<form method="POST" action="{{ route('register') }}" id="register_form">
				@csrf
				<input type="hidden" name="email_cv" value="0">
				<p class="group_name mb-2">
				{!! __('login.login_info') !!}
				</p>

				<span class="ment group_ment ">{{ __('login.join_login_sentence6') }}</span>

				<div class="form-group mb-2">
					<div class="certifi_form_group">
						<select class="form-control country_slt mr-1" name="country">
							<option value="">{{__('boan.country')}}</option>
							<option value="kr">{{__('boan.kr')}}</option>
							<option value="jp">{{__('boan.jp')}}</option>
							<option value="ch">{{__('boan.ch')}}</option>
							<option value="en">{{__('boan.usa')}}</option>
						</select>
						<input id="fullname" placeholder="{{ __('login.name') }}" type="text" class="mr-1 auth_input form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname" value="{{ old('fullname') }}" required autofocus>

						@if ($errors->has('fullname'))
						<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('fullname') }}</strong> </span>
						@endif
					</div>
				</div>

				<div class="form-group mb-2">
					<div class="certifi_form_group">
						<input id="email" placeholder="{{ __('login.email_address') }}" type="email" class="certifi_form_input auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" >
						<button type="button" id="email_certify" class="btn certify_btn active">
						{{ __('login.checking') }}
						</button>

						@if ($errors->has('email'))
						<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('email') }}</strong> </span>
						@endif
					</div>
				</div>

				<div class="form-group mb-2">
					<div class="certifi_form_group">
						<input id="password" placeholder="{{ __('login.join_login_sentence7') }} " type="password" class="auth_input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

						@if ($errors->has('password'))
						<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('password') }}</strong> </span>
						@endif
					</div>
				</div>

				<div class="form-group mb-1">
					<div class="certifi_form_group">
						<input id="password-confirm" placeholder="{{ __('login.check_password') }}" type="password" class="auth_input form-control" name="password_confirmation" required>
					</div>
				</div>

				<!--비밀번호와 비밀번호 확인 일치여부 확인-->
				<span class="ment register_pw_alert">
					<span class="pw_no hide">{{ __('login.wrong_password') }}.</span>
					<span class="pw_yes hide">{{ __('login.right_password') }}.</span>
				</span>
				<!--//비밀번호와 비밀번호 확인 일치여부 확인-->

				@if($recommend)
					<div class="form-group">
						<div class="certifi_form_group">
							<input id="referral_id" type="text" class="auth_input form-control{{ $errors->has('referral_id') ? ' is-invalid' : '' }}" name="referral_id" value="{{ old('referral_id') }}" placeholder="{{__('user.recommender')}} Email" >

							@if ($errors->has('referral_id'))
							<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('referral_id') }}</strong> </span>
							@endif
						</div>
					</div>
				@endif


				<input type="hidden" name="market_type" value="" />

				<div class="form-group mt-4">

					<button type="submit" class="btn_style register_btn_st">
						OK
					</button>

				</div>

			</form>

		</div>

	</div>

</div>

@endsection
