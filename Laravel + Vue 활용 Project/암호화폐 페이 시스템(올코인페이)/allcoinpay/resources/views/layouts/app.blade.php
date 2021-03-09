<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <!-- Meta, title, CSS, favicons, etc. -->
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta property="og:title" content="AllCoin Pay">
	    <meta property="og:description" content="언제 어디서나 코인으로 결제를! AllCoin Pay">
	    <meta property="og:image" content="{{ asset('/image/logo/allcoin_pay_thumnail.png') }}">
	
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/image/favicon/apple-touch-icon.png') }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/image/favicon/favicon-32x32.png') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/image/favicon/favicon-16x16.png') }}">
		<link rel="manifest" href="{{ asset('/image/favicon/site.webmanifest') }}">
		<link rel="mask-icon" href="{{ asset('/image/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
		<link rel="shortcut icon" href="{{ asset('/image/favicon/favicon.ico') }} ">
		<meta name="apple-mobile-web-app-title" content="AllCoin Payment">
		<meta name="application-name" content="Allcoin Payment">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-config" content="{{ asset('/image/favicon/browserconfig.xml') }}">
		<meta name="theme-color" content="#ffffff">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
	    <!--<title> EasyCoinPay | </title>  -->
	    <title> 언제 어디서나 코인으로 결제를! Allcoin Pay</title>

	    <link href="{{ asset('/vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
	    <!-- Font Awesome -->
	    <link href="{{ asset('/vendor/font-awesome/css/all.css') }}" rel="stylesheet">
	    <!--link href='https://cdn.rawgit.com/theeluwin/NotoSansKR-Hestia/master/stylesheets/NotoSansKR-Hestia.css' rel='stylesheet' type='text/css'-->
		
	    <!-- NProgress -->
	    <link href="{{ asset('/vendor/nprogress/nprogress.css') }}" rel="stylesheet">
	    <!-- iCheck -->
	    <link href="{{ asset('/vendor/iCheck/skins/flat/green.css') }}" rel="stylesheet">
	
	    <!-- bootstrap-progressbar -->
	    <link href="{{ asset('/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
	    <!-- JQVMap -->
	    <link href="{{ asset('/vendor/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet" />
	    <!-- bootstrap-daterangepicker -->
	    <link href="{{ asset('/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
	    
	    <!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		@if(Request::segment(1) == 'login' || Request::segment(1) == 'register')
		<link href="{{ asset('css/login.css') }}" rel="stylesheet">
		@endif
	</head>
	<body class="nav-md">
		
		@include('layouts.header')
		
		@yield('content')
		
		@include('layouts.footer')
		
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<!-- jQuery -->
	    <script src="{{ asset('/vendor/jquery/dist/jquery.min.js') }}"></script>
	    <!-- Bootstrap -->
	    <script src="{{ asset('/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	    <!-- FastClick -->
	    <script src="{{ asset('/vendor/fastclick/lib/fastclick.js') }}"></script>
	    <!-- NProgress -->
	    <script src="{{ asset('/vendor/nprogress/nprogress.js') }}"></script>
	    <!-- Chart.js -->
	    <script src="{{ asset('/vendor/Chart.js/dist/Chart.min.js') }}"></script>
	    <!-- gauge.js -->
	    <script src="{{ asset('/vendor/gauge.js/dist/gauge.min.js') }}"></script>
	    <!-- bootstrap-progressbar -->
	    <script src="{{ asset('/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
	    <!-- iCheck -->
	    <script src="{{ asset('/vendor/iCheck/icheck.min.js') }}"></script>
	    <!-- Skycons -->
	    <script src="{{ asset('/vendor/skycons/skycons.js') }}"></script>
	    <!-- Flot -->
	    <script src="{{ asset('/vendor/Flot/jquery.flot.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.pie.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.time.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.stack.js') }}"></script>
	    <script src="{{ asset('/vendor/Flot/jquery.flot.resize.js') }}"></script>
	    <!-- Flot plugins -->
	    <script src="{{ asset('/vendor/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
	    <script src="{{ asset('/vendor/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
	    <script src="{{ asset('/vendor/flot.curvedlines/curvedLines.js') }}"></script>
	    <!-- DateJS -->
	    <script src="{{ asset('/vendor/DateJS/build/date.js') }}"></script>
	    <!-- JQVMap -->
	    <script src="{{ asset('/vendor/jqvmap/dist/jquery.vmap.js') }}"></script>
	    <script src="{{ asset('/vendor/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
	    <script src="{{ asset('/vendor/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
	    <!-- bootstrap-daterangepicker -->
	    <script src="{{ asset('/vendor/moment/min/moment.min.js') }}"></script>
	    <script src="{{ asset('/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
		<script src="{{ asset('/vendor/clipboard/clipboard.min.js') }}"></script>
		<script src="{{ asset('/js/app.js') }}"></script>
		
		@yield('script')
		<script>
			@if(session()->has('jsAlert'))
				alert("{{ session()->get('jsAlert') }}");
			@endif
		</script>
	</body>
</html>
