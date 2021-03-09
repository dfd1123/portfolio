@extends('admin.layouts.app')

@section('content')

<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.notice_update',$notice->id)}}">

	@csrf
	  
  	<div class="card mb-3 tsa-card">
		<div class="card-header">
			공지사항 수정
        </div>
		<div class="card-body">
		  <div class="table-responsive tsa-event-table">
		    <table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
		        <tbody>
		        	<tr>
			        	<th style="width:20%;">제목</th>
			        	<td><input type="text" name="title"  class="form-control tsa-input-st" required="required" value="{{ $notice->title }}"/></td>
			        </tr>
			        <tr>
			        	<th style="width:20%;">내용</th>
			        	<td>
			         		<textarea id="body" name="body" class="form-control" style="height:500px;resize: none;" />{{ $notice->body }}</textarea>
			         	</td>
			        </tr>
			        <tr>
			        	<th>첨부파일</th>
			        	<td>
			        		<div class="filebox">
						  		<label for="file1" class="myButton use">업로드</label> 
						  		<input type="file" id="file1" name="file1"/>
						  		<span class="filename">
						  			@if(isset($notice))
						  				@if($notice->file1 == NULL)
										    (첨부파일없음)
										@else
											<a href="{{asset('/storage/image/'.$notice->file1)}}" target="_blank" class="myButton xbtn">Download</a>
										@endif	
						  			@endif
						  		</span>
						  	</div>
			        	</td>
			        </tr>
		        </tbody>
		    </table>
		  </div>
		  	<div class="org_btn_group btn_area">
				<button type="submit" class="btn btn-default org_btn">수정</button>
				<button type="button" class="btn btn-default org_btn" onclick="location.href='{{url()->previous()}}'">목록</button>
				<a href="{{route('admin.notice_delete',$notice->id)}}" class="listgo">삭제</a>
			</div>
		</div>
	</div>
</form>

@endsection

@section('script')

<script>
	$('#file1').change(function(e){
		var input = this;
		if (input.files && input.files[0]) {
			$(input).parent().find('.filename').text(input.files[0].name);
		}
	});
	$('#body')
		.summernote({
			height: 300,
			lang: 'ko-KR',
			disableDragAndDrop: true
		});
	$('.note-editing-area').css('word-break', 'break-all');
</script>

@endsection