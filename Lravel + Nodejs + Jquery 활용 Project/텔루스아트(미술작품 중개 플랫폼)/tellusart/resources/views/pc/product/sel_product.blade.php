@extends('pc.layouts.app')

@section('content')
<div class="sub_spot gallery" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>갤러리</h2>
	<div class="search_form">
		<form method="get" action="{{route('product_list.sel_product',$ca_id)}}">
			<input type="text" name="keyword" id="skeyword" value="{{$skeyword}}" placeholder="작가명, 작품명, 관련 단어 등으로 검색"/>
			<span><button type="submit">search<i class="fas fa-search"></i></button></span>
		</form>
	</div>
</div>
<div id="container">
	<div id="tab2">
		<ul>
			<li><a href="{{route('product_list.sel_product',0)}}" class="{{($ca_id == 0)?'on':''}}">전체</a></li>
			@foreach($categorys as $category)
				<li><a href="{{route('product_list.sel_product',$category->id)}}" class="{{($ca_id == $category->id)?'on':''}}">{{$category->ca_name}}</a></li>
			@endforeach

		</ul>
	</div>
	
	<div class="gallist">
		<p class="galnm">총 <strong>{{number_format($product_cnt)}} 개</strong>의 작품이 등록되어있습니다.</p>
		<div class="tab-contents animated  fadeInRight">
			<div class="glist" data-offset="{{$offset}}" data-count="{{$product_cnt}}" data-caid="{{$ca_id}}">
				@forelse($products as $product)
				<div class="item">
					<a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('storage/image/product/'.$product->image1)}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('storage/image/'.$product->user->profile_img)}}" alt="작가프로필사진"/></p>
						<ul>
							<li>{{$product->title}}</li>
							<li><em>작가명 : {{$product->artist_name}}</em><em>사이즈 : {{$product->art_width_size}} X {{$product->art_height_size}}cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href="#"><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>{{$product->get_like}}</li>
							<li><a href="#"><i class="far fa-comment-alt"></i></a> {{$product->reviews->count()}}</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> {{number_format(round($product->coin_price,3),3)}}</li>
							<li><em class="kric">￦</em> {{number_format($product->cash_price)}}</li>
						</ul>
						
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
</div>

<script src="{{asset('/js/pc/glist_infinite_scroll.js')}}"></script>

@endsection

