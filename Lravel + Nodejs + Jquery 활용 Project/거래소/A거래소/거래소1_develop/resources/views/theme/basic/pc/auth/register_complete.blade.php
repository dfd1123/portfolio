@extends(session('theme').'.pc.layouts.app')

@section('content')
<div class="register_wrap">
    <div class="register_con">
        <div class="register_box">
            <h2>회원가입 완료</h2>
            <div class="complete_img">
                <img src="/images/icon_complete.svg" alt="" />
            </div>
            <p class="reg_complete_ments">
				이메일 인증과 본인 인증 후 SPOWIDE의 모든 서비스를 이용할 수 있습니다.<br>
				{!! __('login.join_login_sentence9') !!}
            </p>
            <button type="button" class="bg_wh_btn" onclick="location.href='/'">{{ __('login.login') }}</button>
        </div>
    </div>
</div>
@endsection
