@extends('company_ver.layouts.app') 
@section('content')

<div class="estimate_request_wrap">
<div class="sqm_wrap" style="margin-top:20px;">
        <div class="request_box">
            <p>이름</p>
            <div class="input_div">
                <input id="name" name="name" type="email" placeholder="실명입력"/>
            </div>
        </div>
    </div>
    <div class="sqm_wrap">
        <div class="request_box">
            <p>휴대폰 번호</p>
            <div class="input_div">
                <input id="user_contact" name="user_contact" type="email" placeholder="'-'포함 11자리"/>
            </div>
        </div>
    </div>
    <div class="sqm_wrap">
        <div class="request_box">
            <p>이메일<em>입력하신 메일을 확인하여 인증을 완료해주세요.</em></p>
            <div class="input_div">
                <input id="email" name="email" type="email" placeholder="aaa@abc.com"/>
                <button class="blue_button" id="btn_certification">중복확인</button>
                <input type="hidden" name="state" value="0"/>
            </div>
        </div>
    </div>
    <div class="sqm_wrap" style="margin-bottom:20px;">
        <div class="request_box">
            <p>비밀번호</p>
            <div class="input_div" id="pwd1_div">
                <input type="password" id="pwd1" name="password" placeholder="8자리 이상 (영문,숫자,특수문자 포함)"/>
                <span class="gray_button" id="visible1">표시</span>
            </div>
            <div class="input_div" id="pwd2_div">
                <input type="password" id="pwd2" placeholder="비밀번호 확인"/>
                <span class="gray_button" id="visible2">표시</span>
            </div>
            <p class="certification_ment"></p>
            <input type="hidden" name="pwd_certification" value="0"/>
        </div>
    </div>
</div>

@include('company_ver.ft_button.ft_button')

<script>
$(function() {
    //비밀번호 표시 기능
    $('#visible1').bind('touchstart',function(){
        $('#pwd1').attr('type','text');
    });
    $('#visible1').bind('touchend',function(){
        $('#pwd1').attr('type','password');
    });

    $('#visible2').bind('touchstart',function(){
        $('#pwd2').attr('type','text');
    });
    $('#visible2').bind('touchend',function(){
        $('#pwd2').attr('type','password');
    });

    $('#pwd1').on('keyup', function(){
        bottomactive();
    });
    $('#pwd2').on('keyup', function(){
        bottomactive();
    });

    //이메일 중복검사 (차후 기능 추가)
    $('#btn_certification').on('click', function(){
        $('input[name=state]').val(1);
        bottomactive();
    });

    //하단 '다음'버튼 활성화 function
    function bottomactive(){
        if($('#phone').val() !='' && $('#name').val() != '' && $('#pwd1').val() != '' && $('#pwd2').val() != '' 
        && $('[name=state]').val() == 1 && $('[name=pwd_certification]').val()==1){
            $('.ft_button button').addClass('active');
        }
        else{
            $('.ft_button button').removeClass('active');
        }
    }
    $('#name').on('keyup', function(){
        bottomactive();
    });
    $('#phone').on('keyup', function(){
        bottomactive();
    });
    $('#pwd1').on('keyup', function(){
        pwdcertification();
    });
    $('#pwd2').on('keyup', function(){
        pwdcertification();
    });
    function pwdcertification(){
        if($('#pwd1').val() != $('#pwd2').val()){
            $('.certification_ment').html('비밀번호가 일치하지 않습니다');
            $('.certification_ment').attr('style','color:#ff2626;')
            $('#pwd1_div').attr('style','border-bottom: 1px solid #ff2626;')
            $('#pwd2_div').attr('style','border-bottom: 1px solid #ff2626;')
            $('input[name=pwd_certification]').val(0);
            return false;
        }else{
            $('.certification_ment').html('비밀번호가 일치합니다');
            $('.certification_ment').attr('style','color:#007bd2;')
            $('#pwd1_div').attr('style','border-bottom: 1px solid #007bd2;')
            $('#pwd2_div').attr('style','border-bottom: 1px solid #007bd2;')
            $('input[name=pwd_certification]').val(1);
            return false;
        }
    }
    $('.ft_button button').on('click', function(){
        if($(this).hasClass('active')){

            // Ajax 성공하면..
            if(chk()){
                location.href="/company_ver/company_regist/5";
            }
            

            // Ajax 실패하면..
            /*
            swal({
                title: "오류",
                text: "죄송합니다.<br>시스템 오류로 인해 업종이 저장되지 않았습니다.<br>다시 시도해주세요.",
                button: "확인",
            });
            */
        }else{
            swal({
                title: "알림",
                text: "입력란을 모두 입력해주세요",
                button: "확인",
            });
        }

    });

});
function chk(){
    var pw = $('#pwd1').val();
    if(pw.length<8 || pw.length>16){
        swal({
                title: "알림",
                text: "8~16자 사이로 입력해주세요",
                button: "확인",
            });
        return false;
    }
    var check = /^(?=.*[a-zA-Z])(?=.*[^a-zA-Z0-9])(?=.*[0-9]).{8,16}$/;
    if(!check.test(pw)){
        swal({
                title: "알림",
                text: "영문, 숫자, 특수문자의 조합으로 입력해주세요",
                button: "확인",
            });
        return false;
    }
    return true;
}
</script>
@endsection