@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('coinlock.lockset')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('coinlock.locklist')}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>{{ __('coinlock.lockkind')}}</th>
						<th>{{ __('coinlock.fee')}}</th>
						<th>{{ __('coinlock.active')}}</th>
						<th>{{ __('coinlock.time')}}</th>
						<th>{{ __('coinlock.time2')}}</th>
						<th style="width:15%;">{{ __('coinlock.active2')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($coins as $coin)
					<tr>
						<td>{{$coin->coin}}</td>
						<td>{{$coin->ratio}}</td>
						<td>
							@if ($coin->status == -1)
							{{ __('coinlock.ending')}}
							@elseif ($coin->status == 0)
							{{ __('coinlock.off')}}
							@elseif ($coin->status == 1)
							{{ __('coinlock.ing')}}
							@endif
						</td>
						<td>{{$coin->created_dt}}</td>
						<td>{{$coin->updated_dt}}</td>
						<td>
							@if ($coin->status == -1)
							<a href="{{route('admin.coin_lock_action', ['id' => $coin->id, 'type' => 'cancel_exit'])}}" class="btn_cancel_exit myButton navy">{{ __('coinlock.noend')}}</a>
							@elseif ($coin->status == 0)
							<a href="{{route('admin.coin_lock_action', ['id' => $coin->id, 'type' => 'start'])}}" class="btn_start myButton edit">{{ __('coinlock.gogo')}}</a>
							@elseif ($coin->status == 1)
							<a href="{{route('admin.coin_lock_action', ['id' => $coin->id, 'type' => 'exit'])}}" class="btn_exit myButton xbtn">{{ __('coinlock.realend')}}</a>
							@endif
							<a href="{{route('admin.coin_lock_edit', $coin->id)}}" class="myButton edit">{{ __('coinlock.edit')}}</a>
						</td>
					</tr>
					@empty
					<tr>
						<th colspan="7">{{ __('coinlock.nocoinlock')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.coin_lock_create')}}'">{{ __('coinlock.add')}}</button>
		</div>
		@if($coins_page)
		{!! $coins_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('coinlock.update')}}
	</div>
</div>

@endsection

@section('script')
<script>
	$(function() {
		$('.btn_cancel_exit').click(function() {
			return confirm('{{ __('coinlock.endc')}}');
		});

		$('.btn_start').click(function() {
			return confirm('{{ __('coinlock.start')}}');
		});

		$('.btn_exit').click(function() {
			return confirm('{{ __('coinlock.end')}}');
		});
	});
</script>
@endsection