@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('popup.popset')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('popup.poplist')}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;"> {{ __('popup.no')}}</th>
						<th style="width:40%;"> {{ __('popup.title')}}</th>
						<th style="width:10%;"> {{ __('popup.sta')}}</th>
						<th style="width:10%;"> {{ __('popup.writer')}}</th>
						<th style="width:10%;"> {{ __('popup.writedate')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($popups as $popup)
					<tr>
						<td>{{$popup->id}}</td>
						<td><a href="{{route('admin.popup_edit', ['id' => $popup->id, 'country' => $country])}}">{{$popup->title}}</a></td>
						<td>
							@if(strtotime($popup->start_time) > time())
							{{ __('popup.soon')}}
							@elseif(strtotime($popup->start_time) <= time() && time() <= strtotime($popup->end_time))
							{{ __('popup.ing')}}
							@elseif(strtotime($popup->end_time) < time())
							{{ __('popup.end')}}
							@endif
						</td>
						<td>{{$popup->writer_name}}</td>
						<td>{{date("Y-m-d", strtotime($popup->created_at))}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="5">{{ __('popup.nopop')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.popup_create', $country)}}'">{{ __('popup.add')}}</button>
		</div>
		@if($popups)
		{!! $popups->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }} {{ __('notice.update')}}
	</div>
</div>



@endsection