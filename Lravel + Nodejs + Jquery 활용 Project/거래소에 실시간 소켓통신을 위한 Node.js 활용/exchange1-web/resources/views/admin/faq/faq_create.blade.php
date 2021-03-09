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
	{{ __('faq.add')}}
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.faq_insert')}}">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
                        <tr>
                            <th style="width:10%;">{{ __('faq.dv')}}</th>
                            <td>
                                <select name="faq_type" id="faq_type">
                                    <option value="1">{{__('faq.1')}}</option>
                                    <option value="2">{{__('faq.2')}}</option>
                                    <option value="3">{{__('faq.3')}}</option>
                                    <option value="4">{{__('faq.4')}}</option>
                                </select>
                            </td>
                        </tr>
						<tr>
							<th style="width:10%;">{{ __('faq.q1')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('faq.an')}}</th>
							<td>
								<textarea rows="15" name="description" id="editor" class="form-control" required="required"></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('faq.add1')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.faq_list',['country'=>$country, 'types'=>0])}}'">
				{{ __('faq.can')}}
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

