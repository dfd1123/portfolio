@extends('pc.layouts.app')

@section('content')
<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2 class="pt30">구매하기</h2>
</div>
<div id="container">
	<div class="orderbox">
		<div class="cartbox">
			<h3 class="mytit">구매정보작성</h3>
		</div> 
		<!-- 장바구니 리스트 -->
		<form enctype="multipart/form-data" method="POST" action="{{ route('orders.store') }}" id="order_target">
			
			@csrf
			
			<input type="hidden" name="pro_id" value="{{$order_id}}" />
			<div class="orderall">
				<div class="leftform">
					<h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 주문자 정보</h3>
					<span class="subtxt">* 표시는 필수입력 항목</span>
					<div class="boxline">
						<div class="otxt">
							<h4>주문자 정보</h4>
							<dl>
								<dt class="essential">이름</dt>
								<dd><input type="text" id="uname" name="uname" tabindex="2" title="이름" class="required kr"  placeholder="홍길동" value="{{ $user->name }}" required="required" readonly="readonly" /></dd>
							</dl>
							<dl>
								<dt class="essential">연락처</dt>
								<dd><input type="text" id="mobile_number" name="mobile_number" tabindex="2" title="연락처" class="required kr"  placeholder="010-0000-0000" value="{{ $user->mobile_number }}" required="required" readonly="readonly" /></dd>
							</dl>
							<dl>
								<dt class="essential">이메일</dt>
								<dd><input type="text" id="uemail" name="uemail" tabindex="2" title="이메일" class="required kr"  placeholder="이메일" value="{{ $user->email }}" required="required" readonly="readonly" /></dd>
							</dl>
						</div>
						<div class="otxt pt20">
							<h4>배송지 정보</h4>
							<span class="same">
								<div class="check" >
									<input type="checkbox" id="same_order" name="same_order" />
									<label for="same_order"><span></span>주문자와 동일</label>
								</div> 
								
							</span>
							<dl>
								<dt class="essential">이름</dt>
								<dd><input type="text" id="user_name" name="user_name" tabindex="2" title="이름" class="required kr"  placeholder="홍길동" value="{{ old('user_name') }}" required="required" /></dd>
							</dl>
							<dl>
								<dt class="essential">연락처</dt>
								<dd><input type="text" id="user_mobile_number" name="user_mobile_number" tabindex="2" title="연락처" class="required kr"  placeholder="01012345678(-없이 입력하세요.)"  value="{{ old('user_mobile_number') }}" required="required" /></dd>
							</dl>
							<dl>
								<dt class="essential">이메일</dt>
								<dd><input type="text" id="user_email" name="user_email" tabindex="2" title="이메일" class="required kr"  placeholder="이메일" value="{{ old('user_email') }}" required="required"/></dd>
							</dl>
							<dl>
								<dt class="essential">주소</dt>
								<dd>
									<input type="text" id="post_num" name="post_num" tabindex="2" title="우편번호" class="required kr" placeholder="우편번호" value="{{ old('post_num') }}" style="width:80%;"  onclick="Postcode()" readonly="readonly" required="required" /><a href="javascript:void(0)" onclick="Postcode();return false;" class="add_search">주소검색</a>
									<input type="text" id="user_addr1" name="user_addr1" tabindex="2" title="기본주소" value="{{ old('user_addr1') }}" class="required kr" placeholder="기본주소" required="required" />
									<input type="text" id="user_addr2" name="user_addr2" tabindex="2" title="상세주소" value="{{ old('user_addr2') }}" class="required kr" placeholder="상세주소" required="required" />
									<input type="text" name="user_extra_addr" id="user_extra_addr" value="{{ old('user_extra_addr') }}" style="display:none;" />
									<span id="guide" style="color:#999;display:none"></span>
								</dd>
							</dl>
							<dl>
								<dt>배송메세지</dt>
								<dd>
									<input type="text" id="delivery_ask" name="delivery_ask" tabindex="2" title="메세지" class="required kr" placeholder="메세지" style="width:100%;"/>
									<p>- 도서산간 지역의 경우 추후 수령 시 추가 배송비가 발생할 수 있으며, 해외배송은 불가합니다.<br/>
									- 배송지 불분명 및 수신불가 연락처 기입으로 반송되는 왕복 택배 비용은 구매자 부담입니다<br/>
									-  대리 주문으로 해외 주소로 발송 전, 주문품 꼭 확인해주세요. </p>
								</dd>
							</dl>
						</div>
						<div class="otxt pt20" style="display:none;">
							<h4>쿠폰 사용</h4>
							<dl>
								<dt>쿠폰선택</dt>
								<dd>
									<div class="select">
										<select name="bank" id="deposit_bank">
											<option>가입 10%쿠폰할인</option>
											<option>구매 20%쿠폰할인</option>
											<option>가입 10%쿠폰할인</option>
										</select>
										<div class="select__arrow"></div>
									</div>
									<a href="" class="add_search">보유쿠폰 확인하기</a>
									<p>
										- 할인쿠폰 제외 상품이 포함되어 있는 경우, 해당 제품을 제외하고 할인이 적용됩니다.<br/>
										- 브랜드쿠폰과 보너스쿠폰은 중복사용이 불가능합니다.<br/>
										- 일부 상품(할인쿠폰제외상품)에는 보너스쿠폰이 적용되지 않습니다.<br/>
										- 쿠폰에 따라 최대 쿠폰 사용 금액이 제한될 수 있습니다.
									</p>
								</dd>
							</dl>
						</div>
						<div class="otxt linenone pt20">
							<h4>결제방식</h4>
							<ul class="payment">
								<li><label for="deposit_pay"><input type="radio" name="payment_kind" id="deposit_pay" value="0" style="display:none;" />무통장입금</label></li>
								<li><label for="allcoin_pay"><input type="radio" name="payment_kind" id="allcoin_pay" value="10" style="display:none;" />코인결제</label></li>
							</ul>
							<div class="mutong" style="display: none;">
								<dl>
									<dt>입금은행</dt>
									<dd>
										<div class="select">
											<select name="bank" id="deposit_bank">
												<option selected="selected">은행선택</option>
												<option value="농협은행">농협은행</option>
												<option value="대구은행">대구은행</option>
												<option value="부산은행">부산은행</option>
												<option value="국민은행">국민은행</option>
												<option value="기업은행">기업은행</option>
												<option value="우리은행">우리은행</option>
											</select>
											<div class="select__arrow"></div>
										</div>
										7일 이내에 입금이 되지 않으면 자동 주문취소 됩니다.
										<p>
											- 할인쿠폰 제외 상품이 포함되어 있는 경우, 해당 제품을 제외하고 할인이 적용됩니다.<br/>
											- 브랜드쿠폰과 보너스쿠폰은 중복사용이 불가능합니다.<br/>
											- 일부 상품(할인쿠폰제외상품)에는 보너스쿠폰이 적용되지 않습니다.<br/>
											- 쿠폰에 따라 최대 쿠폰 사용 금액이 제한될 수 있습니다.
										</p>
									</dd>
								</dl>
								<dl>
									<dt class="w100">현금영수증 <em>무통장 입금 완료 후 2일 이내 발급</em></dt>
									<dd class="w100">
										 <div  class="receipt_list bilkind_wrap">
								
												<input type="radio" class="tb choice"  checked="checked" name="bil_kind" id="bil_kind1" value="1">
												<label for="bil_kind1"><span></span>개인소득공제용</label>
										
												<input type="radio" class="tb choice" name="bil_kind" id="bil_kind2" value="2">
												<label for="bil_kind2"><span></span>사업자 증빙용</label>
											
												<input type="radio" class="tb choice" name="bil_kind" id="bil_kind3" value="0">
												<label for="bil_kind3"><span></span>신청안함</label>
											
												<div class="tab1_content">
													<ul>
														<li class="w100">
															<div class="individual_kind_wrap" style="width:30%;display: inline-block;">
																<div class="select" style="width:100%;">
																	<select name="individual_kind">
																		<option value="0">핸드폰번호</option>
																		<option value="1">현금영수증카드번호</option>
																	</select>
																	<div class="select__arrow"></div>
																</div>
															</div>
															<div class="mobile_number_wrap" style="width:50%;display: inline-block;">
																<input type="text" name="bilmobile_number" class="required kr" placeholder="핸드폰번호7자리" />
															</div>
															<div class="bilcard_number_wrap" style="width:50%;display: inline-block;display:none;">
																<input type="text" name="bilcard_number" class="required kr" placeholder="현금영수증카드번호 12자리" />
															</div>
															<div class="business_bil" style="width:50%;display: inline-block;display:none;">
																<input type="text" name="business_number" class="required kr" placeholder="사업자번호 입력" />
															</div>
														</li>
													</ul>
												</div>
										</div>
										
									</dd>
								</dl>
								<dl>
									<dt>환불계좌은행</dt>
									<dd>
										<div class="select" style="width:100%;">
											<select name="user_bank_name">
												<option selected="selected">은행선택</option>
												<option value="농협은행">농협은행</option>
												<option value="대구은행">대구은행</option>
												<option value="부산은행">부산은행</option>
												<option value="국민은행">국민은행</option>
												<option value="기업은행">기업은행</option>
												<option value="우리은행">우리은행</option>
											</select>
											<div class="select__arrow"></div>
										</div>
									</dd>
								</dl>
								<dl>
									<dt>환불계좌번호</dt>
									<dd>
										<input type="text" name="user_bank_number" class="required kr" />
									</dd>
								</dl>
								<dl>
									<dt class="w100">무통장입금 이용안내</dt>
									<dd class="w100">
										<p>
											ㆍ입금 시 주문자 이름과 상관없이 금액만 일치하면 정상 입금처리 됩니다.<br/>
											ㆍ반드시 입금 기한 내에 정확한 결제금액을 입금해 주세요.<br/>
											ㆍ입금 기한이 지나면 주문은 자동취소 되므로 다시 주문해주세요.<br/>
											ㆍ자동화 기기에서는 카드를 통해 이체해 주시기 바랍니다.(일부 기기에서는 현금, 통장 이체 제한됨)<br/>
											ㆍ매진, 판매 종료로 인해 주문취소가 될 수 있으며 취소 시 안내문자가 발송됩니다.(주문취소 시, 입금결제건이 전체 취소됩니다.)
										</p>
									</dd>
								</dl>
							</div>
						</div>
						
					</div>
				</div>
				<div class="rightform">
					<h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 주문내역</h3>
					<div class="boxline graybox">
						<div class="pro_order">
							<ul>
								<li><img src="{{asset('storage/image/'.$product->artist_img)}}" alt="{{$product->title}}"></li>
								<li>{{$product->title}}<span class="option">작가명 : {{$product->artist_name}} <br/>사이즈 : {{$product->art_width_size}} X {{$product->art_height_size}}cm</span></li>
							</ul>
							<dl>
								<dt>상품금액   :    </dt>
								<dd>
									<p class="number price en">
										<em class="coinic">c</em> {{number_format($product->coin_price , 8)}}
										<em class="kric">￦</em> {{number_format($product->cash_price)}}
									</p>
								</dd>
							</dl>
							<dl>
								<dt>배송비용   :    </dt>
								<dd>
									<p class="number price en">
										<em class="coinic">c</em> -
										<em class="kric">￦</em> {{number_format($product->delivery_price)}}
									</p>
								</dd>
							</dl>
							<dl style="display:none;">
								<dt>쿠폰사용내역   :    </dt>
								<dd>
									<p class="number price en">
										<em class="coinic">c</em> (-) 500
										<em class="kric">￦</em> (-) 700
									</p>
								</dd>
							</dl>
							<dl class="total">
								<dt>총 결제금액   :    </dt>
								<dd>
									<p class="number price en">
										<em class="coinic">c</em> {{number_format($product->coin_price, 8)}}<br/>
										<em class="kric">￦</em> {{number_format($product->cash_price + $product->delivery_price)}}
									</p>
								</dd>
							</dl>
						</div>
						<div class="check" >
							<input type="checkbox" id="check1" name="check1" />
							<label for="check1"><span></span><br/>주문하실 상품 및 결제, 주문정보 확인하였으며, 이에 동의합니다. <strong>(필수)</strong></label>
						</div>
						<button type="submit" class="joinbt">주문하기</button>
					</div>
				</div>
				
			</div>
		</form>
		<!-- //장바구니 리스트 -->
	</div>
