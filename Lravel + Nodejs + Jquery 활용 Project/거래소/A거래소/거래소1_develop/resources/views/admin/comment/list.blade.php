@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	댓글 관리
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		댓글 관리
	</div>
	<div class="card-body">
		<select class="form-control" onchange="location.href=(this.value)">
			<option value='/admin/comment'>전체</option>
			@foreach($board_list as $board_item)
			<option value='/admin/comment?board_name={{ $board_item->bo_table }}' {{ $board_item->bo_table == $board_name ? 'selected' : '' }}>{{ $board_item->bo_name }}</option>
			@endforeach
		</select>
		<div class="usr_search_box tsa-sch-box">
			<form method="get" action="">
				<input type="hidden" name="board_name" value="{{ $board_name }}">
				<input type="text" name="keyword" style="padding-left:10px;" placeholder="검색할 닉네임이나 댓글내용을 입력해주세요." value="{{ $keyword }}"/>
				<button type="submit">{{ __('user.search') }}</button>
			</form>
		</div>
		
		<div class="table-responsive tsa-table-wrap">
			<form method="POST" action="{{ route('admin.comunity_comment_delete') }}" id="comment_form">
				@csrf
				<input type="hidden" name="board_name" value="{{ $board_name }}">
				<input type="hidden" name="keyword" value="{{ $keyword }}">
				<input type="hidden" name="page" value="{{ $page }}">
				<button type="submit" class="btn btn-danger" style="margin-bottom:10px;">체크삭제</button>
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th><input type="checkbox" id="allCheck" /></th>
							<th>종류</th>
							<th >작성자 닉네임</th>
							<th >댓글내용</th>
							<th >작성날짜</th>
						</tr>
					</thead>
					<tbody>
						@forelse($comments as $comment)
						<tr>
							<td><input type="checkbox" name="delete_item[]" value="{{ $comment->tablename."_".$comment->id }}" /></td>
							<td>
								{{$comment->board_table ?? '-'}}
							</td>
							<td>
								{{$comment->writer_nickname ?? '-'}}
							</td>
							<td>
								{{$comment->comment ?? '-'}}
							</td>
							<td>
								{{$comment->created_at ?? '-'}}   
							</td>
						</tr>
						@empty
						<tr>
							<th colspan="13">댓글이 존재하지 않습니다.</th>
						</tr>
						@endforelse
					</tbody>
				</table>
			</form>
		</div>
		@if($comments)
		{!! $comments->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}
	</div>
</div>

@endsection

@section('script')
<script>
	$(function(){
		$("#allCheck").click(function(){
			if($("#allCheck").prop("checked")) { //해당화면에 전체 checkbox들을 체크해준다 
				$("input[type=checkbox]").prop("checked",true); // 전체선택 체크박스가 해제된 경우 
			} else { //해당화면에 모든 checkbox들의 체크를해제시킨다. 
				$("input[type=checkbox]").prop("checked",false); 
			}
		});
	});
</script>
@endsection
