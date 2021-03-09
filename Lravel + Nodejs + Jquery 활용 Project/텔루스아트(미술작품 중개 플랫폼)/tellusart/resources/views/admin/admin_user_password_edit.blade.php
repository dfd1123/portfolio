@extends('admin.layouts.app')

@section('content')


<form id="admin_password_edit_form"  method="post" action="{{route('admin.admin_user_password_change', $id)}}">

	@csrf
  <div class="card mb-3">
       <div class="card-header">
              <i class="fas fa-table"></i>
              	관리자 비밀번호 변경</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered cate_adm_table" cellspacing="0" style="width:700px;">
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
				<button type="submit" class="btn btn-default">변경</button>
            </div>
        
          </div>
</form>

@endsection
@section('script')
<script>
$('#admin_password_edit_form').submit(function(){
  if($('#password').val() != $('#password_confirm').val()){
    $('#confirm-fail').attr('style','color:#ff0000;font-size:15px;');
    return false;
  }
  else{
    return true;
  }
});
</script>
@endsection