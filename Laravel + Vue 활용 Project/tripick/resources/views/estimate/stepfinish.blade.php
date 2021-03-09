@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--recommend">

    <div class="recommend-step__finished">
        <button type="button" class="hd-title__left-btn hd-title__left-btn--home-wh" onClick="location.href='/af_home'"><span class="none">홈으로 가기</span></button>
    </div>

    <div class="recommend-step__finished-popup">

        <div class="check-circle">
            <div class="check-circle__mark"></div>
        </div>
        <h4 class="finished-popup__guide">여행 추천 입력을<br><b>완료</b>하였습니다.</h4>
        <p class="finished-popup__ment">지금부터 24시간 이내, 업체 및 개인의<br>추천을 받아보실 수 있습니다.</p>

        <p class="loading-dots">
            <span class="loading-dots__dot"></span>
            <span class="loading-dots__dot"></span>
            <span class="loading-dots__dot"></span>
        </p>

        <p class="finished-popup__info">
            <span class="finished-popup__info__label">추천 마감까지</span>
            <span class="finished-popup__info__date"><b id="time-hour"></b>시간 <b id="time-min"></b>분 남음</span>
        </p>

    <button type="button" class="button" onclick="location.href='/estimate/match/{{ $estm_id }}'">입력정보 확인</button>

    </div>

</div>
    
@include('nav.nav_user')
@endsection

@section('script')
<script>
$(function(){
    dailyMissionTimer({{$duration}});
    
    //플래너에게 견적서 등록 알림push
    var param = {
    	'req': 'toPln',
        'push_title': 'Tripick',
        'push_content': '플래너님 지역에 새로운 견적이 올라왔어요!',
        'estm_id' : {{ $estm_id }}
    };
    $.ajax({
        method: "POST",
        data: param,
        dataType: 'json',
        url: '/api/push',
        success: function(data) {
            console.log(data);
        },
        error: function(e) {
            console.log(e);
        }
    })
});
function dailyMissionTimer(duration) {
    
    var timer = duration;
    var hours, minutes, seconds;
    
    var interval = setInterval(function(){
        hours	= parseInt(timer / 3600, 10);
        minutes = parseInt(timer / 60 % 60, 10);
        seconds = parseInt(timer % 60, 10);
		
        hours 	= hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
		
        $('#time-hour').text(hours);
        $('#time-min').text(minutes);

        if (--timer < 0) {
            timer = 0;
            clearInterval(interval);
        }
    }, 1000);
}

</script>
@endsection