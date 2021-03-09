@extends('layouts.app')

@section('content')
<div class="sub_spot mypage">
	<h2>마이페이지</h2>
</div>
<div id="container">
	@include('mypage.include.my_common')
		<div class="cartbox">
			<h3 class="mytit">내 작품</h3>
			<span class="titm_txt">총 <strong>{{$product_cnt}}건</strong>의 등록된 내 작품이 있습니다.</span>
		</div>
	
		<!-- 내 작품 리스트 -->
		<div class="myartbox">
			<div class="glist">
			@forelse($products->limit(16)->get() as $product)
				<div id="pro_{{$product->id}}" class="item">
					<a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('/storage/image/product/'.$product->image1)}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('/storage/image/'.Auth::user()->profile_img)}}" alt="작가프로필사진"/></p>
						<ul>
							<li>{{$product->title}}</li>
							<li><em>작가명 : {{Auth::user()->name}}</em><em>사이즈 : {{$product->art_width_size}} X {{$product->art_height_size}}cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>{{$product->battings->count()}}</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> {{$product->reviews->count()}}</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> {{round((($product->cash_price)/31), 4)}}</li>
							<li><em class="kric">￦</em> {{$product->cash_price}}</li>
						</ul>
					</div>
					<div class="del">
						<a href="{{route('products.edit',$product->id)}}">수정</a><a href="{{route('product.delete',$product->id)}}">삭제</a>
					</div>
				</div>
			@empty
				해당되는 작품이 없습니다.	
			@endforelse
			</div>
		</div>
	</div>
</div>



@endsection

@section('main_script')
<script>
	var $grid = $('.glist').masonry({
	    itemSelector: '.item',
		columnWidth: 5
	  });
	
	$grid.imagesLoaded()
		.always( function( instance ) {
		    $('.glist .item a p').removeClass('is-loading');
		}).progress( function() {
			$grid.masonry('layout');
			
		});

	
</script>
@endsection
