@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">이벤트관리</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		이벤트상세보기
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered event_show_adm_table" width="100%" cellspacing="0">
				<tbody>
					<tr>
						<td>이벤트명</td>
					    <td colspan="5">{{$event->title}}</td>
					</tr>
					<tr>
					    <td>이벤트기간</td>
					    <td>{{explode(' ', $event->start_time)[0]}} ~ {{explode(' ', $event->end_time)[0]}}</td>
					    <td>등록날짜</td>
					    <td>{{explode(' ', $event->created_at)[0]}}</td>
					    <td>작성자</td>
					    <td>관리자</td>
					</tr>
					<tr>
					    <td>상태</td>
					    @if($today < $event->start_time)
					        <td>예정</td>
					    @elseif(explode(' ', $today)[0] <= explode(' ', $event->end_time)[0])
					        <td>진행</td>
					    @elseif($today > $event->end_time)
					        <td>종료</td>
					    @endif
					    <td>조회수</td>
					    <td>{{$event->hit}}</td>
					    <td>추천수</td>
					    <td>{{$event->like}}</td>				
					</tr>
					<tr>
					    <td colspan="8">{!! $event->body !!}</td>
					</tr>
					<tr>
						<td>첨부파일</td>
						<td>
							@if($event->file1 == NULL)
							    (첨부파일없음)
							@else
								<a href="/storage/event/{{$event->file1}}" target="_blank">Download</a>
							@endif				
						</td>
						<td>PC배너</td>
						<td>
							@if($event->pc_banner == NULL)
								(첨부이미지없음)
							@else
								<img src="{{asset('/storage/event/'.$event->pc_banner)}}" style="width:100px;" title="banner">
							@endif
						</td>
						<td>모바일배너</td>
						<td colspan="3">
							@if($event->mobile_banner == NULL)
								(첨부이미지없음)
							@else
								<img src="{{asset('/storage/event/'.$event->mobile_banner)}}" style="width:100px;" title="banner">
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" onclick="location.href='{{route('admin.event_list', 0)}}'" class="org_btn">목록</button>
		</div>
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>
	
</script>

@endsection