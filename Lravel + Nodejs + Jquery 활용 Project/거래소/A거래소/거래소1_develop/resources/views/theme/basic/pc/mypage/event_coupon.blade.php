@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')

<div class="mypage_wrap">

    <div class="mypage_inner">
        
        <div style="background:url(/images/bc_event_coupon.png) no-repeat center center; width:100%; height:224px;padding:0px 146px;">
            @if(isset($event_coupon->id))
            <div style="padding:78px;">
                <p style="color:#009AD6;font-weight:bold;font-size:24px;margin-bottom:10px;">{{ $event_coupon->rank }}등</p>
                <p style="margin-bottom:10px;font-weight:bold;">스포츠코인 14종류</p>
                <p style="font-weight:bold;font-size:24px;">{{ number_format($event_coupon->amount) }}개</p>
            </div>
            <p style="font-weight:bold;">※ 사용 가능 일시는 추후 공지사항을 통해 알려드리겠습니다.</p>
            @else
            <div style="padding:78px;">
                <p style="color:#009AD6;font-weight:bold;font-size:24px;margin-bottom:10px;">당첨 내역 없음</p>
            </div>
            <p style="font-weight:bold;margin-top:50px;">※회원님께서는 아쉽게도 이벤트 대상에서 제외 되었습니다.</p>
            <p style="font-weight:bold;">※다음 이벤트에서 행운을 빌겠습니다.</p>
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