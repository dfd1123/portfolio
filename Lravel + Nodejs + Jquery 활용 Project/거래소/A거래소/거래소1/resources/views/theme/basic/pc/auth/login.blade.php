@extends('theme.basic.pc.layouts.app')

@section('content')

<div class="login_wrap">
    <div class="login_con">
        <div class="login_box">
            <h2>로그인</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="reg_inp">
                    <label for="email">이메일</label>
                    <div>
                        <input type="email" name="email" id="email" required="required" />
                    </div>
                </div>
                <div class="reg_inp">
                    <label for="password">비밀번호</label>
                    <div>
                        <input type="password" name="password" id="password" required="required" />
                    </div>
                </div>
                <button type="submit" class="bg_blue_btn disable">로그인</button>
            </form>
            <div class="sub_btn_wrap">
                @if (Route::has('password.request'))
                    <a class="password_find_btn" href="{{ route('password.request') }}">
                        비밀번호 찾기
                    </a>
                @endif
                <a href="{{route('register')}}" class="register_btn">회원가입</a>
            </div>
        </div>
    </div>
</div>


<script>
    $('input[type="email"]').on('keyup', function(){
        if( $('input[type="email"]').val() != '' && $('input[type="password"]').val() != '' ){
            $('.bg_blue_btn').removeClass('disable');
        }else{
            $('.bg_blue_btn').addClass('disable');
        }
    });

    $('input[type="password"]').on('keyup', function(){
        if( $('input[type="email"]').val() != '' && $('input[type="password"]').val() != '' ){
            $('.bg_blue_btn').removeClass('disable');
        }else{
            $('.bg_blue_btn').addClass('disable');
        }
    });
</script>
@endsection