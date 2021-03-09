@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">

			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="cs_main_tit">{{  __('support.faq') }}</h1>

				<div class="faq_wrap">
					
					<div class="tab_menu_bar" id="faq_tab_list">
						<ul>
							<li class="active">
								<a href="#"><i class="fal fa-bars mr-1"></i>{{__('faq.all')}}</a>
							</li>
							<li>
								<a href="#">{{__('faq.1')}}</a>
							</li>
							<li>
								<a href="#">{{__('faq.2')}}</a>
							</li>
							<li>
								<a href="#">{{__('faq.3')}}</a>
							</li>
							<li>
								<a href="#">{{__('faq.4')}}</a>
							</li>
						</ul>
					</div>
					
					<div class="faq_tab_group mt-3">
						@forelse($faqs as $faq)
							<div class="faq_tab">
								<input type="radio" id="tab_{{$faq->id}}" name="tabs">
								<label for="tab_{{$faq->id}}">
									<span class="faq_type">{{__('faq.'.$faq->faq_type)}}</span>
									<span class="faq_tit">{{$faq->question}}</span>
								</label>
								<div class="tab_content">
									<div class="tab_answer_box">
										<img src="/images/button/btn_notice_black.svg" alt="" class="btn_notice notice_icon_show">
										{!! $faq->answer !!}
									</div>
								</div>
							</div>
						@empty
							<div class="non_data"><img src="/images/icon_notice.svg" alt="" class="btn_notice">{{  __('support.no') }}</div>
						@endforelse
					</div>
					
				</div>

			</div>

		</div>

	</div>

</div>

@endsection