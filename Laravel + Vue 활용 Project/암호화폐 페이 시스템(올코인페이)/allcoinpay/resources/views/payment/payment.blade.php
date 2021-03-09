@extends('layouts.app')

@section('content')
<div class="container ex_padding ex_container pg_wrapper pg_info">
	
	<div class="row">
	
		<div class="col-md-12">
			
			<div class="panel panel-default box_style">
			
				<div class="panel-body"	id="ready_finish">
					
					<h3>결제정보</h3>
					
					<span class="ment">결제를 진행하기 위해, 원하시는 코인과 금액을 입력하세요.<br>화면에 보이는 총 결제 수량은 대략적인 현재시세로 산출한 개수입니다.</span>
					
					<form target="payment_proto_form" action="{{ route('payment_window') }}" method="POST" onsubmit="window.open('about:blank','payment_proto_form','fullscreen=1, left=0 toolbar=0, menubar=0, status=1');"> 
						@csrf
						
						<div class="line_align_pg">
							
							<select name="symbol" id="symbol" required class="line_align_pg_inner">
								<option value="">결제할 코인종류를 선택해 주세요.</option>
								@forelse($coins as $coin)
								<option value="{{ $coin->symbol }}">{{ $coin->name }}</option>
								@empty
								<option value="">결제 가능한 코인이 없습니다.</option>
								@endforelse
							</select>
							
						</div>
						
						<div class="line_align_pg">
							<input class="line_align_pg_inner" type = "number" placeholder="결제금액" id = "coin_price" name="cash_price" onkeyup="debounce_calcul_price();" required>
							<span class="unit">원</span>
						</div>
						
						<div class="line_align_pg line_align_total">
							
							<span class="tit">대략적인 결제 수량</span><input type ="text" id="coin_amt" name="coin_amt" class="line_align_pg_inner" readonly><span id="total_type" class="coin_name"></span> 
							
						</div>
						<input type = "hidden" name="quote" id="quote">
						<input type = "submit" class="line_align_pg pg_btn"  value = "결제하기">
					</form>
				</div>
				<div class="panel-body"	id="waiting_finish" style="display:none;">
					<h3>결제정보</h3>
				</div>
			</div>
			
		</div>	
		
	</div>
	
</div>
@endsection

@section('script')
	<script type= "text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js"></script>
	<script>
		var debounce = null;
		
		function debounce_calcul_price() {
			if(debounce === null) {
				debounce = _.debounce(calcul_price, 250);
			}
			debounce();
		}

		$('#symbol').on('change',function(){
			$("#coin_amt").val(0);
			$("#quote").val(0);
			$("#coin_price").val(0);
			$("#total_type").text($("option:selected",this).val());
			
		});
		
		function calcul_price(){
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			var symbol = $("#symbol option:selected").val();
			if(symbol != ''){
				var price = $("#coin_price").val();
				if(price === '') {
					price = 0;
				}
				
				var total_amt;
				$.ajax({
					url : "/api/call_orderbook",
					type : "POST",
					data : {_token:CSRF_TOKEN, coin:symbol, type:'buy', amount:price },
					dataType : "JSON"
				}).done(function(data) {
					$("#coin_amt").val(data.orders[0].qty);
					$("#quote").val(data.orders[0].price);
					$("#total_type").text(" "+symbol);
					
				}).fail(function(){
					console.log("fail");
				});
			}else{
				alert("이용할 코인을 선택해 주세요.");
				$("#coin_price").val(0);
			}
		}
		
	</script>
@endsection