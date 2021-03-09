@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	N번째 이벤트 현황
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
		금일 당첨 현황
	</div>
	<div class="card-body">
			@csrf
			<div class="table-responsive tsa-event-table" style="width:85%" id="n_event_reload">
				<table id="abc"class="table table-bordered cate_adm_table"  cellspacing="0">
					<tbody >
						<tr >
                            <div id="ok"></div>
							<th style="width:25%">이벤트 조건 현재 거래 번호</th>
						</tr>
                        <tr>
							@if($max_trades_id > 0)
                            <td>{{ $max_trades_id }}</td>
							@else
							<td> 현재 거래 없음</td>
							@endif
                        </tr>
				</table>

                <table id="abc"class="table table-bordered cate_adm_table"  cellspacing="0">
					<tbody >
						<tr >
                            <div id="ok"></div>
                            <th style="width:25%">금일의 잔여 당첨번호</th>
                            <th style="width:25%">당첨 여부</th>
						</tr>  
                        @if(count($json_data_array_new) > 0)
							@foreach($json_data_array_new as $data)
								<tr>  
									@if(!$data['status'])
									<td>
										{{ $data['number'] }}
									</td>
									<td>
										{{ $data['status'] ? '당첨' : '미당첨' }}
									</td>
									@endif
								</tr>
							@endforeach
						@else
							<tr>  
								<td colspan="2">
									이벤트 시행 안하고 있음.
								</td>
							</tr>
                        @endif
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="button" class="btn btn-default mint_btn" onclick="window.location.reload()">
				    새로고침
				</button>
			</div>
	</div>
</div>

@endsection

@section('script')
<script>
     $(document).ready(
            function() {
                setInterval(function() {
                    window.location.reload();
                }, 5000);
            }); 
    

</script>
@endsection
