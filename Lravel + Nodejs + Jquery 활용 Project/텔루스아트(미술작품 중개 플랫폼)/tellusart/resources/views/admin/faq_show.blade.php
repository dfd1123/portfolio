@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">FAQ 관리</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
		FAQ 상세보기
	</div>
	<div class="card-body">
		<div class="table-responsive" style="width:1000px; margin:0 auto;">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<tbody>
					<tr>
						<th style="width:10%;height:90px;">질문</th>
					    <td>{{$faq->question}}</td>
					</tr>
					<tr>
					    <th style="width:10%;height:400px;">답변</th>
					    <td>{{$faq->answer}}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			<button type="button" onclick="location.href='{{url()->previous()}}'" class="org_btn">목록</button>
		</div>
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>
	
</script>

@endsection