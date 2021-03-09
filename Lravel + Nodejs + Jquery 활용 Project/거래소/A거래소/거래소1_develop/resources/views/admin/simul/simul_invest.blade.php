@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('coin.newcoin')}}
	</li>
</ol>

<!-- DataTables -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
	모의투자대회 순위표
	</div>
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
                        <th>순위</th>
                        <th>이름</th>
                        <th>이메일</th>
                        <th>총수익</th>
                        <th>총수익률</th>
					</tr>
				</thead>
				<tbody>
                    @foreach($invests as $key => $invest)
                    <tr>
                        <th>{{ $key + 1 + (($page - 1) * 30) }}</th>
                        <th>{{ $invest->fullname }}</th>
                        <th>{{ $invest->email }}</th>
                        <th>{{ number_format($invest->eval_total,0) }}원</th>
                        <th>{{ number_format($invest->eval_per,2) }}%</th>
                    </tr>
                    @endforeach
                    
					
				</tbody>
            </table>
            @if($invests)
                {!! $invests->render() !!}
            @endif
		</div>
	</div>
</div>

@endsection

@section('script')

@endsection