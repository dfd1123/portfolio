@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
<li class="breadcrumb-item active">ICO 문의</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
<!-- <div class="card-header">
	회원리스트</div> -->
<div class="card-body">

	<div class="table-responsive tsa-table-wrap">

		<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
			<thead>
				<th>번호</th>
				<th>문의자명</th>
				<th>문의자 이메일</th>
				<th>문의 날짜</th>
			</thead>
			<tbody>
			@forelse($qnas as $qna)
			<tr onclick="location.href='/admin/ico_qna_view/{{$qna->id}}'">
				<td>{{$qna->id}}</td>
				<td>{{$qna->name}}</td>
        <td>{{ $qna->email }}</td>
        <td>{{ date("Y-m-d", strtotime($qna->created_at)) }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="4" >등록된 문의가 없습니다.</td>
			</tr>
			@endforelse
			</tbody>
		</table>
	</div>
	@if($qnas)
		{!! $qnas->render() !!}
	@endif
</div>
@endsection
