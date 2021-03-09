@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
	<div class="joinform">
		<form name="form_chk" method="post">
			<input type="hidden" name="m" value="checkplusSerivce">	
			<input type="hidden" name="EncodeData" id="EncodeData" >
		</form>
		<form method="POST" id="register_form" action="{{ route('register') }}">
			@csrf
			<div class="jbox"> 
				<h3 class="tit">회원정보</h3>
				<ul>
					<li><input type="text" id="name" name="name" tabindex="2" title="이름" class="required kr" placeholder="이름" value="" required="" autofocus="" onclick="fnPopup()" required readonly ></li>
					<li><input type="text" id="mobile" name="mobile" tabindex="2" title="핸드폰번호" class="required kr" placeholder="핸드폰번호" value=""  onclick="fnPopup()" required readonly ></li>
					<li>
						<div class="inp_with_btn">
							<input type="email" id="email" name="email" tabindex="2" title="이메일" class="required kr" placeholder="이메일">
							<button type="button" id="email_certify_btn" class="certify_btn">중복검사</button>
							<input type="hidden" id="email_certify" name="email_certify" value="0">
						</div>
					</li>
					<li>
						<div class="inp_with_btn">
							<input type="text" tabindex="2" title="닉네임" class="required kr" placeholder="닉네임" id="nickname" name="nickname" value="" onblur="chkchar(this)" required="">
							<button type="button" id="nickname_certify_btn" class="certify_btn">중복검사</button>
							<input type="hidden" id="nickname_certify" name="nickname_certify" value="0">
						</div>
					</li>
					<li><input type="password" id="password" name="password" tabindex="2" title="비밀번호" class="required kr" placeholder="비밀번호"></li>
					<li><input type="password" id="password-confirm" name="password_confirmation" tabindex="2" title="비밀번호" class="required kr" placeholder="비밀번호확인"></li>
					
					<li>
						<input type="text" id="post_num" name="post_num" value="" tabindex="2" title="우편번호" class="required kr" placeholder="우편번호" style="width:60%;" onclick="Postcode(); required" readonly="readonly" required="required">
						<a href="javascript:void(0)" onclick="Postcode();return false;" class="add_search">주소검색</a>
					</li>
					<li><input type="text" id="address1" name="address1" tabindex="2" title="기본주소" class="required kr" placeholder="기본주소" value="" required=""></li>
					<li><input type="text" id="address2" name="address2" tabindex="2" title="상세주소" class="required kr" placeholder="상세주소" value="" required=""></li>
					<li style="display:none;"><input type="text" id="extra_addr" name="extra_addr" tabindex="2" title="참고주소" class="required kr" placeholder="참고주소" value=""><span id="guide" style="color:#999;display:none"></span></li>
				</ul>
			</div>
			<div class="agbox">
				<h3 class="tit">이용약관 내용 동의</h3>
				<div class="agree">
					<div class="folder_check">
						<ul>
							<li>
								<input type="checkbox" id="agree1" name="agree1" value="0">
								<label for="agree1"><span></span>전체약관동의</label>
							</li>
							<li>
								<input type="checkbox" id="agree2" name="agree2" value="0">
								<label for="agree2"><span></span>서비스 이용약관 동의(필수)</label>
								<label class="btn" for="open-pop">내용보기</label>
							</li>
							<li>
								<input type="checkbox" id="agree3" name="agree3" value="0">
								<label for="agree3"><span></span>개인정보 수집,이용에 대한 동의(필수)</label>
								<label class="btn" for="open-pop2">내용보기</label>
							</li>
							<li>
								<input type="checkbox" id="agree4" name="agree4" value="0">
								<label for="agree4"><span></span><em>전자적 전송매체를 이용한 광고성 정보의 수신에 동의합니다.(선택)</em></label>
							</li>
						</ul>
					</div>
				</div>
				<button type="submit" class="joinbt">회원가입하기</button>
			</div>
			
		</form>
	</div>
</div>


