@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="cs_main_tit">{{ __('event.event')}}</h1>

				<div class="cs_table_wrap">

					<table class="table_label">
						<thead>
							<tr>
								<th>{{ __('event.no')}}</th>
								<th style="width: 45%;">{{ __('event.title')}}</th>
								<th>{{ __('event.active')}}</th>
								<th>{{ __('event.writer')}}</th>
								<th>{{ __('event.start')}}</th>
								<th>{{ __('event.end2')}}</th>
							</tr>
						</thead>
					</table>

					<table class="cs_table">
						<tbody>
							@forelse($events as $event)
								<tr>
									<td>{{$event->id}}</td>
									<td style="width: 45%;"><a href="{{route('event_view',$event->id)}}">{{$event->title}}</a></td>
									<td>
										@if(strtotime($today) < strtotime($event->start_time))
										<b class="red">{{ __('event.soon')}}</b>
										@elseif(strtotime($today) <= strtotime($event->end_time))
										<b class="blue">{{ __('event.ing')}}</b>
										@elseif(strtotime($today) > strtotime($event->end_time))
										<b>{{ __('event.exit')}}</b>
										@endif
									</td>
									<td>{{ __('event.ad')}}</td>
									<td>{{$event->start_time}}</td>
									<td>{{$event->end_time}}</td>
								</tr>
							@empty
							<tr>
								<td colspan="4" class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('event.noevent')}}</td>
							</tr>
							@endforelse
						</tbody>
					</table>

					{!! $events->render() !!}

				</div>

			</div>

		</div>

	</div>

</div>
@endsection