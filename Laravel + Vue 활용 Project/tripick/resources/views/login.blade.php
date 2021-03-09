@extends('layouts.app')

@section('content')
<style>
    .another_login {
        width: 100%;
    }

    .another_login a img {
        width: 100%;
        height: 50px;
    }
</style>
<div class="wrapper wrapper--login">

    <div class="ani-logo-group login">

        <div class="ani-logo-group__logo">
            <svg id="그룹_784" class="logo--text logo--text--00" data-name="그룹 784" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" width="52.346" height="72.256" viewBox="0 0 52.346 72.256">
                <image id="사각형_296" data-name="사각형 296" width="48.65" height="52.327" transform="translate(3.697)"
                    xlink:href="data:image/png;base64,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" />
                <ellipse id="타원_349" data-name="타원 349" cx="3.574" cy="3.574" rx="3.574" ry="3.574"
                    transform="translate(22.423 24.224)" fill="#fff" />
                <path id="패스_2702" data-name="패스 2702"
                    d="M149.817,288.632a3.845,3.845,0,0,0-.736-1.413Q149.426,287.933,149.817,288.632Z"
                    transform="translate(-146.493 -249.603)" fill="#fff" />
                <path id="패스_2703" data-name="패스 2703"
                    d="M172.3,239.743l-1.08-2.087L151.429,209l0,0c-1.148.483-3.074-2.178-3.92-4.283a6.846,6.846,0,0,1-.479-2.1l-3.157-4.572a26.363,26.363,0,0,0-3.518,18.549l-.005.065a26,26,0,0,0,2.178,6.621,3.845,3.845,0,0,1,.74,1.411,26.331,26.331,0,0,0,2.037,3.084c.059.077.123.15.183.227l-.006,0,1.02,1.467-.014.01,19.66,28.425s4.368-3.721,4.87-4.333A11.947,11.947,0,0,0,172.3,239.743Z"
                    transform="translate(-139.93 -185.652)" fill="#fff" />
                <path id="패스_2704" data-name="패스 2704"
                    d="M169.414,220.592l0,0-4.4-6.378a6.846,6.846,0,0,0,.479,2.1C166.34,218.414,168.266,221.076,169.414,220.592Z"
                    transform="translate(-157.92 -197.245)" fill="#fff" />
                <ellipse id="타원_350" data-name="타원 350" cx="4.028" cy="4.028" rx="4.028" ry="4.028"
                    transform="translate(21.856 23.43)" fill="#fff" />
                <path id="패스_2705" data-name="패스 2705"
                    d="M155.968,299.128c-.047-.068-.094-.135-.14-.2C155.874,298.993,155.921,299.06,155.968,299.128Z"
                    transform="translate(-151.331 -257.998)" fill="#fff" opacity="0.5" />
                <path id="패스_2706" data-name="패스 2706" d="M144.929,277.123l-.009-.027Z"
                    transform="translate(-143.509 -242.343)" fill="#fff" opacity="0.5" />
                <path id="패스_2707" data-name="패스 2707" d="M142.042,266.787h0Z" transform="translate(-141.445 -234.949)"
                    fill="#fff" opacity="0.5" />
                <path id="패스_2708" data-name="패스 2708" d="M145.45,278.6l-.008-.021Z"
                    transform="translate(-143.883 -243.405)" fill="#fff" opacity="0.5" />
                <path id="패스_2709" data-name="패스 2709" d="M160.505,305.438h0l-1.024-1.481Z"
                    transform="translate(-153.951 -261.606)" fill="#fff" opacity="0.5" />
                <path id="패스_2710" data-name="패스 2710" d="M144.447,275.68h0Z" transform="translate(-143.169 -241.327)"
                    fill="#fff" opacity="0.5" />
                <path id="패스_2711" data-name="패스 2711" d="M151.683,292.216h0Z" transform="translate(-148.359 -253.187)"
                    fill="#fff" opacity="0.5" />
                <path id="패스_2712" data-name="패스 2712" d="M154.1,296.271l-.1-.152Z"
                    transform="translate(-150.021 -255.986)" fill="#fff" opacity="0.5" />
                <path id="패스_2713" data-name="패스 2713" d="M153.224,294.858l-.075-.125Z"
                    transform="translate(-149.41 -254.992)" fill="#fff" opacity="0.5" />
                <path id="패스_2714" data-name="패스 2714"
                    d="M158.106,302.133q-.151-.2-.3-.4Q157.956,301.935,158.106,302.133Z"
                    transform="translate(-152.753 -260.014)" fill="#fff" opacity="0.5" />
                <path id="패스_2715" data-name="패스 2715" d="M155.012,297.7l-.116-.176Z"
                    transform="translate(-150.663 -256.99)" fill="#fff" opacity="0.5" />
                <path id="패스_2716" data-name="패스 2716" d="M152.4,293.473l-.051-.088Z"
                    transform="translate(-148.839 -254.025)" fill="#fff" opacity="0.5" />
                <path id="패스_2717" data-name="패스 2717"
                    d="M156.976,300.576c-.059-.081-.117-.163-.175-.244C156.859,300.414,156.918,300.5,156.976,300.576Z"
                    transform="translate(-152.029 -259.007)" fill="#fff" opacity="0.5" />
                <path id="패스_2718" data-name="패스 2718" d="M141.728,265.277l0-.016Z"
                    transform="translate(-141.217 -233.856)" fill="#fff" opacity="0.5" />
                <path id="패스_2719" data-name="패스 2719" d="M141.443,263.789h0Z" transform="translate(-141.015 -232.8)"
                    fill="#fff" opacity="0.5" />
                <path id="패스_2720" data-name="패스 2720"
                    d="M155.453,298.363c-.05-.074-.1-.148-.148-.221C155.354,298.216,155.4,298.29,155.453,298.363Z"
                    transform="translate(-150.956 -257.436)" fill="#fff" />
                <path id="패스_2721" data-name="패스 2721"
                    d="M173.287,169.2a26.6,26.6,0,0,0-3.764,2.873c1.131-.263,1.674-1.223,2.665-1.839A3.709,3.709,0,0,0,173.287,169.2Z"
                    transform="translate(-161.153 -164.964)" fill="#fff" />
                <path id="패스_2722" data-name="패스 2722" d="M154.5,296.9q-.08-.122-.158-.245Q154.416,296.778,154.5,296.9Z"
                    transform="translate(-150.263 -256.37)" fill="#fff" />
                <path id="패스_2723" data-name="패스 2723"
                    d="M145.469,278.651a25.98,25.98,0,0,0,1.758,3.837,3.845,3.845,0,0,0-.736-1.413A25.968,25.968,0,0,1,145.469,278.651Z"
                    transform="translate(-143.902 -243.458)" fill="#fff" />
                <path id="패스_2724" data-name="패스 2724" d="M156.458,299.838l-.135-.195Z"
                    transform="translate(-151.686 -258.513)" fill="#fff" />
                <path id="패스_2725" data-name="패스 2725"
                    d="M142.723,269.3a26,26,0,0,1-.68-2.515A26,26,0,0,0,142.723,269.3Z"
                    transform="translate(-141.445 -234.951)" fill="#fff" />
                <path id="패스_2726" data-name="패스 2726"
                    d="M157.53,301.349c-.037-.051-.074-.1-.11-.153C157.457,301.247,157.493,301.3,157.53,301.349Z"
                    transform="translate(-152.473 -259.627)" fill="#fff" />
                <path id="패스_2727" data-name="패스 2727"
                    d="M153.581,295.441q-.084-.133-.166-.267Q153.5,295.308,153.581,295.441Z"
                    transform="translate(-149.601 -255.308)" fill="#fff" />
                <path id="패스_2728" data-name="패스 2728"
                    d="M144.581,276.083q-.068-.2-.133-.4Q144.513,275.883,144.581,276.083Z"
                    transform="translate(-143.17 -241.33)" fill="#fff" />
                <path id="패스_2729" data-name="패스 2729"
                    d="M152.706,293.989c-.059-.1-.117-.2-.175-.293C152.588,293.794,152.647,293.892,152.706,293.989Z"
                    transform="translate(-148.967 -254.248)" fill="#fff" />
                <path id="패스_2730" data-name="패스 2730"
                    d="M141.823,265.733q-.045-.207-.087-.415Q141.778,265.526,141.823,265.733Z"
                    transform="translate(-141.225 -233.897)" fill="#fff" />
                <path id="패스_2731" data-name="패스 2731"
                    d="M145.091,277.583q-.071-.2-.138-.392Q145.02,277.387,145.091,277.583Z"
                    transform="translate(-143.532 -242.411)" fill="#fff" />
                <path id="패스_2732" data-name="패스 2732"
                    d="M141.523,264.206q-.042-.208-.08-.416Q141.481,264,141.523,264.206Z"
                    transform="translate(-141.015 -232.801)" fill="#fff" />
                <path id="패스_2733" data-name="패스 2733"
                    d="M151.873,292.547c-.064-.11-.127-.22-.189-.33C151.746,292.328,151.809,292.438,151.873,292.547Z"
                    transform="translate(-148.36 -253.187)" fill="#fff" />
                <path id="패스_2734" data-name="패스 2734"
                    d="M223.282,240.922a3.491,3.491,0,1,0,3.491,3.491A3.491,3.491,0,0,0,223.282,240.922Z"
                    transform="translate(-197.203 -216.401)" fill="#fff" />
            </svg>

            <svg class="logo--text logo--text--01" xmlns="http://www.w3.org/2000/svg" width="40.391" height="52.808"
                viewBox="0 0 40.391 52.808">
                <path id="패스_2695" data-name="패스 2695"
                    d="M387.647,201.413H372.8v-8.849h40.391v8.849H398.209v43.959H387.647Z"
                    transform="translate(-372.804 -192.564)" fill="#fff" />
            </svg>
            <svg class="logo--text logo--text--02" xmlns="http://www.w3.org/2000/svg" width="25.62" height="40.96"
                viewBox="0 0 25.62 40.96">
                <path id="패스_2696" data-name="패스 2696"
                    d="M530.492,235.615h10.029l.836,8.274h.334a20.626,20.626,0,0,1,6.1-7.021,12.519,12.519,0,0,1,7.188-2.424,14.342,14.342,0,0,1,3.176.293,18,18,0,0,1,2.34.71l-2.173,10.614a12.206,12.206,0,0,0-2.3-.585,17.583,17.583,0,0,0-2.549-.167,10.323,10.323,0,0,0-5.767,2.048q-3.009,2.049-5.015,7.146v27.915h-12.2Z"
                    transform="translate(-530.492 -234.445)" fill="#fff" />
            </svg>
            <svg class="logo--text logo--text--03" xmlns="http://www.w3.org/2000/svg" width="12.488" height="57.803"
                viewBox="0 0 12.488 57.803">
                <path id="패스_2697" data-name="패스 2697"
                    d="M643.24,186.321a6.466,6.466,0,0,1-4.5-1.606,5.652,5.652,0,0,1,0-8.242,6.558,6.558,0,0,1,4.5-1.57,6.429,6.429,0,0,1,4.46,1.57,5.732,5.732,0,0,1,0,8.242A6.339,6.339,0,0,1,643.24,186.321Zm-5.281,6.423h10.419v39.963H637.959Z"
                    transform="translate(-636.96 -174.903)" fill="#fff" />
            </svg>
            <svg class="logo--text logo--text--04" xmlns="http://www.w3.org/2000/svg" width="38.036" height="52.808"
                viewBox="0 0 38.036 52.808">
                <path id="패스_2698" data-name="패스 2698"
                    d="M720.216,192.564h17.341a38.383,38.383,0,0,1,8.135.821,18.171,18.171,0,0,1,6.565,2.748,13.484,13.484,0,0,1,4.389,5.1,17.375,17.375,0,0,1,1.606,7.885,17.763,17.763,0,0,1-1.606,7.814,15.191,15.191,0,0,1-4.353,5.459,18.427,18.427,0,0,1-6.458,3.176,29.631,29.631,0,0,1-7.992,1.035h-7.065v18.768H720.216Zm16.913,25.69q10.774,0,10.776-9.135,0-4.638-2.819-6.423t-8.314-1.784h-5.994v17.341Z"
                    transform="translate(-720.216 -192.564)" fill="#fff" />
            </svg>
            <svg class="logo--text logo--text--05" xmlns="http://www.w3.org/2000/svg" width="12.488" height="57.803"
                viewBox="0 0 12.488 57.803">
                <path id="패스_2697" data-name="패스 2697"
                    d="M643.24,186.321a6.466,6.466,0,0,1-4.5-1.606,5.652,5.652,0,0,1,0-8.242,6.558,6.558,0,0,1,4.5-1.57,6.429,6.429,0,0,1,4.46,1.57,5.732,5.732,0,0,1,0,8.242A6.339,6.339,0,0,1,643.24,186.321Zm-5.281,6.423h10.419v39.963H637.959Z"
                    transform="translate(-636.96 -174.903)" fill="#fff" />
            </svg>
            <svg class="logo--text logo--text--06" xmlns="http://www.w3.org/2000/svg" width="32.541" height="41.961"
                viewBox="0 0 32.541 41.961">
                <path id="패스_2700" data-name="패스 2700"
                    d="M972.817,276.406a20.562,20.562,0,0,1-7.707-1.427,17.641,17.641,0,0,1-6.173-4.1,19.126,19.126,0,0,1-4.1-6.565,24.369,24.369,0,0,1-1.5-8.813,22.508,22.508,0,0,1,1.677-8.92,19.825,19.825,0,0,1,4.46-6.6,19.217,19.217,0,0,1,6.494-4.1,21.485,21.485,0,0,1,7.779-1.428,15.853,15.853,0,0,1,6.565,1.285,21.7,21.7,0,0,1,5.067,3.14l-5,6.78a13.059,13.059,0,0,0-2.961-1.963,7.47,7.47,0,0,0-3.176-.678,9.143,9.143,0,0,0-7.386,3.39q-2.82,3.39-2.819,9.1,0,5.638,2.783,8.992a8.855,8.855,0,0,0,7.136,3.354,10.038,10.038,0,0,0,4.139-.892,14.559,14.559,0,0,0,3.64-2.319l4.139,6.922a18.709,18.709,0,0,1-6.28,3.675A20.763,20.763,0,0,1,972.817,276.406Z"
                    transform="translate(-953.335 -234.445)" fill="#fff" />
            </svg>
            <svg class="logo--text logo--text--07" xmlns="http://www.w3.org/2000/svg" width="37.037" height="56.947"
                viewBox="0 0 37.037 56.947">
                <path id="패스_2701" data-name="패스 2701"
                    d="M1095.126,177.93h10.2v34.254h.285l13.844-17.269h11.418l-13.844,16.342,15.129,23.621h-11.347l-9.705-16.556-5.78,6.565v9.991h-10.2Z"
                    transform="translate(-1095.126 -177.93)" fill="#fff" />
            </svg>
            <svg class="logo--plane" xmlns="http://www.w3.org/2000/svg" width="46.765" height="33.512"
                viewBox="0 0 46.765 33.512">
                <path id="패스_2735" data-name="패스 2735"
                    d="M2894.519,4019.75a.818.818,0,0,0-.4,1.071l1.839,3.948a.816.816,0,0,0,1.079.4l13.894-6.49-1.258,13.158a.816.816,0,0,0,.269.687,3.566,3.566,0,0,0,2.158.817,2.626,2.626,0,0,0,.645-.082c1.128-.294,2-1.3,2.6-3.016l5.468-16.19,10.828-5.051c3.188-1.487,4.83-4.5,3.727-6.857a4.138,4.138,0,0,0-3.2-2.231,7.574,7.574,0,0,0-4.463.687l-12.5,5.86-13.436-5.256c-1.692-.637-3.032-.621-3.972.058a3.331,3.331,0,0,0-1.218,2.639.817.817,0,0,0,.351.646l9.047,6.219-5.181,2.452-8.688-2.207a.817.817,0,0,0-.907.392l-2.166,3.9a.817.817,0,0,0,.425,1.152l6.432,2.656Zm-3.613-4.454"
                    transform="translate(-2888.944 -3999.83)" fill="#fff" />
            </svg>

        </div>

    </div>

    <div class="wrapper--login--inner">
        <input type="hidden" id="push_token">
        <fieldset class="wrapper--login__con">

            <div class="label-effect-group label-effect-group--mb">
                <input type="email" id="login_email" class="label-effect-group__input">
                <label for="login_email" class="label-effect-group__label">이메일 아이디</label>
            </div>

            <div class="label-effect-group">
                <input type="password" id="login_pw" class="label-effect-group__input">
                <label for="login_pw" class="label-effect-group__label">비밀번호</label>
            </div>

            <span class="inline inline--right">
                <a href="/findpw" class="inline__a--accent">비밀번호를 잃어버리셨나요?</a>
            </span>

            <button type="button" id="btn-login" class="button button--main-color">로그인</button>

            <span class="inline inline--center">
                <span class="inline__span">아이디가 아직 없으신가요?</span>
                <a href="/register/agree" class="inline__a--accent">회원가입 바로 가기</a>
            </span>

            <div class="social_login_group">
                <span>SNS LOGIN</span>
                <div id="naverIdLogin" class="social_btn naver">
                    <a id="naverIdLogin_loginButton" href="#"></a>
                </div>
                <div id="kakaoIdLogin" class="social_btn kakao">
                    <a id="custom-login-btn"
                        href="https://kauth.kakao.com/oauth/authorize?client_id=afaf351438a4afb4a66614af56a36dba&redirect_uri=https%3A%2F%2Fxn--oy2b117blyb.com%2Fregister%2Fkakaologin&response_type=code"></a>
                </div>
            </div>

        </fieldset>
        <div class="ft-info">
            <ul class="ft-info__group">
                <li class="ft-info__list">
                    <span class="ft-info__list__label">사업자명</span>
                    <p class="ft-info__list__txt">(주) 아제타<b class="ft-info__list__border">|</b></p>
                    <span class="ft-info__list__label">사업자등록번호</span>
                    <p class="ft-info__list__txt">685-81-01416</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">통신판매업 신고번호</span>
                    <p class="ft-info__list__txt">2019-서울강남-04282</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">대표자명</span>
                    <p class="ft-info__list__txt">정병욱<b class="ft-info__list__border">|</b></p>
                    <span class="ft-info__list__label">이메일</span>
                    <p class="ft-info__list__txt">tripick@naver.com</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">고객센터 전화번호</span>
                    <p class="ft-info__list__txt">02-567-1336</p>
                </li>
                <li class="ft-info__list">
                    <span class="ft-info__list__label">주소</span>
                    <p class="ft-info__list__txt">서울시 강남구 테헤란로 25길 6-9, 4층 404호(석암빌딩)</p>
                </li>
            </ul>
            <br>
            <ol class="ft-info__group ft-info__group--02">
                <li class="ft-info__list--02" onclick="location.href='/terms01';">tripick 이용약관<b
                        class="ft-info__list__border">|</b></li>
                <li class="ft-info__list--02" onclick="location.href='/terms01';">개인정보 수집 및 이용약관</li>
            </ol>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8">
