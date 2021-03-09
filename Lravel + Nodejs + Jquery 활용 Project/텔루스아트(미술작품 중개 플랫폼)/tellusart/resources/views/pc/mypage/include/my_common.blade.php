	<div class="myinfo">
		<div class="titleinfo">
			<p><img src="{{asset('/storage/image/'.Auth::user()->profile_img)}}" alt="내 프로필 사진"/><label for="profile_img"><i class="far fa-cog"></i></label></p>
			<ul>
				<li>닉네임 : {{ Auth::user()->nickname }}</li>
				<li><strong>{{ Auth::user()->name }}</strong> 님</li>
				<li><a href="{{ route('mypage.account_edit') }}">계좌정보</a></li>
				<li><a href="{{ route('mypage.password_edit') }}">비밀번호 수정</a></li>
			</ul>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
            </form>
		</div>
		<div class="mynum">
			<ul>
				<li><a href="{{route('mypage.mybatting_list')}}"><span class="en"><i class="fal fa-images"></i>{{$batting_cnt}}</span>베팅한 작품</a></li>	
				<li><a href="{{route('mypage.myart_list')}}"><span class="en"><i class="fal fa-palette"></i>{{$product_cnt}}</span>내 작품</a></li>	
				<li><a href="{{route('mypage.cart')}}"><span class="en"><i class="fal fa-shopping-cart"></i>{{$cart_cnt}}</span>장바구니</a></li>	
			</ul>
		</div>
		<div class="mycallnum">
			<dl>	
				<dt>등록 된 전화번호
				<dd class="en">{{ Auth::user()->mobile_number }}</dd>
				<dt>등록 된 이메일
				<dd class="en">{{ Auth::user()->email }}</dd>
			</dl>
		</div>
	</div>
	@if($mp_kind < 7)
		<div class="my_cate">
			<ul>
				<li><a href="{{route('mypage.myinfor')}}" class="{{($mp_kind == 0)?"on":""}}">내 정보수정</a></li>
				<li><a href="{{route('mypage.myart_list')}}" class="{{($mp_kind == 1)?"on":""}}">내 작품</a></li>
				<li><a href="{{route('mypage.mybatting_list')}}" class="{{($mp_kind == 2)?"on":""}}">베팅한 건수</a></li>
				<li><a href="{{route('mypage.cart')}}" class="{{($mp_kind == 3)?"on":""}}">장바구니</a></li>
				<li><a href="{{route('mypage.my_order_list')}}" class="{{($mp_kind == 4)?"on":""}}">구매내역</a></li>
				<li><a href="{{route('mypage.my_sale_list')}}" class="{{($mp_kind == 5)?"on":""}}">판매내역</a></li>
				<li><a href="{{route('mypage.my_comment_list')}}" class="{{($mp_kind == 6)?"on":""}}">나의 코멘트</a></li>
			</ul>
		</div>
	@endif
	<form enctype="multipart/form-data" method="post" action="{{route('mypage.profile_img_change')}}" id="profile_img_change" >
		@csrf
		<input type="file" id="profile_img" class="hidden" name="profile_img" value="{{Auth::user()->profile_img}}" />
	</form>
	
<style>
	.titleinfo p label{position:absolute;left:40%;width:29px;height:29px;background:#fea803;border:1px solid #b67905;border-radius:100em;bottom:-15px;line-height:29px;text-align:center;}
	.titleinfo p label i{margin-top:10px;}
</style>
