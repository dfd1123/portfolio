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
	<div>
		슬라이드는 최대 10개까지 표시할 수 있습니다. 이미지 사이즈 1900x799 px 을 꼭 지켜주세요!
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered banner_list_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th width="50%">이미지</th>
						<th>번호</th>
						<th>설명</th>
						<th>이미지파일</th>
					</tr>
				</thead>
				<tbody>
					@forelse($slides as $slide)
					<tr data-id="{{$slide->id}}">
						<td>
							@if($slide->slide_file != NULL)
							<img src="/storage/image/slide/{{$slide->slide_file}}" style="height: 80px;"/>
							@else
							<img src="" style="height: 80px;"/>
							@endif
						</td>
						<td>{{$loop->index == 0 ? '디폴트' : $loop->index}}</td>
						<td>{{$slide->slide_info}}</td>
						<td>
							<span class="filebox">
								<label for="file{{$loop->index}}" class="myButton use">업로드</label> 
								<input id="file{{$loop->index}}" type="file" name="file"/>
							</span>
							@if($slide->slide_default == 'Y')
								(디폴트 슬라이드는 이미지 변경만 가능합니다)
							@else
						<span class="slide-delete myButton" onclick="if(confirm('슬라이드를 삭제하시겠습니까?')){window.location.href = '{{route('admin.slide_delete', $slide->id)}}';} else { return false; }" style="height: 40px; background: #ff7979; vertical-align: text-top;">삭제</span>
							@endif	
						</td>
					</tr>
                    @empty
                    <tr>
						<th colspan="4">슬라이드가 존재하지 않습니다.</th>
                    </tr>
                    @endforelse
				</tbody>
			</table>
		</div>
		@if($slides->count() < 10)
		<div>
			<button type="button" class="org_btn" onclick="location.href='{{route('admin.slide_create')}}'">추가</button>
		</div>
		@endif
		@if($slides_page)
		{!! $slides_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>
    $(".filebox label")
	    .click(function(e){
	    	if($(this).data('loading')){
	    		e.preventDefault();
	    	}
	    })
	    .data('loading', false);
    
	$(".filebox input[type='file']").change(function(e){
		var input = this;
		var label = $(this).parent().find("label");
		
		if(label.data('loading')){
			return;
		}
		if (input.files && input.files[0]) {
			var type = input.files[0].type;
			var type_reg = /^image\/(jpg|png|jpeg|bmp|gif)$/;
			
			if (!type_reg.test(type)) {
				input.value = '';
				alert('이미지 파일 형식만 업로드 할 수 있습니다');
				return;
			}
			
			if(confirm(input.files[0].name + " 파일을 업로드하시겠습니까?")){
				label.data('loading', true).text('로드중');
				
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var id = $(this).closest('tr').data('id');
				
				var formData = new FormData();
				formData.append('_token', CSRF_TOKEN);
				formData.append('file', input.files[0]);
				formData.append('id', id);
				
				$.ajax({
					url: '/slide/file/change',
					type: 'POST',
					/* send the csrf-token and the input to the controller */
					data: formData,
					processData: false,
					contentType: false,
					/* remind that 'data' is the response of the AjaxController */
					success: function (data) {
						label.data('loading', false).text('업로드');
						$(input).closest('tr').find('img').attr('src', "/storage/image/slide/" + data.file);
						alert('이미지 변경완료!'); 
					}
				});
			}
			
			input.value = "";
		}
	});
</script>

@endsection