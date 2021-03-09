<div class="content">
	<h3 class="tit">장바구니</h3>
	<span class="txinfo">총 <strong id="mycart_cnt">1개</strong>의 상품이 장바구니에 있습니다.</span>
	<div class="check" style="text-align:left;margin-top:10px;margin-bottom: 10px;">
		<input type="checkbox" id="check0" name="check0" onclick="CheckAll()">
		<label for="check0"><span></span></label>전체선택
		<button type="submit" class="betgobt all_del_btn" onclick="mobile_cart_delete();">선택삭제</button>
	</div>
	<div class="orderbox" id="mycart-list">
		<!--div class="obox">
			<div class="check">
				<input type="checkbox" id="check18" name="del_unit[]" value="18">
				<label for="check18"><span></span></label>
			</div>
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="cate">회화</span></li>
					<li>보이지 않는 연인들</li>
					<li>작가명 : 살바도르 달리</li>
				</ul>
			</div>
			<div class="ocash">
				<ul>
					<li><em class="coin">c</em>1,235,000</li>
					<li><em class="krw">w</em>1,235,000</li>
				</ul>
			</div>
			
			<div class="obtn kr">
				<button type="submit" class="yellow">베팅하기</button>
				<button type="submit" class="darkgray">구매하기</button>
				<button type="submit" class="gray">보러가기</button>
			</div>
		</div>
		<div class="obox">
			<div class="check">
				<input type="checkbox" id="check18" name="del_unit[]" value="18">
				<label for="check18"><span></span></label>
			</div>
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="cate">회화</span></li>
					<li>보이지 않는 연인들</li>
					<li>작가명 : 살바도르 달리</li>
				</ul>
			</div>
			<div class="ocount">
				<ul>
					<li class="en"><img src="{{asset('/storage/image/mobile/ic_comment.png')}}" alt=""/> 15</li>
					<li class="en"><img src="{{asset('/storage/image/mobile/ic_view.png')}}" alt=""/> 2</li>
				</ul>
			</div>
			<div class="ocash">
				<h3>현재 최고 베팅가 <a href="" class="more">내역보기</a></h3>
				<ul>
					<li><em class="coin">c</em>1,235,000</li>
					<li><em class="krw">w</em>1,235,000</li>
				</ul>
			</div>
			
			<div class="obtn kr">
				<button type="submit" class="yellow">베팅하기</button>
				<button type="submit" class="darkgray">구매하기</button>
				<button type="submit" class="gray">보러가기</button>
			</div>
		</div-->
	</div>
	<a href="#" class="more" onclick="mobile_mypage_cart();" id = "my_cart_more_btn" >더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
	
</div>