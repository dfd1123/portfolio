<!DOCTYPE html>
<html lang="{{ $lang }}">

<head>
    <title>J1BEATZ</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="제이원비츠">
    <meta name="viewport" content="user-scalable=yes">

    <!-- Open graph -->
    <meta property="og:url" content="{{ $base_url }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="제이원비츠" />
    <meta property="og:description" content="음악플랫폼, 제이원비츠" />
    <meta property="og:image" content="{{ asset('img/meta_image.png') }}" />
    <meta name="keyword" content="음악플랫폼, 제이원비츠">
    <meta name="format-detection" content="telephone=no">

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#16162d">
    <meta name="msapplication-TileColor" content="#16162d">
    <meta name="theme-color" content="#ffffff">

    {{--
    <!-- NICECHECK Token -->
    <meta name="enc_data" content="{{ $enc_data }}">
    --}}

    <!-- font -->
    <link rel="dns-prefetch" href="fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:300,400,500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">

    <!-- vendor styles-->
    <link href="{{ mix('css/normalize-custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/jquery/slick-1.8.0/slick.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/jquery/slick-1.8.0/slick-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/jquery/mCustomScrollbar-3.1.5/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/jquery/jquery-ui-1.12.1/jquery-ui.min.css') }}" rel="stylesheet">

    <!-- vendor scripts-->
    <script src="{{ asset('vendor/jquery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery/mCustomScrollbar-3.1.5/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery/slick-1.8.0/slick.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
    
    <!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '2445755279016873');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=2445755279016873&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
</head>
<body>
    <div id="app" v-cloak>
        <global-loading-indicator-component></global-loading-indicator-component>
        <left-header-component></left-header-component>
        <bottom-playbar-component></bottom-playbar-component>
        <right-playlist-component></right-playlist-component>
        <router-view></router-view>
        <my-album-regist-popup-component></my-album-regist-popup-component>
        <button ref="clipboard" id="clipboard" style="display: none"></button>
    </div>

    <!-- Lang -->
    <script>
        window.__ = JSON.parse(atob('{{$lang_data}}'));
        window.__.VERSION = "0.0.1";
    </script>

    <!-- App -->
    @if (app()->environment('production'))
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    @endif
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
