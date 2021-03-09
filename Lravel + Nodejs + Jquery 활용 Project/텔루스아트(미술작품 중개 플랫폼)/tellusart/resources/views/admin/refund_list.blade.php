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
			<form method="get" action="{{route('admin.refund_list')}}">
				@csrf
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 주문상태</span>
					<label class="tsa-list-st"><input type="radio" name="refund_type" value="1" {{($refund_type == 1)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 환불신청</label>
					<label class="tsa-list-st"><input type="radio" name="refund_type" value="2" {{($refund_type == 2)?'checked':''}} class="tsa-hide" /><span class="tsa-indicator"></span> 환불완료</label>
				</div>
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 결제수단</span>
					<label class="tsa-list-st" for="pay_method_1"><input type="radio" name="how_pay" value="0"  {{($how_pay == 0 )?'checked':''}} class="tsa-hide" id="pay_method_1" /><span class="tsa-indicator"></span> 무통장</label>
					<label class="tsa-list-st" for="pay_method_2"><input type="radio" name="how_pay" value="10" {{($how_pay == 10)?'checked':''}} class="tsa-hide" id="pay_method_2"/><span class="tsa-indicator"></span> 코인결제</label>
				</div>
				<div class="line tsa-line">
					<span class="tsa-label-st"><i class="fas fa-angle-right"></i> 주문일자</span>
					<input class="datepicker  tsa-input-st" type="text" value="{{$start_time}}" name="start_time" id="startTime" />
					<span class="tsa-input-st">~</span>
					<input class="datepicker  tsa-input-st" type="text" value="{{$end_time}}" name="end_time" id="endTime" />
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
			<table class="table table-bordered refund_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th colspan="2">주문번호</th>
						<th>주문자</th>
						<th>받는자</th>
						<th rowspan="2">주문합계비용<br/>(배송비포함)</th>
						<th rowspan="2">할인금액</th>
						<th rowspan="2">입급합계</th>
						<th rowspan="2">환불사유</th>
						<th rowspan="2">환불계좌</th>
						<th rowspan="2">작업</th>
					</tr>
					<tr>
						<th>주문상태</th>
						<th>결제수단</th>
						<th>주문자전화</th>
						<th>받는자전화</th>
					</tr>
				</thead>
				<tbody>
					@forelse($orders as $order)
                    <tr>
						<td colspan="2">{{$order->id}}</td>
						<td>{{$order->user->name ?: '-'}}</td>
						<td>{{isset($order->delivery) ? $order->delivery->getter_name : '-'}}</td>
						<td rowspan="2">{{$order->total_price}}{{($how_pay == 0) ? '₩' : 'TLG'}}</td>
						<td rowspan="2">{{$order->sales_price}}{{($how_pay == 0) ? '₩' : 'TLG'}}</td>
						<td rowspan="2">{{$order->payment_price}}{{($how_pay == 0) ? '₩' : 'TLG'}}</td>
						<td rowspan="2">{{$order->pay_cancel_infor}}</td>
						<td rowspan="2">
							@if($order->how_pay == 0)
								{{isset($order->deposit_pay) ? $order->deposit_pay->user_bank_number : '-'}}
							@elseif($order->how_pay == 10)
								-
							@endif
						</td>
						<td rowspan="2">
							@if(($order->order_cancel) == 2 && ($order->order_state == 1 or $order->order_state == 2 or $order->order_state == 3))
								<button class="update-state-button" type="button" data-id="{{$order->id}}">환불승인</button><br>
								<button class="reject-state-button" type="button" data-id="{{$order->id}}">환불취소</button>
							@elseif(($order->order_cancel) == 2 && $order->order_state == 4)
								처리완료
							@endif
						</td>
					</tr>
					<tr>
						@if(($order->order_state)==0)
							<td>주문신청</td>
						@elseif(($order->order_state)==1)
							<td>배송대기</td>
						@elseif(($order->order_state)==2)
							<td>배송중</td>
						@elseif(($order->order_state)==3)
							<td>배송완료</td>
						@elseif(($order->order_state)==4)
							<td>환불완료</td>
						@elseif(($order->order_state)==10)
							<td>주문확정</td>
						@endif
						@if(($order->how_pay)==0)
							<td>무통장</td>
						@elseif(($order->how_pay)==10)
							<td>코인결제</td>
						@endif
						<td>{{$order->user->mobile_number ?: '-'}}</td>
						<td>{{isset($order->delivery) ? $order->delivery->getter_mobile : '-'}}</td>
					</tr>
                    @empty
                    <tr>
                    	<td colspan="10">환불내역이 존재하지 않습니다.</td>
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
	
	$(".update-state-button").click(function(){
		var button = $(this);
		var id = button.data('id');
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		button.attr('disabled', true);
		if(confirm("정말 완료처리 하시겠습니까?")){
			$.ajax({
                url: '/refund/change',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, id: id},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                	button.parent().empty().append('처리완료');
                }
            }); 
		} else {
			button.attr('disabled', false);
		}
	});
	
	$(".reject-state-button").click(function(){
		var button = $(this);
		var id = button.data('id');
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		button.attr('disabled', true);
		if(confirm("고객님께 환불거절 사유의 충분한 설명과 동의를 구하셨습니까?")){
			$.ajax({
                url: '/refund/cancel',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, id: id},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                	button.parent().empty().append('환불취소완료');
                }
            }); 
		} else {
			button.attr('disabled', false);
		}
	});
</script>

@endsection