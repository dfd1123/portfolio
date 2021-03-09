@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')

<div class="mypage_wrap">

    <div class="mypage_inner">
		<!-- 일반 회원정보 변경 타이틀
        <h2 class="title pb-2 mb-4">{{ __('myp.normal_changed_info') }}</h2> -->

		<!-- 기업 회원정보 변경 타이틀 -->
		<h2 class="title pb-3 mb-4">{{ __('myp.btn_changed_info') }}</h2>

        <div class="pw_change_group">
		
		<!-- <p class="mypage_msg pl-3">spowide@spowide.com(자동입력/비활성화)</p> -->

		<!-- 기업회원 정보 변경 form <form method="post" action="{{route('mypage.password_change_update')}}"> -->
			
        <!-- 회사명 -->
			<div class="form-group mb-4">
				<label for="corp" class="info_change_tit mb-2">{{ __('myp.company_name_change') }}</label>
				<input id="corp" placeholder="가입시 저장된 정보" type="text" class="owner_space auth_input form-control" name="corp" value="{{ old('corp') }}" required>
			</div>

			<!-- 대표자명 -->
			<div class="form-group mb-4">
				<label for="owner" class="info_change_tit mb-2">{{ __('myp.ceo_name_change') }}</label>
				<div class="certifi_form_group input_space">
					<input id="owner" placeholder="가입시 저장된 정보" type="text" class="owner_space auth_input form-control{{ $errors->has('owner') ? ' is-invalid' : '' }}" name="owner" required>
				</div>
			</div>

			<!-- 사업자 등록번호 -->
			<div class="form-group mb-4">
				<label for="corp_business_number" class="info_change_tit mb-2">{{ __('myp.business_number_change') }}</label>
				<div class="certifi_form_group">
					<input id="corp_business_number" placeholder="가입시 저장된 정보" type="text" class="auth_input form-control" name="corp_business_number" required>
				</div>
			</div>

			<!-- 사업자 전화번호 -->
			<div class="form-group mb-4">
				<label for="corp_number" class="info_change_tit mb-2">{{ __('myp.company_number_change') }}</label>
				<div class="certifi_form_group">
					<input id="corp_number" placeholder="가입시 저장된 정보" type="text" class="number_space auth_input form-control" name="corp_number" required>
				</div>
			</div>

			<!-- 사업장 주소 -->
			<div class="form-group mb-4">
				<label for="corp_address" class="info_change_tit mb-2">{{ __('myp.company_address_change') }}</label>
				<div class="certifi_form_group">
					<input id="corp_address" placeholder="가입시 저장된 정보" type="text" class="certify_input auth_input form-control{{ $errors->has('corp_address') ? ' is-invalid' : '' }}" name="corp_address" value="{{ old('corp_address') }}" required>
					<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('corp_address') }}</strong> </span>
					<button type="button"  id="corp_address_certify" class="btn certify_btn ml-3 active">
						{{ __('myp.search_address') }}
					</button>
				</div>
			</div>

			<!-- 상세주소 -->
			<div class="form-group mb-4">
				<label for="detail_address" class="info_change_tit mb-2">{{ __('myp.detailed_address') }}</label>
				<input id="detail_address" placeholder="가입시 저장된 정보" type="text" class="auth_input form-control{{ $errors->has('detail_address') ? ' is-invalid' : '' }}" name="detail_address" value="{{ old('detail_address') }}" required>
				<span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('detail_address') }}</strong> </span>
			</div>
			<input type="hidden" name="corp_address_verified" value="0" />
    	</form>	

		<div class="form-group mb-4">
			<label for="my_account_email" class="info_change_tit mb-2">{{ __('myp.email') }}</label>
			<p id="my_account_email" class="mypage_msg pl-3">spowide@spowide.com(자동입력/비활성화)</p>
		</div>
</form>	

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



			<!-- 닉네임 -->
			<div class="form-group mb-4">
			<label for="nickname" class="info_change_tit mb-2">{{ __('myp.nickname_change') }}</label>
				<div class="certifi_form_group nickname_space">
					<input id="nickname" placeholder="닉네임" type="text" class="certify_input auth_input form-control" name="nickname" value="" required="" autofocus="">
					<button type="button" id="nickname_certify" class="btn certify_btn ml-3 active">
					{{ __('myp.double_check') }}
					</button>
				</div>
			</div>
		</form>
				<div class="form-group mt-4">
					<button id="btn_password_change" class="btn_style gradient_btn mt-3">
						{{ __('myp.btn_info_change_success') }}
					</button>
				</div>

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