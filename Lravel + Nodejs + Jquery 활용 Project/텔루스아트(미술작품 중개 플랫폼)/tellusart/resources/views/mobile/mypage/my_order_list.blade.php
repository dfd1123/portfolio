@extends('layouts.app')

@section('content')
<div class="sub_spot mypage">
	<h2>마이페이지</h2>
</div>
<div id="container">
	@include('mypage.include.my_common')
	<div class="orderbox">
		<div class="cartbox">
			<h3 class="mytit">구매내역</h3>
		</div> 
		<div class="countbox">
			<ul>
				<li><span><i class="fal fa-scroll"></i></span><em>주문신청<strong>{{$order_zcnt}}</strong></em></li>
				<li><span><i class="fal fa-luggage-cart"></i></span><em>배송대기<strong>{{$order_ocnt}}</strong></em></li>
				<li><span><i class="fal fa-boxes"></i></span><em>배송중<strong>{{$order_tcnt}}</strong></em></li>
				<li><span><i class="far fa-box"></i></span><em>배송완료<strong>{{$order_thcnt}}</strong></em></li>
				<li><span><i class="fas fa-comment-alt-times"></i></span><em>주문취소<strong>{{$order_fcnt}}</strong></em></li>
				<li><span><i class="far fa-box"></i></span><em>구매확정<strong>{{$order_ficnt}}</strong></em></li>
			</ul>
		</div>
		<div class="period">
			<form id="order_target" method="get" action="{{route('mypage.my_order_list')}}">
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
									<option value="0" {{($status==0)? 'selected="selected"':""}}>주문신청</option>
									<option value="1" {{($status==1)? 'selected="selected"':""}}>배송대기</option>
									<option value="2" {{($status==2)? 'selected="selected"':""}}>배송중</option>
									<option value="3" {{($status==3)? 'selected="selected"':""}}>배송완료</option>
									<option value="4" {{($status==4)? 'selected="selected"':""}}>주문취소</option>
									<option value="10" {{($status==10)? 'selected="selected"':""}}>구매확정</option>
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
						<caption>구매내역 리스트</caption>
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
								<th class="t_layout">주문 번호/날짜</th>
								<th class="t_layout">주문 상품 내역</th>
								<th class="t_layout">배송정보</th>
								<th class="t_layout">판매자정보</th>
								<th class="t_layout">주문상태</th>
								<th class="t_layout">비고</th>
							</tr>
						</thead>

						<tbody>
						@forelse($orders as $order)
							<tr class="pop_parent" >
								<td class="t_layout en web left">
									주문번호 : <strong>{{sprintf('%09d',$order->id)}}</strong><br>
									{{date("Y.m.d H:i", strtotime($order->created_at))}}
								</td>
								<td>
									<div class="cart_txt web">
										<p class="btn_thumb"><a href="" target="_blank"><img src="{{asset('/storage/image/product/'.$order->product->image1)}}" alt="상품 이미지"></a></p>
										<p class="name">
											
											<a href="" class="" target="_blank">{{$order->product->title}}</a>
											<span class="option">작가명 : {{$order->product->artist_name}} <i></i>사이즈 : {{$order->product->art_width_size}} X {{$order->product->art_height_size}}cm</span>
											<span class="price">
												@if($order->how_pay != 10)
													<em class="kric">￦</em> {{number_format($order->total_price)}}
												@else
													<em class="coinic">c</em> {{round($order->total_price)}}
												@endif
											</span>
										</p>
									</div>
									<!-- 모바일 -->
									<div class="mobile_cart mobile">
										<div class="mo_cart_txt">
											
											<p class="btn_thumb"><a href="" target="_blank"><img src="{{asset('/storage/image/homepage/img_pic_sm.png')}}" alt="상품 이미지"></a></p>
											<p class="name"><em class="category">{{$order->product->category->ca_name}}</em><a href="" class="" target="_blank">{{$order->product->title}}</a>
											<span class="option">작가명 : {{$order->product->artist_name}} <i></i>사이즈 : {{$order->product->art_width_size}} X {{$order->product->art_height_size}}cm</span>
											<span class="price">
												@if($order->how_pay != 10)
													<em class="kric">￦</em> {{number_format($order->total_price)}}
												@else
													<em class="coinic">c</em> {{round($order->total_price)}}
												@endif
											</span>
										</p>
										</div>
										
										<div class="mo_cart_info">
											<div class="artist">
												{{$order->product->user->name}}
												<em>{{$order->product->user->mobile_number}}</em>
											</div>
										</div>
										<div class="cart_m_bt">
											<span >
												@if($order->order_state == 0)
													@if($order->order_cancel == 0)
														<button type="button" class="m_btn" title="주문취소" onclick="order_before_cancel({{$order->id}})">주문취소</button>
													@elseif($order->order_cancel == 1)
														주문취소중
													@endif
												@elseif($order->order_state == 1)
													@if($order->order_cancel == 0)
														<button type="button" class="m_btn" title="주문취소" onclick="order_after_cancel({{$order->id}})">환불신청</button>
													@elseif($order->order_cancel == 1)
														주문취소중
													@endif
												@elseif($order->order_state == 2)
													<button type="button" class="m_btn" title="배송내역보기">배송내역보기</button>
												@elseif($order->order_state == 3)
													@if($order->order_cancel == 0)
														<button type="button" class="m_btn_yellow"  title="주문확정">주문확정</button>
														<button type="button" class="m_btn" title="환불신청" onclick="order_after_cancel({{$order->id}})">환불신청</button>
													@elseif($order->order_cancel == 1)
														주문취소중
													@endif
												@elseif($order->order_state == 4)
													주문취소
												@elseif($order->order_state == 10)
													주문완료
												@endif
											</span>
											<span>
												@if($order->order_state == 0)
													<button type="button" class="betbtor kr">주문신청</button>
												@elseif($order->order_state == 1)
													<button type="button" class="betbtno kr">배송대기</button>
												@elseif($order->order_state == 2)
													<button type="button" class="betbting kr">배송중</button>
												@elseif($order->order_state == 3)
													<button type="button" class="betbt kr">배송완료</button>					
												@elseif($order->order_state == 4)
													<button type="button" class="betbt kr">주문취소</button>
												@elseif($order->order_state == 10)
													<button type="button" class="betbt kr">구매확정</button>
												@endif
											</span>
										</div>
									</div>
									<!-- //모바일 -->
								</td>
								<td>
									{{($order->delivery->delivery_company != NULL)? $order->delivery->delivery_company:"-"}}<br>
									{{($order->delivery->send_post_name != NULL)? $order->delivery->send_post_name:""}}
								</td>
								<td class="t_layout web en">
									<div class="artist">
										{{$order->product->user->name}}
										<em>{{$order->product->user->mobile_number}}</em>
									</div>
								</td>
								<td class="t_layout web">
									<div class="cart_m_bt">
										<span class="w100">
											@if($order->order_state == 0)
													<button type="button" class="betbtor kr">주문신청</button>
												@elseif($order->order_state == 1)
													<button type="button" class="betbtno kr">배송대기</button>
												@elseif($order->order_state == 2)
													<button type="button" class="betbting kr">배송중</button>
												@elseif($order->order_state == 3)
													<button type="button" class="betbt kr">배송완료</button>	
												@elseif($order->order_state == 4)
													<button type="button" class="betbt kr">주문취소</button>				
												@elseif($order->order_state == 10)
													<button type="button" class="betbt kr">구매확정</button>
												@endif
										</span>
									</div>
									
								</td>
								<td class="t_layout web">
									<div class="cart_m_bt">
										<span class="w100">
											@if($order->order_state == 0)
												@if($order->order_cancel == 0)
													<button type="button" class="m_btn order_cancel_btn" title="주문취소" data-id="{{$order->id}}">주문취소</button>
												@elseif($order->order_cancel == 1)
													주문취소중
												@endif
											@elseif($order->order_state == 1)
												@if($order->order_cancel == 0)
													<button type="button" class="m_btn order_cancel_btn" title="환불신청" data-id="{{$order->id}}">환불신청</button>
												@elseif($order->order_cancel == 1)
													주문취소중
												@endif
											@elseif($order->order_state == 2)
												<button type="button" class="m_btn" title="배송내역보기">배송내역보기</button>
											@elseif($order->order_state == 3)
												@if($order->order_cancel == 0)
													<button type="button" class="m_btn_yellow"  title="주문확정">주문확정</button>
													<button type="button" class="m_btn order_cancel_btn" title="환불신청" data-id="{{$order->id}}">환불신청</button>
												@elseif($order->order_cancel == 1)
													주문취소중
												@endif
											@elseif($order->order_state == 4)
												주문취소
											@elseif($order->order_state == 10)
												주문완료
											@endif
										</span>
									</div>
								</td>
							</tr>
							
						@empty
							<tr>
								<td colspan="5">검색되는 주문내역이 없습니다.</td>
							</tr>
						@endforelse
							

						</tbody>
					</table>
				</div>
				<div class="paging_board">
					@if($orders)
						{!! $orders->render() !!}
					@endif
				</div>
			</div>
		</form>
		<!-- //주문내역 리스트 -->
	</div>
