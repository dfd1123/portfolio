@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('user.wallet_move') }}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('user.move_list') }}
	</div>
	<div class="card-body">
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
				<select name="keyword_srch">
					<option value="id">{{ __('user.id') }}</option>
					<option value="name">{{ __('user.name') }}</option>
				</select>
				<input type="text" name="keyword" />
				<button type="submit">{{ __('user.search') }}</button>
			</form>
		</div>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">{{ __('user.id') }}</th>
						<th style="width:5%;">{{ __('user.user_id') }}</th>
						<th style="width:5%;">{{ __('user.user_name') }}</th>
						<th style="width:5%;">{{ __('user.money') }}</th>
						<th style="width:5%;">{{ __('user.gb') }}</th>
						<th style="width:5%;">{{ __('user.price') }}</th>
						<th style="width:5%;">{{ __('user.fees') }}</th>
						<th style="width:20%;">{{ __('user.memo') }}</th>
						<th style="width:8%;">{{ __('user.date') }}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($activities as $activity)
					<tr>
						<td>{{$activity->id}}</td>
						<td>{{$activity->username}}</td>
						<td>{{$activity->fullname}}</td>
						<td>{{$activity->cointype}}</td>
						<td>
							@if($activity->type == 'in')
							{{ __('user.in') }}
							@elseif($activity->type == 'out')
							{{ __('user.out') }}
							@endif
						</td>
						<td>{{$activity->amount}}</td>
						<td>
							@if((float) $activity->amount_fee != 0)
							{{$activity->amount_fee}}
							@endif
						</td>
						<td>{{$activity->memo}}</td>
						<td>{{$activity->created_dt}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="9">{{ __('user.user_sentence_3') }}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($activities_page)
		{!! $activities_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('user.user_sentence_2') }}
	</div>
</div>

@endsection

@section('script')

@endsection