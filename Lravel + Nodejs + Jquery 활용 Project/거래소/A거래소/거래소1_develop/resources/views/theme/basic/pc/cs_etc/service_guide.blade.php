@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="cs_main_tit">{{ __('support.terms_of_service') }}</h1>
                
                <div class="guide_text_container" id="privacy_guide_text_con">
                    @include(session('theme').'.pc.cs_etc.include.service_guide')
                </div>

			</div>

		</div>

	</div>

</div>

@endsection