</div>

<div id="order_cancel_reason_modal"  class="jw_modal_wrap hidden">
	<div class="jw_overlay"></div>
	<div class="jw_modal_content_wrap like_cux">
		<div class="jw_modal_content">
			<div class="jw_modal_hd">
				<h5>취소·환불 사유작성</h5>
				<div><i class="fal fa-chevron-down"></i></div>
			</div>
			<div class="jw_modal_bd">
				<div class="content_box">
					<h5><i class="fal fa-chevron-circle-right"></i>취소·환불 사유</h5>
					<input type="hidden" name="temp_order_id" />
					<input type="text" name="temp_cancel_reason" class="form-control" />
				</div>
			</div>
			<div class="jw_modal_ft">
				<button type="button" class="order_cancel_reason_submit cashgo">작성</button>
			</div>
		</div>
	</div>
</div>

<form method="post" action="{{route('mypage.order_cancel')}}" id="order_cancel_reason_form" style="display:none;">
	@csrf
	<input type="hidden" name="order_id" />
	<input type="text" name="cancel_reason" />
</form>

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

$('button.order_cancel_btn').click(function(e){
	$('input[name="temp_order_id"]').val(e.target.dataset.id);
	
	$('#order_cancel_reason_modal').removeClass('hidden');
	setTimeout(function() {
		$('#order_cancel_reason_modal').addClass('active');
	}, 300);
});

$('.order_cancel_reason_submit').click(function(){
	$('input[name="order_id"]').val($('input[name="temp_order_id"]').val());
	$('input[name="cancel_reason"]').val($('input[name="temp_cancel_reason"]').val());
	
	$('#order_cancel_reason_modal').removeClass('hidden');
	setTimeout(function() {
		$('#order_cancel_reason_modal').addClass('active');
	}, 100);
	
	$('#order_cancel_reason_form').submit();
});



$('#order_cancel_reason_modal .jw_overlay, #order_cancel_reason_modal .jw_modal_hd>div').click(function(){
	$('#order_cancel_reason_modal').removeClass('active');
	
	setTimeout(function() {
		$('#order_cancel_reason_modal').addClass('hidden');
	}, 300);
});

function order_before_cancel(order_id) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm('정말 주문을 취소 하시겠습니까?')){
		$.ajax({
			url : '/order/before_cancel',
			type : 'POST',
			/* send the csrf-token and the input to the controller */
			data : { _token : CSRF_TOKEN, order_id : order_id},
			dataType : 'JSON',
			/* remind that 'data' is the response of the AjaxController */
			success : function(data) {
				
			}
		});
	}
}

</script>
@endsection
