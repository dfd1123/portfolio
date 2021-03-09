@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">주문/배송</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		{{$title}}
	</div>
	<div class="card-body">
		<div class="usr_search_box_multi tsa-sch-box">
			<form method="get" action="{{route('admin.order_list')}}">
				@csrf
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 주문상태</span>
					<label class="tsa-list-st"><input type="radio" name="order_state" value="0" {{($order_state == 0)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> &nbsp;주문신청</label>
					<label class="tsa-list-st"><input type="radio" name="order_state" value="1" {{($order_state == 1)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> &nbsp;배송대기</label>
					<label class="tsa-list-st"><input type="radio" name="order_state" value="2" {{($order_state == 2)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> &nbsp;배송중</label>
					<label class="tsa-list-st"><input type="radio" name="order_state" value="3" {{($order_state == 3)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> &nbsp;배송완료</label>
					<label class="tsa-list-st"><input type="radio" name="order_state" value="4" {{($order_state == 4)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> &nbsp;환불/취소</label>
					<label class="tsa-list-st"><input type="radio" name="order_state" value="10" {{($order_state == 10)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> &nbsp;주문확정</label>
				</div>
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 결제수단</span>
					<label class="tsa-list-st" for="pay_method_1"><input type="radio" name="how_pay" value="0"  {{($how_pay == 0 )?'checked':''}} id="pay_method_1" class="tsa-hide"/><span class="tsa-indicator"></span> 무통장</label>
					<label class="tsa-list-st" for="pay_method_2"><input type="radio" name="how_pay" value="10" {{($how_pay == 10)?'checked':''}} id="pay_method_2" class="tsa-hide"/><span class="tsa-indicator"></span> 코인결제</label>
				</div>
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 주문일자</span>
					<input class="datepicker tsa-input-st" type="text" value="{{$start_time}}" name="start_time" id="startTime" />
					<span class="tsa-input-st">~</span>
					<input class="datepicker tsa-input-st" type="text" value="{{$end_time}}" name="end_time" id="endTime" />
					<button class="date-range tsa-date-range tsa-list-st" id="date-today" type="button">오늘</button>
					<button class="date-range tsa-date-range tsa-list-st" id="date-yesterday" type="button">어제</button>
					<button class="date-range tsa-date-range tsa-list-st" id="date-week" type="button">이번주</button>
					<button class="date-range tsa-date-range tsa-list-st" id="date-month" type="button">이번달</button>
					<button class="date-range tsa-date-range tsa-list-st" id="date-last-week" type="button">지난주</button>
					<button class="date-range tsa-date-range tsa-list-st" id="date-last-month" type="button">지난달</button>
					<button type="submit" class="org_btn">검색</button>
				</div>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered order_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th colspan="2">주문번호</th>
						<th>판매자</th>
						<th>주문자</th>
						<th rowspan="3">배송회사</th>
						<th>받는자</th>
						<th rowspan="3">주문합계비용<br/>(배송비포함)</th>
						<th rowspan="3">할인금액</th>
						<th rowspan="3">입급합계</th>
						<th rowspan="3">비고</th>
					</tr>
					<tr>
						<th colspan="2">작품명</th>
						<th rowspan="2">판매자전화</th>
						<th>주문자전화</th>
						<th>받는자전화</th>
					</tr>
					<tr>
						<th colspan="1">주문상태</th>
						<th colspan="1">결제수단</th>
						<th>운송장번호</th>
						<th>배송일시</th>
					</tr>
				</thead>
				<tbody>
					@forelse($orders as $order)
                    <tr>
						<td colspan="2">{{$order->id}}</td>
						<td>{{$order->seller->name ?: '-'}}</td>
						<td>{{$order->user->name ?: '-'}}</td>
						<td rowspan="3" style="border-bottom:1px solid #222;">{{isset($order->delivery) ? $order->delivery->delivery_company : '-'}}</td>
						<td>{{isset($order->delivery) ? $order->delivery->getter_name : '-'}}</td>
						<td rowspan="3" style="border-bottom:1px solid #222;">{{($how_pay == 0) ? number_format($order->total_price,0).' ₩' : number_format($order->total_price,8).' TLC'}}</td>
						<td rowspan="3" style="border-bottom:1px solid #222;">{{($how_pay == 0) ? number_format($order->sales_price,0).' ₩' : number_format($order->sales_price,8).' TLC'}}</td>
						<td rowspan="3" style="border-bottom:1px solid #222;">{{($how_pay == 0) ? number_format($order->payment_price,0).' ₩' : number_format($order->payment_price,8).' TLC'}}</td>
						@if($order->order_state == 0 && $order->how_pay == 0)
						<td rowspan="3" style="border-bottom:1px solid #222;"><a class="order-deposite-button" href="{{route('admin.order_deposite',$order->id)}}">현금입금확인</a></td>
						@elseif($order->order_cancel == 2 && $order->order_state != 4)
						<td rowspan="3" style="border-bottom:1px solid #222;">
							<a class="order-refund-infor-button">환불사유확인</a>
							<span class="reject_infor_soundonly">{{$order->pay_cancel_infor}}</span>
							<a class="order-refund-button" href="{{route('admin.order_refund',$order->id)}}">환불요청확인</a>
						</td>
						@elseif($order->order_state == 2)
						<td rowspan="3" style="border-bottom:1px solid #222;">
							<a class="order-delivery-button" href="{{route('admin.order_delivery',$order->id)}}">배송확인</a>
						</td>
						@else
						<td rowspan="3" style="border-bottom:1px solid #222;">-</td>
						@endif
					</tr>
					<tr>
						<td colspan="2">{{isset($order->product) ? $order->product->title : ''}}</td>
						<td rowspan="2" style="border-bottom:1px solid #222;">{{$order->seller->mobile_number ?: '-'}}</td>
						<td>{{$order->user->mobile_number ?: '-'}}</td>
						<td>{{isset($order->delivery) ? $order->delivery->getter_mobile : '-'}}</td>
					</tr>
					<tr>
						@if(($order->order_state)==0)
							<td colspan="1" style="border-bottom:1px solid #222;">주문신청</td>
						@elseif(($order->order_state)==1)
							<td colspan="1" style="border-bottom:1px solid #222;">배송대기</td>
						@elseif(($order->order_state)==2)
							<td colspan="1" style="border-bottom:1px solid #222;">배송중</td>
						@elseif(($order->order_state)==3)
							<td colspan="1" style="border-bottom:1px solid #222;">배송완료</td>
						@elseif(($order->order_state)==4)
							<td colspan="1" style="border-bottom:1px solid #222;">환불신청</td>
						@elseif(($order->order_state)==5)
							<td colspan="1" style="border-bottom:1px solid #222;">환불완료</td>
						@endif
						@if(($order->how_pay)==0)
							<td colspan="1" style="border-bottom:1px solid #222;">무통장</td>
						@elseif(($order->how_pay)==10)
							<td colspan="1" style="border-bottom:1px solid #222;">코인결제</td>
						@endif
						<td style="border-bottom:1px solid #222;">{{isset($order->delivery) ? $order->delivery->send_post_num : '-'}}</td>
						<td style="border-bottom:1px solid #222;">{{isset($order->delivery) ? $order->delivery->delivery_date : '-'}}</td>
					</tr>
                    @empty
                    <tr>
						<td colspan="9">주문이 존재하지 않습니다.</td>
                    </tr>
                    @endforelse
				</tbody>
			</table>
		</div>
		@if($orders_page)
		{!! $orders_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog tsa-modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">환불사유</h4>
				<button type="button" class="close" data-dismiss="modal">
					×
				</button>
			</div>
			<div class="modal-body">
				<textarea name="reject_infor"  class="tsa-textarea" readonly="readonly"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default org_btn" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>

	</div>
</div>

@endsection

@section('script')
<script>
	$.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년'
    });
    
	$('#startTime').datepicker();
	$('#endTime').datepicker();
	
	var dateFormat = 'YYYY-MM-DD';
	var changeDate = function(fromDate, endDate){
		$('#startTime').val(fromDate.format(dateFormat));
		$('#endTime').val(endDate.format(dateFormat));
	};
	
	$('#date-today').click(function(e){
		var today = moment();
		changeDate(today, today);
	});
	
	$('#date-yesterday').click(function(e){
		var yesterday = moment().subtract(1, 'days');
		changeDate(
			yesterday,
			yesterday
		);
	});
	
	$('#date-week').click(function(e){
		changeDate(
			moment().startOf('week'),
			moment()
		);
	});
	
	$('#date-month').click(function(e){
		changeDate(
			moment().startOf('month'),
			moment()
		);
	});
	
	$('#date-last-week').click(function(e){
		changeDate(
			moment().subtract(1, 'week').startOf('week'),
			moment().subtract(1, 'week').endOf('week')
		);
	});
	
	$('#date-last-month').click(function(e){
		changeDate(
			moment().subtract(1, 'month').startOf('month'),
			moment().subtract(1, 'month').endOf('month')
		);
	});
	
	$('.order-deposite-button').click(function(e){
		if(!confirm("입금확인 처리하시겠습니까?")){
			e.preventDefault();
		}
	});
	
	$('.order-refund-infor-button').click(function(e){
		$("#myModal").modal();
		$("#myModal textarea[name='reject_infor']").val($(this).siblings('.reject_infor_soundonly').text());
	});
	
	$('.order-refund-button').click(function(e){
		if(!confirm("환불확인 처리하시겠습니까?")){
			e.preventDefault();
		}
	});
	
	$('.order-refund-refuse-button').click(function(e){
		if(!confirm("환불거절 처리하시겠습니까?")){
			e.preventDefault();
		}
	});
	
	$('.order-delivery-button').click(function(e){
		if(!confirm("배송완료 처리하시겠습니까?")){
			e.preventDefault();
		}
	});
</script>

@endsection