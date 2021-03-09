@extends('pc.layouts.app')

@section('content')
<div class="sub_spot member">
	<h2>로그인</h2>
	<div class="search_form">
		<form id="search_item" method="get" action="{{route('products.search_list',-1)}}">
			<input type="text" name="keyword" placeholder="작가명, 회화명, 분야 등으로 검색"/>
			<span><button type="submit">search<i class="fas fa-search"></i></button></span>
		</form>
	</div>
</div>
<div id="container">
	<div class="loginbox">
		<div class="logintab-content animated fadeIn">
			<!-- 일반 -->
			<form method="POST" action="{{ route('login') }}">
				@csrf
				<input type="email" id="email" name="email" tabindex="2" title="아이디" class="required kr"  placeholder="아이디"  value="{{ old('email') }}" required autofocus/>
				<input type="password" id="password" name="password" tabindex="2" title="비밀번호" class="required kr" placeholder="비밀번호" required/>
				<div class="folder_check" >
					<input type="checkbox"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
					<label for="remember"><span></span>아이디저장</label>
				</div>
				<button type="submit" class="loginbt">로그인</button>
				<ul class="etclog">
					<li><a href="{{route('register')}}">무료회원가입</a></li>
					<li><a href="{{route('password.request')}}">비밀번호 찾기</a></li>
				</ul>
				<ul class="snslogin">
					<li style="display:none;"><a href=""><i><img src="{{asset('storage/image/homepage/ic_facebook.png')}}" alt=""/></i>페이스북으로 시작하기</a></li>
					<li style="display:none;"><a href="{{route('naver.login')}}"><i><img src="{{asset('storage/image/homepage/ic_naver.png')}}" alt=""/></i>네이버로 시작하기</a></li>
					<!--<li><a href="{{route('kakao.login')}}"><i><img src="{{asset('storage/image/homepage/ic_kakao.png')}}" alt=""/></i>카카오톡으로 시작하기</a></li>-->
				</ul>
			</form>
		</div>
	</div>
	
</div>

@endsection
