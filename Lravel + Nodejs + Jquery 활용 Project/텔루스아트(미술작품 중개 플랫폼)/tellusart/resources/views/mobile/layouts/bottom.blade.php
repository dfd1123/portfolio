<div class="bottomfix">
	@guest
	<a href="{{route('login')}}"><img src="{{asset('/storage/image/mobile/ic_login.png')}}" alt=""/>로그인</a>
	@else
	<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="{{asset('/storage/image/mobile/ic_logout.png')}}" alt=""/>로그아웃</a>
	@endguest
	<!-- <a href=""><img src="../image/ic_logout.png')}}" alt=""/>로그아웃</a>-->
	<a href="{{route('mypage.mobile_mypage')}}?index=4"><img src="{{asset('/storage/image/mobile/img_mym02.png')}}" alt=""/>주문내역</a>
	<a href="{{route('coin.coin_edit')}}" class="ycenter"><img src="{{asset('/storage/image/mobile/ic_coingo.png')}}" alt=""/></a>
	<a href="{{route('mypage.mobile_mypage')}}?index=3"><img src="{{asset('/storage/image/mobile/ic_cart.png')}}" alt=""/>장바구니</a>
	<a href="{{route('mypage.mobile_mypage')}}?index=1"><img src="{{asset('/storage/image/mobile/ic_mypage.png')}}" alt=""/>마이페이지</a>
</div>