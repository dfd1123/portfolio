@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
	
	<div class="board_list">
	
		<div class="blist_box">
			
			<div class="estate">
				@if(($event->start_time) < ($today) && ($today) < ($event->end_time))
					<span class="ing">진행중</span>
				@elseif($today > $event->end_time)
					<span class="noting">종료</span>
				@elseif($today < $event->start_time)
					<span class="noting">예정</span>
				@endif
				<ul>
					<li>{{ date("Y-m-d", strtotime($event->start_time)) }} ~ {{date("Y-m-d", strtotime($event->end_time)) }}</li>
				</ul>
			</div>
			<h3>{{$event->title}}</h3>
			<div class="conbox">
				<p><img src="{{asset('/storage/image/event/'.$event->file1)}}" alt=""/></p>
				{!! $event->body !!}
			</div>
			
			<dl>
				<dt>조회수 : </dt>
				<dd>{{$event->hit}}</dd>
			</dl>
			@if($event->file1 !=null)
			<dl>
				<dt>첨부파일 1 : </dt>
				<dd><img src="{{asset('/storage/image/event/'.$event->file1)}}" alt=""/>img_pic_view1.png </dd>
			</dl>
			@endif
			@if($event->mobile_banner !=null)
			<dl>
				<dt>첨부파일 2 : </dt>
				<dd><img src="{{asset('/storage/image/event/'.$event->mobile_banner)}}" alt=""/>img_pic_view2.png </dd>
			</dl>
			@endif
		</div>
		<button type="submit" class="joinbt"><a href="{{url()->previous()}}" class="listgo" style="color:#fff">목록</a></button>
		
		
		
		
	</div>
</div>

@endsection
