@extends('layouts.app')

@section('content')
<div class="sub_spot mypage">
	<h2>마이페이지</h2>
</div>
<div id="container">
	@include('mypage.include.my_common')
	<div class="orderbox">
		<div class="cartbox">
			<h3 class="mytit">판매내역</h3>
		</div> 
		<div class="countbox">
			<ul>
				<li><span><i class="fal fa-scroll"></i></span><em>입금대기<strong>{{$sale_zcnt}}</strong></em></li>
				<li><span><i class="fal fa-luggage-cart"></i></span><em>입금확인<strong>{{$sale_ocnt}}</strong></em></li>
				<li><span><i class="fal fa-boxes"></i></span><em>배송중<strong>{{$sale_tcnt}}</strong></em></li>
				<li><span><i class="far fa-box"></i></span><em>배송완료<strong>{{$sale_thcnt}}</strong></em></li>
				<li><span><i class="fas fa-comment-alt-times"></i></span><em>환불처리<strong>{{$sale_fcnt}}</strong></em></li>
				<li><span><i class="far fa-box"></i></span><em>판매확정<strong>{{$sale_ficnt}}</strong></em></li>
			</ul>
		</div>
		<div class="period">
			<form id="order_target" method="get" action="{{route('mypage.my_sale_list')}}">
				<ul>
					<li><label for="dateTerm1"><input type="radio" name="dateTerm" id="dateTerm1" style="display:none;" value="1">1일</label></li>
						<li><label for="dateTerm7"><input type="radio" name="dateTerm" id="dateTerm7" style="display:none;" value="7">1주일</label></li>
						<li><label for="dateTerm30"><input type="radio" name="dateTerm" id="dateTerm30" style="display:none;" value="30">1개월</label></li>
						<li><label for="dateTerm365"><input type="radio" name="dateTerm" id="dateTerm365" style="display:none;" value="365">1년</label></li>
						<li class="cale"><span><input id = "from_date" name="from_date" class="datepicker" type = "text" size="15"></span></li>
						<li><span><input id = "to_date" name="to_date" class="datepicker" type = "text" size="15"></span></li>
						<li>
							<div class="select">
								<select name="status">
									<option value="-1" {{($status==-1)? 'selected="selected"':""}}>전체</option>
									<option value="0" {{($status==0)? 'selected="selected"':""}}>입금대기</option>
									<option value="1" {{($status==1)? 'selected="selected"':""}}>입금확인</option>
									<option value="2" {{($status==2)? 'selected="selected"':""}}>배송중</option>
									<option value="3" {{($status==3)? 'selected="selected"':""}}>배송완료</option>
									<option value="4" {{($status==4)? 'selected="selected"':""}}>환불처리</option>
									<option value="10" {{($status==10)? 'selected="selected"':""}}>판매확정</option>
								</select>
								<div class="select__arrow"></div>
							</div>
						</li>
				</ul>
			</form>
		</div>
		<!-- 주문내역 리스트 -->
		<form name="" method="post" action="">
			<div class="buy_goods_list">
				<div class="table_list">
					<table class="list_cart">
						<caption>판매내역 리스트</caption>
						<colgroup>
							<col style="width:15%" class="mnone">
							<col style="">
							<col style="width:12%" class="mnone">
							<col style="width:12%" class="mnone">
							<col style="width:10%" class="mnone">
							<col style="width:10%" class="mnone">
						</colgroup>
						<thead>
							<tr>
								<th class="t_layout">판매 번호/날짜</th>
								<th class="t_layout">판매 상품 내역</th>
								<th class="t_layout">배송정보</th>
								<th class="t_layout">구매자정보</th>
								<th class="t_layout">판매상태</th>
								<th class="t_layout">비고</th>
							</tr>
						</thead>

						<tbody>
						@forelse($sales as $sale)
							<tr class="pop_parent" >
								<td class="t_layout en web left">
									주문번호 : <strong>{{sprintf('%09d',$sale->id)}}</strong><br>
									{{date("Y.m.d H:i", strtotime($sale->created_at))}}
								</td>
								<td>
									<div class="cart_txt web">
										<p class="btn_thumb"><a href="" target="_blank"><img src="{{asset('/storage/image/product/'.$sale->product->image1)}}" alt="상품 이미지"></a></p>
										<p class="name">
											
											<a href="" class="" target="_blank">{{$sale->product->title}}</a>
											<span class="option">작가명 : {{$sale->product->artist_name}} <i></i>사이즈 : {{$sale->product->art_width_size}} X {{$sale->product->art_height_size}}cm</span>
											<span class="price">
												@if($sale->how_pay != 10)
													<em class="kric">￦</em> {{number_format($sale->total_price)}}
												@else
													<em class="coinic">c</em> {{round($sale->total_price)}}
												@endif
											</span>
										</p>
									</div>
									<!-- 모바일 -->
									<div class="mobile_cart mobile">
										<div class="mo_cart_txt">
											
											<p class="btn_thumb"><a href="" target="_blank"><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt="상품 이미지"></a></p>
											<p class="name"><em class="category">{{$sale->product->category->ca_name}}</em><a href="" class="" target="_blank">{{$sale->product->title}}</a>
											<span class="option">작가명 : {{$sale->product->artist_name}} <i></i>사이즈 : {{$sale->product->art_width_size}} X {{$sale->product->art_height_size}}cm</span>
											<span class="price">
												@if($sale->how_pay != 10)
													<em class="kric">￦</em> {{number_format($sale->total_price)}}
												@else
													<em class="coinic">c</em> {{round($sale->total_price)}}
												@endif
											</span>
										</p>
										</div>
										
										<div class="mo_cart_info">
											<div class="artist">
												{{$sale->user->name}}
												<em>{{$sale->user->mobile_number}}</em>
											</div>
										</div>
										<div class="cart_m_bt">
											<span >
												@if($sale->order_state == 0)
													@if($sale->order_cancel == 0)
														입금대기중
													@elseif($sale->order_cancel == 1)
														주문취소중
														<button type="button" class="m_btn" title="사유보기">사유보기</button>
													@endif
												@elseif($sale->order_state == 1)
													@if($sale->order_cancel == 0)
														<button type="button" class="m_btn" title="작품발송">작품발송</button>
													@elseif($sale->order_cancel == 1)
														주문취소중
														<button type="button" class="m_btn" title="사유보기">사유보기</button>
													@endif
												@elseif($sale->order_state == 2)
													@if($sale->order_cancel == 0)
														<button type="button" class="m_btn" title="배송내역보기">배송내역보기</button>
													@elseif($sale->order_cancel == 1)
														주문취소중
														<button type="button" class="m_btn" title="사유보기">사유보기</button>
													@endif
												@elseif($sale->order_state == 3)
													@if($sale->order_cancel == 0)
														판매확정대기
													@elseif($sale->order_cancel == 1)
														주문취소중
														<button type="button" class="m_btn" title="사유보기">사유보기</button>
													@endif
												@elseif($sale->order_state == 4)
													<button type="button" class="m_btn" title="사유보기">사유보기</button>
												@elseif($sale->order_state == 10)
													판매확정
												@endif
											</span>
											<span>
												@if($sale->order_state == 0)
													<button type="button" class="betbtor kr">입금대기</button>
												@elseif($sale->order_state == 1)
													<button type="button" class="betbtno kr">입금확인</button>
												@elseif($sale->order_state == 2)
													<button type="button" class="betbting kr">배송중</button>
												@elseif($sale->order_state == 3)
													<button type="button" class="betbting kr">배송완료</button>
												@elseif($sale->order_state == 4)
													<button type="button" class="betbt kr">환불처리</button>
												@elseif($sale->order_state == 10)
													<button type="button" class="betbt kr">판매확정</button>
												@endif
											</span>
										</div>
									</div>
									<!-- //모바일 -->
								</td>
								<td>
									{{($sale->delivery->delivery_company != NULL)? $sale->delivery->delivery_company:"-"}}<br>
									{{($sale->delivery->send_post_name != NULL)? $sale->delivery->send_post_name:""}}
								</td>
								<td class="t_layout web en">
									<div class="artist">
										{{$sale->user->name}}
										<em>{{$sale->user->mobile_number}}</em>
									</div>
								</td>
								<td class="t_layout web">
									<div class="cart_m_bt">
										<span class="w100">
											@if($sale->order_state == 0)
												<button type="button" class="betbtor kr">입금대기</button>
											@elseif($sale->order_state == 1)
												<button type="button" class="betbtno kr">입금확인</button>
											@elseif($sale->order_state == 2)
												<button type="button" class="betbting kr">배송중</button>
											@elseif($sale->order_state == 3)
												<button type="button" class="betbting kr">배송완료</button>
											@elseif($sale->order_state == 4)
												<button type="button" class="betbt kr">환불처리</button>
											@elseif($sale->order_state == 10)
												<button type="button" class="betbt kr">판매확정</button>
											@endif
										</span>
									</div>
									
								</td>
								<td class="t_layout web">
									<div class="cart_m_bt">
										<span class="w100">
											@if($sale->order_state == 0)
												@if($sale->order_cancel == 0)
													입금대기중
												@elseif($sale->order_cancel == 1)
													주문취소중
													<button type="button" class="view_cancel_reason m_btn" data-id="{{$sale->id}}" title="사유보기">사유보기</button>
												@endif
											@elseif($sale->order_state == 1)
												@if($sale->order_cancel == 0)
													<button type="button" class="m_btn" title="작품발송">작품발송</button>
												@elseif($sale->order_cancel == 1)
													주문취소중
													<button type="button" class="view_cancel_reason m_btn" data-id="{{$sale->id}}" title="사유보기">사유보기</button>
												@endif
											@elseif($sale->order_state == 2)
												@if($sale->order_cancel == 0)
													<button type="button" class="m_btn" title="배송내역보기">배송내역보기</button>
												@elseif($sale->order_cancel == 1)
													주문취소중
													<button type="button" class="view_cancel_reason m_btn" data-id="{{$sale->id}}" title="사유보기">사유보기</button>
												@endif
											@elseif($sale->order_state == 3)
												@if($sale->order_cancel == 0)
													판매확정대기
												@elseif($sale->order_cancel == 1)
													주문취소중
													<button type="button" class="view_cancel_reason m_btn" data-id="{{$sale->id}}" title="사유보기">사유보기</button>
												@endif
											@elseif($sale->order_state == 4)
												<button type="button" class="view_cancel_reason m_btn" data-id="{{$sale->id}}" title="사유보기">사유보기</button>
											@elseif($sale->order_state == 10)
												판매확정
											@endif
										</span>
									</div>
								</td>
							</tr>
							
						@empty
							<tr>
								<td colspan="5">검색되는 판매 내역이 없습니다.</td>
							</tr>
						@endforelse
							

						</tbody>
					</table>
				</div>
				<div class="paging_board">
					@if($sales)
						{!! $sales->render() !!}
					@endif
				</div>
			</div>
		</form>
		<!-- //주문내역 리스트 -->
	</div>
