@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">


	<div class="orderbox">
			
			<!-- 장바구니 리스트 -->
			<form name="" method="post" action="">
				<div class="resultbox">
					<p><img src="{{asset('/storage/image/mobile/ic_orderend.png')}}" alt=""/></p>
					<span>주문해주셔서 감사합니다.</span>
					<ul>
						<li>
							고객님의 주문번호는
							<a href="{{route('order.bil',$order_id)}}">
								<strong>
									{{$order_number}}
								</strong> 
							</a>
							입니다. 
						</li>
						<li>상세한 내역은 고객님의 <a href="{{route('mypage.mobile_mypage')}}?index=4"><strong>마이페이지 주문내역</strong></a>에서 확인하실 수 있습니다. </li>
					</ul>
					<a href="{{route('mypage.mobile_mypage')}}" class="btmy" style="width:40%;">마이페이지 가기</a><a href="{{route('home')}}" class="btbk ml10" style="width:40%">메인으로</a>
				</div>
			</form>
			<!-- //장바구니 리스트 -->
		</div>
</div>

@endsection