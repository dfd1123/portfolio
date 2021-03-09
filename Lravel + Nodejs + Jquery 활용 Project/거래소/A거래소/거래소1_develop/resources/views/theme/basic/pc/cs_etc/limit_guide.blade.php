@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="cs_main_tit">{{ __('support.limited') }}</h1>

                <div class="limit_guide_sub_ment">
                    <p>스포와이드에서는 회원님의 소중한 자산을 보호하기 위해 아래와 같이 입출금 한도를 적용하고 있습니다.</p>
                </div>

                <div class="guide_text_container" id="limit_guide_text_con">
                    @include(session('theme').'.pc.cs_etc.include.limit_guide')
                </div>

			</div>

		</div>

	</div>

</div>

@endsection