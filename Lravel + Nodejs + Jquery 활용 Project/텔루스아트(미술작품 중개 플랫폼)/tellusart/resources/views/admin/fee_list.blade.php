@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">수수료 관리</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-body">
		<div class="table-responsive tsa-table-wrap">
			<table class="table table-bordered banner_list_adm_table" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>상품정산 수수료(%)</th>
						<th>출금 수수료(고정)</th>
						<th>수수료 받는 계정</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="text" name="product_fee" value="{{ $fee->product_fee }}" class="form-control tsa-input-st" style="width:95%;float:left;" />%</td>		
						<td><input type="text" name="withdraw_fee" value="{{ $fee->withdraw_fee }}" class="form-control tsa-input-st" style="width:95%;float:left;" />TLG</td>
						<td><input type="text" name="fee_email" value="{{ $fee->fee_email }}" class="form-control tsa-input-st"/></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
	<div class="card-footer small text-muted">{{ $datetime }}에 업데이트된 정보입니다.</div>
</div>

@endsection

@section('script')

<script>

$("input[name='product_fee']").change(function(){
	var product_fee = $(this).val();
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm("정말 상품 수수료를 '"+product_fee+"'로 변경하시겠습니까?")){
		$.ajax({
            url: '/fee/product/change',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, product_fee: product_fee},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
            	alert('상품 수수료 변경완료!'); 
    		}
        }); 
	}
});

$("input[name='withdraw_fee']").change(function(){
	var withdraw_fee = $(this).val();
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm("정말 출금 수수료를 '"+withdraw_fee+"'로 변경하시겠습니까?")){
		$.ajax({
            url: '/fee/withdraw/change',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, withdraw_fee: withdraw_fee},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
            	alert('출금 수수료 변경완료!'); 
    		}
        }); 
	}
});

$("input[name='fee_email']").change(function(){
	var fee_email = $(this).val();
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	if(confirm("정말 수수료 받는 계정을 '"+fee_email+"'로 변경하시겠습니까?")){
		$.ajax({
            url: '/fee/email/change',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, fee_email: fee_email},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
            	if(data == 1){
            		
            		alert('수수료 받는 계정 변경완료!');
            	}else{
            		$("input[name='fee_email']").val('{{ $fee->fee_email }}');
            		alert('회원가입 되지 않은 이메일 계정입니다.');
            	}
    		}
        }); 
	}
});



</script>

@endsection