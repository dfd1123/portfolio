<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-KN3GZCT');</script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta property="og:title" content="스포와이드(SPOWIDE)">
		<meta property="og:description" content="최첨단 웹 그래픽 기반의 암호화폐 거래소. 팬클럽코인, 스포츠코인, 축구코인, 암호화폐, 가상화폐, 비트코인, 이더리움, 리플, 블록체인">
		<meta property="og:image" content="{{ asset('/storage/image/homepage/favicon/meta_thumnail.jpg') }}">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Locale -->
		<meta name="locale" content="{{ config('app.country') }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Scripts -->
		<script src="{{ asset('/js/vendor/vendor.js') }}"></script>

		<!-- Fonts -->
		<!--
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
		<link href="https://cdn.rawgit.com/theeluwin/NotoSansKR-Hestia/master/stylesheets/NotoSansKR-Hestia.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-6jHF7Z3XI3fF4XZixAuSu0gGKrXwoX/w3uFPxC56OtjChio7wtTGJWRW53Nhx6Ev" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,500,700|Noto+Sans+SC:400,500,700" rel="stylesheet">
        -->


		<!-- Favicon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/storage/image/homepage/favicon/apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/storage/image/homepage/favicon/favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/storage/image/homepage/favicon/favicon-16x16.png') }}">
		<link rel="manifest" href="{{ asset('/storage/image/homepage/favicon/manifest.json') }}">
		<link rel="mask-icon" href="{{ asset('/storage/image/homepage/favicon/safari-pinned-tab.svg') }}" color="#0a92df">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#ffffff">

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/vendor/lib.css') }}" rel="stylesheet">
		<link href="{{ asset('css/'.$path.'/basic_market.css') }}" rel="stylesheet" >
		<link href="{{ asset('css/'.$path.'/'.config('app.country').'.css') }}" rel="stylesheet" >
		<link rel="stylesheet" type="text/css" href="{{asset('/css/pc/spowide.css')}}">
		@if($pagename[1] == '' || $pagename[1] == 'home')
			<link rel="stylesheet" type="text/css" href="{{asset('/css/pc/main.css')}}"></link>
		@elseif(strpos($pagename[1],'market_') !== false)
			<link rel="stylesheet" type="text/css" href="{{asset('/css/pc/market.css')}}"></link>
		@else
			<link rel="stylesheet" type="text/css" href="{{asset('/css/pc/'.$pagename[1].'.css')}}"></link>
		@endif

		<script src="https://kit.fontawesome.com/a1eec22bfc.js"></script>
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
	<body>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KN3GZCT"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
				
		@if(count($popups) > 0)
			@include(session('theme').'.'.$device.'.'.'layouts.popup')
		@endif

		@include(session('theme').'.'.$device.'.'.'layouts.header')

		<div id="wrapper" {{($pagename[1] == 'login' || $pagename[1] == 'otp')?'class=back_bg':''}}>
			<div id="container">
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
			<script data-cfasync = "false" type="text/javascript" src="{{ asset('vendor/tradingview_new/trading_view.js') }}"></script>
		@elseif($pagename[1] == '')
			<script type="text/javascript" src="{{ asset('/js/'.$path.'/home.js') }}"></script>
		@endif
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

			@if(session()->has('comunity_reject'))

				swal({ text: "{{ session()->get('comunity_reject') }}",
					icon: "warning",
					button: '{{ __('message.ok') }}',
				});

			@endif
		</script>

	</body>

</html>
