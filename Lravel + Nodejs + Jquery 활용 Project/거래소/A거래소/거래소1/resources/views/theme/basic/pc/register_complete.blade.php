@extends('theme.basic.pc.layouts.app')

@section('content')
<div class="register_wrap">
    <div class="register_con">
        <div class="register_box">
            <h2>회원가입 완료</h2>
            <div class="complete_img">
                <img src="/images/icon_complete.svg" alt="" />
            </div>
            <p class="reg_complete_ments">
                입력하신 이메일의 메일함에서<br>
                인증을 완료하셔야 로그인이 가능합니다.
            </p>
            <button type="button" class="bg_wh_btn">로그인 하기</button>
        </div>
    </div>
</div>

@endsection
