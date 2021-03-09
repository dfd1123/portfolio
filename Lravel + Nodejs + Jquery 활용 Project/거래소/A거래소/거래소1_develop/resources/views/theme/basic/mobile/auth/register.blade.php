@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
	{{ __('login.join') }}
    </div>
</div>
<div class="register_tab">
	<ul>
		<li class="active" data-kind="normal">일반 회원가입</li>
		<li data-kind="corp">기업 회원가입</li>
	</ul>
</div>

<div class="auth_wrap register_auth_wrap register_form_wrap">

	<div class="auth_panel">

		<h1 class="card_title mb-5 pb-3">{{ __('login.join') }}</h1>

		<div class="auth_form_group">

			<form method="POST" id="register_form" action="{{ route('register') }}">
				@csrf
				<input type="hidden" name="email_cv" value="0">
				<input type="hidden" name="nickname_cv" value="0">
				<input type="hidden" name="mobile_number_cv" value="0">
				<input type="hidden" name="register_type" value="1">
				<input type="hidden" name="country" value="kr">
				<div id="register_wrap">
					<input type="hidden" name="email_cv" value="0">
					<p class="group_name mb-2 mb_none">
					{!! __('login.corp_login_info') !!}
					</p>

					<!-- 일반 회원가입 타이틀 -->
					<span class="general_register_tit normal_tit">{{ __('login.general_register') }}</span>
					<!-- 기업 회원가입 타이틀 -->
					<span class="general_register_tit corp_tit" style="display:none;">{{ __('login.corp_register') }}</span>

					<span class="ment group_ment mb_ment">{!! __('login.join_login_sentence6_mb') !!}</span>

					<p class="group_name mb_block">
					{!! __('login.login_info') !!}
					</p>

					<!--국가 선택 <div class="form-group mb-2">
						<div class="certifi_form_group">
							<select class="form-control country_slt mr-1" name="country">
								<option value="">{{__('boan.country')}}</option>
								<option value="kr">{{__('boan.kr')}}</option>
								<option value="jp">{{__('boan.jp')}}</option>
								<option value="ch">{{__('boan.ch')}}</option>
								<option value="en">{{__('boan.usa')}}</option>
							</select>
						</div>
					</div> -->
					
					<div class="form-group mb-2">
						<div class="certifi_form_group">
							<input id="email" placeholder="{{ __('login.email_address') }}" type="email" class="certify_input certifi_form_input auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required  autofocus>
							
							@if ($errors->has('email'))
							<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('email') }}</strong> </span>
							@endif

							<button type="button"  id="email_certify" class="btn certify_btn active" stlye="padding: 0.375rem 0;">
								{{ __('login.checking') }}
							</button>
						</div>
						<p class="info_about_email">
						{{ __('login.email_warning') }}
						</p>
					</div>

					<div class="form-group mb-2">
						<div class="certifi_form_group input_space">
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
						<span class="pw_no hide">{{ __('login.wrong_password') }}</span>
						<span class="pw_yes hide">{{ __('login.right_password') }}</span>
					</span>
					<!--//비밀번호와 비밀번호 확인 일치여부 확인-->
					<br />
					
					<!--추천인 @if($recommend)
					<div class="form-group">
						<div class="certifi_form_group">
							<input id="referral_id" type="text" class="auth_input form-control{{ $errors->has('referral_id') ? ' is-invalid' : '' }}" name="referral_id" value="{{ old('referral_id') }}" placeholder="{{__('user.recommender')}} Email" >
							
							@if ($errors->has('referral_id'))
							<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('referral_id') }}</strong> </span>
							@endif
						</div>
					</div>
					@endif -->

					<!-- 닉네임 -->
					<div class="form-group mb-2">
						<div class="certifi_form_group">
							<input id="nickname" placeholder="{{ __('login.nickname') }}" type="text" class="certify_input auth_input form-control{{ $errors->has('nickname') ? ' is-invalid' : '' }}" name="nickname" value="{{ old('nickname') }}" required autofocus>

							@if ($errors->has('nickname'))
							<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('nickname') }}</strong> </span>
							@endif

							<button type="button"  id="nickname_certify" class="btn certify_btn active">
								{{ __('login.checking') }}
							</button>
						</div>
					</div>

					<!-- 휴대폰 번호 -->
					<div class="form-group mb-2">
						<div class="certifi_form_group phone_check_space">
							<input id="mobile_number" placeholder="{{ __('login.phone_check') }}" type="text" class="certify_input auth_input form-control{{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" name="mobile_number" value="{{ old('phone_check') }}" required autofocus>

							@if ($errors->has('mobile_number'))
							<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('mobile_number') }}</strong> </span>
							@endif

							<button type="button"  id="mobile_number_certify" class="btn certify_btn active" stlye="padding: 0.375rem 0;">
								{{ __('login.checking') }}
							</button>
						</div>
					</div>

					<div class="form-group mb-2">
						<div class="certifi_form_group">
							<input id="fullname" placeholder="{{ __('login.name') }}" type="text" class="auth_input form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname" value="{{ old('fullname') }}" required autofocus>

							@if ($errors->has('fullname'))
							<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('fullname') }}</strong> </span>
							@endif
						</div>
					</div>

					<input type="hidden" name="market_type" value="" />
					<input type="hidden" name="email_verified" value="0" />
					<input type="hidden" name="mobile_verified" value="0" />

					<div class="fixed_btn">
						<button type="submit" class="btn_style register_btn_st active">
							{{ __('login.register_success') }}
						</button>
					</div>
				</div>
				
			</form>
			
			<!-- 기업 정보 form -->
			<!-- <span class="division"></span>	 -->
					

		</div>

	</div>

