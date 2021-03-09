@extends('theme.basic.pc.layouts.app')

@section('content')

<div class="login_wrap">
    <div class="login_con">
        <div class="login_box">
            <h2>2차 OTP 로그인</h2>
            <form method="POST" action="">
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
                    <label for="otp">OTP 번호 (6자리 입력)</label>
                    <div>
                        <input type="text" name="otp" id="otp" required="required" />
                    </div>
                </div>
                
                <button type="submit" class="bg_blue_btn disable">로그인</button>
            </form>
        </div>
    </div>
</div>

<script>
    $('#otp').on('keyup', function(){
        if( $('#otp').val() != '' ){
            $('.bg_blue_btn').removeClass('disable');
        }else{
            $('.bg_blue_btn').addClass('disable');
        }
    });
</script>
@endsection