@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('trade_history.tr_ad') }}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('trade_history.tr_li') }}
	</div>
	<div class="card-body">
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
				<select name="cointype">
					<option value="all">{{ __('trade_history.all') }}</option>
					<option value="uid">UID</option>
					<option value="fullname">{{ __('coin.user')}}</option>
					<option value="email">Email</option>
					<option value="mobile">핸드폰 번호</option>
				</select>
				<input type="text" id="srch" name="srch" value = "{{ $srch }}" />
				<button type="submit">{{ __('trade_history.search') }}</button>
			</form>
		</div>
		<div class="mb-2">
			<input id="startTime" type="text" class="col-sm-1 form-control form-control-sm" style="display: inline"/>
			<span> ~ </span> 
			<input id="endTime" type="text" class="col-sm-1 form-control form-control-sm" style="display: inline"/>
			<button id="excel-download" type="button" class="myButton navy">Excel</button>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;" rowspan="2">{{ __('trade_history.number') }}</th>
						<th style="width:10%;" rowspan="2">체결시간</th>
						<th style="width:10%;" rowspan="1">구매자 이름</th>
						<th style="width:10%;" rowspan="1">판매자 이름</th>
						<th style="width:10%;" rowspan="1">{{ __('trade_history.coin_type') }}</th>
						<th style="width:10%;" rowspan="2">체결양</th>
                        <th style="width:10%;" rowspan="2">거래된 시세</th>
                        <th style="width:10%;" rowspan="2">거래된 가격</th>
					</tr>
                    <tr>
                        <th rowspan="1">구매자 UID</th>
                        <th rowspan="1">판매자 UID</th>
                        <th rowspan="1">{{ __('trade_history.currency') }}</th>
                    </tr>
				</thead>
				<tbody>
					@forelse($trade_historys as $trade_history)
					<tr>
						<td rowspan="2">{{$trade_history->id}}</td>
						<td rowspan="2">{{date("Y.m.d H:i:s", $trade_history->created)}}</td>
                        <td rowspan="1">{{$trade_history->buyer_fullname}}</td>
                        <td rowspan="1">{{$trade_history->seller_fullname}}</td>
                        <td rowspan="1">{{strtoupper($trade_history->cointype)}}</td>
                        <td rowspan="2">{{$trade_history->contract_coin_amt}}</td>
                        <td rowspan="2">{{$trade_history->buy_coin_price}}</td>
                        <td rowspan="2">{{$trade_history->trade_total_buy}}</td>

					</tr>
                    <tr>
                        <td rowspan="1">{{$trade_history->buyer_uid}}</td>
                        <td rowspan="1">{{$trade_history->seller_uid}}</td>
                        <td rowspan="1">{{ strtolower($trade_history->currency) == 'usd' ? 'USDC' : strtoupper($trade_history->currency) }}{{ __('trade_history.market') }}</td>
                    </tr>
					@empty
					<tr>
						<th colspan="12">{{ __('trade_history.trade_history_sentence1') }}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($trade_historys)
		{!! $trade_historys->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('trade_history.trade_hitory.sentenc2') }}
	</div>
</div>
@endsection

@section('script')
<script>
	$(function() {
		$.datepicker.setDefaults({
			dateFormat: 'yy-mm-dd',
			prevText: '{{ __('event.bfmonth')}}',
			nextText: '{{ __('event.nxfmonth')}}',
			monthNames: ['{{ __('event.1')}}', '{{ __('event.2')}}', '{{ __('event.3')}}', '{{ __('event.4')}}', '{{ __('event.5')}}', '{{ __('event.6')}}', '{{ __('event.7')}}',
			'{{ __('event.8')}}', '{{ __('event.9')}}', '{{ __('event.10')}}', '{{ __('event.11')}}', '{{ __('event.12')}}'],
			monthNamesShort: ['{{ __('event.1')}}', '{{ __('event.2')}}', '{{ __('event.3')}}', '{{ __('event.4')}}', '{{ __('event.5')}}', '{{ __('event.6')}}', '{{ __('event.7')}}',
			'{{ __('event.8')}}', '{{ __('event.9')}}', '{{ __('event.10')}}', '{{ __('event.11')}}', '{{ __('event.12')}}'],
			dayNames:['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
			dayNamesShort: ['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
			dayNamesMin: ['{{ __('event.01')}}', '{{ __('event.02')}}', '{{ __('event.03')}}', '{{ __('event.04')}}', '{{ __('event.05')}}', '{{ __('event.06')}}', '{{ __('event.07')}}'],
			showMonthAfterYear: true,
			yearSuffix: '{{ __('event.y')}}',
			beforeShow: function() {
				setTimeout(function(){
					$('.ui-datepicker').css('z-index', 999);
				}, 0);
			},
			onClose: function(value) {
				var datepicker = this;
				if(value && !moment(value, 'YYYY-MM-DD',true).isValid()){
					alert('{{ __('event.wrong')}}');
					$(datepicker).val('');
				}
			}
		});

		var from = $("#startTime")
			.datepicker()
			.on("change", function() {
				to.datepicker("option", "minDate", $(this).val());
			});
			
		var	to = $("#endTime")
			.datepicker()
			.on("change", function() {
				from.datepicker("option", "maxDate", $(this).val());
			});
		
		$('#excel-download').click(function(e){
			var srch = '{{ $srch }}';
			window.location = '/admin/trade/trade_history_excel?from=' + from.val() + '&to=' + to.val() + '&srch=' + srch;
		});
	});
</script>
@endsection