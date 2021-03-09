<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1, minimum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open graph -->
    <meta property="og:url" content="#" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Tripick" />
    <meta property="og:description" content="세상에서 나를 가장 잘 아는 여행" />
    <meta property="og:image" content="{{ asset('img/favicon/meta_thumnail.png') }}" />
    <meta name="keyword" content="자유여행, 패키지여행, 맞춤여행">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <title>{{ config('app.name', 'TriPick') }}</title>

    <!-- Fonts -->
    <link href='https://spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css' rel='stylesheet' type='text/css'>

    <!-- plugin css -->
    <link rel="stylesheet" href="/vendor/scss/venders/normalize-custom.css">
    <link rel="stylesheet" href="/vendor/scss/venders/jquery.dialog.css">
    <link rel="stylesheet" href="/vendor/scss/venders/datepicker.css">
    <link rel="stylesheet" href="/vendor/slick/slick.css">
    <link rel="stylesheet" href="/vendor/slick/slick-theme.css">
    <link rel="stylesheet" href="/vendor/scss/venders/swiper.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="/vendor/scss/layout/common.css">
    <link rel="stylesheet" href="/vendor/scss/layout/popup.css">

    <!-- 개별 css -->
    <link rel="stylesheet" href="/vendor/scss/pages/{{ $page_css }}.css">

    <!-- plugin js -->
    <script src="/vendor/js/jquery-3.4.1.min.js"></script>
    <script src="/vendor/js/jquery.form.js" type="text/javascript"></script>
    <script src="/vendor/js/jquery.dialog.js"></script>
    <script src="/vendor/js/datepicker.js"></script>
    <script src="/vendor/js/datepicker.en.js"></script>
    <script src="/vendor/slick/slick.js"></script>
    <script src="/vendor/js/swiper.min.js"></script>
    <script src="/vendor/js/common.js"></script>

    <!-- 모달팝업 js -->
    <script src="/vendor/js/modal-popup.js"></script>

    <!-- 기타팝업 js -->
    <script src="/vendor/js/etc-popup.js"></script>

</head>
<body ontouchstart>
    <div id="app">
        @yield('content')
    </div>

    @yield('script')
    
    <script>
        $(function() {
        @if(session()->has('jsAlert'))
            dialog.alert({
                title:'알림',  
                message: '{{ session()->get('jsAlert') }}',
                button: "확인"
            });
        @endif
        @if(session()->has('jsClose'))
            dialog.alert({
                title:'알림',  
                message: '{{ session()->get('jsClose') }}',
                button: "확인",
                callback: function(value){
                    window.close();
                }
            });
            
        @endif
        });
        function refresh_token(){
            $.ajax({
                method: "POST",
                data: param,
                dataType: 'json',
                url: '/api/refresh',
                success: function(data) {
                    if(data.query.length > 0){
                        console.log(data);
                        location.href='/be_home';
                    }
                    else{
                        dialog.alert({
                            title:'알림',  
                            message: '로그인이 필요한 서비스입니다.',
                            button: "확인",
                            callback: function(value){
                                location.href='/login';
                            }
                        });
                    }

                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
    </script>
</body>
</html>
