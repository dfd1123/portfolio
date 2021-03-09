@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
<li class="breadcrumb-item active">현재 접속자</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
<!-- <div class="card-header">
	회원리스트</div> -->
<div class="card-body">
	<div class="table-responsive tsa-table-wrap">
		
		<table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
			<thead>
				<th>IP</th>
				<th>URL</th>
				<th>DATE</th>
				
			</thead>
			<tbody>
			@forelse($users as $user)
			<tr>
				<td>{{$user->ip}}</td>
				<td>{{$user->url}}</td>
                <td>{{$user->date}}</td>
			</tr>
			@empty
			<tr>
				<td colspan="8" >현재 접속중인 인원이 없습니다.</td>
			</tr>
			@endforelse
			</tbody>
		</table>
	</div>
</div>


<div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
</div>



@endsection

@section('script')

@endsection