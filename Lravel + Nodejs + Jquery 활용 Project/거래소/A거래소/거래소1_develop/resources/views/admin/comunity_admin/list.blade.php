@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	커뮤니티 관리자
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		커뮤니티 관리자
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th style="width:7%;">	커뮤니티명</th>
						<th style="width:7%;">	게시판 테이블명</th>
						<th style="width:5%;">	UID</th>
						<th style="width:5%;">	닉네임</th>
						<th style="width:5%;">	이름</th>
						<th style="width:5%;">  이메일(ID)</th>
						<th style="width:5%;">	휴대폰 번호</th>
						<th style="width:6%;">	등록자 UID</th>
						<th style="width:6%;">	등록자 이름</th>
						<th style="width:6%;">	생성날짜</th>
						<th style="width:6%;">	수정날짜</th>
						<th style="width:5%;">	상태</th>
						<th style="width:10%;">	설정</th>
					</tr>
				</thead>
				<tbody>
					@forelse($comunity_admins as $comunity_admin)
					<tr>
						<td>
							@if(empty($comunity_admin->bo_table))
							전체
							@else
							<a href="/comunity?board_name={{$comunity_admin->bo_table}}" target="_blank">{{$comunity_admin->bo_name}}</a>
							@endif
						</td>
						<td>
							{{$comunity_admin->bo_table ?? '-'}}
						</td>
						<td>
							{{$comunity_admin->uid}}
						</td>
						<td>
							{{$comunity_admin->nickname ?? '-'}}
						</td>
						<td>
							{{$comunity_admin->fullname ?? '-'}}
						</td>
						<td>
							{{$comunity_admin->email ?? '-'}}
						</td>
						<td>
							{{$comunity_admin->mobile_number ?? '-'}}
						</td>
						<td>
							{{$comunity_admin->assign_admin}}
						</td>
						<td>
							{{$comunity_admin->assign_admin_name}}
						</td>
						<td>{{date("Y-m-d", strtotime($comunity_admin->created_at))}}</td>
						<td>{{date("Y-m-d", strtotime($comunity_admin->updated_at))}}</td>
						<td>
							@if($comunity_admin->active == 1)
								<span style="color:blue;font-weight:bold;">사용중</span>
							@else
								<span style="color:red;font-weight:bold;">사용안함</span>
							@endif
						</td>
						<td>
							<a href="{{route('admin.comunity_admin_edit', $comunity_admin->id)}}" class="myButton edit">{{ __('setting.edit') }}</a>&nbsp
							<a href="{{route('admin.comunity_admin_delete', $comunity_admin->id)}}" class="link-delete myButton del" onclick="return confirm('{{ __('setting.setting_sentence_8') }}');">{{ __('setting.del') }}</a>
						</td>
					</tr>
					@empty
					<tr>
						<th colspan="13">커뮤니티 관리자가 존재하지 않습니다.</th>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" class="mint_btn" onclick="location.href='{{route('admin.comunity_admin_create')}}'">커뮤니티 관리자 추가</button>
		</div>
		@if($comunity_admins)
		{!! $comunity_admins->render() !!}
		@endif
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('event.update')}}
	</div>
</div>

@endsection

@section('script')

@endsection
