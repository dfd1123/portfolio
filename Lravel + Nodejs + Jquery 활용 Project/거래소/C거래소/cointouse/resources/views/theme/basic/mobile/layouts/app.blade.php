<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta property="og:title" content="cointouse">
    <meta property="og:description" content="trademarket-COINTOUSE">
    <meta property="og:image" content="{{ asset('/storage/image/homepage/favicon/cointouse_thumnail.svg') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Locale -->
    <meta name="locale" content="{{ config('app.country') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('/js/vendor/vendor.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://cdn.rawgit.com/theeluwin/NotoSansKR-Hestia/master/stylesheets/NotoSansKR-Hestia.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-6jHF7Z3XI3fF4XZixAuSu0gGKrXwoX/w3uFPxC56OtjChio7wtTGJWRW53Nhx6Ev" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,500,700|Noto+Sans+SC:400,500,700" rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/storage/image/homepage/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/storage/image/homepage/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/storage/image/homepage/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/storage/image/homepage/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('/storage/image/homepage/favicon/safari-pinned-tab.svg') }}" color="#ffdc00">
    <meta name="msapplication-TileColor" content="#23232c">
    <meta name="theme-color" content="#ffffff">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/lib.css') }}" rel="stylesheet">
    <link href="{{ asset('css/'.$path.'/m_basic_market.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/'.$path.'/'.config('app.country').'.css') }}" rel="stylesheet" >

    <script src="{{ asset('vendor/decimal/decimal.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    

    @if($pagename[1] == 'market')
			<script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/charting_library/charting_library.min.js') }}"></script>
			<script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/datafeeds/udf/dist/polyfills.js') }}"></script>
			<script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/datafeeds/udf/dist/bundle.js') }}"></script>
            <script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/trading_view.js') }}"></script>
			<!--<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>-->
    @elseif(strpos($pagename[1],'market_') !== false)
        <script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/charting_library/charting_library.min.js') }}"></script>
        <script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/datafeeds/udf/dist/polyfills.js') }}"></script>
        <script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/datafeeds/udf/dist/bundle.js') }}"></script>
        <script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/trading_view.js') }}"></script>
    @endif
        
</head>
<body ontouchstart="">
    
    {{-- loading --}}
    <div class="posi_wrap">
        <div>
            <div id="loading"></div>
        </div>
    </div>
    {{-- loading --}}
    
    @include(session('theme').'.'.$device.'.'.'layouts.header')
    
    <div id="m_wrapper"  {{($pagename[1] == 'login' || $pagename[1] == 'otp')?'class=back_bg':''}}>
        <div id="m_main_container" class="{{request()->is('register*')? 'active' : '' }}" >
        
        @yield('content')
        
        </div>
    </div>
    
    @include(session('theme').'.'.$device.'.'.'layouts.footer')
    
    <script src="{{ asset('/js/'.$common_path.'/market.js') }}"></script>
    <script src="{{ asset('/js/'.$path.'/basic.js') }}"></script>
    @if($pagename[1] == 'market')
        <script src="{{ asset('/js/'.$path.'/market.js') }}"></script>
    @elseif(strpos($pagename[1],'market_') !== false)
        <script src="{{ asset('/js/'.$path.'/market_btc.js') }}"></script>
    @elseif($pagename[1] == '')
        <script type="text/javascript" src="{{ asset('/js/'.$path.'/home_new.js') }}"></script>
    @endif
    <!-- <script src="{{ asset('/js/market.js?'.time()) }}"></script> -->
    <script>
        if (typeof __ === 'undefined') { var __ = {}; }

        __.message = {
            @foreach(__('message') as $key => $value)
                '{{$key}}':'{{$value}}',
            @endforeach
        };
        
        @if(session()->has('jsAlert'))

        swal({ text: "{{ session()->get('jsAlert') }}",
            icon: "warning",
            button: '{{ __('message.ok') }}',
        });

        @endif

        @if(session()->has('jsCheck'))

        swal({
            text: "{{ session()->get('jsCheck') }}",
            icon: "success",
            button: '{{ __('message.ok') }}',
        });

        @endif

        @if(session()->has('jsError'))

        swal({
            text: "{{ session()->get('jsError') }}",
            icon: "error",
            button: '{{ __('message.ok') }}',
        });

        @endif
    </script>
</body>
</html>
