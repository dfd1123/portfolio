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
    <link rel="stylesheet" href="{{asset('/css/user_ver/common.css')}}">
    <link rel="stylesheet" href="{{asset('/css/'.$pagename[1].'.css?'.time())}}" />
    
    <script src="{{asset('/js/user_ver/vendor.js')}}"></script>
    <script src="https://malsup.github.io/jquery.form.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/f4024287d7.js"></script>
    <script src="{{asset('/js/common.js')}}"></script>
    <script src="{{asset('/js/company_ver/common.js')}}"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    
        
</head>
<body ontouchstart="">
    
    <div id="app">
        @include('auth.passwords.header')
        
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
    <style>
        
        #app{
            position:relative;
        }
        #content{
            padding:4em 0 !important;
        }
    </style>
    
    
</body>
</html>
