@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--sign-up wrapper--sign-up--01">
    네이버 로그인 
</div>

@endsection

@section('script')
<script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8"></script>

<script>
var naverLogin = new naver.LoginWithNaverId({
    clientId: "NP1uNTROBqcXEWyUVAB_",
    callbackUrl: "https://xn--oy2b117blyb.com/api/login/navercallback",
    isPopup: false
});

naverLogin.init();
window.addEventListener('load', function() {
    naverLogin.getLoginStatus(function(status) {
        if (status) {

            var email = naverLogin.user.getEmail();
            var name = naverLogin.user.getNickName();
            var profileImage = naverLogin.user.getProfileImage();
            var uniqId = naverLogin.user.getId();

            if (email == undefined || email == null) {
                alert("이메일은 필수정보입니다. 정보제공을 동의해주세요.");
                naverLogin.reprompt();
                return;
            }
            if (name == undefined || name == null) {
                alert("이름은 필수정보입니다. 정보제공을 동의해주세요.");
                naverLogin.reprompt();
                return;
            }
            if (profileImage == undefined || profileImage == null) {
                alert("프로필사진은 필수정보입니다. 정보제공을 동의해주세요.");
                naverLogin.reprompt();
                return;
            }
            var param = { 
                'user_name': name,
                'user_email': email,
                'user_pwd': uniqId,
                'user_thumb': profileImage
            };
            /*$.ajax({
                type : "POST",
                data: param,
                dataType: 'json',
                url : '/api/Users',
                success : function(data){
                    console.log(data);
                    if(data.state==1 && data.query !=null){
                        dialog.alert({
                            title:'회원가입',  
                            message: '회원가입이 완료되었습니다. 로그인 해주세요.',
                            button: "확인",
                            callback: function(value){
                                location.href='/login';
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
            });*/

            // window.location.replace("처리후 되돌아갈 곳");
        } else {
            console.log("callback 처리에 실패하였습니다.");
        }
    });
});
</script>
@endsection