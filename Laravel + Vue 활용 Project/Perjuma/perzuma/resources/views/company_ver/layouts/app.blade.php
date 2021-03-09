<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>{{ config('app.name', 'Laravel') }}</title>

    <!--<link rel="stylesheet" href="{{asset('/css/fonts/spoqahans.css')}}">-->
    <link href='//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css' rel='stylesheet' type='text/css'>
    <link href='//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-jp.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{asset('/css/vendor/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/vendor/animate.css')}}">
    <link rel="stylesheet" href="{{asset('/css/vendor/sweetalert.css')}}">
    <link rel="stylesheet" href="{{asset('/css/vendor/datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/vendor/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('/css/company_ver/common.css')}}">
    @if($pagename[1] == 'login' || $pagename[1] == 'register')
        <link rel="stylesheet" href="{{asset('/css/'.$pagename[1].'.css?'.time())}}" />
    @else
        <link rel="stylesheet" href="{{asset('/css/company_ver/'.$pagename[2].'.css?'.time())}}" />
    @endif
    
    <script src="{{asset('/js/user_ver/vendor.js')}}"></script>
    <script src="https://malsup.github.io/jquery.form.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/f4024287d7.js"></script>
    <script src="{{asset('/js/common.js')}}"></script>
    <script src="{{asset('/js/company_ver/common.js')}}"></script>
    
        
</head>
<body ontouchstart="">
    
    <div id="app">
        @if(in_array($pagename[2], $main_hd))
            @include('company_ver.layouts.main_header')<!--긴 파란테마 : X / title / 채팅-->
        @elseif($pagename[2] == 'detail')
            @include('company_ver.layouts.main6_header')<!--파란테마 : X / title + subtitle / 채팅-->
        @elseif($pagename[2] == 'company_login')
            @include('company_ver.layouts.main2_header')<!--흰테마 : X / title / X-->
        @elseif($pagename[2] == 'company_construction')
            @include('company_ver.layouts.main3_header')<!--파란테마 : 뒤로가기 / title + subtitle / 채팅-->
        @elseif($pagename[2] == 'company_const_manage' 
        || $pagename[2] == 'company_bidding_detail'
        || $pagename[2] == 'company_bidding_regist')
            @include('company_ver.layouts.main4_header')<!--파란테마 : 뒤로가기 / title / 채팅-->
        @elseif($pagename[2] == 'company_bidding'
        || $pagename[2] == 'company_request')
            @include('company_ver.layouts.main5_header')<!--파란테마 : X / title  / 채팅-->
        @elseif($pagename[2] == 'company_regist')
            @if($pagename[3] == '6')
                @include('company_ver.layouts.sub_header3')<!--파란테마 : X / title / X-->
            @else
                @include('company_ver.layouts.sub_header')<!--파란테마 : 뒤로가기 / title / 닫기-->
            @endif
        @elseif($pagename[2] == 'company_signup')
            @include('company_ver.layouts.sub_header2')<!--흰테마 : X / title / X-->
        @endif
        
        <div id="content">
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
                <h5>퍼주마 매니저 긴급 메세지 도착</h5>
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
            <h5>퍼주마 매니저(긴급 메세지)</h5>
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
                        <div class="swiper-slide">Slide 1</div>
                        <div class="swiper-slide">Slide 2</div>
                        <div class="swiper-slide">Slide 3</div>
                        <div class="swiper-slide">Slide 4</div>
                        <div class="swiper-slide">Slide 5</div>
                        <div class="swiper-slide">Slide 6</div>
                        <div class="swiper-slide">Slide 7</div>
                        <div class="swiper-slide">Slide 8</div>
                        <div class="swiper-slide">Slide 9</div>
                        <div class="swiper-slide">Slide 10</div>
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
    @if($pagename[2] == 'company_myagent')
        @include('company_ver.company_myagent.company_myreview')
    @endif
    <style>
        
        #app{
            position:relative;
        }
    </style>
    
    
</body>
</html>
