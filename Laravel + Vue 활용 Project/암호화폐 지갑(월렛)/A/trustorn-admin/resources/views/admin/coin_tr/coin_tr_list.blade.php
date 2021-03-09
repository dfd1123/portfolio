@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('cointr.coininout')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('cointr.coinlist')}}
	</div>
	<div class="card-body">
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
				<select name="keyword_srch">
					<option value="all">전체</option>
					<option value="uid">UID</option>
					<option value="fullname">{{ __('cointr.user')}}</option>
					<option value="email">Email</option>
					<!--<option value="mobile">핸드폰 번호</option>-->
				</select>
				<input type="text" name="keyword" />
				<button type="submit">{{ __('cointr.src')}}</button>
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
						<th style="width:5%;">{{ __('cointr.id')}}</th>
						<th style="width:10%;">{{ __('cointr.date')}}</th>
						<th style="width:5%;">UID</th>
						<th style="width:10%;">{{ __('cointr.user')}}</th>
						<th style="width:10%;">E-mail</th>
						<!--<th style="width:10%;">Mobile Number</th>-->
						<th style="width:5%;">{{ __('cointr.coin')}}</th>
						<th style="width:5%;">{{ __('cointr.dv')}}</th>
						<th style="width:5%;">{{ __('cointr.qua')}}</th>
						<!--th style="width:5%;">{{ __('cointr.charge')}}</th-->
						<th style="width:10%;">{{ __('cointr.adrs')}}</th>
						<!--<th style="width:5%;">{{ __('cointr.cf')}}</th>-->
						<th style="width:5%;">TX ID</th>
					</tr>
				</thead>
				<tbody>
					@forelse($transactions as $transaction)
					<tr>
						<td>{{$transaction->id}}</td>
						<td>{{$transaction->created_dt}}</td>
						<td>{{$transaction->uid}}</td>
						<td>{{$transaction->fullname}}</td>
						<td>{{$transaction->email}}</td>
						<!--<td>{{$transaction->mobile_number}}</td>-->
						<td>{{ strtoupper($transaction->cointype) != 'USD' ? strtoupper($transaction->cointype) : 'USDC' }}</td>
						<td>{{$transaction->category}}</td>
						<td>{{$transaction->amount}}</td>
						<!--td>
							@if($transaction->processed == 'n')
							{{ __('cointr.n')}}
							@elseif($transaction->processed == 'y')
							{{ __('cointr.y')}}
							@elseif($transaction->processed == 'fail')
							{{ __('cointr.fail')}}
							@endif
						</td-->
						<td>{{$transaction->address}}</td>
						<!--<td>{{$transaction->confirmations}}</td>-->
						<td>{{$transaction->txid}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="11">{{ __('cointr.nolist')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($transactions_page)
		{!! $transactions_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('cointr.update')}}
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
			window.location = '/admin/coin_tr_excel?from=' + from.val() + '&to=' + to.val();
		});
	});
</script>
@endsection