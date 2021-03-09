@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('airdrop.airdrop')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('airdrop.airdroplist')}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:20%;">{{ __('airdrop.title')}}</th>
						<th style="width:5%;">{{ __('airdrop.moneykind')}}</th>
						<th style="width:5%;">{{ __('airdrop.work')}}</th>
						<th style="width:5%;">{{ __('airdrop.all')}}</th>
						<th style="width:5%;">{{ __('airdrop.ingyeo')}}</th>
						<th style="width:5%;">{{ __('airdrop.start')}}</th>
						<th style="width:5%;">{{ __('airdrop.ending')}}</th>
						<th style="width:5%;">{{ __('airdrop.active')}}</th>
						<th style="width:10%;">{{ __('airdrop.set')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($airdrops as $airdrop)
					<tr>
						<td>{{$airdrop->title}}</td>
						<td>{{$airdrop->coin}}</td>
						<td>{{$airdrop->cases}}</td>
						<td>{{$airdrop->all_cnt}}</td>
						<td>{{$airdrop->residual_cnt}}</td>
						<td>{{date("Y-m-d", strtotime($airdrop->start_time))}}</td>
						<td>{{date("Y-m-d", strtotime($airdrop->end_time))}}</td>
						<td>
							@if($airdrop->status == -1)
							{{ __('airdrop.soon')}}
							@elseif($airdrop->status == 0)
							{{ __('airdrop.close')}}
							@elseif($airdrop->status == 1)
							{{ __('airdrop.mc')}}
							@endif
						</td>
						<td><a href="{{route('admin.airdrop_edit', $airdrop->id)}}" class="myButton edit">{{ __('airdrop.edit')}}</a></td>
					</tr>
					@empty
					<tr>
						<th colspan="9">{{ __('airdrop.empty')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.airdrop_create')}}'">{{ __('airdrop.add')}}</button>
		</div>
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }} {{ __('airdrop.update')}}
	</div>
</div>

@endsection

@section('script')

@endsection