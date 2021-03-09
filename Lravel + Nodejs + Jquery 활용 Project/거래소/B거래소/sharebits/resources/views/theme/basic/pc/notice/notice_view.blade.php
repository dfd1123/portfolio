@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="main_tit">{{ __('support.notice') }}</h1>

				<div class="cs_table_view ios-scroll">

					<div class="panel_subject">
						<span class="subjt">{{$notice->title}}</span>
						<span class="reporting_date">{{ __('support.date_created') }} <span class="pl-2">{{date("Y-m-d", $notice->created)}}</span></span>
					</div>

					<div class="panel_content">
						{!! $notice->description !!}
					</div>

					<div class="panel_footer mt-4">
						@if($before_notice)
							
							<div class="panel_ft_list">
								<span class="ft_label">{{ __('support.before') }}</span>
								<span class="ft_subjt"><a href="{{route('notice_view',$before_notice->id)}}">{{$before_notice->title}}</a></span>
							</div>
						@endif

						@if($after_notice)
							<div class="panel_ft_list">
								<span class="ft_label">{{ __('support.next') }}</span>
								<span class="ft_subjt"><a href="{{route('notice_view',$after_notice->id)}}">{{$after_notice->title}}</a></span>
							</div>		
						@endif
					</div>

					<div class="text-right mt-3">

						<button class="btn_style" onclick="location.href='{{route('notice')}}'">{{ __('support.list') }}</button>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>

@endsection