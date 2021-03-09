@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('coin.newcoin')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('coin.coinlist')}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('coin.id2')}}</th>
						<th style="width:10%;">{{ __('coin.symbol')}}</th>
						<th style="width:10%;">{{ __('coin.coinname')}}</th>
						<th style="width:10%;">{{ __('coin.kind')}}</th>
						<th style="width:10%;">{{ __('coin.market')}}</th>
						<th style="width:10%;">{{ __('coin.ifuse')}}</th>
						<th style="width:10%;">{{ __('coin.set')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($coins as $coin)
					<tr>
						<td>{{$coin->id}}</td>
						<td>{{$coin->symbol}}</td>
						<td>{{$coin->name}}</td>
						<td>{{$coin->cointype}}</td>
						<td>{{$coin->market}}</td>
						<td>{{$coin->active == 1 ? __('coin.use') : __('coin.nouse')}}</td>
						<td>
							@if($coin->active == 1)
							<a href="{{route('admin.coin_listing_update', ['id' => $coin->id, 'active' => '0'])}}" class="myButton del" onclick="return confirm('{{ __('coin.q_nouse')}}');">{{ __('coin.nouse2')}}</a>
							@else
							<a href="{{route('admin.coin_listing_update', ['id' => $coin->id, 'active' => '1'])}}" class="myButton navy" onclick="return confirm('{{ __('coin.q_use')}}');">{{ __('coin.use2')}}</a>
							@endif
						</td>
					</tr>
					@empty
					<tr>
						<th colspan="7">{{ __('coin.coinnohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($coins_page)
		{!! $coins_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('coin.update')}}
	</div>
</div>

@endsection

@section('script')

@endsection