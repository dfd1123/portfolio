@extends('pc.layouts.app')

@section('content')
<div class="sub_spot member">
	<h2>회원가입</h2>
	<div class="search_form">
		<form id="search_item" method="get" action="{{route('products.search_list',-1)}}">
			<input type="text" name="keyword" placeholder="작가명, 회화명, 분야 등으로 검색"/>
			<span><button type="submit">search<i class="fas fa-search"></i></button></span>
		</form>
	</div>
</div>
<div id="container">
	<div class="joinform">
		<form name="form_chk" method="post">
			<input type="hidden" name="m" value="checkplusSerivce">	
			<input type="hidden" name="EncodeData" id="EncodeData" >
		</form>
		<form method="POST" id="register_form" action="{{ route('register') }}">
			@csrf
			<div class="left"> 
				<h3 class=""><i class="fas fa-chevron-circle-right"></i>회원정보</h3>
				<ul>
					<li><input type="text" id="name" name="name" tabindex="2" title="이름" class="required kr"  placeholder="이름" value="{{ old('name') }}" onclick="fnPopup()" required readonly autofocus/></li>
					<li><input type="text" id="mobile" name="mobile" tabindex="2" title="핸드폰번호" class="required kr" placeholder="핸드폰번호" value="{{ old('mobile') }}"" onclick="fnPopup()"  required readonly/></li>
					<li>
						<div class="inp_with_btn">
							<input type="email" id="email" name="email" tabindex="2" title="이메일" class="required kr" placeholder="이메일"/>
							<button type="button" id="email_certify_btn" class="certify_btn">중복검사</button>
							<input type="hidden" id="email_certify" name="email_certify" value="0" />
						</div>
					</li>
					<li>
						<div class="inp_with_btn">
							<input type="text" tabindex="2" title="닉네임" class="required kr" placeholder="닉네임" id="nickname" name="nickname" value="{{ old('nickname') }}" onblur="chkchar(this)" required>
							<button type="button" id="nickname_certify_btn" class="certify_btn">중복검사</button>
							<input type="hidden" id="nickname_certify" name="nickname_certify" value="0" />
						</div>
					</li>
					<li><input type="password" id="password" name="password" tabindex="2" title="비밀번호" class="required kr" placeholder="비밀번호"/></li>
					<li><input type="password" id="password-confirm" name="password_confirmation" tabindex="2" title="비밀번호" class="required kr" placeholder="비밀번호확인"/></li>
					
					<li>
						<input type="text" id="post_num" name="post_num" value="{{ old('post_num') }}" tabindex="2" title="우편번호" class="required kr" placeholder="우편번호" style="width:80%;" onclick="Postcode(); required" readonly="readonly" required="required" />
						<a href="javascript:void(0)" onclick="Postcode();return false;" class="add_search" >주소검색</a>
					</li>
					<li><input type="text" id="address1" name="address1" tabindex="2" title="기본주소" class="required kr" placeholder="기본주소" value="{{ old('address1') }}" required /></li>
					<li><input type="text" id="address2" name="address2" tabindex="2" title="상세주소" class="required kr" placeholder="상세주소" value="{{ old('address2') }}" required /></li>
					<li style="display:none;"><input type="text" id="extra_addr" name="extra_addr" tabindex="2" title="참고주소" class="required kr" placeholder="참고주소"  value="{{ old('extra_addr') }}" /><span id="guide" style="color:#999;display:none"></span></li>
				</ul>
			</div>
			<div class="right">
				<h3 class=""><i class="fas fa-chevron-circle-right"></i>이용약관 내용 동의</h3>
				<div class="agree">
					<div class="folder_check" >
						<ul>
							<li>
								<input type="checkbox" id="agree1" name="agree1" value="0"/>
								<label for="agree1"><span></span>전체약관동의</label>
							</li>
							<li>
								<input type="checkbox" id="agree2" name="agree2" value="0"/>
								<label for="agree2"><span></span>서비스 이용약관 동의(필수)</label>
								<label class="btn" for="open-pop">내용보기</label>
							</li>
							<li>
								<input type="checkbox" id="agree3" name="agree3" value="0"/>
								<label for="agree3"><span></span>개인정보 수집,이용에 대한 동의(필수)</label>
								<label class="btn" for="open-pop2">내용보기</label>
							</li>
							<li>
								<input type="checkbox" id="agree4" name="agree4" value="0"/>
								<label for="agree4"><span></span><em>전자적 전송매체를 이용한 광고성 정보의 수신에 동의합니다.(선택)</em></label>
							</li>
						</ul>
					</div>
					<ul class="etclog">
						<li><a href="{{route('login')}}">로그인</a></li>
						<li><a href="{{ route('password.request') }}">아이디/비번찾기</a></li>
					</ul>
				</div>
				<button type="submit" class="joinbt">회원가입하기</button>
			</div>
			
		</form>
	</div>
