@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">
    <div class="modifybox">
        <h3 class="yellow_tit">비밀번호 변경</h3>
        <form method="post" id="password_change_form" action="{{route('mypage.password_update')}}">
            @csrf
                <ul id="password_edit">
                    <li><input type="password" id="password" name="password" title="비밀번호 입력" class="required kr" placeholder="비밀번호 입력" required="required"></li>
                    <li><input type="password" id="password_confirm" name="password_confirm" title="비밀번호 재입력" class="required kr" placeholder="비밀번호 재입력" required="required"></li>
                </ul>
                <div class="correct_yn">
                    <span class="correct">비밀번호 일치</span>
                    <span class="uncorrect">비밀번호 불일치</span>
                </div>
                <button type="submit" class="joinbt">변경하기</button>
        </form>
    </div>
</div>

@endsection