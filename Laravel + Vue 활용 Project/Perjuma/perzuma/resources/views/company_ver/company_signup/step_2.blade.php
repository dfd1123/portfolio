@extends('company_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">
    <div style="width:100%;height:100%;padding:40px;margin:30px 0;text-align:center;">
        <img src="{{asset('/images/perzuman_round.svg')}}" style="width:90px;height:90px;"/>
        <div style="margin:20px 0">
            <p style="color:#4f5256">시공업체 회원가입이<br/><em style="color:#008cd6">완료</em>되었습니다</p>
        </div>
        <p style="color:#a0a5af">퍼주마에서 퍼펙트한 주방을 만들어주세요!</p>
    </div>
    <button class="home_btn" onclick="location.href='/company_ver/company_regist/1';">업체정보 등록</button>
    
</div>

<style>
    .home_btn{
        display: block;
    font-size: 1.15rem;
    color: #fff;
    width: 87%;
    margin: 0 auto;
    text-align: center;
    vertical-align: middle;
    text-decoration: none;
    border-radius: 43px;
    padding: 0.75rem 0;
    box-shadow: 0px 13px 16px 0 rgba(36, 36, 36, 0.07);
    background-color: #17334a;
    }
</style>
<script>
$(function() {
    
});

</script>

@endsection