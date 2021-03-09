@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="register_wrap">
    <div class="register_con">
        <div class="register_box">
            <h2>회원가입</h2>
            <ul>
                <li class="active" data-register="personal">개인회원</li>
                <li data-register="company">기업회원</li>
            </ul>
            <div class="form_wrap">
                <form method="POST" id="register_form" action="{{route('register')}}">
                    @csrf
                    <input type="hidden" name="email_cv" value="0">
                    <input type="hidden" name="nickname_cv" value="0">
                    <input type="hidden" name="mobile_number_cv" value="0">
                    <input type="hidden" name="register_type" value="1">
                    <input type="hidden" name="country" value="kr">
                    <h4 class="blue_label">로그인 정보</h4>
                    <div class="reg_inp">
                        <label for="email">이메일</label>
                        <div class="check_btn_div">
                            <input id="email" placeholder="{{ __('login.email_address') }}" type="email" class="certifi_form_input auth_input {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" />
							<button type="button" id="email_certify" class="btn certify_btn active">
								{{ __('login.checking') }}
							</button>
                        </div>
                        <span>※정확한 이메일 주소를 입력하세요. 허위 계정일 경우 추후 인증메일을 받아볼 수 없으며, 거래 제한 및 입출금이 금지됩니다.</span>
                    </div>
                    <div class="reg_inp">
                        <label for="password">비밀번호 입력</label>
                        <div>
                            <input id="password" placeholder="{{ __('login.join_login_sentence7') }} " type="password" class="auth_input {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required />
                        </div>
                    </div>
                    <div class="reg_inp">
                        <label for="password-confirm">비밀번호 확인</label>
                        <div>
                            <input id="password-confirm" placeholder="{{ __('login.check_password') }}" type="password" class="auth_input" name="password_confirmation" required />
                        </div>
                        <div class="password_correct_status">
                            <span class="correct" style="display:none;">비밀번호가 일치합니다.</span>
                            <span class="incorrect" style="display:none;">비밀번호가 일치하지 않습니다.</span>
                        </div>
                    </div>
                    <div class="reg_inp">
                        <label for="nickname">닉네임</label>
                        <div class="check_btn_div">
                            <input type="text" id="nickname" name="nickname" required="required" />
                            <button type="button" id="nickname_certify" class="btn certify_btn active">
								{{ __('login.checking') }}
							</button>
                        </div>
                        <span>※불건전한 닉네임을 설정하실 경우 커뮤니티 사용이 금지됩니다.</span>
                    </div>
                    <div class="reg_inp">
                        <label for="mobile_number">휴대폰 번호</label>
                        <div class="check_btn_div">
                            <input type="text" id="mobile_number" name="mobile_number" required="required" />
                            <button type="button" id="mobile_number_certify" class="btn certify_btn active">
								{{ __('login.checking') }}
							</button>
                        </div>
                        <span>※본인명의 휴대폰 번호를 입력하세요. 허위 번호일 경우 추후 본인인증을 진행 하실 수 없으며, 거래 제한 및 입출금이 금지됩니다.</span>
                    </div>
                    <div class="reg_inp">
                        <label for="fullname">이름</label>
                        <div>
                            <input type="text" id="fullname" class="mr-1 auth_input {{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname" value="{{ old('fullname') }}" required autofocus />
                        </div>
                    </div>
                    

                    <div class="company_register_form">

                    </div>

                    @if($recommend)
                        <h4 class="blue_label">추천인 정보</h4>
						<div class="reg_inp">
							<label for="fullname">추천인</label>
							<div>
								<input id="referral_id" type="text" class="auth_input {{ $errors->has('referral_id') ? ' is-invalid' : '' }}" name="referral_id" value="{{ old('referral_id') }}" placeholder="{{__('user.recommender')}} Email" />
							</div>
						</div>
					@endif


					<input type="hidden" name="market_type" value="" />
                    
                    <div class="register_agree_box">
                        <div class="register_agree_inp agree_check_box">
                            <p>이용약관</p>
                            <input type="checkbox" id="agree_1" class="checkbox_style checkbox_style02 hide"><label class="checkbox_label" for="agree_1"></label><label for="agree_1">스포와이드 거래소 서비스 이용약관에 동의합니다.(필수)</label> <a href="{{ route('cs_etc.show', 'service_guide') }}" target="_blank">전문보기 ></a>
                        </div>
                        <div class="register_agree_inp agree_check_box">
                            <p>개인정보처리방침</p>
                            <input type="checkbox" id="agree_2" class="checkbox_style checkbox_style02 hide"><label class="checkbox_label" for="agree_2"></label><label for="agree_2">스포와이드 거래소 개인정보 수집 및 이용에 동의합니다.(필수)</label> <a href="{{ route('cs_etc.show', 'privacy_guide') }}" target="_blank">전문보기 ></a>
                        </div>
                        <div class="register_agree_inp agree_check_box">
                            <p>마케팅 수신 동의</p>
                            <input type="checkbox" id="agree_3" class="checkbox_style checkbox_style02 hide"><label class="checkbox_label" for="agree_3"></label><label for="agree_3">스포와이드 거래소 마케팅 수신에 동의합니다.(선택)</label>
                        </div>
                    </div>
                    <button type="submit">회원가입 완료</button>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="company_register_form">
    <h4 class="blue_label">기업 정보</h4>
    <div class="reg_inp">
        <label for="company_name">회사명</label>
        <div>
            <input type="text" name="company_name" id="company_name" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="ceo_name">대표자명</label>
        <div>
            <input type="text" name="ceo_name" id="ceo_name" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="business_number">사업자 등록번호</label>
        <div>
            <input type="text" name="business_number" id="business_number" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="company_phone_number">사업장 전화번호</label>
        <div>
            <input type="text" name="company_phone_number" id="company_phone_number" required="required" />
        </div>
    </div>
    <div class="reg_inp">
        <label for="company_address">사업장 주소</label>
        <div class="check_btn_div">
            <input type="text" name="company_address" id="company_address" required="required" />
            <button type="button">주소찾기</button>
        </div>
    </div>
    <div class="reg_inp">
        <label for="company_detail_address">상세 주소</label>
        <div>
            <input type="text" name="company_detail_address" id="company_detail_address" required="required" />
        </div>
    </div>
</template>

<script>
	@if ($errors->has('fullname'))
		swal({
				title: '{{ __('message.login_fail') }}',
				text: "{{ $errors->first('fullname') }}",
				icon: "warning",
				button: '{{ __('message.ok') }}',
			});
	@endif
	@if ($errors->has('email'))
		swal({
				title: '{{ __('message.login_fail') }}',
				text: "{{ $errors->first('email') }}",
				icon: "warning",
				button: '{{ __('message.ok') }}',
			});
	@endif
	@if ($errors->has('password'))
		swal({
				title: '{{ __('message.login_fail') }}',
				text: "{{ $errors->first('password') }}",
				icon: "warning",
				button: '{{ __('message.ok') }}',
			});
	@endif
	@if ($errors->has('referral_id'))
		swal({
				title: '{{ __('message.login_fail') }}',
				text: "{{ $errors->first('referral_id') }}",
				icon: "warning",
				button: '{{ __('message.ok') }}',
			});
	@endif
</script>


@endsection
