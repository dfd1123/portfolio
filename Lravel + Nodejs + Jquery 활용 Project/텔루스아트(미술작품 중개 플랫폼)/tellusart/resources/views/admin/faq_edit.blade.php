@extends('admin.layouts.app')

@section('content')

<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.faq_update',$faq->id)}}">

	@csrf
	  
  	<div class="card mb-3 tsa-card">
		<div class="card-header">
			FAQ 추가
        </div>
		<div class="card-body">
		  <div class="table-responsive tsa-event-table" style="width:1000px;margin:0 auto;">
		    <table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
		        <tbody>
		        	<tr>
			        	<th style="width:10%;text-align: center;">질문</th>
			        	<td>
			        		<textarea name="question" class="form-control" style="height:90px; resize: none;">{{ $faq->question }}</textarea>
			        	</td>
			        </tr>
			        <tr>
			        	<th style="width:10%;text-align: center;">답변</th>
			        	<td>
			        		<textarea name="answer" class="form-control" style="height:300px; resize: none;">{{ $faq->answer }}</textarea>
			        	</td>
			        </tr>
		        </tbody>
		    </table>
		  </div>
		  	<div class="org_btn_group btn_area">
				<button type="submit" class="btn btn-default org_btn">수정</button>
				<button type="button" class="btn btn-default org_btn" onclick="location.href='{{url()->previous()}}'">목록</button>
				<a href="{{route('admin.faq_delete',$faq->id)}}" class="listgo">삭제</a>
			</div>
		</div>
	</div>
</form>

@endsection

