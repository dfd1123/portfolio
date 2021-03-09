<div id="mycomment-main" class="content">
	<h3 class="tit">나의 코멘트</h3>
	<span class="txinfo">총 <strong id="mycomment_comment_cnt">0개</strong>의 코멘트가 있습니다.</span>
	
	<div id="mycomment-list" class="orderbox">
	</div>
	<a id="mycomment-show-more" href="javascript:mobile_mypage_comment_list()" class="more">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
</div>

<template id="mycomment-item">
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
			<div class="updown">
				<ul>
					<li><a href="#" class="up" onclick="alert('본인의 코멘트를 추천할 수는 없습니다.');"><i><img src="{{asset('/storage/image/mobile/ic_up.png')}}" alt=""/></i><span class="up-count"><!--2--></span></a></li>
					<li><a href="#" class="down" onclick="alert('본인의 코멘트를 비추천할 수는 없습니다.');"><i><img src="{{asset('/storage/image/mobile/ic_down.png')}}" alt=""/></i><span class="down-count"><!--4--></span></a></li>
				</ul>
			</div>
			<div class="commentbox"></div>
			<div class="obtn w50 kr">
				<a href = '#popupcux4' class="yellow commm_modify">수정하기</a>
				<button type="button" class="gray commm_delete">삭제하기</button>
			</div>
		</div>
	</div>
</template>