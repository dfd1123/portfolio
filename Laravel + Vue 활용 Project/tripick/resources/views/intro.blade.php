@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--intro">
                
    <div class="wrapper--inner">

        <section class="wrapper--intro__con">
            <a class="wrapper--intro__con__skip" href="/be_home">나중에 하기</a>
            <span class="wrapper--intro__con__span">세상에서 나를 가장 잘 아는 여행</span>
            <h1 class="wrapper--intro__con__h1">트리픽<br>시작하기<img src="/img/logo/logo-symbol-tripik-maincolor.svg" alt="symbol-logo"></h1>
        </section>

        <div class="wrapper--intro__button_group">
        <a class="wrapper--intro__button wrapper--intro__button--register" href="/register/agree">회원가입</a>
            <a class="wrapper--intro__button wrapper--intro__button--login" href="/login">로그인</a>
        </div>
        
    </div>

</div>
            
<div class="ft-info">
    <ul class="ft-info__group">
        <li class="ft-info__list">
            <span class="ft-info__list__label">사업자명</span>
            <p class="ft-info__list__txt">(주) 아제타<b class="ft-info__list__border">|</b></p>
            <span class="ft-info__list__label">사업자등록번호</span>
            <p class="ft-info__list__txt">685-81-01416</p>
        </li>
        <li class="ft-info__list">
            <span class="ft-info__list__label">통신판매업신고번호</span>
            <p class="ft-info__list__txt">2019-서울강남-04282</p>
        </li>
        <li class="ft-info__list">
            <span class="ft-info__list__label">대표자명</span>
            <p class="ft-info__list__txt">정병욱<b class="ft-info__list__border">|</b></p>
            <span class="ft-info__list__label">이메일</span>
            <p class="ft-info__list__txt">tripick@naver.com</p>
        </li>
        <li class="ft-info__list">
            <span class="ft-info__list__label">고객센터 전화번호</span>
            <p class="ft-info__list__txt">02-567-1336</p>
        </li>
        <li class="ft-info__list">
            <span class="ft-info__list__label">주소</span>
            <p class="ft-info__list__txt">서울시 강남구 테헤란로 25길 6-9, 4층 404호(석암빌딩)</p>
        </li>
    </ul>
    <br>
    <br>
    <ol class="ft-info__group ft-info__group--02">
        <li class="ft-info__list--02" onclick="location.href='/terms01';">tripick 이용약관<b class="ft-info__list__border">|</b></li>
        <li class="ft-info__list--02" onclick="location.href='/terms01';">개인정보 수집 및 이용약관</li>
    </ol>
</div>
@endsection

@section('script')
<script>
	/*
	console.log('session token : '+getCookie('tripick_token'));
		if(getCookie('tripick_token') != undefined){
			
		}*/
	$.ajax({
        method: "GET",
        data: null,
        dataType: 'json',
        url: '/api/decodejwt',
        success: function(data) {
            console.log(data)
        },
        error: function(e) {
            console.log(e);
        }
    });
</script>
@endsection