</div>

<template id="normal_register">

		<input type="hidden" name="email_cv" value="0">
		<p class="group_name mb-2 mb_none">
		{!! __('login.corp_login_info') !!}
		</p>

		<!-- 일반 회원가입 타이틀 -->
		<span class="general_register_tit normal_tit">{{ __('login.general_register') }}</span>
		<!-- 기업 회원가입 타이틀 -->
		<span class="general_register_tit corp_tit" style="display:none;">{{ __('login.corp_register') }}</span>

		<span class="ment group_ment mb_ment">{!! __('login.join_login_sentence6_mb') !!}</span>

		<p class="group_name mb_block">
		{!! __('login.login_info') !!}
		</p>

		<!--국가 선택 <div class="form-group mb-2">
			<div class="certifi_form_group">
				<select class="form-control country_slt mr-1" name="country">
					<option value="">{{__('boan.country')}}</option>
					<option value="kr">{{__('boan.kr')}}</option>
					<option value="jp">{{__('boan.jp')}}</option>
					<option value="ch">{{__('boan.ch')}}</option>
					<option value="en">{{__('boan.usa')}}</option>
				</select>
			</div>
		</div> -->
		
		<div class="form-group mb-2">
			<div class="certifi_form_group">
				<input id="email" placeholder="{{ __('login.email_address') }}" type="email" class="certify_input certifi_form_input auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required  autofocus>
				
				@if ($errors->has('email'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('email') }}</strong> </span>
				@endif

				<button type="button"  id="email_certify" class="btn certify_btn active">
					{{ __('login.checking') }}
				</button>
			</div>
			<p class="info_about_email">
			{{ __('login.email_warning') }}
			</p>
		</div>

		<div class="form-group mb-2">
			<div class="certifi_form_group input_space">
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
			<span class="pw_no hide">{{ __('login.wrong_password') }}</span>
			<span class="pw_yes hide">{{ __('login.right_password') }}</span>
		</span>
		<!--//비밀번호와 비밀번호 확인 일치여부 확인-->
		<br />
		
		<!--추천인 @if($recommend)
		<div class="form-group">
			<div class="certifi_form_group">
				<input id="referral_id" type="text" class="auth_input form-control{{ $errors->has('referral_id') ? ' is-invalid' : '' }}" name="referral_id" value="{{ old('referral_id') }}" placeholder="{{__('user.recommender')}} Email" >
				
				@if ($errors->has('referral_id'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('referral_id') }}</strong> </span>
				@endif
			</div>
		</div>
		@endif -->

		<!-- 닉네임 -->
		<div class="form-group mb-2">
			<div class="certifi_form_group">
				<input id="nickname" placeholder="{{ __('login.nickname') }}" type="text" class="certify_input auth_input form-control{{ $errors->has('nickname') ? ' is-invalid' : '' }}" name="nickname" value="{{ old('nickname') }}" required autofocus>

				@if ($errors->has('nickname'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('nickname') }}</strong> </span>
				@endif

				<button type="button"  id="nickname_certify" class="btn certify_btn active">
					{{ __('login.checking') }}
				</button>
			</div>
		</div>

		<!-- 휴대폰 번호 -->
		<div class="form-group mb-2">
			<div class="certifi_form_group phone_check_space">
				<input id="mobile_number" placeholder="{{ __('login.phone_check') }}" type="text" class="certify_input auth_input form-control{{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" name="mobile_number" value="{{ old('mobile_number') }}" required autofocus>

				@if ($errors->has('mobile_number'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('mobile_number') }}</strong> </span>
				@endif

				<button type="button"  id="mobile_number_certify" class="btn certify_btn active">
					{{ __('login.checking') }}
				</button>
			</div>
		</div>

		<div class="form-group mb-2">
			<div class="certifi_form_group">
				<input id="fullname" placeholder="{{ __('login.name') }}" type="text" class="auth_input form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname" value="{{ old('fullname') }}" required autofocus>

				@if ($errors->has('fullname'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('fullname') }}</strong> </span>
				@endif
			</div>
		</div>

		<input type="hidden" name="market_type" value="" />
		<input type="hidden" name="email_verified" value="0" />
		<input type="hidden" name="mobile_verified" value="0" />

		<div class="fixed_btn">
			<button type="submit" class="btn_style register_btn_st active">
				{{ __('login.register_success') }}
			</button>
		</div>
