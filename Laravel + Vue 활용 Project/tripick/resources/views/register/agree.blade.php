@extends('layouts.app')

@section('content')

<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<div class="hd-title">
    <button type="button" onclick="location.href='/login'" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
</div>

    <div class="wrapper wrapper--sign-up wrapper--sign-up--01">

        <div class="wrapper--inner">

            <div class="agree-intro">

                <input type="checkbox" id="check_first_agree" class="input-style-01">
                <label for="check_first_agree"><span class="none">약관동의 체크박스</span></label>
                <label class="agree-intro__label" id="view_agree_list">약관동의</label><br>
                <span class="agree-intro__span inline">약관에 먼저 동의해주세요.</span>

            </div>

            <div class="account-list">

                <ul class="account-list__list-group">
                    <li class="account-list__list" id="start_email">
                        <i class="account-list__list__icon account-list__list__icon--email"></i>
                        <span class="account-list__list__txt">이메일로 시작하기</span>
                    </li>
                </ul>

            </div>

        </div>

    </div>

    <div class="popup popup--sign-up popup--sign-up--01" id="view_agree_list_popup">

        <div class="hd-title hd-title--01">
            <button type="button" class="hd-title__left-btn hd-title__left-btn--close"
                id="view_agree_list_close_btn"><span class="none">닫기버튼</span></button>
            <h2 class="hd-title__center">약관동의</h2>
        </div>

        <div class="sign-up-agree sign-up-agree--first">
            <input type="checkbox" id="check_all_agree" class="input-style-01">
            <label for="check_all_agree"><span class="none">모두동의 체크박스</span></label>
            <label for="check_all_agree" class="sign-up-agree__label">모두 동의</label>
        </div>

        <div class="wrapper--inner">

            <div class="sign-up-agree sign-up-agree--etc">
                <input type="checkbox" id="check_agree_01" name="must-agree" class="input-style-01">
                <label for="check_agree_01"><span class="none">회원가입 및 이용약관 체크박스</span></label>
                <label for="check_agree_01" class="sign-up-agree__label">회원가입 및 이용약관 (필수)</label>
            </div>
            <div class="sign-up-agree sign-up-agree--etc">
                <input type="checkbox" id="check_agree_02" name="must-agree" class="input-style-01">
                <label for="check_agree_02"><span class="none">개인정보 수집 및 이용동의 체크박스</span></label>
                <label for="check_agree_02" class="sign-up-agree__label">개인정보 수집 및 이용에 대한 동의 (필수)</label>
            </div>
            <div class="sign-up-agree sign-up-agree--etc">
                <input type="checkbox" id="check_agree_03" name="must-agree" class="input-style-01">
                <label for="check_agree_03"><span class="none">위치정보이용약관 동의 체크박스</span></label>
                <label for="check_agree_03" class="sign-up-agree__label">위치정보이용약관 동의(필수)</label>
            </div>
            <div class="sign-up-agree sign-up-agree--etc">
                <input type="checkbox" id="check_agree_04" name="push_agree" class="input-style-01">
                <label for="check_agree_04"><span class="none">세일정보 푸쉬알림 동의 체크박스</span></label>
                <label for="check_agree_04" class="sign-up-agree__label">세일 정보 푸쉬 알림 동의(선택)</label>
            </div>

        </div>

        <div class="button-bt-fixed">
            <button type="button" class="button button--disable" id="view_agree_list_close_btn-02" disabled="true">동의하고
                계속 진행합니다.</button>
        </div>

    </div>
    @endsection

    @section('script')
    <script>
    //<![CDATA[
    // 사용할 앱의 JavaScript 키를 설정해 주세요.

    /*Kakao.init('da617ac41543531f5f3c0cef1958dc4a');

    function loginWithKakao() {
        // 로그인 창을 띄웁니다.
        Kakao.Auth.login({
            success: function(authObj) {
                alert(JSON.stringify(authObj));
            },
            fail: function(err) {
                alert(JSON.stringify(err));
            }
        });
    };*/
    //]]>
    
    

    $(function() {

        $('#start_email').click(function() {
            if ($('#check_first_agree').prop('checked')) {
                if ($('#check_agree_04').prop('checked')) {
                    location.href = '/register/step1?push_agree=1';
                } else {
                    location.href = '/register/step1?push_agree=0';
                }
            } else {
                dialog.alert({
                    title: '잠깐!',
                    message: '약관에 먼저 동의해주세요.',
                    button: "확인"
                });
            }
        });

        //약관동의 팝업 보기
        $('#view_agree_list').click(function() {
            $('#view_agree_list_popup').addClass('is-active');
        })
        $('#view_agree_list_close_btn, #view_agree_list_close_btn-02').click(function() {
            $('#view_agree_list_popup').removeClass('is-active');
        })
        //end

        //약관동의 - 먼저나오는 약관동의 체크하면 다 체크
        $('#check_first_agree').click(function() {

            var chk = $(this).is(':checked');

            if (chk) {

                $('#check_all_agree').prop('checked', true);
                $('.sign-up-agree--etc input[type="checkbox"]').prop('checked', true);
                $('.button--disable').attr('disabled', false).addClass('is-active');

            } else {

                $('#check_all_agree').prop('checked', false);
                $('.sign-up-agree--etc input[type="checkbox"]').prop('checked', false);
                $('.button--disable').attr('disabled', true).removeClass('is-active');

            }

        })

        //약관동의 - 모두동의 체크하면 다 체크
        $('#check_all_agree').click(function() {

            var chk = $(this).is(':checked');

            if (chk) {

                $('#check_first_agree').prop('checked', true);
                $('.sign-up-agree--etc input[type="checkbox"]').prop('checked', true);
                $('.button--disable').attr('disabled', false).addClass('is-active');

            } else {

                $('#check_first_agree').prop('checked', false);
                $('.sign-up-agree--etc input[type="checkbox"]').prop('checked', false);
                $('.button--disable').attr('disabled', true).removeClass('is-active');

            }

        });
        //end

        //필수동의사항 하나라도 체크안하면 버튼비활성화
        $('input[name="must-agree"]').click(function() {

            var chk1 = $('#check_agree_01').is(':checked');
            var chk2 = $('#check_agree_02').is(':checked');
            var chk3 = $('#check_agree_03').is(':checked');

            if (chk1 == false || chk2 == false || chk3 == false) {

                $('#check_first_agree').prop('checked', false);
                $('#check_all_agree').prop('checked', false);
                $('.button--disable').attr('disabled', true).removeClass('is-active');

            } else {

                $('#check_first_agree').prop('checked', true);
                $('.button--disable').attr('disabled', false).addClass('is-active');

            }

        }); //end
    });
    </script>
    @endsection