@extends('mobile.layouts.app')

@section('content')
<div class="sub_category sub-header">
	<div class="cate-slide">
		<ul>
			<li class="{{($ca_id == 0)?'active':''}}">
				<a href="{{route('product_list.sel_product',0)}}">전체</a>
			</li>
			@foreach($categorys as $category)
				<li class="{{($ca_id == $category->id)?'active':''}}">
					<a href="{{route('product_list.sel_product',$category->id)}}">{{$category->ca_name}}</a>
				</li>
			@endforeach
		</ul>
	</div>
</div>
<div class="sub-container">
	
	<div class="sub-gallery">
		
		
		<div class="grid glist kr" data-offset="{{$offset}}" data-count="{{$product_cnt}}" data-caid="{{$ca_id}}">
			@forelse($products as $product)
			<div class="grid-item">
				<div>
					<a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('storage/image/product/'.$product->image1)}}" alt=""/></p><span>{{$product->category->ca_name}} <strong>{{$product->title}}</strong></span></a>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> {{number_format(round($product->coin_price,3),3)}}</li>
							<li><em class="kric">￦</em> {{number_format($product->cash_price)}}</li>
						</ul>
					</div>
				</div>
			</div>
			@empty
				해당되는 작품이 없습니다.
			@endforelse
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
		<form method="get" action="{{route('product_list.sel_product',$ca_id)}}">
			<div class="search_content">
				<input type="text" name="keyword" id="skeyword" value="{{$skeyword}}" placeholder="작가명, 작품명, 관련 단어 등으로 검색" />
				<button type="submit"><i class="fal fa-search"></i></button>
			</div>
		</form>
		<div></div>
	</div>
</div>

<script>
	var swiper_cate = new Swiper('.cate-container', {
      slidesPerView: 'auto',
      spaceBetween: 30,
      centeredSlides: true,
   
	});
	
	$("#artist_img").change(function(){
		readURLartist(this);
	});

</script>

<script src="{{asset('/js/mobile/glist_infinite_scroll.js')}}"></script>

@endsection