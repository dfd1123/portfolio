@extends('pc.layouts.app')

@section('content')
	<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
		<h2>마이페이지</h2>
	</div>
	<div id="container">
		@include('pc.mypage.include.my_common')
		<div class="orderbox">
			<div class="cartbox">
				<h3 class="mytit">정보수정</h3>
			</div> 
			<!-- 내 정보 수정 -->
			@csrf
            <form name="form_chk" method="post">
                <input type="hidden" name="m" value="checkplusSerivce">	
                <input type="hidden" name="EncodeData" id="EncodeData" >
            </form>
            <div class="modifybox">
                <h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 회원정보수정</h3>
                <form method="post" action="{{route('mypage.myinfor_update')}}">
                        <ul>
                            <li><input type="text" id="mb_id" name="mb_id" tabindex="2" title="아이디" class="required kr" placeholder="홍길동" value="{{Auth::user()->name}}" onclick="fnPopup()" readonly  /></li>
                            <li>
                                <div class="inp_with_btn">
                                    <input type="text" name="mb_nickname" id="nickname" tabindex="2" title="닉네임" class="required kr"  placeholder="닉네임" value="{{Auth::user()->nickname}}" onblur="chkchar(this)" required="required" />	
                                    <button type="button" id="nickname_certify_btn" class="certify_btn">중복확인</button>
                                    <input type="hidden" id="nickname_certify" name="nickname_certify" value="0">
                                </div>									
                            </li>
                            <li><input type="text" id="mb_hp" name="mb_hp" tabindex="2" title="비밀번호" class="required kr" placeholder="핸드폰번호" value="{{Auth::user()->mobile_number}}" onclick="fnPopup()" readonly  required="required" /></li>
                            <li><input type="text" id="post_num" name="post_num" tabindex="2" title="우편번호" class="required kr" placeholder="우편번호" value="{{Auth::user()->post_num}}" style="width:80%;" onclick="Postcode();" readonly="readonly"  required="required"/><a href="javascript:void(0);" onclick="Postcode();return false;" class="add_search">주소검색</a></li>
                            <li><input type="text" id="mb_addr1" name="mb_addr1" tabindex="2" title="주소" class="required kr" placeholder="주소" value="{{Auth::user()->addr1}}"  required="required"/></li>
                            <li><input type="text" id="mb_addr2" name="mb_addr2" tabindex="2" title="상세주소" class="required kr" placeholder="상세주소"  value="{{Auth::user()->addr2}}" required="required"/></li>
                            <li style="display:none;"><input type="text" id="extra_addr" name="extra_addr" tabindex="2" title="참고주소" class="required kr" placeholder="참고주소"  value="{{Auth::user()->extra_addr}}" /><span id="guide" style="color:#999;display:none"></span></li>
                        </ul>
                        <button type="submit" class="joinbt">수정하기</button>
                </form>
            </div>
            <!-- //내 정보 수정 -->
            <div class="secession_btn">
                <button type="button" id="user_delete">회원 탈퇴</button>
            </div>
            
		</div>
	</div>
@endsection

@section('main_script')
<script src="https://ssl.daumcdn.net/dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    $('#user_delete').on('click', function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if(confirm('정말 회원 탈퇴를 하시겠습니까?\n한번 회원 탈퇴를 하신 경우 다시는 해당 메일주소와 계정을 사용할 수 없으며\n고객님의 개인정보는 삭제됩니다.\n그래도 동의하시겠습니까?')){
            $.ajax({
                url : "/user_delete",
                type : "POST",
                data: {_token: CSRF_TOKEN},
                dataType: 'JSON',
                success : function(data) {
                    if(data.status){
                        alert('회원 탈퇴가 완료되었습니다.');
                        document.getElementById('logout-form').submit();
                    }else{
                        alert('회원 탈퇴 오류 발생\n관리자에게 문의하세요.');
                    }
                }
            });
        }
    })
    function fnPopup(){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url : "/auth/checkplus_main",
			type : "POST",
			data: {_token: CSRF_TOKEN},
			dataType: 'JSON',
			success : function(data) {
				$('#EncodeData').val(data.enc_data);
				window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
				document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
				document.form_chk.target = "popupChk";
				document.form_chk.submit();
			}
		});
	}

	function nicecheck_alert(status,messages,mobile_number,name){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		if(status == 0){
			alert(messages);
		}else{
			$.ajax({
				url: '/certify/mobile',
				type: 'POST',
				data: {_token: CSRF_TOKEN, mobile:mobile_number},
				dataType: 'JSON',
				success: function (data) { 
					if(data.exist){
						alert('이미 가입된 휴대폰 번호입니다.');
					}else{
						alert('휴대폰 인증 완료!');
						$('#mb_hp').val(mobile_number);
						$('#mb_id').val(name);
					}     	
				}
			});
			
		}
	}

	function Postcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 도로명 주소의 노출 규칙에 따라 주소를 표시한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var roadAddr = data.roadAddress; // 도로명 주소 변수
                var extraRoadAddr = ''; // 참고 항목 변수

                // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                    extraRoadAddr += data.bname;
                }
                // 건물명이 있고, 공동주택일 경우 추가한다.
                if(data.buildingName !== '' && data.apartment === 'Y'){
                   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                if(extraRoadAddr !== ''){
                    extraRoadAddr = ' (' + extraRoadAddr + ')';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('post_num').value = data.zonecode;
                document.getElementById("mb_addr1").value = roadAddr;
                
                // 참고항목 문자열이 있을 경우 해당 필드에 넣는다.
                if(roadAddr !== ''){
                    document.getElementById("extra_addr").value = extraRoadAddr;
                } else {
                    document.getElementById("extra_addr").value = '';
                }

                var guideTextBox = document.getElementById("guide");
                // 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
                if(data.autoRoadAddress) {
                    var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
                    guideTextBox.innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';
                    guideTextBox.style.display = 'block';

                } else if(data.autoJibunAddress) {
                    var expJibunAddr = data.autoJibunAddress;
                    guideTextBox.innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';
                    guideTextBox.style.display = 'block';
                } else {
                    guideTextBox.innerHTML = '';
                    guideTextBox.style.display = 'none';
                }
            }
        }).open();
    }
	
</script>
@endsection
