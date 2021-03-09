@extends('theme.basic.pc.layouts.app')

@section('content')

<div class="password_wrap">
    <div class="password_con">
        <div class="password_box">
            <h2>비밀번호 변경</h2>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <p>인증 메일을 받으신 메일주소를 입력하시고 재설정 하실 비밀번호를 입력하여 주세요.</p>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="reg_inp">
                    <label for="email">이메일</label>
                    <div>
                        <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus />
                    </div>
                </div>

                <div class="reg_inp">
                    <label for="password">비밀번호 입력</label>
                    <div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" />
                    </div>
                </div>
                <div class="reg_inp">
                    <label for="password-confirm">비밀번호 확인</label>
                    <div>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" />
                    </div>
                    <div class="password_correct_status">
                        <span class="correct" style="display:none;">비밀번호가 일치합니다.</span>
                        <span class="incorrect" style="display:none;">비밀번호가 일치하지 않습니다.</span>
                    </div>
                </div>
                
                <button type="submit" class="bg_wh_btn">이메일 전송</button>
            </form>
        </div>
    </div>
</div>


<script>
    $('input[type="password"]').on('keyup', function(){
        if( $('#password').val() == $('#password-confirm').val() ){
            $('.password_correct_status .incorrect').hide();
            $('.password_correct_status .correct').show();
        }else{
            $('.password_correct_status .correct').hide();
            $('.password_correct_status .incorrect').show();
        }
    });
</script>
@endsection