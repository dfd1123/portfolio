@extends(session('theme').'.mobile.layouts.app')

@section('content')
<div class="m_hd_title">
    <div class="inner">
    {{ __('login.change2') }}
    </div>
</div>

<div class="auth_wrap find_pw_wrap find_pw_email_wrap">

<div class="auth_panel">

    <h1 class="card_title mb-5 pb-3">Setting Password</h1>
    <div class="auth_form_group find_pw_group">
        
        <form method="POST" action="{{ route('password.update') }}">
			@csrf
			
			<input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <div class="certifi_form_group">
                    <label class="certify_label">{!!  __('login.email_address') !!}</label>
                    <input type="text" class="pl-120px auth_input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>
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

            <div class="form-group mb-0 mt-5">
                <button type="submit" class="btn_style">
                {{ __('login.change') }}
                </button>
            </div>
            
        </form>
        
    </div>
</div>

</div>


@endsection
