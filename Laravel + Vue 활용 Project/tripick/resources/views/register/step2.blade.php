@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--sign-up wrapper--sign-up--02">

    <div class="hd-title hd-title--01">
        <button type="button" class="hd-title__left-btn hd-title__left-btn--close"><span
                class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">휴대폰 인증</h2>
    </div>

    <div class="wrapper--inner">

        <fieldset class="sign-up-fieldset sign-up-fieldset--phone">
            <ul class="sign-up-fieldset__group">
                <li class="sign-up-fieldset__list">
                    <input type="tel" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input"
                        placeholder="휴대폰 번호 입력">
                    <button type="button" class="button sign-up-fieldset--phone__button button--disable is-active">인증번호
                        받기</button>
                </li>
                <li class="sign-up-fieldset__list">
                    <input type="tel" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input"
                        placeholder="인증번호 입력">
                </li>
            </ul>
        </fieldset>
        <p class="sign-up-fieldset--phone__certify">
            <span class="sign-up-fieldset--phone__certify__count">00:59</span>
            <span class="sign-up-fieldset--phone__certify__caution">내에 인증번호를 입력해주세요.</span>
        </p>

    </div>

    <div class="button-bt-fixed" onClick="location.href='01-1-home(before).html'">
        <button type="button" class="button button--disable">완료</button>
    </div>

</div>
@endsection

@section('script')

@endsection