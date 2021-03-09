<div class="content">
	<h3 class="tit">주문내역</h3>
	<span class="txinfo">총 <strong id="mybuy_cnt">3개</strong>의 주문내역이 있습니다.</span>
	<div class="process">
		<ul>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_order.png')}}" alt=""/><em id="mybuy_request">1</em>주문신청</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_stop.png')}}" alt="" /><em id="mybuy_ready">1</em>배송대기</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_ing.png')}}" alt="" /><em id="mybuy_ing">1</em>배송중</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_end.png')}}" alt="" /><em id="mybuy_end">1</em>배송완료</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_cancel.png')}}" alt="" /><em id="mybuy_cancel">1</em>주문취소</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_ok.png')}}" alt="" /><em id="mybuy_finish">1</em>구매확정</span></li>
		</ul>
	</div>
	<div class="filter">
		<div class="unit">
			<button type="submit" class="" onclick="mobile_mypage_buy_list(1,1);">1일</button>
			<button type="submit" class="" onclick="mobile_mypage_buy_list(7,1);">1주일</button>
			<button type="submit" class="" onclick="mobile_mypage_buy_list(30,1);">1개월</button>
			<button type="submit" class="" onclick="mobile_mypage_buy_list(365,1);">1년</button>
		</div>
		<div class="date kr">
			<form action="" method="post">
				<span><em>시작일</em><input type="date" value="{{ date('Y-m-d', strtotime('-1 month')) }}" id="mybuy_from_date" onchange="mobile_mypage_buy_list(0,1);" min="2018-09-21" class="datepic k"></span>
				<span><em>종료일</em><input type="date" value="{{ date('Y-m-d', strtotime('now')) }}" id="mybuy_to_date" onchange="mobile_mypage_buy_list(0,1);" min="2018-09-21" class="datepic"></span>
			</form>
		</div>
		<div class="state">
			<div class="select">
				<select name="status" id="mybuy_status" onchange="mobile_mypage_buy_list(0,1);">
					<option value="-1">전체</option>
					<option value="0">주문신청</option>
					<option value="1">배송대기</option>
					<option value="2">배송중</option>
					<option value="3">배송완료</option>
					<option value="4">주문취소</option>
					<option value="5">구매확정</option>
				</select>
				<div class="select__arrow"></div>
			</div>
		</div>
		
	</div>
	<div class="orderbox" id="mybuy-list">
	</div>
	<a href="#" class="more"  onclick="mobile_mypage_buy_list(0,0);" id = "mybuy_more_btn">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
</div>