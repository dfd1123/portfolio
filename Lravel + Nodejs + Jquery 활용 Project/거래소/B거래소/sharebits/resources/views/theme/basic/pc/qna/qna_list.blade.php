@extends(session('theme').'.pc.layouts.app')

@section('content')


<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">

			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="main_tit">
				{{ __('support.one_to_one_ask') }}
					<a href="#" id="qnaWrite" class="write_btn write_btn_st"><i class="fal fa-plus"></i> {{ __('support.do_one_to_one_ask') }}</a>
				</h1>

				<div class="cs_table_wrap qna_table_wrap">

					<table class="table_label">
						<thead>
							<tr>
								<th>{{ __('support.number') }}</th>
								<th>{{ __('support.title') }}</th>
								<th>{{ __('support.answer_whether') }}</th>
								<th>{{ __('support.writer') }}</th>
								<th>{{ __('support.date') }}</th>
							</tr>
						</thead>
					</table>

					<table class="cs_table">
						<tbody>
							@forelse($qnas as $qna)
								<tr>
									<td>{{$qna->id}}</td>
									<td><a href="{{route('qna_show', $qna->id)}}">{{$qna->title}}</a></td>
									<td>
										@if($qna->answered)
											<b class="complete_ans">{{ __('support.success_answer') }}</b>
										@else
											<b class="wait_ans">{{ __('support.waiting_answer') }}</b>
										@endif
									</td>
									<td>{{Auth::user()->fullname}}</td>
									<td>{{date("Y-m-d",$qna->created)}}</td>
								</tr>
							@empty
								<tr>
									<td class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i> {{ __('support.not') }}</td>
								</tr>
							@endforelse
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
					{!! $qnas->render() !!}

				</div>

			</div>

		</div>

	</div>

</div>

<div id="fullscreen_modal" class="hide"></div>

<div class="modal_popup hide" id="qnaWrite1">

	<h1 class="notosans mb-4">{{ __('support.do_one_to_one_ask') }}</h1>
	<form method="post" action="{{route('qna_insert')}}" id="pna_write">
		@csrf
		<div class="form-group">
			<input class="form-control" type="text" name="title" placeholder="{{ __('support.input_title') }}">
		</div>
		
		<div class="form-group mb-4">
			<textarea class="form-control" name="description"></textarea>
		</div>

		<div class="form-group mb-0">

			<div class="both_btn_group pl-5 pr-5">
				<button type="submit" class="btn_style" name="qna_submit" value="create">
				{{ __('support.registration') }}
				</button>
				<a href="#" class="btn_style cancel_btn">
				{{ __('support.cancel') }}
				</a>
			</div>

		</div>
	</form>

</div>

@endsection