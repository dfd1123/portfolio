@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

                <div class="cs_main_tit">
                    {{ __('support.notice') }}
                    <div class="board_buttons">
                        <button class="solid_btn" onclick="location.href='{{route('newsflash')}}'">{{ __('support.list') }}</button>
                    </div>
                </div>

				<div class="cs_table_view ios-scroll cs_table_view_02">

					<div class="panel_subject">
                        <span class="subjt">{{$newsflash->title}}</span>
                        <div class="board_dates">
                            <span class="reporting_date">{{ __('support.writer') }}&nbsp;&nbsp;<span class="pr-2">스포와이드</span>|&nbsp;</span>
                            <span class="reporting_date">{{ __('support.date_created') }}<span class="pl-2">{{date("Y-m-d", $newsflash->created)}}</span></span>
                        </div>
					</div>

					<div class="panel_content">
						{!! $newsflash->description !!}
					</div>

					<div class="panel_footer mt-4">
						@if($before_newsflash)
							
							<div class="panel_ft_list">
								<span class="ft_label">{{ __('support.before') }}</span>
								<span class="ft_subjt"><a href="{{route('newsflash_view',$before_newsflash->id)}}">{{$before_newsflash->title}}</a></span>
							</div>
						@endif

						@if($after_newsflash)
							<div class="panel_ft_list">
								<span class="ft_label">{{ __('support.next') }}</span>
								<span class="ft_subjt"><a href="{{route('newsflash_view',$after_newsflash->id)}}">{{$after_newsflash->title}}</a></span>
							</div>		
						@endif
					</div>

				</div>

			</div>

		</div>

	</div>

</div>

@endsection