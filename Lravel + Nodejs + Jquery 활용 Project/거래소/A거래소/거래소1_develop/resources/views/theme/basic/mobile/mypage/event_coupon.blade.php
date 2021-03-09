@extends(session('theme').'.mobile.layouts.app')

@section('content')

<div class="m_hd_title">
    <div class="inner">
        이벤트 당첨코인
    </div>
</div>
<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-4">
    <div style="width:300px;margin: 50px auto;">
        <div style="background:url(/images/bc_event_coupon.png) no-repeat center center; background-size:270px 149px; width:100%; height:170px;">
            @if(isset($event_coupon->id))
            <div style="padding:52px 62px;">
                <p style="color:#009AD6;font-weight:bold;font-size:20px;">{{ $event_coupon->rank }}등</p>
                <p style="font-weight:bold;font-size:12px;">스포츠코인 14종류</p>
                <p style="font-weight:bold;font-size:20px;">{{ number_format($event_coupon->amount) }}개</p>
            </div>
            <p style="font-weight:bold;font-size:12px;color:#333333;text-align:center;">※사용 가능 일시:추후 공지사항을 통해 안내</p>
            @else
            <div style="padding:69px 48px;">
                <p style="color:#009AD6;font-weight:bold;font-size:20px;">당첨 내역 없음</p>
            </div>
            <p style="font-weight:bold;margin-top:6px;">※ 회원님께서는 아쉽게도 이벤트 대상에서 제외 되었습니다.</p>
            <p style="font-weight:bold;">※ 다음 이벤트에서 행운을 빌겠습니다.</p>
            @endif
        </div>
    </div>
</div>
    
<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.myp = {
        @foreach(__('myp') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
</script>

@endsection