</script>
<script>
    $(function () {
        var mobile_kind = getMobileOperatingSystem();
        if (mobile_kind == "Android") {
            if (typeof window.JS != 'undefined') {
                window.JS.CallPushToken();
            }
        } else if (mobile_kind == "iOS") {
            // IOS function call
            if(typeof webkit !== 'undefined'){
				webkit.messageHandlers.CallPushToken.postMessage("push_token"); 
			}
        }

        var naverLogin = new naver.LoginWithNaverId({
            clientId: "NP1uNTROBqcXEWyUVAB_",
            callbackUrl: "{{ env('APP_URL') }}/register/naverlogin",
            isPopup: false,
            callbackHandle: true
        });

        /* 설정정보를 초기화하고 연동을 준비 */
        naverLogin.init();

        //login input 효과
        $(".label-effect-group__input").focus(function () {
            $(this)
                .parents(".label-effect-group")
                .addClass("focused");
        });

        $(".label-effect-group__input").blur(function () {
            var thisValue = $(this).val();

            if (thisValue == "") {
                $(this).removeClass("filled");
                $(this)
                    .parents(".label-effect-group")
                    .removeClass("focused");
            } else {
                $(this).addClass("filled");
            }
        });
        //end

        $('#btn-login').click(function () {
            var param = {
                'email': $('#login_email').val(),
                'password': $('#login_pw').val(),
                'push_token': $('#push_token').val()
            };
            $.ajax({
                method: "POST",
                data: param,
                dataType: 'json',
                url: 'api/login',
                success: function (data) {
                    if (data.state == 1 && data.user_state != 2) {
                        console.log(data);
                        location.href = '/af_home';
                    } else if (data.state == 1 && data.user_state == 2) {
                        location.href = '/contact_verify';
                    } else {
                        dialog.alert({
                            title: '로그인',
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

        $('#login_email').val(readCookie('user_id'));   
        $('#login_pw').val(readCookie('user_pwd'));        

    });

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    /**
     * @param name  키
     * @param value 값
     * @param days 날짜
     */
    function writeCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function deleteCookie(name) {
        createCookie(name, "", -1);
    }

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

    function loginWithKakao() {
        var params = {
            client_id: "afaf351438a4afb4a66614af56a36dba",
            redirect_uri: "{{ env('APP_URL') }}/register/kakaologin",
            response_type: "code"
        };
        $.ajax({
            type: "GET",
            data: params,
            dataType: 'json',
            url: 'https://kauth.kakao.com/oauth/authorize',
            success: function (data) {
                console.log(data);
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

  


</script>
@endsection