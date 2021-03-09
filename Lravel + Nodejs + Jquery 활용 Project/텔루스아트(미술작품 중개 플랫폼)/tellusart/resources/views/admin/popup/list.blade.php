@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	팝업관리
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	팝업 리스트
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;"> 번호</th>
						<th style="width:40%;"> 제목</th>
						<th style="width:10%;"> 상태</th>
						<th style="width:10%;"> 작성자</th>
						<th style="width:10%;"> 작성일</th>
					</tr>
				</thead>
				<tbody>
					@forelse($popups as $popup)
					<tr>
						<td>{{$popup->id}}</td>
						<td><a href="{{route('admin.popup_edit', ['id' => $popup->id])}}">{{$popup->title}}</a></td>
						<td>
							@if(strtotime($popup->start_time) > time())
							예정
							@elseif(strtotime($popup->start_time) <= time() && time() <= strtotime($popup->end_time))
							진행
							@elseif(strtotime($popup->end_time) < time())
							종료
							@endif
						</td>
						<td>{{$popup->writer_name}}</td>
						<td>{{date("Y-m-d", strtotime($popup->created_at))}}</td>
					</tr>
					@empty
					<tr>
						<th colspan="5">팝업이 존재하지 않습니다</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.popup_create')}}'" style="margin: 0 auto; display: block;">팝업추가</button>
		</div>
		@if($popups)
		{!! $popups->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }} {{ __('notice.update')}}
	</div>
</div>



@endsection