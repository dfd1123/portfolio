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
					<option value="account">{{ __('cointr.user')}}</option>
					<option value="address">{{ __('cointr.adrs')}}</option>
					<option value="coin">{{ __('cointr.coin')}}</option>
				</select>
				<input type="text" name="keyword" />
				<button type="submit">{{ __('cointr.src')}}</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('cointr.id')}}</th>
						<th style="width:5%;">{{ __('cointr.date')}}</th>
						<th style="width:5%;">{{ __('cointr.user')}}</th>
						<th style="width:5%;">{{ __('cointr.coin')}}</th>
						<th style="width:5%;">{{ __('cointr.dv')}}</th>
						<th style="width:5%;">{{ __('cointr.qua')}}</th>
						<!--th style="width:5%;">{{ __('cointr.charge')}}</th-->
						<th style="width:10%;">{{ __('cointr.adrs')}}</th>
						<th style="width:5%;">{{ __('cointr.cf')}}</th>
						<th style="width:5%;">TX ID</th>
					</tr>
				</thead>
				<tbody>
					@forelse($transactions as $transaction)
					<tr>
						<td>{{$transaction->id}}</td>
						<td>{{$transaction->created_dt}}</td>
						<td>{{$transaction->account}}</td>
						<td>{{strtoupper($transaction->cointype)}}</td>
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
						<td>{{$transaction->confirmations}}</td>
						<td>{{$transaction->txid}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="9">{{ __('cointr.nolist')}}</th>
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

@endsection