</div>

<style>
	.inp_with_btn{position:relative;}
	
	.inp_with_btn input{width:100%;padding-right:90px;}
	
	.inp_with_btn button.certify_btn{position: absolute; top: 3px; right: 3px; height: 35px; border: 1px solid #b17501; background: #fea803; color: #fff; padding: 0 14px; border-radius: 5px;}
</style>

@endsection

@section('main_modal')

<!-- 모달창 -->
		<input class="modal-state" id="open-pop" type="checkbox">
		<div class="modal service">
			<label class="modal_bg" for="open-pop"></label>
			<div class="modal_inner kr">
				<label class="modal_close" for="open-pop"></label>
				<h2>서비스 이용약관</h2>
				<p>
				{!! $privacy->pc_contents !!}
				</p>
			</div>
		</div>
		<input class="modal-state" id="open-pop2" type="checkbox">
		<div class="modal service">
			<label class="modal_bg" for="open-pop2"></label>
			<div class="modal_inner kr">
				<label class="modal_close" for="open-pop2"></label>
				<h2>개인정보 수집,이용 약관</h2>
				<p>
				{!! $policy->pc_contents !!}
				</p>
			</div>
		</div>
<!-- //모달창 -->

@endsection

@section('script')
<script src="https://ssl.daumcdn.net/dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
	$('input[name="agree1"]').click(function(){
		if($(this).val() != 1){
			$(this).val(1);
			$('.folder_check ul li input').val(1);
			$('.folder_check ul li input').attr('checked',true);	
		}else{
			$(this).val(0);
			$('.folder_check ul li input').val(0);
			$('.folder_check ul li input').attr('checked',false);
		}
	});
	
	$('input[name="agree2"]').click(function(){
		if($(this).val() != 1){
			$(this).val(1);
		}else{
			$('input[name="agree1"]').attr('checked',false);	
			$('input[name="agree1"]').val(0);
		}
	});
	
	$('input[name="agree3"]').click(function(){
		if($(this).val() != 1){
			$(this).val(1);
		}else{
			$('input[name="agree1"]').attr('checked',false);	
			$('input[name="agree1"]').val(0);
		}
	});
	
	$('input[name="agree4"]').click(function(){
		if($(this).val() != 1){
			$(this).val(1);
		}else{
			$('input[name="agree1"]').attr('checked',false);	
			$('input[name="agree1"]').val(0);
		}
	});
	
	$('#password').change(function(){
		if(!chkPwd($(this).val())){
			$(this).val('');
		}
	});
	
	$('#password-confirm').change(function(){
		if($(this).val() != $('#password').val()){
			alert('비밀번호가 일치하지 않습니다. 다시 입력해주세요.');
			$(this).val('');
		}
	});
	
	$('#register_form').submit(function(){
		if($('#email_certify').val() != 1){
			alert('이메일 중복검사를 해주세요.');
			$('#email_certify').focus();
			return false;
		}else if($('#nickname_certify').val() != 1){
			alert('닉네임 중복검사를 해주세요.');
			$('#nickname').focus();
			return false;
		}else if($('input[name="agree3"]').val() != 1 || $('input[name="agree2"]').val() != 1){
			alert('필수 약관에 동의하여 주세요.');
			$('#agree2').focus();
			return false;
		}else if($('#mobile').val() == '' ){
			//alert('핸드폰 인증을 완료해주세요.');
			return false;
		}else{
			return true;
		}
		
	});
	
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
						$('#name').val('');
						$('#mobile').val('');
					}else{
						alert('휴대폰 인증 완료!');
						$('#mobile').val(mobile_number);
						$('#name').val(name);
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
                document.getElementById("address1").value = roadAddr;
                
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