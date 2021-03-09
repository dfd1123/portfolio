@extends('user_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">
    <div style="width:100%;height:100%;padding:40px;margin:30px 0;text-align:center;">
        <img src="{{asset('/images/perzuman_round.svg')}}" style="width:90px;height:90px;"/>
        <div style="margin:20px 0">
            <p style="color:#4f5256">업체정보 등록이<br/><em style="color:#008cd6">완료</em>되었습니다</p>
        </div>
        <p style="color:#a0a5af">퍼주마에서 퍼펙트한 주방을 만들어주세요!</p>
    </div>
    <a class="go_btn home_btn" href="{{route('user_ver.home')}}">홈으로</a>
    
</div>

<style>
    .home_btn{
        
    }
</style>
<script>
$(function() {
    
});

</script>

@endsection