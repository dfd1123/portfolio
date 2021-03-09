@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
    <div class="loginbox">
        <div class="psearch">
            <form method="POST" action="{{ route('password.email') }}" id="password_for_reset">
                @csrf
                <h3 class="tit">가입하신 이메일 주소를 입력해주세요.</h3>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} required kr" name="email" value="{{ old('email') }}"  tabindex="2" title="이메일주소" required>
                <p>사용하시는 이메일 주소를 통해 비밀번호를 재생성 합니다.</p>
                <div class="both_btn_group">
                    <button type="submit" class="joinbt" >확인</button>
                    <a href="{route('login')}" class="cancel_btn" >취소</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="overlays" style="display:none;"></div>
<div class="sending_progress_wrap" style="display:none;">
    <div class="box">
        <div class="border one"></div>
        <div class="border two"></div>
        <div class="border three"></div>
        <div class="border four"></div>

        <div class="line one"></div>
        <div class="line two"></div>
        <div class="line three"></div>
    </div>
</div>

<script>
    @if (session('status'))
        alert('입력하신 메일 주소로 비밀번호 재설정 URL을 보내드렸습니다.\n메일함을 확인하세요.');
    @endif

    @if ($errors->has('email'))
        alert('입력하신 메일 주소를 다시 한번 확인해주세요.');
    @endif

    $('#password_for_reset').submit(function(){
		$('.overlays').show();
		$('.sending_progress_wrap').show();

		return true;
	})
</script>

@endsection
