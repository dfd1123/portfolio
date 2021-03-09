<div class="content">
	<h3 class="tit">베팅한 건수</h3>
	<span class="txinfo">총 <strong id="my_batting_cnt">0개</strong>의 베팅내역이 있습니다.</span>
	<div class="process">
		<ul class="mybatting">
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_bing.png')}}" alt=""/><em id="my_batting_ing_cnt">1</em>베팅중</span></li>
			<li><span><img src="{{asset('/storage/image/mobile/ic_process_bend.png')}}" alt=""/><em id="my_batting_end_cnt">1</em>베팅마감</span></li>
		</ul>
	</div>
	<div class="filter">
		<div class="unit">
			<button type="button" class="date_term" onclick="mobile_mypage_batting(1,1);">1일</button>
			<button type="button" class="date_term" onclick="mobile_mypage_batting(7,1);">1주일</button>
			<button type="button" class="date_term" onclick="mobile_mypage_batting(30,1);">1개월</button>
			<button type="button" class="date_term" onclick="mobile_mypage_batting(365,1);">1년</button>
		</div>
		<div class="date kr">
			<form action="" method="post">
				<span><em>시작일</em><input type="date" value="{{ date('Y-m-d', strtotime('-1 month')) }}" id="mybatting_from_date" onchange="mobile_mypage_batting(0,1);" min="2018-09-21" class="datepic k"></span>
				<span><em>종료일</em><input type="date" value="{{ date('Y-m-d', strtotime('now')) }}" id="mybatting_to_date" onchange="mobile_mypage_batting(0,1);" min="2018-09-21" class="datepic"></span>
			</form>
		</div>
		<div class="state">
			<div class="select">
				<select name="status" id="mybatting_status" onchange="mobile_mypage_batting(0,1);">
					<option value="0">전체</option>
					<option value="1">베팅중</option>
					<option value="2">베팅마감</option>
				</select>
				<div class="select__arrow"></div>
			</div>
		</div>
	</div>
	<div class="orderbox" id="mybatting-list">
		
		<!--div class="obox">
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="ing">베팅중</span></li>
					<li>보이지 않는 연인들</li>
					<li>작가명 : 살바도르 달리</li>
				</ul>
			</div>
			<div class="ocash mybatting_much">
				<h3>내 베팅금액</h3>
				<ul>
					<li><em class="coin">c</em>1,235,000</li>
				</ul>
			</div>
			
		</div>
		<div class="obox">
			<div class="oinfo">
				<p><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/></a></p>
				<ul>
					<li><span class="end">베팅마감</span></li>
					<li>보이지 않는 연인들</li>
					<li>작가명 : 살바도르 달리</li>
				</ul>
			</div>
			<div class="ocash mybatting_much">
				<h3>내 베팅금액</h3>
				<ul>
					<li><em class="coin">c</em>1,235,000</li>
				</ul>
			</div>
		</div-->
	</div>
	<a href="#" class="more" id="myabatting_product_more_btn" onclick="mobile_mypage_batting(0,0);">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""/></a>
</div>