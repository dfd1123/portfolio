@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
        {{ __('admin_qna.inq')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('admin_qna.answer')}}
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.qna_answer_insert',$qna->id)}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{ __('admin_qna.tt')}}</th>
							<td>
								{{$qna->title}}
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('admin_qna.writer')}}</th>
							<td>
								{{$qna->createdby}}
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('admin_qna.con')}}</th>
							<td>
                            {!! $qna->description !!}
							</td>
						</tr>
						<tr>
							<th style="width:10%;">문의 이미지</th>
								<td>
									@if(!empty($qna->image_url))
									<img src="{{asset('/storage/image/qna/'.$qna->image_url)}}" alt="" style="width: 100%; max-width: initial;"/>
									@else
									<img src="" alt="" />
									@endif
								</td>
							</tr>
					</tbody>
				</table>
			</div>
            <div>
                <h5 class="tsa-label-st pl-2 pb-3">{{ __('admin_qna.answer2')}}</h5>
                <textarea rows="15" name="description" id="editor" class="form-control" required="required"></textarea>
            </div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('admin_qna.answer3')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.qna_list',$qna->country)}}'">
				{{ __('admin_qna.can')}}
				</button>
			</div>
		</form>
	</div>
</div>

<script>
	$('#editor')
		.summernote({
			height: 350,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
	$('.note-group-select-from-files').css('display', 'none');
</script>

@endsection

