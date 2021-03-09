@extends(session('theme').'.mobile.layouts.app')

@section('content')

@include(session('theme').'.mobile.mypage.include.mypage_hd')




<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-4">
    
    


<!-- 공통된 비밀번호 변경 form -->
    <form method="post" action="{{route('mypage.password_change_update')}}">

        @csrf

        <input id="current_password" placeholder="{{ __('myp.now_password') }}" type="password" class="auth_input form-control mt-4 mb-4" name="password" required>

        <input id="new_password" placeholder="{{ __('myp.new_changed_password') }}" type="password" class="auth_input form-control mb-2" required>

        <input id="confirm_password" placeholder="{{ __('myp.new_changed_password_check') }}" type="password" class="auth_input form-control mb-1" name="cpassword" required>

        <!--비밀번호와 비밀번호 확인 일치여부 확인-->
        <span class="ment register_pw_alert">
            <span class="pw_no hide">{{ __('myp.wrong_password') }}</span>
            <span class="pw_yes hide">{{ __('myp.right_password') }}</span>
        </span>
        <!--//비밀번호와 비밀번호 확인 일치여부 확인-->


        <div class="fixed_btn">
            <button type="submit" class="btn_style abslt_btn">
                {{ __('myp.corp_changed_info') }}
            </button>
        </div>
   
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
