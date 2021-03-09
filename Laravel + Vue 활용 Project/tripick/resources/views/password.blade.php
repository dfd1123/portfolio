@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--findpw">

    <div class="hd-title hd-title--01">
        <button type="button" onclick="location.href='/login'" class="hd-title__left-btn hd-title__left-btn--close"><span
                class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">비밀번호 재설정</h2>
    </div>
    <form id="frm_findpw" method="POST" action="/api/pw_email">
    <div class="wrapper--inner">
        <div class="find_pw_panel">
            <label for="" class="_label">이메일 비밀번호 재설정</label>
            <input type="email" name="email" class="_email_input "placeholder="이메일을 입력해주세요." required>
            <button type="submit" onclick="$('.loading_wrap').css('display','block');" class="button">이메일로 전송</button>
        </div>
    </div>
    </form>

    <div class="loading_wrap" style="display:none;">
        <div id="loading"></div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#frm_findpw').ajaxForm({
        dataType : "json",
        beforeSubmit: function() {
            return $('#frm_findpw').valid();
        },
        success: function(data) {
            $('.loading_wrap').css('display','none');
            if(data == "reg-app"){
                dialog.alert({
                    title:'알림',  
                    message: '메일이 전송되었습니다. 해당 메일을 확인해주세요.',
                    button: "확인"
                });
                
            }else if(data == "reg-no"){
                dialog.alert({
                    title:'오류',  
                    message: '존재하지 않는 이메일 계정입니다.',
                    button: "확인"
                });
            }else if(data == "reg-kakao"){
                dialog.alert({
                    title:'오류',  
                    message: '카카오 연동 이메일 계정입니다. 카카오 로그인을 해주세요.',
                    button: "확인"
                });
            }else if(data == "reg-naver"){
                dialog.alert({
                    title:'오류',  
                    message: '네이버 연동 이메일 계정입니다. 네이버 로그인을 해주세요.',
                    button: "확인"
                });
            }else{
                dialog.alert({
                    title:'오류',  
                    message: '네트워크 문제로 잠시 후 다시 시도해주세요.',
                    button: "확인"
                });
            }
        }    
    });
});
</script>
@endsection