</div>
<style>
	.otxt dl dd div.receipt_list ul li.w100 div{
		display:inline-block;
	}
	input.choice[type="radio"] + label {
	    display: inline-block;
	    cursor: pointer;
	    color: #222;
	    font-size: 13px;
	    padding: 0 7px;
	}
</style>
<script src="https://ssl.daumcdn.net/dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>

	function selectMatch(select){ 
		var form = select.form; 
		var value = select[select.selectedIndex].value; 
		form.elements.nt_area.value = form.elements[value].value; 
	} 
	function selectMatch(select){ 
		var form = select.form; 
		var value = select[select.selectedIndex].value; 
		form.elements.nt_area.value = form.elements[value].value; 
	} 

	
	function Postcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 도로명 주소의 노출 규칙에 따라 주소를 표시한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var roadAddr = data.roadAddress; // 도로명 주소 변수
                var extraRoadAddr = ''; // 참고 항목 변수

                // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                    extraRoadAddr += data.bname;
                }
                // 건물명이 있고, 공동주택일 경우 추가한다.
                if(data.buildingName !== '' && data.apartment === 'Y'){
                   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                if(extraRoadAddr !== ''){
                    extraRoadAddr = ' (' + extraRoadAddr + ')';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('post_num').value = data.zonecode;
                document.getElementById("user_addr1").value = roadAddr;
                
                // 참고항목 문자열이 있을 경우 해당 필드에 넣는다.
                if(roadAddr !== ''){
                    document.getElementById("user_extra_addr").value = extraRoadAddr;
                } else {
                    document.getElementById("user_extra_addr").value = '';
                }

                var guideTextBox = document.getElementById("guide");
                // 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
                if(data.autoRoadAddress) {
                    var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
                    guideTextBox.innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';
                    guideTextBox.style.display = 'block';

                } else if(data.autoJibunAddress) {
                    var expJibunAddr = data.autoJibunAddress;
                    guideTextBox.innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';
                    guideTextBox.style.display = 'block';
                } else {
                    guideTextBox.innerHTML = '';
                    guideTextBox.style.display = 'none';
                }
            }
        }).open();
    }
</script>



@endsection