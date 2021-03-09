@extends('pc.layouts.app')

@section('content')
<div class="sub_spot center" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>고객센터</h2>
</div>
<div id="container">
	<div class="my_cate">
		<ul>
			<li><a href="{{route('notices.index')}}" class="">공지사항</a></li>
			<li><a href="{{route('faq.list')}}" class="">FAQ</a></li>
			<li><a href="#" class="on">이벤트</a></li>
		</ul>
	</div>
	<div class="event_viewbox">
			<h3>{{$event->title}}</h3>
			<span class="ev_time">{{ date("Y-m-d", strtotime($event->start_time)) }} ~ {{date("Y-m-d", strtotime($event->end_time)) }}</span>
			@if(($event->start_time) < ($today) && ($today) < ($event->end_time))
				<p class="ing">진행중</p>
			@elseif($today > $event->end_time)
				<p class="noting">종료</p>
			@elseif($today < $event->start_time)
				<p class="noting">예정</p>
			@endif
			<!-- 종료 시 -->
			<!-- <p class="noting">종료</p> -->
			<!-- //종료 시 -->
			@if($event->file1 != NULL)
				<div class="attachment">
					<span><i class="fal fa-file-download"></i> 첨부파일 :</span>
					<span><a href="{{asset('/storage/event/'.$event->file1)}}" target="_blank">{{ $event->file1 }}</a></span>
				</div>
			@endif
			<div class="event_con">
				{!! $event->body !!}
			</div>
		</div>
		<div class="btn_area"><a href="{{url()->previous()}}" class="listgo">목록</a></div>
</div>

@endsection

