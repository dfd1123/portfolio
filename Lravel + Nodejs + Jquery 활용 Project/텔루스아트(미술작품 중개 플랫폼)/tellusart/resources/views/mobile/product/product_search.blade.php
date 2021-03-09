@extends('mobile.layouts.app')

@section('content')

<div class="sub_category sub-header">
		<div class="cate-container">
		<div class="swiper-wrapper">
			<div class="swiper-slide iccate licate_item"><a href="{{route('product_list.sel_product',-1)}}" class="{{($ca_id == -1)?'active':''}}">전체</a></div>
			@foreach($categorys as $category)
				<div class="swiper-slide iccate licate_item"><a href="{{route('product_list.sel_product',$category->id)}}" class="{{($ca_id == $category->id)?'active':''}}">{{$category->ca_name}}</a></div>
			@endforeach
		</div>
	</div>
</div>

<div class="sub-container">
	
	<div class="sub-gallery">
		@if($product_cnt == 0)
			<div class="ending_time result amount_cnt kr hidden">
				<span class="notfound">검색된 작품이 없습니다.</span>
			</div>
		@else
			<div class="ending_time result amount_cnt kr">
				<p class="ingart">총 <strong>{{$product_cnt}}개</strong>의 작품이 검색되었습니다.</p>
			</div>
		@endif
		<div class="grid glist kr" data-offset="{{$offset}}" data-count="{{$product_cnt}}" data-caid="{{$ca_id}}">
			@foreach($products as $product)
				<div id="bat_item{{$product->id}}" class="grid-item">
					<div>
						<a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('/storage/image/product/'.$product->image1)}}" alt=""/></p><span>{{$product->category->ca_name}}<strong>{{$product->title}}</strong></span></a>
						<div class="price en">
							<ul>
								<li><em class="coinic">c</em> {{number_format(round($product->coin_price,3),3)}}</li>
								<li><em class="kric">￦</em> {{number_format($product->cash_price)}}</li>
							</ul>
							@if($product->batting_yn != 0)
								@if($product->batting_status == 0)
									<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt ready kr" onclick="alert('다음주부터 베팅 예정인 작품입니다.')">예정</button>
								@elseif($product->batting_status == 1)
									@guest
										<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt kr" onclick="alert('로그인을 하셔야 베팅이 가능합니다.');location.href='{{route('login')}}';">베팅</button>
									@else
										@if($product->battings->count() > 0)
											<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt ok kr" onclick="batting_load({{$product->id}})">완료</button>
										@else
											<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt kr" onclick="batting_do({{$product->id}})">베팅</button>
										@endif
									@endguest
								@elseif($product->batting_status == 2)
									<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt end kr" onclick="alert('베팅이 종료된 작품입니다.')">종료</button>
								@endif
							@else
								<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt not kr" onclick="alert('베팅을 신청하지 않은 작품입니다.')">불가</button>
							@endif
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	<div id="product_load" class="loading dot" style="display:none;">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>
</div>

<div class="search_bottom">
	<a href="#search_popup" id="search_popup_btn">
		<i class="fal fa-search"></i>
	</a>
</div>

<div class="cux_modal" id="search_popup" style="display:none;">
	<div class="cux_modal_dialog">
		<form id="search_item" method="get" action="{{route('products.search_list',$ca_id)}}">
			<div class="search_content">
				<input type="text" name="keyword" id="skeyword" value="{{$skeyword}}" placeholder="작가명, 작품명, 분야 등으로 검색" />
				<button type="submit"><i class="fal fa-search"></i></button>
			</div>
		</form>
		<div></div>
	</div>
</div>
<script>
	var search_swiper = new Swiper('.cate-container', {
		slidesPerView: 'auto',
		spaceBetween: 30,
		centeredSlides: true,
   
    });

</script>

<script src="{{asset('/js/mobile/sblist_infinite_scroll.js')}}"></script>

@endsection