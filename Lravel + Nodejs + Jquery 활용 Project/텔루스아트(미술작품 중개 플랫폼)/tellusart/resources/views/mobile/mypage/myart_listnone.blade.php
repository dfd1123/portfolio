@extends('layouts.app')

@section('content')
<div class="sub_spot mypage">
	<h2>마이페이지</h2>
</div>
<div id="container">
	@include('mypage.include.my_common')
		<div class="cartbox">
			<h3 class="mytit">내 작품</h3>
			<span class="titm_txt">총 <strong>20건</strong>의 등록된 내 작품이 있습니다.</span>
		</div>
	
		<!-- 내작품 리스트 -->
		<div class="myartbox">
			<h4><strong>베팅중 내 작품</strong></h4>
			<div class="glist">
				<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_big.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별연인, 바다, 이별연인, 바다, 이별연인, 바다, 이별연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
				<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
					<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_md.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
				<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
			</div>
			<div class="morebt"><a href="">more view <i class="fal fa-plus"></i></a></div>
		</div>
		<!-- //내 작품 리스트 -->
		<div class="myartbox">
			<h4><strong>베팅마감된 내 작품</strong></h4>
			<div class="glist">
				<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_big.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별연인, 바다, 이별연인, 바다, 이별연인, 바다, 이별연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
				<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
					<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_md.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
				<div class="item">
					<a href=""><p><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/homepage/img_peo_profile.jpg')}}" alt="작가프로필사진"/></p>
						<ul>
							<li>연인, 바다, 이별</li>
							<li><em>작가명 : 사실주의</em><em>사이즈 : 2014 X 250cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>1253254</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> 1</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> 2,345,345</li>
							<li><em class="kric">￦</em> 345,345</li>
						</ul>
						
					</div>
				</div>
			</div>
			<div class="morebt"><a href="">more view <i class="fal fa-plus"></i></a></div>
		</div>
		<!-- //내 작품 리스트 -->
	</div>
</div>



@endsection

@section('main_script')
<script>
	$('.glist').masonry({ 
		itemSelector: '.item',
		columnWidth: 5,
	});

	
</script>
@endsection