</div>

<div id="view_cancel_reason_modal"  class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap like_cux">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>취소·환불 사유 보기</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					<h5><i class="fal fa-chevron-circle-right"></i>취소·환불 사유</h5>
					<input type="text" name="temp_cancel_reason" readonly="readonly" />
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('main_script')
<script src="https://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script>


$(function() {

   $.datepicker.setDefaults({ 
    changeMonth: true,
    changeYear:true,
    dateFormat: "yy-mm-dd",
    showOn:"button",
    buttonImage: "{{asset('/storage/image/homepage/ic_calendar.png')}}",
    buttonImageOnly:true,
    minDate: "-1Y", //최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전),
    maxDate: "today" //최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후), 

   });

	$("#from_date").datepicker();                    
	$("#to_date").datepicker();
	
	@if($from_date == NULL)
		$('#from_date').datepicker('setDate','-{{$dateTerm}}D');
		$('#to_date').datepicker('setDate','today');
	@else
		$('#from_date').datepicker('setDate',new Date('{{$from_date}}'));
		$('#to_date').datepicker('setDate',new Date('{{$to_date}}'));
		
	@endif
  });

$('#from_date, #to_date').change(function(){
	
	$('#order_target').submit();
	
});

$('input[name="dateTerm"],select[name="status"]').change(function(){
	$('#order_target'
	).submit();
});

$('button.view_cancel_reason').click(function(e){

	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var order_id = e.target.dataset.id;

	$.ajax({
		url : '/mypage/order/view_cancel_reason',
		type : 'POST',
		/* send the csrf-token and the input to the controller */
		data : { _token : CSRF_TOKEN, order_id : order_id},
		dataType : 'JSON',
		/* remind that 'data' is the response of the AjaxController */
		success : function(data) {
			$('input[name="temp_cancel_reason"]').val(data.reason);
			$('#view_cancel_reason_modal').removeClass('hidden');
			setTimeout(function() {
				$('#view_cancel_reason_modal').addClass('active');
			}, 300);
		}
	});
});

$('#view_cancel_reason_modal .jw_overlay, #view_cancel_reason_modal .jw_modal_hd>div').click(function(){
	$('#view_cancel_reason_modal').removeClass('active');
	
	setTimeout(function() {
		$('#view_cancel_reason_modal').addClass('hidden');
	}, 300);
});



</script>
@endsection
