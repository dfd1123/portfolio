@extends('admin.layouts.app')

@section('content')


<form id="admin_user_form"  method="post" action="{{route('admin.admin_user_store')}}">

	@csrf
  
  <div class="card mb-3">
       <div class="card-header">
              <i class="fas fa-table"></i>
              	관리자 추가</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered cate_adm_table" cellspacing="0" style="width:700px;">
                    <tr>
                    	<th style="width:150px;">이름</th>
	                    <td><input type="text" id="fullname" name="fullname" class="form-control" required="required" /></td>
                    </tr>
                    <tr>
                    	<th>전화번호</th>
	                    <td><input type="text" id="mobile_number" name="mobile_number" class="form-control" required="required" /></td>
                    </tr>
                    <tr>
                    	<th>보안등급</th>
	                    <td>
                            <select name="level">
                                @for($i=5;$i>=1;$i--)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<th>아이디(이메일)</th>
                      <td>
                        <input type="email" id="email" name="email" class="form-control" required="required" style="width:80%;display: inline-flex;"/>
                        <button type="button" id="email_certify_btn" class="btn btn-default">중복검사</button>
                        <input type="hidden" id="email_certify" name="email_certify" value="0" />
                      </td>
                    </tr>
                    <tr>
                    	<th>비밀번호</th>
	                    <td><input type="password" id="password" name="password" class="form-control" required="required" /></td>
                    </tr>
                    <tr>
                    	<th>비밀번호 확인</th>
                      <td>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" required="required" />
                        <h4 id="confirm-fail" style="color:#ff0000;font-size:15px;display:none">비밀번호 확인이 틀렸습니다</h4>
                      </td>
                    </tr>
                </table>
              </div>
				<button type="submit" class="btn btn-default">추가</button>
            </div>
        
          </div>
</form>

@endsection
@section('script')
<script>
  $('#email_certify_btn').click(function(){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var email = $('input[name="email"]').val();
		
		if(email == ''){
			alert('이메일 주소를 입력하여주세요');
		}else{
			if(!$(this).hasClass('active')){
				$.ajax({
			       url: '/admin/idconfirm',
			       type: 'POST',
			       /* send the csrf-token and the input to the controller */
			       data: {_token: CSRF_TOKEN, email:email},
			       dataType: 'JSON',
			       /* remind that 'data' is the response of the AjaxController */
			       success: function (data) { 
			       		if(data.exist){
			       			alert('이미 가입된 이메일 주소 입니다.');
			       			$('input[name="email"]').val('');
			       		}else{
			       			if(confirm('사용 가능한 이메일 주소입니다. 사용하시겠습니까?')){
			       				$('input[name="email"]').attr("readonly","readonly");
			       				$('#email_certify').val(1);
			       				$('#email_certify_btn').text('사용가능');
			       			}
			       		}     	
			       }
			   	});
			  }
			}
   });


$('#admin_user_form').submit(function(){
  if($('#email_certify').val() != 1){
			alert('이메일 중복검사를 해주세요.');
			$('#email_certify').focus();
			return false;
  }
  else if($('#password').val() != $('#password_confirm').val()){
    $('#confirm-fail').attr('style','color:#ff0000;font-size:15px;');
    return false;
  }
  else{
    return true;
  }
});
</script>
@endsection