@extends('admin.layouts.app')

@section('content')

<style>
	#n_event_select_start_date::-webkit-inner-spin-button {
			display: none;
			-webkit-appearance: none;
	}
	#n_event_select_end_date::-webkit-inner-spin-button {
			display: none;
			-webkit-appearance: none;
	}
	.n_page_nation{
		text-align:center;
		margin-bottom:10px;
	}
</style>
<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	N번째 이벤트 당첨자 조회
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
		당첨 현황
	</div>
	<div class="card-body">
			@csrf
			<div class="table-responsive tsa-event-table" style="width:85%" id="n_event_reload">


			<input type="date" id="n_event_select_start_date" > ~ 
			<input type="date" id="n_event_select_end_date" > 
			
			<button type="button" id="n_event_search_date_btn" class="btn btn-default" >조회하기</button>
				<table class="table table-bordered cate_adm_table"  cellspacing="0">
					<tbody>
						<tr >
                            <div id="ok"></div>
							<th style="width:25%">당첨 현황</th>
						</tr>
					</tbody>
				</table>
				
				<table  class="table table-bordered cate_adm_table"  cellspacing="0">
					<tbody id="winners_list_tbody">
                        <tr>
							<th>당첨 날짜</th>
							<th>이메일</th>
							<th>이름</th>
							<th>휴대전화</th>
							<th>당첨번호</th>							
							<th>닉네임</th>
							<th>상품명</th>
                        </tr>
						@foreach($winners_list as $winner_list)
						<tr>
							<td>{{$winner_list->c_day}}</td>
							<td>{{$winner_list->email}}</td>
							<td>{{$winner_list->fullname}}</td>
							<td>{{$winner_list->mobile_number}}</td>
							<td>{{$winner_list->num}}</td>							
							<td>{{$winner_list->nickname}}</td>
							<td>{{$winner_list->proname}}</td>
						</tr>
						@endforeach
				</table>
			</div>
			<!-- <div class="n_page_nation">
				<a><<</a>&nbsp;&nbsp;<a><</a>&nbsp;&nbsp;<a>1</a>&nbsp;&nbsp;<a>></a>&nbsp;&nbsp;<a>>></a>
			</div> -->
			<div class="mint_btn_group">
				<button type="button" class="btn btn-default mint_btn" onclick="window.location.reload()">
				    새로고침
				</button>
			</div>
	</div>
</div>
<table class="tbl paginated" id="tbl">
</table>

@endsection

@section('script')

<script>

	$('#n_event_search_date_btn').click( function (){
		var start_date = $('#n_event_select_start_date').val();
		var end_date = $('#n_event_select_end_date').val();
		$.ajax( {
					url: '/admin/n_event/winners_list_refresh',
					type: 'POST',
					data : { _token : CSRF_TOKEN, start_date: start_date, end_date: end_date },
					dataType: "json",
					success: function (data) {
						$('#winners_list_tbody tr').remove();
						$('#winners_list_tbody').append('<tr><th>당첨 날짜</th><th>이메일</th><th>이름</th><th>휴대전화</th><th>당첨번호</th><th>닉네임</th><th>상품명</th></tr>');
						if(data.length>0){
						 	for(i=0;i<data.length;i++){
								var c_day = data[i].c_day;
								var email = data[i].email;
								var num = data[i].num;
								var nickname = data[i].nickname;
								var proname = data[i].proname;
								var mobile_number = data[i].mobile_number;
								var fullname = data[i].fullname;
								var proname = data[i].proname;
								$('#winners_list_tbody').append('<tr><td>'+c_day+'</td><td>'+email+'</td><td>'+fullname+'</td><td>'
																+mobile_number+'</td><td>'+num+'</td><td>'+nickname+'</td><td>'+proname+'</td></tr>');
							 }
						}
						else{
							$('#winners_list_tbody').append('<tr><td colspan="7" style="text-align:center;">선택하신 날짜에 해당하는 당첨자가 없습니다.</td></tr>');
						}	
					},
				} );
			});
</script>
@endsection