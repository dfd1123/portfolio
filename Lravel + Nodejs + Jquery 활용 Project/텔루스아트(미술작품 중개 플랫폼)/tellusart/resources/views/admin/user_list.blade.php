@extends('admin.layouts.app')

@section('content')



<!-- Breadcrumbs-->
          <ol class="breadcrumb tsa-top-tit">
            <li class="breadcrumb-item active">회원 관리</li>
          </ol>

          <!-- DataTables Example -->
          <div class="card mb-3 tsa-card">
            <!-- <div class="card-header">
              	회원리스트</div> -->
            <div class="card-body">
	            <div class="usr_search_box tsa-sch-box">
	            	<form method="get" action="{{route('admin.user_list')}}">
	            		@csrf
			            	<select name="keyword_srch">
			            		<option value="name" {{($keyword_srch == 'name')?'selected=selected':''}}>이름</option>
			            		<option value="id" {{($keyword_srch == 'name')?'selected=selected':''}}>아이디</option>
			            		<option value="nickname" {{($keyword_srch == 'name')?'selected=selected':''}}>닉네임</option>
			            		<option value="mobile" {{($keyword_srch == 'name')?'selected=selected':''}}>휴대전화 뒤 4자리</option>
			            	</select>
			            	<input type="text" name="keyword" value="{{$keyword}}" />
			            	<button type="submit">검색</button>
		            </form>
	            </div>
              <div class="table-responsive tsa-table-wrap">
                <table class="table table-bordered tlc_usertbl" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
	                    <th rowspan="2" class="tsa-label-st" style="width:5%;">프로필</th>
	                    <th class="tsa-label-st" style="width:10%;">이름(닉네임)</th>
	                    <th class="tsa-label-st" style="width:30%;">주소</th>
	                    <th class="tsa-label-st" style="width:10%;">회원종류</th>
	                    <th class="tsa-label-st" rowspan="2" style="width:10%;">보유TLG</th>
                      <th rowspan="2" class="tsa-label-st" style="width:10%;" >탈퇴</th>
                    </tr>
                    <tr>
                    	<th>이메일(ID)</th>
                    	<th>전화번호</th>
                    	<th>가입날짜</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse($users_page as $user)
                    <tr id="userh_{{$user->id}}">
                      <td rowspan="2"><img src="{{asset('storage//image/'.$user->profile_img)}}" style="width:100px;height:100px;" alt="test" /></td>
                      <td>{{$user->name}}({{$user->nickname}})<a href="{{route('admin.user_delete',$user->id)}}" class="myButton xbtn hide" onclick="return confirm('정말 {{$user->name}} 회원님을 삭제하시겠습니까?');">회원삭제</a></td>
                      <td>{{$user->addr1.$user->extra_addr.$user->addr1addr2}}</td>
                      <td>
                      <select class="tsa-select" name="user_level">
                      	  @if(($user->level)==1)
	                      	<option value="1" selected="selected">일반회원</option>
	                      	<option value="2">전문평론가</option>
	                      @elseif(($user->level)==2)
	                      	<option value="1">일반회원</option>
	                      	<option value="2" selected="selected">전문평론가</option>
	                      @endif
                      </select>
                      </td>
                      <td rowspan="2">
                        <button type="button" data-id="{{$user->id}}" class="add_balance_confirm myButton edit">잔액관리</button>
                      </td>
                      <td rowspan="2">
                        <button type="button" data-id="{{$user->id}}" class="secession myButton">탈퇴</button>
                      </td>
                    </tr>
                    <tr id="userf_{{$user->id}}">
                    	<td>{{$user->email}}</td>
                    	<td>{{$user->mobile_number}}</td>
                    	<td>{{$user->created_at}}</td>
                    </tr>
                    @empty
                    <tr>
                    	<td colspan="5" >회원이 존재하지 않습니다.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
	            @if($users_page)
					{!! $users_page->render() !!}
				@endif
            </div>
            <div class="card-footer small text-muted">{{ $datetime }} 에 업데이트된 정보입니다.</div>
          </div>

