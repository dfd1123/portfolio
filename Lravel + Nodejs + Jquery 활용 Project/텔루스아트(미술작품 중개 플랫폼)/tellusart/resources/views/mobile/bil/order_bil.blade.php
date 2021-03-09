@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">

<div class="orderbox">
		
		<!-- 상세 -->
			<div class="content">
				
				<div class="orderbox">
					<div class="ornum">
						<dl>
							<dt>주문번호    :</dt>
							<dd class="en">{{sprintf('%09d', $order->product->id)}}</dd>
						</dl>
						<dl>
							<dt>주문날짜    :</dt>
							<dd class="en"><strong>{{date("Y.m.d", strtotime($order->created_at))}}</strong></dd>
						</dl>
					</div>
					<div class="oinfo_de">
						<p><a href="#"><img src="{{asset('/storage/image/product/'.$order->product->image1)}}" alt=""/></a></p>
						<ul>
							<li>{{$order->product->title}}</li>
							<li><span class="option">- 작가명 : {{$order->product->artist_name}} <br/>- 사이즈 : {{$order->product->art_width_size}} X {{$order->product->art_height_size}}cm</span></li>
						</ul>
					</div>
					<h3 class="tit">주문 처리상태</h3>
					<div class="obox">
						<div class="credit">
							<dl>
								<dt>배송상태</dt>
								<dd>
									@if($order->order_state < 2)
										배송대기
									@elseif($order->order_state == 2)
										배송중
									@elseif($order->order_state >= 3)
										배송완료
									@endif                                        

								</dd>
							</dl>
							<dl>
								<dt>주문상태</dt>
								<dd>
									@if($order->order_state == 0)
										입금대기
									@elseif($order->order_state == 1)
										배송대기
									@elseif($order->order_state == 2)
										배송중
									@elseif($order->order_state == 3)
										배송완료
									@elseif($order->order_state == 4)
										@if($order->order_cancel == 1)
											주문취소
										@elseif($order->order_cancel == 2)
											환불
										@else
											취소/환불 신청
										@endif
									@elseif($order->order_state == 5)
										주문확정
									@endif
								</dd>
							</dl>
						</div>
					</div>
					<h3 class="tit">결제내역</h3>
					<div class="obox">
						<div class="credit">
							<dl>
								<dt>총 상품가격   :    </dt>
								<dd>
									<p class="number price bil_cell en">
										<em class="coinic">c</em> {{number_format($order->product->coin_price, 8)}}
										<em class="kric">￦</em> {{number_format($order->product->cash_price)}}
									</p>
								</dd>
							</dl>
							<dl>
								<dt>배송비   :    </dt>
								<dd>
									<p class="number price bil_cell en">
										@if($order->how_pay == 0)
											<em class="kric">￦</em> {{number_format($order->product->delivery_price)}}
										@elseif($order->how_pay == 10)
											<em class="coinic">c</em> 0
										@endif
									</p>
								</dd>
							</dl>
							<dl class="total">
								<dt>총 결제금액   :    </dt>
								<dd>
									<p class="number price bil_cell en">
										@if($order->how_pay == 0)
											<em class="kric">￦</em> {{number_format($order->total_price)}}
										@elseif($order->how_pay == 10)
											<em class="coinic">c</em> {{number_format($order->total_price)}}
										@endif
									</p>
								</dd>
							</dl>
							<dl>
								<dt>결제방법 :       </dt>
								<dd class="gray">
									@if($order->how_pay == 0)
										무통장입금
									@elseif($order->how_pay == 10)
										코인결제
									@endif
								</dd>
							</dl>
							@if($order->how_pay == 0)
								<dl>
									<dt>은행명 :       </dt>
									<dd>{{$company->account_bank}}</dd>
								</dl>
								<dl>
									<dt>계좌번호 :       </dt>
									<dd>{{$company->account_number}}</dd>
								</dl>
								<dl>
									<dt>예금주 :       </dt>
									<dd>{{$company->account_user}}</dd>
								</dl>
							@endif
						</div>
					</div>
					<h3 class="tit">배송지 정보</h3>
					<div class="obox">
						<div class="credit">
							<dl>
								<dt>수령인   :        </dt>
								<dd>{{$order->delivery->getter_name}}</dd>
							</dl>
							<dl>
								<dt>연락처   :    </dt>
								<dd class="en">{{$order->delivery->getter_mobile}}</dd>
							</dl>
							<dl>
								<dt>이메일</dt>
								<dd>{{$order->delivery->getter_email}}</dd>
							</dl>
							<dl>
								<dt>배송회사   :        </dt>
								<dd>{{$delivery_company}}</dd>
							</dl>
							<dl>
								<dt>운송장번호</dt>
								<dd>
									@if($order->delivery->send_post_num != NULL)
										{{$order->delivery->send_post_num}}
									@else
										-
									@endif
								</dd>
							</dl>
							<dl>
								<dt>배송지    </dt>
								<dd class="w100">{{$order->delivery->user_addr1}}{{$order->delivery->user_extra_addr}} {{$order->delivery->user_addr2}}</dd>
							</dl>
							<dl>
								<dt>배송메세지</dt>
								<dd class="w100">{{$order->delivery->delivery_ask}}</dd>
							</dl>
						</div>
						<div class="obtn kr ">
							<button type="button" class="w100 darkgray" onclick="location.href='{{route('mypage.mobile_mypage')}}?index=4'" style="width: 100%;">목록으로 돌아가기</button>
						</div>
					</div>
				</div>
				
			</div>
			<!-- //상세 -->
	</div>
</div>
    
@endsection