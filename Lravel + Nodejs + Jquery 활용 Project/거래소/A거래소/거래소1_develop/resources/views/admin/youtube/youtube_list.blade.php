@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	유튜브 관리
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	유튜브 목록
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:10%;">	목록 명칭</th>
						<th style="width:10%;">	제목1<br>제목2</th>
						<th style="width:10%;">	부제</th>
						<th style="width:10%;">	Contents A<br>Contents B<br>Contents C</th>
						<th style="width:20%;">	YoutubeID</th>
						<th style="width:10%;">	등록날짜</th>
						<th style="width:20%;">	동작</th>
					</tr>
				</thead>
				<tbody>
					@forelse($youtubes as $youtube)
                    <tr>
						<td>{{ $youtube->sub_text }}</td>
						<td>{{ $youtube->title2 }}<br>{{ $youtube->title }}</td>
						<td>{{ $youtube->sub_title }}</td>
						<td>{{ $youtube->contents_a }}<br>{{ $youtube->contents_b }}<br>{{ $youtube->contents_c }}</td>
                        <td>{{ $youtube->url }}  <a href="https://www.youtube.com/watch?v={{ $youtube->url }}">영상 보러가기</a></td>
                        <td>{{ $youtube->created_at }}</td>
                        <td>
                            @if($youtube->active == 0)
                            <button class="mint_btn" onclick="location.href='{{route('admin.youtube_active', $youtube->id)}}';">이 영상을 메인으로 변경</button>
							@else
							현재 이영상이 메인<br>
							@endif
                            <button class="myButton edit" onclick="location.href='{{route('admin.youtube_edit', $youtube->id)}}';">편집</button>
                            <button class="link-delete myButton del" onclick="location.href='{{route('admin.youtube_delete', $youtube->id)}}';">삭제</button>
                        </td>
                    </tr>
					@empty
					<tr>
						<th colspan="9">등록된 유튜브가 존재하지 않습니다.</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if(Auth::guard('admin')->user()->level <= 4)
			<div>
				<button type="button" class="mint_btn" onclick="location.href='{{route('admin.youtube_create')}}'">유튜브추가</button>
			</div>
		@endif
		@if($youtubes)
		{!! $youtubes->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('event.update')}}
	</div>
</div>

@endsection

@section('script')

@endsection