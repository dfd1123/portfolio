	<div id="left_fix" class="web">
			<div class="openBtn"><i class="far fa-address-book"></i>이<br/>용<br/>방<br/>법<br/><i class="fal fa-angle-right"></i></div>
			@if($howtouse->pc_img1 != NULL || $howtouse->pc_img2 != NULL || $howtouse->pc_img3 != NULL)
				<div class="howtouse_wrap">
					@if($howtouse->pc_img1 != NULL)
						<img src="{{ asset('/storage/image/howtouse/'.$howtouse->pc_img1) }}" />
					@endif
					@if($howtouse->pc_img2 != NULL)
						<img src="{{ asset('/storage/image/howtouse/'.$howtouse->pc_img2) }}" />
					@endif
					@if($howtouse->pc_img3 != NULL)
						<img src="{{ asset('/storage/image/howtouse/'.$howtouse->pc_img3) }}" />
					@endif
				</div>
			@else
				<div class="howtouse_wrap non_howtouse">
					등록된 이용방법이 없습니다.
				</div>
			@endif
		</div>
		<div class="overlay"></div>
	<div id="quickW" class="web">
		<div class="mycoin">
			@guest
				<p>my coin</p>
				<span>-</span>
				<em onclick="location.href='{{ route('login') }}'">로그인</em>
			@else
				<p>my coin</p>
				<span id="my_balance_info">{{ number_format(floor(floatval($coin_balance)*1000)/1000 , 2) }}</span>
				<em>coin</em>
			@endguest
		</div>
		<ul>
			@guest
				<li class="hidden"><a href="{{ route('login') }}"><i class="fal fa-coins"></i>코인충전하기</a></li>
				<li><a href="{{ route('login') }}"><i class="fal fa-wallet"></i>코인관리하기</a></li>
				<li><a href="{{ route('login') }}"><i class="fal fa-id-card"></i>마이페이지</a></li>
				<li><a href="{{ route('login') }}"><i class="fal fa-shopping-cart"></i>장바구니</a></li>
			@else
				<li class="hidden"><a href="#coin_charging" onclick="" id="modaltrigger2"><i class="fal fa-coins"></i>코인충전하기</a></li>
				<li><a href="#coin_edit" onclick="refresh_transactions();" id="modaltrigger3"><i class="fal fa-wallet"></i>코인관리하기</a></li>
				<li><a href="{{ route('mypage.myart_list') }}"><i class="fal fa-id-card"></i>마이페이지</a></li>
				<li>
					<a href="{{ route('mypage.cart') }}">
						<i class="fal fa-shopping-cart"></i>장바구니
						@if($share_cart_cnt > 0)
							<em>{{$share_cart_cnt}}</em>
						@endif
					</a>
				</li>
			@endguest
			<li style="padding-top: 0;">
				<a href="#" onclick="javascript:onPopKBAuthMark();return false;">
					<img src="http://img1.kbstar.com/img/escrow/escrowcmark.gif" border="0" style="width: 60px;">
				</a>
			</li>
		</ul>
		<a href="#" class="scrollToTop">top <i class="fas fa-sort-up"></i></a>
	</div>
	@auth
	<div id="coin_charging" style="display:none;">
		<h2>코인충전하기</h2>
		<section class="cointab">
			<input id="tab-1" type="radio" name="radio-set" class="tab-selector-1" checked="checked" />
			<label for="tab-1" class="tab-label-1">코인충전</label>
			<input id="tab-2" type="radio" name="radio-set" class="tab-selector-2" />
			<label for="tab-2" class="tab-label-2">충전내역</label>
			<div class="coin_content">
				<div class="content-1">
					<!-- 코인충전 -->
					<div class="cashin">
						<dl>
							<dt><i class="fal fa-chevron-circle-right"></i> 코인충전에 사용할 금액</dt>
							<dd><input type="number" title="" id="charge_cash" onkeyup="charge_calcul();" class="required kr" /></dd>
						</dl>
						<dl class="last">
							<dt ><i class="fal fa-chevron-circle-right"></i> 예상되는 최대 Tellus Gold 충전개수 <em>* 최대 개수보다 적을수 있습니다.</em></dt>
							<dd class="fir"><input type="text" title="" id="charge_coin" class="required kr"  disabled="disabled"/></dd>
						</dl>
						<button id="charge_submit" class="cashbt">충전하기</button>
					</div>
					<!-- //코인충전 -->
				</div>
				<div class="content-2">
					<!-- 충전내역 -->
					<div class="cashin" style="height: 390px;"  id="charge_list_div">
						<table>
							<colgroup>
								<col class="">
								<col class="">
								<col class="">
							</colgroup>
							<thead>
								<tr>
									<th>사용한 금액</th>
									<th>충전된 코인</th>
									<th>날짜</th>
								</tr>
							</thead>
							<tbody id = "charge_list">
								<tr>
									<td class="nanot" colspan="3">
										충전 내역이 존재하지 않습니다. 
									</td>
								</tr>
							</tbody>
						</table>
						<!-- 게시판 네비 >
						<div class="paging_board">
							<span class="af">
								<a href=""><i class="fas fa-angle-double-left"></i></a>
								<a href=""><i class="fas fa-angle-left"></i></a>
							</span>
							<ul>
								<li><a href="" class="on">1</a></li>
								<li><a href="" class="">2</a></li>
								<li><a href="" class="">3</a></li>
								<li><a href="" class="">4</a></li>
								<li><a href="" class="">5</a></li>
							</ul>
							<span class="bf">
								<a href=""><i class="fas fa-angle-right"></i></a>
								<a href=""><i class="fas fa-angle-double-right"></i></a>
							</span>
						</div>
						<!-- //게시판 네비 -->
					</div>
					<!-- //충전내역 -->
				</div>
			</div>
		</section>
	</div>
	
	<div id="coin_edit" style="display:none;">
		<h2>코인관리하기</h2>
		<section class="cointab">
			<input id="tab-21" type="radio" name="radio-set2" class="tab-selector-21" checked="checked" />
			<label for="tab-21" class="tab-label-1">코인입출금</label>
			<input id="tab-22" type="radio" name="radio-set2" class="tab-selector-22" />
			<label for="tab-22" class="tab-label-2">입출금내역</label>
			<div class="coin_content">
				<div class="content-1">
					<!-- 코인입출금 -->
					<div class="ipout">
						<p>회원님에게 할당된 아래 주소로 Tellus Gold을 입금할 수 있습니다.</p>
						<div>
							<h4>내 Tellus Gold 입금주소(입금전용)</h4>
							<span>코인을 입금하시려면 아래 QR코드 또는 주소를 사용하세요.</span>
							<ul>
								<li>
									<em><img src = "https://chart.googleapis.com/chart?chs=110x110&cht=qr&chl={{ $coin_address }}&choe=UTF-8"></em>
								</li>
								<li>
									<input type="text" id="address_copy" tabindex="2" title="" value="{{ $coin_address }}" readonly class="required kr"  />	
									<button id="addressCopyButton" data-clipboard-action="copy" data-clipboard-target="#address_copy" class="joinbt">주소복사</button>
								</li>
							</ul>
						</div>
						<dl>
							<dt>출금주소</dt>
							<dd><input type="text" id="address_send" name="" tabindex="2" title="" class="required kr"  />	<button onclick="address_valid();" id="address_valid_btn" class="ylbt">주소체크</button></dd>
							<dt>출금수량(TLG)</dt>
							<dd><input type="number" id="address_send_amount" onkeyup="address_calcul_total();" tabindex="2" title="" class="required kr"  />	<button onclick="address_amount_maximum();" class="ylbt">최대</button></dd>
						</dl>
						<dl>
							<dt>출금수수료(부가세 포함)</dt>
							<dd id="address_send_fee">0</dd>
							<dt>총 출금(수수료 포함)</dt>
							<dd id="address_send_total">0</dd>
						</dl>
						<button type="submit" class="joinbt w95" onclick="address_send();">출금하기</button>
					</div>
					<!-- //코인입출금-->
				</div>
				<div class="content-2">
					<!-- 입출금내역 -->
					<div class="cashin" id="transactions_list_div">
						<table>
							<colgroup>
								<col class="col20">
								<col class="">
								<col class="col20">
							</colgroup>
							<thead>
								<tr>
									<th>입출금</th>
									<th>수량/주소/TXID</th>
									<th>상태/날짜</th>
								</tr>
							</thead>
							<tbody id = "transactions_list">
								@if(isset($transactions))
									@forelse($transactions as $transaction)
										@if($transaction->request_type == 'deposit')
										<tr>
											<td class="blue bold">입금</td>
											<td class="en"><strong>{{$transaction->request_amount}}<em>TLG</em></strong>{{$transaction->request_address ?? ''}}<br>{{$transaction->confirm_tx ?? ''}}</td>
											<td>
												@if($transaction->request_status == 'sell')
												<span class="state ok">판매</span>
												@elseif($transaction->request_status == 'refund')
												<span class="state ok">환불</span>
												@elseif($transaction->request_status == 'batting')
												<span class="state ok">베팅</span>
												@elseif($transaction->request_status == 'reward')
												<span class="state ok">보상</span>
												@else
													@if($transaction->request_status == 'deposit_confirmed')
													<span class="state ok">입금완료</span>
													@else
													<span class="state ok">입금대기</span>
													@endif
												@endif
												<span class="en">{{\Carbon\Carbon::parse($transaction->updated)->format('Y-m-d H:i:s')}}</span>
											</td>
										</tr>
										@else
										<tr>
											<td class="red bold">출금</td>
											<td class="en"><strong>{{$transaction->request_amount}}<em>TLG</em></strong>{{$transaction->request_address ?? ''}}<br>{{$transaction->confirm_tx ?? ''}}</td>
											<td>
												@if($transaction->request_status == 'buy')
												<span class="state okout">구매</span>
												@elseif($transaction->request_status == 'batting')
												<span class="state okout">베팅</span>
												@elseif($transaction->request_status == 'fee')
												<span class="state okout">수수료</span>
												@else
													@if($transaction->request_status == 'withdraw_confirmed')
													<span class="state okout">출금완료</span>
													@else
													<span class="state okout">출금대기</span>
													@endif
												@endif
												<span class="en">{{\Carbon\Carbon::parse($transaction->updated)->format('Y-m-d H:i:s')}}</span>
											</td>
										</tr>
										@endif
									@empty
									{{--
									<tr>
										<td class="nanot" colspan="3">
											transaction 내역이 존재하지 않습니다. 
										</td>
									</tr>
									--}}
									@endforelse
								@endif
							</tbody>
						</table>
						<!-- 게시판 네비
						<div class="paging_board">
							<span class="af">
								<a href=""><i class="fas fa-angle-double-left"></i></a>
								<a href=""><i class="fas fa-angle-left"></i></a>
							</span>
							<ul>
								<li><a href="" class="on">1</a></li>
								<li><a href="" class="">2</a></li>
								<li><a href="" class="">3</a></li>
								<li><a href="" class="">4</a></li>
								<li><a href="" class="">5</a></li>
							</ul>
							<span class="bf">
								<a href=""><i class="fas fa-angle-right"></i></a>
								<a href=""><i class="fas fa-angle-double-right"></i></a>
							</span>
						</div>
						<!-- //게시판 네비 -->
						<!-- 내역이 없을때 -->
						
					</div>
					<!-- //입출금내역 -->
				</div>
			</div>
		</section>
	</div>
	@endauth
	<script >
	$(function(){
		$('#modaltrigger2').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
		$('#modaltrigger3').leanModal({ top: 110, overlay: 0.8, closeButton: ".hidemodal" });
	});
	</script>
	<div id="header">
		<h1><a href="{{ url('/') }}"><img src="{{asset('storage/image/homepage/h1_logo.png')}}" alt="텔루스아트" class="web"/><img src="{{asset('storage/image/homepage/h1_mo_logo.png')}}" alt="텔루스아트" class="mobile"/></a></h1>
		 <nav class="monav">
			<div id="menuToggle">
				<input type="checkbox" />
				<span></span>
				<span></span>
				<span></span>
				<ul id="momenu">
					<li><a href="{{route('product_list.sel_product',0)}}">갤러리</a></li>
					<li><a href="{{route('product.batting_list',["ca_id"=>0, "status"=>1])}}">베팅</a></li>
					<li><a href="{{route('products.create')}}">작품등록</a></li>
					<li><a href="" >로그인</a></li>
					<li><a href="" >회원가입</a></li>
					<li><a href="" >고객센터</a></li>
				</ul>
			</div>
		</nav>
		<div class="lnb">
			<ul>
				<li><a href="{{route('product_list.sel_product',0)}}">갤러리</a></li>
				<li><a href="{{route('product.batting_list',["ca_id"=>0, "status"=>1])}}">베팅</a></li>
				<li><a href="{{route('products.create')}}">작품등록</a></li>
				<li><a href="{{route('notice.list')}}">고객센터</a></li>
			</ul>
		</div>
		<div class="gnb">
			<ul>
				@guest
					<li><a href="{{ route('login') }}"><i class="fal fa-lock"></i> login</a></li>
					<li>
						 @if (Route::has('register'))
						 	<a href="{{ route('register') }}"><i class="fal fa-user-circle"></i> join</a>
						 @endif 
					</li>
				@else
					<li class="nav-item">
                         <a href="{{route('mypage.myinfor')}}">
                              {{ Auth::user()->name }} <span class="caret"></span>
                         </a>
					</li>
					<li>
                         <div>
                             <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                  {{ __('Logout') }}
                             </a>

                             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                 @csrf
                             </form>
                         </div>
                     </li>
				
				
				@endguest
			</ul>
		</div>
			<div class="mobile hamburger_top" id="hamburger-1">
				<i class="fal fa-search"></i>
				<span class="line"></span>
				<span class="line"></span>
				<span class="line"></span>
			</div>
			
	</div>

	<form name="KB_AUTHMARK_FORM" method="get">
		<input type="hidden" name="page" value="C021590"/>
		<input type="hidden" name="cc" value="b034066:b035526"/>
		<input type="hidden" name="mHValue" value='46df1f044e7c844c918e4808064a8d88201712271501362'/>
	</form>

	<script>
	function onPopKBAuthMark()
	{
		window.open('','KB_AUTHMARK','height=604, width=648, status=yes, toolbar=no, menubar=no,location=no');
		document.KB_AUTHMARK_FORM.action='https://okbfex.kbstar.com/quics';
		document.KB_AUTHMARK_FORM.target='KB_AUTHMARK';
		document.KB_AUTHMARK_FORM.submit();
	}
	</script>
	