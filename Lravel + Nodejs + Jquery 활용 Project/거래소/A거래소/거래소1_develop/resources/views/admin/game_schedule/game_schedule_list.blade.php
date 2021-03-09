@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	경기일정 관리
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	경기일정 리스트
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;"> 번호</th>
            <th style="width:35%;"> 경기날짜</th>
            <th style="width:15%;"> 경기수</th>
						<th style="width:10%;"> 작성날짜</th>
            <th style="width:10%;"> 수정날짜</th>
            <th style="width:10%;"> 비고</th>
					</tr>
				</thead>
				<tbody>
					@forelse($game_schedules as $game_schedule)
					<tr>
						<td>{{$game_schedule->id}}</td>
            <td><a href="{{route('admin.game_schedule_edit', $game_schedule->id)}}">{{date("Y-m-d", strtotime($game_schedule->date))}}</a></td>
            <td>{{count(json_decode($game_schedule->schedule_lists ?? '[]', true))}}</td>
						<td>{{date("Y-m-d", strtotime($game_schedule->created_at))}}</td>
            <td>{{date("Y-m-d", strtotime($game_schedule->updated_at))}}</td>
            <td>
              <button type="button" class="delete_game_schedule" data-id="{{$game_schedule->id}}">삭제</button>
            </td>
					</tr>
					@empty
					<tr>
						<th colspan="7"> 등록된 경기 일정이 없습니다.</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.game_schedule_create')}}'">추가</button>
    </div>

    @if(isset($game_schedule))
    <form action="{{route('admin.game_schedule_delete', $game_schedule->id)}}" method="post" id="game_schedule_delete">
      @csrf
      <input type="hidden" name="id" id="game_schedule_id">
    </form>
    @endif

		@if($game_schedules)
		{!! $game_schedules->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }} {{ __('notice.update')}}
	</div>
</div>

<script>
  $('.delete_game_schedule').on('click', function(){
    const id = $(this).data('id');
    $('#game_schedule_id').val(id);
    if(confirm('정말 이 경기 일정을 삭제하시겠습니까?')){
      $('#game_schedule_delete').submit();
    }
  })
</script>

@endsection