</template>

<template id="corp_register">

		<input type="hidden" name="email_cv" value="0">
		<p class="group_name mb-2 mb_none">
		{!! __('login.corp_login_info') !!}
		</p>

		<!-- 일반 회원가입 타이틀 -->
		<span class="general_register_tit normal_tit">{{ __('login.general_register') }}</span>
		<!-- 기업 회원가입 타이틀 -->
		<span class="general_register_tit corp_tit" style="display:none;">{{ __('login.corp_register') }}</span>

		<span class="ment group_ment mb_ment">{!! __('login.join_login_sentence6_mb') !!}</span>

		<p class="group_name mb_block">
		{!! __('login.login_info') !!}
		</p>

		<!--국가 선택 <div class="form-group mb-2">
			<div class="certifi_form_group">
				<select class="form-control country_slt mr-1" name="country">
					<option value="">{{__('boan.country')}}</option>
					<option value="kr">{{__('boan.kr')}}</option>
					<option value="jp">{{__('boan.jp')}}</option>
					<option value="ch">{{__('boan.ch')}}</option>
					<option value="en">{{__('boan.usa')}}</option>
				</select>
			</div>
		</div> -->
		
		<div class="form-group mb-2">
			<div class="certifi_form_group">
				<input id="email" placeholder="{{ __('login.email_address') }}" type="email" class="certify_input certifi_form_input auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required  autofocus>
				
				@if ($errors->has('email'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('email') }}</strong> </span>
				@endif

				<button type="button"  id="email_certify" class="btn certify_btn active">
					{{ __('login.checking') }}
				</button>
			</div>
			<p class="info_about_email">
			{{ __('login.email_warning') }}
			</p>
		</div>

		<div class="form-group mb-2">
			<div class="certifi_form_group input_space">
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
			<span class="pw_no hide">{{ __('login.wrong_password') }}</span>
			<span class="pw_yes hide">{{ __('login.right_password') }}</span>
		</span>
		<!--//비밀번호와 비밀번호 확인 일치여부 확인-->
		<br />
		
		<!--추천인 @if($recommend)
		<div class="form-group">
			<div class="certifi_form_group">
				<input id="referral_id" type="text" class="auth_input form-control{{ $errors->has('referral_id') ? ' is-invalid' : '' }}" name="referral_id" value="{{ old('referral_id') }}" placeholder="{{__('user.recommender')}} Email" >
				
				@if ($errors->has('referral_id'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('referral_id') }}</strong> </span>
				@endif
			</div>
		</div>
		@endif -->

		<!-- 닉네임 -->
		<div class="form-group mb-2">
			<div class="certifi_form_group">
				<input id="nickname" placeholder="{{ __('login.nickname') }}" type="text" class="certify_input auth_input form-control{{ $errors->has('nickname') ? ' is-invalid' : '' }}" name="nickname" value="{{ old('nickname') }}" required autofocus>

				@if ($errors->has('nickname'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('nickname') }}</strong> </span>
				@endif

				<button type="button"  id="nickname_certify" class="btn certify_btn active">
					{{ __('login.checking') }}
				</button>
			</div>
		</div>

		<!-- 휴대폰 번호 -->
		<div class="form-group mb-2">
			<div class="certifi_form_group phone_check_space">
				<input id="mobile_number" placeholder="{{ __('login.phone_check') }}" type="text" class="certify_input auth_input form-control{{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" name="mobile_number" value="{{ old('mobile_number') }}" required autofocus>

				@if ($errors->has('mobile_number'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('mobile_number') }}</strong> </span>
				@endif

				<button type="button"  id="mobile_number_certify" class="btn certify_btn active">
					{{ __('login.checking') }}
				</button>
			</div>
		</div>

		<div class="form-group mb-2">
			<div class="certifi_form_group">
				<input id="fullname" placeholder="{{ __('login.name') }}" type="text" class="auth_input form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname" value="{{ old('fullname') }}" required autofocus>

				@if ($errors->has('fullname'))
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('fullname') }}</strong> </span>
				@endif
			</div>
		</div>

		<input type="hidden" name="market_type" value="" />
		<input type="hidden" name="email_verified" value="0" />
		<input type="hidden" name="mobile_verified" value="0" />


		<p class="group_name mb_block">
		{!! __('login.corp_login_info') !!}
		</p>

		<!-- 회사명 -->
		<div class="form-group mb-2">
			<div class="certifi_form_group">
				<input id="corp" placeholder="{{ __('login.corp_name') }}" type="text" class="certifi_form_input auth_input form-control{{ $errors->has('corp') ? ' is-invalid' : '' }}" name="corp" value="{{ old('corp') }}" required>
			</div>
		</div>

		<!-- 대표자명 -->
		<div class="form-group mb-2">
			<div class="certifi_form_group input_space">
				<input id="owner" placeholder="{{ __('login.corp_ceo') }} " type="text" class="owner_space auth_input form-control{{ $errors->has('owner') ? ' is-invalid' : '' }}" name="owner" required>
			</div>
		</div>

		<!-- 사업자 등록번호 -->
		<div class="form-group mb-1">
			<div class="certifi_form_group">
				<input id="corp_business_number" placeholder="{{ __('login.corp_register_number') }}" type="text" class="auth_input form-control" name="corp_business_number" required>
			</div>
		</div>

		<!-- 사업자 전화번호 -->
		<div class="form-group mb-1">
			<div class="certifi_form_group">
				<input id="corp_number" placeholder="{{ __('login.corp_phone_number') }}" type="text" class="number_space auth_input form-control" name="corp_number" required>
			</div>
		</div>

		<!-- 사업장 주소 -->
		<div class="form-group mb-2">
			<div class="certifi_form_group mt-2">
				<input id="corp_address" placeholder="{{ __('login.corp_address') }}" type="text" class="certify_input auth_input form-control{{ $errors->has('corp_address') ? ' is-invalid' : '' }}" name="corp_address" value="{{ old('corp_address') }}" required>

				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('corp_address') }}</strong> </span>

				<button type="button"  id="corp_address_certify" class="btn certify_btn active">
					{{ __('login.checking') }}
				</button>
			</div>
		</div>

		<!-- 상세주소 -->
		<div class="form-group mb-4">
			<div class="certifi_form_group">
				<input id="detail_address" placeholder="{{ __('login.corp_detail_address') }}" type="text" class="auth_input form-control{{ $errors->has('detail_address') ? ' is-invalid' : '' }}" name="detail_address" value="{{ old('detail_address') }}" required>

				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('detail_address') }}</strong> </span>
			</div>
		</div>

		<input type="hidden" name="corp_address_verified" value="0" />

		<!-- 기업 회원가입 입력 후 회원가입 완료 버튼 -->
		<div class="fixed_btn">
			<button type="submit" class="btn_style register_btn_st active">
				{{ __('login.register_success') }}
			</button>
		</div>
</template>

<script>
	$('.register_tab ul li').on('click', function(){
		var kind = $(this).data('kind');
		var templete = $($('#' + kind + '_register').html());
		$('#register_wrap').html(templete);
		$('.register_tab ul li').removeClass('active');
		$('.general_register_tit').hide();
		$('.'+kind+'_tit').show();
		$(this).addClass('active');

		if(kind=='normal'){
			$('input[name="register_type"]').val(1);
		}else{
			$('input[name="register_type"]').val(2);
		}
	});
</script>
@endsection
