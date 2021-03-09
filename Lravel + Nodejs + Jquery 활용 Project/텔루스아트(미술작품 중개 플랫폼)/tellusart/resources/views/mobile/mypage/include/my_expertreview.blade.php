<div id="my-expertreview-main" class="content">
	<h3 class="tit">나의 전문가 리뷰</h3>
	<span class="txinfo">총 <strong id="my_expertreview_expertreview_cnt">0개</strong>의 전문가 리뷰가 있습니다.</span>
	
	<div id="my-expertreview-list" class="orderbox">
	</div>
	<a id="my-expertreview-show-more" href="javascript:mobile_mypage_expertreview_list()" class="more" style="display: none;">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
</div>

<style>
	.obox li{display:block;margin-bottom:3px;text-align:left;}
	.obox li img{border-radius:100px;max-width:60px;}
	.obox li:nth-of-type(1){color:#888888;font-size:13px;}
	.obox li:nth-of-type(1) strong{color:#222;}
	.obox li:nth-of-type(2){color:#222;font-size:15px;}
	.obox li:nth-of-type(3){color:#666;font-size:13px;line-height:150%;}
	.obox li.star i{display:inline-block;margin-right:0px;max-width:18px;}
	.obox li.star i img{max-width:100%;}
	.obox li.star span{display:inline-block;color:#fe3803;font-size:15px;vertical-align:middle;font-weight:bold;margin-left:5px;}
</style>

<template id="my-expertreview-item">
	<div>
		<div class="o_date kr">
			<span>작성일 : <strong>2019.03.30</strong></span>
		</div>
		<div class="obox">
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="cate item-category"><!--회화--></span></li>
					<li class="item-title"><!--보이지 않는 연인들--></li>
					<li>작가명 : <span class="item-artist-name"><!--살바도르 달리--></span></li>
				</ul>
			</div>
			<li class="star">
				<span class="columlist-star05" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star1" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star15" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star2" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star25" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star3" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star35" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star4" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_off.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star45" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_half.png')}}" alt=""/></i>
				</span>
				<span class="columlist-star5" style="display: none">
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
					<i><img src="{{asset('/storage/image/mobile/ic_star_on.png')}}" alt=""/></i>
				</span>
				<span class="en columlist-star-num">1.5</span>
			</li>
			<div class="commentbox"></div>
			<div class="obtn w50 kr">
				<a href='#popupcux5' class="yellow commm_modify">수정하기</a>
				<button type="button" class="gray commm_delete">삭제하기</button>
			</div>
		</div>
	</div>
</template>