<script src="https://ssl.daumcdn.net/dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
var chk1 =0;
var chk2 =0;

antcheck(1); //agree1 약관동의 전체 체크
antcheck(2); //agree2 필수
antcheck(3); //agree3 필수
antcheck(4); //agree4 선택


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
	}else if(chk1 !=1 || chk2 !=1){
		alert('필수 약관에 동의하여 주세요.');
		$('#agree2').focus();
		return false;
	}else if($('#mobile').val() == ''){
		//alert('핸드폰 인증을 완료해주세요.');
		return false;
	}

	return true;
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

function antcheck(idx){
	if(idx==1){
		$('input:checkbox[name="agree1"]').click(function(){
			var ischeckEmpty1 = isEmpty($('input[name="agree1"]').prop('checked'));
			if(ischeckEmpty1){
				$('input:checkbox[name="agree2"]').prop('checked',false);
				chk1 = 0;
				$('input:checkbox[name="agree3"]').prop('checked',false);
				chk2 = 0;
				$('input:checkbox[name="agree4"]').prop('checked',false);
			}
			else{
				$('input:checkbox[name="agree2"]').prop('checked',true);
				chk1 = 1;
				$('input:checkbox[name="agree3"]').prop('checked',true);
				chk2 = 1;
				$('input:checkbox[name="agree4"]').prop('checked',true);
			}
		});
	}
	else{
		$('input:checkbox[name="agree'+idx+'"]').click(function(){
			var ischeckEmpty2 = isEmpty($('input[name="agree2"]').prop('checked'));
			var ischeckEmpty3 = isEmpty($('input[name="agree3"]').prop('checked'));
			var ischeckEmpty4 = isEmpty($('input[name="agree4"]').prop('checked'));
			if(idx==2 ? ischeckEmpty2 : idx==3 ? ischeckEmpty3 : ischeckEmpty4){
				$('input:checkbox[name="agree1"]').prop('checked',false);
				if(idx==2)	chk1=0;
				else if(idx==3)	chk2=0;
			}
			else{
				if(idx==2)	chk1=1;
				else if(idx==3)	chk2=1;
			}
			if(!ischeckEmpty2 && !ischeckEmpty3 && !ischeckEmpty4){
				$('input:checkbox[name="agree1"]').prop('checked',true);
			}
		});
	}
}
function chkPwd(str){
	var pw = str;
	var num = pw.search(/[0-9]/g);
	var eng = pw.search(/[a-z]/ig);
	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

	if(pw.length < 8 || pw.length > 20){
		alert("8자리 ~ 20자리 이내로 입력해주세요.");
		return false;
	}

	if(pw.search(/₩s/) != -1){
		alert("비밀번호는 공백없이 입력해주세요.");
		return false;
	} if(num < 0 || eng < 0 || spe < 0 ){
		alert("영문,숫자, 특수문자를 혼합하여 입력해주세요.");
		return false;
	}
	return true;
}
function isEmpty(str){
	if(typeof str == "undefined" || str == null || str == "")
		return true;
	else
		return false ;
}
function chkchar(obj)
{
	 var chrTmp;
	 var strTmp  = obj.value;
	 var strLen  = strTmp.length;
	 var chkAlpha = false;
	 var resString = '';
	    if (strLen > 0) {
	        for (var i=0; i<strTmp.length; i++)
	        {
	            chrTmp = strTmp.charCodeAt(i);
	            if (!((chrTmp > 47 && chrTmp < 58) || (chrTmp > 64 && chrTmp < 91) || (chrTmp > 96 && chrTmp < 123) || (chrTmp > 44031 && chrTmp < 55203) || (chrTmp > 12592 && chrTmp < 12644)))
	            {
	                chkAlpha = true;
	            }
	            else
	            {
	                resString = resString + String.fromCharCode(chrTmp);
	            }
	        }
	    }
	 if (chkAlpha == true)
	 {
		  alert("한글,영문,숫자로만 작성해주세요.");
		  obj.value = resString;
		  obj.focus();
		  return false;
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
