@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--sign-up wrapper--sign-up--02">

    <div class="hd-title hd-title--01">
        <button type="button" class="hd-title__left-btn hd-title__left-btn--close" onclick="history.back();"><span
                class="none">닫기버튼</span></button>
        <h2 class="hd-title__center">회원가입</h2>
    </div>
    <form id="user_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="req" value="user">
        <input type="hidden" name="push_agree" value="{{ $push_agree }}">
        <input type="hidden" name="reg_type" value="app">
        <input type="file" name="thumb" id="user_thumb" class="none-input">
        <div class="wrapper--inner">
            <div class="sign-up-profile">
                <figure class="sign-up-profile__image" id="user_thumb_temp" onclick="$('#user_thumb').click();">
                    <figcaption class="sign-up-profile__caption">프로필<br>이미지</figcaption>
                </figure>
            </div>

            <fieldset class="sign-up-fieldset">
                <ul class="sign-up-fieldset__group">
                    <li class="sign-up-fieldset__list">
                        <label for="sign_up_name" class="sign-up-fieldset__list__label">이름</label>
                        <input type="text" id="sign_up_name" name="user_name" class="sign-up-fieldset__list__input"
                            required>
                    </li>
                    <li class="sign-up-fieldset__list">
                        <label for="sign_up_mail" class="sign-up-fieldset__list__label">이메일</label>
                        <input type="email" id="sign_up_mail" name="user_email" class="sign-up-fieldset__list__input"
                            placeholder="tripick@naver.com" required>
                    </li>
                    <li class="sign-up-fieldset__list">
                        <label for="sign_up_pw" class="sign-up-fieldset__list__label">비밀번호</label>
                        <input type="password" id="sign_up_pw" name="user_pwd" class="sign-up-fieldset__list__input"
                            autocomplete="off" placeholder="6자 이상 입력해주세요." required>
                    </li>
                    <li class="sign-up-fieldset__list">
                        <label for="sign_up_pw" class="sign-up-fieldset__list__label">비밀번호 확인</label>
                        <input type="password" id="sign_up_pw2" class="sign-up-fieldset__list__input" autocomplete="off"
                            placeholder="6자 이상 입력해주세요." required>
                    </li>
                    <li class="sign-up-fieldset__list">
                        <input type="tel" id="sign_up_contact" name="user_contact"
                            class="sign-up-fieldset__list__input sign-up-fieldset--phone__input"
                            placeholder="휴대폰 번호 입력">
                        <!--button type="button" class="button sign-up-fieldset--phone__button button--disable is-active" onclick ="alert('구현중입니다. 휴대폰 인증은 무시하고 진행하세요.');">인증번호
                        받기</button-->
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

            <div class="button-wrap">
                <button type="submit" id="user_regist" class="button is-active">완료</button>
            </div>

        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $(function () {
        $('#user_thumb').change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#user_thumb_temp').css('background-image', 'url(' + e.target.result + ')');
                    $('#user_thumb_temp').css('background-size', '100% 100%');
                    $('#user_thumb_temp').empty();
                }

                reader.readAsDataURL(this.files[0]);
            }
            //$('#user_thumb_temp').css('background-image','url()')
        });

        $('#user_form').ajaxForm({
            type: "POST",
            dataType: 'json',
            url: '/api/Users',
            beforeSubmit : function (data, form, option) {

                if ($('#sign_up_pw').val() === $('#sign_up_pw2').val()) {
                  
                    return true;
                }
                
                dialog.alert({
                        title: '암호오류',
                        message: '비밀번호를 확인해주세요',
                        button: "확인"
                    });

                return false;
            },
            success: function (data) {
                if (data.state == 1 && data.query != null) {
                    dialog.alert({
                        title: '회원가입',
                        message: '회원가입이 완료되었습니다. 로그인 해주세요.',
                        button: "확인",
                        callback: function (value) {
                            location.href = '/login';
                        }
                    });
                } else {
                    dialog.alert({
                        title: '오류',
                        message: data.msg,
                        button: "확인"
                    });
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    

    });
</script>
@endsection