<div id="add_balance_edit_wrap" class="jw_modal_wrap hidden">
  <div class="jw_overlay"></div>
  <div class="jw_modal_content_wrap">
    <div class="jw_modal_content">
      <div class="jw_modal_hd">
        <h5>잔액관리</h5>
      </div>
      <div class="jw_modal_bd">
        <div class="content_box">
          <div class="section_div" style="padding: 20px 0; border-bottom: 1px solid #dddddd;">
              <h5>TLG</h5>
              <label class="tsa-label-st">보유잔액</label>
              <div class="mb-2">
                <input type="text" name="available_balance_tlc" readonly="readonly" class="form-control tsa-input-st"/>
              </div>
					</div>

          <div class="section_div" style="padding: 20px 0; border-bottom: 1px solid #dddddd;">
            <label class="tsa-label-st">내역</label>
            <div>
              <input class="form-control tsa-input-st" type="text" name="reason" id="add_balance_reason" />
            </div>
          </div>
          <label class="tsa-label-st mt-3">추가할 금액</label>
          <div>
            <input class="form-control tsa-input-st" type="number" step="any" name="amount" id="add_balance_amount" required />
          </div>
          <button id="add_balance_save" class="btn btn-default mint_btn mt-3">추가하기</button>
        </div>
      </div>
      <div class="jw_modal_ft">
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')

<script>
	$("select[name='user_level']").change(function(){
		var level = $(this).val();
		var id = $(this).parent().parent().attr('id');
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		id = id.replace('userh_','');
		
		if(confirm('정말 해당 회원의 레벨을 수정하시겠습니까?')){
			$.ajax({
                    url: '/user/level/change',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, level: level, id:id},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                    alert('회원레벨 수정완료!'); 
                }
            }); 
		}
  });

  @if(Auth::guard('admin')->user()->level <= 3)
  
  $('#add_balance_save').click(function(e){
		var cointype = $('#cointype').val();
		var reason = $('#add_balance_reason').val();
		var amount_input =  $('#add_balance_amount').val();
		var button = $(e.currentTarget);
		var id = button.data('id');
    
		if(reason === '') {
			alert('내역을 입력해야 합니다.');
			return;
		}

		if(amount_input === '') {
			alert('추가할 금액을 입력해야 합니다.');
			return;
		}

		var amount = parseFloat(amount_input);
		if(amount == NaN) {
			alert('잘못된 금액을 입력하셨습니다.');
			return false;
		}

		if(amount == 0) {
			alert('추가할 금액이 0 입니다.');
			return false;
		}

		if(confirm('정말 해당금액을 추가하시겠습니까?')){
			button.attr('disabled', true);
			$.ajax({
				url : "/balance/add_user",
				type : "POST",
				data : {
					_token : CSRF_TOKEN,
					id : id,
					reason : reason,
					amount : amount_input
				},
				dataType : "JSON",
				success : function(data) {
					if(data.status === 1) {
            button.attr('disabled', false);
            refresh_balance_modal(id);
            $('#add_balance_reason').val('');
            $('#add_balance_amount').val('');
						alert('해당 금액이 추가되었습니다.');
					}
				}
			});
		}
  });

  $('.secession').on('click', function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var uid = $(this).data('id');

    if(confirm('정말 회원을 탈퇴를 하시겠습니까?\n한번 탈퇴를 시킨 경우 다시는 해당 메일주소와 계정을 사용할 수 없으며\n고객님의 개인정보는 삭제됩니다.\n그래도 동의하시겠습니까?')){
      $.ajax({
        url : "/user_delete",
        type : "POST",
        data: {_token: CSRF_TOKEN, uid:uid},
        dataType: 'JSON',
        success : function(data) {
          if(data.status){
            alert('회원 탈퇴가 완료되었습니다.');
            window.location.reload();
          }else{
            alert('회원 탈퇴 오류 발생\n관리자에게 문의하세요.');
          }
        }
      });
    }
  })

  @else

  $('#add_balance_save').click(function(e){
		alert('보안등급 3등급 부터 사용 가능한 기능입니다.\n보안등급 3등급 이상인 관리자에게 문의하세요.');
  });

  @endif
  
  function refresh_balance_modal(id) {
    $.ajax({
      url : "/balance/refresh_user",
      type : "POST",
      data : {_token : CSRF_TOKEN, id : id},
      dataType : "JSON",
      success : function(data) {
        $('input[name=available_balance_tlc]').val(data);
      }
    });
}

  loadPopup('.add_balance_confirm', '#add_balance_edit_wrap', function(e) {
    $('input[name=available_balance_tlc]').val('');
		$('#add_balance_reason').val('');
		$('#add_balance_amount').val('');
		$('#add_balance_save').attr('disabled', false).data('id', $(e.currentTarget).data('id'));
	});

  function loadPopup(button, popup, onload){    
		$(button).click(function(e){
        var id = $(e.currentTarget).data('id');
        refresh_balance_modal(id);

				$(popup).removeClass('hidden');
				setTimeout(function() { $(popup).addClass('active'); }, 300);
				onload(e);
			});

			$(popup + ' .jw_overlay, ' + popup + ' .jw_modal_hd>div').click(function(){
			$(popup).removeClass('active');
			setTimeout(function() { $(popup).addClass('hidden');}, 300);
		});
	}
</script>

@endsection