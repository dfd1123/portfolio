@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	커뮤니티 관리
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	커뮤니티 관리
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:5%;">	커뮤니티명</th>
						<th style="width:5%;">	게시판 테이블명</th>
						<th style="width:5%;">	게시글 수</th>
						<th style="width:5%;">	코인종류</th>
						<th style="width:5%;">	코인심볼</th>
						<th style="width:5%;">	생성날짜</th>
						<th style="width:5%;">	수정날짜</th>
						<th style="width:5%;">	사용여부</th>
						<th style="width:5%;">	설정</th>
					</tr>
				</thead>
				<tbody>
					@forelse($comunity_boards as $comunity_board)
					<tr>
						<td>
                            <a href="/comunity?board_name={{$comunity_board->bo_table}}" target="_blank">{{$comunity_board->bo_name}}</a>
						</td>
						<td>
                            {{$comunity_board->bo_table}}
						</td>
						<td>{{$comunity_board->post_cnt}}</td>
						<td>
                           {{$comunity_board->coin_type}}
                        </td>
						<td>
                           {{$comunity_board->coin_symbol}}
                        </td>
						<td>{{date("Y-m-d", strtotime($comunity_board->created_at))}}</td>
						<td>{{date("Y-m-d", strtotime($comunity_board->updated_at))}}</td>
						<td>
							@if($comunity_board->status == 1)
								<span style="color:blue;font-weight:bold;">사용중</span>
							@else
								<span style="color:red;font-weight:bold;">사용안함</span>
							@endif
						</td>
						<td>
							<button type="button" onclick="location.href='{{route('admin.comunity_manage_edit', $comunity_board->bo_table)}}'">수정</button>
						</td>
					</tr>
					@empty
					<tr>
						<th colspan="9">{{ __('event.nohere')}}</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.comunity_manage_create')}}'">커뮤니티 추가</button>
		</div>
		@if($comunity_boards)
		{!! $comunity_boards->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('event.update')}}
	</div>
</div>

@endsection

@section('script')

@endsection