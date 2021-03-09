@extends('pc.layouts.app')

@section('content')
<div class="sub_spot center" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>고객센터</h2>
</div>
<div id="container">
	<div class="my_cate">
		<ul>
			<li style="position:relative;">
				<a href="{{route('notices.index')}}" class="">
					공지사항
					@auth
						@if($newnotice_cnt > 0)
							<div class="newcircle">
								{{$newnotice_cnt}}
							</div>
						@endif
					@else
						@if($count1 > 0)
							<div class="newcircle">
								{{$count1}}
							</div>
						@endif
					@endauth
				</a>
			</li>
			<li style="position:relative;">
				<a href="{{route('faq.list')}}" class="">
					FAQ
					@auth
						@if($newfaq_cnt > 0)
							<div class="newcircle">
								{{$newfaq_cnt}}
							</div>
						@endif
					@else
						@if($count3 > 0)
							<div class="newcircle">
								{{$count3}}
							</div>
						@endif
					@endauth
				</a>
			</li>
			<li style="position:relative;">
				<a href="#"  class="on">
					이벤트
					@auth
						@if($newevent_cnt > 0)
							<div class="newcircle">
								{{$newevent_cnt}}
							</div>
						@endif
					@else
						@if($count3 > 0)
							<div class="newcircle">
								{{$count3}}
							</div>
						@endif
					@endauth
				</a>
			</li>
		</ul>
	</div>
	<div class="event_boxlist">
		<h3>총 <strong>{{count($events)}}</strong>개의 게시물이 있습니다.</h3>
		<div class="evlist">
			@forelse($events as $event)
				<div class="evenlist">
					<p><a href="{{route('events.show',$event->id)}}"><img src="{{asset('/storage/event/'.$event->pc_banner)}}" alt=""/></a></p>
					<div>
						@if(($event->start_time) < ($today) && ($today) < ($event->end_time))
							<span class="ing">진행중</span>
						@elseif($today > $event->end_time)
							<span class="noting">종료</span>
						@elseif($today < $event->start_time)
							<span class="noting">예정</span>
						@endif
						<h4>{{$event->title}}
						@if(date("Y.m.d", strtotime("-2 days")) < date("Y.m.d", strtotime($event->created_at)))
							<em style="color:#fea803">New</em>
						@endif
						</h4>
						<ul>
							<li>{{ date("Y.m.d", strtotime($event->start_time)) }} ~ {{ date("Y.m.d", strtotime($event->end_time)) }}</li>
						</ul>
						
					</div>
				</div>
			@empty
				등록된 이벤트가 없습니다.
			@endforelse
				

		</div>
	</div>
	@if(count($events) > 7)
		<div class="paging_board">
			<span class="af">
				<a href="/events/event_show"><i class="fas fa-angle-double-left"></i></a>
			</span>
			{!! $events->render() !!}
			<span class="bf">
				<a href="/events/event_show"><i class="fas fa-angle-double-right"></i></a>
			</span>
		</div>
	@endif
</div>

@endsection