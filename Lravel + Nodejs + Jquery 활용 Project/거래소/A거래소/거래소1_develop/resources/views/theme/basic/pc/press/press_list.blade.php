@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="cs_main_tit">{{ __('support.press') }}</h1>

				<div class="cs_table_wrap">

					<table class="table_label">
						<thead>
							<tr>
								<th>{{ __('support.number') }}</th>
								<th>{{ __('support.title') }}</th>
								<th>{{ __('support.writer') }}</th>
								<th>{{ __('support.date') }}</th>
							</tr>
						</thead>
					</table>

					<table class="cs_table">
						<tbody>
    						{{--@forelse($notices as $notice)--}}
								<tr>
									<td>{{--{{$notice->id}}--}}1</td>
									<td><a href="{{ route('press.show', '1241') }}">{{--{{$notice->title}}--}}보도자료 제목입니다.</a></td>
									<td>{{ __('support.admin') }}</td>
									<td>{{--{{date("Y-m-d", $notice->created)}}--}}2019-08-21</td>
								</tr>
                            {{--@empty--}}
								<tr>
									<td colspan="4" class="non_data"><img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('support.no_press') }}</td>
								</tr>
                            {{--@endforelse--}}
						</tbody>
					</table>

					<!--게시물 10개 이상이면 페이지 넘어가면서 페이지네이션 생김-->
					<!--<div class="cs_pagination">
						<ul>
							<li class="paging_arrow"><a href="#"><i class="fal fa-angle-double-left"></i></a></li>
							<li class="paging_arrow mr-1"><a href="#"><i class="fal fa-angle-left"></i></a></li>
							<li class="active"><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li><a href="#">6</a></li>
							<li class="paging_arrow ml-1"><a href="#"><i class="fal fa-angle-right"></i></a></li>
							<li class="paging_arrow"><a href="#"><i class="fal fa-angle-double-right"></i></a></li>
						</ul>
					</div>-->
					<!--//게시물 10개 이상이면 페이지 넘어가면서 페이지네이션 생김-->
					{{--{!! $notices->render() !!}--}}
				</div>

			</div>

		</div>

	</div>

</div>
@endsection