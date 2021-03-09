@extends('layouts.app')
@section('content')
			
<div class="panel panel-default box_style notice_panel">
	<div class="panel-body">
		<h3>공지사항</h3>
		<div class="row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>번호</th>		
						<th>제목</th>		
						<th>작성자</th>	
						<th>날짜</th>		
					</tr>
				</thead>
				<tbody>
				@forelse($notices as $notice)
					<tr>
						<td>{{$notice->id}}</td>
						<td><a href="{{ route('notice_show',$notice->id)}} ">{{$notice->title}}</a></td>
						<td>관리자</td>
						<td>{{date("Y-m-d", $notice->created)}}</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>공지사항이 존재하지 않습니다.</td>
					</tr>
				@endforelse
				</tbody>
			</table>	
			{!! $notices->render() !!}
		</div>
	</div>
</div>
		
	

@endsection

@section('script')
	<script>
	
		
	</script>
@endsection