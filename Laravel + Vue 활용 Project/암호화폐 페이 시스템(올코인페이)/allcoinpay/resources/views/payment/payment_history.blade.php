@extends('layouts.app')

@section('content')
<div class="panel-heading">
		
	<form action="" method="POST" name="search_field">
		@csrf
		<div class="sch_wrap_group">
			
			<div class="sch_wrap">
		
				<input type="text" class="input_search" name="qry" value="">
				
				<input type="submit" value="검색">
				
				<select>
					
					<option>-</option>
					<option>상태</option>
					<option>주문자 ID</option>
					<option>거래번호</option>
					
				</select>
				
			</div>
		
			<!--button type="button">3일이내 거래건 다시 체크</button-->
			
		</div>
		
	</form>
	
</div>
<div class="panel panel-default box_style panel_box">
	<div class="panel-body">
	<h3>결제현황</h3> 
	(정산이 완료된 경우 환불이 불가합니다.)
		<table class="table table-striped">
			<thead>
				<tr>
					<th >생성된 시간</th>
					<th >결제된 시간</th>
					<th >주문 번호</th>
					<th >요청한 가격</th>
					<th >코인종류</th>
					<th >결제된 코인 양</th>
					<th >구매자 이름</th>
					<th >구매자 코인주소</th>
					<th >판매자 코인주소</th>
					<th >상태</th>
					<th >동작</th>
				</tr>
			</thead>
			<tbody>
				@forelse($historys as $history)
				<tr>
					<td>{{ date("Y-m-d H:i:s",strtotime($history->created_dt.' + 9 HOURS')) }}</td>
					<td>{{ date("Y-m-d H:i:s",strtotime($history->updated_dt.' + 9 HOURS')) }}</td>
					<td>{{ str_pad($history->id, 10, '0', STR_PAD_LEFT) }}</td>
					<td>{{ $history->cash_price }}원</td>
					<td>{{ $history->cointype }}</td>
					<td>{{ $history->coin_amt }}</td>
					<td>{{ $history->buyer_fullname }}</td>
					<td>{{ $history->buyer_address }}</td>
					<td>{{ $history->address }}</td>
					<td>{{ $history->status }}</td>
					<td>
					@if($history->status == 'complete')
					<button type = "button" class="payment_refund" data-id="{{ $history->id }}" style="padding: 0 10px;background: #90afe4;border: 0;color: #fff;border-radius: 5px;">환불</button>	
					@else
					-
					@endif
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="10">내역이 없습니다.</td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
	

@endsection

@section('script')
<script>
	var check_ajax = true;
	$('.payment_refund').on('click',function(){
		if(check_ajax){
			check_ajax = false;
			var check = confirm('정말 환불하시겠습니까?');
			if(check){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var id = $(this).data('id');
				$.ajax({
					url : "/api/payment_refund",
					type : "POST",
					data : {_token:CSRF_TOKEN, id : id},
					dataType : "JSON"
				}).done(function(data) {
					if(data.status == 'OK'){
						alert('환불요청이 수락되었습니다.');
						location.reload();
					}else if(data.status == 'error'){
						alert('환불요청이 실패하였습니다. 잠시 후 다시 시도해 주세요.');
					}
					
				}).error(function(){
					console.log("error");
				});
				
			}
		}
	});
	
</script>
@endsection