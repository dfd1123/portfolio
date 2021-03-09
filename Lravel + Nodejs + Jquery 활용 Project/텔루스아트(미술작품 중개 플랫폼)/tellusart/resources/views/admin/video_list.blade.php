@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">마케팅/홍보</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		{{$title}}
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered video_list_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>영상제목</th>
						<th>영상링크(유튜브)</th>
						<th>설정</th>
					</tr>
				</thead>
				<tbody>
					@forelse($videos as $video)
				    <tr data-id="{{$video->id}}">
						<td><input type="text" name="title" value="{{$video->title}}" class="form-control tsa-input-st"/></td>		
						<td><input type="text" name="link" value="{{$video->video_link}}" class="form-control tsa-input-st"/></td>
						<td>
							<a href="javascript:;" class="link-use myButton use" style="{{($video->use_video)==0 ? '' : 'display:none'}}">사용</a>
							<span class="link-using myButton edit" style="{{($video->use_video)==1 ? '' : 'display:none'}}">사용중</span>
							<a href="{{route('admin.video_delete', $video->id)}}" class="link-delete myButton xbtn">삭제</a>
						</td>
					</tr>
					@empty
					<tr>
						<th colspan="3">홍보영상이 존재하지 않습니다.</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="org_btn" onclick="location.href='{{route('admin.video_create')}}'">추가</button>
		</div>
		@if($videos_page)
		{!! $videos_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>

$("input[name='title']").change(function(){
	var title = $(this).val();
	var id = $(this).closest('tr').data('id');
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm("정말 영상제목을 '"+title+"'(으)로 변경하시겠습니까?")){
		$.ajax({
            url: '/video/title/change',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, title: title, id:id},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
            	alert('영상제목 변경완료!'); 
    		}
        }); 
	}
});

$("input[name='link']").change(function(){
	var video_link = $(this).val();
	var id = $(this).closest('tr').data('id');
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm("정말 영상링크를 변경하시겠습니까?")){
		$.ajax({
            url: '/video/link/change',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, video_link: video_link, id:id},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
            	alert('영상링크 변경완료!'); 
    		}
        }); 
	}
});

$(".link-use").click(function(){
	var link = $(this);
	var row = link.closest('tr');
	var id = row.data('id');
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm("홍보영상을 변경하시겠습니까?")){
		$.ajax({
            url: '/video/use/change',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, use_video: 1, id:id},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
            	var table = $('.video_list_adm_table');
            	table.find('.link-using').hide();
            	table.find('.link-use').show();
            	row.find('.link-use').hide();
            	row.find('.link-using').show();
            	alert('홍보영상 변경완료!');
    		}
        }); 
	}
});

$('.link-delete').click(function(e){
	if(!confirm("영상을 삭제하시겠습니까?")){
		e.preventDefault();
	}
});

</script>

@endsection