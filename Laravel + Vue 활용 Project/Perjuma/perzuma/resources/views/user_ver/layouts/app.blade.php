<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Style-Type" content="text/css">
    @if(in_array($pagename[2], $main_hd))
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">
    @else
        <meta name="msapplication-TileColor" content="#007bd2">
        <meta name="theme-color" content="#007bd2">
    @endif
	<title>{{ config('app.name', 'Laravel') }}</title>

    <!--<link rel="stylesheet" href="{{asset('/css/fonts/spoqahans.css')}}">-->
    <link href='//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css' rel='stylesheet' type='text/css'>
    <link href='//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-jp.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{asset('/css/user_ver/vendor.css')}}">
    <link rel="stylesheet" href="{{asset('/css/user_ver/common.css')}}">
    <link rel="stylesheet" href="{{asset('/css/user_ver/header.css?'.time())}}" />
    @if($pagename[1] == 'login' || $pagename[1] == 'register')
        <link rel="stylesheet" href="{{asset('/css/'.$pagename[1].'.css?'.time())}}" />
    @elseif(in_array($pagename[2], $main_hd))
        <link rel="stylesheet" href="{{asset('/css/user_ver/main.css?'.time())}}" />
    @else
        <link rel="stylesheet" href="{{asset('/css/user_ver/'.$pagename[2].'.css?'.time())}}" />
    @endif
    
    <script src="{{asset('/js/user_ver/vendor.js')}}"></script>
    <script src="https://kit.fontawesome.com/f4024287d7.js"></script>
    <script src="{{asset('/js/common.js')}}"></script>
    <script src="{{asset('/js/user_ver/common.js')}}"></script>
        
</head>
<body ontouchstart="">
    <div id="loading_wrap">
        <div class="loading_content">
            <div class="lds-ripple"><div></div><div></div></div>
        </div>
    </div>
    <div id="app">
        @if(in_array($pagename[2], $main_hd) || $pagename[1] == 'login')
            @include('user_ver.layouts.main_header')
        @elseif($pagename[2] == 'estimate_request' || $pagename[2] == 'result_confirm' || $pagename[1] == 'register')
            @include('user_ver.layouts.sub_header')
        @elseif($pagename[2] == 'request_complete' || $pagename[2] == 'estimate_manage' )
            @include('user_ver.layouts.sub_header2')
        @elseif($pagename[2] == 'company_page')
            @include('user_ver.layouts.sub_header3')
        @elseif($pagename[2] == 'construct_status' || $pagename[2] == 'ask_estimate')
            @include('user_ver.layouts.sub_header4')
        @elseif($pagename[2] == 'corporation_status')
            @include('user_ver.layouts.sub_header5')
        @endif
        <div id="menu_wrap">
            <div class="menu_overlay"></div>
            <div class="menu_content">
                <ul>
                    <li>
                        <a  anim="ripple">이용약관</a>
                    </li>
                    <li>
                        <a anim="ripple">고객센터</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" onclick="unregist();" anim="ripple">회원탈퇴</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" onclick="logout();" anim="ripple">로그아웃</a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="content" class="slideInRight animated faster">
            @yield('content')
        </div>
    </div>
    <div class="alarm_wrap" style="display:none;">
        <div class="overlay"></div>
        <div class="alarm_content">
            <div class="alarm_box">
                <div>
                    <div class="alarm_hd">
                        <h5>알람</h5>
                    </div>
                    <div id="alarm_body" class="alarm_body"  data-offset="" data-count="">
                        <div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="message_wrap">
        <div id="message_box">
            
        </div>
    </div>

    <template id="alarm_li">
        <div class="alarm_li" data-id="1">
            <div class="alarm_li_left">
                <div class="icon_wrap"></div>
                <h5></h5>
                <span></span>
                <p></p>
            </div>
            <div class="alarm_li_right">
                <button type="button"  data-id="1">확인하기</button>
            </div>
        </div>
    </template>

    <template id="message_content">
        <div class="message_hd">
            <h5></h5>
        </div>
        <div class="message_body">
            <div class="message_text_wrap">
                <div class="message_icon">
                    <img src="{{asset('/images/icon_emergency.svg')}}" class="new_emergency" style="display:none;" alt="">
                    <img src="{{asset('/images/icon_alarm_new.svg')}}" class="new_alarm" style="display:none;" alt="">
                </div>
                <h3 id="msg_title"></h3>
                <span id="msg_send_dt"></span>
                <p id="msg_content"></p>
            </div>
            <div class="message_slider_wrap" style="background:#ddd;">
                <div id="message_slider" class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                        <div class="swiper-slide"></div>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-paginate" style="position: absolute;z-index:10;"></div>
                </div>
            </div>
        </div>
        <div class="message_ft">
            <button type="button" >확인</button>
        </div>
    </template>
    
    <style>
        
        #app{
            position:relative;
        }
    </style>
    <script>
        @if(session('verified'))
            swal({
                title: "인증 성공",
                text: "이메일 인증을 성공하셨습니다.\n지금부터 시공 견적을 요청할 수 있습니다.",
                button: "확인",
            });
        @endif
    </script>
    
    
</body>
</html>
