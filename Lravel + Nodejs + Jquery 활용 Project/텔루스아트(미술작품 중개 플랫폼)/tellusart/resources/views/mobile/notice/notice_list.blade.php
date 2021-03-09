@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
	
	<div class="board_list">
		<div id="notice_list" data-offset="{{$offset}}" data-count="{{$notice_cnt}}">
			@forelse($notices as $key => $notice)
				<div class="date en">
					{{ date("Y.m.d", strtotime($notice->created_at)) }}
				</div>
				<div class="blist_box">
					<h3><a href="{{route('notices.show',$notice->id)}}" style="color:#000">
						{{ $notice->title }}
						@if(date("Y.m.d", strtotime($notice->created_at)) >= date("Y.m.d",strtotime("-2 days")))
							<span style="color:#fec603">New</span>
						@endif
					</a></h3>
					<div class="conbox">
						{!! $notice->body !!}
					</div>
					<ul>
						<li class="en"><img src="{{asset('/storage/image/mobile/ic_viewcount.png')}}" alt=""/> {{ $notice->hit }}</li>
						<li class="en"><img src="{{asset('/storage/image/mobile/ic_file.png')}}" alt=""/>
							첨부파일: 
							@if($notice->file1 !=null)
							1
							@else
							0
							@endif
						</li>
					</ul>
				</div>
			@empty
						공지사항이 없습니다.
			@endforelse
		</div>
		<div id="board_load" class="loading dot" style="display:none;">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
		@if($notices)
			<a href="#" class="more" onclick="mobile_notice_more()">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""></a>
		@endif
	</div>
</div>
@endsection
