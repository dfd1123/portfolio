@extends('mobile.layouts.app')

@section('content')
<div class="main-content">
	<div class="vis-container">
		<div class="swiper-wrapper">
		@foreach($events as $event)
			<div class="visimg">
				<h3>{{$event->title}}</h3>
				<p>
					<em>
						{{str_limit(str_replace($pattern,'',$event->body),35)}}
					</em>
					<div class="protect_img" onclick="location.href='{{route('events.show',$event->id)}}'">
						<img src="{{asset('storage/event/'.$event->mobile_banner)}}" alt=""/>
					</div>
				</p>
			</div>
		@endforeach
		</div>
		<div class="swiper-pagination"></div>
	  
	</div>

	<div class="category">
		<h2>카테고리</h2>
		 <div class="cate-container">
			<div class="swiper-wrapper">
				{{--@for($i=1;$i<=2;$i++)--}}
					@foreach($categorys as $category)
						<div class="swiper-slide iccate" onclick="location.href='{{route('products.search_list',$category->id)}}'"><img src="{{asset('storage/image/'.$category->ca_icon)}}" alt="{{$category->ca_name}}"/>{{$category->ca_name}}</div>
					@endforeach
				{{--@endfor--}}
			</div>
		</div>
		<div class="cmore cate-next"><img src="{{asset('/storage/image/mobile/ic_latest_go.png')}}" alt=""/></div>
	</div>
	<div class="mainbest">
		<h2>베팅중인작품</h2>
		<a href="{{route('product.batting_list',["ca_id"=>0, "status"=>1])}}" class="bmore en">more <img src="{{asset('/storage/image/mobile/ic_view.png')}}" alt=""/></a>
		<div class="best-container">
			<div class="swiper-wrapper">
			@forelse($product_battings as $key => $product_batting)
						@if($key == 0)
						<div class="swiper-slide">
							<div class="betart">
								<a href="{{route('products.show',$product_batting->id)}}">
									@if($product_batting->get_like != 0)
									<span class="mark"><img src="{{asset('/storage/image/mobile/img_best_mark.png')}}" alt=""/></span>								
									@endif
									<p>
										<em>[{{$product_batting->category->ca_name}}] <strong>{{$product_batting->title}}</strong></em>
										<img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/>
									</p>
								</a>
							</div>
						</div>
						@elseif($key == 1)
						<div class="swiper-slide">
							<div class="betart">
								<a href="{{route('products.show',$product_batting->id)}}">
									@if($product_batting->get_like != 0)
									<span class="mark"><img src="{{asset('/storage/image/mobile/img_no2_mark.png')}}" alt=""/></span>								
									@endif
									<p>
										<em>[{{$product_batting->category->ca_name}}] <strong>{{$product_batting->title}}</strong></em>
										<img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/>
									</p>
								</a>
							</div>
						</div>
						@elseif($key == 2)
						<div class="swiper-slide">
							<div class="betart">
								<a href="{{route('products.show',$product_batting->id)}}">
									@if($product_batting->get_like != 0)
									<span class="mark"><img src="{{asset('/storage/image/mobile/img_no3_mark.png')}}" alt=""/></span>								
									@endif
									<p>
										<em>[{{$product_batting->category->ca_name}}] <strong>{{$product_batting->title}}</strong></em>
										<img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/>
									</p>
								</a>
							</div>
						</div>
						@else
						<div class="swiper-slide">
							<div class="betart">
								<a href="{{route('products.show',$product_batting->id)}}">
									<p>
										<em>[{{$product_batting->category->ca_name}}] <strong>{{$product_batting->title}}</strong></em>
										<img src="{{asset('storage/image/product/'.$product_batting->image1)}}" alt=""/>
									</p>
								</a>
							</div>
						</div>
						@endif
							
					@empty
						베팅중인 작품이 없습니다.
					@endforelse
			</div>
		</div>
	</div>
	<div class="main-gallery">
		<h2>판매중인 작품</h2>
		<a href="{{route('product_list.sel_product',0)}}" class="bmore en">more <img src="{{asset('/storage/image/mobile/ic_view.png')}}" alt=""/></a>
		<div class="grid kr">
			@foreach($products as $product)
				<div class="grid-item"><a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('storage/image/product/'.$product->image1)}}" alt=""/></p><span>{{$product->category->ca_name}}<strong>{{$product->title}}</strong></span></a></div>
			@endforeach
		</div>
	</div>
	<div class="mcenter">
		<h2 class="en">center</h2>
		<ul>
			<li><a href="{{route('notice.list')}}" class="en"><img src="{{asset('/storage/image/mobile/ic_main_bottom01.png')}}" alt=""/>notice</a></li>
			<li><a href="{{ $video->video_link }}" class="en" target="_blank"><img src="{{asset('/storage/image/mobile/ic_main_bottom02.png')}}" alt=""/>movie</a></li>
			<li><a href="{{route('howtouse.howtouse')}}" class="en"><img src="{{asset('/storage/image/mobile/ic_main_bottom03.png')}}" alt=""/>guide</a></li>
		</ul>
	</div>
	<div class="mcall">
		<p class="en"><a href="tel:{{$company->phone_num}}">{{$company->phone_num}}</a></p>
		<i><img src="{{asset('/storage/image/mobile/ic_call.png')}}" alt=""/></i>
	</div>
</div>
<style>
	#wrap{
		padding: 40px 0 60px 0;
		background:#fff;
	}
</style>
  <script>
		
    var swiper = new Swiper('.vis-container', {
      slidesPerView: 1,
      spaceBetween: 30,
      slidesPerGroup: 1,
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
	var swiper = new Swiper('.cate-container', {
      slidesPerView: 4,
      spaceBetween: 30,
      centeredSlides: true,
	  navigation: {
        nextEl: '.cate-next',
      },
   
    });
	 var swiper = new Swiper('.best-container', {
      slidesPerView: 'auto',
      spaceBetween: 30,
      
		});

  </script>
@endsection
