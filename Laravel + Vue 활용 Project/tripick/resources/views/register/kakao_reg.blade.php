@extends('layouts.app')

@section('content')
<!-- 카카오로그인/가입 관련 오류 메세지 페이지 -->
<div class="wrapper wrapper--sign-up wrapper--sign-up--02">
	@if($check_login == 'not-register')
    <div class="hd-title hd-title--01">
        <button type="button" class="hd-title__left-btn hd-title__left-btn--close" onclick="location.href='/login';"><span
                class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">회원가입</h2>
    </div>
    @endif
    <form id="user_form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="req" value = "user">
    <input type="hidden" id="push_token" name="push_token">
    <input type="hidden" name="push_agree" value = "1">
    <input type="hidden" name="reg_type" value = "kakao">
    <input type="hidden" name="user_name" value = "{{ isset($user_info->properties->nickname) ? $user_info->properties->nickname : '' }}">
    <input type="hidden" name="user_email" id="login_email" value = "{{ isset($user_info->kakao_account->email) ? $user_info->kakao_account->email : '' }}">
    <input type="hidden" name="user_thumb_another" value = "{{ isset($user_info->properties->thumbnail_image) ? $user_info->properties->thumbnail_image : '' }}">
    <input type="hidden" name="user_pwd" id="login_pw" value = "{{ isset($user_info->id) ? $user_info->id : '' }}">
    @if($check_login == 'not-register')
    <div class="wrapper--inner">

        <fieldset class="sign-up-fieldset">
            <ul class="sign-up-fieldset__group">
                <li class="sign-up-fieldset__list">
                    <input type="tel" id="sign_up_contact" name="user_contact" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input" placeholder="휴대폰 번호 입력" required>
                    <button type="button" class="button sign-up-fieldset--phone__button button--disable is-active" onclick ="alert('구현중입니다. 휴대폰 인증은 무시하고 진행하세요.');">인증번호 받기</button>
                </li>
                <!--li class="sign-up-fieldset__list">
                    <input type="tel" class="sign-up-fieldset__list__input sign-up-fieldset--phone__input"
                        placeholder="인증번호 입력">
                </li-->
            </ul>
        </fieldset>
        <p class="sign-up-fieldset--phone__certify none-input">
            <span class="sign-up-fieldset--phone__certify__count">00:59</span>
            <span class="sign-up-fieldset--phone__certify__caution">내에 인증번호를 입력해주세요.</span>
        </p>
        
    </div>

    <div class="button-bt-fixed">
        <button type="submit" id="user_regist" class="button is-active">완료</button>
    </div>
    @endif
    </form>
</div>
@endsection

@section('script')
<script>
$(function() {
	var mobile_kind = getMobileOperatingSystem();
    if (mobile_kind == "Android") {
        if(typeof window.JS != 'undefined'){
            window.JS.CallPushToken();
        }
    } else if (mobile_kind == "iOS") {
        // IOS function call
        if(typeof webkit !== 'undefined'){
			webkit.messageHandlers.CallPushToken.postMessage("push_token"); 
		}
    }


	@if($check_login == 'not-info')
	dialog.alert({
        title:'오류',  
        message: '카카오 유저정보가 없습니다.<br>카카오에 문의하시거나 일반회원가입을 이용해주세요.',
        button: "확인",
        callback: function(value){
        	location.href='/login';
        }
    });
    @elseif($check_login == 'not-access')
	dialog.alert({
        title:'오류',  
        message: '잘못된 접근입니다.<br>다시 시도해주세요.',
        button: "확인",
        callback: function(value){
        	location.href='/login';
        }
    });
	@elseif($check_login == 'not-agree')
	dialog.alert({
        title:'오류',  
        message: '카카오 이메일 이용 동의를 안하셨습니다.<br>다시 로그인 해서 트리픽 이메일 이용동의를 체크해주세요.',
        button: "확인",
        callback: function(value){
        	location.href='/login';
        }
    });
	@elseif($check_login == 'not-kakao')
	dialog.alert({
        title:'오류',  
        message: '해당 카카오 이메일과 동일한 이메일이 회원가입 되어 있습니다.<br>일반 로그인하시거나 TRIPICK에 문의해주세요.',
        button: "확인",
        callback: function(value){
        	location.href='/login';
        }
    });
	@elseif($check_login == 'not-register')
	$('#user_form').ajaxForm({
        type : "POST",
        dataType: 'json',
        url : '/api/Users',
        success : function(data){
            console.log(data);
            if(data.state==1 && data.query !=null){
            	var param = { 
	            	'email': $('#login_email').val(), 
	            	'password': $('#login_pw').val(), 
	            	'push_token': $('#push_token').val() 
            	};
                $.ajax({
			        method: "POST",
			        data: param,
			        dataType: 'json',
			        url: '/api/login',
			        success: function(data) {
			            if(data.state ==1){
			                console.log(data);
			                location.href = '/af_home';
			            }
			            else{
			                dialog.alert({
			                    title:'로그인',  
			                    message: data.msg,
			                    button: "확인"
			                });
			            }
			
			        },
			        error: function(e) {
			            console.log(e);
			        }
			    });
            }else{
                dialog.alert({
                    title:'오류',  
                    message: data.msg,
                    button: "확인"
                });
            }
        },
        error : function(e){
            console.log(e);
        }
    });
	
	@elseif($check_login == 'success')
	setTimeout(function(){
		var param = { 
	    	'email': $('#login_email').val(), 
	    	'password': $('#login_pw').val(), 
	    	'push_token': $('#push_token').val() 
		};
	    $.ajax({
	        method: "POST",
	        data: param,
	        dataType: 'json',
	        url: '/api/login',
	        success: function(data) {
	            if(data.state ==1){
	                console.log(data);
	                location.href = '/af_home';
	            }
	            else{
	                dialog.alert({
	                    title:'로그인',  
	                    message: data.msg,
	                    button: "확인"
	                });
	            }
	
	        },
	        error: function(e) {
	            console.log(e);
	        }
	    });
	},1000);
	
	@else
	dialog.alert({
        title:'오류',  
        message: '알수없는오류',
        button: "확인"
    });
	@endif
	
});

function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }
    return "unknown";
}

function CallBackToken(token) {
    $('#push_token').val(token);
}
</script>
@endsection