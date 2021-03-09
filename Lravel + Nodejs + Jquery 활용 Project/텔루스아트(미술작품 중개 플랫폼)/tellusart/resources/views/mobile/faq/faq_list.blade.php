@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
	
	<div class="noticebox">
			<span class="txinfo">총 <strong>{{count($faqs)}}개</strong>의 항목 있습니다.</span>

			<ul id="navi">
				@forelse($faqs as $faq)
				<li class="group">
					<div class="title"> <strong class="en">Q</strong><p>
						{{$faq->question}}
						@if(date("Y.m.d", strtotime($faq->created_at)) >= date("Y.m.d",strtotime("-2 days")))
							<span style="color:#fec603">New</span>
						@endif
						<span class="en">{{date("Y.m.d", strtotime($faq->updated_at))}}</span>
					</p></div>
					<ul class="sub_notice">
						<li>
						 <strong class="en">Q</strong>
							<p>{{$faq->answer}}</p>
						</li> 
					</ul>
				</li>
				@empty
				<li class="nondata">
					등록된 FAQ가 없습니다.
				</li>
				@endforelse
				 
			</ul>
			<a href="" class="more">더보기<img src="{{asset('/storage/image/mobile/ic_plust.png')}}" alt=""></a>
			<script>
	
				$(".sub_notice").hide();
				$("#navi .title").click(function () {
					if ( $(this).next().css("display") == "none" ) {
						$(".sub_notice").slideUp();
					};
					$(this).next().slideToggle();
				})
		
			</script>
		</div>
</div>
@endsection