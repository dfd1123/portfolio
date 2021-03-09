@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">
                
                <div class="guide_img_container">
                    <img src="/images/guide_web.jpg" style="width:100%;" alt />
                </div>

			</div>

		</div>

	</div>

</div>

@endsection