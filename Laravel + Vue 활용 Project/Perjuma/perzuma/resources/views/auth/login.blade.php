@extends('user_ver.layouts.app') 
@section('content')

<div class="main">
    <div class="ment_div">
        <p class="login_ment">로그인</p>
        <p class="next_ment">다음에 하기 ></p>
    </div>
    <div class="input_div">
        <input type="email" id="id" placeholder="이메일 아이디"/>
    </div>
    <div class="input_div">
        <input type="password" id="pwd" placeholder="비밀번호"/>
    </div>
    <input type="hidden" name="pwd_certification" value="0"/>
    <div class="bottom_div">
        <p class="forgotpwd" id="forgotpwd">비밀번호를 잊어버리셨습니까?</p>
    </div>
    <button type="submit" class="login_btn" id="login">
        <i class="fal fa-long-arrow-right"></i>
    </button>
</div>
<div class="bottom">
    <p>아직 퍼주마 회원이 아니신가요?</p>
    <div>
        <a href="{{route('register').'?ver=user_ver'}}">사장님 회원가입</a>
        <a href="{{route('register').'?ver=company_ver'}}">시공업체 회원가입</a>
    </div>
</div>
<script>
$(function() {

    function pwdcertification(){
        var email = $('#id').val();
        var password = $('#pwd').val();
        $.ajax({
            type : "POST",
            dataType: "json",
            data : {email : email, password : password},
            url : "/api/login",
            success : function(data) {
                if(typeof data.not_verify != 'undefined'){
                    swal({
                        title: "인증 필요",
                        text: "이메일 인증을 하지 않으셨습니다.\n가입하신 이메일 계정을 확인하세요.",
                        button: "확인",
                    });

                    return false;
                }else if(typeof data.unregist_wait != 'undefined'){
                    swal({
                        title: "계정 탈퇴",
                        text: "계정 탈퇴 대기중인 계정입니다.",
                        button: "확인",
                    });

                    return false;
                }else if(typeof data.unregist_complete != 'undefined'){
                    swal({
                        title: "계정 탈퇴",
                        text: "탈퇴된 계정입니다",
                        button: "확인",
                    });

                    return false;
                }
                
                $('#ment').html('비밀번호가 일치합니다');
                $('#ment').attr('style','color:#007bd2;');
                $('#id').attr('style','border-bottom: 1px solid #007bd2;');
                $('#pwd').attr('style','border-bottom: 1px solid #007bd2;');
                localStorage.passportToken = data.token;
                $.ajaxSetup({
                    beforeSend: function (xhr)
                    {
                        xhr.setRequestHeader("Authorization", `Bearer ${data.token}`);
                    }
                });
                if(data.type==1){
                    location.href="/user_ver";
                }
                else if(data.type==2){
                    location.href="/company_ver";
                }
                else if(data.type==3){
                    location.href="/company_ver/company_regist/1"
                }
                else if(data.type==4){
                    location.href="/company_ver/company_regist/2"
                }
                else if(data.type==5){
                    location.href="/company_ver/company_regist/3"
                }
                else if(data.type==6){
                    location.href="/company_ver/company_regist/4"
                }
                else if(data.type==7){
                    location.href="/company_ver/company_regist/5"
                }
            },
            error : function(data){
                $('#ment').html('비밀번호가 일치하지 않습니다');
                $('#ment').attr('style','color:#ff2626;');
                $('#id').attr('style','border-bottom: 1px solid #ff2626;');
                $('#pwd').attr('style','border-bottom: 1px solid #ff2626;');
                swal({
                    title: "알림",
                    text: "비밀번호가 틀렸습니다",
                    button: "확인",
                });
            }
        });
    }
    $('#login').on('click', function(){
        pwdcertification();

    });
    $(document).on('keypress', '#id', function(e) {
        if (e.which == 13) {
            pwdcertification();
        }
    });
    $(document).on('keypress', '#pwd', function(e) {
        if (e.which == 13) {
            pwdcertification();
        }
    });

    @if(session('not_verify'))
        swal({
            title: "인증 필요",
            text: "이메일 인증을 하지 않으셨습니다.\n가입하신 이메일 계정을 확인하세요.",
            button: "확인",
        });
    @endif
    
    @if(session('unregist wait'))
        swal({
            title: "계정 탈퇴",
            text: "계정 탈퇴 대기중인 계정입니다.",
            button: "확인",
        });
    @endif

    @if(session('unregist complete'))
        swal({
            title: "계정 탈퇴",
            text: "탈퇴된 계정입니다",
            button: "확인",
        });
    @endif
    $('#forgotpwd').click(function(){
        location.href='/forgotpwd';
    });

});
</script>


@endsection