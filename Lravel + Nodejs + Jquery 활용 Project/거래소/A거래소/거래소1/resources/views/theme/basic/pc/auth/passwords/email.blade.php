@extends('theme.basic.pc.layouts.app')

@section('content')

<div class="password_wrap">
    <div class="password_con">
        <div class="password_box">
            <h2>비밀번호 찾기</h2>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <p>비밀번호를 분실하셨나요? 사용자 아이디로 사용하는 이메일 주소를 입력하세요. 새로운 비밀번호를 만들기 위해 이메일을 통한 링크를 받게 됩니다.</p>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="reg_inp">
                    <label for="email">이메일</label>
                    <div>
                        <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus />
                    </div>
                </div>
                
                <button type="submit" class="bg_wh_btn">이메일 전송</button>
            </form>
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

    swal({
            title: '계정 정지',
            text: '관리자에 의해 정지된 계정입니다.\n고객센터로 문의주시기 바랍니다.',
            icon: "error",
            button: __.message.ok,
        });
</script>
@endsection
