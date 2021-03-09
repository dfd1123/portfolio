@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--grade">

    <div class="hd-title hd-title--01">
        <button type="button" onclick="history.back();" class="hd-title__left-btn hd-title__left-btn--prev"><span
                class="none">이전화면</span></button>
        <h2 class="hd-title__center">플래너 등급 설명</h2>
    </div>
    
    <div class="wrapper--inner" >
        <div class="plnr_grade_hd">
            <ul class="plnr_grade_medals">
                <li class="_list">
                    <img src="/img/icon/icon-grade-03.svg" alt="icon-grade">
                    <b>Bronze</b>
                    <span>Junior</span>
                </li>
                <li class="_list">
                    <img src="/img/icon/icon-grade-02.svg" alt="icon-grade">
                    <b>Silver</b>
                    <span>Senior</span>
                </li>
                <li class="_list">
                    <img src="/img/icon/icon-grade-01.svg" alt="icon-grade">
                    <b>Gold</b>
                    <span>Master</span>
                </li>
            </ul>
        </div>
        <div class="plnr_grade_desc">
            <h4>플래너 등급이란?</h4>
            <p>트리픽에선 플래너에 대한 등급을 Bronze(Junior) - Silver(Senior) - Gold(Master)로 구분합니다.<br>등급이 높아질수록 여행객에 더욱 특별하고 만족스러운 경험을 제공한 우수 가이드임을 알 수 있습니다.<br>가이드에 대한 기준은 여행객에 대한 가이드 횟수,<br>여행객의 만족도 등으로 평가되며, 여행객이 프로필 확인 시 등급에 대해 직관적으로 확인 가능합니다.</p>
        </div>
    </div>

</div>
    
@include('nav.nav_user')

@endsection

@section('script')
<script>

</script>
@endsection