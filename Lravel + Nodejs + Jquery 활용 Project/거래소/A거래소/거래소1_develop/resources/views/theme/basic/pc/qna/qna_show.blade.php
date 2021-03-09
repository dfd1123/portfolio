@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">

			@include(session('theme').'.pc.notice.include.sub_menu')

			<div class="right_con">

				<h1 class="cs_main_tit">{{ __('support.one_to_one_ask') }}</h1>

				<div class="cs_table_view ios-scroll">
					
					<div class="panel_subject">
						<span class="subjt">{{$qna->title}}</span>
						<span class="reporting_date">
						{{ __('support.writer') }} <span class="rpt_author pl-2 pr-3 mr-3">{{Auth::user()->fullname}}</span>
						{{ __('support.date_created') }} <span class="pl-2">{{date("Y-m-d",$qna->created)}}</span>
						</span>
					</div>

					<div class="panel_content qna_content question_content">
						@if(!empty($qna->image_url))
						<img src="{{asset('/storage/image/qna/'.$qna->image_url)}}" alt="" style="width: 100%;"/>
						@else
						<img src="" alt="" />
						@endif
					</div>
					
					<div class="panel_content qna_content question_content">
						{!! $qna->description !!}
					</div>
					
					<div class="panel_content qna_content answer_content">
						<div class="answer_box">
							@if($qna_answer)
								<p class="answer_p">
									<i class="fas fa-exclamation-circle"></i>
									{!! $qna_answer->description !!}
								</p>
							@else
								<p class="answer_p nothing_p">
									<img src="/images/icon_notice.svg" alt="" class="btn_notice">
									{{ __('support.surpport_sentence1') }}
								</p>
							@endif
						</div>
					</div>
					
					<div class="text-right mt-3">
						@if(!$qna_answer)
							<!--답변 대기 중일 때 수정버튼 나타남-->
							<button id="qnaModify" class="btn_style cancel_btn write_btn">{{ __('support.modify') }}</button>
							<!--답변 대기 중일 때 수정버튼 나타남-->
						@endif
						<button class="btn_style" onclick="location.href='{{route('qna_list')}}'">{{ __('support.list') }}</button>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>
@if(!$qna_answer)
	<div id="fullscreen_modal" class="hide_modal"></div>

	<div class="modal_popup hide_modal qna_modal" id="qnaModify1">

		<h1 class="notosans mb-4">{{ __('support.change_one_to_one_ask') }}</h1>
		<form method="post" enctype="multipart/form-data" action="{{route('qna_insert')}}">
			@csrf
			<input type="hidden" name="id" value="{{$id}}" />
			<div class="form-group">
				<input class="form-control" type="text" name="title" value="{{$qna->title}}" placeholder="{{ __('support.input_title') }}">
			</div>
			
			<div class="form-group mb-3">
				<textarea class="form-control" name="description">{!! $qna->description !!}</textarea>
			</div>

			<div class="form-group mb-4" style="height: 35px;">
				<input type="file" name="file1" id="thum_file" class="hide img_up" >
				<input type="text" value="" class="form-control mr-2 float-left filename_input" style="width: 70%;" readonly>
				<label class="form-control attach_btn float-left" for="thum_file" style="width: 28%;">{{ __('support.add_image') }}</label>
			</div>

			<div class="form-group mb-0">

				<div class="both_btn_group pl-5 pr-5">
					<button type="submit" class="btn_style" name="qna_submit" value="edit">
					{{ __('support.modify_good') }}
					</button>
					<a href="#" class="btn_style cancel_btn">
					{{ __('support.cancel') }}
					</a>
				</div>

			</div>
		</form>

	</div>
@endif

@endsection