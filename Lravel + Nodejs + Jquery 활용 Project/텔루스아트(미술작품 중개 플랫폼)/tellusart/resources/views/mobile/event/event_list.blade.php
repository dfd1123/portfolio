@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
	
	<div class="board_list">
		<div id="event_list" data-offset="{{count($events)}}" data-count="{{$event_cnt}}">
		@forelse($events as $event)
			<div class="date en">
				{{explode(' ',$event->created_at)[0]}}
			</div>
			<div class="elist_box">
				<h3><a href="{{route('events.show',$event->id)}}">
					{{$event->title}}
					@if(date("Y.m.d", strtotime($event->created_at)) >= date("Y.m.d",strtotime("-2 days")))
						<span style="color:#fec603">New</span>
					@endif
				</a></h3>
				
				<div class="conbox">
					<p><a href="{{route('events.show',$event->id)}}"><img src="{{asset('/storage/event/'.$event->pc_banner)}}" alt=""/></a></p>
				</div>
				<div class="estate">
					@if(($event->start_time) < ($today) && ($today) < ($event->end_time))
						<span class="ing">진행중</span>
					@elseif($today > $event->end_time)
						<span class="noting">종료</span>
					@elseif($today < $event->start_time)
						<span class="noting">예정</span>
					@endif
					<ul>
						<li>{{ date("Y.m.d", strtotime($event->start_time)) }} ~ {{ date("Y.m.d", strtotime($event->end_time)) }}</li>
					</ul>
				</div>
			</div>
		@empty
			등록된 이벤트가 없습니다.
		@endforelse
		</div>
	<div id="board_load" class="loading dot" style="display:none;">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>
	@if($events)
		<a href="#" class="more" onclick="mobile_event_more()">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""></a>
	@endif
	</div>
</div>

@endsection