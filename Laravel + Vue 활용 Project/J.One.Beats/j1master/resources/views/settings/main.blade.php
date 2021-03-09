@extends('header')
@section('content')

<div class="box" id="external-events">
    <div class="external-event bg-purple" onclick="location.href='/settings/banner';">
        <div class="event-title">
             배너 / 이벤트
        </div>
        <div class="event-content">
            <div class="date">
                배너 및 이벤트 등록
            </div>
        </div>
    </div>
    <div class="external-event bg-aqua" onclick="location.href='/settings/bookkeeping';">
        <div class="event-title">
            수익 / 정산
        </div>
        <div class="event-content">
            <div class="date">
                판매수익 및 정산
            </div>
        </div>
    </div>
    <div class="external-event bg-purple" onclick="location.href='/settings/license';">
        <div class="event-title">
            라이센스 상품
        </div>
        <div class="event-content">
            <div class="date">
               이용권 관리
            </div>
        </div>
    </div>
    <div class="external-event bg-pink" onclick="location.href='/notice';">
        <div class="event-title">
            공지사항
        </div>
        <div class="event-content">
            <div class="date">
                공지사항 관리
            </div>
        </div>
    </div>
    <div class="external-event bg-pink" onclick="location.href='/faq';">
        <div class="event-title">
            FAQ 설정
        </div>
        <div class="event-content">
            <div class="date">
                Saturday, 25 November, 2018
            </div>
        </div>
    </div>
    <div class="external-event bg-pink" onclick="location.href='/bbs';">
        <div class="event-title">
            1:1 문의 관리
        </div>
        <div class="event-content">
            <div class="date">
                Saturday, 25 November, 2018
            </div>
        </div>
    </div>
    <div class="external-event bg-orange" style="opacity: 0.3" onclick="alert('모바일 앱 전용 기능입니다')">{{--<div class="external-event bg-orange" onclick="location.href='/settings/push';">--}}
        <div class="event-title">
            PUSH 관리
        </div>
        <div class="event-content">
            <div class="date">
                Saturday, 25 November, 2018
            </div>
        </div>
    </div>

    
</div>
<script>
    menuactive('setting');
$(window).load(function() {
    setTimeout(function() {
        $('.loader').hide();
    }, 100);
});
</script>
@endsection
