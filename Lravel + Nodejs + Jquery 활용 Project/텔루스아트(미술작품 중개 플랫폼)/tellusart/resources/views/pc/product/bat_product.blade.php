@extends('pc.layouts.app')

@section('content')

<div class="sub_spot betting" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>베팅</h2>
	<div class="search_form">
		<form method="get" action="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>$status])}}">
			<input type="text" id="skeyword" name="keyword" value="{{$skeyword}}" placeholder="작가명, 작품명, 소개 등으로 검색"/>
			<span><button type="submit"value="search">search<i class="fas fa-search"></i></button></span>
		</form>
	</div>
</div>
<div id="container">
		<div id="tab2">
			<ul>
				<li><a href="{{route('product.batting_list',["ca_id"=>0, "status"=>1])}}" class="{{($ca_id == 0)?'on':''}}">전체</a></li>
				@foreach($categorys as $category)
					<li><a href="{{route('product.batting_list',["ca_id"=>$category->id, "status"=>1])}}" class="{{($ca_id == $category->id)?'on':''}}">{{$category->ca_name}}</a></li>
				@endforeach
			</ul>
		</div>
		<div class="ending_time kr">
			<h3>베팅 남은시간</h3>
			<div id="clock-ticker" class="en">
				<div class="block">
					<span class="flip-top" id="numdays">{{$days}}</span>
					<em class="label">Days</em>
				</div>
				
				<div class="block">
					<span class="flip-top" id="numhours">{{$hours}}</span>
					<em class="label">Hours</em>
				</div>
				
				<div class="block">
					<span class="flip-top" id="nummins">{{$mins}}</span>
					<em class="label">Mins</em>
				</div>
				
				<div class="block">
					<span class="flip-top" id="numsecs">{{$secs}}</span>
					<em class="label">Secs</em>
				</div>
			</div>
			<p class="ingart">총 <strong>{{number_format($product_cnt)}}개</strong>의 작품이 {{ $status == 1 ? '베팅중 입니다.' : ($status == 0 ? date("m월 d일", strtotime("next Monday")).'부터 베팅예정 입니다.' : '베팅종료 되었습니다.' ) }}</p>
		</div>
		<span class="warning"><i class="fal fa-comment-alt-smile"></i>베팅킹은 좋아요 숫자가 아닌 베팅 총액 기준입니다.</span>
		<div class="betlist">
			<div class="big_tab">
				<ul class="kr">
					@if($status==1)
						<li class="activeClass"><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>1])}}">베팅중</a></li>
						<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>0])}}">베팅예정</a></li>
						<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>2])}}">베팅종료</a></li>	
					@elseif($status==0)
						<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>1])}}">베팅중</a></li>
						<li class="activeClass"><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>0])}}">베팅예정</a></li>
						<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>2])}}">베팅종료</a></li>	
					@elseif($status==2)
						<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>1])}}">베팅중</a></li>
						<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>0])}}">베팅예정</a></li>
						<li class="activeClass"><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>2])}}">베팅종료</a></li>	
					@endif	
				</ul>
				
			</div>
			<div id="subtab01" class="tab-contents animated  fadeInRight">
				<div class="blist" data-offset="{{$offset}}" data-count="{{$product_cnt}}" data-caid="{{$ca_id}}" data-status="{{$status}}">
					@forelse($products as $product)
						<div id="bat_item{{$product->id}}" class="item">
							<a href="{{route('products.show',$product->id)}}"><p class="is-loading"><img src="{{asset('/storage/image/product/'.$product->image1)}}" alt=""/></p></a>
							<div class="peo_txt">
								<p><img src="{{asset('/storage/image/'.$product->user->profile_img)}}" alt="작가프로필사진"/></p>
								<ul>
									<li>{{$product->title}}</li>
									<li><em>작가명 : {{$product->artist_name}}</em><em>사이즈 : {{$product->art_width_size}} X {{$product->art_height_size}}cm</em></li>
								</ul>
							</div>
							<div class="action_rv en">
								<ul>
									<li id="bat_cnt{{$product->id}}"><a href="#"><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>{{$product->battings->count()}}</li>
									<li><a href="#"><i class="far fa-comment-alt"></i></a> {{$product->reviews->count()}}</li>
								</ul>
							</div>
							<div class="price en batting_modal_wrap">
								<ul>
									<li><em class="coinic">c</em> {{number_format(round($product->coin_price,3),3)}}</li>
									<li><em class="kric">￦</em> {{number_format($product->cash_price)}}</li>
								</ul>
								@if($status == 1)
									@if($uid == NULL)
										<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt kr" onclick="alert('로그인을 하셔야 베팅이 가능합니다.');location.href='{{route('login')}}';">베팅</button>
									@elseif(($product->battings->count())==0)
										<!--<button type="button" id="batting_btn{{$product->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_do({{$product->id}})">베팅</button>-->
										<a href="#popupcux" id="batting_btn{{$product->id}}" class="modaltrigger batting_btn betbt kr" onclick="batting_do({{$product->id}})">베팅</a>
									@else
										<a href="#popupcux" id="batting_btn{{$product->id}}" class="modaltrigger batting_btn betbt ok kr" onclick="batting_load({{$product->id}})">완료</a>
									@endif
								@elseif($status == 0)
									<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt kr">예정</button>
								@elseif($status == 2)
									<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt kr">종료</button>
								@endif
							</div>
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
</div>

<div class="cux_modal" id="popupcux" style="display:none;">
	<div class="cux_modal_dialog">
		<h2>베팅하기</h2>
		 <!-- Modal content-->
		 <dl>
			<dt><i class="fal fa-chevron-circle-right"></i>베팅금액</dt>
			<dd><input type="text" name="batting_price" placeholder="최소 1 TLG" class="required kr"/></dd>
		 </dl>
		 <div class="footer_btn">
		 	<button id="batting_price_submit" class="cashgo">베팅하기</button>
		 </div>
	</div>
</div>


<script src="{{asset('/js/pc/blist_infinite_scroll.js')}}"></script>

@endsection

