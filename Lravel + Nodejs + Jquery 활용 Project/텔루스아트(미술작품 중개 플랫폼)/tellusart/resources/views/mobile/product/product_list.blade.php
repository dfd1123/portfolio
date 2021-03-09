@extends('layouts.app')

@section('content')

<div class="sub_spot gallery">
	<h2>갤러리</h2>
	<div class="search_form">
		<form>
			<input type="search" placeholder="작가명, 회화명, 분야 등으로 검색"/>
			<span><input type="submit"value="search"><i class="fas fa-search"></i></span>
		</form>
	</div>
</div>
<div id="container">
	<div id="tab2">
		<ul>
			@foreach($categorys as $category)
				<li><a href="{{route('products.index',$category->id)}}" class="{{($ca_id == $category->id)?'on':''}}">{{$category->ca_name}}</a></li>
			@endforeach

		</ul>
	</div>
	
	<div class="gallist">
		<p class="galnm">총 <strong>{{number_format(count($products))}}개</strong>의 작품이 등록되어있습니다.</p>
		<div class="tab-contents animated  fadeInRight">
			<div class="glist">
				@foreach($products as $product)
				<div class="item">
					<a href="{{route('products.show',$product->id)}}"><p><img src="{{asset('storage/image/product/'.$product->image1)}}" alt=""/></p></a>
					<div class="peo_txt">
						<p><img src="{{asset('storage/image/'.$product->user->profile_img)}}" alt="작가프로필사진"/></p>
						<ul>
							<li>{{$product->title}}</li>
							<li><em>작가명 : {{$product->user->name}}</em><em>사이즈 : {{$product->art_width_size}} X {{$product->art_height_size}}cm</em></li>
						</ul>
					</div>
					<div class="action_rv en">
						<ul>
							<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>{{$product->get_like}}</li>
							<li><a href=""><i class="far fa-comment-alt"></i></a> {{$product->reviews->count()}}</li>
						</ul>
					</div>
					<div class="price en">
						<ul>
							<li><em class="coinic">c</em> {{number_format(($product->cash_price)/31)}}</li>
							<li><em class="kric">￦</em> {{number_format($product->cash_price)}}</li>
						</ul>
						
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</div>



@endsection

@section('script')

<script>
		$('.glist').masonry({ 
			itemSelector: '.item',
			columnWidth: 5,
		});
	</script>

@endsection