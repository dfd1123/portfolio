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
		거래 오류 목록 (해당 목록은 새벽에 다시 원복됩니다.)
		@if(Auth::guard('admin')->user()->level <= 4)
		<button class="myButton navy" id="recovery_error_btn">복구버튼</button>
		@endif
	</div>
	<div class="card-body">

		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:10%;" >주문번호</th>
                        <th style="width:10%;" >이름</th>
                        <th style="width:10%;" >이메일</th>
                        <th style="width:10%;" >전화번호</th>
                        <th style="width:10%;" >구분</th>
                        <th style="width:10%;" >거래대기된 양</th>
					</tr>
				</thead>
				<tbody>
					@forelse($trade_errors as $trade_error)
						@if($trade_error->buy_COIN_amt == 0 && $trade_error->sell_COIN_amt == 0)
						@else
						<tr>
							<td>{{$trade_error->id}}</td>
							<td>{{$trade_error->fullname}}</td>
							<td>{{$trade_error->email}}</td>
							<td>{{$trade_error->mobile_number}}</td>
							<td>{{$trade_error->type}}</td>
							@if($trade_error->type == 'buy')
							<td>{{ bcmul($trade_error->buy_coin_price,$trade_error->buy_COIN_amt,0) }} {{ strtoupper($trade_error->currency) }}</td>
							@else
							<td>{{ bcmul($trade_error->sell_COIN_amt,1,8) }} {{ strtoupper($trade_error->cointype) }}</td>
							@endif
							
						</tr>
						@endif
					@empty
					<tr>
						<th colspan="12">{{ __('trade_history.trade_history_sentence1') }}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('trade_history.trade_hitory.sentenc2') }}
	</div>
</div>
@endsection

@section('script')
<script>
	var error_check_ajax = true;
	$('#recovery_error_btn').click(function(e){
		if(error_check_ajax){
			error_check_ajax = false;
			$.ajax({
				url: '/admin/trade/trade_recovery',
				type: 'POST',
				data: {_token: CSRF_TOKEN},
				dataType: 'JSON',
				async: false,
				success: function (data) { 
					console.log(data);
					alert(data.message);
					location.reload();
				}
			});
		}
	});
</script>
@endsection