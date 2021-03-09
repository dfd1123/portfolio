@extends(session('theme').'.mobile.layouts.app')

@section('content')

@include(session('theme').'.mobile.mypage.include.mypage_hd')

<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-4">
    
    <p class="mypage_msg">{!! __('myp.mypage_sentence7') !!}
    </p>

    <form method="post" action="{{route('mypage.password_change_update')}}">

        @csrf

        <input id="current_password" placeholder="{{ __('myp.now_password') }}" type="password" class="auth_input form-control mb-3" name="password" required>

        <input id="new_password" placeholder="{{ __('myp.mypage_sentence') }}" type="password" class="auth_input form-control mb-2" required>

        <input id="confirm_password" placeholder="{{ __('myp.check_new_password') }}" type="password" class="auth_input form-control mb-1" name="cpassword" required>

        <!--비밀번호와 비밀번호 확인 일치여부 확인-->
        <span class="ment register_pw_alert">
            <span class="pw_no hide">{{ __('myp.wrong_password') }}</span>
            <span class="pw_yes hide">{{ __('myp.right_password') }}</span>
        </span>
        <!--//비밀번호와 비밀번호 확인 일치여부 확인-->

        <button id="btn_password_change" class="btn_style mt-3">
        {{ __('myp.change_password') }}
        </button>
    
    </form>

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
