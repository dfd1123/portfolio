@extends('pc.layouts.app')

@section('content')

<div class="sub_spot betting" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>작품검색</h2>
	<div class="search_form">
		<form id="search_item" method="get" action="{{route('products.search_list',$ca_id)}}">
			<input type="text" id="skeyword" name="keyword" value="{{$skeyword}}" placeholder="작가명, 작품명, 분야 등으로 검색"/>
			<span><button type="submit">search<i class="fas fa-search"></i></button></span>
		</form>
	</div>
</div>
<div id="container">
	<div id="tab2">
		<ul>
			<li><a href="{{route('products.search_list',-1)}}" class="{{($ca_id == -1)?'on':''}}">전체</a></li>
			@foreach($categorys as $category)
				<li><a href="{{route('products.search_list',$category->id)}}" class="{{($ca_id == $category->id)?'on':''}}">{{$category->ca_name}}</a></li>
			@endforeach
		</ul>
	</div>

	<div class="betlist">
		<div id="subtab01" class="tab-contents animated  fadeInRight">
			<div class="blist" data-offset="{{$offset}}" data-count="{{$product_cnt}}" data-caid="{{$ca_id}}">
				@forelse($products as $product)
					<div id="bat_item{{$product->id}}" class="item">
						<a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('/storage/image/product/'.$product->image1)}}" alt=""/></p></a>
						<div class="peo_txt">
							<p><img src="{{asset('/storage/image/'.$product->user->profile_img)}}" alt="작가프로필사진"/></p>
							
							<ul>
								<li><span class="category">{{$product->category->ca_name}}</span>{{$product->title}}</li>
								<li><em>작가명 : {{$product->artist_name}}</em><em>사이즈 : {{$product->art_width_size}} X {{$product->art_height_size}}cm</em></li>
							</ul>
						</div>
						<div class="action_rv en">
							<ul>
								<li><a href="#"><i class="far fa-heart"></i><i class="fas fa-heart"></i></a><span id="bat_cnt{{$product->id}}">{{$product->get_like}}</span></li>
								<li><a href="#"><i class="far fa-comment-alt"></i></a> {{$product->reviews->count()}}</li>
							</ul>
						</div>
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
											<a href="#popupcux" id="batting_btn{{$product->id}}" class="modaltrigger batting_btn betbt ok kr" onclick="batting_load({{$product->id}})">완료</a>
										@else
											<a href="#popupcux" id="batting_btn{{$product->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_do({{$product->id}})">베팅</a>
										@endif
									@endguest
								@elseif($product->batting_status == 2)
									<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt end kr" onclick="alert('베팅이 종료된 작품입니다.')">종료</button>
								@endif
							@else
								<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt not kr" onclick="alert('베팅을 신청하지 않은 작품입니다.')">불가</button>
							@endif
						</div>
						<span id="already_bat_price{{$product->id}}" style="display:none;">0</span>

					</div>
					
				@empty
					해당되는 작품이 없습니다.	
				@endforelse
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
</div>


<div class="cux_modal" id="popupcux" style="display:none;">
		<div class="cux_modal_dialog">
			<h2>베팅하기</h2>
			 <!-- Modal content-->
			 <dl>
				<dt><i class="fal fa-chevron-circle-right"></i>베팅금액</dt>
				<dd><input type="text" name="batting_price" class="required kr"/></dd>
			 </dl>
			 <div class="footer_btn">
			 	<button type="button" class="cashgo">베팅하기</button>
			 </div>
		</div>
	</div>


<script src="{{asset('/js/pc/sblist_infinite_scroll.js')}}"></script>

@endsection