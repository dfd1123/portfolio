@extends(session('theme').'.mobile.layouts.app')

@section('content')

<div class="m_hd_title">
    <div class="inner">
        회원 탈퇴
    </div>
</div>
<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-4">
    <p class="mypage_msg text-center">
        <span class="tit">회원탈퇴 주의사항 확인</span><br><br>
        보유자산이 남아있다면 출금 후<br>회원탈퇴를 진행해 주세요.<br><br>
        탈퇴 진행 시 보유하신 잔고 정보가<br>삭제되어 복구 불가능합니다.<br><br>
        회원탈퇴 시 스포와이드에 등록된 회원님의 개인 정보는 모두 삭제되며 복구되지 않습니다.<br><br>
        단, 관계법령에 따라 회사가 보관하여야<br>하는 정보는 일정 기간 보관됩니다.
    </p>
<!-- 공통된 비밀번호 변경 form -->
    <form method="post" action="{{route('mypage.member_secession')}}" id="secession_form">
        @csrf

        <div class="fixed_btn">
            <button type="button" class="btn_style abslt_btn" id="btn_member_out">
                회원 탈퇴
            </button>
        </div>
   
    </form>
</div>
    
<script>
    $(function(){
		$('#btn_member_out').click(function(){
			swal({
				title: "회원 탈퇴",
				text: "정말 탈퇴하시겠습니까?",
				icon: "warning",
				buttons: ['취소',__.message.ok ]
			}).then((confirm)=>{
				if(confirm){
					$('#secession_form').submit();
				}
			});
		});
	});

    if (typeof __ === 'undefined') { var __ = {}; }
    __.myp = {
        @foreach(__('myp') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
</script>

@endsection
