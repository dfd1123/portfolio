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
				<a href="#"  class="on">
				FAQ
					@auth
						@if($newfaq_cnt > 0)
							<div class="newcircle">
								{{$newfaq_cnt}}
							</div>
						@endif
					@else
						@if($count2 > 0)
							<div class="newcircle">
								{{$count2}}
							</div>
						@endif
					@endauth
				</a>
			</li>
			<li style="position:relative;">
				<a href="{{route('events.index')}}" class="">
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
	<div class="noticebox">
		<h3>총 <strong>{{count($faqs)}}개</strong> 게시물이 있습니다.</h3>
		<ul id="navi">
			@forelse($faqs as $faq)
			 <li class="group">
				<div class="title"> <strong class="en">Q</strong>
					<p>{{$faq->question}}</p>
					@if(date("Y.m.d", strtotime("-2 days")) < date("Y.m.d", strtotime($faq->created_at)))
						<em style="color:#fea803">New</em>
					@endif
					<span class="en">{{date("Y.m.d", strtotime($faq->updated_at)) }}</span>
				</div>
				<ul class="sub_notice">
					<li>
						<strong class="en">A</strong>
						<p>
							{{$faq->answer}}
						</p>
					</li> 
				</ul>
			 </li>
			 @empty
			 <li class="nondata">
			 	등록된 FAQ가 없습니다.
			 </li>
			 @endforelse
			 
		</ul>
	</div>
	@if(count($faqs) > 7)
		<div class="paging_board">
			<span class="af">
				<a href="/faqs/1"><i class="fas fa-angle-double-left"></i></a>
			</span>
			{!! $faqs->render() !!}
			<span class="bf">
				<a href="/faqs/10"><i class="fas fa-angle-double-right"></i></a>
			</span>
		</div>
	@endif
</div>

@endsection
