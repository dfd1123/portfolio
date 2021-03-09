@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
        <h1 class="logo">
            <a href="/?country=kr">
                <img src="{{ asset('/storage/image/homepage/spowide_header_logo.png')}}" style="    width: 106px;" alt="logo"/>
            </a>
        </h1>
    </div>
</div>

<div class="auth_wrap find_pw_wrap find_pw_email_wrap">

<div class="auth_panel">

    <h1 class="card_title pb-3">{{ __('login.change2') }}</h1>
    <div class="auth_form_group find_pw_group">
        
        <form method="POST" action="{{ route('password.update') }}">
			@csrf
			
			<input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <span class="ment mb-3">{{__('login.password_reset')}}</span>
                <div class="certifi_form_group">
                    <input type="text" class="pl-120px auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" style="font-size: 1rem;" placeholder="로그인 이메일 주소를 입력하세요." required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <div class="certifi_form_group">
                    <input id="password" placeholder="{{ __('login.join_login_sentence7') }} " type="password" class="auth_input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                    @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('password') }}</strong> </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
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


            <div class="form-group mb-0 mt-5">

                <div class="both_btn_group">
                    <button type="button" class="btn_style cancel_btn" onclick="location.href='/'">
                    {{ __('login.cancel') }}
                    </button>
                    <button type="submit" class="btn_line_style">
                    {{ __('login.password_change_success') }}
                    </button>
                </div>
            </div>
            
        </form>
        
    </div>
</div>

</div>


@endsection
