@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">공지사항 관리</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		공지사항 상세보기
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<tbody>
		        	<tr>
			        	<th style="width:20%;">제목</th>
			        	<td>{{$notice->title}}</td>
			        </tr>
			        <tr>
			        	<th style="width:20%;">내용</th>
			        	<td  style="height:500px;">
			         		{!! $notice->body !!}
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
											<a href="{{asset('/storage/notice/'.$notice->file1)}}" target="_blank" class="myButton xbtn">Download</a>
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
			<button type="submit" class="btn btn-default org_btn" onclick="location.href='{{route('admin.notice_edit',$notice->id)}}'">수정</button>
			<button type="button" class="btn btn-default org_btn" onclick="history.back()">목록</button>
			<a href="{{route('admin.notice_delete',$notice->id)}}" class="listgo">삭제</a>
		</div>
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>
	
</script>

@endsection