@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('event.set')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('event.list')}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">	{{ __('event.pc')}}</th>
						<th style="width:5%;">	{{ __('event.mo')}}</th>
						<th style="width:15%;">	{{ __('event.title')}}</th>
						<th style="width:5%;">	{{ __('event.start')}}</th>
						<th style="width:5%;">	{{ __('event.end2')}}</th>
						<th style="width:5%;">	{{ __('event.active')}}</th>
						<th style="width:5%;">	{{ __('event.lan')}}</th>
						<th style="width:5%;">	{{ __('event.ifuse')}}</th>
						<th style="width:10%;">	{{ __('event.sett')}}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($events as $event)
					<tr>
						<td>
							@if($event->image_url == null)
							<span>{{ __('event.noimg')}}</span>
							@else
							<a href="{{asset('/storage/image/event/'.$event->image_url)}}" target="_blank">{{ __('event.seeimg')}}</a>
							@endif
						</td>
						<td>
							@if($event->image_mobile_url == null)
							<span>{{ __('event.noimg')}}</span>
							@else
							<a href="{{asset('/storage/image/event/'.$event->image_mobile_url)}}" target="_blank">{{ __('event.seeimg')}}</a>
							@endif
						</td>
						<td>{{$event->title}}</td>
						<td>{{$event->start_time}}</td>
						<td>{{$event->end_time}}</td>
						<td>
							@if(strtotime($today) < strtotime($event->start_time))
							{{ __('event.soon')}}
							@elseif(strtotime($today) <= strtotime($event->end_time))
							{{ __('event.ing')}}
							@elseif(strtotime($today) > strtotime($event->end_time))
							{{ __('event.end')}}
							@endif
						</td>
						<td>{{strtoupper($event->lang)}}</td>
						<td>{{$event->active == 1 ? __('event.use') : __('event.nouse')}}</td>
						<td><a href="{{route('admin.event_edit', $event->id)}}" class="myButton edit">{{ __('event.edit')}}</a>&nbsp<a href="{{route('admin.event_delete', $event->id)}}" class="link-delete myButton del" onclick="return confirm('{{ __('event.really')}}');">{{ __('event.del')}}</a></td>
					</tr>
					@empty
					<tr>
						<th colspan="9">{{ __('event.nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.event_create')}}'">{{ __('event.add')}}</button>
		</div>
		@if($events_page)
		{!! $events_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('event.update')}}
	</div>
</div>

@endsection

@section('script')

@endsection