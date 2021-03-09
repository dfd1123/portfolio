@extends('company_ver.layouts.app') 
@section('content')

<div class="main">
    <div class="ment_div">
        <p class="login_ment">로그인</p>
        <p class="next_ment">다음에 하기 ></p>
    </div>
    <div class="input_div">
        <input id="id" placeholder="이메일 아이디"/>
    </div>
    <div class="input_div">
        <input id="pwd" placeholder="비밀번호"/>
    </div>
    <input type="hidden" name="pwd_certification" value="0"/>
    <p id="ment"></p>
    <div class="login_btn" id="login">
        <i class="fal fa-long-arrow-right"></i>
    </div>
</div>
<div class="bottom">
    <p>아직 퍼주마 회원이 아니신가요?</p>
    <div>
        <button>사장님 회원가입</button>
        <button>시공업체 회원가입</button>
    </div>
</div>
<script>
$(function() {

    function pwdcertification(){
        if($('#pwd1').val() != $('#pwd2').val()){
            $('#ment').html('비밀번호가 일치하지 않습니다');
            $('#ment').attr('style','color:#ff2626;')
            $('#id').attr('style','border-bottom: 1px solid #ff2626;')
            $('#pwd').attr('style','border-bottom: 1px solid #ff2626;')
            $('input[name=pwd_certification]').val(0);
            return false;
        }else{
            $('#ment').html('비밀번호가 일치합니다');
            $('#ment').attr('style','color:#007bd2;')
            $('#id').attr('style','border-bottom: 1px solid #007bd2;')
            $('#pwd').attr('style','border-bottom: 1px solid #007bd2;')
            $('input[name=pwd_certification]').val(1);
            return true;
        }
    }
    $('#login').on('click', function(){
        if(pwdcertification()){

            // Ajax 성공하면..
            location.href="/company_ver/";
            

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
                text: "비밀번호가 틀렸습니다",
                button: "확인",
            });
        }

    });

});
</script>


@endsection