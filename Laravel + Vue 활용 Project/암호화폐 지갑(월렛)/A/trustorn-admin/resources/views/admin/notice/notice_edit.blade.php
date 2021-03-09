@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
        @if($country == 'kr')
		{{ __('notice.set')}}
        @elseif($country == 'jp')
            お知らせの管理
        @elseif($country == 'ch')
            公告管理
        @elseif($country == 'en')
            Notice Management
        @endif
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
        @if($country == 'kr')
		{{ __('notice.edit')}}
        @elseif($country == 'jp')
            お知らせ修正
        @elseif($country == 'ch')
            编辑公告
        @elseif($country == 'en')
            Notice Edit
        @endif
	</div>
	<div class="card-body">
		<form method="post" action="{{route('admin.notice_update',$id)}}">
			@csrf
            <input type="hidden" name="country" value="{{$country}}">
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;"> {{ __('notice.tt')}}</th>
							<td>
								<input type="text" name="title" class="form-control tsa-input-st" value="{{$notice->title}}" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;"> {{ __('notice.con')}}</th>
							<td>
								<textarea rows="15" name="description" id="editor" class="form-control" required="required">{!! $notice->description !!}</textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('notice.edit1')}}
				</button>
                <button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.notice_delete',['country' => $country, 'id' => $id])}}'">
				{{ __('notice.del')}}
                </button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.notice_list',$country)}}'">
				{{ __('notice.cel')}}
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

