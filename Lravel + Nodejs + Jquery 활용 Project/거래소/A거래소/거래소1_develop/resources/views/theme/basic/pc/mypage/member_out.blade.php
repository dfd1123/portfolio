@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')

<div class="mypage_wrap">

    <div class="mypage_inner">
		<h1 class="tit pb-2 mb-5">회원탈퇴 안내</h1>
		<h2 class="tit pb-2 mb-5">회원탈퇴 주의사항 확인</h2>
		<ul>
			<li>보유자산이 남아있다면 출금 후 회원탈퇴를 진행해 주세요.<br>탈퇴 진행 시 보유하신 잔고 정보가 삭제되어 복구 불가능합니다.</li><br>
			<li>회원탈퇴 시 스포와이드에 등록된 회원님의 개인 정보는 모두 삭제되며 복구되지 않습니다.<br>단, 관계법령에 따라 회사가 보관하여야 하는 정보는 일정 기간 보관됩니다.</li>
		</ul>
		<form method="post" action="{{route('mypage.member_secession')}}" id="secession_form">
			@csrf
			<div class="form-group mt-4">
				<button type="button" id="btn_member_out" class="btn_style gradient_btn mt-3">
					회원 탈퇴
				</button>
			</div>
		</form>

        </div>

    </div>

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