@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
<li class="breadcrumb-item active">회원 로그인 이력</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
<div class="card-body">
	<div class="usr_search_box tsa-sch-box">
		<form method="get" action="">
			<select name="keyword_srch">
				<option value="all" {{$keyword_srch == 'all' ? 'selected' : ''}}>전체</option>
				<option value="uid" {{$keyword_srch == 'uid' ? 'selected' : ''}}>UID</option>
				<option value="fullname" {{$keyword_srch == 'fullname' ? 'selected' : ''}}>사용자</option>
				<option value="email" {{$keyword_srch == 'email' ? 'selected' : ''}}>Email</option>
				<option value="mobile" {{$keyword_srch == 'mobile' ? 'selected' : ''}}>핸드폰 번호</option>
			</select>
			<input type="text" name="keyword" />
			<button type="submit">{{ __('user.search') }}</button>
		</form>
	</div>
	<div class="table-responsive tsa-table-wrap">
	<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
		<thead>
			<th>시간</th>
			<th>UID</th>
			<th>사용자</th>
			<th>이메일</th>
			<th>핸드폰 번호</th>
			<th>IP</th>
			<th>장치</th>
			<th>장치 분류</th>
			<th>브라우저</th>
			<th>OS</th>
			<th>언어</th>
			<th>위치</th>
		</thead>
		<tbody>
		@forelse($traces as $trace)
		<tr>
			<td>{{$trace->time}}</td>
			<td>{{$trace->uid}}</td>
			<td>{{$trace->fullname}}</td>
			<td>{{$trace->email}}</td>
			<td>{{$trace->mobile_number}}</td>
			<td>{{$trace->ip}}</td>
			<td>{{$trace->device}}</td>
			<td>{{$trace->device_kind}}</td>
			<td>{{$trace->browser}}</td>
			<td>{{$trace->os}}</td>
			<td>{{$trace->lang}}</td>
			<td>{{$trace->location}}</td>
		</tr>
		@empty
		<tr>
			<td colspan="12" >회원 로그인 이력이 없습니다</td>
		</tr>
		@endforelse
		</tbody>
	</table>
	</div>
	@if($traces)
		{!! $traces->render() !!}
	@endif
</div>
<div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
@endsection

@section('script')
<script>
</script>
@endsection