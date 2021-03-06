@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="cs_main_tit">{{ __('support.notice') }}</h1>

				<div class="cs_table_wrap">
					<div class="comunity_table_hd">
							<form method="GET" action="" id="comunity_srch">
									<div class="comunity_board_sch_wrap">
											<select class="comunity_board_select_bar" name="filter">
												<option value="all" {{($srch_filter == 'all')?'selected=selected':''}}>{{ __('support.all') }}</option>
												<option value="title" {{($srch_filter == 'title')?'selected=selected':''}}>{{ __('support.title') }}</option>
												<option value="content" {{($srch_filter == 'description')?'selected=selected':''}}>{{ __('support.contents') }}</option>
											</select>
											<span class="comunity_board_sch_bar">
													<input type="text" name="srch" placeholder="{{ __('support.word_search') }}" {{($srch != NULL)?'value='.$srch:''}}>
													<button type="submit"></button>
											</span>
									</div>
							</form>
					</div>
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
							@forelse($notices as $notice)
								<tr>
									<td>{{$notice->id}}</td>
									<td><a href="{{route('notice_view',$notice->id)}}">{{$notice->title}}</a></td>
									<td>{{ __('support.admin') }}</td>
									<td>{{date("Y-m-d", $notice->created)}}</td>
								</tr>
							@empty
								<tr>
									<td colspan="4" class="non_data"><img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('support.surpport_sentence2') }}</td>
								</tr>
							@endforelse
						</tbody>
					</table>

					<!--????????? 10??? ???????????? ????????? ??????????????? ?????????????????? ??????-->
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
					<!--//????????? 10??? ???????????? ????????? ??????????????? ?????????????????? ??????-->
					<div class="cs_pagination">
						{!! $notices->render() !!}
					</div>
				</div>

			</div>

		</div>

	</div>

</div>
@endsection