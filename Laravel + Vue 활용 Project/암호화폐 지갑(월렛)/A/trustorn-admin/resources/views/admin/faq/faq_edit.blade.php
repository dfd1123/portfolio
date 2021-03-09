@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
        {{ __('faq.set')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('faq.edit')}}
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.faq_update',$id)}}">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
                        <tr>
                            <th style="width:10%;">{{ __('faq.dv')}}</th>
                            <td>
                                <select name="faq_type" id="faq_type">
                                    <option value="1" {{ ($faq->faq_type == 1)? "selected=selected":""}}>{{__('faq.1')}}</option>
                                    <option value="2" {{ ($faq->faq_type == 2)? "selected=selected":""}}>{{__('faq.2')}}</option>
                                    <option value="3" {{ ($faq->faq_type == 3)? "selected=selected":""}}>{{__('faq.3')}}</option>
                                </select>
                            </td>
                        </tr>
						<tr>
							<th style="width:10%;">{{ __('faq.q1')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" value="{{$faq->question}}" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('faq.an')}}</th>
							<td>
								<textarea rows="15" name="description" id="editor" class="form-control" required="required">{!! $faq->answer !!}</textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
					수정
				</button>
                <button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.faq_delete',['country' => $country, 'id' => $id])}}'">
                    삭제
                </button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.faq_list',['country'=>$country, 'types'=>$faq->faq_type])}}'">
					취소
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

