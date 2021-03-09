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
		<ul class="nav nav-tabs">
			@if($state == 0)
			    <li class="active"><a href="{{route('admin.event_list',0)}}">전체</a></li>
			    <li><a href="{{route('admin.event_list',1)}}">예정</a></li>
			    <li><a href="{{route('admin.event_list',2)}}">진행</a></li>
			    <li><a href="{{route('admin.event_list',3)}}">종료</a></li>
			@elseif($state == 1)
			    <li><a href="{{route('admin.event_list',0)}}">전체</a></li>
			    <li class="active"><a href="{{route('admin.event_list',1)}}">예정</a></li>
			    <li><a href="{{route('admin.event_list',2)}}">진행</a></li>
			    <li><a href="{{route('admin.event_list',3)}}">종료</a></li>
			@elseif($state == 2)
			    <li><a href="{{route('admin.event_list',0)}}">전체</a></li>
			    <li><a href="{{route('admin.event_list',1)}}">예정</a></li>
			    <li class="active"><a href="{{route('admin.event_list',2)}}">진행</a></li>
			    <li><a href="{{route('admin.event_list',3)}}">종료</a></li>
			@elseif($state == 3)
			    <li><a href="{{route('admin.event_list',0)}}">전체</a></li>
			    <li><a href="{{route('admin.event_list',1)}}">예정</a></li>
			    <li><a href="{{route('admin.event_list',2)}}">진행</a></li>
			    <li class="active"><a href="{{route('admin.event_list',3)}}">종료</a></li>
			@endif
		</ul>
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered event_list_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>이벤트번호</th>
						<th>이벤트명</th>
						<th>이벤트기간</th>
						<th>상태</th>
						<th>등록일</th>
						<th>조회수</th>
						<th>추천수</th>
						<th>설정</th>
					</tr>
				</thead>
				<tbody>
					@forelse($events as $event)
				    <tr>
						<td>{{$event->id}}</td>
						<td><a class="a-st-2" href="{{route('admin.event_show', $event->id)}}">{{$event->title}}</a></td>
						<td>{{explode(' ', $event->start_time)[0]}} ~ {{explode(' ', $event->end_time)[0]}}</td>
						@if($today < $event->start_time)
							<td class="eve-1">예정</td>
						@elseif($today <= $event->end_time)
							<td class="evt-2">진행</td>
						@elseif($today > $event->end_time)
						    <td class="evt-3">종료</td>
						@endif
						<td>{{explode(' ', $event->created_at)[0]}}</td>
						<td>{{$event->hit}}</td>
						<td>{{$event->like}}</td>
						<td><a href="{{route('admin.event_edit', $event->id)}}" class="myButton edit">편집</a> <a href="{{route('admin.event_delete', $event->id)}}" class="link-delete myButton del">삭제</a></td>
					</tr>
					@empty
					<tr>
						<th colspan="7">이벤트가 존재하지 않습니다.</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="org_btn" onclick="location.href='{{route('admin.event_create')}}'">추가</button>
		</div>
		@if($events_page)
		{!! $events_page->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>

$('.link-delete').click(function(e){
	if(!confirm("이벤트를 삭제하시겠습니까?")){
		e.preventDefault();
	}
});

</script>

@endsection