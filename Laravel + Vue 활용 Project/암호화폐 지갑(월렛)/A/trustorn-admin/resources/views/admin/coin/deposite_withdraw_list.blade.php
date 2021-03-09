@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('coin.inoutset')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('coin.inoutlist')}}
	</div>
	<div class="card-body">
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
					<select name="cointype">
						<option value="all">{{ __('coin.all')}}</option>
						@foreach($coins as $coin)
							<option value="{{$coin->api}}">{{__('coin_name.'.$coin->api)}}</option>
						@endforeach
					</select>
					<input type="text" name="srch" />
					<button type="submit">{{ __('coin.search')}}</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:10%;">{{ __('coin.date')}}</th>
						<th style="width:10%;">{{ __('coin.id')}}</th>
						<th style="width:10%;">{{ __('coin.name')}}</th>
						<th style="width:10%;">{{ __('coin.kind')}}</th>
                        <th style="width:10%;">{{ __('coin.inout')}}</th>
                        <th style="width:10%;">{{ __('coin.qna')}}</th>
                        <th style="width:10%;">TX ID</th>
					</tr>
                  
				</thead>
				<tbody>
					@forelse($lists as $list)
					<tr>
						<td>{{ $list->updated }}</td>
						<td>{{ $list->account }}</td>
						<td>{{ $list->fullname }}</td>
						@if(strtoupper($list->cointype) == 'USD')
						<td>USDC</td>
						@else
						<td>{{ strtoupper($list->cointype) }}</td>
						@endif
						<td>{{ __('admin_wallet.'.$list->category) }}</td>
						<td>{{ number_format($list->amount,8) }}</td>
						<td>{{ $list->txid }}</td>
					</tr>
					@empty
					<tr>
						<th rowspan="7">{{ __('coin.inoutnohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($lists)
		{!! $lists->render() !!}
		@endif
	</div>
</div>



@endsection