@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="main_tit">{{ __('event.event')}}</h1>

				<div class="cs_table_view ios-scroll">

					<div class="panel_subject">
						<span class="subjt">{{$event->title}}</span>
						<span class="reporting_date">
							<span class="pl-2">{{ __('event.active')}}</span>
							<span class="pl-2 pr-2">
								@if(strtotime($today) < strtotime($event->start_time))
								<b class="red">{{ __('event.soon')}}</b>
								@elseif(strtotime($today) <= strtotime($event->end_time))
								<b class="blue">{{ __('event.ing')}}</b>
								@elseif(strtotime($today) > strtotime($event->end_time))
								<b>{{ __('event.exit')}}</b>
								@endif
							</span>
							<span class="pl-2 pr-2">{{ __('event.start')}}</span><span>{{$event->start_time}}&nbsp;&nbsp; ~ </span>
							<span class="pl-1 pr-2">{{ __('event.end2')}}</span><span>{{$event->end_time}}</span>
						</span>
						{{-- <p class="right_date_line">
							{{ __('event.writeday')}} <span class="pl-2">{{date("Y-m-d", $event->created)}}</span>
						</p> --}}
					</div>

					<div class="panel_content">
						<img src="{{asset('/storage/image/event/' . $event->image1)}}" /><br />
						<img src="{{asset('/storage/image/event/' . $event->image2)}}" /><br />
						<img src="{{asset('/storage/image/event/' . $event->image3)}}" /><br />
						{!! $event->description !!}
					</div>

					<div class="panel_footer mt-4">
						@if($before_event)
							<div class="panel_ft_list">
								<span class="ft_label">{{ __('event.before')}}</span>
								<span class="ft_subjt"><a href="{{route('event_view',$before_event->id)}}">{{$before_event->title}}</a></span>
							</div>
						@endif

						@if($after_event)
							<div class="panel_ft_list">
								<span class="ft_label">{{ __('event.next')}}</span>
								<span class="ft_subjt"><a href="{{route('event_view',$after_event->id)}}">{{$after_event->title}}</a></span>
							</div>		
						@endif
					</div>

					<div class="text-right mt-3">
						<button class="btn_style" onclick="location.href='{{route('event')}}'">{{ __('event.list2')}}</button>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>

@endsection