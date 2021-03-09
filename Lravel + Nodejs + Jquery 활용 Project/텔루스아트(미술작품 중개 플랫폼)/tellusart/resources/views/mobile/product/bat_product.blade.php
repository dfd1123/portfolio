@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">
	<div class="state">
		<div class="select">
			<select id="select-category" name="status">
				<option value="0" {{($ca_id == 0)?'selected':''}}>전체</option>
				@foreach($categorys as $category)
				<option value="{{$category->id}}" {{($ca_id == $category->id)?'selected':''}}>{{$category->ca_name}}</option>
				@endforeach
			</select>
			<div class="select__arrow"></div>
		</div>
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
		<p class="ingart">총 <strong>{{ $product_cnt }}개</strong>의 작품이 {{ $status == 1 ? '베팅중 입니다.' : ($status == 0 ? date("m월 d일", strtotime("next Monday")).'부터 베팅예정 입니다.' : '베팅종료 되었습니다.' ) }}</p>
	</div>
	<span class="warning"><i class="fal fa-comment-alt-smile"></i>베팅킹은 좋아요 숫자가 아닌 베팅 총액 기준입니다.</span>
	<div class="sub-gallery">
		<ul class="tabs w30">
			@if($status==1)
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>1])}}" class="active">베팅중</a></li>
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>0])}}">베팅예정</a></li>
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>2])}}">베팅종료</a></li>	
			@elseif($status==0)
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>1])}}">베팅중</a></li>
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>0])}}" class="active">베팅예정</a></li>
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>2])}}">베팅종료</a></li>	
			@elseif($status==2)
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>1])}}">베팅중</a></li>
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>0])}}">베팅예정</a></li>
				<li><a href="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>2])}}" class="active">베팅종료</a></li>	
			@endif
		</ul>
		<div id="product_list" class="grid kr"></div>
		<a id="product-show-more" class="more" style="display: none;">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""></a>
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

<template id="grid-item-template">
	<div class="grid-item">
		<div>
			<a href="" class="item-show"><p class="is-loading"><img class="image" src="{{--asset('/storage/image/mobile/img_pic_sm.png')--}}" alt=""/></p><span><span class="product-category"><!--회화--></span><strong class="title"><!--보이지않는 연인들--></strong></span></a>
			<div class="price en">
				<ul>
					<li><em class="coinic">c</em> <span class="coinic-value"><!--2,345,345--></span></li>
					<li><em class="kric">￦</em> <span class="kric-value"><!--345,345--></span></li>
				</ul>
				<a href="#popupcux" class="modaltrigger betbt kr"><!--베팅--></a>
			</div>
		</div>
	</div>
</template>

<div class="search_bottom">
	<a href="#search_popup" id="search_popup_btn">
		<i class="fal fa-search"></i>
	</a>
</div>

<div class="cux_modal" id="search_popup" style="display:none;">
	<div class="cux_modal_dialog">
		<form method="get" action="{{route('product.batting_list',["ca_id"=>$ca_id, "status"=>$status])}}">
			<div class="search_content">
				<input type="text" name="keyword" id="skeyword" value="{{$skeyword}}" placeholder="작가명, 작품명, 관련 단어 등으로 검색" />
				<button type="submit"><i class="fal fa-search"></i></button>
			</div>
		</form>
		<div></div>
	</div>
</div>

<style>
	#wrap {
		padding: 40px 0 60px 0;
	}
</style>
<script>
	$(document).ready(function(){
		var theDaysBox  = $("#numdays");
		var theHoursBox = $("#numhours");
		var theMinsBox  = $("#nummins");
		var theSecsBox  = $("#numsecs");

		var refreshId = setInterval(function() {
			var currentSeconds = theSecsBox.text();
			var currentMins    = theMinsBox.text();
			var currentHours   = theHoursBox.text();
			var currentDays    = theDaysBox.text();
			
			if(currentSeconds == 0 && currentMins == 0 && currentHours == 0 && currentDays == 0) {
			
			} else if(currentSeconds == 0 && currentMins == 0 && currentHours == 0) {
			
			theDaysBox.html(currentDays-1);
			theHoursBox.html("23");
			theMinsBox.html("59");
			theSecsBox.html("59");
			} else if(currentSeconds == 0 && currentMins == 0) {
			
			theHoursBox.html(currentHours-1);
			theMinsBox.html("59");
			theSecsBox.html("59");
			} else if(currentSeconds == 0) {

			theMinsBox.html(currentMins-1);
			theSecsBox.html("59");
			} else {
			theSecsBox.html(currentSeconds-1);
			}
		}, 1000);

		refresh_products('{{$uid}}', '{{$ca_id}}', '{{$status}}', 0, 10);
	});

	$('#select-category').change(function(e){
		document.location = "/products/batting/" + $(e.target).val() + "/1";
	});

	$('#product-show-more').click(function(e) {
		var list = $("#product_list");
		var itemCount = list.children().length;
		refresh_products('{{$uid}}', '{{$ca_id}}', '{{$status}}', itemCount, 10);
	});
</script>

@endsection