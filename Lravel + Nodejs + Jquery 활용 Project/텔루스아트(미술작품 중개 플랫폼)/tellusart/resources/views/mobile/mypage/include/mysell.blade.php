<div class="content">
	<h3 class="tit">판매내역</h3>
	<span class="txinfo">총 <strong id="mysell_cnt">3개</strong>의 주문내역이 있습니다.</span>
	<div class="process">
		<ul>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_ipstop.png')}}" alt=""/><em id="mysell_request">1</em>입금대기</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_ipok.png')}}" alt=""/><em id="mysell_ready">1</em>입금확인</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_ing.png')}}" alt=""/><em id="mysell_ing">1</em>배송중</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_end.png')}}" alt=""/><em id="mysell_end">1</em>배송완료</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_re.png')}}" alt=""/><em id="mysell_cancel">1</em>환불처리</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_sellok.png')}}" alt=""/><em id="mysell_finish">1</em>판매확정</span></li>
		</ul>
	</div>
	<div class="filter">
		<div class="unit">
			<button type="submit" class="" onclick="mobile_mypage_sell_list(1,1);">1일</button>
			<button type="submit" class="" onclick="mobile_mypage_sell_list(7,1);">1주일</button>
			<button type="submit" class="" onclick="mobile_mypage_sell_list(30,1);">1개월</button>
			<button type="submit" class="" onclick="mobile_mypage_sell_list(365,1);">1년</button>
		</div>
		<div class="date kr">
			<form action="" method="post">
				<span><em>시작일</em><input type="date" value="{{ date('Y-m-d', strtotime('-1 month')) }}" id="mysell_from_date" onchange="mobile_mypage_sell_list(0,1);" min="2018-09-21" class="datepic k"></span>
				<span><em>종료일</em><input type="date" value="{{ date('Y-m-d', strtotime('now')) }}" id="mysell_to_date" onchange="mobile_mypage_sell_list(0,1);" min="2018-09-21" class="datepic"></span>
			</form>
		</div>
		<div class="state">
			<div class="select">
				<select name="status" id="mysell_status" onchange="mobile_mypage_sell_list(0,1);">
					<option value="-1">전체</option>
					<option value="0">입금대기</option>
					<option value="1">입금확인</option>
					<option value="2">배송중</option>
					<option value="3">배송완료</option>
					<option value="4">환불처리</option>
					<option value="5">판매확정</option>
				</select>
				<div class="select__arrow"></div>
			</div>
		</div>
	</div>
	<div class="orderbox" id="mysell-list">
		<!--div class="o_date kr">
			<span>주문번호 : <strong>1254356t211</strong></span>
			<em class="en">2019.03.21</em>
		</div>
		<div class="obox">
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="ing">배송중</span></li>
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
				<button type="submit" class="yellow">주문확정</button>
				<button type="submit" class="darkgray">배송내역보기</button>
				<button type="submit" class="gray">교환신청</button>
			</div>
		</div>
		<div class="o_date kr">
			<span>주문번호 : <strong>1254356t211</strong></span>
			<em class="en">2019.03.21</em>
		</div>
		<div class="obox">
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="stop">배송중</span></li>
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
				<button type="submit" class="yellow">주문확정</button>
				<button type="submit" class="darkgray">배송내역보기</button>
				<button type="submit" class="gray">교환신청</button>
			</div>
		</div>
		<div class="o_date kr">
			<span>주문번호 : <strong>1254356t211</strong></span>
			<em class="en">2019.03.21</em>
		</div>
		<div class="obox">
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="end">배송중</span></li>
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
				<button type="submit" class="yellow">주문확정</button>
				<button type="submit" class="darkgray">배송내역보기</button>
				<button type="submit" class="gray">교환신청</button>
			</div>
		</div-->
	</div>
	<a href="#" class="more"  onclick="mobile_mypage_sell_list(0,0);" id = "mysell_more_btn">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
</div>